<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-20 15:33
 */
namespace app\common\library\wechat;

use EasyWeChat\Factory;

class WeChat
{
    public $app;

    public function __construct($config)
    {
        $this->app = Factory::officialAccount(['app_id' => $config['app_id'],'secret'=> $config['app_secret']]);
    }

    /**
     * 发送模板消息
     * @param $template_id
     * @param $openid
     * @param $arr
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    public function send($template_id,$openid,$arr,$url = '')
    {
        $data = [
            'touser' => $openid,
            'template_id' => $template_id,
            'url' => $url == '' ? base_url() : $url,
            'data' => $arr
        ];
        return $this->app->template_message->send($data);
    }

    public function getUserInfo($openid)
    {
        $user = $this->app->user->get($openid);
        return $user;
    }
}