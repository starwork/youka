<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-16 15:02
 */

namespace app\common\library\oauth;


use app\common\exception\BaseException;

class WeChat
{
    private $appId;
    private $appSecret;

    private $access_token = null;
    private $openid = null;

    private $error;

    public function __construct($appId,$appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    public function getUrl($state = '')
    {
        $redirect_uri = base_url() . 'getuser.php';
        return 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appId.'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_userinfo&state='.$state.'#wechat_redirect';
    }

    private function getOpenId($code)
    {
        if(!$code){
            throw new BaseException(['msg' => 'code is null']);
        }
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
        $result = json_decode(curl($url, [
            'appid' => $this->appId,
            'secret' => $this->appSecret,
            'grant_type' => 'authorization_code',
            'code' => $code
        ]), true);
        if (isset($result['errcode'])) {
            throw new BaseException(['msg' => $result['errmsg']]);
        }
        $this->access_token = $result['access_token'];
        $this->openid = $result['openid'];
    }

    public function getUserInfo($code)
    {
        $this->getOpenId($code);
        $url = 'https://api.weixin.qq.com/sns/userinfo';
        $result = json_decode(curl($url, [
            'access_token' => $this->access_token,
            'openid' => $this->openid,
            'lang' => 'zh_CN'
        ]), true);

        if (isset($result['errcode'])) {
            throw new BaseException(['msg' => $result['errmsg']]);
        }
        return $result;
    }
}