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
        //Log::debug($list->toArray());
        return $this->fetch('index', compact('list'));
    }

    public function state($comment_id,$state)
    {
        $model = CommentModel::get($comment_id);
        if($state){
            $data = [
                'status' => 10
            ];
        }else{
            $data = [
                'status' => 20
            ];
        }
        $msg = $state ? '显示' : '隐藏';
        if($model->save($data)){
            return $this->renderSuccess($msg.'成功');
        }else{
            return $this->renderError($msg.'失败');
        }
    }

    public function delete($id)
    {
        $model = CommentModel::get($id);
        if($model->remove()){
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError('删除失败');
    }

    public function reply($comment_id)
    {
        $content = $this->request->post('content');
        if($content == ''){
            return $this->renderSuccess('输入回复内容');
        }
        $data = [
            'parent_id' => $comment_id,
            'user_id' => $this->store['user']['store_user_id'],
            'content' => $content
        ];
        $model = new CommentModel();
        if($model->allowField(true)->save($data)){
            return $this->renderSuccess('回复成功');
        }
        return $this->renderError('回复失败');
    }
}