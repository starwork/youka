<?php

namespace app\store\controller;

use app\common\model\Express;
use app\store\model\Order as OrderModel;
use think\Log;

/**
 * 订单管理
 * Class Order
 * @package app\store\controller
 */
class Order extends Controller
{
    /**
     * 待发货订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function delivery_list()
    {
        return $this->getList('待发货订单列表', [
            'pay_status' => 20,
            'delivery_status' => 10
        ]);
    }

    /**
     * 待收货订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function receipt_list()
    {
        return $this->getList('待收货订单列表', [
            'pay_status' => 20,
            'delivery_status' => 20,
            'receipt_status' => 10
        ]);
    }

    /**
     * 待付款订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function pay_list()
    {
        return $this->getList('待付款订单列表', [
            'pay_status' => 10,
            'order_status' => 10
        ]);
    }

    /**
     * 已完成订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function complete_list()
    {
        return $this->getList('已完成订单列表', ['order_status' => 30]);
    }

    /**
     * 已取消订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function cancel_list()
    {
        return $this->getList('已取消订单列表', ['order_status' => 20]);
    }

    /**
     * 全部订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function all_list()
    {
        return $this->getList('全部订单列表',[]);
    }

    /**
     * 订单列表
     * @param $title
     * @param $filter
     * @return mixed
     * @throws \think\exception\DbException
     */
    private function getList($title, $filter = [])
    {
        $model = new OrderModel;
        $start_time = $this->request->param('start_time');
        $end_time = $this->request->param('end_time');
        $search = $this->request->param('search');
        if($start_time != '' && $end_time != ''){
            $start_time1 = strtotime($start_time);
            $filter['create_time'][] = ['>=',$start_time1];
            $end_time1 = strtotime($end_time);
            $filter['create_time'][] = ['<=',$end_time1];
        }else{
            if($start_time != ''){
                $start_time1 = strtotime($start_time);
                $filter['create_time'] = ['>=',$start_time1];
            }
            if($end_time != ''){
                $end_time1 = strtotime($end_time);
                $filter['create_time'] = ['<=',$end_time1];
            }
        }

        if($search != ''){
            $filter['order_no'] = ['like','%'.$search.'%'];
        }
        $list = $model->getList($filter);
        $s  = $this->action;
        return $this->fetch('index', compact('title','list','start_time','end_time','search','s'));
    }

    public function export()
    {
        $dataType = $this->request->param('dataType');
        $filter = [];
        switch ($dataType){
            case 'all_list':
                break;
            case 'delivery_list':
                $filter = [
                    'pay_status' => 20,
                    'delivery_status' => 10
                ];
                break;
            case 'receipt_list':
                $filter = [
                    'pay_status' => 20,
                    'delivery_status' => 20,
                    'receipt_status' => 10
                ];
                break;
            case 'pay_list':
                $filter = [
                    'pay_status' => 10,
                    'order_status' => 10
                ];
                break;
            case 'complete_list':
                $filter = [
                    'order_status' => 30
                ];
                break;
            case 'cancel_list':
                $filter = [
                    'order_status' => 20
                ];
                break;
        }
        return $this->exportList($filter);
    }

    private function exportList($filter=[])
    {
        $headlist = [
            '订单号',
            '商品名称',
            '商品规格',
            '单价',
            '数量',
            '支付方式',
            '付款金额',
            '运费金额',
            '下单时间',
            '买家',
            '买家留言',
            '收货人姓名',
            '联系电话',
            '收货人地址',
            '物流公司',
            '物流单号',
            '付款状态',
            '付款时间',
            '发货状态',
            '发货时间',
            '订单状态',
            '微信支付交易号',
            '是否已评价'
        ];
        $model = new OrderModel;
        $start_time = $this->request->param('start_time');
        $end_time = $this->request->param('end_time');
        $search = $this->request->param('search');
        if($start_time != '' && $end_time != ''){
            $start_time1 = strtotime($start_time);
            $filter['create_time'][] = ['>=',$start_time1];
            $end_time1 = strtotime($end_time);
            $filter['create_time'][] = ['<=',$end_time1];
        }else{
            if($start_time != ''){
                $start_time1 = strtotime($start_time);
                $filter['create_time'] = ['>=',$start_time1];
            }
            if($end_time != ''){
                $end_time1 = strtotime($end_time);
                $filter['create_time'] = ['<=',$end_time1];
            }
        }

        if($search != ''){
            $filter['order_no'] = ['like','%'.$search.'%'];
        }
        $list = $model->with(['goods.image', 'address', 'user'])
            ->where($filter)
            ->order(['create_time' => 'desc'])->select();
        $data = [];
        foreach ($list as $k => $order){
            if($order['delivery_status']['value'] == 20){
                $express_company = Express::get($order['express_id']);
            }
            foreach ($order['goods'] as $goods){
                $arr[] = $order['order_no'];        //订单号
                $arr[] = $goods['goods_name'];      //商品名称
                $arr[] = $goods['goods_attr'];      //商品规格
                $arr[] = $goods['goods_price'];     //单价
                $arr[] = $goods['total_num'];       //数量
                $arr[] = '微信支付';                //支付方式
                $arr[] = $order['pay_price'];       //付款金额
                $arr[] = $order['express_price'];   //运费金额
                $arr[] = $order['create_time'];     //下单时间
                $arr[] = $order['user']['phone'];   //买家
                $arr[] = $order['remark'];          //买家留言
                $arr[] = $order['address']['name']; //收货人姓名
                $arr[] = $order['address']['phone'];//联系电话
                $arr[] = implode('',$order['address']['region']).$order['address']['detail'];   //收货人地址
                $arr[] = !empty($express_company) ? $express_company['name'] : '';  //物流公司
                $arr[] = $order['express_no'];  //物流单号
                $arr[] = $order['pay_status']['text'];  //付款状态
                $arr[] = !empty($order['pay_time']) ? date('Y-m-d H:i:s',$order['pay_time']) : ''; //付款时间
                $arr[] = $order['delivery_status']['text']; //发货状态
                $arr[] = !empty($order['delivery_time']) ? date('Y-m-d H:i:s',$order['delivery_time']) : '';    //发货时间
                $arr[] = $order['order_status']['text'];
                $arr[] = $order['transaction_id'];
                $arr[] = $order['comment_status'] == 20 ? '是' : '否';
                $data[] = $arr;
                unset($arr);
            }
        }
        csv_export($data,$headlist,'order-'.date('Ymd'));
    }

    /**
     * 订单详情
     * @param $order_id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function detail($order_id)
    {
        $detail = OrderModel::detail($order_id);
        $express_company = Express::order('sort')->field('id,name,code')->select();
        return $this->fetch('detail', compact('detail','express_company'));
    }

    /**
     * 确认发货
     * @param $order_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delivery($order_id)
    {
        $model = OrderModel::detail($order_id);
        if ($model->delivery($this->postData('order'))) {
            return $this->renderSuccess('发货成功');
        }
        $error = $model->getError() ?: '发货失败';
        return $this->renderError($error);
    }

    public function edit_price()
    {

    }

}
