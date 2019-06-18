<template>
  <div class="yg-kanban">
    <van-nav-bar class="yg-nav-bar" title="运营看板" left-arrow @click-left="onClickLeft"/>
    <div class="user-kanban-content">
      <van-row class="kanban1">
        <van-col span="8">
          <div class="title">今日新增会员</div>
          <div class="value">{{detail.toady_user_count ? detail.toady_user_count : 0}}</div>
        </van-col>
        <van-col span="8">
          <div class="title">今日业绩</div>
          <div class="value">{{detail.toady_total ? detail.toady_total : 0}}</div>
        </van-col>
        <van-col span="8">
          <div class="title">今日直属下级业绩</div>
          <div class="value">{{detail.toady_child_total ? detail.toady_child_total : 0}}</div>
        </van-col>
      </van-row>
      <div class="kanban2">
        <div class="header-title">今日核心指标</div>
        <van-row>
          <van-col span="12">
            <div class="title">我的订单(件)</div>
            <div class="value">{{detail.order_count ? detail.order_count : 0}}</div>
          </van-col>
          <van-col span="12">
            <div class="title">下级订单(件)</div>
            <div class="value">{{detail.order_child_count ? detail.order_child_count :0}}</div>
          </van-col>
        </van-row>
        <van-row>
          <van-col span="12">
            <div class="title">邀请代理(人)</div>
            <div class="value">{{detail.toady_total ? detail.toady_total : 0}}</div>
          </van-col>
          <van-col span="12">
            <div class="title">分享销售(次)</div>
            <div class="value">{{detail.toady_total ? detail.toady_total : 0}}</div>
          </van-col>
        </van-row>
      </div>
    </div>
  </div>
</template>

<script>
import {getPanel} from '../../api/data'

export default {
  name: "kanban",
  components: {
  },
  data() {
    return {
			detail: {}
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
  },
  mounted() {
		const self = this;
		getPanel().then(res => {
			self.detail = res.data;
		})
  }
}
</script>

<style lang="less">
.yg-kanban {
  .user-kanban-content {
    .kanban1 {
      background-color: #fff;
      text-align: center;
      padding-top: 0.5rem;
      padding-bottom: 0.25rem;
      .title {
        font-size: 12px;
        color: #666666;
      }
      .value {
        font-size: 36px;
        color: #333333;
        font-weight: 600;
        padding: 0.25rem 0;
      }
    }
    .kanban2 {
      margin-top: 0.5rem;
      background-color: #fff;
      padding: 0.25rem 0.25rem 0.25rem 0.25rem;
      .header-title {
        font-size: 16px;
        color: #333;
        padding: 0.25rem 0 0.5rem 0;
      }
      .title {
        font-size: 12px;
        color: #999;
      }
      .value {
        font-size: 28px;
        color: #333;
        padding: 0.25rem 0;
      }
    }
  }
}
</style>
