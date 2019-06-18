<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-11 16:38
 */

namespace app\store\controller\user;

use app\store\controller\Controller;
use app\common\model\Message as MessageModel;

class Message extends Controller
{
    public function index($uid = 0,$dataType = 'all')
    {
        $filer = [];
        $uid > 0 && $filer['uid'] = $uid;
        $types = ['system','delivery','notice'];
        if(in_array($dataType,$types)){
            $filer['type'] = $dataType;
        }
        $model = new MessageModel();
        $list = $model->getList($filer);
        return $this->fetch('index',compact('list'));
    }

    public function send()
    {
        $model = new MessageModel();
        $type_arr = $model->type_arr;
        if($this->request->isAjax()){
            $data = $this->postData('message');
            if(!in_array($data['type'],array_keys($type_arr))){
                return $this->renderError('类型不正确');
            }
            if($data['type'] == 'notice'){
                $uids = explode('|',$data['uids']);
                if(empty($uids)){
                    return $this->renderError('输入发送会员');
                }
                foreach ($uids as $uid){
                    $save_data = [
                        'user_id' => $uid,
                        'title' => $data['title'],
                        'type' => $data['type'],
                        'content' => $data['content']
                    ];
                    $model->add($save_data);
                }
                return $this->renderSuccess('发送成功');
            }else{
                $model->add($data);
                return $this->renderSuccess('发送成功');
            }
        }

        return $this->fetch('send',compact('type_arr'));
    }


    public function add()
    {
        if($this->request->isAjax()){
            $model = new MessageModel();
            if(!$model->add($this->postData('message'))){
                $error = $model->getError() ? $model->getError() : '添加失败';
                return $this->renderError($error);
            }
            return $this->renderSuccess('添加成功');
        }
        return $this->fetch();
    }

    public function delete($id)
    {
        $model = MessageModel::get($id);
        if(!$model->remove()){
            $error = $model->getError() ? $model->getError() : '删除失败';
            return $this->renderError($error);
        }
        return $this->renderSuccess('删除成功');
    }
}