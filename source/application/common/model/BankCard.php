<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-09 17:14
 */

namespace app\common\model;


class BankCard extends BaseModel
{
    protected $name = 'bank_card';


    public function file()
    {
        return $this->belongsTo('UploadFile','bank_icon');
    }

    public function getList($uid = 0)
    {
        $filder = [];
        // 筛选条件
        $filter = [];
        $uid > 0 && $filter['user_id'] = $uid;

        $list = $this->with('file')->where($filter)->order('id')->select();
        return $list;
    }
}