import request from '../utils/request';

/**
 * 用户中心
 * @returns {AxiosPromise}
 */
export function getUserCenterApi() {
  return request({
    url: '/user.index/index',
    method: 'get'
  })
}

/**
 * 用户信息
 * @returns {AxiosPromise}
 */
export function getUserInfoApi() {
  return request({
    url: '/user.index/userinfo',
    method: 'get'
  })
}


export function SaveAvatar(data){
	return request({
	  url: '/user.index/SaveAvatar',
	  method: 'post',
		data
	})
}

export function SaveNickName(data){
	return request({
	  url: '/user.index/SaveNickName',
	  method: 'post',
		data
	})
}

export function GetPhoneVerfity(data){
	return request({
		url: '/user/getPhoneVerfity',
		method: 'post',
		data
	})
}

export function UpdatePhone(data){
	return request({
	  url: '/user.index/update_phone',
	  method: 'post',
		data
	})
}

export function SetParent(code){
	return request({
	  url: '/user.index/SetParent',
	  method: 'post',
		data:{
			invite_code: code
		}
	})
}