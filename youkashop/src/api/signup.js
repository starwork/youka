import request from '../utils/request';

/**
 * 用户注册
 * @param data
 * @returns {AxiosPromise}
 */
export function registered(data) {
  return request({
    url: '/user/resgiter',
    method: 'post',
    data
  })
}

/**
 * 用户注册验证码
 * @param data
 * @returns {AxiosPromise}
 */
export function regSMSApi(data) {
  return request({
    url: '/user/getResVerfiy',
    method: 'post',
    data
  })
}
