import request from '../utils/request';

/**
 * 用户登录
 * @param data
 * @returns {AxiosPromise}
 */
export function login(data) {
  return request({
    url: '/user/login',
    method: 'post',
    data
  })
}
