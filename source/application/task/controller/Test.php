<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-06-12 14:40
 */

namespace app\task\controller;


use app\api\model\Order;
use think\Db;

class Test
{
    public function push()
    {
        $model = new Order();
        exit;
        $list = $model->with('user')->where('order_status',30)->select();
        foreach ($list as $order){
            // 开启事务
            Db::startTrans();
            try {
                $push = $this->getReward($order);
                if (isset($push['price']) && $push['price'] > 0.01) {
                    Db::name('price_log')->insert([
                        'user_id' => $push['user_id'],
                        'price' => $push['price'],
                        'text' => $push['text'],
                        'order_id' => $order['order_id'],
                        'create_time' => $order['receipt_time']
                    ]);
                    Db::name('user')->where('user_id', $push['user_id'])->setInc('price', $push['price']);
                }
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
            }
        }
    }

    public function getReward($order)
    {
        $parent = \app\api\model\User::get($order['user']['pid']);  //一级分销商
        $parent_order_count = Db::name('order')->alias('o')->join('user u','o.user_id = u.user_id')->where('u.pid',$parent['user_id'])->where('pay_status',20)->where('pay_time','<',$order['pay_time'])->count();
        //echo Db::getLastSql();
        $push = [];
        if($parent){
            $parent = $parent->toArray();
            if($parent_order_count%5 == 1){
                $push['price'] = 138;
                $push['text'] = '第'.($parent_order_count).'单，直推奖';
                $push['is_frozen'] = 1;
                $push['jd'] = 0;
            }
            $push['user_id'] = $parent['user_id'];
            $push['nickName'] = $parent['nickName'].'第'.($parent_order_count+1).'单';
        }
        return $push;
    }


}