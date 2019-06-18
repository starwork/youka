<template>
  <div class="youga-cart">
    <Header/>
    <div v-if="hasData" class="youga-cart-content">
      <van-row class="yg-cart-header">
        <van-col span="12" class="text-left yg-cart-title">购物车</van-col>
<!--        <van-col span="12" class="text-right yg-cart-manage">管理</van-col>-->
        <van-col span="24" class="text-left yg-cart-quantity">共{{goodsList.length}}件宝贝</van-col>
      </van-row>
      <div class="yg-cart-content-offset position-relative">
        <div class="yg-cart-list">
          <div class="yg-cart-list-item">
<!--            <div class="yg-select-all">-->
<!--              <van-checkbox v-model="checked" class="title" checked-color="#00BD71">悠咖外贸直销厂商</van-checkbox>-->
<!--            </div>-->
            <van-checkbox-group class="card-goods" v-model="checkedGoods">
              <van-swipe-cell :right-width="65" :left-width="65" class="address-item" v-for="item in goodsList" :key="item.goods_id">
                <van-checkbox label-disabled class="card-goods__item" checked-color="#00BD71" :name="itemId(item)">
                  <van-card    :thumb="item.image[0].file_path">
                    <div slot="title" class="goods-title" @click="gotoDetailGoods(item.goods_id)">
                      <span class="goods-title">{{item.goods_name}}</span>
                    </div>
                    <div slot="desc" class="goods-standard">
                      <span class="goods-standard">{{item.standard}}</span>
                    </div>
                    <div slot="price" class="goods-price">
                      <span class="sum">{{item.goods_price}}</span><span class="unit">元</span>
                    </div>
                    <div slot="num" class="goods-numbers">
                      <van-stepper  v-model="item.total_num" disable-input @plus="AddCart(item.goods_id,item.goods_sku_id)" @minus="SubCart(item.goods_id,item.goods_sku_id)"/>
                    </div>
                  </van-card>
                </van-checkbox>
                <div slot="right" class="yg-swtch" @click="deleteSwtichRight(item.goods_id,item.goods_sku_id)">
                  <span class="title">删除</span>
                </div>
                <div slot="left" class="yg-swtch" @click="collectionSwtichRight(item.goods_id)">
                  <span class="title">收藏</span>
                </div>
              </van-swipe-cell>
            </van-checkbox-group>
          </div>
        </div>
        <van-submit-bar style="bottom:55.19px" :price="totalPrice" :disabled="!checkedGoods.length" :button-text="submitBarText" @submit="onSubmit">
          <van-checkbox checked-color="#00BD71" v-model="submitChecked" @click="selectAllGoodsList">全选</van-checkbox>
        </van-submit-bar>
      </div>
    </div>
    <div v-else class="yg-no-data text-center">
      <div class="img">
        <img src="data:image/png;base64,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" alt="没有数据">
      </div>
      <div class="title">
        购物车竟然是空的！
      </div>
    </div>
    <TabBar/>
  </div>
</template>

<script>
import Header from '../../components/Header';
import TabBar from '../../components/Tabbar';

import {getCartListApi, delGoodsToCartApi,addGoodsToCartApi,subGoodsToCartApi, cartGoodsToCollectApi, cartGoodsSettlementApi} from '../../api/cart'

