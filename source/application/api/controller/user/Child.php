<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-14 9:49
 */

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\Order as OrderModel;

/**
 * 用户下级
 * @package app\api\controller\user
 */
class Child extends Controller
{
    private $user;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();   // 用户信息
    }

    /**
     * 下级订单列表
     * @param $dataType
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists($dataType)
    {
        $model = new OrderModel;
        $list = $model->getChildList($this->user['user_id'], $dataType);
        return $this->renderSuccess(compact('list'));
    }

}