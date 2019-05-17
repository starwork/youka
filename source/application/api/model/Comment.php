<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-10 14:56
 */

namespace app\api\model;

use app\common\model\Comment as CommentModel;
use app\api\model\Order as OrderModel;

class Comment extends CommentModel
{
    public function add($user,$data)
    {
        if(empty($data['order_id']) || empty($data['goods_id'])){
            $this->error = '提交失败';
            return false;
        }
        $filer = [];
        $filer['user_id'] = $user['user_id'];
        $filer['order_id'] = $data['order_id'];
        $order = OrderModel::where($filer)->find();
        if(empty($order) && $order['order_status'] != 30){
            $this->error = '没有确认收货';
            return false;
        }
        $filer = [];
        $filer['user_id'] = $user['user_id'];
        $filer['goods_id'] = $data['goods_id'];
        $filer['spec_sku_id'] = $data['spec_sku_id'];
        $order_goods = OrderGoods::where($filer)->find();
        if(!$order_goods){
            $this->error = '提交失败';
            return false;
        }
        $data['user_id'] = $user['user_id'];
        return $this->allowField(true)->validate(true)->save($data);
    }
}