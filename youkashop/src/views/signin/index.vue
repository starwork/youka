<template>
  <div class="yg-sginin" ref="sgininHeight">
    <div class="close">
      <van-icon name="cross" @click="onClickLeft"/>
    </div>
    <div class="title">
      <span class="padding-offset">登录</span>
      <span class="padding-offset-line"></span>
    </div>
    <div class="sub-title">还没有账号，立即<router-link to="/signup">注册</router-link></div>
    <van-cell-group>
      <van-field v-model="phoneNum" type="tel" placeholder="请输入手机号码" @keyup.enter.native="toSignin"/>
      <van-field v-model="password" type="password" placeholder="请输入密码" @keyup.enter.native="toSignin"/>
    </van-cell-group>
    <div class="text-center">
      <van-button
        class="yg-login-btn"
        @click.native.prevent="toSignin"
        :loading="loading"
        loading-type="spinner"
      >登陆</van-button>
      <div class="forget-pwd">
        <router-link to="/forget">忘记了？找回密码</router-link>
      </div>
      <!-- <div class="fast-login">
        快速登录
      </div>
      <van-row class="text-center fast-login-btn">
        <van-col span="24"><a href="http://youka.nicaila.com/user.php"><img src="../../assets/login/wexin.png" alt="微信登录"/></a></van-col>
        <van-col span="12"><img src="data:image/png;base64,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" alt="微博登录"/></van-col>
      </van-row> -->
    </div>
  </div>
</template>

<script>
import { Notify } from 'vant';
import { mapState } from "vuex";
import { login } from '../../api/signin';

export default {
  name: "sginin",
  components: {
  },
  data() {
    return {
      phoneNum: '',
      password: '',
      loading: false,
    }
  },
  computed: {
    ...mapState(["user"])
  },
  methods: {
    /**
     * 返回
     */
    onClickLeft() {
      const self = this;
      self.$router.go(-1);
    },
    /**
     * 登录验证
     */
    toSignin() {
      const self = this;

      if(self.phoneNum === '') {
        Notify('手机号码不得为空！');
        return false;
      }

      if(self.password === '') {
        Notify('登录密码不得为空！');
        return false;
      }

      self.loading = true;

      login({
        username: self.phoneNum,
        password: self.password
      }).then((response) => {
        if(response.code === 1) {
          Notify({
            message: '登录成功，即将跳转到首页！',
            background: '#00BD71'
          });
          // 存储token
          self.$store.dispatch("user/SetToken", response.data.token);
          setTimeout(function () {
            self.$router.push({
              path: '/home'
            });
            self.loading = false;
          }, 1500)
        }
      }).catch((error) => {
        console.log(error);
        self.loading = false;
      })
    }
  },
  mounted() {
    const self = this;
    self.$refs.sgininHeight.style.height = window.screen.height + 'px';
  }
}
</script>

<style lang="less">
.yg-sginin {
  position: relative;
  background-color: #fff;
  height: 100%;
  padding-left: 0.25rem;
  padding-right: 0.25rem;
  .close {
    position: relative;
    padding-top: 40px;
    i {
      font-size: 17px;
    }
  }
  .title {
    position: relative;
    .padding-offset {
      position: relative;
      font-size: 30px;
      color: @textColor;
      z-index: 1;
    }
    .padding-offset-line {
      position: relative;
      bottom: 10px;
      height: 5px;
      width: 1.6rem;
      display: block;
      background-color: @mainColor;
    }
  }
  .sub-title {
    font-size: 17px;
    color: #999;
    margin-bottom: 54px;
  }
  .yg-login-btn {
    width: 90%;
    background: @mainGradientColor;
    color: #fff;
    font-size: 17px;
    margin-top: 20px;
  }
  .forget-pwd {
    font-size: 12px;
    color: @mainColor;
    margin-top: 20px;
  }
  .fast-login {
    font-size: 12px;
    color: @titleColor;
    margin-top: 50px;
  }
  .fast-login-btn {
    margin-top: 20px;
    img {
      width: 42px;
    }
  }
}
</style>
