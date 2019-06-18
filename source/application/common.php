<?php

// 应用公共函数库文件

use think\Request;
use app\common\library\Auth;
use app\api\model\User;

/**
 * 打印调试函数
 * @param $content
 * @param $is_die
 */
function pre($content, $is_die = true)
{
    header('Content-type: text/html; charset=utf-8');
    echo '<pre>' . print_r($content, true);
    $is_die && die();
}

/**
 * 驼峰命名转下划线命名
 * @param $str
 * @return string
 */
function toUnderScore($str)
{
    $dstr = preg_replace_callback('/([A-Z]+)/', function ($matchs) {
        return '_' . strtolower($matchs[0]);
    }, $str);
    return trim(preg_replace('/_{2,}/', '_', $dstr), '_');
}

/**
 * 生成密码hash值
 * @param $password
 * @return string
 */
function yoshop_hash($password)
{
    return md5(md5($password) . 'youkashop_salt_SmTRx');
}

/**
 * 获取当前域名及根路径
 * @return string
 */
function base_url()
{
    $request = Request::instance();
    $subDir = str_replace('\\', '/', dirname($request->server('PHP_SELF')));
    return $request->scheme() . '://' .$request->host().'/';
    return $request->scheme() . '://' . $request->host() . $subDir . ($subDir === '/' ? '' : '/');
}

/**
 * 写入日志
 * @param string|array $values
 * @param string $dir
 * @return bool|int
 */
function write_log($values, $dir)
{
    if (is_array($values))
        $values = json_encode($values, JSON_UNESCAPED_UNICODE);
    // 日志内容
    $content = '[' . date('Y-m-d H:i:s') . ']' . "\t" . $values . PHP_EOL ;
    try {
        // 文件路径
        $filePath = $dir . '/logs/';
        // 路径不存在则创建
        !is_dir($filePath) && mkdir($filePath, 0755, true);
        // 写入文件
        return file_put_contents($filePath . date('Ymd') . '.log', $content, FILE_APPEND);
    } catch (\Exception $e) {
        return false;
    }
}

/**
 * curl请求指定url
 * @param $url
 * @param array $data
 * @return mixed
 */
function curl($url, $data = [])
{
    // 处理get数据
    if (!empty($data)) {
        $url = $url . '?' . http_build_query($data);
    }
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}

if (!function_exists('array_column')) {
    /**
     * array_column 兼容低版本php
     * (PHP < 5.5.0)
     * @param $array
     * @param $columnKey
     * @param null $indexKey
     * @return array
     */
    function array_column($array, $columnKey, $indexKey = null)
    {
        $result = array();
        foreach ($array as $subArray) {
            if (is_null($indexKey) && array_key_exists($columnKey, $subArray)) {
                $result[] = is_object($subArray) ? $subArray->$columnKey : $subArray[$columnKey];
            } elseif (array_key_exists($indexKey, $subArray)) {
                if (is_null($columnKey)) {
                    $index = is_object($subArray) ? $subArray->$indexKey : $subArray[$indexKey];
                    $result[$index] = $subArray;
                } elseif (array_key_exists($columnKey, $subArray)) {
                    $index = is_object($subArray) ? $subArray->$indexKey : $subArray[$indexKey];
                    $result[$index] = is_object($subArray) ? $subArray->$columnKey : $subArray[$columnKey];
                }
            }
        }
        return $result;
    }
}

/**
 * 多维数组合并
 * @param $array1
 * @param $array2
 * @return array
 */
function array_merge_multiple($array1, $array2)
{
    $merge = $array1 + $array2;
    $data = [];
    foreach ($merge as $key => $val) {
        if (
            isset($array1[$key])
            && is_array($array1[$key])
            && isset($array2[$key])
            && is_array($array2[$key])
        ) {
            $data[$key] = array_merge_multiple($array1[$key], $array2[$key]);
        } else {
            $data[$key] = isset($array2[$key]) ? $array2[$key] : $array1[$key];
        }
    }
    return $data;
}

