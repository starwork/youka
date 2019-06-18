<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-22 16:47
 */

namespace app\store\controller;

/**
 *  微信支付记录
 * Class PaymentLog
 * @package app\store\controller
 */
class PaymentLog extends Controller
{
    public function index($num = 1)
    {
        $data = $this->getDate($num);
        $arr = [];
        foreach ($data as $time){
            $arr = array_merge($arr,$this->getLog($time));
        }
    }

    /**
     * 获取最近的日期
     * @param $num
     * @return array
     */
    private function getDate($num)
    {
        $data = [];
        $time = time();
        for($i = 0; $i < $num;$i++){
            $data[] = date('Ymd',$time-($i*86400));
        }
        return $data;
    }

    private function getLog($time)
    {
        $filepath = APP_PATH.'common/library/wechat/logs/'.$time.'.log';
        if(file_exists($filepath)){
            $content = file_get_contents($filepath);
            $data = array_filter(explode(PHP_EOL,$content));
            return $data;
        }
    }
}