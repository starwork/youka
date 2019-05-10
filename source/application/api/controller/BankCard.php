<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-10 15:35
 */

namespace app\api\controller;

use app\api\model\BankCard as BankCardModel;

class BankCard extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    /* @var \app\api\model\Cart $model */
    private $model;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();
        $this->model = new BankCardModel();
    }

    /**
     * 收货地址列表
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function lists()
    {
        $list = $this->model->getList($this->user['user_id']);
        return $this->renderSuccess([
            'list' => $list,
        ]);
    }

    /**
     * 添加
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function add()
    {
        if ($this->model->add($this->user, $this->request->post())) {
            return $this->renderSuccess([], '添加成功');
        }
        return $this->renderError('添加失败');
    }

    /**
     * 收货地址详情
     * @param $address_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function detail($id)
    {
        $detail = $this->model->detail($this->user['user_id'],$id);
        return $this->renderSuccess(compact('detail'));
    }

    /**
     * 编辑收货地址
     * @param $address_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function edit($id)
    {
        $model = $this->model->detail($this->user['user_id'],$id);
        if ($model->edit($this->request->post())) {
            return $this->renderSuccess([], '更新成功');
        }
        return $this->renderError('更新失败');
    }

    /**
     * 删除
     * @param $address_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function delete($id)
    {
        $model = $this->model->detail($this->user['user_id'],$id);
        if ($model->remove($this->user)) {
            return $this->renderSuccess([], '删除成功');
        }
        return $this->renderError('删除失败');
    }
}