/**
 * 获取全局唯一标识符
 * @param bool $trim
 * @return string
 */
function getGuidV4($trim = true)
{
    // Windows
    if (function_exists('com_create_guid') === true) {
        $charid = com_create_guid();
        return $trim == true ? trim($charid, '{}') : $charid;
    }
    // OSX/Linux
    if (function_exists('openssl_random_pseudo_bytes') === true) {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    // Fallback (PHP 4.2+)
    mt_srand((double)microtime() * 10000);
    $charid = strtolower(md5(uniqid(rand(), true)));
    $hyphen = chr(45);                  // "-"
    $lbrace = $trim ? "" : chr(123);    // "{"
    $rbrace = $trim ? "" : chr(125);    // "}"
    $guidv4 = $lbrace .
        substr($charid, 0, 8) . $hyphen .
        substr($charid, 8, 4) . $hyphen .
        substr($charid, 12, 4) . $hyphen .
        substr($charid, 16, 4) . $hyphen .
        substr($charid, 20, 12) .
        $rbrace;
    return $guidv4;
}

function randomStr($num = 6){
    $arr = array_merge(range(0,9));
    $str = '';
    $arr_len = count($arr);
    for($i = 0;$i < $num;$i++){
        $rand = mt_rand(0,$arr_len-1);
        $str.=$arr[$rand];
    }
    return $str;
}

function check_auth($name,$uid)
{
    if($uid == 1){
        return true;
    }
    $auth = new Auth();
    if($auth->check($name,$uid)){
        return true;
    }else{
        return false;
    }
}

function check_url($name,$uid,$vars = '', $suffix = true, $domain = false)
{
    if(check_auth($name,$uid)){
        return url($name,$vars, $suffix, $domain);
    }else{
        return ' " style="display:none;" ';
    }
}

function csv_export($data = [], $headlist = [], $fileName)
{
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$fileName.'.csv"');
    header('Cache-Control: max-age=0');
    //打开PHP文件句柄,php://output 表示直接输出到浏览器
    $fp = fopen('php://output', 'a');

    //输出Excel列名信息
    foreach ($headlist as $key => $value) {
        //CSV的Excel支持GBK编码，一定要转换，否则乱码
        $headlist[$key] = iconv('utf-8', 'gbk', $value);
    }

    //将数据通过fputcsv写到文件句柄
    fputcsv($fp, $headlist);

    //计数器
    $num = 0;

    //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
    $limit = 100000;

    //逐行取出数据，不浪费内存
    $count = count($data);
    for ($i = 0; $i < $count; $i++) {

        $num++;

        //刷新一下输出buffer，防止由于数据过多造成问题
        if ($limit == $num) {
            ob_flush();
            flush();
            $num = 0;
        }

        $row = $data[$i];
        foreach ($row as $key => $value) {
            $row[$key] = iconv('utf-8', 'gbk',  "\t".$value);
        }

        fputcsv($fp, $row);
    }
}

function read_csv($file)
{
    if (!is_file($file)) {
        return false;
    }

    $handle = fopen($file, 'r');
    if (!$handle) {
        return false;
    }
    $arr = [];
    while (($data = fgetcsv($handle)) !== false) {
        // 下面这行代码可以解决中文字符乱码问题
        // $data[0] = iconv('gbk', 'utf-8', $data[0]);

        // 跳过第一行标题
        if ($data[0] == '订单号') {
            continue;
        }
        $arr[] = $data;

    }
    fclose($handle);
    return $arr;
}

/**
 * 将字符串转换为数组
 *
 * @param	string	$data	字符串
 * @return	array	返回数组格式，如果，data为空，则返回空数组
 */
