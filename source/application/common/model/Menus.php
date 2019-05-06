<?php
namespace app\common\model;
use app\store\controller\setting\Cache;

/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-05 17:31
 */
class Menus extends BaseModel
{
    protected $name = 'menus';


    /**
     * 所有分类
     * @return mixed
     */
    public static function getALL()
    {
        $model = new static;
        if (!Cache::get('menus_' . $model::$wxapp_id)) {
            $data = $model->order(['sort' => 'asc'])->select();
            $all = !empty($data) ? $data->toArray() : [];
            $tree = [];
            foreach ($all as $first) {
                if ($first['pid'] != 0) continue;
                $twoTree = [];
                foreach ($all as $two) {
                    if ($two['pid'] != $first['id']) continue;
                    $threeTree = [];
                    foreach ($all as $three)
                        $three['pid'] == $two['id']
                        && $threeTree[$three['id']] = $three;
                    !empty($threeTree) && $two['submenu'] = $threeTree;
                    $twoTree[$two['id']] = $two;
                }
                if (!empty($twoTree)) {
                    array_multisort(array_column($twoTree, 'sort'), SORT_ASC, $twoTree);
                    $first['submenu'] = $twoTree;
                }
                $tree[$first['id']] = $first;
            }
            Cache::set('menu_' . $model::$wxapp_id, compact('all', 'tree'));
        }
        return Cache::get('menu_' . $model::$wxapp_id);
    }

    /**
     * 获取所有分类
     * @return mixed
     */
    public static function getCacheAll()
    {
        return self::getALL()['all'];
    }

    /**
     * 获取所有分类(树状结构)
     * @return mixed
     */
    public static function getCacheTree()
    {
        return self::getALL()['tree'];
    }
}