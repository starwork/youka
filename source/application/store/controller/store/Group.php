<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-09 10:23
 */

namespace app\store\controller\store;


use app\common\library\Tree;
use app\store\controller\Controller;
use app\store\model\StoreGroup as StoreGroupModel;
use app\store\model\StoreRules;

class Group extends Controller
{
    public function index()
    {
        $model = new StoreGroupModel();
        $list =  $model->getList();
        return $this->fetch('index', compact('list'));
    }

    public function add()
    {
        $model = new StoreGroupModel();
        if($this->request->isAjax()){
            if ($model->add($this->postData('group'))) {
                return $this->renderSuccess('添加成功');
            }
            return $this->renderError($model->getError() ?: '添加失败');
        }
        $data = StoreRules::order(['sort','id'])->field('id,pid as parent,title as text')->select();
        $data = $data->toArray();
        foreach ($data as $k => $v){
            if($v['parent'] == 0){
                $data[$k]['parent'] = "#";
            }
            $data[$k]['state'] = [
                'selected' => false
            ];
        }
        $jstree = json_encode($data);
        return $this->fetch('add',compact('jstree'));
    }

    public function edit($id)
    {
        $model = StoreGroupModel::get($id);
        if($this->request->isAjax()){
            if ($model->edit($this->postData('group'))) {
                return $this->renderSuccess('更新成功');
            }
            return $this->renderError($model->getError() ?: '更新失败');
        }
        $rules = explode(',',$model['rules']);
        $data = StoreRules::order(['sort','id'])->field('id,pid as parent,title as text')->select();
        $data = $data->toArray();
        foreach ($data as $k => $v){
            if($v['parent'] == 0){
                $data[$k]['parent'] = "#";
            }
           if(in_array($v['id'],$rules)){
               $data[$k]['state'] = [
                   'opened' => true
               ];
               if($this->is_select_child($data,$v['id'],$rules)){
                   $data[$k]['state']['selected'] = true;
               }
           }else{
               $data[$k]['state'] = [
                   'selected' => false
               ];
           }
        }
        $jstree = json_encode($data);
        return $this->fetch('edit',compact('model','jstree'));
    }

    private function is_select_child($data,$pid,$rules){
        $childs = [];
        foreach ($data as $k => $v){
            if($v['parent'] == $pid){
                $childs[] = $v;
            }
        }
        foreach ($childs as $vv){
            if(!in_array($vv['id'],$rules)){
                return false;
            }
        }
        return true;
    }

    public function delete($id)
    {
        $model = new StoreGroupModel();
        if ($model->remove($id)) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError($model->getError() ?: '删除失败');
    }
}