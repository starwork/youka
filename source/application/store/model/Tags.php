<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-27 14:41
 */

namespace app\store\model;

use app\common\model\Tags as TagsModel;

class Tags extends TagsModel
{

    public function getIdByName($tags_name)
    {
        return self::where(compact('tags_name'))->value('id');
    }
}