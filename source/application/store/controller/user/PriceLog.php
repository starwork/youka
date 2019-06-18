<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-13 16:58
 */

namespace app\store\controller\user;


use app\store\controller\Controller;
use app\common\model\PriceLog as PriceLogModel;

class PriceLog extends Controller
{
    public function index($filer=[])
    {
        $user_id = $this->request->param('user_id');
        $user_id > 0 && $filer['user_id'] = $user_id;
        $model = new PriceLogModel();
        $list =  $model->getList($filer);
        return $this->fetch('index',compact('list'));
    }
}