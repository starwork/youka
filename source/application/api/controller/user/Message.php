<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-11 13:36
 */

namespace app\api\controller\user;


use app\api\controller\Controller;
use app\common\model\Message as MessageModel;
use think\Request;

/**
 * 用户消息
 * Class Message
 * @package app\api\controller\user
 */
class Message extends Controller
{

    protected $user;

    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();   // 用户信息
    }

    public function index($dataType = '')
    {
        return $this->getList($dataType);
    }

    public function getList($type)
    {
        return MessageModel::where('user_id',$this->user['user_id'])->where('type',$type)->paginate(15,false,[
            'query' => Request::instance()->request()
        ]);
    }
}