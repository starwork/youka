import request from '../utils/request';

/**
 * 首页信息
 * @returns {AxiosPromise}
 */
export function getHomeApi() {
  return request({
    url: '/',
    method: 'get'
  })
}
