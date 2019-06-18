<template>
  <div class="yg-user-withdraw">
    <van-nav-bar class="yg-nav-bar" title="提现" left-arrow @click-left="onClickLeft"/>
    <div v-if="bank.length > 0" class="yg-withdraw-list">
      <div class="withdraw-content">
        <van-cell is-link @click="selectWithdrawCard">
          <div v-if="select_card" class="custom-withdraw" slot="title">
            <van-row class="card-item-content">
              <van-col span="4" class="card-logo">
                <img :src="select_card.file_path" alt="bank logo">
              </van-col>
              <van-col span="20" class="card-info">
                <div class="title">{{select_card.bank_name}}（尾号{{select_card.bank_card_no}}）</div>
                <div class="sub">{{select_card.card_type}}</div>
              </van-col>
            </van-row>
          </div>
        </van-cell>
        <div class="withdraw-card">
          <div class="title">提现金额</div>
          <div class="edu">
            <div class="fuhao">￥</div>
            <van-field type="number" class="tixian" v-model="withdrawNum"/>
          </div>
          <div class="tips">
            可提现余额{{userInfo.price}}元，审核通过后24小时到账
          </div>
          <div class="queding">
            <van-button class="yg-main-btn" @click="SubmitWith">确认提现</van-button>
          </div>
				<div class="tips">
					提现须知：
					<div class="tips">1）每周只能提取1次，每次需大于等于10元；</div>
					<div class="tips">2）仅限银行卡提现，1个工作日到账；</div>
					<div class="tips">3）根据国家法律规定，推广费按照劳务费缴税，税率为5%；</div>
					<div class="tips">4）每日最大提现金额由银行卡限额决定。</div>
				</div>
        </div>
				
				
      </div>
    </div>
    <div v-else class="yg-no-data text-center">
      <div class="img">
        <img src="data:image/png;base64,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" alt="没有数据">
      </div>
      <div class="title">
        您还没有银行卡哦！
      </div>
      <div class="btn">
        <van-button size="small" class="no-data-btn" @click="gotoVerified">添加银行卡</van-button>
      </div>
    </div>

    <van-popup
      v-model="selectBankCardPopup"
      position="right"
      class="yg-popup-select-card"
    >
      <van-radio-group v-model="selectBankCardRadio" @change="selectBankCardRadioChange">
        <van-cell-group>
          <van-cell v-for="item in bank" clickable @click="SelectCard(item)" class="withdraw-item">
            <van-radio :name="item.id"/>
            <div slot="title" class="custom-card">
              <van-row class="card-item-content">
                <van-col span="4" class="card-logo">
                  <img :src="item.file_path" alt="bank logo">
                </van-col>
                <van-col span="20" class="card-info">
                  <div class="title">{{item.bank_name}}（尾号{{item.bank_card_no}}）</div>
                  <div class="sub">{{item.card_type}}</div>
                </van-col>
              </van-row>
            </div>
          </van-cell>
        </van-cell-group>
      </van-radio-group>
    </van-popup>
		<van-loading v-if="loading" class="yg-loading"/>
  </div>
</template>

<script>
import {getWithDraw,submitWithDraw} from '../../api/bankCard'
export default {
  name: "withdraw",
  components: {
  },
  data() {
    return {
			select_card:{},
			userInfo:{},
			bank:[],
			loading: true,
      hasData: true,
      withdrawNum: 0.00,
      selectBankCardPopup: false,
      selectBankCardRadio: 1,
    }
  },
  methods: {
    onClickLeft() {
      const self = this;
      self.$router.go(-1);
    },
    deleteSwtichRight() {
      const self = this;
      self.Toast('删除');
    },
		
		
    /**
     * 选择银行卡
     */
    selectWithdrawCard() {
      const self = this;
      self.selectBankCardPopup = true;
    },
    /**
     * 选中银行卡
     */
    selectBankCardRadioChange(val) {
      const self = this;
			self.selectBankCardRadio = val;
			self.bank.forEach((value) => {
				if(value.id == val){
					self.select_card = value
				}
			})
      self.selectBankCardPopup = false;
    },
		
		SelectCard(item){
			const self = this;
			self.select_card = item
			self.selectBankCardRadio = item.id;
			self.selectBankCardPopup = false;
		},
    /**
     * 实名认证
     */
    gotoVerified() {
      const self = this;
      self.$router.push({
        path: '/user/bankCardAdd'
      })
    },
		
		SubmitWith(){
			const self = this;
			if(parseFloat(self.withdrawNum) > parseFloat(self.userInfo.price)){
				self.$toast('提现金额不能超过'+self.userInfo.price);
				return false;
			}
			if(self.selectBankCardRadio == 0){
				self.$toast('选择提现银行卡');
				return false;
			}
			submitWithDraw(self.withdrawNum,self.selectBankCardRadio).then(res => {
				if(res.code == 1){
					self.$toast(res.msg);
					setTimeout(function(){
						self.$router.replace({
							path: '/user/center'
						});
					},1000)
				}
			})
		},
		
  },
	mounted() {
		const self = this;
		getWithDraw().then(res => {
			self.loading = false
			console.log(res)
			self.userInfo = res.data.userInfo
			self.bank = res.data.bank
			if(self.bank.length > 0){
				self.select_card = self.bank[0]
				self.selectBankCardRadio = self.bank[0].id
			}
		})
	}
}
</script>

<style lang="less">
.yg-user-withdraw {
  .yg-withdraw-list {

  }
  .custom-card {
    .card-item-content {
      display: flex;
      .card-logo {
        align-self: center;
        text-align: center;
        img {
          width: 30px;
        }
      }
      .card-info {
        .title {
          color: #333;
          font-size: 16px;
          font-weight: 600;
        }
        .sub {
          color: #999;
          font-size: 13px;
        }
      }
    }
  }

  .withdraw-content {
    .van-cell__right-icon {
      align-self: center;
    }
    .custom-withdraw {
      .card-item-content {
        display: flex;
        .card-logo {
          align-self: center;
          text-align: center;
          img {
            width: 30px;
          }
        }
        .card-info {
          .title {
            color: #333;
            font-size: 16px;
            font-weight: 600;
          }
          .sub {
            color: #999;
            font-size: 13px;
          }
        }
      }
    }
  }

  .withdraw-card {
    background-color: #fff;
    padding: 0 0.25rem;
    .title {
      font-size: 12px;
      color: #666;
      padding-top: 0.25rem;
    }
    .edu {
      display: flex;
      width: 100%;
      .fuhao {
        width: 10%;
        text-align: right;
        font-size: 24px;
        color: #333;
        align-self: center;
        position: relative;
        top: 4px;
      }
      .tixian {
        width: 90%;
        padding-left: 0;
        font-size: 30px;
        color: #333;
      }
    }
    .tips {
      font-size: 12px;
      color: #999;
      padding: 0.25rem 0;
    }
    .queding {
      text-align: center;
      padding-bottom: 0.5rem;
      padding-top: 0.5rem;
      .yg-main-btn {
        color: #fff;
        width: 80%;
      }
    }
  }

  .yg-popup-select-card {
    left: 0;
    .withdraw-item {
      .van-cell__title {
        width: 80%;
      }
      .van-cell__value {
        width: 20%;
        align-self: center;
        .van-radio__icon--checked {
          .van-icon {
            border-color: @mainColor;
            background-color: @mainColor;
          }
        }
      }
      .van-cell__title,
      .van-cell__value {
        flex: none;
      }
    }
  }
}
</style>
