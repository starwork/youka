import request from '../utils/request';

/**
 * 订单提交页面\
 * @param type
 * @returns {AxiosPromise}
 */
export function getOrderSubmit(goods,address_id) {
	console.log(goods)
  return request({
    url: '/order/submit',
    method: 'post',
	data: {goods:goods,address_id:address_id}
  })
}


/**
 * 订单提交\
 * @param type
 * @returns {AxiosPromise}
 */
export function SubmitOrder(goods,remark,address_id,type) {
	console.log(goods)
  return request({
    url: '/order/buy',
    method: 'post',
	data: {goods:goods,address_id:address_id,type:type,remark:remark}
  })
}

/**
 * 订单详情\
 * @param type
 * @returns {AxiosPromise}
 */
export function getOrderDetail(order_id) {
  return request({
    url: '/user.order/detail',
    method: 'get',
	params: {
      order_id: order_id
    }
  })
}


/**
 * 订单列表\
 * @param type
 * @returns {AxiosPromise}
 */
export function getOrderListApi(type) {
  return request({
    url: '/user.order/lists',
    method: 'get',
    params: {
      dataType: type
    }
  })
}

export function getChildOrder(type){
	return request({
	  url: '/user.order/ChildLists',
	  method: 'get',
	  params: {
	    dataType: type
	  }
	})
}

/**
 * 取消订单
 * @param {Object} order_id
 */
export function CancelOrder(order_id){
	return request({
	  url: '/user.order/cancel',
	  method: 'post',
		data: {order_id:order_id}
	})
}

/**
 * 确认收货
 * @param {Object} order_id
 */
export function ReceiptOrder(order_id){
	return request({
	  url: '/user.order/receipt',
	  method: 'post',
		data: {order_id:order_id}
	})
}

/**
 * 立即支付
 * @param {Object} order_id
 */
export function PayOrder(order_id){
	return request({
	  url: '/user.order/pay',
	  method: 'post',
		data: {order_id:order_id}
	})
}

/**
 * 物流查询
 * @param {Object} order_id
 */
export function ExpressOrder(order_id){
	return request({
	  url: '/user.order/express',
	  method: 'post',
		data: {order_id:order_id}
	})
}

/**
 * 评论
 * @param {Object} order_id
 * @param {Object} goods_id
 */
export function Comment(goods,order_id){
	return request({
	  url: '/user.order/comment',
	  method: 'post',
		data:{ goods:goods,order_id:order_id}
	})
}