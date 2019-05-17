<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-14 13:47
 */

namespace app\api\validate;


use think\Validate;

class Comment extends Validate
{
    protected $rule = [
        'goods_id' => 'require|integer',
        'user_id' => 'require|integer',
        'order_id' => 'require|integer',
        'content' => 'require'
    ];

    protected $message = [
        'goods_id.require' => '评论商品不能为空',
        'goods_id.integer' => '评论商品不能为空',
        'user_id.require' => '评论用户不能为空',
        'user_id.integer' => '评论用户不能为空',
        'content.require' => '评论内容不能为空'
    ];
}