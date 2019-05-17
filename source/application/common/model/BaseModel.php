<?php

namespace app\common\model;

use think\Db;
use think\Model;
use think\Request;
use think\Session;

/**
 * 模型基类
 * Class BaseModel
 * @package app\common\model
 */
class BaseModel extends Model
{
    public static $wxapp_id;
    public static $base_url;

    /**
     * 模型基类初始化
     */
    public static function init()
    {
        parent::init();
        // 获取当前域名
        self::$base_url = base_url();
        // 后期静态绑定wxapp_id
        self::$wxapp_id = '10001';
        //self::bindWxappId(get_called_class());
        self::afterWrite(function ($model){
            self::SaveStoreLog();
        });
        self::afterDelete(function ($model){
            self::SaveStoreLog();
        });
    }

    private static function SaveStoreLog()
    {
        $request = Request::instance();
        // 控制器名称
        $controller = toUnderScore($request->controller());
        // 方法名称
        $action = $request->action();
        // 当前uri
        $url = $controller . '/' . $action;
        $store = Session::get('yoshop_store');
        if($store){
            $data = [
                'store_user_id' => $store['user']['store_user_id'],
                'name' => $url,
                'title' => Db::name('store_rules')->where('name',$url)->order('level','desc')->value('title'),
                'data' => json_encode($request->param()),
                'create_time' => time()
            ];
            Db::name('store_log')->insert($data);
        }
    }

    /**
     * 后期静态绑定类名称
     * 用于定义全局查询范围的wxapp_id条件
     * 子类调用方式:
     *   非静态方法:  self::$wxapp_id
     *   静态方法中:  $self = new static();   $self::$wxapp_id
     * @param $calledClass
     */
    private static function bindWxappId($calledClass)
    {
        $class = [];
        if (preg_match('/app\\\(\w+)/', $calledClass, $class)) {
            $callfunc = 'set' . ucfirst($class[1]) . 'WxappId';
            method_exists(new self, $callfunc) && self::$callfunc();
        }
    }

    /**
     * 设置wxapp_id (store模块)
     */
    protected static function setStoreWxappId()
    {
        $session = Session::get('yoshop_store');
        self::$wxapp_id = $session['wxapp']['wxapp_id'];
    }

    /**
     * 设置wxapp_id (api模块)
     */
    protected static function setApiWxappId()
    {
        $request = Request::instance();
        self::$wxapp_id = $request->param('wxapp_id');
    }

    /**
     * 获取当前域名
     * @return string
     */
    protected static function baseUrl()
    {
        $request = Request::instance();
        $host = $request->scheme() . '://' . $request->host();
        $dirname = dirname($request->baseUrl());
        return empty($dirname) ? $host : $host . $dirname . '/';
    }

    /**
     * 定义全局的查询范围
     * @param \think\db\Query $query
     */
//    protected function base($query)
//    {
//        if (self::$wxapp_id > 0) {
//            $query->where($query->getTable() . '.wxapp_id', self::$wxapp_id);
//        }
//    }

}
