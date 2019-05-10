<?php

namespace app\api\controller;

use app\api\model\WxappPage;
use app\api\model\Goods as GoodsModel;
use app\common\library\delivery\KdNiao;
use app\api\model\Setting as SettingModel;

/**
 * 首页控制器
 * Class Index
 * @package app\api\controller
 */
class Index extends Controller
{
    /**
     * 首页diy数据
     * @return array
     * @throws \think\exception\DbException
     */
    public function page()
    {
        // 页面元素
        $wxappPage = WxappPage::detail();
        $items = $wxappPage['page_data']['array']['items'];
        // 新品推荐
        $model = new GoodsModel;
        $newest = $model->getNewList();
        // 猜您喜欢
        $best = $model->getBestList();
        return $this->renderSuccess(compact('items', 'newest', 'best'));
    }

    public function index()
    {
        $config = SettingModel::getItem('store', 10001);
        $delivery = new KdNiao($config);
        return $delivery->Search('9895266621537','YZBK');
        $items = [];
        return $this->renderSuccess(compact('items'));
    }
}
