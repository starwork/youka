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
    public function index($level = 0,$nickName = '')
    {
        $model = new UserModel;
        $filer = [];
        $level > 0 && $filer['level'] = $level;
        $nickName != '' && $filer['nickName'] = ['like','%'.$nickName.'%'];
        $list = $model->getList($filer);
        return $this->fetch('index', compact('list','level','nickName'));
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

    public function export($filter=[])
    {
        return $this->exportList($filter);
    }

    private function exportList($filter=[])
    {
        $headlist = [
            'ID',
            '会员昵称',
            '手机号',
            '会员余额',
            '性别',
            '会员等级',
            '上级',
            '上上级',
            '注册时间'
        ];
        $model = new UserModel();
        $list = $model->where($filter)->select();
        $data = [];
        foreach ($list as $k => $user){
            $parent = $user['pid'] ? $model->where('user_id',$user['pid'])->value('nickName') : '';
            $pparent= $user['ppid'] ? $model->where('user_id',$user['ppid'])->value('nickName') : '';
            $arr = [
                $user['user_id'],
                $user['nickName'],
                $user['phone'],
                $user['price'],
                $user['gender'],
                $user['level']['text'],
                $parent,
                $pparent,
                $user['create_time']
            ];
            $data[] = $arr;
        }
        csv_export($data,$headlist,'user-'.date('Ymd'));
    }

    public function edit($user_id)
    {
        $model = UserModel::get($user_id);
        if($this->request->isAjax()){
            if(!$model->edit($this->postData('user'))){
                $error = $model->getError() ? $model->getError() : '修改失败';
                return $this->renderError($error);
            }
            return $this->renderSuccess('修改成功');
        }

    }

}
