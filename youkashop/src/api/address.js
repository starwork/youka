import request from '../utils/request';

/**
 * 收货地址列表
 * @returns {AxiosPromise}
 */
export function getAddressListApi() {
  return request({
    url: '/address/lists',
    method: 'get'
  })
}

/**
 * 添加收货地址
 * @param data
 * @returns {AxiosPromise}
 */
export function addressApi(data) {
  return request({
    url: '/address/add',
    method: 'post',
    data
  })
}

/**
 * 查询收货地址
 * @param id
 * @returns {AxiosPromise}
 */
export function getAddressInfoApi(id) {
  return request({
    url: '/address/detail',
    method: 'get',
    params: {
      address_id: id
    }
  })
}

/**
 * 修改收货地址
 * @param data
 * @returns {AxiosPromise}
 */
export function editAddressApi(data) {
  return request({
    url: '/address/edit',
    method: 'post',
    data
  })
}

/**
 * 删除收货地址
 * @param id
 * @returns {AxiosPromise}
 */
export function deleteAddressApi(id) {
  return request({
    url: '/address/delete',
    method: 'get',
    params: {
      address_id: id
    }
  })
}
