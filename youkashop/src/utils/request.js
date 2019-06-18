import axios from 'axios'
import {Notify} from 'vant';
import qs from 'qs';
import router from '../router';

import store from '../store'
import {getToken,getOpenId,removeOpenId} from '../utils/auth'

// create an axios instance
const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
  withCredentials: false, // send cookies when cross-domain requests
  timeout: 5000 * 5 // request timeout
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

// request interceptor
service.interceptors.request.use(
  config => {
    // do something before request is sent

    if (store.getters.token) {
      // let each request carry token
      // ['token'] is a custom headers key
      // please modify it according to the actual situation
      config.headers['token'] = getToken()
      config.headers['open_id'] = getOpenId()
			if(isWeiXin()){
				config.headers['deviceType'] = 'wechat'
			}
    }

    // 当POST请求 序列化请求参数
    if(config.method === 'post') {
      config.data = qs.stringify(config.data)
    }

    return config
  },
  error => {
    // do something with request error
    console.log(error); // for debug
    return Promise.reject(error)
  }
);

// response interceptor
service.interceptors.response.use(
  /**
   * If you want to get http information such as headers or status
   * Please return  response => response
   */

  /**
   * Determine the request status by custom code
   * Here is just an example
   * You can also judge the status by HTTP Status Code
   */
  response => {
    const res = response.data;

    // if the custom code is not 20000, it is judged as an error.
    if (res.code !== 1) {
      Notify({
        message: res.msg || 'error',
        duration: 5 * 1000
      });
		if(res.code == -1){
			store.dispatch('user/RemoveToken');
			removeOpenId();
			router.push({
			  path: '/'
			})
		}
		if(res.code == -10){
			router.push({
				path : '/subscribe'
			})
		}
		if(res.code == -3){
			router.push({
				path : '/user/info',
				query:{
					type: 'phone'
				}
			})
		}
      return res
    } else {
      return res
    }

  },
  error => {
    // console.log('err' + error); // for debug
    Notify({
      message: '接口请求失败：' + error.message,
      duration: 5 * 1000
    });
    return Promise.reject(error);
  }
);

export default service
