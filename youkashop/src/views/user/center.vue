<template>
  <div class="youga-user">
    <div class="youga-user-content">
      <div class="header">
        <van-row class="display-flex position-relative">
          <van-col span="5" class="header-1 text-center">
            <img :src="userInfo.avatarUrl ? userInfo.avatarUrl : 'data:image/png;base64,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'" alt="用户头像"/>
          </van-col>
          <van-col span="14" class="header-2">
            <div class="name">{{userInfo.nickName ? userInfo.nickName : ''}} <span>{{userInfo.level.level_name ? userInfo.level.level_name : ''}}</span></div>
            <div class="shangji">我的上级：{{userInfo.parent}}</div>
            <div class="yqm"><span>我的邀请码：{{userInfo.invite_code}}</span> <span class="copy" :data-clipboard-text="userInfo.invite_code" @click="copy">复制</span></div>
          </van-col>
          <van-col span="5" class="header-3 text-right">
            <div class="user-mang" @click="goToPage('/user/info')">
              账户管理 >
            </div>
          </van-col>
        </van-row>
      </div>
      <div class="shouru">
        <van-row class="position-relative display-flex size-border">
          <van-col class="size-bor-right" span="8">
            <div class="title">{{userInfo.total}}</div>
            <div class="sub-title">总收入(元)</div>
          </van-col>
          <van-col class="size-bor-right" span="8">
            <div class="title">{{userInfo.yesterday}}</div>
            <div class="sub-title">昨日收入(元)</div>
          </van-col>
          <van-col  span="8">
            <div class="title">{{userInfo.point}}</div>
            <div class="sub-title">我的咖豆</div>
          </van-col>
        </van-row>
      </div>
      <div class="yue">
        <van-row class="position-relative display-flex">
          <van-col span="12" class="text-left yue-title">
            账户余额(元)：<span>{{userInfo.price}}</span>
          </van-col>
          <van-col span="12" class="text-right yue-tx">
            <van-button class="yg-tx-btn" @click="toPath('/user/withdraw')">去提现 ></van-button>
          </van-col>
        </van-row>
      </div>
      <div class="dingdan">
        <van-row class="position-relative display-flex">
          <van-col span="12" class="title" info="5">我的订单</van-col>
          <van-col span="12" class="quanbu"><router-link to="/user/order?active=0">查看全部 <van-icon name="arrow"/></router-link></van-col>
        </van-row>
      </div>
      <div class="dd-list">
        <van-row class="display-flex position-relative">
          <van-col span="6">
            <div class="dd-item" @click="goToPage('/user/order?active=1')">
              <div class="img">
                <img src="../../assets/user/payment.png" alt="待付款">
                <div v-if="orderCount.payment !== 0" class="van-info">{{orderCount.payment}}</div>
              </div>
              <div class="title">待付款</div>
            </div>
          </van-col>
          <van-col span="6">
            <div class="dd-item" @click="goToPage('/user/order?active=2')">
              <div class="img">
                <img src="../../assets/user/delivery.png" alt="待发货">
                <div v-if="orderCount.delivery > 0" class="van-info">{{orderCount.delivery}}</div>
              </div>
              <div class="title">待发货</div>
            </div>
          </van-col>
          <van-col span="6">
            <div class="dd-item" @click="goToPage('/user/order?active=3')">
              <div class="img">
                <img src="../../assets/user/received.png" alt="待收货">
                <div v-if="orderCount.received !== 0" class="van-info">{{orderCount.received}}</div>
              </div>
              <div class="title">待收货</div>
            </div>
          </van-col>
          <van-col span="6">
            <div class="dd-item" @click="goToPage('/user/order?active=4')">
              <div class="img">
                <img src="../../assets/user/comment.png" alt="待评价">
                <div v-if="orderCount.comment !== 0" class="van-info">{{orderCount.comment}}</div>
              </div>
              <div class="title">待评价</div>
            </div>
          </van-col>
        </van-row>
      </div>
      <div class="fenxiang">
        <van-row class="position-relative display-flex">
          <van-col span="12" class="title">
            <!-- <div @click="goToPage('/user/userQR')"> -->
            <div @click="goToMa()">
              <img alt="xx" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAAXNSR0IArs4c6QAAAOVJREFUWAntVu0RwiAMJR5juAUsoe7BUN1DXQK2cA9MPLijln4QueOH4Q80DS/h0ZcGrLUXpdQUYzzjvDsA4IVOznv/LJ25OCcEORycAqZEpzJ4WrNwdD55CAEqoAuTMQa3LNnKtlYcYmDokASEAWFAGNBU26mKUYU7WpHS/2DmzsWhK3A1wBl68ZB8XWHKy144GU/mP2EAuJ1MpSO6o5quLbzhB/0gFbA6mZZAa76YcOzWESEjt7VAW3YpxcMZ0Fv30/IO1cRWQUucrr4fFfRCFBVwmRyvAm4n833iX1QwrJMhFbwBsvKJgDjKNZkAAAAASUVORK5CYII="/>
              <p class="fenxiang_text">我的二维码</p>
            </div>
          </van-col>
          <van-col span="12" class="title">
            <div @click="goToIn">
              <img alt="xxx" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAiCAYAAAA+stv/AAAAAXNSR0IArs4c6QAAAcFJREFUWAntVz1LxDAYvngVRQSnVm4R9BcUWrjVyaO4uHTyXxzicK5yg/oL3F2uu6Au4ia0q6vgYLGdXLqd9XmhhVBzTZoq59BArm3ePB95kzQ91tMsjuNsATpjjJl5nh9FUfSmQ8V0QIX4HbBDwsPEK0zs65hobKAqXg5A18RKSaBy5cVJkMM8IwO7aHtEnx2uXXqrbKAqTinn2Ee41zKhZEAkzs837j9hQMuE1IBMvMyCrgmpAQjMUIcqi0xg4sH3/X5pUnSVGoDwJoAvqtusNEELEpj1NE1XRcLKbSBhVEUATE9OVRSjNtnoqY9BP3UFI1koUIejWBAEc1kfw/O8NaRpio7HGOl2FQADRDIJw/CiGqt7dl33FPEpOH+sAXB+IHZjmubESJLkHA/jOrLfjhUDHUP7i2EO3yEwQB1hAd03ESvnHzjhGlnEBdwBYnSWxLQLSLzXVJwwuoXTGki3oa6IKq4z0GWgy0CXgaVnQHoc173RcKrd4mDRPq6Ju5UBHNGHdQZVYkufgs7Av8hATIul+EpRWTet+3BaMX2SXYLxpDWrHsFV37btpyzLNrCn98BBf0L+vNBXMeq1ZVln30mgxmzIROZ4AAAAAElFTkSuQmCC">
              <p class="fenxiang_text">分享给好友</p>
            </div>
          </van-col>
        </van-row>
      </div>

      <div class="dingdan fuwu-title">
        <van-row class="position-relative display-flex">
          <van-col span="12" class="title">我的服务</van-col>
          <van-col span="12" class="quanbu"></van-col>
        </van-row>
      </div>
      <div class="fuwu">
        <van-row class="position-relative">
          <van-col class="fuwu-item  fuwu-item1" span="6">
            <div @click="goToPage('/user/orderManagement')">
              <div class="logo"><img alt="xx" src="../../assets/user/1.png"/></div>
              <div class="title">订货管理</div>
            </div>
          </van-col>
          <van-col class="fuwu-item fuwu-item1" span="6">
            <div @click="goToPage('/user/personManagement')">
              <div class="logo"><img alt="xx" src="../../assets/user/2.png"/></div>
              <div class="title">人员管理</div>
            </div>
          </van-col>
          <van-col class="fuwu-item fuwu-item1" span="6">
            <div @click="goToPage('/user/wealthManagement')">
              <div class="logo"><img alt="xx" src="../../assets/user/3.png"/></div>
              <div class="title">财富管理</div>
            </div>
          </van-col>
          <van-col class="fuwu-item fuwu-item1" span="6">
            <div @click="goToPage('/user/statistics')">
              <div class="logo"><img alt="xx" src="../../assets/user/4.png"/></div>
              <div class="title">数据统计</div>
            </div>
          </van-col>
          <van-col class="fuwu-item" span="6">
            <div @click="goToPage('/user/collection')">
              <div class="logo"><img alt="xx" src="../../assets/user/5.png"/></div>
              <div class="title">我的收藏</div>
            </div>
          </van-col>
          <van-col class="fuwu-item" span="6">
            <div @click="goToPage('/user/myInvitation')">
              <div class="logo"><img alt="xx" src="../../assets/user/6.png"/></div>
              <div class="title">我的邀请</div>
            </div>
          </van-col>
          <van-col class="fuwu-item" span="6">
            <div @click="goToPage('/user/callback')">
              <div class="logo"><img alt="xx" src="../../assets/user/7.png"/></div>
              <div class="title">意见反馈</div>
            </div>
          </van-col>
          <van-col class="fuwu-item" span="6">
            <div @click="sorry">
              <div class="logo"><img alt="xx" src="../../assets/user/8.png"/></div>
              <div class="title">我的咖豆</div>
            </div>
          </van-col>
        </van-row>
      </div>
      <!-- <div class="kefu">
        <img src="data:image/png;base64,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" alt="kefu">
      </div> -->
      <kefu />
    </div>
    <Tabbar/>
    <van-loading v-if="loading" class="yg-loading"/>
  </div>
