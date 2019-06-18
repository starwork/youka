<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-22 17:34
 */

namespace app\store\controller\user;


use app\api\model\User;
use app\common\library\wechat\TplMsg;
use app\store\controller\Controller;
use app\common\model\PayBank as PayBankModel;
use app\common\model\Message as MessageModel;
use app\common\model\PriceLog;
use think\Db;

/**
 * 提现申请
 * Class PayBank
 * @package app\store\controller\user
 */
class PayBank extends Controller
{
    public function index($user_id = 0)
    {
        $model = new PayBankModel();
        $filer = [];
        $user_id > 0 && $filer['user_id'] = $user_id;
        $list = $model->getList($filer);
        $lv = config('draw'); //提现配置
        return $this->fetch('index',compact('list','lv'));
    }

    public function setting()
    {
        if($this->request->isAjax()){
            $data = $this->request->post();
            $path = CONF_PATH.'extra/draw.php';
            file_put_contents($path, "<?php\nreturn " . var_export($data, true) . ";");
            return $this->renderSuccess('修改成功');
        }
        $config = config('draw');
        return $this->fetch('setting',compact('config'));
    }

    public function pay_success($id)
    {
        $model = PayBankModel::get($id);
        if($model['payment_no'] || $model['status']['value'] != 10){
            return $this->renderError('该提现已完成');
        }
        $payment_no = $this->request->post('payment_no','');
        if($payment_no == ''){
            return $this->renderError('输入提现交易号');
        }
        $is_set = (new PayBankModel())->where('payment_no',$payment_no)->find();
        if($is_set){
            return $this->renderError('该提现交易号已输入');
        }
        $data = [
            'payment_no' => $payment_no,
            'status' => 20
        ];
        if($model->save($data)){
            (new MessageModel())->sendNotice('提现成功','提现金额会在24小时到账。',$model['user_id']);
            $openid = User::where('user_id',$model['user_id'])->value('open_id');
            if($openid){
                $tpl = new TplMsg();
                $model['status'] = '提现成功';
                $model['content'] = '提现金额会在24小时到账';
                $tpl->SendTmpl($openid,'pay_bank',$model);
            }
            return $this->renderSuccess('提交完成');
        }else{
            return $this->renderError('提交失败');
        }
    }


    public function pay_error($id)
    {
        $model = PayBankModel::get($id);
        if($model['payment_no'] || $model['status']['value'] != 10){
            return $this->renderError('该提现已完成或未通过');
        }
        $remark = $this->request->post('remark','');
        if($remark == ''){
            return $this->renderError('输入理由');
        }
        Db::startTrans();
        try{
            User::where('user_id',$model['user_id'])->setInc('price',$model['price']);
            (new PriceLog())->allowField(true)->save([
                'user_id' => $model['user_id'],
                'price' => $model['price'],
                'text' => '提现失败，返回金额',
                'type' => 2,
                'order_id' => 0
            ]);
            $data = [
                'remark' => $remark,
                'status' => 30
            ];
            $model->allowField(true)->save($data);
            Db::commit();
            (new MessageModel())->sendNotice('提现失败','失败原因:'.$remark.',提现金额返回余额。',$model['user_id']);
            $openid = User::where('user_id',$model['user_id'])->value('open_id');
            if($openid){
                $tpl = new TplMsg();
                $model['status'] = '提现失败';
                $model['content'] = '失败原因:'.$remark.',提现金额返回余额。';
                $tpl->SendTmpl($openid,'pay_bank',$model);
            }
            return $this->renderSuccess('提交完成');
        }catch (\Exception $e){
            Db::rollback();
            return $this->renderError('提交失败');
        }
    }
}