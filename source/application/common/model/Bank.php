<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-06-04 11:19
 */

namespace app\common\model;


class Bank extends BaseModel
{
    protected $name = 'bank';

    public $updateTime = false;

    public function file()
    {
        return $this->belongsTo('UploadFile','icon');
    }

    public function getList()
    {
        return $this->order('sort')->select();
    }

    public function add($data)
    {
        $data['code'] = isset($data['code']) ? strtoupper($data['code']) : '';
        return $this->allowField(true)->save($data);
    }

    public function edit($data)
    {
        $data['code'] = isset($data['code']) ? strtoupper($data['code']) : '';
        return $this->allowField(true)->save($data);
    }

    public function remove()
    {
        return $this->delete();
    }
}