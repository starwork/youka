<template>
    <div id="myInvitation">
         <van-nav-bar class="yg-nav-bar" title="团队业绩" left-arrow @click-left="onClickLeft"/>
		 <div ref="content">
			 <van-tabs  v-model="active" title-inactive-color="#000000" title-active-color="#02BD72">
			        <van-tab class="van_today" title="当日">
			             <van-row>
			                <ul>
			                    <li v-for="(item,index) in p1List" @click="goToPage('/user/teamUserInfo')" :key="index">
			                        <div class="li_left">
			                            <img v-if="index == 0" src="../../assets/team/1.png" alt="">
			                            <img v-if="index == 1" src="../../assets/team/2.png" alt="">
			                            <img v-if="index == 2" src="../../assets/team/3.png" alt="">
			                            <div v-if="index > 2">{{index+1}}</div>
			                        </div>
			                        <div class="li_center">
			                            <div class="li_center_left">
			                                <img :src="item.avatarUrl ? item.avatarUrl : 'http://youka.qiniuniu.com/uploads/20190611151504525df1727.png'" alt="">
			                            </div>
			                            <div class="li_center_right">
			                                <p>{{item.nickName ? item.nickName : '用户'+item.user_id}}</p>
			                                <van-tag :color="item.level.value == 30?dailishang:(item.level.value==20?jingxiao:fenxiao)" plain>{{item.level.level_name}}</van-tag>
			                            </div>
			                        </div>
			                        <div class="li_right">
			                            <p>￥{{item.total}}</p>
			                        </div>
			                    </li>
			                </ul>
			            </van-row>
			        </van-tab>
			        <van-tab class="van_week" title="本周">
			             <van-row>
			                <ul>
			                    <li v-for="(item,index) in p2List" @click="goToPage('/user/teamUserInfo')" :key="index">
			                        <div class="li_left">
			                            <img v-if="index == 0" src="../../assets/team/1.png" alt="">
			                            <img v-if="index == 1" src="../../assets/team/2.png" alt="">
			                            <img v-if="index == 2" src="../../assets/team/3.png" alt="">
			                            <div v-if="index > 2">{{index+1}}</div>
			                        </div>
			                        <div class="li_center">
			                            <div class="li_center_left">
			                                <img :src="item.avatarUrl ? item.avatarUrl : 'http://youka.qiniuniu.com/uploads/20190611151504525df1727.png'" alt="">
			                            </div>
			                            <div class="li_center_right">
			                                <p>{{item.nickName ? item.nickName : '用户'+item.user_id}}</p>
			                                <van-tag :color="item.level.value == 30?dailishang:(item.level.value==20?jingxiao:fenxiao)" plain>{{item.level.level_name}}</van-tag>
			                            </div>
			                        </div>
			                        <div class="li_right">
			                            <p>￥{{item.total}}</p>
			                        </div>
			                    </li>
			                </ul>
			            </van-row>
			        </van-tab>
			        <van-tab class="van_month" title="本月">
			             <van-row>
			                <ul>
			                    <li v-for="(item,index) in p3List" @click="goToPage('/user/teamUserInfo')" :key="index">
			                        <div class="li_left">
			                            <img v-if="index == 0" src="../../assets/team/1.png" alt="">
			                            <img v-if="index == 1" src="../../assets/team/2.png" alt="">
			                            <img v-if="index == 2" src="../../assets/team/3.png" alt="">
			                            <div v-if="index > 2">{{index+1}}</div>
			                        </div>
			                        <div class="li_center">
			                            <div class="li_center_left">
			                                <img :src="item.avatarUrl ? item.avatarUrl : 'http://youka.qiniuniu.com/uploads/20190611151504525df1727.png'" alt="">
			                            </div>
			                            <div class="li_center_right">
			                                <p>{{item.nickName ? item.nickName : '用户'+item.user_id}}</p>
			                                <van-tag :color="item.level.value == 30?dailishang:(item.level.value==20?jingxiao:fenxiao)" plain>{{item.level.level_name}}</van-tag>
			                            </div>
			                        </div>
			                        <div class="li_right">
			                            <p>￥{{item.total}}</p>
			                        </div>
			                    </li>
			                </ul>
			            </van-row>
			        </van-tab>
			        <van-tab class="van_laste" title="至今">
			             <van-row>
			                <ul>
			                    <li v-for="(item,index) in p4List" @click="goToPage('/user/teamUserInfo')" :key="index">
			                        <div class="li_left">
			                            <img v-if="index == 0" src="../../assets/team/1.png" alt="">
			                            <img v-if="index == 1" src="../../assets/team/2.png" alt="">
			                            <img v-if="index == 2" src="../../assets/team/3.png" alt="">
			                            <div v-if="index > 2">{{index+1}}</div>
			                        </div>
			                        <div class="li_center">
			                            <div class="li_center_left">
			                                <img :src="item.avatarUrl ? item.avatarUrl : 'http://youka.qiniuniu.com/uploads/20190611151504525df1727.png'" alt="">
			                            </div>
			                            <div class="li_center_right">
			                                <p>{{item.nickName ? item.nickName : '用户'+item.user_id}}</p>
			                                <van-tag :color="item.level.value == 30?dailishang:(item.level.value==20?jingxiao:fenxiao)" plain>{{item.level.level_name}}</van-tag>
			                            </div>
			                        </div>
			                        <div class="li_right">
			                            <p>￥{{item.total}}</p>
			                        </div>
			                    </li>
			                </ul>
			            </van-row>
			        </van-tab>
			        
			    </van-tabs>
		 </div>
    </div>
