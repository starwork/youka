<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-08 11:55
 */

namespace app\store\controller\goods;


use app\store\controller\Controller;
use app\common\model\Comment as CommentModel;
use think\Log;

class Comment extends Controller
{
    /**
     * 评论列表
     * @return mixed
     */
    public function index($uid = 0,$goods_id = 0)
    {
        $model = new CommentModel();
        $list = $model->getList($uid,$goods_id);

        Log::debug($list->toArray());
        return $this->fetch('index', compact('list'));
    }

    public function delete()
    {

    }
}