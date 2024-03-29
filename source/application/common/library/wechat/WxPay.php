<?php

namespace app\common\library\wechat;

use app\common\model\PayBank;
use app\common\model\PriceLog;
use app\common\model\User;
use app\common\model\Wxapp as WxappModel;
use app\task\model\Setting as SettingModel;
use app\common\model\User as UserModel;
use app\common\library\sms\Driver as SmsDriver;
use app\common\exception\BaseException;
use think\Db;

/**
 * 微信支付
 * Class WxPay
 * @package app\common\library\wechat
 */
class WxPay
{
    private $config; // 微信支付配置

    /**
     * 构造方法
     * WxPay constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 统一下单API
     * @param $order_no
     * @param $openid
     * @param $total_fee
     * @return array
     * @throws BaseException
     */
    public function unifiedorder($order_no, $openid, $total_fee)
    {
        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time . $openid);
        // API参数
        $params = [
            'appid' => $this->config['app_id'],
            'attach' => 'test',
            'body' => $order_no,
            'mch_id' => $this->config['mchid'],
            'nonce_str' => $nonceStr,
            'notify_url' => base_url() . 'notice.php',  // 异步通知地址
            'openid' => $openid,
            'out_trade_no' => $order_no,
            'spbill_create_ip' => \request()->ip(),
            'total_fee' => $total_fee * 100, // 价格:单位分
            'trade_type' => 'JSAPI',
        ];
        // 生成签名
        $params['sign'] = $this->makeSign($params);
        // 请求API
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $result = $this->postXmlCurl($this->toXml($params), $url);
        $prepay = $this->fromXml($result);
        // 请求失败
        if ($prepay['return_code'] === 'FAIL') {
            throw new BaseException(['msg' => $prepay['return_msg'], 'code' => -10]);
        }
        if ($prepay['result_code'] === 'FAIL') {
            throw new BaseException(['msg' => $prepay['return_msg'], 'code' => -10]);
        }
        // 生成 nonce_str 供前端使用
        $paySign = $this->makePaySign($params['nonce_str'], $prepay['prepay_id'], $time);
        return [
            'prepay_id' => $prepay['prepay_id'],
            'nonceStr' => $nonceStr,
            'timeStamp' => (string)$time,
            'paySign' => $paySign
        ];
    }


    public function pay_bank($user,$price,$bank)
    {
        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time.$user['user_id']);
        //商户企业付款单号
        $partner_trade_no = $this->PayBankNo();
        // API参数
        $params = [
            'mch_id' => $this->config['mchid'],
            'partner_trade_no' => $partner_trade_no,
            'nonce_str' => $nonceStr,
            'enc_bank_no' => $bank['bank_card_no'],
            'enc_true_name' => $bank['bank_name'],
            'bank_code' => $bank['code'],
            'amount' => $price * 100, // 价格:单位分
            'desc' => '提现',
        ];
        // 生成签名
        $params['sign'] = $this->makeSign($params);
        // 请求API
        $url = 'https://api.mch.weixin.qq.com/mmpaysptrans/pay_bank';
        Db::startTrans();
        try{
            User::where('user_id',$user['user_id'])->setDec('price',$price);
            PriceLog::save([
                'user_id' => $user['user_id'],
                'price' => '-'.$price,
                'text' => '提现',
                'order_id' => 0
            ]);
            $pay_bank = PayBank::save([
                'user_id' => $user['user_id'],
                'bank_name' => $bank['bank_name'],
                'card_no' => $bank['bank_card_no'],
                'trade_no' => $partner_trade_no,
            ]);
            $result = $this->postXmlCurl($this->toXml($params), $url);
            $prepay = $this->fromXml($result);
            // 请求失败
            if ($prepay['return_code'] === 'FAIL') {
                throw new BaseException(['msg' => $prepay['return_msg'], 'code' => -10]);
            }
            if ($prepay['result_code'] === 'FAIL') {
                throw new BaseException(['msg' => $prepay['return_msg'], 'code' => -10]);
            }
            Db::commit();
            $pay_bank->save(['payment_no' => $prepay['payment_no'], 'cmms_amt' => $prepay['cmms_amt']]);
            return true;
        }catch (\Exception $e){
            Db::rollback();
            return false;
        }
    }

    /**
     * 生成订单号
     */
    protected function PayBankNo()
    {
        return date('Ymd') . substr(implode(NULL, array_map('bank', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    /**
     * 支付成功异步通知
     * @param \app\task\model\Order $OrderModel
     * @throws BaseException
     * @throws \Exception
     * @throws \think\exception\DbException
     */
    public function notify($OrderModel)
    {
//        $xml = <<<EOF
//<xml><appid><![CDATA[wx62f4cad175ad0f90]]></appid>
//<attach><![CDATA[test]]></attach>
//<bank_type><![CDATA[ICBC_DEBIT]]></bank_type>
//<cash_fee><![CDATA[1]]></cash_fee>
//<fee_type><![CDATA[CNY]]></fee_type>
//<is_subscribe><![CDATA[N]]></is_subscribe>
//<mch_id><![CDATA[1499579162]]></mch_id>
//<nonce_str><![CDATA[963b42d0a71f2d160b3831321808ab79]]></nonce_str>
//<openid><![CDATA[o9coS0eYE8pigBkvSrLfdv49b8k4]]></openid>
//<out_trade_no><![CDATA[2018062448524950]]></out_trade_no>
//<result_code><![CDATA[SUCCESS]]></result_code>
//<return_code><![CDATA[SUCCESS]]></return_code>
//<sign><![CDATA[E252025255D59FE900DAFA4562C4EF5C]]></sign>
//<time_end><![CDATA[20180624122501]]></time_end>
//<total_fee>1</total_fee>
//<trade_type><![CDATA[JSAPI]]></trade_type>
//<transaction_id><![CDATA[4200000146201806242438472701]]></transaction_id>
//</xml>
//EOF;
//        if (!$xml = file_get_contents('php://input')) {
//            $this->returnCode(false, 'Not found DATA');
//        }
        // 将服务器返回的XML数据转化为数组
        //$data = $this->fromXml($xml);
        // 记录日志
        //$this->doLogs($xml);

        //$this->doLogs($data);
        $str = '{"appid":"wx25cea91902de42d5","attach":"test","bank_type":"CFT","cash_fee":"13500","fee_type":"CNY","is_subscribe":"Y","mch_id":"1492616512","nonce_str":"21c84a8f07584c11afc97d98cd1faac4","openid":"oh0jB5_IsKzYaX3LJ78tpqJoVSBM","out_trade_no":"2019061454545010","result_code":"SUCCESS","return_code":"SUCCESS","sign":"FA049AA6B0394F635D0F525EFE8BB89E","time_end":"20190614164526","total_fee":"13500","trade_type":"JSAPI","transaction_id":"4200000316201906144245247926"}';
        $data = json_decode($str,true);
        // 订单信息
        //$order = $OrderModel->payDetail($data['out_trade_no']);
        $order = $OrderModel::get(['order_no' =>$data['out_trade_no']]);
        empty($order) && $this->returnCode(true, '订单不存在');

        // 小程序配置信息
        $wxConfig = SettingModel::getItem('wechat');
        // 设置支付秘钥
        $this->config['apikey'] = $wxConfig['apikey'];
        // 保存微信服务器返回的签名sign
        $dataSign = $data['sign'];
        // sign不参与签名算法
        unset($data['sign']);
        // 生成签名
        $sign = $this->makeSign($data);
        // 判断签名是否正确  判断支付状态
        if (/*($sign === $dataSign)
            && */($data['return_code'] == 'SUCCESS')
            && ($data['result_code'] == 'SUCCESS')) {
            $OrderModel->getRewardData($order);
            // 更新订单状态
            $order->updatePayStatus($data['transaction_id']);
	    
            // 发送短信通知
         	$phone = UserModel::where('user_id',$order['user_id'])->value('phone');
            if($phone){
                //$this->sendSms($phone,$order['wxapp_id'], $order['order_no']);
            }
            //发送微信模板消息
            $this->sendMessage($order, $order['wxapp_id']);
            // 返回状态
            $this->returnCode(true, 'OK');
        }
        // 返回状态
        $this->returnCode(false, '签名失败');
    }

    /**
     * 发送短信通知
     * @param $wxapp_id
     * @param $order_no
     * @return mixed
     * @throws \think\Exception
     */
    private function sendSms($phone,$wxapp_id, $order_no)
    {
        // 短信配置信息
        $config = SettingModel::getItem('sms', $wxapp_id);
        $SmsDriver = new SmsDriver($config);
        return $SmsDriver->sendSms($phone,'order_pay', compact('order_no'));
    }

    private function sendMessage($order,$wxapp_id)
    {
        $config = SettingModel::getItem('wechat',$wxapp_id);
        $tlpmsg = SettingModel::getItem('tplmsg',$wxapp_id);
        $openid = UserModel::where('user_id',$order['user_id'])->value('open_id');
        if(!empty($openid) && $tlpmsg['order_pay']['is_enable'] == 1){
            $wechat = new WeChat($config);
            $data = [
                'first' => '订单'.$order['order_no'].'支付成功',
                'keyword1' => $order['order_no'],
                'keyword2' => date('Y年m月d日 H:i:s'),
                'keyword3' => $order['pay_price'],
                'keyword4' => '微信支付',
                'remark' => '感谢您的惠顾！'
            ];
            $wechat->send($tlpmsg['order_pay']['template_code'],$openid,$data);
        }
    }

    /**
     * 返回状态给微信服务器
     * @param bool $is_success
     * @param string $msg
     */
    private function returnCode($is_success = true, $msg = null)
    {
        $xml_post = $this->toXml([
            'return_code' => $is_success ? $msg ?: 'SUCCESS' : 'FAIL',
            'return_msg' => $is_success ? 'OK' : $msg,
        ]);
        die($xml_post);
    }

    /**
     * 写入日志记录
     * @param $values
     * @return bool|int
     */
    private function doLogs($values)
    {
        return write_log($values, __DIR__);
    }

    /**
     * 生成paySign
     * @param $nonceStr
     * @param $prepay_id
     * @param $timeStamp
     * @return string
     */
    private function makePaySign($nonceStr, $prepay_id, $timeStamp)
    {
        $data = [
            'appId' => $this->config['app_id'],
            'nonceStr' => $nonceStr,
            'package' => 'prepay_id=' . $prepay_id,
            'signType' => 'MD5',
            'timeStamp' => $timeStamp,
        ];

        //签名步骤一：按字典序排序参数
        ksort($data);

        $string = $this->toUrlParams($data);

        //签名步骤二：在string后加入KEY
        $string = $string . '&key=' . $this->config['apikey'];

        //签名步骤三：MD5加密
        $string = md5($string);

        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);

        return $result;
    }

    /**
     * 将xml转为array
     * @param $xml
     * @return mixed
     */
    private function fromXml($xml)
    {
        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 以post方式提交xml到对应的接口url
     * @param $xml
     * @param $url
     * @param int $second
     * @return mixed
     */
    private function postXmlCurl($xml, $url, $second = 30)
    {
        $ch = curl_init();
        // 设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//严格校验
        // 设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        // 要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        // 运行curl
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * 生成签名
     * @param $values
     * @return string 本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    private function makeSign($values)
    {
        //签名步骤一：按字典序排序参数
        ksort($values);
        $string = $this->toUrlParams($values);
        //签名步骤二：在string后加入KEY
        $string = $string . '&key=' . $this->config['apikey'];
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 格式化参数格式化成url参数
     * @param $values
     * @return string
     */
    private function toUrlParams($values)
    {
        $buff = '';
        foreach ($values as $k => $v) {
            if ($k != 'sign' && $v != '' && !is_array($v)) {
                $buff .= $k . '=' . $v . '&';
            }
        }
        return trim($buff, '&');
    }

    /**
     * 输出xml字符
     * @param $values
     * @return bool|string
     */
    private function toXml($values)
    {
        if (!is_array($values)
            || count($values) <= 0
        ) {
            return false;
        }

        $xml = "<xml>";
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

}
