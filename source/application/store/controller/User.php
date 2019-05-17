<?php

namespace app\store\controller;

use app\store\model\User as UserModel;

/**
 * 用户管理
 * Class User
 * @package app\store\controller
 */
class User extends Controller
{
    /**
     * 用户列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new UserModel;
        $list = $model->getList();
        return $this->fetch('index', compact('list'));
    }

    public function update_level()
    {
        $data = $this->request->post();
        $model = UserModel::get($data['user_id']);
        if($model->save(['level' => $data['level']])){
            return $this->renderSuccess('修改成功');
        }
        return $this->renderError('修改失败');
    }

}
