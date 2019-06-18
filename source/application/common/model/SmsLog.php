<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-06 16:21
 */

namespace app\common\model;


class SmsLog extends BaseModel
{
    protected $name = 'sms_logs';

    public function getByPhoneData($type,$phone){
        if(!$sms_log = self::where('phone',$phone)->where('status',10)->where('type',$type)->order('create_time','desc')->find()){
            $this->error = '';
            return false;
        }
        $sms_log['data'] = json_decode($sms_log['data'],true);
        return $sms_log;
    }
}