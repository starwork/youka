<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-10 10:47
 */

namespace app\common\model;


use think\Request;

class Express extends BaseModel
{
    protected $name = 'express';

    public function getList($search = '')
    {
        return $this->order(['sort' => 'asc'])
            ->paginate(15);
    }

    public function add($data)
    {
        return $this->allowField(true)->save($data);
    }

    public function edit($data)
    {
        return $this->allowField(true)->save($data);
    }

    public function remove($id)
    {
        return $this->find($id)->delete();
    }
}