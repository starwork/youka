<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-10 15:41
 */

namespace app\api\model;

use app\common\model\BankCard as BankCardModel;

class BankCard extends BankCardModel
{
    public function add($user,$data)
    {
        $data['user_id'] = $user['user_id'];
        return $this->allowField(true)->save($data);
    }

    public function detail($user,$id)
    {
        return $this->where('user_id',$user['user_id'])->where('id',$id)->find();
    }

    public function edit($data)
    {
        return $this->allowField(true)->save($data);
    }

    public function remove($user)
    {
        return $this->where('user_id',$user['user_id'])->delete();
    }
}