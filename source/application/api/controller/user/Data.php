<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-15 10:23
 */

namespace app\api\controller\user;


use app\api\controller\Controller;
use app\api\model\User;
use app\common\library\Time;
use app\common\model\Order as OrderModel;
use app\api\model\User as UserModel;
use app\common\model\PriceLog;
use think\Db;
use think\Request;

/**
 * 用户数据
 * Class Data
 * @package app\api\controller\user
 */
class Data extends Controller
{
    protected $user;

    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();   // 用户信息
    }

    public function getUserInfo($user_id)
    {
        $model = new UserModel();
        $user = $model->find($user_id);
        if(!$user){
            return $this->renderError('用户不存在');
        }
        if($user['pid'] != $this->user['user_id'] && $user['ppid'] != $this->user['user_id']){
            return $this->renderError('用户不存在');
        }
        $user['pparent'] = $model->where('user_id',$user['ppid'])->value('nickName');
        $user['count'] = $model->where('pid',$user['user_id'])->count();
        return $this->renderSuccess(compact('user'));
    }


    /**
     * 数据统计
     * @return array
     * @throws \think\Exception
     */
    public function index()
    {
        list($toady_start,$today_end) = Time::Today();
        list($yesterday_start,$yesterday_end) = Time::YesterDay();
        list($month_start,$month_end) = Time::Month();
        list($last_month_start,$last_month_end) = Time::LastMonth();
        $month_user_count = $this->user_count($month_start,$month_end);
        $month_total = $this->price_total($month_start,$month_end);
        $month_order_total = $this->order_total($month_start,$month_end);
        $month_child_total = $this->child_price_total($month_start,$month_end);

        $today_user_count = $this->user_count($toady_start,$today_end);
        $yesterday_user_count = $this->user_count($yesterday_start,$yesterday_end);
        $last_month_user_count = $this->user_count($last_month_start,$last_month_end);

        $user_list = $this->getPriceList($month_start,$month_end);
        $data = compact('month_user_count','month_total','month_order_total','month_child_total','today_user_count','yesterday_user_count','last_month_user_count','user_list');
        return $this->renderSuccess($data);
    }

    /**
     * 运营面板
     */
    public function panel()
    {
        list($start,$end) = Time::Today();
        $toady_user_count = $this->user_count($start,$end);
        $toady_total = $this->price_total($start,$end);
        $toady_child_total = $this->child_price_total($start,$end);

        $order_count = $this->order_count($start,$end);
        $order_child_count = $this->child_order_count($start,$end);
        return $this->renderSuccess(compact('toady_user_count','toady_total','toady_child_total','order_count','order_child_count'));
    }

    /**
     * 团队管理
     * @param int $type
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function team($nickname = '')
    {
        list($toady_start,$today_end) = Time::Today();
        list($yesterday_start,$yesterday_end) = Time::YesterDay();
        list($month_start,$month_end) = Time::Month();
        list($last_month_start,$last_month_end) = Time::LastMonth();

        $today_user_count = $this->user_count($toady_start,$today_end);
        $month_user_count = $this->user_count($month_start,$month_end);
        $yesterday_user_count = $this->user_count($yesterday_start,$yesterday_end);
        $last_month__user_count = $this->user_count($last_month_start,$last_month_end);
        $list = [];
        $list[10] = $this->getUserList(10,$nickname);
        $list[20] = $this->getUserList(20,$nickname);
        $list[30] = $this->getUserList(30,$nickname);
        $all_list = [];
        $all_list[10] = $this->getChildUser(10,$nickname);
        $all_list[20] = $this->getChildUser(20,$nickname);
        $all_list[30] = $this->getChildUser(30,$nickname);
        return $this->renderSuccess(compact('today_user_count','month_user_count','yesterday_user_count','last_month__user_count','list','all_list'));
    }

    /**
     * 团队业绩
     * @param string $dataType
     * @return array
     */
    public function teamPrice()
    {
        $today = $this->team_price('today');
        $week = $this->team_price('week');
        $month = $this->team_price('month');
        $all = $this->team_price('all');
        return $this->renderSuccess(compact('today','week','month','all'));
    }


    private function team_price($dataType = 'today')
    {
        switch ($dataType){
            case 'today':
                list($start,$end) = Time::Today();
                break;
            case 'week':
                list($start,$end) = Time::Week();
                break;
            case 'month':
                list($start,$end) = Time::Month();
                break;
            case 'all':
                $start = 0;
                $end = time();
                break;
        }
        $list = $this->getPriceList($start,$end);
        return $list;
    }


    /**
     * 新增直属会员
     * @param $start
     * @param $end
     * @return array
     * @throws \think\Exception
     */
    private function user_count($start,$end)
    {
        $user_count = Db::name('user')->where('pid',$this->user['user_id'])->where('create_time','BETWEEN',[$start,$end])->count();
        return $user_count;
    }


    /**
     * 业绩统计
     * @param $start
     * @param $end
     * @return array
     */
    private function price_total($start,$end)
    {
        $total = Db::name('price_log')->where('user_id',$this->user['user_id'])->where('price','>',0)->where('create_time','BETWEEN',[$start,$end])->sum('price');
        return $total;
    }

    /**
     * 下级业绩
     * @param $start
     * @param $end
     * @return float|int
     */
    private function child_price_total($start,$end)
    {
        $total = Db::name('price_log')->alias('pl')->join('user u','pl.user_id = u.user_id')-> where('u.pid',$this->user['user_id'])->where('pl.price','>',0)->where('pl.create_time','BETWEEN',[$start,$end])->sum('pl.price');
        return $total;
    }

    /**
     * 订单数
     * @param $start
     * @param $end
     * @return int|string
     * @throws \think\Exception
     */
    public function order_count($start,$end)
    {
        $count =  Db::name('order')->where('user_id',$this->user['user_id'])->where('create_time','BETWEEN',[$start,$end])->count();
        return $count;
    }

    /**
     * 下级订单数
     * @param $start
     * @param $end
     * @return int|string
     * @throws \think\Exception
     */
    private function child_order_count($start,$end)
    {
        $count =  Db::name('order')->alias('o')->join('user u','o.user_id = u.user_id')-> where('u.pid',$this->user['user_id'])->where('o.create_time','BETWEEN',[$start,$end])->count();
        return $count;
    }

    /**
     * 订单总价格
     * @param $start
     * @param $end
     * @return float|int
     */
    private function order_total($start,$end)
    {
        $total =  Db::name('order')->where('user_id',$this->user['user_id'])->where('create_time','BETWEEN',[$start,$end])->sum('total_price');
        return $total;
    }

    private function getPriceList($start,$end)
    {
        $price_model = new PriceLog();
        $list = $price_model
            ->alias('p')
            ->join('user u','p.user_id = u.user_id')
            ->where('u.level','>',0)
            ->where(function ($query){
                $query->where('u.pid',$this->user['user_id'])->whereOr('u.ppid',$this->user['user_id']);
            })
            ->where('p.create_time','BETWEEN',[$start,$end])
            ->field('p.user_id,sum(p.price) as total')
            ->group('p.user_id')
            ->order('total','desc')
            ->select();
        foreach ($list as $k => $v){
            $user = User::get($v['user_id']);
            $list[$k]['nickName'] = $user['nickName'];
            $list[$k]['avatarUrl'] = $user['avatarUrl'];
            $list[$k]['level'] = $user['level'];
        }
        return $list;
    }

    /**
     * 获取直属下级
     * @param $level
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getUserList($level,$nickname)
    {
        if($nickname){
            $list = User::where('pid',$this->user['user_id'])
                ->where('level',$level)
                ->where('nickName','like','%'.$nickname.'%')
                ->select();
        }else{
            $list = User::where('pid',$this->user['user_id'])
                ->where('level',$level)
                ->select();
        }

        return $list;
    }


    /**
     * 团队成员
     * @param $level
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getChildUser($level,$nickname)
    {
        if($nickname){
            $list = User::where(function ($query){
                $query->where('pid',$this->user['user_id'])->whereOr('ppid',$this->user['user_id']);
            })
                ->where('level',$level)
                ->where('nickName','like','%'.$nickname.'%')
                ->select();
        }else{
            $list = User::where(function ($query){
                $query->where('pid',$this->user['user_id'])->whereOr('ppid',$this->user['user_id']);
            })
                ->where('level',$level)
                ->select();
        }

        return $list;
    }


    /**
     *  获取直属下级数量
     * @param $user
     * @return int|string
     * @throws \think\Exception
     */
    private function getUserCount($user)
    {
        $count = User::where('pid',$user['user_id'])->count();
        return $count;
    }

    /**
     * 获取团队成员数量
     * @param $user
     * @return int|string
     * @throws \think\Exception
     */
    private function getChildUserCount($user)
    {
        $count = User::where('pid',$user['user_id'])->count();
        $p_count = User::where('ppid',$user['user_id'])->count();
        return $count+$p_count;
    }
}