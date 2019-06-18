<template>
  <div class="yg-user-info">
    <van-nav-bar class="yg-nav-bar" title="账户管理" left-arrow @click-left="onClickLeft"/>
    <van-cell-group class="user-center">
      <van-cell class="user-header display-flex" title="更换头像" is-link>
        <van-uploader :after-read="onRead">
          <img :src="userAvtar" alt="头像"/>
        </van-uploader>
      </van-cell>
      <van-cell title="昵称" is-link :value="userInfo.nickName" @click="userInfoPopup = true"/>
      <van-cell title="邀请码" :value="userInfo.invite_code"/>
      <van-cell title="推荐人" is-link :value="userInfo.parent" @click="editReferrer"/>
      <van-cell title="等级" :value="userInfo.level.level_name ? userInfo.level.level_name : ''"/>
      <van-cell title="注册时间" :value="userInfo.create_time"/>
      <van-cell title="手机号" :value="userInfo.phone"/>
      <van-switch-cell title="公开手机号" class="pubilc-num" v-model="publicPhoneNum" active-color="#00BD71"/>
      <van-cell title="修改绑定手机" is-link :value="userInfo.phone" @click="userPhoneNumPopup = true"/>
      <van-cell title="管理收货地址" is-link @click="goToPage('/user/address')"/>
      <van-cell title="管理银行卡" is-link @click="goToPage('/user/bankCard')"/>
    </van-cell-group>
    <div class="logout text-center">
      <van-button class="btn" @click="logout">退出登录</van-button>
    </div>

    <!--弹窗内容 - 邀请-->
    <van-popup class="yg-popup" v-if="userInfo.pid == 0" v-model="referrerPopup" :close-on-click-overlay="false">
      <div class="title text-center">推荐人</div>
      <van-field class="referrer-num" type="number" v-model="referrerNum" :disabled="radio == 2" placeholder="请输入推荐人邀请码"/>
      <div class="referrer-tip">指定推荐人只有一次机会，请慎重填写，若不填写系统会自动为您匹配上级</div>
      <div class="referrer-checkbox display-flex">
        <div class="title display-flex">是否有推荐人：</div>
        <!-- <van-checkbox class="display-flex" v-model="referrerChecked" checked-color="#00BD71">{{referrerChecked ? '是' : '否'}}</van-checkbox> -->
        <van-radio-group class="display-flex" v-model="radio">
          <van-radio checked-color="#00BD71" name="1">是</van-radio>
          <van-radio checked-color="#00BD71" name="2">否</van-radio>
        </van-radio-group>
      </div>
      <div class="submit-btn text-center">
        <van-button type="default" @click="referrerPopup = false">取消</van-button>
        <van-button class="yg-main-btn" @click="SetParent">确认</van-button>
      </div>
    </van-popup>
    <!--弹窗内容 - 昵称-->
    <van-popup class="yg-popup" v-model="userInfoPopup" :close-on-click-overlay="false">
      <div class="title text-center">修改昵称</div>
      <van-field class="referrer-num" v-model="userName" placeholder="请输入新昵称"/>
      <div class="submit-btn text-center">
        <van-button type="default" @click="userInfoPopup = false">取消</van-button>
        <van-button class="yg-main-btn" @click="SaveNickName">确认</van-button>
      </div>
    </van-popup>
    <!--弹窗内容 - 手机号-->
    <van-popup class="yg-popup" v-model="userPhoneNumPopup" :close-on-click-overlay="false">
      <div class="title text-center">修改手机号</div>
      <van-field class="referrer-num" v-model="phone" placeholder="请输入新手机号码"/>
      <van-field v-model="userPhoneSms" type="number" placeholder="请输入验证码">
        <van-button slot="button" @click="PhoneVerfity" plain size="small" class="yg-btn-plain">{{verfity_btn}}</van-button>
      </van-field>
      <!-- <div class="referrer-tip">更换手机号成功后，需重新使用新手机号登录</div> -->
      <div class="submit-btn text-center">
        <van-button type="default" @click="userPhoneNumPopup = false">取消</van-button>
        <van-button class="yg-main-btn" @click="SavePhone">确认</van-button>
      </div>
    </van-popup>

    <van-loading v-if="loading" class="yg-loading"/>
  </div>
</template>

<script>
import {getUserInfoApi,SaveAvatar,SaveNickName,GetPhoneVerfity,UpdatePhone,SetParent} from '../../api/user'
import {UploadImage} from '../../api/upload'


