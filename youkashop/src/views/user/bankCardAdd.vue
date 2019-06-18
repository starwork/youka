<template>
  <div class="yg-user-bank-card-add" ref="bankCardRef">
    <van-nav-bar class="yg-nav-bar" title="新增银行卡" left-arrow @click-left="onClickLeft"/>
    <div class="van-address-edit">
      <div class="van-cell van-field">
        <div class="van-cell__title van-field__label"><span>真实姓名</span></div>
        <div class="van-cell__value">
          <div class="van-field__body">
            <input type="text" v-model="userName" placeholder="请输入本人真实姓名" class="van-field__control">
          </div>
        </div>
      </div>
      <div class="van-cell van-field">
        <div class="van-cell__title van-field__label"><span>银行卡号</span></div>
        <div class="van-cell__value">
          <div class="van-field__body">
            <input type="text" v-model="bankCardNum" @blur="SearchBank" placeholder="请输入本人常用银联储蓄卡" class="van-field__control">
          </div>
        </div>
      </div>
      <div class="van-cell van-field">
        <div class="van-cell__title van-field__label"><span>银行名称</span></div>
        <div class="van-cell__value">
          <div class="van-field__body">
            <input type="text" disabled="disabled" v-model="bankCardName" placeholder="输入卡号后自动识别所属银行" class="van-field__control">
          </div>
        </div>
      </div>
			<div class="van-cell van-field">
			  <div class="van-cell__title van-field__label"><span>开户行名称</span></div>
			  <div class="van-cell__value">
			    <div class="van-field__body">
			      <input type="text" v-model="OpenbankName" placeholder="请输入开户行名称" class="van-field__control">
			    </div>
			  </div>
			</div>
      <div class="van-cell van-field">
        <div class="van-cell__title van-field__label"><span>预留手机</span></div>
        <div class="van-cell__value">
          <div class="van-field__body">
            <input type="text" v-model="userPhoneNum" placeholder="请输入您在银行开户时的预留手机号" class="van-field__control">
          </div>
        </div>
      </div>

      <div class="gy-protocol">
        <van-checkbox v-model="protocol" checked-color="#00BD71">我已阅读并同意
          <router-link to="#">《隐私协议》</router-link>
        </van-checkbox>
      </div>

      <div class="van-address-edit__buttons">
        <button class="van-button van-button--danger yg-main-btn van-button--normal van-button--block" @click="bankCardSubmit">
          <span class="van-button__text">确认提交</span>
        </button>
      </div>
    </div>
    <div class="footer-tips">
      <div class="item">若您没有中国大陆身份证，请联系客服</div>
      <div class="item">为了确保您的提现及时到账，请填写您常用的有效银联储蓄卡</div>
      <div class="item">（长期未用的储蓄卡会因为失效而无法提现）</div>
    </div>
  </div>
</template>

<script>
import {addBankCardApi,getBankInfo} from '../../api/bankCard';

export default {
  name: "bankCardAdd",
  components: {},
  data() {
    return {
      userName: '',
      bankCardNum: '',
	  CardType: '',
      bankCardName: '',
	  OpenbankName: '',
      userPhoneNum: '',
      cardIcon: 0,
      protocol: true,
	  disabled:true
    }
  },
  methods: {
    onClickLeft() {
      const self = this;
      self.$router.go(-1);
    },
    // 提交按钮
    bankCardSubmit() {
      const self = this;
      if(self.userName === '') {
        self.$notify('请填写真实姓名！');
        return false;
      }
      if(self.bankCardNum === '') {
        self.$notify('请填写银行卡号！');
        return false;
      }
      if(self.bankCardName === '') {
        self.$notify('请填写银行名称！');
        return false;
      }
			if(self.OpenbankName === '') {
			  self.$notify('请填写开户行名称！');
			  return false;
			}
      if(self.userPhoneNum === '') {
        self.$notify('请填写预留手机号！');
        return false;
      }
      if(self.protocol === false) {
        self.$notify('请阅读并同意《隐私协议》！');
        return false;
      }
			if(self.cardIcon == 0){
				self.$notify('暂不支持该银行卡');
				return false;
			}

      self.addBankCard();
    },
		SearchBank(){
			getBankInfo(this.bankCardNum).then(res => {
				console.log(res)
				if(res.code == 1){
					this.bankCardName = res.data.name
					this.CardType = res.data.cardType
					this.cardIcon = res.data.icon
				}else{
					this.bankCardName = ''
					this.CardType = ''
					this.cardIcon = 0
				}

			})
		},
    // 添加银行卡
    addBankCard() {
      const self = this;
      addBankCardApi({
				real_name:self.userName,
				bank_card_no:self.bankCardNum,
				bank_name:self.bankCardName,
				bank_icon: self.cardIcon,
				card_type: self.CardType,
				phone: self.userPhoneNum,
				open_bank_name: self.OpenbankName
      }).then(response => {
        if (response.code === 1) {
          self.$notify({
            message: '添加成功！',
            background: '#00BD71'
          });
          // 返回列表
          setTimeout(function () {
            self.$router.push({
              path: '/user/banKCard'
            });
          }, 1500)

        } else {
          self.$notify(response.data)
        }
        self.loading = false;
      }).catch(error => {
        console.log(error);
        self.loading = false;
      })
    }
  },
  mounted() {
    const self = this;
    self.$refs.bankCardRef.style.height = document.documentElement.scrollHeight + 'px';
  }
}
</script>

<style lang="less">
.yg-user-bank-card-add {
  position: relative;

  .gy-protocol {
    padding-left: 0.4rem;

    .van-checkbox__label {
      font-size: 12px;
    }

    .van-checkbox__icon {
      font-size: 12px;
    }
  }

  .footer-tips {
    width: 100%;
    position: fixed;
    bottom: 0.25rem;

    .item {
      width: 100%;
      font-size: 12px;
      color: #999;
      text-align: center;
    }
  }
}
</style>
