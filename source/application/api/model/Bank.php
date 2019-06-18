<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-06-04 11:35
 */

namespace app\api\model;

use app\common\model\Bank as BankModel;

class Bank extends BankModel
{
    protected $hidden = [
        'create_time'
    ];
}