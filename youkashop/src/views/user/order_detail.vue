<template>
	<div>
		<van-nav-bar class="yg-nav-bar" title="订单详情" left-arrow @click-left="onClickLeft"/>
		<div class="main" v-if="show">
			<div class="order-status">
				<div class="left">
					<img v-if="order.order_status.value == 30" src="../../assets/images/order/order-success.png" alt="">
					<img v-if="order.pay_status.value == 10  && order.order_status.value == 10" src="../../assets/images/order/pay.png" alt="">
					<img v-if="order.pay_status.value == 20 && order.delivery_status.value == 10 && order.order_status.value == 10" src="../../assets/images/order/delivery.png" alt="">
					<img v-if="order.delivery_status.value == 20 && order.receipt_status.value == 10 && order.order_status.value == 10" src="../../assets/images/order/receipt.png" alt="">
					<div class="status" v-if="order.receipt_status.value == 20 && order.order_status.value == 30">交易成功</div>
					<div class="status" v-if="order.pay_status.value == 10  && order.order_status.value == 10">{{order.pay_status.text}}</div>
					<div class="status" v-if="order.pay_status.value == 20 && order.delivery_status.value == 10 && order.order_status.value == 10">{{order.delivery_status.text}}</div>
					<div class="status" v-if="order.delivery_status.value == 20 && order.receipt_status.value == 10 && order.order_status.value == 10">{{order.receipt_status.text}}</div>
					<div class="status" v-if="order.order_status.value == 20">订单已关闭</div>
				</div>
				<div class="status-r">{{order.desc}}</div>
			</div>
			<div class="order-address">
				<img src="../../assets/images/order/address.png" alt="">
				<div>
					<div class="address-title">
						<div class="address-name">{{order.address.name}}</div>
						<div class="address-phone">{{order.address.phone}}</div>
					</div>
					<div class="address-desc">收货地址：{{order.address.region.province}}{{order.address.region.city}}{{order.address.region.region}}{{order.address.detail}}</div>
				</div>
			</div>
			<div class="order-detail">
				<div class="goods-list">
					<div class="goods-item" v-for="(item,index) in order.goods">
						<img :src="item.image.file_path" alt="">
						<div class="goods-content">
							<div class="goods-name">{{item.goods_name}}</div>
							<div class="goods-desc">{{item.spec.goods_attr ? item.spec.goods_attr : ''}}</div>
							<div class="goods-price">¥{{item.goods_price}} <span>x{{item.total_num}}</span></div>
						</div>
					</div>
				</div>
				<div class="order-row">
					<div>商品总额</div>
					<div>¥{{order.total_price-order.express_price}}</div>
				</div>
				<div class="order-row">
					<div>运费</div>
					<div>+¥{{order.express_price}}</div>
				</div>
				<div class="line"></div>
				<div class="order-row order-price">
					<div>订单金额</div>
					<div class="price">¥{{order.total_price}}</div>
				</div>
			</div>
			
			<div class="order-list">
				<div class="order-row">
					<div>订单编号</div>
					<div>{{order.order_no}}</div>
				</div>
				<div class="order-row">
					<div>创建时间</div>
					<div>{{order.create_time}}</div>
				</div>
				<div class="order-row" v-if="order.pay_status.value == 20">
					<div>付款时间</div>
					<div>{{order.pay_time}}</div>
				</div>
				<div class="order-row" v-if="order.delivery_status.value == 20">
					<div>发货时间</div>
					<div>{{order.pay_time}}</div>
				</div>
				<div class="order-row" v-if="order.receipt_status.value == 20">
					<div>成交时间</div>
					<div>{{order.receipt_time}}</div>
				</div>
				<div class="order-row" v-if="order.remark">
					<div>备注</div>
					<div>{{order.remark}}</div>
				</div>
			</div>
			<div class="line"></div>
			<div class="order-row phone" @click="toHelper">
				<div>
					<img src="../../assets/images/order/telephone.png" alt="">
				联系卖家
				</div>
			</div>
			<div class="order-button">
				<button v-if="order.pay_status.value == 10 && order.order_status.value == 10" plain hairline class="cancel" @click="cancel(order.order_id)">取消订单</button>
				<button v-if="order.pay_status.value == 10 && order.order_status.value == 10" plain hairline @click="pay(order.order_id)">立即付款</button>
				<button v-if="order.pay_status.value == 20 && order.order_status.value == 10 && order.delivery_status.value == 10" plain hairline @click="delivery(order.order_id)">提醒发货</button>
				<button v-if="order.delivery_status.value == 20 && order.order_status.value == 10 && order.receipt_status.value == 10" plain hairline @click="received(order.order_id)">确认收货</button>
				<button v-if="order.order_status.value == 30 && order.comment_status == 10" plain hairline @click="comment(order.order_id)">立即评价</button>
				<button v-if="order.order_status.value == 20" plain hairline @click="del(order.order_id)" class="cancel">删除</button>
			</div>
		</div>
		<van-loading v-if="!show" class="yg-loading"/>
	</div>
</template>

