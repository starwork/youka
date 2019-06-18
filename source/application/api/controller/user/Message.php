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
use think\Db;
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

    public function index($dataType = 'delivery')
    {
        //物流未读数
        $delivery_count = $this->getCount('delivery');
        $notice_count = $this->getCount('notice');
        $system_count = $this->getCount('system');
        $message_num = $this->message_num;

        $list = $this->getList($dataType);
        $data = $list->items();
        foreach ($data as $k => $message){
            if($message['type'] == 'system'){
                $message_user = Db::name('message_user')->where('user_id',$this->user['user_id'])->where('message_id',$message['id'])->find();
                if($message_user){
                    $data[$k]['status'] = 20;
                }else{
                    $data[$k]['status'] = 10;
                    Db::name('message_user')->insert(['user_id'=>$this->user['user_id'],'message_id'=> $message['id']]);
                }
            }else{
                if($message['status']['value'] == 10){
                    $message->save(['status' => 20]);
                }
            }
        }
        $list->data = $data;


        return $this->renderSuccess(compact('list','delivery_count','notice_count','system_count','message_num'));
    }

    public function getList($type)
    {
        if($type == 'system'){
            return MessageModel::where('type',$type)->order('id','desc')->paginate(15,false,[
                'query' => Request::instance()->request()
            ]);
        }else{
            return MessageModel::where('type',$type)->where('user_id',$this->user['user_id'])->order('id','desc')->paginate(15,false,[
                'query' => Request::instance()->request()
            ]);
        }

    }

    private function getCount($type)
    {
        if($type == 'system'){
            $count = MessageModel::where('type',$type)->count();
            $yd_count = Db::name('message_user')->where('user_id',$this->user['user_id'])->count();
            return $count-$yd_count;
        }else{
            return MessageModel::where('type',$type)->where('user_id',$this->user['user_id'])->where('status',10)->count();
        }
    }
}