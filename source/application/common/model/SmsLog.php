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
        if(!$sms_log = self::where('phone',$phone)->order('create_time','desc')->find()){
            $this->error = '';
            return false;
        }
        return json_encode($sms_log['data'],true);
    }
}