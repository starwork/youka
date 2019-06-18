import request from '../utils/request';

/**
 * 购物车列表
 * @returns {AxiosPromise}
 */
export function getCartListApi() {
  return request({
    url: '/cart/lists',
    method: 'get'
  })
}


/**
 * 添加商品到购物车里
 * @returns {AxiosPromise}
 */
export function addGoodsToCartApi(data) {
  return request({
    url: '/cart/add',
    method: 'get',
    params: data
  })
}
/**
 * 购物车山坡减少
 * @returns {AxiosPromise}
 */
export function subGoodsToCartApi(data) {
  return request({
    url: '/cart/sub',
    method: 'get',
    params: data
  })
}

/**
 * 删除购物车某个商品
 * @returns {AxiosPromise}
 */
export function delGoodsToCartApi(data) {
  return request({
    url: '/cart/delete',
    method: 'get',
    params: data
  })
}

/**
 * 购物车某个商品添加到收藏
 * @returns {AxiosPromise}
 */
export function cartGoodsToCollectApi(data) {
  return request({
    url: '/user.collect/add',
    method: 'post',
    data
  })
}

/**
 * 购物车结算
 * @returns {AxiosPromise}
 */
export function cartGoodsSettlementApi() {
  return request({
    url: '/order/cart',
    method: 'post',
  })
}
