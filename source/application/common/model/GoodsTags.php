<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-27 14:29
 */

namespace app\common\model;


class GoodsTags extends BaseModel
{
    protected $name = 'goods_tags';
    protected $updateTime = false;

    protected $hidden = ['create_time','goods_id'];

    /**
     * 关联文件库
     * @return \think\model\relation\BelongsTo
     */
    public function tags()
    {
        return $this->belongsTo('Tags')->bind(['tags_name']);
    }
}