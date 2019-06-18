<?php

namespace app\api\controller;

use app\api\controller\user\Message;
use app\api\model\Setting;
use app\api\model\User as UserModel;
use app\api\model\Cart as CartModel;
use app\common\model\Message as MessageModel;
use app\common\exception\BaseException;
use app\common\library\wechat\WeChat;
use think\Controller as ThinkController;
use think\Db;

/**
 * API控制器基类
 * Class BaseController
 * @package app\store\controller
 */
class Controller extends ThinkController
{
    const JSON_SUCCESS_STATUS = 1;
    const JSON_ERROR_STATUS = 0;

    protected $cart_num = 0;
    protected $message_num = 0;

    /* @ver $wxapp_id 小程序id */
    protected $wxapp_id;

    /**
     * 基类初始化
     * @throws BaseException
     */
    public function _initialize()
    {
        // 当前小程序id
        $this->wxapp_id = $this->getWxappId();
        $this->getCartNum();
        $this->getMessageNum();
    }


    private function getCartNum()
    {
        if ($token = $this->request->header('token')) {
            if ($user = UserModel::getUser($token)) {
                $this->cart_num = (new CartModel($user['user_id']))->getTotalNum();
                if($this->cart_num <= 0){
                    $this->cart_num = 0;
                }
            }
        }
    }

    private function getMessageNum()
    {
        if ($token = $this->request->header('token')) {
            if ($user = UserModel::getUser($token)) {
                $count = MessageModel::where('type','system')->count();
                $yd_count = Db::name('message_user')->where('user_id',$user['user_id'])->count();
                $system_count = $count - $yd_count;
                $delivery_count = MessageModel::where('type','delivery')->where('user_id',$user['user_id'])->where('status',10)->count();
                $notice_count = MessageModel::where('type','notice')->where('user_id',$user['user_id'])->where('status',10)->count();
                $this->message_num = $system_count + $delivery_count + $notice_count;
                if($this->message_num <= 0){
                    $this->message_num = 0;
                }
            }
        }
    }

    protected function getUserInfoNotError()
    {
        if ($token = $this->request->header('token')) {
            if ($user = UserModel::getUser($token)) {
                $user['subscribe'] = 1;
                $deviceType = $this->request->header('deviceType','');
                if($user['open_id'] && $deviceType == 'wechat'){
                    $user['subscribe'] = $this->getIsSubscribe($user['open_id']);
                }
                return $user;
            }
        }
    }

    /**
     * 获取当前小程序ID
     * @return mixed
     * @throws BaseException
     */
    private function getWxappId()
    {
        $wxapp_id = 10001;
//        if (!$wxapp_id = $this->request->param('wxapp_id')) {
//            throw new BaseException(['msg' => '缺少必要的参数：wxapp_id']);
//        }
        return $wxapp_id;
    }

    /**
     * 获取当前用户信息
     * @return mixed
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    protected function getUser()
    {
        $user = UserModel::get(58);
        $user['subscribe'] = 1;
        return $user;
//        dump($this->request->header());exit;
        if (!$token = $this->request->header('token')) {
            throw new BaseException(['code' => -1, 'msg' => '缺少必要的参数']);
        }
        if (!$user = UserModel::getUser($token)) {
            throw new BaseException(['code' => -1, 'msg' => '没有找到用户信息']);
        }
        if($user['invite_code'] == ''){
            $userModel = new UserModel();
            $code = $userModel->getInviteCode();
            UserModel::where('user_id',$user['user_id'])->update(['invite_code' => $code]);
            $user['invite_code'] = $code;
        }
        $deviceType = $this->request->header('deviceType','');
        $user['subscribe'] = 0;
        if($user['open_id'] && $deviceType == 'wechat'){
            $user['subscribe'] = $this->getIsSubscribe($user['open_id']);
        
        }
        return $user;
    }


    private function getIsSubscribe($openid)
    {
        $config = Setting::getItem('wechat',10001);
        $wechat = new WeChat($config);
        $user = $wechat->getUserInfo($openid);
        return $user['subscribe'];
    }

    /**
     * 返回封装后的 API 数据到客户端
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function renderJson($code = self::JSON_SUCCESS_STATUS, $msg = '', $data = [])
    {
        return compact('code', 'msg', 'data');
    }

    /**
     * 返回操作成功json
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function renderSuccess($data = [], $msg = 'success')
    {
        return $this->renderJson(self::JSON_SUCCESS_STATUS, $msg, $data);
    }

    /**
     * 返回操作失败json
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function renderError($msg = 'error', $data = [])
    {
        return $this->renderJson(self::JSON_ERROR_STATUS, $msg, $data);
    }

    /**
     * 获取post数据 (数组)
     * @param $key
     * @return mixed
     */
    protected function postData($key)
    {
        return $this->request->post($key . '/a');
    }

}
