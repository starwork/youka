<?php

namespace app\api\controller;

use app\api\model\Comment;
use app\api\model\Goods as GoodsModel;
use app\api\model\Cart as CartModel;

/**
 * 商品控制器
 * Class Goods
 * @package app\api\controller
 */
class Goods extends Controller
{
    /**
     * 商品列表
     * @param $category_id
     * @param $search
     * @param $sortType
     * @param $sortPrice
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($category_id = 0, $search = '', $sortType = 'all', $sortPrice = false)
    {
        $model = new GoodsModel;
        $list = $model->getList(10, $category_id, $search, $sortType, $sortPrice);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 获取商品详情
     * @param $goods_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function detail($goods_id)
    {
        // 商品详情
        $detail = GoodsModel::detail($goods_id);
        if (!$detail || $detail['goods_status']['value'] != 10) {
            return $this->renderError('很抱歉，商品信息不存在或已下架');
        }
        $detail['stock_num'] = 0;
        foreach ($detail['spec'] as $spec){
            $detail['stock_num'] += $spec['stock_num'];
        }
        // 规格信息
        $specData = $detail['spec_type'] == 20 ? $detail->getManySpecData($detail['spec_rel'], $detail['spec']) : null;
        $commentModel = new Comment();
        $comment = $commentModel->with(['user'=> function($query){
        }])
            ->where('goods_id',$goods_id)
            ->where('status',10)
            ->where('is_delete', '=', 0)
            ->where('parent_id', '=', 0)
            ->order('create_time','desc')
            ->paginate(2);

        $sku = $this->getSuk($specData,$detail['spec'],$detail['image'][0]['file_path']);
        $sku['price'] = !empty($sku['price']) ? $sku['price'] : $detail['spec'][0]['goods_price'];
        $sku['collection_id'] = $detail['spec'][0]['spec_sku_id'];

        $BestList = (new GoodsModel())->getBestList();
        $user = $this->getUser();
        // 购物车商品总数量
        $cart_total_num = (new CartModel($user['user_id']))->getTotalNum();
        return $this->renderSuccess(compact('detail', 'cart_total_num','specData','comment','sku','BestList'));
    }


    private function getSuk($data,$spec,$imgUrl)
    {
        $suk = [];
        $suk['tree'] = [];
        if(isset($data) && count($data) > 0){
            foreach ($data['spec_attr'] as $k => $v){
                $arr['k'] = $v['group_name'];
                foreach ($v['spec_items'] as $kk => $item){
                    $arr['v'][$kk]['id'] = $item['item_id'];
                    $arr['v'][$kk]['name'] = $item['spec_value'];
                    $arr['v'][$kk]['imgUrl'] = $imgUrl;
                }
                $arr['k_s'] = 's'.($k+1);
                $suk['tree'][] = $arr;
            }
            $list = [];
            $min_price = 99999999999;
            foreach ($data['spec_list'] as $key => $value){
                $attrs = explode('_',$value['spec_sku_id']);
                $min_price = $value['form']['goods_price'] < $min_price ? $value['form']['goods_price'] : $min_price;
                $goods = [
                    'id' => $value['spec_sku_id'],
                    'price' => $value['form']['goods_price'],
                    'stock_num' => $value['form']['stock_num'],
                    's1' => isset($attrs[0]) ? $attrs[0] : 0,
                    's2' => isset($attrs[1]) ? $attrs[1] : 0,
                    's3' => isset($attrs[2]) ? $attrs[2] : 0,
                ];
                $list[] = $goods;
            }
            $suk['list'] = $list;
            $suk['price'] = $min_price;
        }else{
            $list[] = [
                'id' => '',
                'price' => $spec[0]['goods_price'],
                'stock_num' => $spec[0]['stock_num'],
                's1' =>  0,
                's2' =>  0,
                's3' =>  0,
            ];
            $suk['list'] = $list;
            $suk['price'] = $spec[0]['goods_price'];
            $suk['none_sku'] = true;
        }
        return $suk;
    }


    public function comment_list($goods_id)
    {
        $commentModel = new Comment();
        $list = $commentModel->getList(0,$goods_id,10);
        return $this->renderSuccess($list);
    }

}