function string2array($data) {
    $data = trim($data);
    if($data == '') return array();
    if(strpos($data, 'Array')===0){
        @eval("\$Array = $data;");
    }else{
        if(strpos($data, '{\\')===0) $data = stripslashes($data);
        $array=json_decode($data,true);
        if(strtolower(CHARSET)=='gbk'){
            $array = mult_iconv("UTF-8", "GBK//IGNORE", $array);
        }
    }
    return $array;
}
/**
 * 将数组转换为字符串
 *
 * @param	array	$data		数组
 * @param	bool	$isformdata	如果为0，则不使用new_stripslashes处理，可选参数，默认为1
 * @return	string	返回字符串，如果，data为空，则返回空
 */
function array2string($data, $isformdata = 1) {
    if($data == '' || empty($data)) return '';

    if($isformdata) $data = new_stripslashes($data);
    if(strtolower(CHARSET)=='gbk'){
        $data = mult_iconv("GBK", "UTF-8", $data);
    }
    if (version_compare(PHP_VERSION,'5.3.0','<')){
        return addslashes(json_encode($data));
    }else{
        return addslashes(json_encode($data,JSON_FORCE_OBJECT));
    }
}

//一级分销
function getFirstPrice($config,$goods,$parent,$count){
    if($count%5 == 0){
        $price = [
            'price' => $goods['goods_price']*$config['first_money']/100,
            'text' =>  '首推津贴(未释放)',
            'is_frozen' => 1,
            $parent['jd'] = 0
        ];
    }else{
        $parent_level = $parent['level']['level'];
        if(!empty($config[$parent_level]['first_money'])){
            $price = [
                'price' => $goods['goods_price']*$config[$parent_level]['first_money']/100,
                'text' =>  '第'.($count+1).'单，一级佣金',
                'is_frozen' => 0,
                'jd' => $count%5 == 1 ? 1 : 0
            ];
        }
    }
    return $price;
}
//二级分销
function getSecondPrice($config,$goods,$parent)
{
    $pparent_level = $parent['level']['level'];
    if(!empty($config[$pparent_level]['second_money'])){
        $price = [
            'price' => $goods['goods_price']*$config[$pparent_level]['second_money']/100,
            'text' => '二级佣金'
        ];
    }
    return $price;
}

function getFistAward($config,$goods,$pids)
{
    //经销商
    $agent = User::where('user_id','in',$pids)->where('level',20)->order('user_id','desc')->limit(2)->select();

    if(is_array($agent)){
        if($agent[0]){
            $agent[0]['price'] = $goods['goods_price']*$config[20]['maintain']['diect']/100;
            $agent[0]['text'] = '经销商维护奖';
        }
        if($agent[1]){
            $agent[1]['price'] = $goods['goods_price']*$config[20]['maintain']['indirect']/100;
            $agent[1]['text'] = '经销商维护奖';
        }
        return $agent;
    }
}

function getSecondAward($config,$goods,$pids)
{
    $city_agent = User::where('user_id','in',$pids)->where('level',30)->order('user_id','desc')->limit(2)->select();
    if(is_array($city_agent)){
        if($city_agent[0]){
            $city_agent[0]['price'] = $goods['goods_price']*$config[30]['maintain']['diect']/100;
            $city_agent[0]['text'] = '市级代理维护奖';
        }
        if($city_agent[1]){
            $city_agent[1]['price'] = $goods['goods_price']*$config[30]['maintain']['indirect']/100;
            $city_agent[1]['text'] = '市级代理维护奖';
        }
        return $city_agent;
    }
}

//转换剩余时间格式
function get_surplus_time($time = 0){
    if (!$time) {
        return false;
    }
    if ($time < 0) {
        return '已结束';
    } else {
        if ($time < 60) {
            return $time . '秒';
        } else {
            if ($time < 3600) {
                return floor($time / 60) . '分钟';
            } else {
                if ($time < 86400) {
                    return floor($time / 3600) . '小时';
                } else {
                    if ($time < 259200) {//3天内
                        return floor($time / 86400) . '天';
                    } else {
                        return floor($time / 86400) . '天';
                    }
                }
            }
        }
    }
}