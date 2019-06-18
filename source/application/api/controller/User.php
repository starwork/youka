<?php

namespace app\api\controller;

use app\api\model\User as UserModel;
use app\common\library\wechat\TplMsg;
use app\common\model\Setting;
use app\common\model\SmsLog;
use app\api\model\Setting as SettingModel;
use app\api\model\Order as OrderModel;
use app\common\library\sms\Driver as SmsDriver;
use think\Cache;
use think\Validate;

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
        $data = $this->request->post();
        $rule = [
            'username' => 'require',
            'password' => 'require',
        ];
        $message = [
            'username.require' => '用户名不能为空',
            'password.require' => '密码不能为空',
        ];
        $validate = new Validate($rule,$message);
        if(!$validate->check($data)){
            return $this->renderError($validate->getError());
        }
        if(!$user_id = $model->login($data)){
            $error = $model->getError() ? $model->getError() : '登录失败';
            return $this->renderError($error);
        }
        $token = $model->getToken();
        return $this->renderSuccess(compact('user_id', 'token'));
    }

    public function qrcode()
    {
        $code = $this->request->get('code');
        $pid = base64_encode($code);
        $time = OrderModel::where('user_id',$pid)->where('order_status',30)->min('receipt_time');
        if(time()-$time >= 3600){
            return $this->renderError('二维码已失效');
        }

    }



    public function getWechatUser()
    {
        $userModel = new UserModel();
        $wx_user = $userModel->wx_login($this->request->param('code'));
        $user = $userModel->where('open_id',$wx_user['openid'])->find();
        $state = $this->request->param('state','');
        $parent = $userModel->where('invite_code',$state)->find();
        $pid = 0;
        if($parent){
            $start_time = time() - (3600*24*30*3);  //三个月前
            $order_count = (new \app\api\model\Order())->where('user_id',$parent['user_id'])->where('pay_status',20)->where('pay_time' ,'>',$start_time)->count();
            
            if($order_count == 0){
                $pid = 0;
            }else{
                $pid = $parent['user_id'];
            }
        }
        if($user){
            $token = $userModel->token($user['user_id']);
            // 记录缓存, 7天
            Cache::set($token, $user, 86400 * 7);
            $user_id = $user['user_id'];
            $this->redirect(base_url().'home?open_id='.$wx_user['openid'].'&token='.$token);
            return $this->renderSuccess(compact('user_id', 'token'));
        }
        $wx_user['type'] = 'wechat';
        $wx_user['pid'] = $pid;
        $this->redirect(base_url().'index.php/api/user/resgiter?'.http_build_query($wx_user));
        //return $this->renderError('error',$wx_user);
    }

    public function resgiter()
    {
        $type = $this->request->param('type');
        $model = new UserModel();
        $data = $this->request->param();
        if($type == 'wechat'){
            if(empty($data['openid'])){
                return $this->renderError('没有获取授权');
            }
            if($user = UserModel::where('open_id',$data['openid'])->find()){
                $user_id = $user['user_id'];
                $token = $user->token();
                // 记录缓存, 7天
                Cache::set($token, $user, 86400 * 7);
                return $this->renderSuccess(compact('user_id', 'token'));
            }
        }else{
            $rule = [
                'phone' => 'require|regex:/^1[3-9][0-9]\d{8}$/',
                'password' => 'require|alphaNum|min:6',
                'code' => 'require',
            ];
            $message = [
                'phone.require' => '手机号不能为空',
                'phone.regex' => '手机号格式不正确',
                'password.require' => '密码不能为空',
                'password.alphaNum' => '密码只能为字母和数字',
                'password.min' => '密码不能小于6位',
                'code.require' => '验证码不能为空'
            ];
            $validate = new Validate($rule,$message);
            if(!$validate->check($data)){
                return $this->renderError($validate->getError());
            }
            if($user = UserModel::where('phone',$data['phone'])->find()){
                return $this->renderError('手机号已注册');
            }
            $SmsLogModel = new SmsLog();
            if(!$sms_log = $SmsLogModel->getByPhoneData('register',$data['phone'])){
                $error = $SmsLogModel->getError() ? $SmsLogModel->getError() : '验证码不正确';
                return $this->renderError($error);
            }
            if($sms_log['data']['code'] != $data['code']){
                return $this->renderError('验证码不正确');
            }
            $sms_log->save(['status'=>20]);
        }




        if($user){
            $user->save(['open_id' => $data['openid']]);
            $user_id = $user['user_id'];
            $token = $user->token($user_id);
            // 记录缓存, 7天
            Cache::set($token, $user, 86400 * 7);
            return $this->renderSuccess(compact('user_id', 'token'));
        }else{
            $pid = $this->request->param('pid',0);
            $parent = UserModel::get($pid);
            $pid = 0;
            $ppid = 0;
            $path = '';
            if($parent){
                $pid = $parent['user_id'];
                $ppid = $parent['pid'];
                $path = explode(',',$parent['path']);
                $path[] = $parent['user_id'];
                $path = array_filter(array_unique($path));
            }
            if($type == 'wechat'){
                $user_data = [
//                    'phone' => $data['phone'],
//                    'password' => yoshop_hash($data['password']),
                    'pid' => $pid,
                    'ppid' => $ppid,
                    'path' => is_array($path) ? implode(',',$path) : '',
                    'wxapp_id' => '10001',
                    'open_id' => $data['openid'],
                    'nickName' => $data['nickname'],
                    'avatarUrl' => $data['headimgurl'],
                    'gender' => $data['sex'],
                    'country' => $data['country'],
                    'province' => $data['sex'],
                    'city' => $data['city']
                ];
            }else{
                $user_data = [
                    'phone' => $data['phone'],
                    'password' => yoshop_hash($data['password']),
                    'pid' => $pid,
                    'ppid' => $ppid,
                    'path' => is_array($path) ? implode(',',$path) : '',
                    'wxapp_id' => '10001'
                ];
            }
            if($model->allowField(true)->save($user_data)){
                if($model['pid']){
                    $openid = UserModel::where('user_id',$model['pid'])->value('open_id');
                    if($openid){
                        $tpl = new TplMsg();
                        $tpl->SendTmpl($openid,'child',$model);
                    }
                }
                if($model['ppid']){
                    $openid = UserModel::where('user_id',$model['ppid'])->value('open_id');
                    if($openid){
                        $tpl = new TplMsg();
                        $tpl->SendTmpl($openid,'child',$model);
                    }
                }
                //$sms_data->delete();
                $user_id = $model['user_id'];
                $token = $model->token($user_id);
              	if($type == 'wechat'){
                	$this->redirect(base_url().'home?open_id='.$model['open_id'].'&token='.$token);
                }
                // 记录缓存, 7天
                Cache::set($token, $model, 86400 * 7);
                return $this->renderSuccess(compact('user_id', 'token'));
            }else{
                return $this->renderError('注册失败');
            }
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
        $rule = [
            'phone' => 'require|regex:/^1[3-9][0-9]\d{8}$/',
            'password' => 'require|alphaNum|min:6',
            'code' => 'require',
        ];
        $message = [
            'phone.require' => '手机号不能为空',
            'phone.regex' => '手机号格式不正确',
            'password.require' => '密码不能为空',
            'password.alphaNum' => '密码只能为字母和数字',
            'password.min' => '密码不能小于6位',
            'code.require' => '验证码不能为空'
        ];
        $validate = new Validate($rule,$message);
        if(!$validate->check($data)){
            return $this->renderError($validate->getError());
        }
        if(!$user = UserModel::where('phone',$data['phone'])->find()){
            return $this->renderError('密码修改失败');
        }
        $SmsLogModel = new SmsLog();
        if(!$sms_log = $SmsLogModel->getByPhoneData('reset_pasw',$data['phone'])){
            $error = $SmsLogModel->getError() ? $SmsLogModel->getError() : '验证码不正确';
            return $this->renderError($error);
        }
        if($sms_log['data']['code'] != $data['code']){
            return $this->renderError('验证码不正确');
        }
        $sms_log->save(['status'=>20]);

        $user_data = [
            'password' => yoshop_hash($data['password'])
        ];
        if($user->allowField(true)->save($user_data)){
            return $this->renderSuccess('密码修改成功');
        }else{
            return $this->renderError('密码修改失败');
        }
    }


    public function getResVerfiy()
    {
        $config = SettingModel::getItem('sms', $this->wxapp_id);
        $SmsDriver = new SmsDriver($config);
        $data = $this->request->post();
        $rule = [
            'phone' => 'require|regex:/^1[3-9][0-9]\d{8}$/',
        ];
        $message = [
            'phone.require' => '手机号不能为空',
            'phone.regex' => '手机号格式不正确'
        ];
        $validate = new Validate($rule,$message);
        if(!$validate->check($data)){
            return $this->renderError($validate->getError());
        }
        $user = UserModel::get(['phone'=>$data['phone']]);
        if($user){
            return $this->renderError('手机号已注册，或绑定');
        }
        $code = randomStr(6);
        if($SmsDriver->sendSms($data['phone'],'resgiter', compact('code'))){
            (new SmsLog())->allowField(true)->save([
                'phone' => $data['phone'],
                'data'=> json_encode(compact('code')),
                'type' => 'resgiter',
                'status' => 10
            ]);
            return $this->renderSuccess();
        }
        return $this->renderError('发送失败');
    }

    public function getPwdVerfity()
    {
        $config = SettingModel::getItem('sms', $this->wxapp_id);
        $SmsDriver = new SmsDriver($config);
        $data = $this->request->post();
        $rule = [
            'phone' => 'require|regex:/^1[3-9][0-9]\d{8}$/',
        ];
        $message = [
            'phone.require' => '手机号不能为空',
            'phone.regex' => '手机号格式不正确'
        ];
        $validate = new Validate($rule,$message);
        if(!$validate->check($data)){
            return $this->renderError($validate->getError());
        }
        $user = UserModel::get(['phone'=>$data['phone']]);
        if(!$user){
            return $this->renderError('手机号未注册或绑定');
        }
        $code = randomStr(6);
        if($SmsDriver->sendSms($data['phone'],'resetpwd', compact('code'))){
            (new SmsLog())->allowField(true)->save([
                'phone' => $data['phone'],
                'data'=> json_encode(compact('code')),
                'type' => 'resetpwd',
                'status' => 10
            ]);
            return $this->renderSuccess();
        }
        return $this->renderError('发送失败');
    }


    public function getPhoneVerfity()
    {
        $config = SettingModel::getItem('sms', $this->wxapp_id);
        $SmsDriver = new SmsDriver($config);
        $data = $this->request->post();
        $rule = [
            'phone' => 'require|regex:/^1[3-9][0-9]\d{8}$/',
        ];
        $message = [
            'phone.require' => '手机号不能为空',
            'phone.regex' => '手机号格式不正确'
        ];
        $validate = new Validate($rule,$message);
        if(!$validate->check($data)){
            return $this->renderError($validate->getError());
        }
        $user = UserModel::get(['phone'=>$data['phone']]);
        if($user){
            return $this->renderError('手机号已注册或绑定');
        }
        $code = randomStr(6);
        if($SmsDriver->sendSms($data['phone'],'resgiter', compact('code'))){
            (new SmsLog())->allowField(true)->save([
                'phone' => $data['phone'],
                'data'=> json_encode(compact('code')),
                'type' => 'resgiter',
                'status' => 10
            ]);
            return $this->renderSuccess();
        }
        return $this->renderError('发送失败');
    }

    public function bind_phone()
    {
        $data = $this->request->post();
        $rule = [
            'phone' => 'require|regex:/^1[3-9][0-9]\d{8}$/',
            'code' => 'require',
        ];
        $message = [
            'phone.require' => '手机号不能为空',
            'phone.regex' => '手机号格式不正确',
            'code.require' => '验证码不能为空'
        ];
        $validate = new Validate($rule,$message);
        if(!$validate->check($data)){
            return $this->renderError($validate->getError());
        }
    }

}
