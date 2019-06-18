<?php

namespace app\store\controller;

use app\store\model\Category;
use app\store\model\Delivery;
use app\store\model\Goods as GoodsModel;
use think\Debug;

/**
 * 商品管理控制器
 * Class Goods
 * @package app\store\controller
 */
class Goods extends Controller
{
    /**
     * 商品列表(出售中)
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index($goods_status = 0,$category_id = 0,$goods_name = '')
    {
        $model = new GoodsModel;
        $list = $model->getList($goods_status,$category_id,$goods_name);
        $catgory = Category::getCacheTree();
        return $this->fetch('index', compact('list','catgory','goods_status','category_id','goods_name'));
    }


    public function export($goods_status = 0,$category_id = 0,$goods_name = '')
    {
        // 筛选条件
        $filter = [];
        $category_id > 0 && $filter['category_id'] = $category_id;
        $goods_status > 0 && $filter['goods_status'] = $goods_status;
        !empty($search) && $filter['goods_name'] = ['like', '%' . trim($search) . '%'];

        return $this->exportList($filter);
    }

    private function exportList($filter = [])
    {
        $headlist = [
            'ID',
            '商品名称',
            '商品编码',
            '商品分类',
            '初始销量',
            '实际销量',
            '商品状态',
            '商品规格',
            '商品价格',
            '商品划线价',
            '当前库存数量',
            '商品重量(Kg)'
        ];
        $sort = ['goods_sort', 'goods_id' => 'desc'];
        $model = new GoodsModel();
        $list = $model->with(['category','tags.tags', 'spec', 'spec_rel.spec'])
            ->where('is_delete', '=', 0)
            ->where($filter)
            ->order($sort)
            ->select();
        $data = [];
        foreach ($list as $k => $goods){
            if ($goods['spec_type'] == 20){
                $list[$k]['specData'] = $goods->getManySpecData($goods['spec_rel'], $goods['spec']);

                foreach ($list[$k]['specData']['spec_list'] as $v){
                    $arr = [
                        $goods['goods_id'],
                        $goods['goods_name'],
                        $v['form']['goods_no'],
                        $goods['category']['name'],
                        $goods['sales_initial'],
                        $goods['sales_actual'],
                        $goods['goods_status']['text'],
                        $v['goods_attr'],
                        $v['form']['goods_price'],
                        $v['form']['line_price'],
                        $v['form']['stock_num'],
                        $v['form']['goods_weight'],
                    ];
                    $data[] = $arr;
                }
            }else{
                $arr = [
                    $goods['goods_id'],
                    $goods['goods_name'],
                    $goods['spec'][0]['goods_no'],
                    $goods['category']['name'],
                    $goods['sales_initial'],
                    $goods['sales_actual'],
                    $goods['goods_status']['text'],
                    '',
                    $goods['spec'][0]['goods_price'],
                    $goods['spec'][0]['line_price'],
                    $goods['spec'][0]['stock_num'],
                    $goods['spec'][0]['goods_weight'],
                ];
                $data[] = $arr;
            }
        }
        csv_export($data,$headlist,'goods-'.date('Ymd'));
    }

    /**
     * 添加商品
     * @return array|mixed
     */
    public function add()
    {
        if (!$this->request->isAjax()) {
            // 商品分类
            $catgory = Category::getCacheTree();
            // 配送模板
            $delivery = Delivery::getAll();
            return $this->fetch('add', compact('catgory', 'delivery'));
        }
        $model = new GoodsModel;
        if ($model->add($this->postData('goods'))) {
            return $this->renderSuccess('添加成功', url('goods/index'));
        }
        $error = $model->getError() ?: '添加失败';
        return $this->renderError($error);
    }

    public function state($goods_id,$state)
    {
        $model = GoodsModel::get($goods_id);
        if($state){
            $data = [
                'goods_status' => 10
            ];
        }else{
            $data = [
                'goods_status' => 20
            ];
        }
        $msg = $state ? '上架' : '下架';
        if($model->save($data)){
            return $this->renderSuccess($msg.'成功');
        }else{
            return $this->renderSuccess($msg.'失败');
        }
    }

    public function hot($goods_id,$state)
    {
        $model = GoodsModel::get($goods_id);
        if($state){
            $data = [
                'is_hot' => 1
            ];
        }else{
            $data = [
                'is_hot' => 0
            ];
        }
        if($model->save($data)){
            return $this->renderSuccess('修改成功');
        }else{
            return $this->renderSuccess('修改失败');
        }
    }

    /**
     * 删除商品
     * @param $goods_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($goods_id)
    {
        $model = GoodsModel::get($goods_id);
        if (!$model->remove()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

    /**
     * 商品编辑
     * @param $goods_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function edit($goods_id)
    {
        // 商品详情
        $model = GoodsModel::detail($goods_id);
        if (!$this->request->isAjax()) {
            // 商品分类
            $catgory = Category::getCacheTree();
            // 配送模板
            $delivery = Delivery::getAll();

            //tags
            $tags = $model['tags']->toArray();
            $tags_name = is_array($tags) ? implode(',',array_column($tags,'tags_name')) : '';
            $tags_ids =  is_array($tags) ? implode(',',array_column($tags,'tags_id')) : '';
            // 多规格信息
            $specData = 'null';
            if ($model['spec_type'] == 20)
                $specData = json_encode($model->getManySpecData($model['spec_rel'], $model['spec']));
            return $this->fetch('edit', compact('model', 'catgory', 'delivery', 'specData','tags_name','tags_ids'));
        }
        // 更新记录
        if ($model->edit($this->postData('goods'))) {
            return $this->renderSuccess('更新成功', url('goods/index'));
        }
        $error = $model->getError() ?: '更新失败';
        return $this->renderError($error);
    }


}
