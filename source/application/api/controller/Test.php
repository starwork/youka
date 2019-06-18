<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-06-11 9:23
 */

namespace app\api\controller;


use app\api\model\Setting;
use app\common\library\wechat\TplMsg;
use app\common\library\wechat\WeChat;
use app\api\model\Order as OrderModel;
use app\api\model\User as UserModel;
use app\common\model\PriceLog;
use think\Db;

class Test extends Controller
{
    public function send_tplmsg()
    {
        $config = Setting::getItem('wechat',10001);
        $tlpmsg = Setting::getItem('tplmsg',10001);
        $wechat = new WeChat($config);
        $data = [
            'first' => '亲，您的订单已签收。',
            'keyword1' =>'21312312312312',
            'keyword2' => date('Y年m月d日 H:i:s'),
            'remark' => ''
        ];
        $wechat->send($tlpmsg['receipt_id'],'oh0jB5wRwaZQNd0NNLJouDFyU7m8',$data);
    }
  
    public function test()
    {
      
//		$orderModel = new \app\common\model\Order();
//      	$order = \app\common\model\Order::get(['order_no' => '2019061751101545']);
//        $this->getRewardData($order);
//      	exit;


//        $orderModel = new OrderModel();
//        $pay_order_lists = $orderModel->where(['pay_status'=>20])->order('pay_time','asc')->select();
//        foreach ($pay_order_lists as $order){
//            $count = Db::name('price_log')->where('user_id',28)->where('order_id',$order['order_id'])->count();
//            if($count == 0){
//                echo $order['user']['user_id'].'----------'.$order['order_id'].'<br>';
//            }
//        }
//        exit;

//        $order = OrderModel::get(['order_no'=>'2019061510098571']);
//        $parent = UserModel::get($order['user']['pid']);
//        $parent_order_count = Db::name('order')->alias('o')->join('user u','o.user_id = u.user_id')->where('u.pid',$parent['user_id'])->where('pay_status',20)->where('pay_time','<',time())->count();
//        $count = $parent_order_count +1;
//        dump($count);
//        exit;
//        Db::query("UPDATE yoshop_user SET price = 0, point = 0");
//        Db::query("DELETE FROM yoshop_price_log where type = 1");
//        Db::query("DELETE FROM yoshop_price_log where type = 3");
//        Db::query("DELETE FROM yoshop_point_log");
//
//        $orderModel = new OrderModel();
//        $pay_order_lists = $orderModel->where(['pay_status'=>20])->order('pay_time','asc')->select();
//
//        foreach ($pay_order_lists as $k => $order){
//            $this->getRewardData($order);
//            unset($order);
//        }
//        $receipt_order_lists = $orderModel->where(['receipt_status'=>20])->order('pay_time','asc')->select();
//        foreach ($receipt_order_lists as $k => $order){
//            $data = PriceLog::where('order_id',$order['order_id'])->where('status',10)->where('type',1)->select();
//            $this->getPrice($order,$data);
//        }
//        $user_list = (new UserModel())->where('price','>',0)->select();
//        foreach ($user_list as $k => $user){
//            $pay_bank_price = PriceLog::where('type',2)->sum('price');
//            if($pay_bank_price > 0){
//                (new UserModel())->where('user_id',$user['user_id'])->setDec('price',$pay_bank_price);
//            }
//        }
    }


    public function getPrice($order,$data)
    {
        $parent = UserModel::get($order['user']['pid']);
        $count = Db::name('order')->alias('o')->join('user u','o.user_id = u.user_id')->where('u.pid',$parent['user_id'])->where('o.pay_status',20)->where('o.pay_time','<=',$order['pay_time'])->order('o.pay_time','asc')->group('u.user_id')->count();
        //$count = $parent_order_count +1;
        $bs = floor($count/5);
        $push = null;
        if($count%5 == 2){
            $parent_order = Db::name('order')->alias('o')->join('user u','o.user_id = u.user_id')->where('u.pid',$parent['user_id'])->where('pay_status',20)->order('pay_time','asc')->limit($bs*5,1)->group('u.user_id')->select();
            $parent_order = isset($parent_order[0]) ? $parent_order[0] : null;
            $is_first = false;
            if($parent_order){
                $is_first = OrderModel::where('user_id',$parent_order['user_id'])->where('pay_time','<',$parent_order['pay_time'])->count() == 0 ? true : false;
            }
            if($parent_order && $is_first){
                $push = PriceLog::where('order_id',$parent_order['order_id'])->where('type',3)->find();
                if(!$push){
                    $config = \app\store\model\Setting::getItem('retail');
                    $push = [];
                    $push['price'] = $config['first_money'];
                    $push['text'] = '第'.($bs*5+1).'单，直推奖';
                    $push['user_id'] = $parent['user_id'];
                    Db::name('price_log')->insert([
                        'user_id' => $push['user_id'],
                        'price' => $push['price'],
                        'text' => $push['text'],
                        'type' => 3,
                        'order_id' => $parent_order['order_id'],
                        'create_time' => $order['pay_time']
                    ]);
                    $push['id'] = Db::getLastInsID();
                }elseif($push['status'] == 20){
                    $push = null;
                }
            }
        }
        // 开启事务
        Db::startTrans();
        try {
            if($push){
                Db::name('user')->where('user_id',$push['user_id'])->setInc('price',$push['price']);
                Db::name('price_log')->where('id',$push['id'])->update(['status' => 20]);
            }
            foreach ($data as $k => $v){
                Db::name('user')->where('user_id',$v['user_id'])->setInc('price',$v['price']);
                Db::name('price_log')->where('id',$v['id'])->update(['status' => 20]);
            }
            Db::commit();
            return true;
        }catch (\Exception $e) {
            Db::rollback();
            return false;
        }
    }