<script>
import {getOrderDetail,CancelOrder,ReceiptOrder,PayOrder,Comment} from '../../api/order'
import {getWechatInit,isWeiXin} from '../../api/wechat';
import wx from 'weixin-js-sdk';
export default{
	name:'order_detail',
	data(){
		return {
			order: {},
			show:false,
			is_wx: false,
		}
	},
	methods:{
		onClickLeft(){
			const self = this;
      		self.$router.go(-1);
		},
		getDetail(order_id){
			getOrderDetail(order_id).then(res => {
				this.order = res.data.order
				this.show = true
			})
		},
		//订单取消
		cancel(order_id){
			const self = this;
			this.$dialog.confirm({
				message : '确认取消订单'
			}).then(() => {
				CancelOrder(order_id).then(res => {
					if(res.code == 1){
						self.$notify({
						  message: '订单已取消',
						  background: '#00BD71'
						});
						self.onClickLeft();
					}
				})
			})
			
		},
		//订单支付
		pay(order_id){
			const self = this;
			if(isWeiXin()){
				PayOrder(order_id).then(res => {
					console.log(res)
					wx.chooseWXPay({
						timestamp: res.data.timeStamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
						nonceStr: res.data.nonceStr, // 支付签名随机串，不长于 32 位
						package: 'prepay_id='+res.data.prepay_id, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=\*\*\*）
						signType: 'MD5', // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
						paySign: res.data.paySign, // 支付签名
						success: function (res) {
							if(res.errMsg == "chooseWXPay:ok"){
								self.$dialog.alert({
									message: '支付成功'
								  }).then(() => {
									  self.getDetail(order_id)
								  });
							}else if(res.errMsg == 'chooseWXPay:cancel'){
								self.$toast('支付取消');
								//self.getDetail(order_id)
							}else if(res.errMsg == 'chooseWXPay:fail'){
								self.$toast('支付失败');
								//self.toDeatil(order_id)
							}
							
						}
					});
				})
			}else{
				self.$toast('只支持微信浏览器');
			}
		},
		//提醒发货
		delivery(order_id){
			
		},
		//确认收货
		receipt(order_id){
			const self = this;
			self.$dialog.confirm({
				message: '你确定要确认收货吗？',
			}).then(() => {
				ReceiptOrder(order_id).then(res => {
					if(res.code == 1){
						self.$notify({
						  message: '收货成功',
						  background: '#00BD71'
						});
						self.getDetail(order_id)
					}
				})
			})
		},
		toDeatil(order_id){
			this.$router.push({
				path: '/order/detail',
				query:{
					order_id: order_id
				}
			})
		},
		toHelper(){
			this.$router.push({
				path: '/helper'
			})
		},
	},
	activated() {
		
	},
	created() {
		var order_id= this.$route.query.order_id
		this.getDetail(order_id);
		if(isWeiXin()){
			getWechatInit().then(res => {
				console.log(res)
				wx.config(res.data);
			})
		}
		
	}
}
</script>

<style lang="less">
.main{
	padding-bottom: 50px;
}
.order-status{
	height: 60px;
	line-height: 60px;
	background: url('../../assets/images/order/status_bg.png') no-repeat;
	background-size: 100%;
	display: flex;
	justify-content: space-between;
	align-content: center;
	padding: 0 15px;
	color: #fff;
	.left{
		height: 60px;
		display: flex;
		img{
			height: 19px;
			margin:20px 12px 0 17px;
		}
	}
	.status{
		font-size: 16px;
	}
	.status-r{
		font-size: 11px;
	}
}
.order-address{
	background: #fff;
	display: flex;
	padding: 13px 17px;
	img{
		margin-top: 6px;
		height: 13px;
		margin-right: 12px
	}
	.address-title{
		display: flex;
		font-size: 16px;
		height: 24px;
		line-height: 24px;
		font-weight: 600;
		.address-name{
			margin-right: 24px;
		}
	}
	.address-desc{
		font-size: 12px;
		height: 16px;
		color: #999;
	}
}
.goods-list{
	background: #fff;
	padding: 0 17px;
	margin-top: 13px;
	.goods-item{
		display: flex;
		padding: 13px 0;
		border-bottom: 1px solid #eee;
		img{
			width: 80px;
			height: 80px;
		}
		.goods-content{
			margin-left: 12px;
			.goods-name{
				font-size: 13px;
			}
			.goods-desc{
				font-size: 12px;
				color: #999;
			}
			.goods-price{
				display: contents;
				font-size: 13px;
				span{
					display: inline-block;
					margin-left: 10px;
					font-size: 11px;
					color: #999;
				}
			}
		}
	}
	.goods-item:last-child{
		border-bottom: none;
	}
}
.order-row{
	background: #fff;
	padding: 0 17px;
	display: flex;
	justify-content: space-between;
	font-size: 12px;
	color: #999;
	height: 30px;
	line-height: 30px;
}
.order-price{
	height: 43px;
	line-height: 43px;
	font-size:14px;
	.price{
		color: #FA4E1B;
	}
}
.order-list{
	margin-top: 13px;
}
.order-button{
	position: fixed;
	left: 0;
	right: 0;
	bottom: 0;
	height: 50px;
	background: #fff;
	padding: 0 17px;
	text-align: right;
	button{
		background: #fff;
		border: 1px solid #00BD71;
		margin-left: 12px;
		margin-top: 12px;
		height: 26px;
		line-height: 26px;
		padding: 0 10px;
		text-align: center;
		color: #00BD71 ;
		font-size: 13px;
	}
	.cancel{
		color: #999;
		border: 1px solid #999;
	}
}
.phone{
	height: 40px;
	line-height: 40px;
	align-items: center
}
.phone > div{
	margin: 0 auto;
	height: 40px;
	line-height: 40px;
}
.phone img{
	width: 17px;
	height: 17px;
	vertical-align: middle;
	margin-right: 10px;
}
.line{
	height: 1px;
	width: 100%;
	background: #eee;
}
</style>
