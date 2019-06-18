<template>
    <div id="teamUserInfo">
        <van-nav-bar class="yg-nav-bar" title="个人资料" left-arrow @click-left="onClickLeft"/>
        <div class="info_top">
            <div class="info_user">
                <div class="info_user_pic">
                    <img :src="userInfo.avatarUrl" alt="">
                </div>
                <p class="info_user_name">{{userInfo.nickName}}</p>
            </div>
        </div>
        <div class="info_bottom">
            <van-cell-group>
                <van-cell title="手机号" :value="userInfo.phone" />
                <van-cell title="级别" :value="userInfo.level.level_name" />
                <van-cell title="邀请人" :value="userInfo.parent" />
                <van-cell title="上级" :value="userInfo.pparent" />
                <van-cell title="下级人数" :value="userInfo.count+'人'" />
            </van-cell-group>
        </div>
    </div>
</template>
<script>
import {getUserInfo} from '../../api/data'
export default {
    name:"teamUserInfo",
    data(){
        return{
            userInfo:{
                
            }
        }
    },
    methods:{
        /**
         * 返回
         */
        onClickLeft() {
        const self = this;
        self.$router.go(-1);
        },
    },
	watch:{
		'$route' (to, from) {
			this.$router.go(0);
		}
	},
	created() {
		console.log(this)
		var user_id = this.$route.query.user_id;
		getUserInfo(user_id).then(res => {
			if(res.code == 1){
				this.userInfo = res.data.user;
			}
		})
	}
}
</script>
<style lang="less">
    .info_top{
        display: flex;
        justify-content: center;
        align-items: center;
        height: 150px;
        background: linear-gradient(to right,#74CC98,#00BD71);
        .info_user{
            display: flex;
            flex-direction: column;
            align-items: center;
            .info_user_pic{
                width: 68px;
                height: 68px;
                // border: 1px solid red;
                border-radius: 34px;
				img{
					width: 100%;
					height: 100%;
				}
            }
            p{
                margin: 10px;
                color: #ffffff;
                font-size: 16px;
            }
        }
        
    }
    .info_bottom{
        .van-cell__title{
            color: #969799;
        }
        .van-cell__value{
            color: #323233;
        }
    }
</style>