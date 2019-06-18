<template>
  <div class="yg-item">
    <van-nav-bar class="yg-nav-bar" title="商品详细" left-arrow @click-left="onClickLeft"/>
    <div class="item-content" v-if="goodList.goods_name">
      <div class="goods">
        <van-swipe class="goods-swipe" :autoplay="3000">
          <van-swipe-item v-for="thumb in goodList.image" :key="thumb.image_id">
            <img :src="thumb.file_path" >
          </van-swipe-item>
        </van-swipe>

        <van-cell-group>
          <van-cell>
            <div class="goods-price">{{ defaultGoodInfo.goods_price }}</div>
            <div class="goods-title">{{ goodList.goods_name }}</div>
          </van-cell>
          <van-cell class="goods-express">
            <van-col span="10">运费：{{ goodList.delivery.name }}</van-col>
            <van-col span="14">销量：{{ goodList.goods_sales }} 件</van-col>
          </van-cell>
        </van-cell-group>

        <van-cell-group class="goods-info-group">
          <van-cell class="goods-info">
            <van-col span="24" class="title">商品详细</van-col>
            <van-col span="24" class="detail" v-html="goodList.content"></van-col>
<!--            <van-col span="8" class="left">规格</van-col>-->
<!--            <van-col span="16" class="right">{{goodList.spec_type}}</van-col>-->
<!--            <van-col span="8" class="left">重量</van-col>-->
<!--            <van-col span="16" class="right">21kg</van-col>-->
<!--            <van-col span="8" class="left">保质期</van-col>-->
<!--            <van-col span="16" class="right">12个月</van-col>-->
<!--            <van-col span="8" class="left">包装</van-col>-->
<!--            <van-col span="16" class="right">纸质</van-col>-->
<!--            <van-col span="8" class="left">存储方式</van-col>-->
<!--            <van-col span="16" class="right">阴凉干燥处</van-col>-->
          </van-cell>
        </van-cell-group>

        <van-cell-group class="goods-comment-group">
          <van-cell class="goods-comment" @click="goToComment(goodList.goods_id)">
            <van-col span="12" class="l-title">宝贝评价 ({{commentList.total}})</van-col>
            <van-col span="12" class="r-title">查看全部 <van-icon name="arrow"/></van-col>
						<div style="clear: both;"></div>
            <div class="comment-list">
              <div class="comment-item" v-for="item in commentList.data" :key="item.comment_id">
                <div class="comment-header">
                  <img :src="item.user.avatarUrl">
									<div class="comment-info">
									  <div class="name">{{item.user.nickName}} <span class="fx">{{item.user.level.level_name}}</span></div>
									  <div class="time">{{item.update_time}}</div>
									</div>
                </div>
								<div class="content">{{item.content}}</div>
              </div>
            </div>
          </van-cell>
        </van-cell-group>

        <van-cell-group class="goods-recommend-group">
          <van-cell class="goods-recommend">
            <van-col span="24" class="title">推荐商品</van-col>
            <van-row>
              <van-col span="8" v-for="item in BestList">
								<div @click="goToItem(item.goods_id)">
									<div class="image"><img :src="item.image[0].file_path"/></div>
									<div class="g-title">{{item.goods_name}}</div>
									<div class="price">￥<span>{{item.spec[0].goods_price}}</span></div>
								</div>
              </van-col>
            </van-row>
          </van-cell>
        </van-cell-group>

        <van-goods-action>
          <van-goods-action-mini-btn icon="cart-o" :info="cart_total_num" @click="onClickCart">
            购物车
          </van-goods-action-mini-btn>
          <van-goods-action-big-btn class="btn-1" @click="addCartBase">
            加入购物车
          </van-goods-action-big-btn>
          <van-goods-action-big-btn class="btn-2" @click="nowBuyBase">
            自己买
          </van-goods-action-big-btn>
          <van-goods-action-big-btn class="btn-3" @click="undone">
            去推广
          </van-goods-action-big-btn>
        </van-goods-action>
      </div>

      <van-sku
        v-model="showBase"
        :sku="skuData.sku"
        :goods="skuData.goods_info"
        :goods-id="skuData.goods_id"
        reset-stepper-on-hide
        reset-selected-sku-on-hide
        disable-stepper-input
        :close-on-click-overlay="closeOnClickOverlay"
        @add-cart="onAddCartClicked"
      >
        <template slot="sku-actions" slot-scope="props">
          <div class="van-sku-actions">
            <van-button class="yg-main-btn" :loading="btnLoading" bottom-action @click="props.skuEventBus.$emit('sku:addCart')">确定</van-button>
          </div>
        </template>
      </van-sku>
    </div>
		
		<van-popup v-model="share_show" position="bottom">
			<div class="share">
				<div class="icon-box">
					<div class="item" @click="ShareWX">
						<img class="wx" src="../../assets/share/1.png" alt="">
						<p>微信</p>
					</div>
					<div class="item" @click="SharePYQ">
						<img class="pyq" src="../../assets/share/2.png" alt="">
						<p>朋友圈</p>
					</div>
					<div class="item" @click="ShareCopy">
						<img class="pyq" src="../../assets/share/3.png" alt="">
						<p>复制链接</p>
					</div>
				</div>
				<div class="footer" @click="share_show = false">取消</div>
			</div>
		</van-popup>
		<span class="copy" style="display: none;" :data-clipboard-text="url">复制</span>
  </div>
