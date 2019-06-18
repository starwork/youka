<template>
  <div class="youga-news">
    <Header/>
    <div class="youga-news-content">
      <van-row class="yg-news-header">
        <van-col span="24" class="text-left yg-news-title">消息</van-col>
        <van-col v-if="count > 0" span="24" class="text-left yg-news-quantity">有{{count}}条信息未读</van-col>
      </van-row>
      <div class="yg-news-content-offset position-relative">
        <div class="yg-news-all-list">
          <van-row class="yg-news-type">
            <van-col span="8">
              <div @click="toLogistics">
                <div class="news-img text-center position-relative">
                  <img src="data:image/png;base64,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" alt="交易物流">
					<div class="van-info" v-if="delivery_count > 0">{{delivery_count}}</div>
				</div>
                <div class="news-title text-center">交易物流</div>
              </div>
            </van-col>
            <div @click="toNotice">
              <van-col span="8">
                <div class="news-img text-center position-relative">
                  <img src="data:image/png;base64,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" alt="通知消息">
                 <div class="van-info" v-if="notice_count > 0">{{notice_count}}</div>
                </div>
                <div class="news-title text-center">通知消息</div>
              </van-col>
            </div>
            <van-col span="8">
              <div @click="toSystem">
                <div class="news-img text-center position-relative">
                  <img src="data:image/png;base64,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" alt="系统消息">
				<div class="van-info" v-if="system_count > 0">{{system_count}}</div>
                </div>
                <div class="news-title text-center">系统消息</div>
              </div>
            </van-col>
          </van-row>
          <div class="yg-news-content">
            <div v-for="(item, index) in newsList" :key="index" class="yg-news-content-item py-1">
              <div @click="goToLogis(item.id, newsType)">
                <van-row class="display-flex">
                  <van-col span="12" class="item-1">
                    {{item.title}}
                  </van-col>
                  <van-col span="12" class="item-2">
                    {{item.update_time}}
                  </van-col>
                </van-row>
                <van-row class="display-flex py-1">
                  <van-col v-if="newsType === 1" span="8" class="item-3 display-flex">
                    <img :src="item.image"/>
                  </van-col>
                  <van-col :span="newsType === 1 ? 16 : 24" :class="newsType === 1 ? 'item-4' : 'item-5 px-1'">
                    {{item.content}}
                  </van-col>
                </van-row>
              </div>
            </div>
          </div><!--物流信息-->
        </div>
      </div>
    </div>
    <Tabbar/>
		<van-loading v-if="loading" class="yg-loading"/>
  </div>
</template>

<script>
import Header from '../../components/Header';
import Tabbar from '../../components/Tabbar';

import {getNewsListApi} from '../../api/news';

