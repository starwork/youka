<?php

namespace app\common\model;

use app\common\library\Time;
use think\Request;

/**
 * 用户模型类
 * Class User
 * @package app\common\model
 */
class User extends BaseModel
{
    protected $name = 'user';

    protected $hidden = ['password'];

    // 性别
    private $gender = ['未知', '男', '女'];

    /**
     * 关联收货地址表
     * @return \think\model\relation\HasMany
     */
    public function address()
    {
        return $this->hasMany('UserAddress');
    }

    /**
     * 关联银行卡
     * @return \think\model\relation\HasMany
     */
    public function bank_card()
    {
        return $this->hasMany('BankCard');
    }

    /**
     * 关联收货地址表 (默认地址)
     * @return \think\model\relation\BelongsTo
     */
    public function addressDefault()
    {
        return $this->belongsTo('UserAddress', 'address_id');
    }

    /**
     * 显示性别
     * @param $value
     * @return mixed
     */
    public function getGenderAttr($value)
    {
        return $this->gender[$value];
    }

    /**
     * 显示等级
     * @param $value
     * @return array
     */
    public function getLevelAttr($value)
    {
        $level = [
            0 => '普通会员',
            10 => '分销商',
            20 => '经销商',
            30 => '市级代理',
        ];
        $data = [
            'level' => $value,
            'level_name' => isset($level[$value]) ? $level[$value] : $level[0]
        ];
        return $data;
    }

    /**
     * 获取用户列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($filer = [])
    {
        $request = Request::instance();
        $user_id = $request->param('user_id',0);
        $user_id > 0 && $filer['user_id'] = $user_id;
        $pid = $request->param('pid',0);
        $ppid = $request->param('ppid',0);

        $list = $this->where($filer)->where(function ($query) use ($pid,$ppid){
            if($pid){
                $query->where('pid',$pid);
            }
            if($ppid){
                $query->where('pid',$ppid)->whereOr('ppid',$ppid);
            }
        })->order(['create_time' => 'desc'])
            ->paginate(15, false, ['query' => $request->request()]);
        $data = $list->items();
        foreach ($data as $k => $v){
            $data[$k]['parent'] = $v['pid'] ? $this->find($v['pid']) : null;
            $data[$k]['pparent'] = $v['ppid'] ? $this->find($v['ppid']) : null;
            $data[$k]['child_total'] = $this->child_total($v['user_id']);
            $data[$k]['trem_count'] = $this->trem_count($v['user_id']);
        }
        $list->data = $data;
        return $list;
    }

    public function child_total($user_id)
    {
        return $this->where('pid',$user_id)->count();
    }


    public function trem_count($user_id)
    {
        return $this->where(function ($query) use($user_id){
            $query->where('pid',$user_id)->whereOr('ppid',$user_id);
        }) ->count();
    }

    /**
     * 获取用户信息
     * @param $where
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($where)
    {
        //return self::get($where, ['address', 'addressDefault']);
        $user = self::get($where);
        if(!$user){
            return false;
        }
        $user = $user->hidden(['password']);
        $parent = self::find($user['pid']);
        $user['total'] = PriceLog::where('user_id',$user['user_id'])->where('price','>',0)->sum('price');
        list($start,$end) = Time::YesterDay();
        $user['yesterday'] = PriceLog::where('user_id',$user['user_id'])->where('price','>',0)->where('create_time','BETWEEN',[$start,$end])->sum('price');
        return $user;
    }


    public function getInviteCode()
    {
        $code = randomStr(12);
        if(!$this->where('invite_code',$code)->find()){
            return $code;
        }else{
            return $this->getInviteCode();
        }
    }

}
