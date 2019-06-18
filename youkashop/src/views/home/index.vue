<template>
  <div class="youga-home">
    <Header/>
    <div class="youga-home-content">
      <div class="yg-banner">
				<van-swipe :autoplay="3000" indicator-color="white">
					<van-swipe-item v-for="banner in homeBanner">
						<img class="w-100" :src="banner.file.file_path" alt="banner">
					</van-swipe-item>
				</van-swipe>
      </div>
      <div class="yg-content px-1 position-relative">
        <div class="yg-info-img display-flex w-100">
          <div class="yg-banner1">
              <img src="../../assets/home/1.png" alt="">
              <p>厂家直销</p>
          </div>
          <div class="yg-banner1">
              <img src="../../assets/home/2.png" alt="">
              <p>无中间价</p>
          </div>
          <div class="yg-banner1">
              <img src="../../assets/home/3.png" alt="">
              <p class="yg-banner1-space1"></p>
              <p>品控质检</p>
          </div>
          <div class="yg-banner1">
              <img src="../../assets/home/4.png" alt="">
              <p class="yg-banner1-space2"></p>
              <p>一键分销</p>
          </div>
        </div>
      </div>
      <div class="yg-content-nav position-relative">
        <van-tabs v-model="active">
          <van-tab :title="item.name" v-for="(item) in categoryList" :key="item.category_id">
            <van-row class="px-1 yg-bg-fff" v-for="(items, index) in item.list" :key="items.goods_id" :class="{ 'mt-1': index !== 0 }">
              <div @click="goToItem(items.goods_id)">
                
                <van-col v-if="items.is_hot === 1" span="12" class="text-right yg-list-tags py-1">
                  <van-tag type="danger">
                    <van-icon name="fire"/>热销</van-tag>
                </van-col>
                <van-col span="24" class="text-center py-1 py-2">
                  <img :src="items.file.file_path" class="w-100" alt="商品图">
                </van-col>
                <van-col span="18" class="text-left yg-list-tag-title py-1">{{ items.goods_name }}</van-col>

                <van-col span="6" class="text-right py-1 yg-list-price">￥{{items.goods_min_price}}</van-col>
                <van-col span="24" class="display-flex">
                  <van-col span="12" class="text-left pb-1 yg-list-labels">
                  <span v-for="(itemTag, index) in items.tags" :key="index">{{itemTag.tags_name}}</span>
                </van-col>
                <van-col span="12" class="text-right pb-1 yg-list-sales">销量 {{items.goods_sales}}</van-col>
                </van-col>
              </div>
            </van-row>
          </van-tab>
        </van-tabs>
      </div>

    </div>
    <Tabbar/>
    <van-loading v-if="loading" class="yg-loading"/>
  </div>
</template>

<script>
import Header from '../../components/Header';
import Tabbar from '../../components/Tabbar';

import {getHomeApi} from '../../api/home'

export default {
  components: {
    Header,
    Tabbar
  },

  data() {
    return {
      active: 0,
			page_show: false,
      loading: false,
      homeBanner: null,
      categoryList: null,
      totalList:[]
    };
  },

  methods: {
    // 首页信息
    getHomeA() {
      const self = this;
      //self.loading = true;
      getHomeApi().then(response => {
        // console.log(response)

        self.homeBanner = response.data.banner;
        // 商品列表
        self.categoryList = response.data.category;
        
        var maleList = response.data.category[0].list;
        var femaleList = response.data.category[1].list;

        // self.totalList.push(maleList[0])
        // self.totalList.push(maleList[1])
        // self.totalList.push(femaleList[0])
        // self.totalList.push(femaleList[1])
        // console.log("totalList:",self.totalList);
        // console.log("商品:",self.categoryList);
        //self.loading = false;
				self.page_show = true
      }).catch(error => {
        //self.loading = false;
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
    // 滚动固定顶部
    handleScroll(){
      let scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
      let tabTop = document.getElementsByClassName("van-tabs__wrap")[0];
      if(scrollTop >= 370){
        tabTop.style.position = "fixed";
        tabTop.style.top = '0';
        tabTop.style.zIndex = '999';
      }else{
        tabTop.style.position = '';
      }
    }
  },
  created() {
    const self = this;
    self.getHomeA();
  },
  mounted(){
    window.addEventListener('scroll',this.handleScroll);
  },
  destroyed(){
    window.removeEventListener('scroll',this.handleScroll);
  }
};
</script>

<style lang="less">
.yg-header-title{
      font-size: 24px;
    font-weight: 600;
}
.youga-home-content {
  .yg-content{
    border: 1px solid #EEE;
    background: #fff;
    border-radius: 10px;
    padding: 15px 0;
    width: 90%;
    bottom: 60px;
    left: 20px;
  }
  .yg-info-img {
    bottom: 1rem;
    flex: 1;
    .yg-banner1{
      width: 25%;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      align-items: center;
      img{
        width: 50%;
      }
      p{
        font-size: 14px;
        margin: 0;
        
      }
      .yg-banner1-space1{
        height: 1px;
      }
      .yg-banner1-space2{
        height: 4px;
      }
    }
  }

  .yg-content-nav {
    bottom: 1rem;
    padding-bottom: 1.75rem;

    .yg-bg-fff {
      background-color: #fff;
    }

    .yg-list-tag-title {
      font-size: 0.5rem;
    }

    .yg-list-tags {
      display: flex;
      position: absolute;
      right: 10px;
      flex-direction: row-reverse;
      .van-tag {
        font-size: 0.3rem;
        padding: .3em 1em;
      }
    }

    .yg-list-title {
      font-size: 0.4rem;
    }

    .yg-list-price {
      font-size: 0.4rem;
      color: #e83936;
      font-weight: 600;
    }

    .yg-list-labels {
      display: flex;
      span {
        font-size: 0.35rem;
        color: #777;
        margin-left: 5px;
        margin-right: 5px;
      }
    }

    .yg-list-sales {
      font-size: 0.35rem;
      color: #777;
    }

    .van-tabs {
      .van-tabs-wrap{
        margin-bottom: 30px;
      }
      .van-tab {
        font-size: 0.4rem;
        
      }
      .van-tab:first-child{
        border-right: 1px solid #777;
      }
      .py-2{
        padding-bottom: 0;
        margin-bottom: -10px;
      }
     
    }
     .van-tabs__wrap .van-tab::after{
          content:" ";
          position: absolute;
          right: 0;
          top: 18px;
          height: 18px;
          z-index: 9999;
          border-right: 1px solid rgb(155, 152, 152);
      }
      .van-tabs__wrap .van-tab:last-child{
        &::after{
          border: 0;
        }
      }
  }
}
</style>
