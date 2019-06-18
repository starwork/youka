<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-22 15:16
 */

namespace app\store\controller\data;


use app\store\controller\Controller;
use app\store\model\Category;
use app\store\model\Goods as GoodsModel;

class Goods extends Controller
{
    public function lists($goods_status = 0,$category_id = 0,$goods_name = '')
    {
        $model = new GoodsModel;
        $list = $model->getList($goods_status,$category_id,$goods_name);
        $catgory = Category::getCacheTree();
        $this->view->engine->layout('data/layout');
        return $this->fetch('index', compact('list','catgory','goods_status','category_id','goods_name'));
    }
}