</template>

<script>

import Tabbar from '../../components/Tabbar';
import VuejsDialog from 'vuejs-dialog';
import Clipboard from 'clipboard'
import kefu from './kefu'

import {getUserCenterApi} from '../../api/user';


export default {
	name:'user_center',
  components: {
    Tabbar,
    kefu
  },

  data() {
    return {
      loading: true,
	  pay_count:0,
	  draw_num:0,
      userInfo:{
      	level:{
      		level_name:''
      	}
      },
      orderCount: {},
    };
  },

  methods: {
    // 页面跳转
    goToPage(pageName) {
      const self = this;
      self.$router.push({
        path: pageName
      })
    },
		
		copy(){
			let clipboard = new Clipboard('.copy')
			clipboard.on('success',res => {
				this.$toast('复制成功');
			});
		},
		goToIn(){
			const self = this;
			if(self.pay_count > 0){
				self.$router.push({
					path: '/user/userInvite'
				})
			}else{
				self.$dialog.confirm({
				  message: '哎呀，自己还没买呢'
				},{
				  okText: '立即购买',
				  cancelText: '我知道了'
				}).then(() => {
				  //确认操作
				  self.$router.push({
						path: '/home'
					})
				}).catch(() => {
				  //取消操作
				  
				});
			}
		},
    goToMa(){
      const self = this;
			if(self.pay_count > 0){
				self.$router.push({
					path: '/user/userQR'
				})
			}else{
				self.$dialog.confirm({
				  message: '哎呀，自己还没买呢'
				},{
				  okText: '立即购买',
				  cancelText: '我知道了'
				}).then(() => {
				  //确认操作
				  self.$router.push({
						path: '/home'
					})
				}).catch(() => {
				  //取消操作
				  
				});
			}
    },
		
		toPath(url){
			if(url == '/user/withdraw'){
				// if(parseInt(this.userInfo.price) < 1){
				// 	this.$toast('不满足最低申请金额');
				// 	return false;
				// }
				// if(parseInt(this.draw_num) >= 4){
				// 	this.$toast('本周超过申请次数，请下周再申请！');
				// 	return false;
				// }
			}
			this.$router.push({
				path: url
			})
		},

    //获取用用户信息
    getUserCenter() {
      const self = this;
      getUserCenterApi().then(response => {
        if(response.code === 1) {
          self.userInfo = response.data.userInfo;
          self.orderCount = response.data.orderCount;
          self.pay_count = response.data.pay_count;
					self.draw_num = response.data.draw_num
        }
        self.loading = false;
      }).catch(error => {
        console.error(error);
        self.loading = false;
      })
    },
    sorry() {
      const self = this;
      self.$toast('该功能正在开发中！');
    },
  },
	activated() {
		// const self = this;
		// self.getUserCenter();
	},
  created() {
    const self = this;
    self.getUserCenter();
  }
};
</script>

