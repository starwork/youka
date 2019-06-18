<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-23 16:19
 */

namespace app\store\controller\data;


use app\store\controller\Controller;
use app\store\model\Category as CategoryModel;

class Category extends Controller
{
    public function index()
    {
        $model = new CategoryModel;
        $list = $model->getCacheTree();
        return $this->fetch('index', compact('list'));
    }
}