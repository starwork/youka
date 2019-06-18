<?php

namespace app\common\model;

use app\common\library\wechat\TplMsg;
use think\Db;
use think\Hook;

/**
 * 订单模型
 * Class Order
 * @package app\common\model
 */
class Order extends BaseModel
{
    protected $name = 'order';

    /**
     * 订单模型初始化
     */
    public static function init()
    {
        parent::init();
        // 监听订单处理事件
        $static = new static;
        Hook::listen('order', $static);
    }

    /**
     * 订单商品列表
     * @return \think\model\relation\HasMany
     */
    public function goods()
    {
        return $this->hasMany('OrderGoods');
    }

    /**
     * 关联订单收货地址表
     * @return \think\model\relation\HasOne
     */
    public function address()
    {
        return $this->hasOne('OrderAddress');
    }

    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
    }

    /**
     * 付款状态
     * @param $value
     * @return array
     */
    public function getPayStatusAttr($value)
    {
        $status = [10 => '待付款', 20 => '已付款'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 发货状态
     * @param $value
     * @return array
     */
    public function getDeliveryStatusAttr($value)
    {
        $status = [10 => '待发货', 20 => '已发货'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 收货状态
     * @param $value
     * @return array
     */
    public function getReceiptStatusAttr($value)
    {
        $status = [10 => '待收货', 20 => '已收货'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 收货状态
     * @param $value
     * @return array
     */
    public function getOrderStatusAttr($value)
    {
        $status = [10 => '进行中', 20 => '取消', 30 => '已完成'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 生成订单号
     */
    protected function orderNo()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    /**
     * 订单详情
     * @param $order_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($order_id)
    {
        return self::get($order_id, ['goods.image', 'address']);
    }


    public function getRewardData($order)
    {
        $price_log_count = PriceLog::where('order_id',$order['order_id'])->count();

        if($price_log_count > 0){
            return true;
        }
        $config = \app\store\model\Setting::getItem('retail');
        $parent = User::get($order['user']['pid']);  //一级分销商
        $pparent = User::get($order['user']['ppid']);    //二级分销商
        $count = Db::name('order')->alias('o')->join('user u','o.user_id = u.user_id')->where('u.pid',$parent['user_id'])->where('o.pay_status',20)->where('o.pay_time','<=',$order['pay_time'])->group('u.user_id')->count();

        $is_first = Order::where('user_id',$order['user_id'])->where('pay_time','<',$order['pay_time'])->count() == 0 ? true : false;

        $push = [];
        if($parent){
            //获取咖豆
            Db::name('point_log')->insert([
                'user_id' => $parent['user_id'],
                'form_id' => 'order-'.$order['order_id'],
                'point' => 1,
                'create_time' => time()
            ]);
            Db::name('user')->where('user_id',$parent['user_id'])->setInc('point',1);
            if($count%5 == 1 && $is_first){
                $push['price'] = $config['first_money'];
                $push['text'] = '第'.$count.'单，直推奖';
                $push['user_id'] = $parent['user_id'];
            }
            $parent = $parent->toArray();
            $parent_level = $parent['level']['level'];
            if(!empty($config[$parent_level]['first_money'])){
                $parent['price'] = round($order['total_price']*$config[$parent_level]['first_money']/100,2);
                $parent['text'] = $is_first ? '一级佣金' : '一级佣金(复购)';
            }
        }
        if($pparent){
            $pparent_level = $pparent['level']['level'];
            if(!empty($config[$pparent_level]['second_money'])){
                $pparent = $pparent->toArray();
                $pparent['price'] = round($order['total_price']*$config[$pparent_level]['second_money']/100,2);
                $pparent['text'] = '二级佣金';
            }
        }
        $pids = explode(',',$order['user']['path']);
        $pids[] = $order['user']['pid'];
        $pids[] = $order['user']['ppid'];
        $pids = array_unique(array_filter($pids));
        foreach ($pids as $k => $pid){
            if($pid == $order['user']['pid'] || $pid == $order['user']['ppid']){
                unset($pids[$k]);
            }
        }
        //市场维护奖
        //经销商
        $agent = User::where('user_id','in',$pids)->where('level',20)->order('user_id','desc')->limit(2)->select();
        //dump($agent);exit;

        $zhi_pids = [
            $order['user']['pid'],
            $order['user']['ppid']
        ];
        if($agent){
            $agent = $agent->toArray();
            foreach ($agent as $k =>$v){
                if(in_array($v['user_id'],$zhi_pids)){
                    $agent[$k]['price'] = round($order['total_price']*$config[20]['maintain']['diect']/100,2);
                    $agent[$k]['text'] = '经销商维护奖(直辖区域)';
                }else{
                    $agent[$k]['price'] = round($order['total_price']*$config[20]['maintain']['indirect']/100,2);
                    $agent[$k]['text'] = '经销商维护奖(直接育成)';
                }
            }
//            if(isset($agent[0])){
//                $agent[0]['price'] = round($order['total_price']*$config[20]['maintain']['diect']/100,2);
//                $agent[0]['text'] = '经销商维护奖';
//                if($push && $push['user_id'] == $agent[0]['user_id']){
//                    $agent[0]['price'] = 0;
//                }
//            }
//
//            if(isset($agent[1])){
//                $agent[1]['price'] = round($order['total_price']*$config[20]['maintain']['indirect']/100,2);
//                $agent[1]['text'] = '经销商维护奖';
//            }
        }
        //市级代理
        $city_agent = User::where('user_id','in',$pids)->where('level',30)->order('user_id','desc')->limit(2)->select();
        if($city_agent){
            $city_agent = $city_agent->toArray();
            foreach ($city_agent as $key =>$val){
                if(in_array($val['user_id'],$zhi_pids)){
                    $city_agent[$key]['price'] = round($order['total_price']*$config[30]['maintain']['diect']/100,2);
                    $city_agent[$key]['text'] = '市级代理维护奖(直辖区域)';
                }else{
                    $city_agent[$key]['price'] = round($order['total_price']*$config[30]['maintain']['indirect']/100,2);
                    $city_agent[$key]['text'] = '市级代理维护奖(直接育成)';
                }
            }
        }

        Db::startTrans();
        try{
            if($push && $push['price'] > 0.01){
              if($this->IsPriceLog($push,$order['order_id'])){
                Db::name('price_log')->insert([
                    'user_id' => $push['user_id'],
                    'price' => $push['price'],
                    'text' => $push['text'],
                    'type' => 3,
                    'order_id' => $order['order_id'],
                    'create_time' => time()
                ]);
              }
            }else{
                if($parent && $parent['price'] > 0.01){
                    if($this->IsPriceLog($parent,$order['order_id'])){
                        Db::name('price_log')->insert([
                            'user_id' => $parent['user_id'],
                            'price' => $parent['price'],
                            'text' => $parent['text'],
                            'type' => 1,
                            'order_id' => $order['order_id'],
                            'create_time' => time()
                        ]);
                    }
                }
            }
            if($pparent && $pparent['price'] > 0.01){
              if($this->IsPriceLog($pparent,$order['order_id'])){
                Db::name('price_log')->insert([
                    'user_id' => $pparent['user_id'],
                    'price' => $pparent['price'],
                    'text' => $pparent['text'],
                    'type' => 1,
                    'order_id' => $order['order_id'],
                    'create_time' => time()
                ]);
              }

            }
            if($agent){
                foreach ($agent as $v){
                    if($v['price'] > 0.01){
                        if($this->IsPriceLog($v,$order['order_id'])){
                            Db::name('price_log')->insert([
                              'user_id' => $v['user_id'],
                              'price' => $v['price'],
                              'text' => $v['text'],
                              'type' => 1,
                              'order_id' => $order['order_id'],
                              'create_time' => time()
                          ]);
                        }

                    }
                }
            }

            if($city_agent){
                foreach ($city_agent as $vv){
                    if($vv['price'] > 0.01){
                      if($this->IsPriceLog($vv,$order['order_id'])){
                        Db::name('price_log')->insert([
                            'user_id' => $vv['user_id'],
                            'price' => $vv['price'],
                            'text' => $vv['text'],
                            'type' => 1,
                            'order_id' => $order['order_id'],
                            'create_time' => time()
                        ]);
                      }
                    }
                }
            }
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
        }
        return true;

        if($push){
            $openid = User::where('user_id',$push['user_id'])->value('open_id');
            if($openid) {
                $tpl = new TplMsg();
                $data = [
                    'first' => '您获取订单' . $order['order_no'] . ',直推奖',
                    'price' => $push['price'],
                    'remark' => '下个会员确认收货后，可提现。'
                ];
                $tpl->SendTmpl($openid, 'yongjin', $data);
            }
        }
        $price_list = Db::name('price_log')->where('type',1)->where('order_id',$order['order_id'])->select();
        foreach ($price_list as $price_log){
            $openid = User::where('user_id',$price_log['user_id'])->value('open_id');
            if($openid){
                $tpl = new TplMsg();
                $data = [
                    'first' => '您获取订单'.$order['order_no'].','.$price_log['text'],
                    'price' => $price_log['price'],
                    'remark' => '订单确认收货后，可提现。'
                ];
                $tpl->SendTmpl($openid,'yongjin',$data);
            }
        }


        $data = [
            'push' => $push,
            'parent' => $parent,
            'pparent' => $pparent,
            'agent' => $agent,
            'city_agent' => $city_agent,
        ];



        return $data;
    }
  
  
  private function IsPriceLog($pricelog,$order_id)
  {
  	$count = Db::name('price_log')->where('order_id',$order_id)->where('user_id',$pricelog['user_id'])->where('text',$pricelog['text'])->lock(true)->count();
    return $count == 0;
  }
}
