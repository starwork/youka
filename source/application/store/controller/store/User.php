<?php

namespace app\store\controller\store;

use app\store\controller\Controller;
use app\store\model\StoreGroup;
use app\store\model\StoreGroupAccess;
use app\store\model\StoreUser as StoreUserModel;

/**
 * 商户管理员控制器
 * Class StoreUser
 * @package app\store\controller
 */
class User extends Controller
{
    /**
     * 更新当前管理员信息
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function renew()
    {
        $model = StoreUserModel::detail($this->store['user']['store_user_id']);
        if ($this->request->isAjax()) {
            if ($model->renew($this->postData('user'))) {
                return $this->renderSuccess('更新成功');
            }
            return $this->renderError($model->getError() ?: '更新失败');
        }
        return $this->fetch('renew', compact('model'));
    }

    public function index()
    {
        $model = new StoreUserModel();
        $list =  $model->getList();
        return $this->fetch('index', compact('list'));
    }

    public function add()
    {
        $model = new StoreUserModel();
        if($this->request->isAjax()){
            if ($model->add($this->postData('user'))) {
                return $this->renderSuccess('添加成功');
            }
            return $this->renderError($model->getError() ?: '添加失败');
        }
        $list = StoreGroup::all();
        return $this->fetch('add',compact('list'));
    }

    public function edit($id)
    {
        $model = StoreUserModel::get($id);
        if($this->request->isAjax()){
            if ($model->edit($this->postData('user'))) {
                return $this->renderSuccess('更新成功');
            }
            return $this->renderError($model->getError() ?: '更新失败');
        }
        $group_ids = StoreGroupAccess::where('uid',$id)->column('group_id');
        $list = StoreGroup::all();
        return $this->fetch('edit',compact('list','model','group_ids'));
    }

    public function delete($id)
    {
        $model = new StoreUserModel();
        if ($model->remove($id)) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError($model->getError() ?: '删除失败');
    }
}
