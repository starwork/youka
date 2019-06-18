<template>
	<div>
		<van-nav-bar class="yg-nav-bar" title="订单提交" left-arrow @click-left="onClickLeft"/>
		
		<div class="content">
			<van-cell-group>
			  <van-cell class="address" v-if="address" @click="ChangeAddress">
				<div slot="title">
				  <div class="address-title">
					  <div class="name">{{address.name}}</div>
					  <div class="phone">{{address.phone}}</div>
					  <div v-if="address.is_defalut == 1" class="is_defalut">默认</div>
				  </div>
				  <div class="address-sub-title">{{address.detail}}</div>
				</div>
				<van-icon slot="right-icon" name="arrow" class="custom-icon"/>
			  </van-cell>
			  <van-cell v-else>
				  <div slot="title" style="text-align: center;">
				    您还没有收货地址哦！
					<van-button plain hairline type="primary" @click="AddAddress">新增地址</van-button>
				  </div>
			  </van-cell>
			</van-cell-group>
			
			<van-cell-group class="goods-list">
			  <van-cell v-for="item in goodsList">
				<van-card
				  :num="item.total_num"
				  :price="item.goods_sku.goods_price"
				  :desc="item.goods_sku.goods_attr"  
				  :title="item.goods_name"
				  :thumb="item.image[0].file_path"
				>
				</van-card>
			  </van-cell>
			</van-cell-group>
			
			<div class="row">
				<label class="label">商品总价</label>
				<div>¥ {{total_price}}</div>
			</div>
			<div class="row">
				<label class="label">运费</label>
				<div>¥ {{express_price}}</div>
			</div>
			<div class="row" v-if="discount_price">
				<label class="label">优惠金额</label>
				<div>-{{discount_price}}</div>
			</div>
			<div class="row" v-if="discount_msg">
				<label class="label">优惠信息</label>
				<div>{{discount_msg}}</div>
			</div>
			<div class="row">
				<label class="label">订单备注</label>
				<van-field v-model="remark" placeholder="选填，请先和商家协商一致" />
			</div>
		</div>
		<div class="notice" >
			<van-notice-bar v-if="!exist_address"
			  text="收货地址不存在"
			  wrapable 
			  :scrollable="false"
			  left-icon="volume-o"
			/>
			<van-notice-bar v-if="has_error"
			  :text="error"
			  wrapable 
			  :scrollable="false"
			  left-icon="volume-o"
			/>
		</div>
		<div class="submit-bar van-hairline--top">
			<div class="price">¥ {{order_pay_price}}</div>
			<van-button :loading="submit_loading" :disabled="submit_disabled" @click="SubmitOrder">提交订单</van-button>
		</div>
		
		<van-popup v-model="showAddressList" position="bottom">
			<div class="address-list">
				<van-radio-group v-model="address_id">
					<van-cell-group>
						<van-cell class="address-item" style="" v-for="item in addressList" @click="SelectAddress(item)">
							<div slot="title" class="address-item-box">
								<div class="check">
									<van-radio :name="item.address_id"></van-radio>
								</div>
								<div>
									<div class="address-title">
									  <div class="name">{{item.name}}</div>
									  <div class="phone">{{item.phone}}</div>
									  <div v-if="item.is_defalut == 1" class="is_defalut">默认</div>
									</div>
									<div class="address-sub-title">{{item.detail}}</div>
								</div>
							</div>
							<van-icon slot="right-icon" name="edit" class="custom-icon" @click="editAddress(item.address_id)"/>
						</van-cell>
					</van-cell-group>
				</van-radio-group>
				<button class="yg-address-list_add van-button van-button--danger van-button--large van-button--square van-address-list__add" @click="AddAddress">
				  <span class="van-button__text">新增地址</span>
				</button>
			</div>
		</van-popup>
	</div>
	
</template>

