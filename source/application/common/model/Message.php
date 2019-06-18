<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-11 13:36
 */

namespace app\common\model;


use think\Db;
use think\Request;

class Message extends BaseModel
{
    protected $name = 'message';

    public $type_arr = [
        'system' => '系统消息',
        'notice' => '通知消息',
    ];

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function getStatusAttr($value)
    {
        $status = [10 => '未读', 20 => '已阅读'];
        return ['text' => $status[$value], 'value' => $value];
    }

    public function getList($filter)
    {
        return $this->with('user')->where($filter)->order('id','desc')->paginate(15,false,[
            'query' => Request::instance()->request()
        ]);
    }

    public function add($data)
    {
        return $this->allowField(true)->save($data);
    }

    /**
     *  发送通知
     * @param $title
     * @param $content
     * @param $user_id
     * @return false|int
     */
    public function sendNotice($title,$content,$user_id)
    {
        $data = [
            'title' => $title,
            'content' => $content,
            'user_id' => $user_id,
            'type' => 'notice',
        ];
        return $this->add($data);
    }

    /**
     * 发送系统消息
     * @param $title
     * @param $content
     * @return false|int
     */
    public function sendSystem($title,$content)
    {
        $data = [
            'title' => $title,
            'content' => $content,
            'type' => 'system',
        ];
        return $this->add($data);
    }

    public function read()
    {
        if($this['status'] == 10){
            $this->save([
                'status' => 20
            ]);
        }else{
            $this->error = '消息已阅读';
            return false;
        }
    }

    public function remove()
    {
        if($this['type'] == 'system'){
            Db::name('message_user')->where('message_id',$this['id'])->delete();
        }
        return $this->delete();
    }
}