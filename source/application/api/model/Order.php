<?php

namespace app\api\model;

use think\Db;
use app\common\model\Order as OrderModel;
use app\common\exception\BaseException;

/**
 * 订单模型
 * Class Order
 * @package app\api\model
 */
class Order extends OrderModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'update_time'
    ];

    /**
     * 订单确认-立即购买
     * @param User $user
     * @param $goods_id
     * @param $goods_num
     * @param $goods_sku_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function getBuyNow($user, $goods_id, $goods_num, $goods_sku_id,$address_id)
    {
        // 商品信息
        /* @var Goods $goods */
        $goods = Goods::detail($goods_id);
        // 判断商品是否下架
        if ($goods['goods_status']['value'] != 10) {
            $this->setError('很抱歉，商品信息不存在或已下架');
        }
        // 商品sku信息
        $goods['goods_sku'] = $goods->getGoodsSku($goods_sku_id);
        // 判断商品库存
        if ($goods_num > $goods['goods_sku']['stock_num']) {
            $this->setError('很抱歉，商品库存不足');
        }
        // 商品单价
        $goods['goods_price'] = $goods['goods_sku']['goods_price'];
        // 商品总价
        $goods['total_num'] = $goods_num;
        $goods['total_price'] = $totalPrice = bcmul($goods['goods_price'], $goods_num, 2);
        // 商品总重量
        $goods_total_weight = bcmul($goods['goods_sku']['goods_weight'], $goods_num, 2);
        // 当前用户收货城市id
        $address = UserAddress::where('user_id',$user['user_id'])->where('address_id',$address_id)->find();
        $cityId = $address ? $address['city_id'] : null;
        // 是否存在收货地址
        $exist_address = !$user['address']->isEmpty();
        // 验证用户收货地址是否存在运费规则中
        if (!$intraRegion = $goods['delivery']->checkAddress($cityId)) {
            $exist_address && $this->setError('很抱歉，您的收货地址不在配送范围内');
        }
        // 计算配送费用
        $expressPrice = $intraRegion ?
            $goods['delivery']->calcTotalFee($goods_num, $goods_total_weight, $cityId) : 0;
        //用户是否复购
        $filer = [];
        $filer['user_id'] = $user['user_id'];
        $filer['pay_status'] = 20;
        $count = self::where($filer)->count();
        $tags = [];
        //复购
        if($count > 0){
            $config = \app\store\model\Setting::getItem('retail');
            $totalPrice = $totalPrice*$config['many_buy']/100;
            $tags[] = '复购';
        }else{
            $tags[] = '首次';
        }
        return [
            'goods_list' => [$goods],               // 商品详情
            'order_total_num' => $goods_num,        // 商品总数量
            'order_total_price' => $totalPrice,    // 商品总金额 (不含运费)
            'order_pay_price' => bcadd($totalPrice, $expressPrice, 2),  // 实际支付金额
            'address' => $user['address_default'],  // 默认地址
            'exist_address' => $exist_address,  // 是否存在收货地址
            'express_price' => $expressPrice,    // 配送费用
            'intra_region' => $intraRegion,    // 当前用户收货城市是否存在配送规则中
            'has_error' => $this->hasError(),
            'error_msg' => $this->getError(),
            'tags' => $tags
        ];
    }

    /**
     * 订单确认-购物车结算
     * @param $user
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCart($user,$address_id)
    {
        $model = new Cart($user['user_id']);
        return $model->getList($user,$address_id);
    }

    /**
     * 新增订单
     * @param $user_id
     * @param $order
     * @return bool
     * @throws \Exception
     */
    public function add($user_id, $order)
    {
        if (empty($order['address'])) {
            $this->error = '请先选择收货地址';
            return false;
        }
        Db::startTrans();
        // 记录订单信息
        $this->save([
            'user_id' => $user_id,
            'wxapp_id' => self::$wxapp_id,
            'order_no' => $this->orderNo(),
            'total_price' => $order['order_total_price'],
            'pay_price' => $order['order_pay_price'],
            'express_price' => $order['express_price'],
        ]);
        // 订单商品列表
        $goodsList = [];
        // 更新商品库存 (下单减库存)
        $deductStockData = [];
        foreach ($order['goods_list'] as $goods) {
            /* @var Goods $goods */
            $goodsList[] = [
                'user_id' => $user_id,
                'wxapp_id' => self::$wxapp_id,
                'goods_id' => $goods['goods_id'],
                'goods_name' => $goods['goods_name'],
                'image_id' => $goods['image'][0]['image_id'],
                'deduct_stock_type' => $goods['deduct_stock_type'],
                'spec_type' => $goods['spec_type'],
                'spec_sku_id' => $goods['goods_sku']['spec_sku_id'],
                'goods_spec_id' => $goods['goods_sku']['goods_spec_id'],
                'goods_attr' => $goods['goods_sku']['goods_attr'],
                'content' => $goods['content'],
                'goods_no' => $goods['goods_sku']['goods_no'],
                'goods_price' => $goods['goods_sku']['goods_price'],
                'line_price' => $goods['goods_sku']['line_price'],
                'goods_weight' => $goods['goods_sku']['goods_weight'],
                'total_num' => $goods['total_num'],
                'total_price' => $goods['total_price'],
            ];
            // 下单减库存
            $goods['deduct_stock_type'] == 10 && $deductStockData[] = [
                'goods_spec_id' => $goods['goods_sku']['goods_spec_id'],
                'stock_num' => ['dec', $goods['total_num']]
            ];
        }
        // 保存订单商品信息
        $this->goods()->saveAll($goodsList);
        // 更新商品库存
        !empty($deductStockData) && (new GoodsSpec)->isUpdate()->saveAll($deductStockData);
        // 记录收货地址
        $this->address()->save([
            'user_id' => $user_id,
            'wxapp_id' => self::$wxapp_id,
            'name' => $order['address']['name'],
            'phone' => $order['address']['phone'],
            'province_id' => $order['address']['province_id'],
            'city_id' => $order['address']['city_id'],
            'region_id' => $order['address']['region_id'],
            'detail' => $order['address']['detail'],
        ]);
        Db::commit();
        return true;
    }

    /**
     * 用户中心订单列表
     * @param $user_id
     * @param string $type
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($user_id, $type = 'all')
    {
        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'payment';
                $filter['pay_status'] = 10;
                break;
            case 'delivery';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 10;
                break;
            case 'received';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 10;
                break;
            case 'comment';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 20;
                $filter['comment_status'] = 20;
                break;
        }
        return $this->with(['goods.image'])
            ->field('order_id,order_no,pay_status,total_price,express_price,delivery_status,receipt_status,comment_status')
            ->where('user_id', '=', $user_id)
            ->where('order_status', '<>', 20)
            ->where($filter)
            ->order(['create_time' => 'desc'])
            ->select();
    }


    /**
     * 下级订单
     * @param $user_id
     * @param string $type
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getChildList($user_id, $type = 'all')
    {
        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'payment';
                $filter['pay_status'] = 10;
                break;
            case 'delivery';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 10;
                break;
            case 'received';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 10;
                break;
            case 'comment';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 20;
                $filter['comment_status'] = 20;
                break;
        }
        return $this->with(['goods.image'])
            ->alias('o')
            ->join('user u','o.user_id = u.user_id')
            ->where('u.pid', '=', $user_id)
            ->where('order_status', '<>', 20)
            ->where($filter)
            ->order(['create_time' => 'desc'])
            ->select();
    }

    /**
     * 取消订单
     * @return bool|false|int
     * @throws \Exception
     */
    public function cancel()
    {
        if ($this['pay_status']['value'] == 20) {
            $this->error = '已付款订单不可取消';
            return false;
        }
        // 回退商品库存
        $this->backGoodsStock($this['goods']);
        return $this->save(['order_status' => 20]);
    }

    /**
     * 回退商品库存
     * @param $goodsList
     * @return array|false
     * @throws \Exception
     */
    private function backGoodsStock(&$goodsList)
    {
        $goodsSpecSave = [];
        foreach ($goodsList as $goods) {
            // 下单减库存
            if ($goods['deduct_stock_type'] == 10) {
                $goodsSpecSave[] = [
                    'goods_spec_id' => $goods['goods_spec_id'],
                    'stock_num' => ['inc', $goods['total_num']]
                ];
            }
        }
        // 更新商品规格库存
        return !empty($goodsSpecSave) && (new GoodsSpec)->isUpdate()->saveAll($goodsSpecSave);
    }

    /**
     * 确认收货
     * @return bool|false|int
     */
    public function receipt()
    {
        if ($this['delivery_status']['value'] == 10 || $this['receipt_status']['value'] == 20) {
            $this->error = '该订单不合法';
            return false;
        }
        if($this['user']['pid'] == 0){
            return $this->save([
                'receipt_status' => 20,
                'receipt_time' => time(),
                'order_status' => 30
            ]);
        }else{
            $data = $this->getReward($this);
            // 开启事务
            Db::startTrans();
            try {
                $parent = $data['parent'];
                $pparent = $data['pparent'];
                $agent = $data['agent'];
                $city_agent = $data['city_agent'];
                if($parent){
                    Db::name('price_log')->insert([
                        'user_id' => $parent['user_id'],
                        'price' => $parent['price'],
                        'text' => $parent['text'],
                        'order_id' => $this['order_id'],
                        'create_time' => time()
                    ]);
                    Db::name('user')->where('user_id',$parent['user_id'])->setInc('price',$parent['price']);
                }
                if($pparent){
                    Db::name('price_log')->insert([
                        'user_id' => $pparent['user_id'],
                        'price' => $pparent['price'],
                        'text' => $pparent['text'],
                        'order_id' => $this['order_id'],
                        'create_time' => time()
                    ]);
                    Db::name('user')->where('user_id',$pparent['user_id'])->setInc('price',$pparent['price']);
                }
                if($agent){
                    foreach ($agent as $v){
                        Db::name('price_log')->insert([
                            'user_id' => $v['user_id'],
                            'price' => $v['price'],
                            'text' => $v['text'],
                            'order_id' => $this['order_id'],
                            'create_time' => time()
                        ]);
                        Db::name('user')->where('user_id',$v['user_id'])->setInc('price',$v['price']);
                    }
                }

                if($city_agent){
                    foreach ($city_agent as $vv){
                        Db::name('price_log')->insert([
                            'user_id' => $vv['user_id'],
                            'price' => $vv['price'],
                            'text' => $vv['text'],
                            'order_id' => $this['order_id'],
                            'create_time' => time()
                        ]);
                        Db::name('user')->where('user_id',$vv['user_id'])->setInc('price',$vv['price']);
                    }
                }
                $this->save([
                    'receipt_status' => 20,
                    'receipt_time' => time(),
                    'order_status' => 30
                ]);
                Db::commit();
                return true;
            } catch (\Exception $e) {
                Db::rollback();
            }
            return false;
        }
    }

    private function addPiceLog($data)
    {
        Db::name('price_log')->insert($data);
    }


    private function getReward($order)
    {
        $config = \app\store\model\Setting::getItem('retail');

        $parent = User::get($order['user']['pid']);  //一级分销商
        $pparent = User::get($order['user']['ppid']);    //二级分销商
        $parent_order_count = Db::name('order')->alias('o')->join('user u','o.user_id = u.user_id')->where('u.pid',$parent['user_id'])->where('order_status',30)->count();
        if($parent_order_count == 0){
            $parent['price'] = $order['total_price']*$config['first_money']/100;
            $parent['text'] = '直推奖';
        }else{
            $parent_level = $parent['level']['level'];
            if(!empty($config[$parent_level]['first_money'])){
                $parent['price'] = $order['total_price']*$config[$parent_level]['first_money']/100;
                $parent['text'] = '一级佣金';
            }
        }
        $pparent_level = $pparent['level']['level'];
        if(!empty($config[$pparent_level]['second_money'])){
            $pparent['price'] = $order['total_price']*$config[$pparent_level]['second_money']/100;
            $pparent['text'] = '二级佣金';
        }
        $pids = explode(',',$order['user']['path']);
        //市场维护奖
        //经销商
        $agent = User::where('user_id','in',$pids)->where('level',20)->order('id','desc')->limit(2);
        if(is_array($agent)){
            if($agent[0]){
                $agent[0]['price'] = $order['total_price']*$config[20]['maintain']['diect']/100;
                $agent[0]['text'] = '经销商维护奖';
            }
            if($agent[1]){
                $agent[1]['price'] = $order['total_price']*$config[20]['maintain']['indirect']/100;
                $agent[1]['text'] = '经销商维护奖';
            }
        }
        //市级代理
        $city_agent = User::where('user_id','in',$pids)->where('level',30)->order('id','desc')->limit(2);
        if(is_array($city_agent)){
            if($city_agent[0]){
                $city_agent[0]['price'] = $order['total_price']*$config[30]['maintain']['diect']/100;
                $city_agent[0]['text'] = '市级代理维护奖';
            }
            if($city_agent[1]){
                $city_agent[1]['price'] = $order['total_price']*$config[30]['maintain']['indirect']/100;
                $city_agent[1]['text'] = '市级代理维护奖';
            }
        }
        $data = [
            'parent' => $parent,
            'pparent' => $pparent,
            'agent' => $agent,
            'city_agent' => $city_agent,
        ];
        return $data;
    }

    /**
     * 获取订单总数
     * @param $user_id
     * @param string $type
     * @return int|string
     */
    public function getCount($user_id, $type = 'all')
    {
        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'payment';
                $filter['pay_status'] = 10;
                break;
            case 'delivery';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 10;
                break;
            case 'received';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 10;
            case 'comment';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 20;
                $filter['comment_status'] = 10;
                break;
        }
        return $this->where('user_id', '=', $user_id)
            ->where('order_status', '<>', 20)
            ->where($filter)
            ->count();
    }

    /**
     * 订单详情
     * @param $order_id
     * @param null $user_id
     * @return null|static
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    public static function getUserOrderDetail($order_id, $user_id)
    {
        if (!$order = self::get([
            'order_id' => $order_id,
            'user_id' => $user_id,
            'order_status' => ['<>', 20]
        ], ['goods' => ['image', 'spec', 'goods'], 'address'])) {
            throw new BaseException(['msg' => '订单不存在']);
        }
        return $order;
    }

    /**
     * 判断商品库存不足 (未付款订单)
     * @param $goodsList
     * @return bool
     */
    public function checkGoodsStatusFromOrder(&$goodsList)
    {
        foreach ($goodsList as $goods) {
            // 判断商品是否下架
            if ($goods['goods']['goods_status']['value'] != 10) {
                $this->setError('很抱歉，商品 [' . $goods['goods_name'] . '] 已下架');
                return false;
            }
            // 付款减库存
            if ($goods['deduct_stock_type'] == 20 && $goods['spec']['stock_num'] < 1) {
                $this->setError('很抱歉，商品 [' . $goods['goods_name'] . '] 库存不足');
                return false;
            }
        }
        return true;
    }

    /**
     * 设置错误信息
     * @param $error
     */
    private function setError($error)
    {
        empty($this->error) && $this->error = $error;
    }

    /**
     * 是否存在错误
     * @return bool
     */
    public function hasError()
    {
        return !empty($this->error);
    }

}