<script>
	import {getOrderSubmit,SubmitOrder} from '../../api/order';
	import {getWechatInit,isWeiXin} from '../../api/wechat';
	import wx from 'weixin-js-sdk';
	export default{
		name:'order_sumbit',
		data(){
			return {
				type:'',
				address_id:0,
				address:{
					
				},
				submit_loading: false,
				submit_disabled: true,
				addressList:[],
				goodsList:[],
				total_price:0,
				order_pay_price:0,
				express_price:0,
				remark:'',
				showAddressList: false,
				exist_address: true,
				error:'',
			}
		},
		methods:{
			getData(){
				const self = this;
				var data= this.$route.query.item
				var type= this.$route.query.type
				self.type = type;
				console.log(data)
				getOrderSubmit(data,'').then(res =>{
					if(res.code == 1){
						self.exist_address = res.data.exist_address
						self.error = res.data.error_msg
						self.address_id = res.data.address ?  res.data.address.address_id : 0
						self.addressList = res.data.address_list
						self.goodsList = res.data.goods_list
						self.address = res.data.address
						self.total_price = res.data.goods_total_price
						self.express_price = res.data.express_price
						self.order_pay_price = res.data.order_pay_price
						self.discount_msg = res.data.discount_msg
						self.discount_price = res.data.discount_price
						if(!res.data.has_error && res.data.exist_address){
							self.submit_disabled = false
						}
					}
				})
			},
			ChangeAddress(){
				this.showAddressList = true;
			},
			SelectAddress(item){
				console.log(item)
				const self = this;
				self.address_id = item.address_id
				self.address = item;
				self.showAddressList = false;
				
			},
			onClickLeft() {
			  const self = this;
			  self.$router.go(-1);
			},
			SubmitOrder(){
				const self = this;
				var data= this.$route.query.item
				self.submit_loading = true;
				SubmitOrder(data,self.remark,self.address_id,self.type).then(res => {
					self.submit_loading = false;
					if(res.code == -2){
						//self.$toast('设置推荐人');
						self.$router.push({
							path: '/user/info',
							query:{
								type: 'referrer'
							}
						})
						return false;
					}
					
					var order_id = res.data.order_id;
					console.log(order_id);
					wx.chooseWXPay({
						timestamp: res.data.payment.timeStamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
						nonceStr: res.data.payment.nonceStr, // 支付签名随机串，不长于 32 位
						package: 'prepay_id='+res.data.payment.prepay_id, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=\*\*\*）
						signType: 'MD5', // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
						paySign: res.data.payment.paySign, // 支付签名
						success: function (res) {
							if(res.errMsg == "chooseWXPay:ok"){
								self.$dialog.alert({
									message: '支付成功'
								  }).then(() => {
									  self.toDeatil(order_id)
								  });
							}else if(res.errMsg == 'chooseWXPay:cancel'){
								self.$toast('支付取消');
								self.toDeatil(order_id)
							}
						}
					});
				})
			},
			toDeatil(order_id){
				this.$router.replace({
					path: '/order/detail',
					params: {
						order_id: order_id
					}
				})
			},
			AddAddress(){
				const self = this;
				self.$router.push({
				  path: '/user/addressAdd'
				})
			},
			// 修改地址
			editAddress(id) {
			  const self = this;
			  self.$router.push({
			    path: '/user/addressAdd',
			    query: {
			      id: id
			    }
			  });
			},
		},
		activated() {
			this.getData();
		},
		created(){
			this.getData();
			if(isWeiXin()){
				getWechatInit().then(res => {
					console.log(res)
					wx.config(res.data);
				})
			}
		},
		
	}
</script>

<style lang="less">
	.notice{
		position: fixed;
		left: 0;
		right: 0;
		bottom: 50px;
		width: 100%;
	}
	.content{
		padding-bottom: 50px;
	}
	.address::before{
		position: absolute;
		right: 0;
		bottom: 0;
		left: 0;
		height: 2px;
		background: repeating-linear-gradient(-45deg, #ff6c6c 0, #ff6c6c 20%, transparent 0, transparent 25%, #1989fa 0, #1989fa 45%, transparent 0, transparent 50%);
		background-size: 80px;
		content: '';
	}
	.address-item-box{
		display: flex;
		.check{
			margin-right: 20px;
		}
	}
	.address-item,.address{
		background: #fff;
		margin-top: 13px;
		font-size: 16px;
		.address-title{
			display: flex;
			.phone{
				margin: 0 13px 0 24px
			}
			.is_defalut{
				font-size: 12px;
				color: #00BD71;
				background: #E8FFF2;
				width: 32px;
				height: 19px;
				text-align: center;
			}
		}
		.address-sub-title{
			font-size: 12px;
		}
		.custom-icon {
		  align-self: center;
		  font-size: 18px;
		}
	}
	.goods-list{
		margin: 13px 0;
		.van-card{
			background: #fff;
		}
	}
	.row{
		background: #fff;
		padding: 0 14px;
		font-size: 14px;
		height: 45px;
		line-height: 45px;
		display: flex;
		.label{
			display: block;
			width: 83px;
		}
		input{
			flex: 1;
			border: none;
		}
	}
	.submit-bar{
		background: #fff;
		display: flex;
		justify-content: space-between;
		align-items: center;
		height: 50px;
		width: 100%;
		line-height: 50px;
		position: fixed;
		bottom: 0;
		.price{
			margin-left: 17px;
			font-size: 21px;
			color: #FA4E1B;
		}
		button{
			height: 50px;
			width: 126px;
			font-size: 18px;
			color: #fff;
			background: #00BD71;
		}
	}
	.address-list{
		width: 100%;
		height: 100vh;
	}
	.van-button--danger{
		background-color: #00BD71;
		border: 0.02667rem solid #00BD71;
	}
</style>
