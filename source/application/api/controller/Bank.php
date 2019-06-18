<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-10 15:35
 */

namespace app\api\controller;

use app\api\model\BankCard as BankCardModel;

class Bank extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    /* @var \app\api\model\Cart $model */
    private $model;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();
        $this->model = new BankCardModel();
    }

    public function bankInfo($card)
    {
        $url = 'https://ccdcapi.alipay.com/validateAndCacheCardInfo.json?_input_charset=utf-8&cardNo='.$card.'&cardBinCheck=true';
        $data = json_decode(curl($url),true);
        if(isset($data['validated']) && $data['validated']){
            $bank_code = $data['bank'];
            $type = $data['cardType'];
            $type_arr = [
                'DC'  => '储蓄卡',
                'CC' =>  '信用卡',
                'SCC' => '准贷记卡',
                'PC' =>  '预付费卡'
            ];
            if($bank = (new \app\api\model\Bank())->where('code',$bank_code)->find()){
                $bank['cardType'] = $type_arr[$type];
                return $this->renderSuccess($bank->toArray());
            }
            return $this->renderError('暂不支持该银行卡');
        }else{
            return $this->renderError('卡号不正确');
        }
        return json_decode($data);
    }

    /**
     * 收货地址列表
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function lists()
    {
        $list = $this->model->getList($this->user['user_id']);
        return $this->renderSuccess([
            'list' => $list,
        ]);
    }

    /**
     * 添加
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function add()
    {
        if ($this->model->add($this->user, $this->request->post())) {
            return $this->renderSuccess([], '添加成功');
        }
        return $this->renderError('添加失败');
    }

    /**
     * 收货地址详情
     * @param $address_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function detail($id)
    {
        $detail = $this->model->detail($this->user,$id);
        return $this->renderSuccess(compact('detail'));
    }

    /**
     * 编辑收货地址
     * @param $address_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function edit()
    {
        $id = $this->request->post('id',0);
        $model = $this->model->detail($this->user,$id);
        if ($model->edit($this->request->post())) {
            return $this->renderSuccess([], '更新成功');
        }
        return $this->renderError('更新失败');
    }

    /**
     * 删除
     * @param $address_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function delete($id)
    {
        $model = $this->model->detail($this->user,$id);
        if(!$model){
            return $this->renderSuccess([], '删除成功');
        }
        if ($model->remove($this->user)) {
            return $this->renderSuccess([], '删除成功');
        }
        return $this->renderError('删除失败');
    }
}