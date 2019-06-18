import router from './router'
import store from './store'

import { getToken,setToken,getOpenId,setOpenId,setCode,getCode } from './utils/auth' // get token from cookie
import getPageTitle from './utils/get-page-title'

const whiteList = ['/signup', '/signin', '/home', '/forget']; // no redirect whitelist



router.beforeEach(async(to, from, next) => {
  // set page title
  document.title = getPageTitle(to.meta.title);
	console.log(to)
	if(to.query.invite_code){
		setCode(to.query.invite_code)
		//window.location.href= 'http://youka.qiniuniu.com/user.php?state='+to.query.invite_code
	}
//   if(true){
//     const OpenId = getOpenId();
// 		const Token = getToken();
//     if(OpenId && Token){
// 
//     }else{
//       if(to.query.open_id){
// 				store.dispatch("user/SetToken", to.query.token);
//         setOpenId(to.query.open_id)
//       }else{
// 				var invite_code = getCode()
// 				console.log(invite_code)
//         window.location.href= 'http://www.youkamall.cn/user.php?state='+invite_code
//       }
//     }
//   }
  // determine whether the user has logged in
  const hasToken = getToken();

  if (hasToken) {
    // 登录状态
    if (to.path === '/signin' || to.path === '/signup' || to.path === '/forget') {
      next('/')
    } else {
      // 请求申请个人信息接口 => 当有Token的时候
      next()
    }
  } else {
    if (whiteList.indexOf(to.path) !== -1) {
      // in the free login whitelist, go directly
      next()
    } else {
      // other pages that do not have permission to access are redirected to the login page.
      next(`/signin`)
    }
  }
});


//判断是否是微信浏览器的函数
function isWeiXin(){
  //window.navigator.userAgent属性包含了浏览器类型、版本、操作系统类型、浏览器引擎类型等信息，这个属性可以用来判断浏览器类型
  var ua = window.navigator.userAgent.toLowerCase();
  //通过正则表达式匹配ua中是否含有MicroMessenger字符串
  if(ua.match(/MicroMessenger/i) == 'micromessenger'){
    return true;
  }else{
    return false;
  }
}
