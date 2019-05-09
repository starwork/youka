<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-08 14:45
 */

namespace app\store\model;


use app\common\library\Tree;
use app\store\controller\setting\Cache;
use think\Model;
use think\Request;

class StoreRules extends Model
{
    protected $name = 'store_rules';

    public function getList($filter= [])
    {
        $list = self::order('sort','id')->select();
        $tree = new Tree($list->toArray());
        $list = $tree->get_tree(0);
        return $list;
    }

    public function add($data)
    {
        if($data['pid']){
            $parent = self::get($data['pid']);
            $level = $parent['level'] + 1;
        }else{
            $level = 0;
        }
        $data = array_merge($data,compact('level'));
        return $this->allowField(true)->save($data);
    }

    public function edit($data)
    {
        if($data['pid']){
            $parent = self::get($data['pid']);
            $level = $parent['level'] + 1;
        }else{
            $level = 0;
        }
        $data = array_merge($data,compact('level'));
        return $this->allowField(true)->save($data);
    }

    /**
     * 删除
     * @param $category_id
     * @return bool|int
     */
    public function remove($id)
    {
        // 判断是否存在子分类
        if ((new self)->where(['pid' => $id])->count()) {
            $this->error = '该节点下存在子节点，请先删除';
            return false;
        }
        return self::get($id)->delete();
    }
}