export default {
  name: "userInfo",
  components: {
  },
  data() {
    return {
		userInfo:{
			level:{
				level_name:''
			}
		},
		verfity_time: 0,
		phone:'',
      publicPhoneNum: true,

      userAvtar: '',
      userLevel: '',

      referrerNum: '',
      referrerChecked: false,
       radio: '1',

      referrerPopup: false,

      userInfoPopup: false,
      userName: '',

      userPhoneNumPopup: false,
      userPhoneSms: '',


      loading: true,
			default_parent: '', 	//默认推荐人
			default_code: '', 	//默认推荐人
    }
  },
	watch:{
		'radio':function(){
			if(this.radio == 2){
				if(this.default_code == ''){
					this.$toast('暂无默认推荐人');
					this.radio = "1";
					return false;
				}
				this.referrerNum = this.default_code
			}
		},
	},
	computed:{
		verfity_btn(){
			if(this.verfity_time <= 0){
				return  '获取验证码';
			}else{
				return  this.verfity_time+'秒';
			}
		}
	},
  methods: {
    onClickLeft() {
      const self = this;
      self.$router.go(-1);
    },
    /**
     * 修改推荐人
     */
    editReferrer() {
      const self = this;
	  if(self.userInfo.pid == 0){
		  self.referrerPopup = true;
	  }
    },
		SetParent(){
			if(this.referrerNum == ''){
				this.$notify('输入推荐人邀请号');
				return false;
			}
			SetParent(this.referrerNum).then(res => {
				this.getUserInfo();
				this.referrerPopup = false;
			})
		},
    /**
     * 上传头像
     */
    onRead(file) {
			const self = this;
      console.log(file)
			var formData = new FormData();
			formData.append('iFile',file.file)
			console.log(formData)
			UploadImage(formData).then(res => {
				console.log(res)
				if(res.data.code == 1){
						SaveAvatar({avatar:res.data.data.file_path}).then(response => {
							console.log(response)
							self.userAvtar = response.data.userInfo.avatarUrl !== '' ? response.data.userInfo.avatarUrl : 'data:image/png;base64,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';

						})
				}
			})
    },
    // 页面跳转
    goToPage(pageName) {
      const self = this;
      self.$router.push({
        path: pageName
      })
    },
    // 登出系统
    logout() {
      const self = this;
      self.$store.dispatch('user/RemoveToken');
      self.$router.push({
        path: '/'
      })
    },
    // 获取用户信息
    getUserInfo() {
      const self = this;
      getUserInfoApi().then(response => {
        console.log(response);
				self.userInfo = response.data.userInfo
        self.userAvtar = response.data.userInfo.avatarUrl !== '' ? response.data.userInfo.avatarUrl : 'data:image/png;base64,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';
        self.userName = response.data.userInfo.nickName;
				self.default_code = response.data.default_code;

        self.loading = false;
      }).catch(error => {
        console.log(error)
      })
    },
		SaveNickName(){
			const self = this;
			SaveNickName({nickname: self.userName}).then(res => {
				console.log(res)
				if(res.code == 1){
					self.userInfo.nickName = self.userName;
					self.userInfoPopup = false;
				}else{
					self.userName = self.userInfo.nickName;
				}
			})
		},
		PhoneVerfity(){
			const self = this;
			if(self.verfity_time <= 0){
				if(self.phone == ''){
					Toast('输入新手机号');
					return false;
				}
				var reg = /^\d{11}$/;
				if(!reg.test(self.phone)){
					Toast('手机号不正确');
					return false;
				}
				GetPhoneVerfity({phone:self.phone}).then(res => {
					if(res.code == 1){
						self.verfity_time = 60
						self.timer = setInterval(self.TimeSend,1000);
					}
				})
			}
		},
		TimeSend(){
			if(this.verfity_time <= 0){
				clearInterval(this.timer);
			}
			this.verfity_time = this.verfity_time - 1;
		},
		SavePhone(){
			const self = this;
			UpdatePhone({phone:self.phone,code:self.userPhoneSms}).then(res => {
				console.log(res)
				if(res.code == 1){
					self.userInfo.phone = self.phone;
					self.userPhoneNumPopup = false;
					self.getUserInfo();
				}
			})
		}
  },
	activated() {
		const self = this;
		var query = self.$route.query;
		if(query.type == 'phone'){
			self.userPhoneNumPopup = true;
		}
		if(query.type == 'phone'){
			self.userPhoneNumPopup = true;
		}
		if(query.type == 'referrer'){
			self.referrerPopup = true;
		}
	},
  created() {
		const self = this;
    self.getUserInfo();
		
  }
}
</script>

<style lang="less">
.van-cell{
	overflow: hidden;
}
.yg-user-info {
  .user-center {
    .user-header {
      margin-top: 0.25rem;

      img {
        width: 50px;
        align-self: center;
      }

      .van-cell__title {
        align-self: center;
      }

      .van-icon.van-icon-arrow {
        align-self: center;
      }
    }
  }
  .logout {
    margin-top: 0.25rem;

    .btn {
      width: 100%;
      color: #FA4E1B;
      font-size: 17px;
    }
  }
  .yg-popup {
    .referrer-num {
      margin-top: 0.25rem;
    }
    .referrer-tip {
      font-size: 12px;
      color: #FA4E1B;
      padding: 0 0.25rem;
      margin-top: 0.25rem;
    }
    .referrer-checkbox {
      padding: 0.25rem;
      .title {
        color: #999;
        font-size: 12px;
        align-self: center;
      }

      .van-radio-group {
        margin-left: 5%;
        .van-radio__icon {
          height: 30px;
          align-self: center;
          .van-icon {
            font-size: 12px;
          }
          margin-left: 10px;
        }
        .van-radio__label {
          color: #333;
          font-size: 12px;
          
          align-self: center;
        }
      }
    }
    .submit-btn {
      margin-top: 0.25rem;
      button {
        color: #999;
        width: 40%;
        &.yg-main-btn {
          color: #fff;
          margin-left: 4%;
        }
      }
    }
  }
}
</style>

