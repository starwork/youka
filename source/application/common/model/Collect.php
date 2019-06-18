<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-11 17:13
 */

namespace app\common\model;


use think\Request;

class Collect extends BaseModel
{
    protected $name = 'collect';
    protected $updateTime = false;

    protected $append = [
      'price'
    ];

    public function getPriceAttr($value,$data)
    {
        $price = (new GoodsSpec())->where('goods_id',$data['goods_id'])->min('goods_price');
        return $price;
    }

    public function goods()
    {
        return $this->belongsTo('Goods');
    }
    public function getList($filder)
    {
        return $this->with('goods.image.file')->where($filder)->paginate(15,false,[
            'query' => Request::instance()->request()
        ]);
    }

    public function add($user,$data)
    {
        $data['user_id'] = $user['user_id'];
        return $this->allowField(true)->save($data);
    }

    public function remove()
    {
        return $this->delete();
    }
}