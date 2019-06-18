<template>
  <div class="yg-userQR">
    <van-nav-bar class="yg-nav-bar" title="我的二维码" left-arrow @click-left="onClickLeft"/>
    <div class="user-qr-content" ref="userQRRef">
      <div class="title">邀请成功获得奖励</div>
      <div class="content">
        <div class="sub">我的邀请码为</div>
        <div class="invite-code">{{code}}</div>
        <div class="tips">邀请好友可得现金哦</div>
        <div class="qr-img">
          <img :src="qrcode" alt="二维码">
        </div>
        <div class="yg-share">
          <van-button class="my-share">查看我的邀请</van-button>
        </div>
      </div>
    </div>
		<van-loading v-if="loading" class="yg-loading"/>
  </div>
</template>

<script>
	import {getUserInfoApi} from '../../api/user'
export default {
  name: "userQR",
  components: {
  },
  data() {
    return {
			loading:true,
			code:'',
			qrcode: '',
			
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
		// 获取用户信息
		getUserInfo() {
		  const self = this;
		  getUserInfoApi().then(response => {
		    console.log(response);
				self.code = response.data.userInfo.invite_code
				self.qrcode = response.data.qrcode;
		    self.loading = false;
		  }).catch(error => {
		    console.log(error)
		  })
		},
  },
  mounted() {
    const self = this;
		self.getUserInfo();
    self.$refs.userQRRef.style.height = window.screen.height - 47 - 4 + 'px';
  }
}
</script>

<style lang="less">
.yg-userQR {
  .user-qr-content {
    background-color: #D8F5F5;
    padding: 0 1rem;
    .title {
      text-align: center;
      font-size: 36px;
      color: #00BD71;
      padding: 0.5rem 0;
    }
    .content {
      text-align: center;
      background-color: #fff;
      .sub {
        font-size: 16px;
        color: #333;
        padding: 0.5rem 0 0.25rem 0;
      }
      .invite-code {
        font-size: 29px;
        color: #FC6B50;
        padding: 0.25rem 0;
      }
      .tips {
        font-size: 18px;
        color: #999;
        padding: 0 0 0.5rem 0;
      }
      .qr-img {
        padding: 0.25rem 0;
        img {
          width: 128px;
        }
      }
      .yg-share {
        width: 100%;
        text-align: center;
        padding-bottom: 0.5rem;
        .my-share {
          font-size: 13px;
          color: #00BD71;
          background: #E8FFF2;
          border-radius: 50px;
        }
      }
    }
  }
}
</style>
