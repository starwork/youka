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