    public function getRewardData($order)
    {
        $price_log_count = PriceLog::where('order_id',$order['order_id'])->count();
        if($price_log_count > 0){
            return true;
        }
        $config = \app\store\model\Setting::getItem('retail');
        $parent = UserModel::get($order['user']['pid']);  //一级分销商
        $pparent = UserModel::get($order['user']['ppid']);    //二级分销商
        $count = Db::name('order')->alias('o')->join('user u','o.user_id = u.user_id')->where('u.pid',$parent['user_id'])->where('o.pay_status',20)->where('o.pay_time','<=',$order['pay_time'])->order('o.pay_time','asc')->group('u.user_id')->count();

        $is_first = (new OrderModel())->where('user_id',$order['user_id'])->where('pay_time','<',$order['pay_time'])->count() == 0 ? true : false;

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
            }else{
                $parent = $parent->toArray();
                $parent_level = $parent['level']['level'];
                if($count <= 10  && $pparent['user_id'] != 28){
                    $parent_level = 10;
                }
                if(!empty($config[$parent_level]['first_money'])){
                    $parent['price'] = round($order['total_price']*$config[$parent_level]['first_money']/100,2);
                    $parent['text'] = $is_first ? '一级佣金' : '一级佣金(复购)';
                }
            }
        }
        if($pparent){
            $pp_order_count = Db::name('order')->alias('o')->join('user u','o.user_id = u.user_id')->where('u.pid',$pparent['user_id'])->where('pay_status',20)->where('pay_time','<',$order['pay_time'])->group('u.user_id')->count();
            $pparent_level = $pparent['level']['level'];
            if($pp_order_count <= 10 && $pparent['user_id'] != 28){
                $pparent_level = 10;
            }
            if(!empty($config[$pparent_level]['second_money'])){
                $pparent = $pparent->toArray();
                $pparent['price'] = round($order['total_price']*$config[$pparent_level]['second_money']/100,2);
                $pparent['text'] = '二级佣金';
            }
        }
        $pids = explode(',',$order['user']['path']);
        $pids = array_unique(array_filter($pids));
        foreach ($pids as $k => $pid){
            if($pid == $order['user']['pid'] || $pid == $order['user']['ppid']){
                unset($pids[$k]);
            }
        }
        //市场维护奖
        //经销商
        $agent = UserModel::where('user_id','in',$pids)->where('level',20)->order('user_id','desc')->limit(2)->select();
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
                    $agent[$k]['text'] = '经销商维护奖(育成区域)';
                }
            }
        }
        //市级代理
        $city_agent = UserModel::where('user_id','in',$pids)->where('level',30)->order('user_id','desc')->limit(2)->select();
        if($city_agent){
            $city_agent = $city_agent->toArray();
            foreach ($city_agent as $key =>$val){
                if(in_array($val['user_id'],$zhi_pids)){
                    $city_agent[$key]['price'] = round($order['total_price']*$config[30]['maintain']['diect']/100,2);
                    $city_agent[$key]['text'] = '市级代理维护奖(直辖区域)';
                }else{
                    $city_agent[$key]['price'] = round($order['total_price']*$config[30]['maintain']['indirect']/100,2);
                    $city_agent[$key]['text'] = '市级代理维护奖(育成区域)';
                }
            }
        }
//        $data = [
//            'push' => $push,
//            'parent' => $parent,
//            'pparent' => $pparent,
//            'agent' => $agent,
//            'city_agent' => $city_agent,
//        ];
//        dump($data);exit;
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
                        'create_time' => $order['pay_time']
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
                            'create_time' => $order['pay_time']
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
                        'create_time' => $order['pay_time']
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
                                'create_time' => $order['pay_time']
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
                                'create_time' => $order['pay_time']
                            ]);
                        }
                    }
                }
            }
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
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



    public function getUseInfo($openid)
    {
        $config = Setting::getItem('wechat',10001);
        $wechat = new WeChat($config);
        $user = $wechat->getUserInfo($openid);
        return $user;
    }

    public function index()
    {
        $tpl = new TplMsg();
        $order = \app\api\model\Order::get(35);
        $tpl->SendTmpl('oh0jB5wRwaZQNd0NNLJouDFyU7m8','order_pay',$order);
        $tpl->SendTmpl('oh0jB5wRwaZQNd0NNLJouDFyU7m8','delivery',$order);
        $data = [
                    'first' => '您获取订单' . $order['order_no'] . ',直推奖',
                    'price' => 138,
                    'remark' => '下个会员确认收货后，可提现。'
                ];
        $tpl->SendTmpl('oh0jB5wRwaZQNd0NNLJouDFyU7m8','yongjin',$data);
        $data = [
            'level' =>[
                'level_name' => '经销商'
            ],
            'level_last_name' => '分销商'
        ];
        $tpl->SendTmpl('oh0jB5wRwaZQNd0NNLJouDFyU7m8','level',$data);
        $pay_bank = [
            'price' => 100,
            'bank_name' => '工商银行',
            'card_no' => '4548646487645348',
            'create_time' => '2019-05-08',
            'status' => '通过审核',
            'content' => '24小时到账'
        ];
        $tpl->SendTmpl('oh0jB5wRwaZQNd0NNLJouDFyU7m8','pay_bank',$pay_bank);

        $user = [
            'nickName' => '风',
            'level' => [
                'level_name' => '普通会员'
            ]
        ];
        $tpl->SendTmpl('oh0jB5wRwaZQNd0NNLJouDFyU7m8','child',$user);
    }
}