<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-07 10:22
 */
namespace app\common\library\delivery;

class KdNiao
{
    protected $EBusinessID = '1470750';
    protected $AppKey = '9529a733-fd22-435b-9e09-bcf4f0aed184';
    protected $ReqURL = 'http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx';

    /**
     * 构造方法
     * Driver constructor.
     * @param $config
     * @throws Exception
     */
    public function __construct($config)
    {
        $this->EBusinessID = $config['express']['id'];
        $this->AppKey = $config['express']['keyid'];
    }

    /**
     * 物流查询
     * @param $delivery_no
     * @param $com
     * @return mixed
     */
    function Search($delivery_no,$com){
        $requestData= "{'OrderCode':'','ShipperCode':'".$com."','LogisticCode':'".$delivery_no."'}";

        $datas = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->AppKey);
        $result= $this->sendPost($this->ReqURL, $datas);

        //根据公司业务处理返回的信息......
        return json_decode($result,true);
    }

    function orderTracesSubByJson($order_no,$delivery_no,$com)
    {
        $requestData="{'OrderCode': ".$order_no.",".
            "'ShipperCode':".$com.",".
            "'LogisticCode':".$delivery_no.",".
            "'PayType':1,".
            "'ExpType':1,".
            "'IsNotice':0,".
            "'Cost':1.0,".
            "'OtherCost':1.0,".
            "'Sender':".
            "{".
            "'Company':'LV','Name':'Taylor','Mobile':'15018442396','ProvinceName':'上海','CityName':'上海','ExpAreaName':'青浦区','Address':'明珠路73号'},".
            "'Receiver':".
            "{".
            "'Company':'GCCUI','Name':'Yann','Mobile':'15018442396','ProvinceName':'北京','CityName':'北京','ExpAreaName':'朝阳区','Address':'三里屯街道雅秀大厦'},".
            "'Commodity':".
            "[{".
            "'GoodsName':'鞋子','Goodsquantity':1,'GoodsWeight':1.0}],".
            "'Weight':1.0,".
            "'Quantity':1,".
            "'Volume':0.0,".
            "'Remark':'小心轻放'}";
    }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }
}