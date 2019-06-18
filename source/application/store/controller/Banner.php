<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-30 13:54
 */

namespace app\store\controller;

use app\common\model\Banner as BannerModel;

class Banner extends Controller
{
    public function index()
    {
        $model = new BannerModel();
        $list  = $model->getList();
        return $this->fetch('index',compact('list'));
    }

    public function add()
    {
        if($this->request->isAjax()){
            $model = new BannerModel();
            if(!$model->add($this->postData('banner'))){
               $error = $model->getError() ? $model->getError() : '添加失败';
               return $this->renderError($error);
            }
            return $this->renderSuccess('添加成功');
        }
        return $this->fetch();
    }

    public function edit($id)
    {
        $model = BannerModel::get($id);
        if($this->request->isAjax()){
            if(!$model->edit($this->postData('banner'))){
                $error = $model->getError() ? $model->getError() : '修改失败';
                return $this->renderError($error);
            }
            return $this->renderSuccess('修改成功');
        }
        return $this->fetch('edit',compact('model'));
    }

    public function delete($id)
    {
        $model = BannerModel::get($id);
        if($model->remove()){
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError('删除失败');
    }
}