</template>

<script>
import {Toast} from 'vant';

import {getGoodsInfoApi} from '../../api/goods';
import {addGoodsToCartApi} from '../../api/cart';
import skuData from './data';
import {getWechatInit,isWeiXin} from '../../api/wechat';
import wx from 'weixin-js-sdk';
import Clipboard from 'clipboard'
export default {
  name: "item",
  components: {
  },
  data() {
    //this.skuData = skuData;
    return {
			share_show:false,
			url:'',
	  cart_total_num: 0,
      showBase: false,
      closeOnClickOverlay: true,

      goodList: {
        spec: [{
          goods_price: ''
        }],
        delivery: {}
      },
      defaultGoodInfo: {},
      commentList: {},
      skuData: skuData,
			BestList: [],	//推荐商品

      bugType: 1, // 够买类型
      btnLoading: false,
    }
  },
  methods: {
    /**
     * 返回
     */
    onClickLeft() {
      const self = this;
      self.$router.go(-1);
    },

		ShareWX(){
			const self = this;
			wx.ready(function(){
				wx.updateAppMessageShareData({
					title: self.goodList.goods_name,
					link: window.location.href,
				  imgUrl: 'http://youka.qiniuniu.com/logo.jpg', // 分享图标
				  success: function () {
							// 设置成功
							self.$toast('分享成功');
						}
				})
			})
			
		},
		SharePYQ(){
			const self = this;
			wx.ready(function(){
				wx.updateTimelineShareData({
					title: self.goodList.goods_name,
					link: window.location.href,
				  imgUrl: 'http://youka.qiniuniu.com/logo.jpg', // 分享图标
				  success: function () {
							// 设置成功
							self.$toast('分享成功');
						}
				})
			});
			
		},
		ShareCopy(){
			let clipboard = new Clipboard('.copy')
			clipboard.on('success',res => {
				this.$toast('复制成功');
			});
		},

    formatPrice() {
      return '¥' + (this.goods.price / 100).toFixed(2);
    },
    onClickCart() {
      this.$router.push('cart');
    },
    sorry() {
      const self = this;
      self.$toast('该功能正在开发中！');
    },
    // 获取商品详细
    getGoodsInfo() {
			Toast.loading({
				message: '加载中...'
			});
      const self = this;
      getGoodsInfoApi(self.$route.query.id).then(response => {
        console.log(response);
				Toast.clear();
        self.goodList = response.data.detail;
        self.cart_total_num = response.data.cart_total_num
        self.defaultGoodInfo = response.data.detail.spec[0];
        self.commentList = response.data.comment;
				self.BestList = response.data.BestList
				self.skuData.goods_id = response.data.detail.goods_id
				self.skuData.sku = response.data.sku;
				self.skuData.sku.stock_num = response.data.detail.stock_num
				self.skuData.goods_info = {
					title : response.data.detail.goods_name,
					picture: response.data.detail.image[0].file_path
				}
      })
    },
    // 加入购物车
    addCartBase() {
      const self = this;
      self.showBase = true;
      self.bugType = 1;
    },
    // 为自己购买
    nowBuyBase() {
      const self = this;
      self.showBase = true;
      self.bugType = 0;
    },
    // 打开规格后确定按钮
    onAddCartClicked(data) {
			console.log(data)
      const self = this;
      // 添加到购物车
      if(self.bugType === 1) {
				self.btnLoading = true;
        addGoodsToCartApi({
          goods_id: data.goodsId,
          goods_num: data.selectedNum,
          goods_sku_id: data.selectedSkuComb.id,
        }).then(response => {
          if(response.code === 1) {
			  self.cart_total_num = response.data.cart_total_num
            self.$notify({
              message: '添加成功！',
              background: '#00BD71'
            });
          }
					self.showBase = false;
          self.btnLoading = false;
        })
      }
      // 为自己购买
      if(self.bugType === 0) {
				var item = data.goodsId+'-'+data.selectedSkuComb.id+'-'+data.selectedNum;
				self.$router.push({
					path: '/order/submit',
					query:{
						item: item
					}
				})
        //self.$toast('该业务还未完成！')
      }

    },

		goToComment(goods_id){
			console.log('123123')
			const self = this;
			self.$router.push({
				path: '/comment',
				query:{
					goods_id: goods_id
				}
			})
		},
    //未完成
    undone() {
      const self = this;
      self.$toast('该业务未完成！');
    },
		// 跳转到详细页
		goToItem(id) {
		  const self = this;
		  self.$router.push({
		    path: '/item',
		    query: {
		      id: id
		    }
		  })
		}
  },
	watch:{
		'$route' (to, from) {
			this.$router.go(0);
	 }
	},
	activated(){
		
	},
  created() {
    const self = this;
    self.getGoodsInfo();
		self.url = window.location.href;
  },
  mounted() {
    const self = this;
		if(isWeiXin()){
			getWechatInit().then(res => {
				console.log(res)
				wx.config(res.data);
				
				wx.ready(function(){
					wx.updateAppMessageShareData({
						title: self.goodList.goods_name,
						link: window.location.href,
					  imgUrl: 'http://youka.qiniuniu.com/logo.jpg', // 分享图标
					  success: function () {
								// 设置成功
							}
					})
					
					wx.updateTimelineShareData({
						title: self.goodList.goods_name,
						link: window.location.href,
					  imgUrl: 'http://youka.qiniuniu.com/logo.jpg', // 分享图标
					  success: function () {
								// 设置成功
							}
					})
				})
			})
		}
  }
}
</script>

