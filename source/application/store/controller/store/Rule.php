<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-09 10:29
 */

namespace app\store\controller\store;


use app\store\controller\Controller;
use app\store\model\StoreRules as StoreRulesModel;

class Rule extends Controller
{
    public function index()
    {
        $model = new StoreRulesModel();
        $list =  $model->getList();
        return $this->fetch('index', compact('list'));
    }

    public function add()
    {
        $model = new StoreRulesModel();
        if($this->request->isAjax()){
            if ($model->add($this->postData('rule'))) {
                return $this->renderSuccess('添加成功');
            }
            return $this->renderError($model->getError() ?: '添加失败');
        }
        $list =  $model->getList();
        return $this->fetch('add',compact('list'));
    }

    public function edit($id)
    {
        $model = StoreRulesModel::get($id);
        if($this->request->isAjax()){
            if ($model->edit($this->postData('rule'))) {
                return $this->renderSuccess('更新成功');
            }
            return $this->renderError($model->getError() ?: '更新失败');
        }
        $list =  $model->getList();
        return $this->fetch('edit',compact('list','model'));
    }

    public function delete($id)
    {
        $model = new StoreRulesModel();
        if ($model->remove($id)) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError($model->getError() ?: '删除失败');
    }
}