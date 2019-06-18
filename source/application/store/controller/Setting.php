<?php

namespace app\store\controller;

use app\common\library\sms\Driver as SmsDriver;
use app\store\model\Category;
use app\store\model\Setting as SettingModel;
use think\Cache;
use think\Log;

/**
 * 系统设置
 * Class Setting
 * @package app\store\controller
 */
class Setting extends Controller
{
    /**
     * 商城设置
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function store()
    {
        $catgory = Category::getCacheTree();
        $this->assign('catgory',$catgory);
        return $this->updateEvent('store');
    }

    /**
     * 交易设置
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function trade()
    {
        return $this->updateEvent('trade');
    }

    /**
     * 短信通知
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function sms()
    {
        return $this->updateEvent('sms');
    }

    /**
     * 发送短信通知测试
     * @param $AccessKeyId
     * @param $AccessKeySecret
     * @param $sign
     * @param $msg_type
     * @param $template_code
     * @param $accept_phone
     * @return array
     * @throws \think\Exception
     */
    public function smsTest($AccessKeyId, $AccessKeySecret, $sign, $msg_type, $template_code, $accept_phone)
    {
        $SmsDriver = new SmsDriver([
            'default' => 'aliyun',
            'engine' => [
                'aliyun' => [
                    'AccessKeyId' => $AccessKeyId,
                    'AccessKeySecret' => $AccessKeySecret,
                    'sign' => $sign,
                    $msg_type => compact('template_code', 'accept_phone'),
                ],
            ],
        ]);
        $templateParams = [];
        if ($msg_type === 'order_pay') {
            $templateParams = ['order_no' => '2018071200000000'];
        }
        if ($msg_type === 'resgiter') {
            $templateParams = ['code' => '123456'];
        }
        if ($msg_type === 'resetpwd') {
            $templateParams = ['code' => '123456'];
        }
        if ($msg_type === 'delivery') {
            $templateParams = ['order_no' => '2018071200000000','company'=>'中通快递','express_no'=> '1000000000'];
        }
        if ($msg_type === 'pay_success') {
            $templateParams = ['price' => '10.5'];
        }
        if ($msg_type === 'pay_error') {
            $templateParams = ['content' => '提现失败测试'];
        }
        if ($SmsDriver->sendSms($accept_phone,$msg_type, $templateParams, true)) {
            return $this->renderSuccess('发送成功');
        }
        return $this->renderError('发送失败 ' . $SmsDriver->getError());
    }

    /**
     * 上传设置
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function storage()
    {
//        (new \app\common\model\Setting())->defaultData();
        return $this->updateEvent('storage');
    }

    /**
     * 支付设置
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function payment()
    {
        return $this->updateEvent('payment');
    }

    /**
     * 微信设置
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function wechat()
    {
        return $this->updateEvent('wechat');
    }


    /**
     * 分销设置
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function retail()
    {
        return $this->updateEvent('retail');
    }

    /**
     * 模板消息
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function tplmsg()
    {
        return $this->updateEvent('tplmsg');
    }



    public function tplmsg_test($template_code, $openid)
    {

    }

    public function privacy()
    {
        return $this->updateEvent('privacy');
    }

    public function server()
    {
        return $this->updateEvent('server');
    }
    /**
     * 更新商城设置事件
     * @param $key
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    private function updateEvent($key)
    {
        if (!$this->request->isAjax()) {
            $values = SettingModel::getItem($key);
            Log::debug($values);
            return $this->fetch($key, compact('values'));
        }
        $model = new SettingModel;
        if ($model->edit($key, $this->postData($key))) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError('更新失败');
    }

}
