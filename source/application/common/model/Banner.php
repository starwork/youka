<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-30 13:56
 */

namespace app\common\model;


class Banner extends BaseModel
{
    protected $name = 'banner';

    protected $updateTime = false;

    public function file()
    {
        return $this->belongsTo('UploadFile','file_id');
    }

    public function getList()
    {
        return $this->with('file')->order('sort')->select();
    }

    public function add($data)
    {
        if (!isset($data['file_id']) || empty($data['file_id'])) {
            $this->error = '请上传推广图片';
            return false;
        }
        return $this->allowField(true)->save($data);
    }

    public function edit($data)
    {
        if (!isset($data['file_id']) || empty($data['file_id'])) {
            $this->error = '请上传推广图片';
            return false;
        }
        return $this->allowField(true)->save($data);
    }

    public function remove()
    {
        return $this->delete();
    }
}