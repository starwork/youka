<?php

namespace app\store\model;

use app\common\model\StoreUser as StoreUserModel;
use think\Db;
use think\Request;
use think\Session;

/**
 * 商家用户模型
 * Class StoreUser
 * @package app\store\model
 */
class StoreUser extends StoreUserModel
{

    public function group()
    {
        return $this->belongsToMany('StoreGroup','store_group_access','group_id','uid');
    }
    /**
     * 商家用户登录
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login($data)
    {
        // 验证用户名密码是否正确
        if (!$user = self::useGlobalScope(false)->with(['wxapp'])->where([
            'user_name' => $data['user_name'],
            'password' => yoshop_hash($data['password'])
        ])->find()) {
            $this->error = '登录失败, 用户名或密码错误';
            return false;
        }
        if (empty($user['wxapp'])) {
            $this->error = '登录失败, 未找到小程序信息';
            return false;
        }
        // 保存登录状态
        Session::set('yoshop_store', [
            'user' => [
                'store_user_id' => $user['store_user_id'],
                'user_name' => $user['user_name'],
            ],
            'wxapp' => $user['wxapp']->toArray(),
            'is_login' => true,
        ]);
        return true;
    }

    /**
     * 商户信息
     * @param $store_user_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($store_user_id)
    {
        return self::get($store_user_id);
    }

    /**
     * 更新当前管理员信息
     * @param $data
     * @return bool
     */
    public function renew($data)
    {
        if ($data['password'] !== $data['password_confirm']) {
            $this->error = '确认密码不正确';
            return false;
        }
        // 更新管理员信息
        if ($this->save([
                'user_name' => $data['user_name'],
                'password' => yoshop_hash($data['password']),
            ]) === false) {
            return false;
        }
        // 更新session
        Session::set('yoshop_store.user', [
            'store_user_id' => $this['store_user_id'],
            'user_name' => $data['user_name'],
        ]);
        return true;
    }

    public function getList($filter= [])
    {
        $id = $this->getPk();
        return $this->with('group')
            ->where($filter)
            ->order($id,'desc')
            ->paginate(15, false, [
                'query' => Request::instance()->request()
            ]);
    }

    public function add($data)
    {
        $data['wxapp_id'] = '10001';
        // 开启事务
        Db::startTrans();
        try {
            $data['password'] = yoshop_hash($data['password']);
            // 添加管理员
            $this->allowField(true)->save($data);
            $arr = [];
            array_map(function ($val) use (&$arr) {
                $arr[] = [
                    'uid' => $this['store_user_id'],
                    'group_id' => $val
                ];
            }, $data['group_ids']);
            $model = new StoreGroupAccess();
            $model->saveAll($arr);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }

    public function edit($data)
    {
        // 开启事务
        Db::startTrans();
        try {
            if($data['password']){
                $data['password'] = yoshop_hash($data['password']);
            }else{
                unset($data['password']);
            }
            // 添加管理员
            $this->allowField(true)->save($data);
            $arr = [];

            $model = new StoreGroupAccess();
            $model->where('uid',$this['store_user_id'])->delete();
            array_map(function ($val) use (&$arr) {
                $arr[] = [
                    'uid' => $this['store_user_id'],
                    'group_id' => $val
                ];
            }, $data['group_ids']);
            $model->saveAll($arr);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }

    /**
     * 删除
     * @param $category_id
     * @return bool|int
     */
    public function remove($id)
    {
        // 开启事务
        Db::startTrans();
        try {
            $model = new StoreGroupAccess();
            $model->where('uid',$this['store_user_id'])->delete();
            self::get($id)->delete();
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }

}
