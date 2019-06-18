import request from '../utils/request';

/**
 * 收藏列表
 * @returns {AxiosPromise}
 */
export function getStatisticsListApi() {
  return request({
    url: 'user.data/index',
    method: 'get'
  })
}
