<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-08 14:47
 */

namespace app\store\model;


use think\Model;
use think\Request;

class StoreGroup extends Model
{
    public function getList($filter= [])
    {
        $id = $this->getPk();
        return $this
            ->where($filter)
            ->order($id, 'desc')
            ->paginate(15, false, [
                'query' => Request::instance()->request()
            ]);
    }

    public function add($data)
    {
        if(is_array($data['rules'])){
            $data['rules'] = implode(',',$data['rules']);
        }else{
            $data['rules'] = '';
        }
        return $this->allowField(true)->save($data);
    }

    public function edit($data)
    {
        if(is_array($data['rules'])){
            $data['rules'] = implode(',',$data['rules']);
        }else{
            $data['rules'] = '';
        }
        return $this->allowField(true)->save($data);
    }

    /**
     * 删除
     * @param $category_id
     * @return bool|int
     */
    public function remove($id)
    {
        return self::get($id)->delete();
    }
}