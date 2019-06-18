import request from '../utils/request';

/**
 * 收藏列表
 * @returns {AxiosPromise}
 */
export function getCollectionListApi() {
  return request({
    url: '/user.collect/',
    method: 'get'
  })
}

/**
 * 删除某个收藏
 * @returns {AxiosPromise}
 */
export function delCollectionItemApi(id) {
  return request({
    url: '/user.collect/delete',
    method: 'get',
    params: {
      id: id
    }
  })
}
