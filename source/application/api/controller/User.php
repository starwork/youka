<?php

namespace app\api\controller;

use app\api\model\User as UserModel;
use app\common\model\Setting;
use app\common\model\SmsLog;
use app\common\library\sms\Driver as SmsDriver;

/**
 * 用户管理
 * Class User
 * @package app\api
 */
class User extends Controller
{
    /**
     * 用户自动登录
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function login()
    {
        $model = new UserModel();
        $user_id = $model->login($this->request->post());
        $token = $model->getToken();
        return $this->renderSuccess(compact('user_id', 'token'));
    }


    public function register()
    {
        $model = new UserModel();
        $data = $this->request->post();
        if($user = UserModel::where('phone',$data['phone'])->find()){
            return $this->renderError('手机号已注册');
        }
//        $SmsLogModel = new SmsLog();
//        if(!$sms_data = $SmsLogModel->getByPhoneData('register',$data['phone'])){
//            $error = $SmsLogModel->getError() ? $SmsLogModel->getError() : '验证码不正确';
//            return $this->renderError($error);
//        }
//        if($sms_data['code'] != $data['code']){
//            return $this->renderError('验证码不正确');
//        }
        $user_data = [
            'phone' => $data['phone'],
            'password' => yoshop_hash($data['password'])
        ];
       if($model->allowField(true)->save($user_data)){
           $user_id = $model->login([
               'username' => $data['phone'],
               'password' => $data['password'],
               'wxapp' => '10001'
           ]);
           $token = $model->getToken();
           return $this->renderSuccess(compact('user_id', 'token'));
       }else{
           return $this->renderError('注册失败');
       }
    }


    public function getRegisterCode($phone)
    {
        if($user = UserModel::where('phone',$phone)->find()){
            return $this->renderError('手机号已注册');
        }
        $config = Setting::getItem('sms',$this->wxapp_id);
        $SmsDriver = new SmsDriver($config);
        $code = randomStr();
        return $SmsDriver->sendSms('register',compact('code'));
    }


    public function resetpwd()
    {
        $data = $this->request->post();
        if(!$user = UserModel::where('phone',$data['phone'])->find()){
            return $this->renderError('密码修改失败');
        }
//        $SmsLogModel = new SmsLog();
//        if(!$sms_data = $SmsLogModel->getByPhoneData('reset_pasw',$data['phone'])){
//            $error = $SmsLogModel->getError() ? $SmsLogModel->getError() : '验证码不正确';
//            return $this->renderError($error);
//        }
//        if($sms_data['code'] != $data['code']){
//            return $this->renderError('验证码不正确');
//        }

        $user_data = [
            'password' => yoshop_hash($data['password'])
        ];
        if($user->allowField(true)->save($user_data)){
            return $this->renderSuccess('密码修改成功');
        }else{
            return $this->renderError('密码修改失败');
        }
    }
}