<style lang="less">
	.share{
		.icon-box{
			display: flex;
			justify-content: space-between;
			.item{
				flex: 1;
				text-align: center;
				padding: 40px 0;
				img.wx{
					width: 38px;
					height: 33px;
				}
				img.pyq{
					width: 32px;
					height: 32px;
				}
				p{
					margin: 0;
					margin-top: 14px;
					font-size: 12px;
				}
			}
		}
		.footer{
			height: 50px;
			line-height: 50px;
			font-size: 16px;
			text-align: center
		}
	}
	.van-stepper__input[disabled]{
		color: #333;
	}
.goods {
  padding-bottom: 50px;
  &-swipe {
    img {
      width: 100%;
      display: block;
    }
  }
  &-title {
    font-size: 16px;
    color: #333;
    font-weight: 600;
    padding-top: 0.25rem;
  }
  &-price {
    font-size: 21px;
    color: #FA4E1B;
  }
  &-express {
    .van-cell__value--alone {
      color: #999;
      font-size: 12px;
    }
    padding: 5px 15px;
  }
  &-info-group {
    margin: 15px 0;
    .goods-info {
      .title {
        font-size: 16px;
        color: #333;
        font-weight: 600;
        padding-bottom: 0.25rem;
      }
      .left {
        font-size: 14px;
        color: #666;
        padding: 0.1rem 0;
      }
      .right {
        font-size: 14px;
        color: #333;
        font-weight: 500;
        padding: 0.1rem 0;
      }
    }
  }
  &-comment-group {
    margin: 15px 0;
    .goods-comment {
      .l-title {
        font-size: 16px;
        color: #333;
        font-weight: 600;
        text-align: left;
      }
      .r-title {
        font-size: 12px;
        color: #00BD71;
        text-align: right;
        .van-icon {
          position: relative;
          top: 1px;
        }
      }
      .comment-list {
        .comment-item {
          padding: 0.25rem 0.1rem;
          .comment-header {
						display: inline-flex;
            padding-right: 0.25rem;
            img {
              width: 28px;
							height: 28px;
							margin-right: 10px;
            }
          }
          .name {
            font-size: 12px;
            color: #333;
            .fx {
              font-size: 10px;
              color: #FB8642;
              border: 1px solid #FB8642;
              padding: 2px;
              border-radius: 5px;
              margin-left: 5px;
            }
            .jx {
              font-size: 10px;
              color: @mainColor;
              border: 1px solid @mainColor;
              border-radius: 5px;
              margin-left: 5px;
            }
          }
          .time {
            font-size: 10px;
            color: #D8D8D8;
          }
          .content {
            font-size: 12px;
            color: #333;
          }
        }
      }
    }
  }
  &-recommend-group {
    margin: 15px 0;
    .goods-recommend {
      .title {
        font-size: 16px;
        color: #333;
        font-weight: 600;
        padding-bottom: 0.25rem;
      }
      .image {
        overflow: hidden;
        padding: 0.1rem;
        img {
          width: 100%;
        }
      }
      .g-title {
        font-size: 12px;
        color: #333;
      }
      .price {
        font-size: 10px;
        color: #FA4E1B;
        span {
          font-size: 14px;
          font-weight: 500;
        }
      }
    }
  }
  &-cell-group {
    margin: 15px 0;
    .van-cell__value {
      color: #999;
    }
  }
  &-tag {
    margin-left: 5px;
  }
  &-info {
    .detail {
      img {
        width: 100%;
      }
    }
  }
  .van-goods-action {
    .btn-1 {
      background-color: #fff;
      border: none;
      font-size: 16px;
      color: #999;
    }
    .btn-2 {
      background-color: #fff;
      border: none;
      font-size: 16px;
      color: @mainColor;
    }
    .btn-3 {
      background-color: @mainColor;
      border: 1px solid @mainColor;
      font-size: 16px;
      color: #fff;
    }
    .van-info {
      background-color: @mainColor;
    }
  }
}
</style>
