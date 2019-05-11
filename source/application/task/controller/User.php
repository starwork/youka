<?php

namespace app\task\controller;

use app\common\library\wechat\WUser;
use app\task\model\Setting;
use think\Controller;

/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-11 14:24
 */
class User extends Controller
{
    public function index()
    {
        $config = Setting::getItem('wechat');
        $app = new WUser($config['app_id'],$config['app_secret']);
        if(!$user = $app->getUser($this->request->param())){
            $error = $app->getError() ? $app->getError() : 'error';
            return $this->error($error);
        }
        $UserModel = new \app\api\model\User();
    }
}