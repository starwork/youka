<?php

namespace app\task\controller;

use app\common\library\oauth\WeChat;
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
        $UserModel = new \app\api\model\User();
        $wechat_user = $UserModel->wx_login($this->request->param('code'));
        $user = $UserModel->where('openid',$wechat_user['openid'])->find();
        if($user){
            $token = $user->token();
            // 记录缓存, 7天
            Cache::set($this->token, $user, 86400 * 7);
        }
    }


    public function getUrl($state = '')
    {
        $config = Setting::getItem('wechat');
        $Wechat = new WeChat($config['app_id'],$config['app_secret']);
        $this->redirect($Wechat->getUrl($state));
    }


}