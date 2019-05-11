<?php

namespace app\common\library\wechat;

/**
 * 微信网页获取用户
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-11 14:10
 */

class WUser
{
    private $appId;
    private $authorize_url = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    private $appSecret;

    private $access_token = null;
    private $openid = null;

    private $error;


    public function __construct($appId,$appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    public function authorize()
    {
        $redirect_uri = base_url() . 'getUser.php';
        return $this->authorize_url.'?appid=APPID&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
    }

    public function getUser($data)
    {
        $code = $data['code'];
        $this->getAccessToken($code);
        return $this->getUserInfo();
    }


    private function getAccessToken($code)
    {
        if(!$code){
            $this->error = 'code不存在';
            return false;
        }
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
        $result = json_decode(curl($url, [
            'appid' => $this->appId,
            'secret' => $this->appSecret,
            'grant_type' => 'authorization_code',
            'js_code' => $code
        ]), true);

        if (isset($result['errcode'])) {
            $this->error = $result['errmsg'];
            return false;
        }
        $this->access_token = $result['access_token'];
        $this->openid = $result['openid'];
    }

    private function getUserInfo()
    {
        if(!$this->access_token && !$this->openid){
            $this->error = '参数不正确';
            return false;
        }
        $url = 'https://api.weixin.qq.com/sns/userinfo';
        $result = json_decode(curl($url, [
            'access_token' => $this->access_token,
            'openid' => $this->openid,
            'lang' => 'zh_CN'
        ]), true);

        if (isset($result['errcode'])) {
            $this->error = $result['errmsg'];
            return false;
        }
        return $result;
    }

    public function getError()
    {
        return $this->error;
    }
}