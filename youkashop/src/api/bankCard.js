import request from '../utils/request';

/**
 * 银行卡号列表
 * @returns {AxiosPromise}
 */
export function getBankCardListApi() {
  return request({
    url: '/bank/lists',
    method: 'get'
  })
}

/**
 * 添加银行卡
 * @param data
 * @returns {AxiosPromise}
 */
export function addBankCardApi(data) {
  return request({
    url: '/bank/add',
    method: 'post',
    data
  })
}

/**
 * 删除银行卡
 * @param id
 * @returns {AxiosPromise}
 */
export function deleteBankCardApi(id) {
  return request({
    url: '/bank/delete',
    method: 'get',
    params: {
      id: id
    }
  })
}

export function getBankInfo(card){
	return request({
	  url: '/bank/bankInfo',
	  method: 'get',
	  params: {
	    card: card
	  }
	})
}

export function getWithDraw()
{
	return request({
	  url: '/user.price/withdraw',
	  method: 'get',
	})
}

export function submitWithDraw(price,bank_id)
{
	return request({
	  url: '/user.price/pay_bank',
	  method: 'post',
		data: { price:price,bank_id:bank_id }
	})
}