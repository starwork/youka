<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\Order as OrderModel;
use app\api\model\OrderGoods;
use app\api\model\Wxapp as WxappModel;
use app\common\library\wechat\WxPay;
use app\api\model\Setting as SettingModel;
use app\api\model\Comment as CommentModel;
use app\common\library\delivery\KdNiao;
use app\common\model\Express;

/**
 * 用户订单管理
 * Class Order
 * @package app\api\controller\user
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

    /**
     * 我的订单列表
     * @param $dataType
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists($dataType)
    {
        $model = new OrderModel;
        $list = $model->getList($this->user['user_id'], $dataType);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 下级订单列表
     * @param $dataType
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function ChildLists($dataType)
    {
        $model = new OrderModel;
        $list = $model->getChildList($this->user['user_id'], $dataType);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 订单详情信息
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function detail($order_id)
    {
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        return $this->renderSuccess(['order' => $order]);
    }

    /**
     * 取消订单
     * @param $order_id
     * @return array
     * @throws \Exception
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function cancel($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if ($model->cancel()) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError());
    }

    /**
     * 确认收货
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function receipt($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if ($model->receipt()) {
                $config = SettingModel::get('wechat');
                if(!empty($config['receipt_id']) && !empty($this->user['open_id'])){
                    $wechat = new WeChat($config);
                    $data = [
                        'first' => '亲，您的订单已签收。',
                        'keyword1' => $model['order_no'],
                        'keyword2' => date('Y年m月d日 H:i:s'),
                        'remark' => ''
                    ];
                    $wechat->send($config['receipt_id'],$this->user['open_id'],$data);
                }
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError());
    }

    /**
     * 立即支付
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function pay($order_id)
    {
        // 订单详情
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        // 判断商品状态、库存
        if (!$order->checkGoodsStatusFromOrder($order['goods'])) {
            return $this->renderError($order->getError());
        }
        // 发起微信支付
        //$wxConfig = WxappModel::getWxappCache();
        $config = SettingModel::getItem('wechat');
        $WxPay = new WxPay($config);
        $wxParams = $WxPay->unifiedorder($order['order_no'], $this->user['open_id'], $order['pay_price']);
        return $this->renderSuccess($wxParams);
    }



    /**
     * 订单物流查询
     * @param $order_no
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function express($order_id)
    {
        $order = OrderModel::with('address')-> where('order_id',$order_id)->where('user_id',$this->user['user_id'])->find();
        if($order){
            $config = SettingModel::getItem('store', $this->wxapp_id);
            $delivery = new KdNiao($config);
            $company = Express::find($order['express_id']);
            $result = $delivery->Search($order['express_no'],$company['code']);
            $ren = [];
            if($result['Success']){
                $list = $result['Traces'];
                foreach ($list as $value){
                    if(strpos($value['AcceptStation'],'派件人:') !== false){
                        $arr = explode('派件人:',$value['AcceptStation']);
                        $arr1 = isset($arr[1]) ? explode('派件中',$arr[1]) : '';
                        $ren['name'] = $arr1[0] ?  $arr1[0] : '';
                        $ren['phone'] = $arr1[1] ? str_replace('派件员电话','',$arr1[1]) : '';
                    }
                }
                if($order['delivery_time']){
                    $data = [
                        'AcceptStation' => '已发货',
                        'msg' => '包裹正在等待揽收',
                        'AcceptTime' => date('Y-m-d H:i:s',$order['delivery_time'])
                    ];
                    array_unshift($list,$data);
                }
                if($order['receipt_time']){
                    $data = [
                        'AcceptStation' => '[收货地址]'.implode('',$order['address']['region']).' '.$order['address']['detail'],
                        'AcceptTime' => date('Y-m-d H:i:s',$order['receipt_time'])
                    ];
                    array_push($list,$data);
                }
            }
            $list = array_reverse($list);
            return $this->renderSuccess(compact('list','ren'));
        }
        return $this->renderError('订单不存在');
    }

    public function comment()
    {
        $user = $this->getUser();
        $model = New CommentModel();
        $goods = $this->request->post('goods/a');
        $order_id = $this->request->post('order_id',0);
        foreach ($goods as $good){
            $order_goods = (new OrderGoods())->where('order_id',$order_id)->where('goods_id',$good['goods_id'])->find();
            if($order_goods && !empty($good['content'])){
                $data = [
                    'user_id' => $user['user_id'],
                    'order_id' => $order_id,
                    'goods_id' => $good['goods_id'],
                    'content' => $good['content']
                ];
                $model->save($data);
            }
            (new \app\api\model\Order())->where('order_id',$order_id)->update(['comment_status' => 20]);
        }
        return $this->renderSuccess('提交成功');
    }

}
