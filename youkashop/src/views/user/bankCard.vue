<template>
  <div class="yg-user-bank-card">
    <van-nav-bar class="yg-nav-bar" title="我的银行卡" left-arrow @click-left="onClickLeft"/>
    <div v-if="hasData" class="user-bank-card-list">

      <van-swipe-cell :right-width="65" class="card-item" v-for="item in bankCardList" :key="item.id">
        <van-cell-group>
          <van-cell>
            <div slot="title">
              <van-row class="card-item-content">
                <van-col span="6" class="card-logo">
                  <img :src="item.file_path" alt="bank logo">
                </van-col>
                <van-col span="18" class="card-info">
                  <div class="title">{{ item.bank_name }}</div>
                  <div class="sub">{{item.card_type}}</div>
                </van-col>
              </van-row>
              <van-row class="card-num">
                <van-col class="text-center dian" span="6"><span>·</span><span>·</span><span>·</span><span>·</span></van-col>
                <van-col class="text-center dian" span="6"><span>·</span><span>·</span><span>·</span><span>·</span></van-col>
                <van-col class="text-center dian" span="6"><span>·</span><span>·</span><span>·</span><span>·</span></van-col>
                <van-col class="text-center number" span="6"><span>{{ substrStr(item.bank_card_no) }}</span></van-col>
              </van-row>
            </div>
          </van-cell>
        </van-cell-group>
        <div slot="right" class="yg-swtch" @click="deleteSwtichRight(item.id)">
          <span class="title">删除</span>
        </div>
      </van-swipe-cell>

      <button class="yg-address-list_add van-button van-button--danger van-button--large van-button--square van-address-list__add" @click="addBankCard">
        <span class="van-button__text">添加新卡</span>
      </button>
    </div>
    <div v-else class="yg-no-data text-center">
      <div class="img">
        <img src="data:image/png;base64,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" alt="没有数据">
      </div>
      <div class="title">
        您还没有添加银行卡哦！
      </div>
      <div class="btn">
        <van-button size="small" class="no-data-btn" @click="addBankCard">添加银行卡</van-button>
      </div>
    </div>
    <van-loading v-if="loading" class="yg-loading"/>
  </div>
</template>

<script>
import {getBankCardListApi, deleteBankCardApi} from '../../api/bankCard'

export default {
  name: "bankCard",
  components: {
  },
  data() {
    return {
      hasData: true,
      loading: true,
      bankCardList: [],
    }
  },
  methods: {
    // 返回
    onClickLeft() {
      const self = this;
      self.$router.go(-1);
    },
    // 删除
    deleteSwtichRight(id) {
      const self = this;
      self.$dialog.confirm({
        title: '确定删除',
        message: '该操作无法撤回，请确定是否继续操作？'
      }).then(() => {
        self.deleteBankCard(id);
      }).catch(() => {
        self.$notify({
          message: '已取消操作',
          background: '#00BD71',
        })
      });
    },
    // 删除银行卡
    deleteBankCard(id) {
      const self = this;
      self.loading = true;
      deleteBankCardApi(id).then(response => {
        if (response.code === 1) {
          self.$notify({
            message: '删除成功！',
            background: '#00BD71'
          });
          // 刷新列表
          self.getBankCardList();
          self.loading = false;
        } else {
          self.$notify(response.data);
          self.loading = true;
        }
      }).catch(error => {
        self.loading = false;
      })
    },
    // 获取列表数据
    getBankCardList() {
      const self = this;
      getBankCardListApi().then(response => {
        if(response.code === 1) {
          if( response.data.list.length !== 0 ) {
            self.hasData = true;
            self.bankCardList = response.data.list;
          } else {
            self.hasData = false;
          }
        }
        self.loading = false;
      }).catch(error => {
        console.log(error);
        self.loading = false;
      });
    },
    // 截取字符串后面四位数
    substrStr(str) {
      return str.substr(str.length - 4)
    },
    // 判断银行卡logo
    judgeCardType(bankCardName) {
      console.log(bankCardName)
      let bankCardLogo = '';
      switch (bankCardName) {
        case '工商银行':
          bankCardLogo = '../../assets/bank/98/gs.png';
        break;
        case '农业银行':
          bankCardLogo = '../../assets/bank/98/ny.png';
          break;
      }
      return bankCardLogo;
    },
    // 跳转到新增卡号
    addBankCard() {
			console.log('111');
      const self = this;
      self.$router.push({
        path: '/user/banKCardAdd'
      })
    }
  },
  created() {
    const self = this;
    self.getBankCardList();
  }
}
</script>

<style lang="less">
.yg-user-bank-card {
  .user-bank-card-list {
    .card-item {
      .card-item-content {
        display: flex;
        .card-logo {
          align-self: center;
          text-align: center;
          img {
            width: 49px;
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
            font-size: 16px;
          }
        }
      }
      .card-num {
        margin-top: 0.25rem;
        margin-bottom: 0.25rem;
        .dian {
          color: #eee;
          font-size: 50px;
          line-height: 20px;
          padding-left: 5px;
          padding-right: 5px;
        }
        .number {
          font-size: 23px;
          color: #666;
        }
      }
    }

    .van-swipe-cell__right {
      width: 65px;
      height: 100%;
      .yg-swtch {
        background-color: #FA4E1B;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        .title {
          align-self: center;
          color: #fff;
          font-size: 14px;
        }
      }
    }
  }
  .yg-address-list_add {
    position: fixed;
    left: 0;
    bottom: 0;
    background-color: @mainColor;
    border: 0.02667rem solid @mainColor;
  }
}
</style>
