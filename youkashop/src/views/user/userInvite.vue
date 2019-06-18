<template>
  <div class="yg-invite">
    <van-nav-bar class="yg-nav-bar" title="分享给好友" left-arrow @click-left="onClickLeft"/>
    <div class="user-invite-content" ref="userInviteRef">
      <div class="title">邀请函</div>
      <div class="sub-title">INVITATION</div>
      <div class="header-tips">赶快和我注册悠咖</div>
      <div class="header-tips">和我一起赚钱吧</div>
      <div class="content">
        <div class="bg-img">
          <img alt="背景图" src="data:image/png;base64,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CAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACAQTAALBBIBAMAEgEEwACC7bbs5eBABcuX9dtv109ioA4Mr9dNn27OxVAMCVe3bZ9vjsVQDAlXt82fbg7FUAwJV7cNl2d9vLs1cCAFfq5ba7l8vlctn25OzVAMCVenL5zbZvt706e0UAcGVebfv28qZtj85eFQBcmUeXt237atvzs1cGAFfi+bavfhfMNx7Nvjh5gQBwthd7+1HsO6L5j4kmAH9dL7b9/f+M5Vs7TY9nAfireb737Sxveaf5aL6eBeDP79WO5r37neUH7DafzHADAP58Xu5o3IftKt8Tzrs7xug93jGw/ac5GgyAL8fNjnY929GyB/ttgk/w37KY3HUed+z3AAAAAElFTkSuQmCC"/>
        </div>
        <div class="title">我用悠咖赚钱，和我一起吧</div>
        <div class="qr">
          <img alt="用户二维码" :src="qrcode"/>
        </div>
      </div>
    </div>
		<van-loading v-if="loading" class="yg-loading"/>
  </div>
</template>

<script>
	import {getUserInfoApi} from '../../api/user'
export default {
  name: "userInvite",
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
    self.$refs.userInviteRef.style.height = window.screen.height - 47 - 4 + 'px';
		self.getUserInfo();
  }
}
</script>

<style lang="less">
.yg-invite {
  .user-invite-content {
    background-color: #D8F5F5;
    padding: 0 1rem;
    .title {
      font-size: 39px;
      color: #00BD71;
      letter-spacing: 3.25px;
      font-weight: 600;
      text-align: center;
      padding-top: 0.5rem;
    }
    .sub-title {
      font-size: 20px;
      color: #00BD71;
      letter-spacing: 4.17/2px;
      text-align: center;
      padding-bottom: 0.5rem;
    }
    .header-tips {
      font-size: 24px;
      color: #00BD71;
      letter-spacing: 2.5px;
      text-align: center;
      padding: 0.1rem 0;
    }
    .content {
      position: relative;
      padding-top: 0.5rem;
      .bg-img {
        position: relative;
        text-align: center;
        img {
          width: 230px;
        }
      }
      .title {
        font-size: 14px;
        color: #666;
        position: absolute;
        top: 1.25rem;
        left: 0;
        right: 0;
        letter-spacing: 0;
        font-weight: normal;
        text-align: center;
        padding-top: 0;
      }
      .qr {
        position: absolute;
        bottom: 1.25rem;
        left: 0;
        right: 0;
        text-align: center;
        img {
          width: 128px;
        }
      }
    }
  }
}
</style>
