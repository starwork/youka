import request from '../utils/request';


// 对Date的扩展，将 Date 转化为指定格式的String
// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符，
// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18
Date.prototype.Format = function (fmt) {
    var o = {
        "M+": this.getMonth() + 1,                 //月份
        "d+": this.getDate(),                    //日
        "h+": this.getHours(),                   //小时
        "m+": this.getMinutes(),                 //分
        "s+": this.getSeconds(),                 //秒
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度
        "S": this.getMilliseconds()             //毫秒
    };
    if (/(y+)/.test(fmt))
        fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt))
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}   

export function formatTimeToStr(times, pattern) {
    var d = new Date(times).Format("yyyy-MM-dd hh:mm:ss");
    if (pattern) {
        d = new Date(times).Format(pattern);
    }
    return d.toLocaleString();
}

export function getPanel(){ 	//运营面板
	return request({
		url: '/user.data/panel',
		method: 'get'
	});
}

export function getPrivacy(){
	return request({
		url: '/index/getPrivate',
		method: 'get'
	});
}

export function getServer(){
	return request({
		url: '/index/getServer',
		method: 'get'
	});
}

export function getPerson(){	//人员管理
	return request({
		url: '/user.price/personManage',
		method: 'get'
	});
}

export function getWealth(){	//财富管理
	return request({
		url: '/user.price/wealthManage',
		method: 'get'
	});
}

export function getTeam(){	//团队管理
	return request({
		url: '/user.data/team',
		method: 'get'
	});
}

export function getTeamPrice(){	//团队业绩
	return request({
		url: '/user.data/teamPrice',
		method: 'get'
	});
}

export function getChild(level){	//我的邀请
	return request({
		url: '/user.index/myChild',
		method: 'get',
		params:{
			level:level
		}
	});
}

export function getUserInfo(user_id){	//下级信息
	return request({
		url: '/user.data/getUserinfo',
		method: 'get',
		params: {
		  user_id: user_id
		}
	});
}

export function getPriceList(){	//收支流水
	return request({
		url: '/user.price/lists',
		method: 'get'
	});
}

export function getMonthPrice(time){	//奖励收入
	return request({
		url: '/user.price/InPrice',
		method: 'get',
		params:{
			month: time
		}
	});
}

export function getOutPrice(time){	//奖励支出
	return request({
		url: '/user.price/OutPrice',
		method: 'get',
		params:{
			month: time
		}
	});
}

export function subOrder(){
	return request({
		url: '/user.price/subOrderManage',
		method: 'get'
	});
}

export function submitCallback(type,content){
	return request({
		url: '/user.index/callback',
		method: 'post',
		data:{type:type,content:content}
	});
}

export function Issubscribe(){
	return request({
		url: '/user.index/Issubscribe',
		method: 'get'
	});
}

export function getTarBarNum(){
	return request({
		url: '/index/getNum',
		method: 'get'
	});
}