<style lang="less">
.copy{
	margin-left: 10px;
	display: inline-block;
	font-size: 10px;
	text-decoration: underline;
}
.youga-user {
  background-color: #fff;
  .youga-user-content {
    .header {
      background: @mainGradientColor;
      padding: 20px 10px;
      overflow: hidden;
      .header-1 {
        align-self: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        img {
          width: 63px;
					height: 63px;
					border-radius: 50%;
        }
      }
      .header-2 {
        align-self: center;
        color: #fff;
        .name {
          font-size: 19px;
          padding-bottom: 5px;
          display: flex;
          span {
            font-size: 12px;
            color: #FFD900;
            border: 1px #FFD900 solid;
            padding: 2px 5px;
            border-radius: 50px;
            align-self: center;
            margin-left: 5px;
          }
        }
        .shangji {
          font-size: 12px;
          padding-bottom: 5px;
        }
        .yqm {
          font-size: 12px;
					display: flex;
        }
      }
      .header-3 {
        align-self: center;
        .user-mang {
          background: linear-gradient(to right, rgb(135, 238, 197), rgb(97, 240, 157));
          color: #fff;
          font-size: 13px;
          border-radius: 50px;
          padding: 5px 5px;
          width: 100%;
          position: relative;
          left: 0px;
        }
      }
    }
    .size-border{
      border-bottom: 1px solid #EEEEEE;
      padding-bottom: 10px;
      .size-bor-right{
        border-right: 1px solid #EEEEEE;
      }
    }
    .shouru {
      padding: 10px 0;
      text-align: center;
      .title {
        font-size: 18px;
        font-weight: 600;
        color: @textColor;
      }
      .sub-title {
        font-size: 12px;
        color: #999;
        margin-top: 5px;
      }
    }
    .yue {
      padding: 0.25rem;
      border-bottom: 5px solid #F5F5F5 ;
      .yue-title {
        padding-left: 10px;
        color: #999;
        font-size: 12px;
        span {
          color: @textColor;
          font-weight: 600;
          font-size: 24px;
          position: relative;
          top: 2px;
        }
      }
      .yue-tx {
        position: relative;
        bottom: 8px;
        .yg-tx-btn {
          height: 30px;
          line-height: 30px;
          color: #666;
          border-color: #666;
          border-radius: 50px;
          font-size: 12px;
          margin-top: 10px;
        }
      }
    }
    .dingdan {
      padding: 10px;
      border-bottom: 1px solid #EEEEEE;
      .title {
        align-self: center;
        font-size: 16px;
        color: #333;
        text-align: left;
        font-weight: 600;
        padding: 2px 10px;
      }
      .quanbu {
        display: flex;
        flex-direction: row-reverse;
        a {
          font-size: 13px;
          color: #999;
          align-self: center;
          i.van-icon {
            position: relative;
            top: 2px;
          }
        }
      }
    }
    .dd-list {
      padding: 0.3rem .25rem;
      text-align: center;
      border-bottom: 5px solid #F5F5F5 ;
      .img {
        .van-info {
          right: 30px;
          top: 0;
        }
        img {
          width: 30px;
        }
      }
      .title {
        font-size: 13px;
        color: #333;
      }
      .dd-item {
        position: relative;
      }
    }
    .fenxiang {
      padding: 0.4rem .25rem;
      text-align: center;
      border-bottom: 5px solid #F5F5F5 ;
      .fenxiang_text{
        display: inline;
         padding-left: 10px;
      }
      .title {
        align-self: center;
        font-size: 14px;
        line-height: 14px;
        color: #333;
        img {
          width: 16px;
          position: relative;
          top: 3px;
        }
      }
    }
    .fuwu-title {
      padding: 10px;
    }
    .fuwu {
      margin-bottom: 50px;
      // padding: .3rem 0.25rem;
      .fuwu-item1{
        border-bottom: 1px solid #EEEEEE;
      }
      .fuwu-item {
        text-align: center;
        padding: .3rem .2rem .3rem .27rem;
        
        .logo {
          img {
						margin: 0 auto;
            width: 24px;
						display:block;
          }
        }
        .title {
					margin-top: 8px;
          font-size: 12px;
          color: #666;
        }
      }
    }
    .kefu {
      position: fixed;
      right: 0;
      bottom: 35%;
      img {
        width: 50px;
      }
    }
  }
}
</style>
