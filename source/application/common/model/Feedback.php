<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-06-14 9:30
 */

namespace app\common\model;


use think\Validate;

class Feedback extends BaseModel
{
    protected $name = 'feedback';

    public $updateTime = false;

    public function add($data)
    {
        $rule = [
            'content' => 'require'
        ];
        $msg = [
            'content.require' => '输入反馈内容'
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            $this->error = $validate->getError();
            return false;
        }
        return $this->allowField(true)->save($data);
    }
}