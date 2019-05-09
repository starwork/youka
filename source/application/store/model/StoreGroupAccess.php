<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-09 13:49
 */

namespace app\store\model;


use think\Model;

class StoreGroupAccess extends Model
{
    protected $name = 'store_group_access';
    public $autoWriteTimestamp = false;
}