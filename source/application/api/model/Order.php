<?php

namespace app\api\model;

use app\common\model\PriceLog;
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

    protected $append = ['goods_num'];

    public function getGoodsNumAttr($value,$data)
    {
        return (new OrderGoods())->where('order_id',$data['order_id'])->sum('total_num');
    }

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
            $discount = [
                $config['many_msg'] => $totalPrice*(100-$config['many_buy'])/100
            ];
        }else{
            $tags[] = '首次';
            $discount_msg = '';
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
            'discount_msg' => $discount_msg,
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

    public function getOrderList($user,$data,$address_id)
    {
        // 商品列表
        $goodsList = [];
        $goodsIds = array_unique(array_column($data, 'goods_id'));
        foreach ((new Goods())->getListByIds($goodsIds) as $goods) {
            $goodsList[$goods['goods_id']] = $goods;
        }
        // 当前用户收货城市id
        $address = UserAddress::where('user_id',$user['user_id'])->where('address_id',$address_id)->find();
        if($address){
            $cityId = $address['city_id'];
            $user['address_default'] = $address;
        }else{
            $cityId = $user['address_default'] ? $user['address_default']['city_id'] : null;
        }
        // 是否存在收货地址
        $exist_address = !$user['address']->isEmpty();
        // 商品是否在配送范围
        $intraRegion = true;
        // 购物车商品列表
        $cartList = [];
        foreach ($data as $key => $cart) {
            // 判断商品不存在则自动删除
            if (!isset($goodsList[$cart['goods_id']])) {
                $this->delete($cart['goods_id'], $cart['goods_sku_id']);
                continue;
            }
            /* @var Goods $goods */
            $goods = $goodsList[$cart['goods_id']];
            // 商品sku信息
            $goods['goods_sku_id'] = $cart['goods_sku_id'];
            // 商品sku不存在则自动删除
            if (!$goods['goods_sku'] = $goods->getGoodsSku($cart['goods_sku_id'])) {
                $this->delete($cart['goods_id'], $cart['goods_sku_id']);
                continue;
            }
            // 判断商品是否下架
            if ($goods['goods_status']['value'] != 10) {
                $this->setError('很抱歉，商品 [' . $goods['goods_name'] . '] 已下架');
            }
            // 判断商品库存
            if ($cart['goods_num'] > $goods['goods_sku']['stock_num']) {
                $this->setError('很抱歉，商品 [' . $goods['goods_name'] . '] 库存不足');
            }
            // 商品单价
            $goods['goods_price'] = $goods['goods_sku']['goods_price'];
            // 商品总价
            $goods['total_num'] = $cart['goods_num'];
            $goods['total_price'] = $total_price = bcmul($goods['goods_price'], $cart['goods_num'], 2);
            // 商品总重量
            $goods['goods_total_weight'] = bcmul($goods['goods_sku']['goods_weight'], $cart['goods_num'], 2);
            // 验证用户收货地址是否存在运费规则中
            if ($intraRegion = $goods['delivery']->checkAddress($cityId)) {
                $goods['express_price'] = $goods['delivery']->calcTotalFee(
                    $cart['goods_num'], $goods['goods_total_weight'], $cityId);
            } else {
                $exist_address && $this->setError("很抱歉，您的收货地址不在商品 [{$goods['goods_name']}] 的配送范围内");
            }
            $cartList[] = $goods->toArray();
        }
        // 商品总金额
        $orderTotalPrice = array_sum(array_column($cartList, 'total_price'));
        // 所有商品的运费金额
        $allExpressPrice = array_column($cartList, 'express_price');
        // 订单总运费金额
        $expressPrice = $allExpressPrice ? Delivery::freightRule($allExpressPrice) : 0.00;

        //用户是否复购
        $filer = [];
        $filer['user_id'] = $user['user_id'];
        $filer['pay_status'] = 20;
        $count = self::where($filer)->count();
        $tags = [];
        $goodsTotalPrice = $orderTotalPrice;
        $discount_price = 0;    //优惠金额
        //复购
        if($count > 0){
            $config = \app\store\model\Setting::getItem('retail');
            $orderTotalPrice = $orderTotalPrice*$config['many_buy']/100;
            $tags[] = '复购';
            $discount_msg = $config['many_msg'];
            $discount_price = $goodsTotalPrice - $orderTotalPrice;
        }else{
            $tags[] = '首次';
            $discount_msg = '';
        }

        return [
            'goods_list' => $cartList,                       // 商品列表
            //'order_total_num' => $this->getTotalNum(),       // 商品总数量
            'goods_total_price' => round($goodsTotalPrice, 2),              // 商品总金额 (不含运费)
            'order_total_price' => round($orderTotalPrice, 2),              // 商品总金额 (不含运费)
            'order_pay_price' => bcadd($orderTotalPrice, $expressPrice, 2),    // 实际支付金额
            'address' => $user['address_default'],  // 默认地址
            'exist_address' => $exist_address,      // 是否存在收货地址
            'express_price' => $expressPrice,       // 配送费用
            'intra_region' => $intraRegion,         // 当前用户收货城市是否存在配送规则中
            'discount_msg' => $discount_msg,   //优惠信息
            'discount_price' => $discount_price,   //优惠信息
            'has_error' => $this->hasError(),
            'error_msg' => $this->getError(),
            'address_list' => (new UserAddress())->getList($user['user_id'])
        ];
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
            'remark' => $order['remark'],
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
                $filter['comment_status'] = 10;
                break;
        }
        return $this->with(['goods.image'])
            ->field('order_id,order_no,pay_status,total_price,express_price,delivery_status,receipt_status,comment_status,order_status')
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
        return $this->with(['goods.image','user'])
            ->alias('o')
            ->join('user u','o.user_id = u.user_id')
            ->where('u.pid', '=', $user_id)
            ->where('o.order_status', '<>', 20)
            ->where($filter)
            ->order(['o.create_time' => 'desc'])
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
        return $this->allowField(true)->save(['order_status' => 20]);
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
  
  	public function getPrice($data)
    {
      $parent = User::get($this['user']['pid']);
      $count = Db::name('order')->alias('o')->join('user u','o.user_id = u.user_id')->where('u.pid',$parent['user_id'])->where('pay_status',20)->where('pay_time','<=',$this['pay_time'])->group('u.user_id')->count();
      //$count = $parent_order_count +1;
      $bs = floor($count/5);
      $push = null;
      if($count%5 == 2){
        $parent_order = Db::name('order')->alias('o')->join('user u','o.user_id = u.user_id')->where('u.pid',$parent['user_id'])->where('pay_status',20)->order('pay_time','asc')->limit($bs*5,1)->group('u.user_id')->select();
        $parent_order = isset($parent_order[0]) ? $parent_order[0] : null;
          $is_first = false;
          if($parent_order){
              $is_first = OrderModel::where('user_id',$parent_order['user_id'])->where('pay_time','<',$parent_order['pay_time'])->count() == 0 ? true : false;
          }
        if($parent_order && $is_first){
          $push = PriceLog::where('order_id',$parent_order['order_id'])->where('type',3)->find();
          if(!$push){
            $config = \app\store\model\Setting::getItem('retail');
            $push = [];
            $push['price'] = $config['first_money'];
            $push['text'] = '第'.($bs*5+1).'单，直推奖';
            $push['user_id'] = $parent['user_id'];
            Db::name('price_log')->insert([
              'user_id' => $push['user_id'],
              'price' => $push['price'],
              'text' => $push['text'],
              'type' => 3,
              'order_id' => $parent_order['order_id'],
              'create_time' => time()
            ]);
           $push['id'] = Db::getLastInsID();
          }elseif($push['status'] == 20){
              $push = null;
          }
        }
      }
      // 开启事务
      Db::startTrans();
      try {
        if($push){
          Db::name('user')->where('user_id',$push['user_id'])->setInc('price',$push['price']);
          Db::name('price_log')->where('id',$push['id'])->update(['status' => 20]);
        }
        foreach ($data as $k => $v){
          Db::name('user')->where('user_id',$v['user_id'])->setInc('price',$v['price']);
          Db::name('price_log')->where('id',$v['id'])->update(['status' => 20]);
        }
        $this->allowField(true)->save([
          'receipt_status' => 20,
          'receipt_time' => time(),
          'order_status' => 30
        ]);
        Db::commit();
        return true;
      }catch (\Exception $e) {
        Db::rollback();
        return false;
      }
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
            return $this->allowField(true)->save([
                'receipt_status' => 20,
                'receipt_time' => time(),
                'order_status' => 30
            ]);
        }else{
            if($data = PriceLog::where('order_id',$this['order_id'])->where('status',10)->where('type',1)->select()){
				if($this->getPrice($data)){
                	return true;
                }
            }else{
              $this->getRewardData($this);
              $data = PriceLog::where('order_id',$this['order_id'])->where('type',1)->select();
              if($this->getPrice($data)){
                	return true;
                }
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

        //$parent = User::get($order['user']['pid']);  //一级分销商
        //$pparent = User::get($order['user']['ppid']);    //二级分销商
        //$parent_goods_count = Db::name('order_goods')->alias('og')->join('order o','og.order_id = o.order_id')->join('user u','o.user_id = u.user_id')->where('u.pid',$parent['user_id'])->where('pay_status',20)->where('pay_time',$order['pay_time'])->sum('og.total_num');
        //按照商品数量分销
//        $first = [];
////        $second = [];
////        $agent = [];
////        $city_agent = [];
////        $pids = explode(',',$order['user']['path']);
////        foreach ($order['goods'] as $goods){
////            for ($num = 0;$num < $goods['total_num'];$num++){
////                $count = $parent_goods_count + $num;
////                $first[] = getFirstPrice($config,$goods,$parent,$count);
////                $second[] = getSecondPrice($config,$goods,$pparent);
////                $agent[] = getFistAward($config,$goods,$pids);
////                $city_agent[] = getFistAward($config,$goods,$pids);
////            }
////        }
////        $data = [
////            'parent' => $first,
////            'pparent' => $second,
////            'agent' => $agent,
////            'city_agent' => $city_agent,
////        ];
////        return $data;
////        exit;

        $parent = User::get($order['user']['pid']);  //一级分销商
        $pparent = User::get($order['user']['ppid']);    //二级分销商
        $parent_order_count = Db::name('order')->alias('o')->join('user u','o.user_id = u.user_id')->where('u.pid',$parent['user_id'])->where('pay_status',20)->where('pay_time','<',$order['pay_time'])->count();
        $push = [];
        if($parent){
            $count = $parent_order_count +1 ;
            $bs = floor($count/5);
            $push = null;
            if($count = $bs*5+2){
                $parent_order = Db::name('order')->alias('o')->join('user u','o.user_id = u.user_id')->where('u.pid',$parent['user_id'])->where('pay_status',20)->order('pay_time','asc')->limit($bs*5,1)->select();
                $parent_order = isset($parent_order[0]) ? $parent_order[0] : null;
                if($parent_order){
                    $push = PriceLog::where('order_id',$parent_order['order_id'])->where('type',3)->find();
                }else{
                    $push['price'] = $config['first_money'];
                    $push['text'] = '第'.($parent_order_count).'单，直推奖';
                    $push['order_id'] = $parent_order['order_id'];
                    $push['user_id'] = $parent['user_id'];
                    $push['wu_log'] = 1;
                }
            }
            $parent = $parent->toArray();
            $parent_level = $parent['level']['level'];
            if(!empty($config[$parent_level]['first_money'])){
                $parent['price'] = round($order['total_price']*$config[$parent_level]['first_money']/100,2);
                $parent['text'] = '第'.($parent_order_count+1).'单，一级佣金';
                $parent['is_frozen'] = 0;
                $parent['jd'] = 0;
            }
        }
        $pparent_level = $pparent['level']['level'];
        if(!empty($config[$pparent_level]['second_money']) && $pparent){
            $pparent = $pparent->toArray();
            $pparent['price'] = round($order['total_price']*$config[$pparent_level]['second_money']/100,2);
            $pparent['text'] = '二级佣金';
        }
        $pids = explode(',',$order['user']['path']);
        $pids[] = $order['user']['pid'];
        $pids[] = $order['user']['ppid'];
        $pids = array_unique(array_filter($pids));
	
        //市场维护奖
        //经销商
        $agent = User::where('user_id','in',$pids)->where('level',20)->order('user_id','desc')->limit(2)->select();
        //dump($agent);exit;
        if($agent){
            $agent = $agent->toArray();
            if(isset($agent[0])){
                $agent[0]['price'] = round($order['total_price']*$config[20]['maintain']['diect']/100,2);
                $agent[0]['text'] = '经销商维护奖';
            }
            if(isset($agent[1])){
                $agent[1]['price'] = round($order['total_price']*$config[20]['maintain']['indirect']/100,2);
                $agent[1]['text'] = '经销商维护奖';
            }
        }
        //市级代理
        $city_agent = User::where('user_id','in',$pids)->where('level',30)->order('user_id','desc')->limit(2)->select();
        if($city_agent){
            $city_agent = $city_agent->toArray();
            if(isset($city_agent[0])){
                $city_agent[0]['price'] = round($order['total_price']*$config[30]['maintain']['diect']/100,2);
                $city_agent[0]['text'] = '市级代理维护奖';
            }
            if(isset($city_agent[1])){
                $city_agent[1]['price'] = round($order['total_price']*$config[30]['maintain']['indirect']/100,2);
                $city_agent[1]['text'] = '市级代理维护奖';
            }
        }
        $data = [
            'push' => $push,
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
                break;
            case 'comment';
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 20;
                $filter['order_status'] = 30;
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
            'user_id' => $user_id
        ], ['goods' => ['image', 'spec', 'goods'], 'address'])) {
            throw new BaseException(['msg' => '订单不存在']);
        }
        if($order['order_status']['value'] == 10 && $order['pay_status']['value'] == 10){
            $settingModel = new Setting();
            $config = $settingModel->getItem('trade');
            $desc = '';
            if(!empty($config['order']['close_days'])){
                $end_time = strtotime($order['create_time'])+($config['order']['close_days']*86400);
                $time = $end_time - time();
                if($time > 86400){
                    $desc .= floor($time / 86400).'天';
                    $time = $time % 86400;
                }
                if($time > 3600){
                    $desc .= floor($time / 3600).'小时';
                    $time = $time % 3600;
                }else{
                    if($desc){
                        $desc .= '0小时';
                    }
                }
                if($time > 60){
                    $desc .= floor($time / 60).'分钟';
                }
            }
            $order['desc'] = '剩余'.$desc.'自动关闭';
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
