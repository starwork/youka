<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-17 9:38
 */

namespace app\api\controller\user;


use app\api\controller\Controller;
use app\common\model\Collect as CollectModel;

class Collect extends Controller
{
    protected $user;

    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();
    }

    /**
     * 我的收藏
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new CollectModel();
        $filer = [];
        $filer['user_id'] = $this->user['user_id'];
        $list = $model->getList($filer);
        return $this->renderSuccess($list);
    }

    public function delete($id)
    {
        $model = CollectModel::where('user_id',$this->user['user_id'])->find($id);
        if($model->remove()){
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError('删除失败');
    }

    public function add()
    {
        $goods_id = $this->request->post('goods_id');
        $model = new CollectModel();
        $collect = $model->where('goods_id',$goods_id)->where('user_id',$this->user['user_id'])->find();
        if($collect){
            return $this->renderSuccess('收藏成功');
        }
        if($model->add($this->user,['goods_id' => $goods_id])){
            return $this->renderSuccess('收藏成功');
        }
        return $this->renderError('收藏失败');
    }
}