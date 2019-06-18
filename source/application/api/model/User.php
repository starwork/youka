<?php

namespace app\api\model;

use app\common\library\oauth\WeChat;
use app\common\library\wechat\TplMsg;
use app\common\library\wechat\WUser;
use app\common\model\User as UserModel;
//use app\api\model\Wxapp;
use app\common\exception\BaseException;
use think\Cache;
use think\Db;
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


    protected $append = [
        'parent',
        'user_count',
        'child_count'
    ];
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'password',
        'open_id',
        'wxapp_id',
        'update_time'
    ];


    public function getNickNameAttr($value, $data)
    {
        return $value ? $value : '用户'.$data['user_id'];
    }


    public function getAvatarUrlAttr($value, $data)
    {
        return $value ? $value : 'http://youka.qiniuniu.com/uploads/20190611151504525df1727.png';
    }

    public function getUserCountAttr($value, $data)
    {
        return $this->where('pid',$data['user_id'])->count();
    }

    public function getChildCountAttr($value, $data)
    {
        return $this->where(function ($query) use ($data){
            $query->where('pid',$data['user_id'])->whereOr('ppid',$data['user_id']);
        })->count();
    }

    public function getParentAttr($value, $data)
    {
        $parent = $this->find($data['pid']);
        if($parent){
            $parent_name = $parent['nickName'] ? $parent['nickName'] : '用户'.$parent['user_id'];
        }else{
            $parent_name = '无上级';
        }
        return $parent_name;
    }

    /**
     * 获取用户信息
     * @param $token
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function getUser($token)
    {

        $user = self::get(['user_id' => Cache::get($token)['user_id']]);
        if(!empty($user)){
            self::Upgrade($user);
        }
        $user_id = Cache::get($token)['user_id'];
        if($user_id){
            return self::detail(['user_id' => $user_id]);
        }
        throw new BaseException(['code' => -1, 'msg' => '没有找到用户信息']);
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
     * 微信小程序登录
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
            throw new BaseException(['msg' => '请到 [后台-设置-微信公众号] 填写appid 和 appsecret']);
        }
        // 微信登录 (获取session_key)
        $WxUser = new WxUser($wxapp['app_id'], $wxapp['app_secret']);
        if (!$session = $WxUser->sessionKey($code)) {
            throw new BaseException(['msg' => $WxUser->getError()]);
        }
        return $session;
    }


    public function wx_login($code)
    {
        if(empty($code)){
            throw new BaseException(['msg' => 'code is null']);
        }
        $config = Setting::getItem('wechat');
        if (empty($config['app_id']) || empty($config['app_secret'])) {
            throw new BaseException(['msg' => '请到 [后台-设置-微信公众号] 填写appid 和 appsecret']);
        }
        $wechat = new WeChat($config['app_id'], $config['app_secret']);

        return $wechat->getUserInfo($code);
    }

    /**
     * 生成用户认证的token
     * @param $openid
     * @return string
     */
    public function token($openid)
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
    private function wx_register($open_id, $userInfo)
    {
        if (!$user = self::get(['open_id' => $open_id])) {
            $user = $this;
            $userInfo['open_id'] = $open_id;
            $userInfo['wxapp_id'] = self::$wxapp_id;
        }
        $userInfo['nickName'] = preg_replace('/[\xf0-\xf7].{3}/', '', $userInfo['nickname']);
        $userInfo['avatarUrl'] = $userInfo['headimgurl'];
        if (!$user->allowField(true)->save($userInfo)) {
            throw new BaseException(['msg' => '用户注册失败']);
        }
        return $user['user_id'];
    }


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
    public function getChild($user,$filter)
    {
        $list = $this->where('pid',$user['user_id'])->where($filter)->paginate(15);
        return $list;
    }

    /**
     * 设置推荐人
     * @param \think\Model $invite_code
     * @return UserModel|bool
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function  setParent($invite_code)
    {
        if($this['pid'] == 0){
            $parent = $this->where(['invite_code'=>$invite_code])->find();
            if(!$parent){
                $this->error = '推荐人不存在';
                return false;
            }
            $default_code = config('default_code');
            if($invite_code != $default_code){
                $start_time = time() - (3600*24*30*3);  //三个月前
                $order_count = (new Order())->where('user_id',$parent['user_id'])->where('pay_status',20)->where('pay_time' ,'>',$start_time)->count();

                if($order_count == 0){
                    $this->error = '该推荐人3个月无交易,无法设为推荐人';
                    return false;
                }
            }
            $path = explode(',',$parent['path']);
            $path[] = $parent['user_id'];
            $path = array_filter(array_unique($path));
            $paths = implode(',',$path);
            $this->allowField(true)->save(['pid' => $parent['user_id'], 'ppid' => $parent['pid'], 'path' => $paths]);
            if($parent['open_id']){
                $tpl = new TplMsg();
                $tpl->SendTmpl($parent['open_id'],'child',$this);
            }
            if($parent['pid']){
                $openid = User::where('user_id',$parent['pid'])->value('open_id');
                if($openid){
                    $tpl = new TplMsg();
                    $tpl->SendTmpl($openid,'child',$this);
                }
            }
            return true;
        }else{
            $this->error = '推荐人已设置';
            return false;
        }
    }



    private static function Upgrade($user)
    {
        $level = $user['level']['level'];
        if($level == 0){
            //升级分销商
            $filer = [];
            $filer['user_id'] = $user['user_id'];
            $filer['pay_status'] = 20;
            $order_count = (new Order())->where($filer)->count();
            if($order_count > 0){
                $user->allowField(true)->save(['level' => 10]);
                $openid = $user['open_id'];
                if($openid){
                    $tpl = new TplMsg();
                    $user['level_last_name'] = '普通会员';
                    $tpl->SendTmpl($openid,'level',$user);
                }
            }

        }elseif ($level == 10){
            //升级经销商
            $filer = [];
            $filer['pid'] = $user['user_id'];
            $filer['level'] = 10;
            $user_count = self::where($filer)->count();
            if($user_count >= 10){
                $user->allowField(true)->save(['level' => 20]);
                $openid = $user['open_id'];
                if($openid){
                    $tpl = new TplMsg();
                    $user['level_last_name'] = '分销商';
                    $tpl->SendTmpl($openid,'level',$user);
                }
            }
        }elseif($level == 20){
            //升级市级代理
            $filer = [];
            $filer['pid'] = $user['user_id'];
            $filer['level'] = 20;
            $user_count = self::where($filer)->count();
            $path = $user['path'] ? $user['path'].','.$user['user_id'] : $user['user_id'];
            if($user_count >= 4){
                $order_count = Db::name('order')
                                ->alias('o')
                                ->join('user u','o.user_id = u.user_id')
                                ->where('o.pay_status',20)
                                ->where('u.path','like',$path.'%')->count();
                if($order_count >= 300){
                    $order_price = Db::name('order')
                        ->alias('o')
                        ->join('user u','o.user_id = u.user_id')
                        ->where('o.pay_status',20)
                        ->where('u.path','like',$path.'%')->sum('o.total_price');
                    if($order_price > 3000000){
                        $user->allowField(true)->save(['level' => 30]);
                        $openid = $user['open_id'];
                        if($openid){
                            $tpl = new TplMsg();
                            $user['level_last_name'] = '经销商';
                            $tpl->SendTmpl($openid,'level',$user);
                        }
                    }
                }
            }
        }
    }
}
