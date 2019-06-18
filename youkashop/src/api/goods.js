import request from '../utils/request';

/**
 * 商品详细
 * @param id 参数
 * @returns {AxiosPromise}
 */
export function getGoodsInfoApi(id) {
  return request({
    url: '/goods/detail',
    method: 'get',
    params: {
      goods_id: id
    }
  })
}

export function getCommentList(goods_id,page) {
  return request({
    url: '/goods/comment_list',
    method: 'get',
    params: {
      goods_id: goods_id,
			page:page
    }
  })
}
