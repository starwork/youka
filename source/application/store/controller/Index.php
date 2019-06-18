<?php
namespace app\store\controller;

use app\common\library\Time;
use app\store\model\Order as OrderModel;
use app\store\model\Goods as GoodsModel;
use app\store\model\User as UserModel;

/**
 * 后台首页
 * Class Index
 * @package app\store\controller
 */
class Index extends Controller
{
    public function index()
    {
        $today = Time::Today();
        $yesterday_time = Time::YesterDay();
        $week_time = Time::Week();
        $last_week_time = Time::LastWeek();
        $month_time = Time::Month();
        $last_month_time = Time::LastMonth();
        $order_model = new OrderModel();
        $goods_model = new GoodsModel();
        $user_model = new UserModel();
        $total_data = [
            'price_total' => $order_model->whereBetween('pay_time',$today)->sum('pay_price'),
            'order_total' => $order_model->whereBetween('create_time',$today)->count(),
            'goods_total' => $goods_model->where('is_delete',0)->count(),
            'user_total' => $user_model->count(),
        ];

        $goods_data = [
            'down' => $goods_model->getCount(['goods_status' => 20]),
            'up' => $goods_model->getCount(['goods_status' => 10]),
            'stock' => $goods_model->getStock(50)->count(),
            'count' => $goods_model->getCount([]),
        ];
        $user_data = [
            'today' => $user_model->whereBetween('create_time',$today)->count(),
            'yesterday' => $user_model->whereBetween('create_time',$yesterday_time)->count(),
            'month' => $user_model->whereBetween('create_time',$month_time)->count(),
            'all' => $user_model->count(),
        ];
        $month_order_count = $order_model->whereBetween('create_time',$month_time)->count();
        $month_last_order_count = $order_model->whereBetween('create_time',$last_month_time)->count();

        $week_order_count = $order_model->whereBetween('create_time',$week_time)->count();
        $week_last_order_count = $order_model->whereBetween('create_time',$last_week_time)->count();

        $month_order_total = $order_model->whereBetween('pay_time',$month_time)->sum('pay_price');
        $month_last_order_total = $order_model->whereBetween('pay_time',$month_time)->sum('pay_price');

        $week_order_total = $order_model->whereBetween('pay_time',$week_time)->sum('pay_price');
        $week_last_order_total = $order_model->whereBetween('pay_time',$last_week_time)->sum('pay_price');
        $order_data = [
            'delivery' => $order_model->where([
                'pay_status' => 20,
                'delivery_status' => 10
            ])->count(),
            'pay' => $order_model->where([
                'pay_status' => 10,
                'order_status' => 10
            ])->count(),
            'receipt' => $order_model->where([
                'pay_status' => 20,
                'delivery_status' => 20,
                'receipt_status' => 10
            ])->count(),
            'comment' => $order_model->where(['order_status' => 30])->count(),
            'month_order_count' => $month_order_count,
            'month_last_order_count' => $month_last_order_count,
            'month_bl' => $this->getBL($month_order_count,$month_last_order_count),
            'week_order_count' => $week_order_count,
            'week_last_order_count' => $week_last_order_count,
            'week_bl' => $this->getBL($week_order_count,$week_last_order_count),

            'month_order_total' => $month_order_total,
            'month_last_order_total' => $month_last_order_total,
            'month_price_bl' => $this->getBL($month_order_total,$month_last_order_total),
            'week_order_total' => $week_order_total,
            'week_last_order_total' => $week_last_order_total,
            'week_price_bl' => $this->getBL($week_order_total,$week_last_order_total),
        ];
        $hot_goods = $goods_model->field('goods_id,goods_name,sales_actual,category_id')->with('category')->where('is_delete',0)->where('is_hot',1)->select();


        return $this->fetch('index',compact('total_data','goods_data','user_data','order_data','hot_goods'));
    }

    private function getBL($count,$last_count)
    {
        return  $last_count > 0 ? round(($count-$last_count)/$last_count,2) *100: $count*100;
    }

    public function data($time = '',$type = 'order')
    {
        list($value,$time_tamp) = $this->getTimeStamp($time);
        $data = [];
        $order_model = new OrderModel();
        if ($type == 'order'){
            foreach ($time_tamp as $k => $v){
                $data[] = $order_model->whereBetween('create_time',$v)->count();
            }
        }
        if($type == 'price'){
            foreach ($time_tamp as $k => $v){
                $data[] = $order_model->whereBetween('pay_time',$v)->sum('pay_price');
            }
        }
        return ['key' => array_keys($time_tamp),'data'=>$data,'value'=>$value];
    }

    private function getTimeStamp($time){
        if($time){
            if($time == 'week'){
                list($start_time,$end_time) = Time::Week();
            }elseif ($time == 'month'){
                list($start_time,$end_time) = Time::Month();
            }else{
                $arr = explode(' - ',$time);
                $start_time = strtotime(trim($arr[0]));
                $end_time = isset($arr[1]) ? strtotime(trim($arr[1]))+84600-1 : $start_time+84600-1;
            }
        }else{
            list($start_time,$end_time) = Time::Today();
            $end_time = time();
        }
        $data = [];
        $time = date('Y-m-d',$start_time).' - '.date('Y-m-d',$end_time);
        if(($end_time - $start_time) < 86400){
            while ($start_time < $end_time){
                $data[date('H:i',$start_time)] = [$start_time,$start_time+3600-1];
                $start_time = $start_time + 3600;
            }
        }else{

            while ($start_time < $end_time){
                $data[date('m-d',$start_time)] = [$start_time,$start_time+86400-1];
                $start_time = $start_time + 86400;
            }
        }
        return [$time,$data];
    }

    public function demolist()
    {
        return $this->fetch('demo-list');
    }


}