export default {
  components: {
    Header,
    Tabbar,
  },

  data() {
    return {
			loading: false,
      newsType: 1, // 1=交易信息 | 2=通知消息 | 3=系统消息
      newsList: [],
      newsNum: null,
			delivery_count:0,
			notice_count:0,
			system_count:0,
      logisticsList: [
        {
          id: 1,
          time: '19/03/21',
          status: 0,
          title: '快递正在派送中',
          content: '您所购物品正配送中，收货时请检查快递外包装是否完整，确认无误再签收。',
          img: 'https://img.yzcdn.cn/public_files/2017/10/24/320454216bbe9e25c7651e1fa51b31fd.jpeg',
        },
        {
          id: 2,
          time: '19/03/21',
          status: 0,
          title: '快递正在派送中',
          content: '您所购物品正配送中，收货时请检查快递外包装是否完整，确认无误再签收。',
          img: 'https://img.yzcdn.cn/public_files/2017/10/24/320454216bbe9e25c7651e1fa51b31fd.jpeg',
        },
        {
          id: 3,
          time: '19/03/21',
          status: 0,
          title: '快递正在派送中',
          content: '您所购物品正配送中，收货时请检查快递外包装是否完整，确认无误再签收。',
          img: 'https://img.yzcdn.cn/public_files/2017/10/24/320454216bbe9e25c7651e1fa51b31fd.jpeg',
        },
      ],
      noticeList: [
        {
          id: 1,
          time: '19/03/21',
          status: 0,
          title: '分销商通知',
          content: '尊敬的会员可可西，您好，恭喜你在2019-03-21 成为优秀的分销商',
        },
        {
          id: 2,
          time: '19/03/21',
          status: 0,
          title: '分销商通知',
          content: '尊敬的会员可可西，您好，恭喜你在2019-03-21 成为优秀的分销商',
        }
      ],
      systemList: [
        {
          id: 1,
          time: '19/03/21',
          status: 0,
          title: '系统通知',
          content: '尊敬的会员可可西，您好，恭喜你在2019-03-21 成为优秀的分销商',
        },
        {
          id: 2,
          time: '19/03/21',
          status: 0,
          title: '系统通知',
          content: '尊敬的会员可可西，您好，恭喜你在2019-03-21 成为优秀的分销商',
        }
      ],
    };
  },

  methods: {
    /**
     * 跳转到物流交易页面
     */
    toLogistics() {
      const self = this;
      self.newsType = 1;
      // self.newsList = self.logisticsList;
      self.getNewsList('delivery');
    },
    /**
     * 跳转到通知消息
     */
    toNotice() {
      const self = this;
      self.newsType = 2;
      self.getNewsList('notice');
    },
    /**
     * 跳转到系统消息
     */
    toSystem() {
      const self = this;
      self.newsType = 3;
      self.getNewsList('system');
    },
    // 获取消息接口封装
    getNewsList(type) {
      const self = this;
			self.loading = true
			self.newsList = [];
      getNewsListApi(type).then(response => {
        console.log(response);
				self.loading = false
        self.newsList = response.data.list.data;
        self.count = response.data.message_num;
        self.delivery_count = response.data.delivery_count;
        self.notice_count = response.data.notice_count;
        self.system_count = response.data.system_count;
      })
    },
    // 跳转到物流信息
    goToLogis(id, type) {
      const self = this;
      console.log(id);
      console.log(type);
    }
  },
  created() {
    const self = this;
    self.getNewsList('delivery');
  }
};
</script>

<style lang="less">
.youga-news {
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

  .youga-news-content {

    .yg-news-header {
      height: 150px;
      background: @mainGradientColor;
    }

    .yg-news-title {
      padding-top: 0.6rem;
      padding-left: 0.25rem;
      color: #fff;
      font-size: 0.5rem;
    }

    .yg-news-quantity {
      padding-top: 0.4rem;
      padding-left: 0.25rem;
      color: #fff;
      font-size: 0.4rem;
    }

    .yg-news-content-offset {
      position: relative;
      bottom: 1.25rem;
      padding-right: 0.25rem;
      padding-left: 0.25rem;
      padding-bottom: 0.5rem;
      .yg-news-all-list {
        padding: 0.25rem 0;
        background-color: #fff;
        border-radius: 0.25rem;

        // 消息分类
        .yg-news-type {
          margin-bottom: 0.5rem;
          .news-img {
            img {
				margin: 0 auto;
			  display: block;
              width: 40px;
            }
            .van-info {
              top: 0;
              right: 40px;
            }
          }
          .news-title {
            font-size: 13px;
            color: @textColor;
          }
        }
      }

      .yg-news-content {
        .yg-news-content-item {
          .item-1 {
            color: #555;
            font-weight: 600;
            font-size: 17px;
            text-align: left;
            padding-left: 0.25rem;
          }
          .item-2 {
            color: #aeaeae;
            font-size: 12px;
            text-align: right;
            padding-right: 0.25rem;
            align-self: center;
          }
          .item-3 {
            justify-content: center;
            img {
              width: 80px;
              height: 80px;
            }
          }
          .item-4 {
            font-size: 14px;
            color: #555;
            align-self: center;
          }
          .item-5 {
            font-size: 14px;
            color: #555;
          }
        }
      }
    }
  }
}
</style>
