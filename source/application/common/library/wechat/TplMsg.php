<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-06-12 14:10
 */

namespace app\common\library\wechat;


use app\common\model\Setting as SettingModel;

class TplMsg
{
    public $app;
    public $tmpl;

    public function __construct()
    {
        $config = SettingModel::getItem('wechat');
        $this->app = new WeChat($config);
        $this->tmpl = SettingModel::getItem('tplmsg',10001);
    }

    public function SendTmpl($openid,$dataType,$order)
    {
        if(isset($this->tmpl[$dataType]) && $this->tmpl[$dataType]['is_enable'] == 1){
            $template_code = $this->tmpl[$dataType]['template_code'];
            if($template_code){
                switch ($dataType){
                    case 'order_pay':   //支付成功
                        $data = [
                            'first' => '订单'.$order['order_no'].'支付成功',
                            'keyword1' => $order['order_no'],
                            'keyword2' => date('Y年m月d日 H:i:s'),
                            'keyword3' => $order['pay_price'],
                            'keyword4' => '微信支付',
                            'remark' => '感谢您的惠顾！'
                        ];
                        break;
                    case 'delivery':    //订单发货
                        $address = implode('',$order['address']['region']).' '.$order['address']['detail'];
                        $data = [
                            'first' => '您购买的订单已经发货啦，正快马加鞭向您飞奔而去。',
                            'keyword1' => $order['order_no'],
                            'keyword2' => date('Y年m月d日 H:i:s'),
                            'keyword3' => $order['express_company'],
                            'keyword4' => $order['express_no'],
                            'keyword5' => $address,
                            'remark' => '请保持收件手机畅通！'
                        ];
                        break;
                    case 'yongjin':    //佣金
                        $data = [
                            'first' => $order['first'],
                            'keyword1' => $order['price'],
                            'keyword2' => date('Y年m月d日 H:i:s'),
                            'remark' => $order['remark']
                        ];
                        break;
                    case 'level':    //等级
                        $data = [
                            'first' => '会员等级提升通知',
                            'keyword1' => $order['level']['level_name'],
                            'keyword2' => $order['level_last_name'],
                            'keyword3' => date('Y年m月d日 H:i:s'),
                            'remark' => '您的直推数和团队规模已经满足'.$order['level']['level_name'].'的升级条件，系统已自动将您提升为'.$order['level']['level_name'].'。'
                        ];
                        break;
                    case 'pay_bank':    //提现
                        $data = [
                            'first' => '您好。您的提现申请已处理。',
                            'keyword1' => $order['price'],
                            'keyword2' => $order['bank_name'].":".$order['card_no'],
                            'keyword3' => $order['create_time'],
                            'keyword4' => $order['status'],
                            'keyword5' => date('Y-m-d H:i:s'),
                            'remark' => $order['content']
                        ];
                        break;
                    case 'child':    //下级会员
                        $data = [
                            'first' => '有新伙伴加入您的团队',
                            'keyword1' => date('Y-m-d H:i:s'),
                            'keyword2' => $order['nickName'],
                            'keyword3' => isset($order['level']['level_name']) ? $order['level']['level_name'] : '普通会员',
                            'remark' => '恭喜您的团队又壮大啦'
                        ];
                        break;
                }
                $this->send($template_code,$openid,$data);
            }
        }
    }

    private function send($template_code,$openid,$data)
    {
        $this->app->send($template_code,$openid,$data);
    }

}