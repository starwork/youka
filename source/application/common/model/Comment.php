<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-08 11:56
 */

namespace app\common\model;


use think\Request;

class Comment extends BaseModel
{
    protected $name = 'comment';

    /**
     * 关联用户
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
    }

    /**
     * 关联用户
     * @return \think\model\relation\BelongsTo
     */
    public function goods()
    {
        return $this->belongsTo('Goods');
    }

    /**
     * 计费方式
     * @param $value
     * @return mixed
     */
    public function getStatusAttr($value)
    {
        $status = [10 => '显示', 20 => '隐藏'];
        return ['text' => $status[$value], 'value' => $value];
    }

    public function getList($uid = 0,$goods_id = 0,$status = 0,$sortType = 'all')
    {
        // 筛选条件
        $filter = [];
        $uid > 0 && $filter['user_id'] = $uid;
        $goods_id > 0 && $filter['goods_id'] = $goods_id;
        $status > 0 && $filter['status'] = $status;

        // 排序规则
        $sort = [];
        if ($sortType === 'all') {
            $sort = ['comment_id' => 'desc'];
        }
        $list =$this->with(['goods.image.file', 'user'])
                    ->where('is_delete', '=', 0)
                    ->where($filter)
                    ->order($sort)
                    ->paginate(15,false,[
                        'query' =>  Request::instance()->request()
                    ]);
        return $list;
    }
}