<?php

namespace app\api\controller;

use app\api\model\Banner;
use app\api\model\UploadFile;
use app\api\model\WxappPage;
use app\api\model\Goods as GoodsModel;
use app\common\exception\BaseException;
use app\common\library\delivery\KdNiao;
use app\api\model\Setting as SettingModel;
use app\api\model\Category as CategoryModel;
use EasyWeChat\Factory;

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

    public function getNum()
    {
        $data = [
            'cart_num' => $this->cart_num,
            'message_num' => $this->message_num,
        ];
        return $this->renderSuccess($data);
    }

    public function index()
    {
        $user = $this->getUserInfoNotError();
        if($user){
            if(!$user['subscribe']){
                throw new BaseException(['code' => -10, 'msg' => '关注公众号']);
            }
            if($user['phone'] == ''){
                throw new BaseException(['code' => -3, 'msg' => '绑定手机号']);
            }
        }
        $store = SettingModel::getItem('store');
        $category = [];
        $banner = '';
        $banner = (new Banner())->getList();
        $category[0] = !empty($store['category'][0]) ? $this->getCategoryList($store['category'][0]) : [];
        $category[1] = !empty($store['category'][1]) ? $this->getCategoryList($store['category'][1]) : [];
        return $this->renderSuccess(compact('banner','banner1','category'));
    }

    private function getCategoryList($category_id)
    {
        $category = CategoryModel::get($category_id);
        $model = new \app\api\model\Goods();
        $category['list'] = $model->getList(10,$category_id)->items();
        return $category;
    }

    public function WxConfig($url)
    {
        $wx_config = SettingModel::getItem('wechat');
        $config = [
            'app_id' => $wx_config['app_id'],
            'secret' => $wx_config['app_secret']
        ];
        $app = Factory::officialAccount($config);
      	$app->jssdk->setUrl($url);
        $config = $app->jssdk->buildConfig(['chooseWXPay','updateAppMessageShareData','updateTimelineShareData','onMenuShareAppMessage','onMenuShareTimeline'], false,false, false);
        return $this->renderSuccess($config);
    }

    public function getPrivate()
    {
        $data = SettingModel::getItem('privacy');
        $content = html_entity_decode($data['content']);
        return $this->renderSuccess(compact('content'));
    }

    public function getServer()
    {
        $data = SettingModel::getItem('server');
        $content = html_entity_decode($data['content']);
        return $this->renderSuccess(compact('content'));
    }
}
