<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\Order as OrderModel;
use app\api\model\User;

/**
 * 个人中心主页
 * Class Index
 * @package app\api\controller\user
 */
class Index extends Controller
{
    /**
     * 获取当前用户信息
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function detail()
    {
        // 当前用户信息
        $userInfo = $this->getUser();
        // 订单总数
        $model = new OrderModel;
        $orderCount = [
            'payment' => $model->getCount($userInfo['user_id'], 'payment'),
            'received' => $model->getCount($userInfo['user_id'], 'received'),
        ];
        return $this->renderSuccess(compact('userInfo', 'orderCount'));
    }

    /**
     * 修改头像
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function SaveAvatar()
    {
        $avatarUrl = $this->request->post('avatar');
        if($this->saveUser(['avatarUrl' => $avatarUrl])){
           return $this->renderSuccess($this->getUser());
        }else{
            return $this->renderError('修改失败');
        }
    }

    public function SaveNickName()
    {
        $nickname = $this->request->post('nickname');
        if($this->saveUser(['nickName' => $nickname])){
            return $this->renderSuccess($this->getUser());
        }else{
            return $this->renderError('修改失败');
        }
    }

    private function saveUser($data)
    {
        $model = $this->getUser();
        return $model->allowField(true)->save($data);
    }

    public function child_list()
    {
        $model = $this->getUser();
        $list = $model->getChild(['level','>',0]);
        return $this->renderSuccess($list);
    }

    public function no_child_list()
    {
        $model = $this->getUser();
        $list = $model->getChild(['level',0]);
        return $this->renderSuccess($list);
    }


}
