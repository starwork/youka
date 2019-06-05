<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-20 15:33
 */
namespace app\common\library;

use EasyWeChat\Factory;

class WeChat
{
    public $app;

    public function __construct($config)
    {
        $this->app = Factory::officialAccount($config);
    }

    public function send($template_id,$openid,$arr)
    {
        $data = [
            'touser' => $openid,
            'template_id' => $template_id,
            'data' => $arr
        ];
        return $this->app->template_message->send($data);
    }
}