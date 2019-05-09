<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-08 10:46
 */

namespace app\common\library\delivery;


class Kd100
{
    public $url = 'https://www.kuaidi100.com/query';

    public $cookie = '';

    function __construct()
    {
        $this->cookie = dirname(__FILE__).'/cookie.txt';
        $url = 'https://www.kuaidi100.com';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);  //保存cookie信息
        curl_exec($ch);
        curl_close($ch);
    }

    public function Search($delivery_no,$com)
    {
        $data = [
            'type' => $com,
            'postid' => $delivery_no,
            'temp' => '0.4207741214331646',
            'phone' => ''
        ];
        $result = $this->curl($this->url,$data);
        return json_decode($result,true);
    }
    /**
     * curl请求指定url
     * @param $url
     * @param array $data
     * @return mixed
     */
    function curl($url, $data = [])
    {
        // 处理get数据
        if (!empty($data)) {
            $url = $url . '?' . http_build_query($data);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
        curl_setopt($curl,CURLOPT_COOKIEFILE,$this->cookie);//发送cookie文件
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}