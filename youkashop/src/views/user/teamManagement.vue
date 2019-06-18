<template>
    <div id="teamManagement">
        <van-nav-bar class="yg-nav-bar" title="人员管理" left-arrow @click-left="onClickLeft"/>
        <div class="serach">
            <van-search placeholder="搜索" v-model="searchValue" @search="getData">
                <div slot="left-icon">
                    <i class="van-icon van-icon-search"></i>
                </div>
            </van-search>
        </div>
        <div class="expen">
            <div class="recomment_con">
                <div class="con_left">
                    <p class="p_title">进入新增会员</p>
                    <p class="priceIncome">{{teamToday.todayAmount}}</p>
                    <p class="p_des">昨日：{{teamToday.yesterdayAmount}}人</p>
                </div>
                <div class="con_right">
                    <p class="p_title">本月新增会员</p>
                    <p class="priceIncome">{{teamMonth.monthAmount}}</p>
                    <p class="p_des">昨日：{{teamMonth.lastMonthAmount}}人</p>
                </div>
            </div>
        </div>
        <div class="chanel" ref="content">
            <van-tabs v-model="active" title-inactive-color="#000000" title-active-color="#02BD72">
                <van-tab class="sub_Member" title="直属下级">
                     <van-row v-if="list[30].length > 0">
                        <p class="p_title"><van-icon name="arrow" />市级代理商</p>
                        <ul>
                            <li v-for="(item,index) in list[30]" :key="index">
                                <div class="li_left">
                                    <img :src="item.avatarUrl" alt="">
                                </div>
                                <div class="li_right">
                                    <p>{{item.nickName}}</p>
                                    <p>团队数量:<span class="li_amount">{{item.child_count}}</span>  <span class="li_space"></span> 直属下级:<span class="li_amount">{{item.user_count}}</span></p>
                                </div>
                            </li>
                        </ul>
                    </van-row>
                    <van-row v-if="list[20].length > 0">
                        <p class="p_title"><van-icon name="arrow" />经销商</p>
                        <ul>
                            <li v-for="(item,index) in list[20]" :key="index">
                                <div class="li_left">
                                    <img :src="item.avatarUrl" alt="">
                                </div>
                                <div class="li_right">
                                    <p>{{item.nickName}}</p>
                                    <p>团队数量:<span class="li_amount">{{item.child_count}}</span>  <span class="li_space"></span> 直属下级:<span class="li_amount">{{item.user_count}}</span></p>
                                </div>
                            </li>
                        </ul>
                    </van-row>
                    <van-row v-if="list[10].length > 0">
                        <p class="p_title"><van-icon name="arrow" />分销商</p>
                        <ul>
                            <li v-for="(item,index) in list[10]" :key="index">
                                <div class="li_left">
                                    <img :src="item.avatarUrl" alt="">
                                </div>
                                <div class="li_right">
                                    <p>{{item.nickName}}</p>
                                    <p>团队数量:<span class="li_amount">{{item.child_count}}</span>  <span class="li_space"></span> 直属下级:<span class="li_amount">{{item.user_count}}</span></p>
                                </div>
                            </li>
                        </ul>
                    </van-row>
                </van-tab>
                <van-tab class="team_Member" title="团队成员">
					<van-row v-if="list[30].length > 0">
					    <p class="p_title"><van-icon name="arrow" />市级代理商</p>
					    <ul>
					        <li v-for="(item,index) in list[30]" :key="index">
					            <div class="li_left">
					                <img :src="item.avatarUrl" alt="">
					            </div>
					            <div class="li_right">
					                <p>{{item.nickName}}</p>
					                <p>团队数量:<span class="li_amount">{{item.child_count}}</span>  <span class="li_space"></span> 直属下级:<span class="li_amount">{{item.user_count}}</span></p>
					            </div>
					        </li>
					    </ul>
					</van-row>
                    <van-row v-if="list[20].length > 0">
                        <p class="p_title"><van-icon name="arrow" />经销商</p>
                        <ul>
                            <li v-for="(item,index) in list[20]" :key="index">
                                <div class="li_left">
                                    <img :src="item.avatarUrl" alt="">
                                </div>
                                <div class="li_right">
                                    <p>{{item.nickName}}</p>
                                    <p>团队数量:<span class="li_amount">{{item.child_count}}</span>  <span class="li_space"></span> 直属下级:<span class="li_amount">{{item.user_count}}</span></p>
                                </div>
                            </li>
                        </ul>
                    </van-row>
					 <van-row v-if="list[10].length > 0">
					    <p class="p_title"><van-icon name="arrow" />分销商</p>
					    <ul>
					        <li v-for="(item,index) in list[10]" :key="index">
					            <div class="li_left">
					                <img :src="item.avatarUrl" alt="">
					            </div>
					            <div class="li_right">
					                <p>{{item.nickName}}</p>
					                <p>团队数量:<span class="li_amount">{{item.child_count}}</span>  <span class="li_space"></span> 直属下级:<span class="li_amount">{{item.user_count}}</span></p>
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

