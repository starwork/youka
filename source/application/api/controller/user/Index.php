<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\Order as OrderModel;
use think\Validate;

/**
 * 个人中心主页
 * Class Index
 * @package app\api\controller\user
 */
class Index extends Controller
{
    /**
     * 获取当前用户信息
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function detail()
    {
        // 当前用户信息
        $userInfo = $this->getUser();
        // 订单总数
        $model = new OrderModel;
        $orderCount = [
            'payment' => $model->getCount($userInfo['user_id'], 'payment'),
            'delivery' => $model->getCount($userInfo['user_id'], 'delivery'),
            'received' => $model->getCount($userInfo['user_id'], 'received'),
            'comment' => $model->getCount($userInfo['user_id'], 'comment'),
        ];
        return $this->renderSuccess(compact('userInfo', 'orderCount'));
    }

    /**
     * 修改头像
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function SaveAvatar()
    {
        $avatarUrl = $this->request->post('avatar');
        if($this->saveUser(['avatarUrl' => $avatarUrl])){
           return $this->renderSuccess($this->getUser());
        }else{
            return $this->renderError('修改失败');
        }
    }

    public function SaveNickName()
    {
        $nickname = $this->request->post('nickname');
        if($this->saveUser(['nickName' => $nickname])){
            return $this->renderSuccess($this->getUser());
        }else{
            return $this->renderError('修改失败');
        }
    }

    private function saveUser($data)
    {
        $model = $this->getUser();
        return $model->allowField(true)->save($data);
    }

    /**
     * 我的激活下级
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function child_list()
    {
        $model = $this->getUser();
        $list = $model->getChild(['level','>',0]);
        return $this->renderSuccess($list);
    }

    /**
     * 未激活下级
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function no_child_list()
    {
        $model = $this->getUser();
        $list = $model->getChild(['level',0]);
        return $this->renderSuccess($list);
    }



    public function orcode()
    {
        $model = $this->getUser();

    }

    /**
     * 修改手机号
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function update_phone()
    {
        $model = $this->getUser();
        $data = $this->request->post();
        $rule = [
            'phone' => 'require|regex:/^1[3-9][0-9]\d{8}$/',
            'code' => 'require'
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
        if($model->save(['phone' => $data['phone']])){
            return $this->renderSuccess($model);
        }
        return $this->renderError('修改失败');
    }
}