</template>
<script>
import {getTeamPrice} from '../../api/data'
export default {
    name:"myInvitation",
    data(){
        return{
             p1List:[
                
            ],
             p2List:[
                
            ],
			p3List:[],
			p4List:[],
            dailishang:"#00BD71",
            jingxiao:"#39BAEF",
            fenxiao:"#FDC2A0"
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
        // 页面跳转
        goToPage(pageName) {
        const self = this;
        self.$router.push({
            path: pageName
        })
        },
    },
	updated() {
		const self = this;
		console.log(self.$refs.content.offsetTop)
		var height = window.screen.height-self.$refs.content.offsetTop;
		console.log(height)
		if(self.$refs.content.offsetHeight < height){
			self.$refs.content.style.height = height+'px';
		}
	},
	created() {
		const self = this;
		getTeamPrice().then(res => {
			console.log(res)
			self.p1List = res.data.today
			self.p2List = res.data.week
			self.p3List = res.data.month
			self.p4List = res.data.all
		})
	}
}
</script>
<style lang="less" scoped>
    #myInvitation{
        background: #FFFFFF;
        .van_today,.van_week,.van_month,.van_laste{
                padding: 0 20px;
                .p_title{
                    font-size: 18px;
                    font-weight: 700;
                    margin: 15px 0 10px 0;
                }
                ul{
                    margin-top: 5px;
                    li{
                        list-style: none;
                        display: flex;
                        align-items: center;
                        border-bottom: 1px solid rgb(221, 220, 220);
                        padding: 5px 0;
                        .li_left{
                            width: 30px;
                            height: 30px;
                            img{
                                width: 100%;
                                height: 100%;
                            }
                            // border: 1px solid red;
                            margin: 0 10px;
                        }
                        .li_center{
                            display: flex;
                            align-items: center;
                            .li_center_left{
                                width: 30px;
                                height: 30px;
                                img{
                                    width: 100%;
                                    height: 100%;
                                }
                                // border: 1px solid red;
                                margin: 0 10px;
                            }
                            .li_center_right{
                                p{
                                    margin: 5px 0 0 10px;
                                    font-size: 16px;
                                }
                                .van-tag{
                                    border: 1px solid;
                                }
                            }
                           
                        }
                        .li_right{
                            
                            font-size: 26px;
                            color: #FA4E1B;
                            position: absolute;
                            right: 20px;
                            
                        }
                    }
                }
            }
    }
</style>