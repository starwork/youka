<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-06-13 9:40
 */

namespace app\store\controller\data;


use app\store\controller\Controller;
use app\store\model\User as UserModel;

class User extends Controller
{
    public function index($level = 0,$nickName = '')
    {
        $model = new UserModel();
        $filer = [];
        $level > 0 && $filer['level'] = $level;
        $nickName > 0 && $filer['nickName'] = ['like','%'.$nickName.'%'];
        $list = $model->getList($filer);
        $this->view->engine->layout('data/layout');
        return $this->fetch('index', compact('list','level','nickName'));
    }
}