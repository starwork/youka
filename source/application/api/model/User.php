<?php

namespace app\api\model;

use app\common\model\User as UserModel;
//use app\api\model\Wxapp;
use app\common\library\wechat\WxUser;
use app\common\exception\BaseException;
use think\Cache;
use think\Log;
use think\Request;

/**
 * 用户模型类
 * Class User
 * @package app\api\model
 */
class User extends UserModel
{
    private $token;

    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'create_time',
        'update_time'
    ];

    /**
     * 获取用户信息
     * @param $token
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function getUser($token)
    {
//        dump($token);exit;
        return self::detail(['user_id' => Cache::get($token)['user_id']]);
    }

    /**
     * 用户登录
     * @param array $post
     * @return string
     * @throws BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
//    public function login($post)
//    {
//        // 微信登录 获取session_key
//        $session = $this->wxlogin($post['code']);
//        // 自动注册用户
//        $userInfo = json_decode(htmlspecialchars_decode($post['user_info']), true);
//        $user_id = $this->register($session['openid'], $userInfo);
//        // 生成token (session3rd)
//        $this->token = $this->token($session['openid']);
//        // 记录缓存, 7天
//        Cache::set($this->token, $session, 86400 * 7);
//        return $user_id;
//    }

    public function login($data)
    {
        if(!$user = $this->where([
            'phone' => $data['username'],
            'password' => yoshop_hash($data['password'])
        ])->find()){
            $this->error = '登录失败, 用户名或密码错误';
            return false;
        }
        Log::debug($user);
        // 生成token (session3rd)
        $this->token = $this->token($user['user_id']);
        // 记录缓存, 7天
        Cache::set($this->token, $user, 86400 * 7);
        return $user['user_id'];
    }

    /**
     * 获取token
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * 微信登录
     * @param $code
     * @return array|mixed
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    private function wxlogin($code)
    {
        // 获取当前小程序信息
        $wxapp = Wxapp::detail();
        if (empty($wxapp['app_id']) || empty($wxapp['app_secret'])) {
            throw new BaseException(['msg' => '请到 [后台-小程序设置] 填写appid 和 appsecret']);
        }
        // 微信登录 (获取session_key)
        $WxUser = new WxUser($wxapp['app_id'], $wxapp['app_secret']);
        if (!$session = $WxUser->sessionKey($code)) {
            throw new BaseException(['msg' => $WxUser->getError()]);
        }
        return $session;
    }

    /**
     * 生成用户认证的token
     * @param $openid
     * @return string
     */
    private function token($openid)
    {
        $wxapp_id = self::$wxapp_id;
        // 生成一个不会重复的随机字符串
        $guid = \getGuidV4();
        // 当前时间戳 (精确到毫秒)
        $timeStamp = microtime(true);
        // 自定义一个盐
        $salt = 'token_salt';
        return md5("{$timeStamp}_{$openid}_{$guid}_{$salt}");
    }

    /**
     * 自动注册用户
     * @param $open_id
     * @param $userInfo
     * @return mixed
     * @throws BaseException
     * @throws \think\exception\DbException
     */
//    private function register($open_id, $userInfo)
//    {
//        if (!$user = self::get(['open_id' => $open_id])) {
//            $user = $this;
//            $userInfo['open_id'] = $open_id;
//            $userInfo['wxapp_id'] = self::$wxapp_id;
//        }
//        $userInfo['nickName'] = preg_replace('/[\xf0-\xf7].{3}/', '', $userInfo['nickName']);
//        if (!$user->allowField(true)->save($userInfo)) {
//            throw new BaseException(['msg' => '用户注册失败']);
//        }
//        return $user['user_id'];
//    }


    public function register($data)
    {
        if (!$user = self::get(['phone' => $data['phone']])) {
            $user = $this;
            $userInfo['phone'] = $data['phone'];
        }else{
            throw new BaseException(['msg' => '手机号已注册']);
        }
    }


    /**
     * 我的邀请
     */
    public function getChild($filter)
    {
        $list = $this->where('parent',$this['user_id'])->where($filter)->paginate(15);
        return $list;
    }
}