export default {
	name:'cart',
  components: {
    Header,
    TabBar,
  },

  data() {
    return {
      active: 0,
	  tip:'',
      checkedGoods: [],
      goods: [
        {
        id: '1',
        title: '进口香蕉',
        desc: '约250g，2根',
        price: 200,
        num: 1,
        thumb: 'https://img.yzcdn.cn/public_files/2017/10/24/2f9a36046449dafb8608e99990b3c205.jpeg',
        standard: '三白俩灰',
      }, {
        id: '2',
        title: '陕西蜜梨',
        desc: '约600g',
        price: 690,
        num: 1,
        thumb: 'https://img.yzcdn.cn/public_files/2017/10/24/f6aabd6ac5521195e01e8e89ee9fc63f.jpeg',
        standard: '三白俩灰',
      }, {
        id: '3',
        title: '美国伽力果',
        desc: '约680g/3个',
        price: 2680,
        num: 1,
        thumb: 'https://img.yzcdn.cn/public_files/2017/10/24/320454216bbe9e25c7651e1fa51b31fd.jpeg',
        standard: '三白俩灰',
      }
      ],// 测试数据
      goodsList: [],

      checked: false,
      submitChecked: false,

      hasData: false,

      goodsTotal: 0, // 商品总数
      goodsAllPrice: 0, // 商品总价格
    };
  },
  watch:{
	  'checkedGoods': function(){
		  if(this.checkedGoods.length == this.goodsList.length){
			  this.submitChecked = true;
		  }else{
			  this.submitChecked = false;
		  }
	  },
  },
  computed: {
    itemId(){
      return function(item){
        return item.goods_id+'-'+item.goods_sku_id;
      }
    },
    submitBarText() {
      const self = this;
      self.goodsTotal = 0;
      self.checkedGoods.map(item => {
        self.goodsList.map(items => {
          var value = items.goods_id+'-'+items.goods_sku_id;
          if(item === value) {
            self.goodsTotal = parseInt(self.goodsTotal) + parseInt(items.total_num);
          }
        })
      });
      return '结算' + (self.goodsTotal !== 0 ? `(${self.goodsTotal})` : '');
    },
    totalPrice() {
      const self = this;
      self.goodsAllPrice = 0;
      self.checkedGoods.map(item => {
        self.goodsList.map(items => {
          var value = items.goods_id+'-'+items.goods_sku_id;
          if(item === value) {
            self.goodsAllPrice = self.goodsAllPrice + (Number(items.goods_price) * 100) * items.total_num;
          }
        })
      });
      return self.goodsAllPrice;
    }
  },
  methods: {
    formatPrice(price) {
      return (price / 100).toFixed(2);
    },
    onSubmit() {
      const self = this;
      // self.$toast('点击结算');
	  var str = '';
	  self.checkedGoods.map(item => {
	    self.goodsList.map(items => {
	      var value = items.goods_id+'-'+items.goods_sku_id;
	      if(item === value) {
	        str += items.goods_id+'-'+items.goods_sku_id+'-'+items.total_num+','
	      }
	    })
	})
		console.log(str);
      self.$router.push({
        path: '/order/submit',
        query:{
          item: str,
          type: 'cart'
        }
      })
      return false;
      cartGoodsSettlementApi().then(response => {
        response.code === 1 ?
          self.$notify({
            message: '付款成功，请到订单查看！',
            background: '#00BD71',
          })
          :
          '';
      })
    },
    // 购物车列表
    getCartList(){
      const self = this;
      getCartListApi().then(response => {
        self.goodsList = response.data.goods_list;
        self.hasData = response.data.goods_list.length > 0;
        // 勾选id
        self.goodsList.map(item => {
           var value = item.goods_id+'-'+item.goods_sku_id;
          self.checkedGoods.push(value);
        });
        self.submitChecked = true;
      })
    },
	ChangeGoodsNum(value){
		if (this.changing) {
			return;
		}
		this.changing = true;
	},
	AddCart(goods_id,goods_sku_id){
		addGoodsToCartApi({goods_id:goods_id,goods_sku_id:goods_sku_id}).then(res => {
			console.log(res)
		})
	},
	SubCart(goods_id,goods_sku_id){
		subGoodsToCartApi({goods_id:goods_id,goods_sku_id:goods_sku_id}).then(res => {
			console.log(res)
		})
	},
    // 全选商品
    selectAllGoodsList() {
      const self = this;
      if(self.submitChecked) {
        self.checkedGoods = [];
      } else {
        // 勾选id
        self.checkedGoods = [];
        self.goodsList.map(item => {
          var value = item.goods_id+'-'+item.goods_sku_id
          console.log(value)
          self.checkedGoods.push(value);
        });
      }
    },
    // 滑动删除
    deleteSwtichRight(id, sid) {
      const self = this;
      self.$dialog.confirm({
        title: '确定删除',
        message: '该操作无法撤回，请确定是否继续操作？'
      }).then(() => {
        self.delGoodsToCart(id, sid);
      }).catch(() => {
        self.$notify({
          message: '已取消操作',
          background: '#00BD71',
        })
      });
    },
    // 滑动收藏
    collectionSwtichRight(id) {
      const self = this;
      cartGoodsToCollectApi({
        goods_id: id
      }).then(response => {
        if(response.code === 1) {
          self.$notify({
            message: '添加到收藏成功！',
            background: '#00BD71',
          })
        }
      })
    },
    // 删除商品
    delGoodsToCart(id,sid) {
      const self = this;
      delGoodsToCartApi({
        goods_id: id,
        goods_suk_id: sid
      }).then(response => {
        if (response.code === 1) {
          self.$notify({
            message: '删除成功！',
            background: '#00BD71'
          });
          // 刷新列表
          self.getCartList();
        }
      })
    },
	// 跳转详情页
    gotoDetailGoods(id){
        const self = this;
        self.$router.push({
          path: '/item',
          query: {
            id: id
          }
        })
    },
  },
  created() {
    const self = this;
    self.getCartList();
  }
};
</script>

