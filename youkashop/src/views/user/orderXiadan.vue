
<template>
    <div id="orderXiadan">
        <van-nav-bar class="yg-nav-bar" title="订货下单" left-arrow @click-left="onClickLeft"/>

    <div v-if="hasData" class="order_list">
        <ul>
            <li v-for="(item,index) in List" :key="index" @click="goToItem(item.goods_id)">
				<div class="li_left">
				    <div class="li_img">
				        <img :src="item.image[0].file_path" alt="">
				    </div>
				</div>
				<div class="li_right">
				    <p class="li_title">{{item.goods_name}}</p>
				    <p class="li_price">￥{{item.goods_min_price}}</p>
				    <p class="li_amount">销量:{{item.goods_sales}}</p>
				</div>
            </li>
        </ul>
    </div>
    <div v-else class="yg-no-data text-center">
      <div class="img">
        <img src="data:image/png;base64,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" alt="没有数据">
      </div>
      <div class="title">
        您的订单竟然是空的！
      </div>
    </div>

    <van-loading v-if="loading" class="yg-loading"/>
    </div>
</template>
<script>
import {getGoodsListApi} from '../../api/list'
export default {
  name: 'orderXiadan',
  data () {
    return {
      loading: true,
	  List:[
          {
             pic:"",
             title:"女士纯棉船袜",
             price:"136.00",
             amount:32,
             goods_id:12 
          },
          {
             pic:"",
             title:"女士纯棉船袜",
             price:"136.00",
             amount:32,
             goods_id:12  
          },
          {
             pic:"",
             title:"女士纯棉船袜",
             price:"136.00",
             amount:32,
             goods_id:12  
          }
      ],
      currentPage: 1,
      totalCount: null,
      hasData: true,
      loading:false
    }
  },
  mounted () {
    const self = this;
    
	

  },
  created() {
  	getGoodsListApi().then(res => {
		this.List = res.data.list.data;
	})
  },
  methods:{
    /**
     * 返回
     */
    onClickLeft() {
      const self = this;
      self.$router.go(-1);
    },
    // 页面跳转
    goToPage(pageName) {
      const self = this;
      self.$router.push({
        path: pageName
      })
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
    },
  }
}
</script>
<style lang="less" scoped>

    #orderXiadan{
        background: #ffffff;
        .order_list{
            ul{
                li{
                    list-style: none;
                    display: flex;
                    margin: 10px 0;
                    .li_left{
                        flex: 1;
                        display: flex;
                        margin: 0 15px;
                        
                        justify-content: center;
                        align-items: center;
                        .li_img{
                            width: 60px;
                            height: 60px;
                            // border: 1px solid red;
                            img{
								width: 100%;
								height: 100%;
                            }
                        }
                        
                    }
                    .li_right{
                        flex: 3;
                        p{
                            margin: 10px 0;
                        }
                        .li_title{
                            font-size: 16px;
                        }
                        .li_price{
                            font-size: 20px;
                            color: #FA4E1B;
                        }
                        .li_amount{
                            font-size: 14px;
                            color: rgb(185, 183, 183);
                        }
                    }
                }
            }
        }
    }
</style>
