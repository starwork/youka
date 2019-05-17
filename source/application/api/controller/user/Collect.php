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

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
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

    public function del($id)
    {
        $model = CollectModel::where('user_id',$this->user['user_id'])->find($id);
        if($model->remove()){
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError('删除失败');
    }

    public function add($goods_id)
    {
        $model = new CollectModel();
        if($model->add($this->user,['goods_id' => $goods_id])){
            return $this->renderSuccess('收藏成功');
        }
        return $this->renderError('收藏失败');
    }
}