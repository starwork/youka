<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-15 9:22
 */

namespace app\common\model;


use think\Request;

class PayBank extends BaseModel
{
    protected $name = 'pay_bank';

    protected $updateTime = false;

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function getStatusAttr($value)
    {
        $status = [10 => '未处理', 20 => '打款成功',30 => '打款失败'];
        return ['text' => $status[$value], 'value' => $value];
    }

    public function getList($filer)
    {
        $list  = $this->with('user')
            ->where($filer)
            ->order('create_time','desc')
            ->paginate(15,false,[
                'query' => Request::instance()->request()
            ]);
        return $list;
    }
}