import request from '../utils/request';

/**
 * 商品列表
 * @param data 参数
 * @returns {AxiosPromise}
 */
export function getGoodsListApi(data) {
  return request({
    url: '/goods/lists',
    method: 'get',
    params: data
  })
}
