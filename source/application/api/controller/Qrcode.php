<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-06-05 15:11
 */

namespace app\api\controller;

use app\api\model\User as UserModel;
use Endroid\QrCode\QrCode as QrCodeModel;
use think\Db;

class Qrcode extends Controller
{

    public function index($user_id)
    {
        $user = UserModel::get($user_id);
        // 上传目录
        $uplodDir = WEB_PATH . 'uploads/orcode';
        $file_name = $uplodDir . '/'.$user['user_id'].'.png';
        if(file_exists($file_name)){
            header('Content-Type: image/png');
            die(file_get_contents($file_name));
        }
        $text = base_url().'index.php/api/qrcode/discrimi/user_id/'.$user_id;
        $qrCode = new QrCodeModel($text);
        $qrCode->setSize(300);
        // Set advanced options
        $qrCode->setWriterByName('png');
        $qrCode->setMargin(10);
        $qrCode->setEncoding('UTF-8');
        //$qrCode->setErrorCorrectionLevel('high');
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        // $qrCode->setLabel('Scan the code', 16, __DIR__ . '/../assets/fonts/noto_sans.otf', LabelAlignment::CENTER);
        // $qrCode->setLogoPath(__DIR__ . '/../assets/images/symfony.png');
        //$qrCode->setLogoSize(150, 200);
        //$qrCode->setRoundBlockSize(true);
        //$qrCode->setValidateResult(false);
        //$qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
        // Directly output the QR code
        $qrCode->writeFile($file_name);
        header('Content-Type: ' . $qrCode->getContentType());
        die($qrCode->writeString());

    }

    public function discrimi($user_id)
    {
        $user = UserModel::get($user_id);
        //$start_time = time() - (3600*24*30*3);  //三个月前
        $order_count = (new \app\api\model\Order())->where('user_id',$user['user_id'])->where('pay_status',20)->count();

        if($order_count == 0){
            $this->alert('二维码失效');
        }
        //exit(base_url().'signup?invite_code='.$user['invite_code']);
        $this->redirect(base_url().'signup?invite_code='.$user['invite_code']);
    }

    public function alert($msg)
    {
        $str = '<script> alert("'.$msg.'");WeixinJSBridge.call(\'closeWindow\'); </script>';
        die($str);
    }
}