<style lang="less">
.youga-cart {
  .header {
    .van-nav-bar {
      background: @mainGradientColor;

      &[class*=van-hairline]::after {
        border: none;
      }

      i,
      .yg-header-title {
        color: #fff !important;
        font-size: 24px;
        font-weight: 600;

      }
    }
  }

  .youga-cart-content {

    .yg-cart-header {
      height: 150px;
      background: @mainGradientColor;
    }

    .yg-cart-title {
      padding-top: 0.6rem;
      padding-left: 0.25rem;
      color: #fff;
      font-size: 0.5rem;
    }

    .yg-cart-manage {
      padding-top: 0.6rem;
      padding-right: 0.25rem;
      color: #fff;
      font-size: 0.4rem;
    }

    .yg-cart-quantity {
      padding-top: 0.4rem;
      padding-left: 0.25rem;
      color: #fff;
      font-size: 0.4rem;
    }

    .yg-cart-content-offset {
      position: relative;
      bottom: 1.25rem;
      padding-right: 0.25rem;
      padding-left: 0.25rem;

      .yg-cart-list {
        padding: 0.25rem 0;
        background-color: #fff;
        border-radius: 0.25rem;

        .yg-cart-list-item {
          .yg-select-all {
            padding: 0 0.25rem 0.25rem 0.25rem;

            .van-checkbox__label {
              font-size: 0.4rem;
            }

            .van-checkbox__icon {
              vertical-align: inherit;
            }
          }
        }
      }

      // 提交按钮
      .van-submit-bar {
        .van-submit-bar__bar {
          .van-checkbox {
            padding-left: 0.5rem;
          }
          .van-button {
            background-color: #00BD71;
            border: 0.02667rem solid #00BD71;
            border-radius: 1.33333rem;
            height: 1rem;
            line-height: 1rem;
            margin-right: 0.5rem;
          }
        }
      }
    }

    .card-goods__item {
      position: relative;
      background-color: #fff;
    }

    .card-goods__item .van-checkbox__label {
      width: 100%;
      height: auto;
      padding: 0 0.25rem 0 0.4rem;
      box-sizing: border-box;
    }

    .card-goods__item .van-checkbox__icon {
      top: 50%;
      left: 0.25rem;
      z-index: 1;
      position: absolute;
      margin-top: -0.25rem;
    }

    .card-goods__item .van-card__price {
      color: #f44;
    }

    .van-card {
      background-color: #fff;

      .van-card__title {
        font-size: 0.4rem;
      }
      .goods-title{
        font-size: 0.4rem;
        color: #333;
        font-weight: 600;
        padding-top: 0.25rem;
      }

      .goods-standard {
        color: @titleColor;
        margin-top: 0.3rem;
      }

      .van-card__bottom {
        margin-top: 0.3rem;

        .van-card__price {
          .goods-price {
            .sum {
              font-size: 0.45rem;
              font-weight: 600;
            }

            .unit {
              color: @titleColor;
              margin-left: 0.05rem;
            }
          }
        }
      }
    }
  }

  .van-swipe-cell__right {
    width: 65px;
    height: 100%;
    right: -1px;
    .yg-swtch {
      background-color: #FA4E1B;
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      .title {
        align-self: center;
        color: #fff;
        font-size: 14px;
      }
    }
  }

  .van-swipe-cell__left {
    width: 65px;
    height: 100%;
    left: -1px;
    .yg-swtch {
      background-color: #f0ad4e;
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      .title {
        align-self: center;
        color: #fff;
        font-size: 14px;
      }
    }
  }
}
</style>
