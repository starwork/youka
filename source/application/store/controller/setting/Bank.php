<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-06-04 11:46
 */

namespace app\store\controller\setting;


use app\store\controller\Controller;
use app\store\model\Bank as BankModel;

class Bank extends  Controller
{
    public function index()
    {
        $model = new BankModel();
        $list = $model->getList();
        return $this->fetch('',compact('list'));
    }

    public function add()
    {
        if($this->request->isAjax()){
            $model = new BankModel();
            if($model->add($this->postData('bank'))){
                return $this->renderSuccess('添加成功');
            }
            return $this->renderError('添加失败');
        }
        return $this->fetch();
    }

    public function edit($id)
    {
        $model = BankModel::get($id);
        if($this->request->isAjax()){
            if($model->edit($this->postData('bank'))){
                return $this->renderSuccess('修改成功');
            }
            return $this->renderError('修改失败');
        }
        return $this->fetch('edit',compact('model'));
    }

    public function delete($id)
    {
        $model = BankModel::get($id);
        if($model->remove()){
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError('删除失败');
    }
}