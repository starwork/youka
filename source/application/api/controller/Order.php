<?php

namespace app\api\controller;

use app\api\model\Order as OrderModel;
use app\api\model\Setting;
use app\api\model\Wxapp as WxappModel;
use app\api\model\Cart as CartModel;
use app\common\exception\BaseException;
use app\common\library\delivery\KdNiao;
use app\common\library\wechat\WxPay;

/**
 * 订单控制器
 * Class Order
 * @package app\api\controller
 */
class Order extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();   // 用户信息
    }

    public function buyNowBefore($goods_id, $goods_num, $goods_sku_id,$address_id = 0)
    {
        // 商品结算信息
        $model = new OrderModel;
        $address_id == 0 && $address_id = $this->user['address_id'];
        $order = $model->getBuyNow($this->user, $goods_id, $goods_num, $goods_sku_id,$address_id);
        return $this->renderSuccess($order);
    }

    /**
     * goods goods_id-goods_sku_id-goods_num
     * @return array
     */
    public function Submit()
    {
        if(!$goods = $this->getSubmitGoods($this->request->post('goods',''))){
            return $this->renderError('商品不存在');
        }

        $address_id = $this->request->post('address_id',0);
        $model = new OrderModel();
        $order = $model->getOrderList($this->user,$goods,$address_id);
        return $this->renderSuccess($order);
    }

    /**
     *  获取商品数字
     * @param $str
     * @return bool
     */
    private function getSubmitGoods($str)
    {
        $data = explode(',',$str);
        if(!empty($data)){
            $goods = [];
            foreach ($data as $k => $v){
                $arr = explode('-',$v);
                if(count($arr) == 3 && $arr[0] > 0 && $arr[2] > 0){
                    $good = [
                        'goods_id' => $arr[0],
                        'goods_sku_id' => $arr[1],
                        'goods_num' => $arr[2],
                    ];
                    $goods[] = $good;
                }
            }
        }
        if(!empty($goods)){
            return $goods;
        }
        return false;
    }

    public function buy()
    {
        if(!$this->user['subscribe']){
            throw new BaseException(['code' => -10, 'msg' => '关注公众号']);
        }
        if($this->user['phone'] == ''){
            throw new BaseException(['code' => -3, 'msg' => '绑定手机号']);
        }
        $default_code  = config('default_code');
        if($this->user['pid'] == 0 && $default_code != $this->user['invite_code']){
            throw new BaseException(['code' => -2, 'msg' => '设置推荐人']);
        }
        $type = $this->request->post('type','');
        $remark = $this->request->post('remark','');
        if(!$goods = $this->getSubmitGoods($this->request->post('goods',''))){
            return $this->renderError('商品不存在');
        }
        $address_id = $this->request->post('address_id',0);
        $model = new OrderModel();
        $order = $model->getOrderList($this->user,$goods,$address_id);
        if (!$this->request->isPost()) {
            return $this->renderSuccess($order);
        }
        if ($model->hasError()) {
            return $this->renderError($model->getError());
        }
        $order['remark'] = $remark;
        // 创建订单
        if ($model->add($this->user['user_id'], $order)) {
            if($type == 'cart'){
                $cart_model = new CartModel($this->user['user_id']);
                foreach ($goods as $good){
                    $cart_model->delete($good['goods_id'],$good['goods_sku_id']);
                }
            }
            //return $this->renderSuccess('','订单提交成功');
            // 发起微信支付
            return $this->renderSuccess([
                'payment' => $this->wxPay($model['order_no'], $this->user['open_id']
                    , $order['order_pay_price']),
                'order_id' => $model['order_id']
            ]);
        }
        $error = $model->getError() ?: '订单创建失败';

        return $this->renderError($error);
    }

    /**
     * 订单确认-立即购买
     * @param $goods_id
     * @param $goods_num
     * @param $goods_sku_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function buyNow($goods_id, $goods_num, $goods_sku_id,$address_id = 0)
    {
        // 商品结算信息
        $model = new OrderModel;
        $address_id == 0 && $address_id = $this->user['address_id'];
        $order = $model->getBuyNow($this->user, $goods_id, $goods_num,$goods_sku_id,$address_id );
        if (!$this->request->isPost()) {
            return $this->renderSuccess($order);
        }
        if ($model->hasError()) {
            return $this->renderError($model->getError());
        }
        // 创建订单
        if ($model->add($this->user['user_id'], $order)) {
            //return $this->renderSuccess('','订单提交成功');
            // 发起微信支付
            return $this->renderSuccess([
                'payment' => $this->wxPay($model['order_no'], $this->user['open_id']
                    , $order['order_pay_price']),
                'order_id' => $model['order_id']
            ]);
        }
        $error = $model->getError() ?: '订单创建失败';
        return $this->renderError($error);
    }

    public function cartBefore($address_id = 0)
    {
        // 商品结算信息
        $model = new OrderModel;
        $order = $model->getCart($this->user,$address_id);
        return $this->renderSuccess($order);
    }

    public function cart1()
    {
        
    }

    /**
     * 订单确认-购物车结算
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function cart()
    {
        // 商品结算信息
        $model = new OrderModel;
        $order = $model->getCart($this->user,$this->request->post('addres_id'));
        if (!$this->request->isPost()) {
            return $this->renderSuccess($order);
        }
        // 创建订单
        if ($model->add($this->user['user_id'], $order)) {
            // 清空购物车
            $Card = new CartModel($this->user['user_id']);
            $Card->clearAll();
            return $this->renderSuccess('','订单提交成功');
            // 发起微信支付
            return $this->renderSuccess([
                'payment' => $this->wxPay($model['order_no'], $this->user['open_id']
                    , $order['order_pay_price']),
                'order_id' => $model['order_id']
            ]);
        }
        $error = $model->getError() ?: '订单创建失败';
        return $this->renderError($error);
    }

    /**
     * 构建微信支付
     * @param $order_no
     * @param $open_id
     * @param $pay_price
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    private function wxPay($order_no, $open_id, $pay_price)
    {
//        $wxConfig = WxappModel::getWxappCache();
        // 发起微信支付
        //$wxConfig = WxappModel::getWxappCache();
        $config = Setting::getItem('wechat');
        $WxPay = new WxPay($config);
        return $WxPay->unifiedorder($order_no,$open_id, $pay_price);
    }

}
