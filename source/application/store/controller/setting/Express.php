<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-10 10:54
 */

namespace app\store\controller\setting;


use app\store\controller\Controller;
use app\common\model\Express as ExpressModel;

class Express extends Controller
{
    public function index($search = '')
    {
        $model = new ExpressModel();
        $list = $model->getList($search);
        return $this->fetch('index',compact('list'));
    }

    public function company()
    {
        $str = "顺丰速运	SF
百世快递	HTKY
中通快递	ZTO
申通快递	STO
圆通速递	YTO
韵达速递	YD
邮政快递包裹	YZPY
EMS	EMS
天天快递	HHTT
京东快递	JD
优速快递	UC
德邦快递	DBL
宅急送	ZJS
TNT快递	TNT
UPS	UPS
DHL	DHL
FEDEX联邦(国内件）	FEDEX
FEDEX联邦(国际件）	FEDEX_GJ
安捷快递	AJ
阿里跨境电商物流	ALKJWL
安迅物流	AX
安邮美国	AYUS
亚马逊物流	AMAZON
澳门邮政	AOMENYZ
安能物流	ANE
澳多多	ADD
澳邮专线	AYCA
安鲜达	AXD
安能快运	ANEKY
八达通  	BDT
百腾物流	BETWL
北极星快运	BJXKY
奔腾物流	BNTWL
百福东方	BFDF
贝海国际 	BHGJ
八方安运	BFAY
百世快运	BTWL
春风物流	CFWL
诚通物流	CHTWL
传喜物流	CXHY
城市100	CITY100
城际快递	CJKD
CNPEX中邮快递	CNPEX
COE东方快递	COE
长沙创一	CSCY
成都善途速运	CDSTKY
联合运通	CTG
疯狂快递	CRAZY
CBO钏博物流	CBO
承诺达	CND
D速物流	DSWL
到了港	DLG 
大田物流	DTWL
东骏快捷物流	DJKJWL
德坤	DEKUN
德邦快运	DBLKY
大马鹿	DML
E特快	ETK
EWE	EWE
快服务	KFW
飞康达	FKD
富腾达  	FTD
凡宇货的	FYKD
速派快递	FASTGO
丰通快运	FT
冠达   	GD
广东邮政	GDEMS
共速达	GSD
广通       	GTONG
迦递快递	GAI
港快速递	GKSD
高铁速递	GTSD
汇丰物流	HFWL
黑狗冷链	HGLL
恒路物流	HLWL
天地华宇	HOAU
鸿桥供应链	HOTSCM
海派通物流公司	HPTEX
华强物流	hq568
环球速运  	HQSY
华夏龙物流	HXLWL
豪翔物流 	HXWL
合肥汇文	HFHW
辉隆物流	HLONGWL
华企快递	HQKD
韩润物流	HRWL
青岛恒通快递	HTKD
货运皇物流	HYH
好来运快递	HYLSD
皇家物流	HJWL
捷安达  	JAD
京广速递	JGSD
九曳供应链	JIUYE
急先达	JXD
晋越快递	JYKD
加运美	JYM
景光物流	JGWL
佳怡物流	JYWL
京东快运	JDKY
佳吉快运	CNEX
跨越速运	KYSY
快速递物流	KSDWL
快8速运	KBSY
龙邦快递	LB
立即送	LJSKD
联昊通速递	LHT
民邦快递	MB
民航快递	MHKD
美快    	MK
门对门快递	MDM
迈隆递运	MRDY
明亮物流	MLWL
南方	NF
能达速递	NEDA
平安达腾飞快递	PADTF
泛捷快递	PANEX
品骏快递	PJ
PCA Express	PCA
全晨快递	QCKD
全日通快递	QRT
快客快递	QUICK
全信通	QXT
荣庆物流	RQ
七曜中邮	QYZY
如风达	RFD
日日顺物流	RRS
瑞丰速递	RFEX
赛澳递	SAD
苏宁物流	SNWL
圣安物流	SAWL
晟邦物流	SBWL
上大物流	SDWL
盛丰物流	SFWL
速通物流	ST
速腾快递	STWL
速必达物流	SUBIDA
速递e站	SDEZ
速呈宅配	SCZPDS
速尔快递	SURE
闪送	SS
盛通快递	STKD
台湾邮政	TAIWANYZ
唐山申通	TSSTO
特急送	TJS
通用物流	TYWL
腾林物流	TLWL
全一快递	UAPEX
优联吉运	ULUCKEX
UEQ Express	UEQ
万家康  	WJK
万家物流	WJWL
武汉同舟行	WHTZX
维普恩	WPE
万象物流	WXWL
微特派	WTP
温通物流	WTWL
迅驰物流  	XCWL
信丰物流	XFEX
希优特	XYT
新杰物流	XJ
源安达快递	YADEX
远成物流	YCWL
远成快运	YCSY
义达国际物流	YDH
易达通  	YDT
原飞航物流	YFHEX
亚风快递	YFSD
运通快递	YTKD
亿翔快递	YXKD
运东西网	YUNDX
壹米滴答	YMDD
邮政国内标快	YZBK
一站通速运	YZTSY
驭丰速运	YFSUYUN
余氏东风	YSDF
耀飞快递	YF
韵达快运	YDKY
云路	YL
增益快递	ZENY
汇强快递	ZHQKD
众通快递	ZTE
郑州速捷	SJ
中通快运	ZTOKY
中邮快递	ZYKD
中粮我买网	WM
芝麻开门	ZMKM
中骅物流	ZHWL
中铁物流	ZTWL
	
AAE全球专递	AAE
ACS雅仕快递	ACS
ADP Express Tracking	ADP
安圭拉邮政	ANGUILAYOU
APAC	APAC
Aramex	ARAMEX
奥地利邮政	AT
Australia Post Tracking	AUSTRALIA
比利时邮政	BEL
BHT快递	BHT
秘鲁邮政	BILUYOUZHE
巴西邮政	BR
不丹邮政	BUDANYOUZH
CDEK	CDEK
程光物流	CG
加拿大邮政	CA
递必易国际物流	DBYWL
大道物流	DDWL
德国云快递	DGYKD
到乐国际	DLGJ
DHL德国	DHL_DE
DHL(英文版)	DHL_EN
DHL全球	DHL_GLB
DHL Global Mail	DHLGM
丹麦邮政	DK
DPD	DPD
DPEX	DPEX
递四方速递	D4PX
EMS国际	EMSGJ
易客满	EKM
EPS (联众国际快运)	EPS
EShipper	ESHIPPER
丰程物流	FCWL
法翔速运	FX
FQ	FQ
芬兰邮政	FLYZ
方舟国际速递	FZGJ
国际e邮宝	GJEYB
国际邮政包裹	GJYZ
GE2D	GE2D
冠泰	GT
GLS	GLS
欧洲专线(邮政)	IOZYZ
澳大利亚邮政	IADLYYZ
阿尔巴尼亚邮政	IAEBNYYZ
阿尔及利亚邮政	IAEJLYYZ
阿富汗邮政	IAFHYZ
安哥拉邮政	IAGLYZ
埃及邮政	IAJYZ
阿鲁巴邮政	IALBYZ
阿联酋邮政	IALYYZ
阿塞拜疆邮政	IASBJYZ
博茨瓦纳邮政	IBCWNYZ
波多黎各邮政	IBDLGYZ
冰岛邮政	IBDYZ
白俄罗斯邮政	IBELSYZ
波黑邮政	IBHYZ
保加利亚邮政	IBJLYYZ
巴基斯坦邮政	IBJSTYZ
黎巴嫩邮政	IBLNYZ
波兰邮政	IBOLYZ
宝通达	IBTD
贝邮宝	IBYB
出口易	ICKY
德国邮政	IDGYZ
危地马拉邮政	IWDMLYZ
乌干达邮政	IWGDYZ
乌克兰EMS	IWKLEMS
乌克兰邮政	IWKLYZ
乌拉圭邮政	IWLGYZ
林克快递	ILKKD
文莱邮政	IWLYZ
新喀里多尼亚邮政	IXGLDNYYZ
爱尔兰邮政	IE
夏浦物流	IXPWL
印度邮政	IYDYZ
夏浦世纪	IXPSJ
厄瓜多尔邮政	IEGDEYZ
俄罗斯邮政	IELSYZ
飞特物流	IFTWL
瓜德罗普岛邮政	IGDLPDYZ
哥斯达黎加邮政	IGSDLJYZ
韩国邮政	IHGYZ
华翰物流	IHHWL
互联易	IHLY
哈萨克斯坦邮政	IHSKSTYZ
黑山邮政	IHSYZ
津巴布韦邮政	IJBBWYZ
吉尔吉斯斯坦邮政	IJEJSSTYZ
捷克邮政	IJKYZ
加纳邮政	IJNYZ
柬埔寨邮政	IJPZYZ
克罗地亚邮政	IKNDYYZ
肯尼亚邮政	IKNYYZ
科特迪瓦EMS	IKTDWEMS
罗马尼亚邮政	ILMNYYZ
摩尔多瓦邮政	IMEDWYZ
马耳他邮政	IMETYZ
尼日利亚邮政	INRLYYZ
塞尔维亚邮政	ISEWYYZ
塞浦路斯邮政	ISPLSYZ
乌兹别克斯坦邮政	IWZBKSTYZ
西班牙邮政	IXBYYZ
新加坡EMS	IXJPEMS
希腊邮政	IXLYZ
新西兰邮政	IXXLYZ
意大利邮政	IYDLYZ
英国邮政	IYGYZ
亚美尼亚邮政	IYMNYYZ
也门邮政	IYMYZ
智利邮政	IZLYZ
日本邮政	JP
今枫国际	JFGJ
极光转运	JGZY
吉祥邮转运	JXYKD
嘉里国际	JLDT
绝配国际速递	JPKD
佳惠尔	SYJHE
联运通	LYT
联合快递	LHKDS
林道国际	SHLDHY
荷兰邮政	NL
新顺丰	NSF
ONTRAC	ONTRAC
OCS	OCS
全球邮政	QQYZ
POSTEIBE	POSTEIBE
啪啪供应链	PAPA
秦远海运	QYHY
启辰国际	VENUCIA
瑞典邮政	RDSE
SKYPOST	SKYPOST
瑞士邮政	SWCH
首达速运	SDSY
穗空物流	SK
首通快运	STONG
申通快递国际单	STO_INTL
上海久易国际	JYSD
泰国138	TAILAND138
USPS美国邮政	USPS
万国邮政	UPU
中越国际物流	VCTRANS
星空国际	XKGJ
迅达国际	XD
香港邮政	XGYZ
喜来快递	XLKD
鑫世锐达	XSRD
新元国际	XYGJ
ADLER雄鹰国际速递	XYGJSD
日本大和运输(Yamato)	YAMA
YODEL	YODEL
一号线	YHXGJSD
约旦邮政	YUEDANYOUZ
玥玛速运	YMSY
鹰运	YYSD
易境达	YJD
洋包裹	YBG
友家速递	YJ
	
AOL（澳通）	AOL
BCWELT   	BCWELT
笨鸟国际	BN
优邦国际速运	UBONEX
UEX   	UEX
韵达国际	YDGJ
爱购转运	ZY_AG
爱欧洲	ZY_AOZ
澳世速递	ZY_AUSE
AXO	ZY_AXO
贝海速递	ZY_BH
蜜蜂速递	ZY_BEE
百利快递	ZY_BL
斑马物流	ZY_BM
百通物流	ZY_BT
策马转运	ZY_CM
EFS POST	ZY_EFS
宜送转运	ZY_ESONG
飞碟快递	ZY_FD
飞鸽快递	ZY_FG
风行快递	ZY_FX
风行速递	ZY_FXSD
飞洋快递	ZY_FY
皓晨快递	ZY_HC
海悦速递	ZY_HYSD
君安快递	ZY_JA
时代转运	ZY_JD
骏达快递	ZY_JDKD
骏达转运	ZY_JDZY
久禾快递	ZY_JH
金海淘	ZY_JHT
联邦转运FedRoad	ZY_LBZY
龙象快递	ZY_LX
美国转运	ZY_MGZY
美速通	ZY_MST
美西转运	ZY_MXZY
QQ-EX	ZY_QQEX
瑞天快递	ZY_RT
瑞天速递	ZY_RTSD
速达快递	ZY_SDKD
四方转运	ZY_SFZY
上腾快递	ZY_ST
天际快递	ZY_TJ
天马转运	ZY_TM
滕牛快递	ZY_TN
太平洋快递	ZY_TPY
唐三藏转运	ZY_TSZ
TWC转运世界	ZY_TWC
润东国际快线	ZY_RDGJ
同心快递	ZY_TX
天翼快递	ZY_TY
德国海淘之家	ZY_DGHT
德运网	ZY_DYW
文达国际DCS	ZY_WDCS
同舟快递	ZY_TZH
UCS合众快递	ZY_UCS
星辰快递	ZY_XC
先锋快递	ZY_XF
西邮寄	ZY_XIYJ
云骑快递	ZY_YQ
优晟速递	ZY_YSSD
运淘美国	ZY_YTUSA
至诚速递	ZY_ZCSD
增速海淘	ZYZOOM
中驰物流	ZH
中欧快运	ZO
准实快运	ZSKY
中外速运	ZWSY
郑州建华	ZZJH";
        $data = explode(PHP_EOL,$str);
        foreach ($data as $v){
            $arr = explode("\t",$v);
            if(count($arr) == 2){
                $list[] = [
                    'name' => $arr[0],
                    'code' => $arr[1]
                ];
            }
        }
//        dump($list);exit;
//
//        $list =[
//            ['name' => '顺丰速运','code' => 'SF'],
//            ['name' => '百世快递','code' => 'HTKY'],
//            ['name' => '中通快递','code' => 'ZTO'],
//            ['name' => '申通快递','code' => 'STO'],
//            ['name' => '圆通速递','code' => 'YTO'],
//            ['name' => '韵达速递','code' => 'YD'],
//            ['name' => '邮政快递包裹','code' => 'YZPY'],
//            ['name' => 'EMS','code' => 'EMS'],
//            ['name' => '天天快递','code' => 'HHTT'],
//            ['name' => '优速快递','code' => 'UC'],
//            ['name' => '德邦快递','code' => 'DBL'],
//            ['name' => '宅急送','code' => 'ZJS'],
//        ];
        return $this->fetch('company',compact('list'));
    }

    public function add()
    {
        if($this->request->isAjax()){
            $model = new ExpressModel();
            if($model->add($this->postData('express'))){
                return $this->renderSuccess('添加成功');
            }
            $error = $model->getError() ?: '添加失败';
            return $this->renderError($error);
        }
        return $this->fetch();
    }

    public function edit($id)
    {
        $model = ExpressModel::get($id);
        if($this->request->isAjax()){
            if($model->add($this->postData('express'))){
                return $this->renderSuccess('添加成功');
            }
            $error = $model->getError() ?: '添加失败';
            return $this->renderError($error);
        }
        return $this->fetch('edit',compact('model'));
    }

    public function delete($id)
    {
        $model = new ExpressModel();
        if($model->remove($id)){
            return $this->renderSuccess('删除成功');
        }
        $error = $model->getError() ?: '删除失败';
        return $this->renderError($error);
    }
}