<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-13 16:59
 */

namespace app\common\model;


use think\Request;

class PriceLog extends BaseModel
{
    protected $name = 'price_log';

    protected $updateTime = false;

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function order()
    {
        return $this->belongsTo('Order');
    }

    public function getList($filer,$order = [])
    {
        return $this->with('user')->where($filer)->order('create_time','desc')->paginate(15,false,[
            'query' => Request::instance()->request()
        ]);
    }
}