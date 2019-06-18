import request from '../utils/request';

export function getWechatInit(){
	return request({
	  url: '/index/WxConfig',
	  method: 'post',
	  data:{ url:location.href.split('#')[0] }
	})
}

//判断是否是微信浏览器的函数
export function isWeiXin(){
  //window.navigator.userAgent属性包含了浏览器类型、版本、操作系统类型、浏览器引擎类型等信息，这个属性可以用来判断浏览器类型
  var ua = window.navigator.userAgent.toLowerCase();
  //通过正则表达式匹配ua中是否含有MicroMessenger字符串
  if(ua.match(/MicroMessenger/i) == 'micromessenger'){
    return true;
  }else{
    return false;
  }
}