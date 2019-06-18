import request from '../utils/request';

/**
 * 重置
 * @param data
 * @returns {AxiosPromise}
 */
export function resetpwdApi(data) {
  return request({
    url: '/apiuser/resetpwd',
    method: 'post',
    data
  })
}

/**
 * 重置
 * @param data
 * @returns {AxiosPromise}
 */
export function resetpwdSMSApi(data) {
  return request({
    url: '/user/getPwdVerfity',
    method: 'post',
    data
  })
}
