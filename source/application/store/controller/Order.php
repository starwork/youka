<?php

namespace app\store\controller;

use app\common\model\Express;
use app\store\model\Order as OrderModel;

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
    public function delivery_list($start_time = '',$end_time = '',$search = '')
    {
        return $this->getList('待发货订单列表', [
            'pay_status' => 20,
            'delivery_status' => 10
        ],$start_time,$end_time,$search);
    }

    /**
     * 待收货订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function receipt_list($start_time = '',$end_time = '',$search = '')
    {
        return $this->getList('待收货订单列表', [
            'pay_status' => 20,
            'delivery_status' => 20,
            'receipt_status' => 10
        ],$start_time,$end_time,$search);
    }

    /**
     * 待付款订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function pay_list($start_time = '',$end_time = '',$search = '')
    {
        return $this->getList('待付款订单列表', [
            'pay_status' => 10,
            'order_status' => 10
        ],$start_time,$end_time,$search);
    }

    /**
     * 已完成订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function complete_list($start_time = '',$end_time = '',$search = '')
    {
        return $this->getList('已完成订单列表', ['order_status' => 30],$start_time,$end_time,$search);
    }

    /**
     * 已取消订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function cancel_list($start_time = '',$end_time = '',$search = '')
    {
        return $this->getList('已取消订单列表', ['order_status' => 20],$start_time,$end_time,$search);
    }

    /**
     * 全部订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function all_list($start_time = '',$end_time = '',$search = '')
    {
        return $this->getList('全部订单列表',[],$start_time,$end_time,$search);
    }

    /**
     * 订单列表
     * @param $title
     * @param $filter
     * @return mixed
     * @throws \think\exception\DbException
     */
    private function getList($title, $filter = [],$start_time = '',$end_time = '',$search = '')
    {
        $model = new OrderModel;
        if($start_time != ''){
            $start_time = strtotime($start_time);
            $filter['create_time'][] = ['>=',$start_time];
        }
        if($end_time != ''){
            $end_time = strtotime($end_time);
            $filter['create_time'][] = ['<=',$end_time];
        }

        if($search != ''){
            $filter['order_no'] = ['like','%'.$search.'%'];
        }
        $list = $model->getList($filter);
        $s  = $this->action;
        return $this->fetch('index', compact('title','list','start_time','end_time','search','s'));
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

}
