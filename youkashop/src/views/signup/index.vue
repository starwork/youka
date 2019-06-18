<template>
  <div class="yg-sginin" ref="sgininHeight">
    <div class="close">
      <van-icon name="arrow-left" @click="onClickLeft"/>
    </div>
    <div class="title">
      <span class="padding-offset">注册</span>
      <span class="padding-offset-line"></span>
    </div>
    <div class="sub-title">已有帐号，立即<router-link to="/signin">登陆</router-link></div>
    <van-cell-group>
      <van-field v-model="phoneNum" type="tel" placeholder="请输入手机号码" />
      <van-field v-model="phoneSms" type="number" placeholder="请输入验证码">
        <van-button v-show="show" slot="button" plain size="small" class="yg-btn-plain" @click="getSMS()">发送验证码</van-button>
        <van-button v-show="!show" slot="button" plain size="small" class="yg-btn-plain">{{count}}秒</van-button>
      </van-field>
      <van-field v-model="password" type="password" placeholder="设置登录密码" />
    </van-cell-group>
    <div class="gy-protocol">
      <van-checkbox v-model="protocol" checked-color="#00BD71">我已阅读并同意<router-link to="#">《隐私协议》</router-link></van-checkbox>
    </div>
    <div class="text-center">
      <van-button
        class="yg-login-btn"
        :loading="loading"
        loading-type="spinner"
        @click="toSignup"
      >完成</van-button>
    </div>
  </div>
</template>

<script>
import {Notify} from 'vant';
import {registered, regSMSApi} from '../../api/signup';

export default {
  name: "index",
  components: {
  },
  data() {
    return {
      phoneNum: '',
      password: '',
      phoneSms: '',
      protocol: false,
      loading: false,

      show: true,
      count: '',
      timer: null,
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
    /**
     * 注册按钮
     */
    toSignup() {
      const self = this;
      if(self.phoneNum === '') {
        Notify('请输入手机号码');
        return false
      }
      if(self.phoneSms === '') {
        Notify('请输入手机验证码');
        return false
      }
      if(self.password === '') {
        Notify('请输入注册密码');
        return false
      }
      if(self.protocol === false) {
        Notify('请阅读并同意《隐私协议》');
        return false;
      }
      self.loading = true;
      // 注册
      registered({
        phone: self.phoneNum,
        code: self.phoneSms,
        password: self.password
      }).then((response) => {
        if(response.code === 1) {
          self.$notify({
            message: '注册成功，即将跳转到登录页！',
            background: '#00BD71'
          });
          setTimeout(function () {
            // self.$store.commit('SET_TOKEN', response.data.token);
            self.$router.push({
              path: '/signin'
            });
            self.loading = false;
          }, 1500)
        }
      }).catch((error) => {
        console.log(error);
        self.loading = false;
      })
    },
    // 获取验证码
    getSMS() {
      const self = this;
      if(self.phoneNum === '') {
        self.$notify('请输入手机号码');
        return false
      }

      const TIME_COUNT = 60;
      if (!self.timer) {
        self.count = TIME_COUNT;
        self.show = false;
        self.timer = setInterval(() => {
          if (self.count > 0 && self.count <= TIME_COUNT) {
            self.count--;
          } else {
            self.show = true;
            clearInterval(self.timer);
            self.timer = null;
          }
        }, 1000)
      }
      // 请求接口
      regSMSApi({
        phone: self.phoneNum
      }).then(response => {
        self.$notify({
          message: '发送成功，请注意查收！',
          background: '#00BD71'
        });
      });
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
  .yg-btn-plain {
    color: @mainColor;
    border-color: @mainColor;
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
  .gy-protocol {
    padding-left: 0.4rem;
    .van-checkbox__label {
      font-size: 12px;
    }
    .van-checkbox__icon {
      font-size: 12px;
    }
  }
}
</style>
