<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-22 11:15
 */

namespace app\api\behavior;

use think\Response;

class CORS
{
    public function run()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: token,Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: *');
    }
}