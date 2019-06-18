import request from '../utils/request';

/**
 * 消息提醒
 * @param type delivery 物流 system系统 notice 通知
 * @returns {AxiosPromise}
 */
export function getNewsListApi(type) {
  return request({
    url: '/user.message/index',
    method: 'get',
    params: {
      dataType: type
    }
  })
}
