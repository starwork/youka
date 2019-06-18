<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\Order as OrderModel;
use app\api\model\User;
use app\common\library\QrcodeServer;
use app\common\library\Time;
use app\common\model\Feedback;
use app\common\model\PayBank;
use app\common\model\PriceLog;
use app\common\model\SmsLog;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
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
    public function UserInfo()
    {
        // 当前用户信息
        $userInfo = $this->getUser();
        $default_code = config('default_code');
        $qrcode = base_url().'index.php/api/qrcode/index/user_id/'.$userInfo['user_id'];
        return $this->renderSuccess(compact('userInfo','default_code','qrcode'));
    }

    public function invite_qrcode()
    {
        $user = $this->getUser();
        $text = base_url().'code='.$user['invite_code'];
        $qrCode = new QrCode($text);
        $qrCode->setSize(300);
        // Set advanced options
        $qrCode->setWriterByName('png');
        $qrCode->setMargin(10);
        $qrCode->setEncoding('UTF-8');
        //$qrCode->setErrorCorrectionLevel('high');
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        // $qrCode->setLabel('Scan the code', 16, __DIR__ . '/../assets/fonts/noto_sans.otf', LabelAlignment::CENTER);
        // $qrCode->setLogoPath(__DIR__ . '/../assets/images/symfony.png');
        //$qrCode->setLogoSize(150, 200);
        //$qrCode->setRoundBlockSize(true);
        //$qrCode->setValidateResult(false);
        //$qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
        // Directly output the QR code
        header('Content-Type: ' . $qrCode->getContentType());

        die($qrCode->writeString());
    }

    public function index()
    {
        // 当前用户信息
        $userInfo = $this->getUser();
        $userInfo['total'] = PriceLog::where('user_id',$userInfo['user_id'])->where('type','<>','2')->where('price','>',0)->sum('price');
        $time = Time::YesterDay();
        $userInfo['yesterday'] = PriceLog::where('user_id',$userInfo['user_id'])->whereBetween('create_time',$time)->where('type','<>','2')->where('price','>',0)->sum('price');
        $parent = (new User())->where('user_id',$userInfo['pid'])->value('nickName');
        // 订单总数
        $model = new OrderModel;
        $orderCount = [
            'payment' => $model->getCount($userInfo['user_id'], 'payment'),
            'delivery' => $model->getCount($userInfo['user_id'], 'delivery'),
            'received' => $model->getCount($userInfo['user_id'], 'received'),
            'comment' => $model->getCount($userInfo['user_id'], 'comment'),
        ];
        $pay_count = $model->where('user_id',$userInfo['user_id'])->where('pay_status',20)->count();
        //本周提现次数
        $week_time = Time::Week();
        $draw_num = (new PayBank())->where('user_id',$userInfo['user_id'])->whereBetween('create_time',$week_time)->count();
        return $this->renderSuccess(compact('userInfo', 'orderCount','pay_count','draw_num'));
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
            $userInfo = $this->getUser();
           return $this->renderSuccess(compact('userInfo'));
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

    public function myChild($level = 0)
    {
        $user = $this->getUser();
        $model = new User();
        if($level > 0){
            $child_list = $model->where('level','=',$level)->where('pid',$user['user_id'])->select();
        }else{
            $child_list = $model->where('level','>',0)->where('pid',$user['user_id'])->select();
        }
        $all_count = $model->where('pid',$user['user_id'])->count();
        $child_count_1 = $model->where('level',10)->where('pid',$user['user_id'])->count();
        $child_count_2 = $model->where('level',20)->where('pid',$user['user_id'])->count();
        $child_count_3 = $model->where('level',30)->where('pid',$user['user_id'])->count();
        $select = [];
        $select[] = [
            'title' => '全部',
            'level' => '',
            'num' => $all_count
        ];
        $select[] = [
            'title' => '分销商',
            'level' => 10,
            'num' => $child_count_1
        ];
        $select[] = [
            'title' => '经销商',
            'level' => 20,
            'num' => $child_count_2
        ];
        $select[] = [
            'title' => '市级代理',
            'level' => 30,
            'num' => $child_count_3
        ];

        $not_child_list = $model->where('level',0)->where('pid',$user['user_id'])->select();
        return $this->renderSuccess(compact('child_list','not_child_list','select'));
    }

    /**
     * 我的激活下级
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function child_list()
    {
        $user = $this->getUser();
        $model = new User();
        $list = $model->where('level','>',0)->where('pid',$user['user_id'])->paginate(15);
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
        $user = $this->getUser();
        $model = new User();
        $list = $model->where('level',0)->where('pid',$user['user_id'])->paginate(15);
        return $this->renderSuccess($list);
    }



    public function orcode()
    {
        $model = $this->getUser();
        $qrcode = new QrCode();
        $url = 'https://www.baidu.com';
        $qrcode->setText($url)->setSize(300);
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
        if($user = (new User())->where('phone',$data['phone'])->find()){
            return $this->renderError('手机号已注册');
        }
        $SmsLogModel = new SmsLog();
        $sms_log = $SmsLogModel->getByPhoneData('resgiter',$data['phone']);
        if(!$sms_log){
            $error = $SmsLogModel->getError() ? $SmsLogModel->getError() : '验证码不正确';
            return $this->renderError($error);
        }
        if($sms_log['data']['code'] != $data['code']){
            return $this->renderError('验证码不正确');
        }
        if($model->allowField(true)->save(['phone' => $data['phone']])){
            $SmsLogModel->where('id',$sms_log['id'])->update(['status'=>20]);
            return $this->renderSuccess($model);
        }
        return $this->renderError('修改失败');
    }



    public function SetParent($invite_code)
    {
        $user = $this->getUser();
        if($invite_code == $user['invite_code']){
            return $this->renderError('不能设为自己');
        }
        if(!$user->setParent($invite_code)){
            $error = $user->getError() ? $user->getError() : '设置失败';
            return $this->renderError($error);
        }else{
            $userInfo = $this->getUser();
            return $this->renderSuccess(compact('userInfo'));
        }
    }


    public function feedback()
    {
        $model = new Feedback();
        $user = $this->getUser();
        $today = Time::Today();
        $count = $model->where('user_id',$user['user_id'])->whereBetween('create_time',$today)->count();
        if($count > 0){
            return $this->renderError('今日已提交');
        }
        $data = $this->request->post();
        $data['user_id'] = $user['user_id'];
        if(!$model->add($data)){
            $error = $model->getError() ? $model->getError() : '提交失败';
            return $this->renderError($error);
        }
        return $this->renderSuccess('提交成功');
    }
}
