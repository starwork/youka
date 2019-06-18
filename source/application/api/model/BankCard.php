<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-10 15:41
 */

namespace app\api\model;

use app\common\model\BankCard as BankCardModel;
use think\Validate;

class BankCard extends BankCardModel
{
    public function file()
    {
        return $this->belongsTo('UploadFile','bank_icon')->bind(['file_path']);
    }

    public function getBankCardNoAttr($value)
    {
        return substr($value,-4);
    }

    public function add($user,$data)
    {
        $data['user_id'] = $user['user_id'];
        $rule = [
            'real_name'  => 'require',
            'bank_card_no' => 'require',
            'bank_name' => 'require',
            'open_bank_name' => 'require',
            'phone' => 'require'
        ];
        $msg = [
            'real_name.require' => '填写真实姓名',
            'bank_card_no.require'     => '填写银行卡号',
            'bank_name.require'     => '暂不支持该银行卡',
            'open_bank_name.require'     => '填写银行卡开户行名称',
            'phone.require'     => '填写预留手机号',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            $this->error = $validate->getError();
            return false;
        }
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

    public function remove()
    {
        return $this->delete();
    }
}