import {getTeam} from '../../api/data'
export default {
    name:"teamManagement",
    data(){
        return{
            active:0,
            searchValue:'',
            teamToday:{
                todayAmount:0,
                yesterdayAmount:2
            },
            teamMonth:{
                monthAmount:0,
                lastMonthAmount:23
            },
			list:[],
			all_list:[],
            p1List:[
                {
                    pic:"../../assets/images/user/my_ach.png",
                    name:"钱宇",
                    teamAmount:12,
                    memberAmount:123
                },
                 {
                    pic:"../../assets/images/user/telephone.png",
                    name:"拉菲",
                    teamAmount:12,
                    memberAmount:123
                },
                 {
                    pic:"../../assets/images/user/telephone.png",
                    name:"蒋丽",
                    teamAmount:12,
                    memberAmount:123
                }
            ],
             p2List:[
                {
                    pic:"../../assets/images/user/telephone.png",
                    name:"钱宇",
                    teamAmount:12,
                    memberAmount:123
                },
                 {
                    pic:"../../assets/images/user/telephone.png",
                    name:"拉菲",
                    teamAmount:12,
                    memberAmount:123
                },
                 {
                    pic:"../../assets/images/user/telephone.png",
                    name:"蒋丽",
                    teamAmount:12,
                    memberAmount:123
                }
            ]
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
		getData(){
			const self = this;
			getTeam(self.searchValue).then(res =>{
				console.log(res)
				self.teamToday.todayAmount = res.data.today_user_count
				self.teamToday.yesterdayAmount = res.data.yesterday_user_count
				self.teamMonth.monthAmount = res.data.month_user_count
				self.teamMonth.lastMonthAmount = res.data.last_month__user_count
				self.list = res.data.list
				self.all_list = res.data.all_list
				
				console.log(self.list[10].length)
			})
		},
    },
	updated() {
		const self = this;
		console.log(self.$refs.content.offsetTop)
		var height = window.innerHeight-self.$refs.content.offsetTop;
		console.log(height)
		self.$refs.content.style.height = height+'px';
	},
	mounted() {
		
	},
	created() {
		this.getData();
	}
}
</script>
<style lang="less" scoped>
    
    #teamManagement{
        .search{
            
        }
        .expen{
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #FFFFFF;
            margin: 10px 0;

            .recomment_con{
                display: flex;
                flex-direction: row;
                justify-content: space-around;
                width: 100%;
                .con_left{
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }
                .con_right{
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }
                p{
                    margin: 0;
                    padding: 10px 0;
                }
                .p_title{
                    font-size: 14px;
                    color: rgb(136, 135, 135);
                }
                .priceIncome1{
                    font-size: 30px;
                    font-weight: 700;
                    color: black;
                }
                .p_des{
                    font-size: 13px;
                    color: rgb(185, 184, 184);
                }
            }
        }
        .chanel{
            background: #FFFFFF;
            .title-active-color{
                color: #02BD72;
            }
            // 团队成员
            .team_Member,.sub_Member{
                padding: 0 20px;
                .p_title{
                    font-size: 18px;
                    font-weight: 700;
                    margin: 15px 0 10px 0;
                }
                ul{
                    li{
                        list-style: none;
                        display: flex;
                        align-items: center;
                        border-bottom: 1px solid rgb(204, 204, 204);
                        .li_left{
                            width: 40px;
                            height: 40px;
                            img{
                                width: 100%;
                                height: 100%;
                            }
                            // border: 1px solid red;
                            margin: 0 20px;
                        }
                        .li_right{
                            .li_amount{
                                color: #02BD72;
                            }
                            .li_space{
                                padding: 0 10px;
                            }
                            p:first-child{
                                font-size: 16px;

                            }
                            p:last-child{
                                font-size: 14px;
                                color: rgb(163, 163, 163);
                            }
                            
                        }
                    }
                }
            }
        }
    }
</style>