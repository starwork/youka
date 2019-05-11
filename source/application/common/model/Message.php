<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-11 13:36
 */

namespace app\common\model;


use think\Request;

class Message extends BaseModel
{
    protected $name = 'message';

    public function user()
    {
        return $this->hasOne('User');
    }

    public function getStatusAttr($value)
    {
        $status = [10 => '未读', 20 => '已阅读'];
        return ['text' => $status[$value], 'value' => $value];
    }

    public function getList($filter)
    {
        return $this->with('user')->where($filter)->paginate(15,false,[
            'query' => Request::instance()->request()
        ]);
    }

    public function add($data)
    {
        return $this->allowField(true)->save($data);
    }

    public function edit($data)
    {
        return $this->allowField(true)->save($data);
    }

    public function read()
    {
        if($this['status'] == 10){
            $this->save([
                'status' => 20
            ]);
        }else{
            $this->error = '消息已阅读';
            return false;
        }
    }

    public function remove()
    {
        return $this->delete();
    }
}