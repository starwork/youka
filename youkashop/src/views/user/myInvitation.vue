<template>
    <div id="myInvitation">
         <van-nav-bar class="yg-nav-bar"  left-arrow @click-left="onClickLeft">
             <p class="nav_bar_title" slot="title" @click="show = !show">{{title}}({{num}})</p>
         </van-nav-bar>
		  <div v-show="show" class="li_class">
             <ul>
                 <li v-for="(item,index) in titleList" :class="{actived:curIndex == index}" @click="chooseLi(index,item)" :key="index">
                     <p class="li_left">{{item.title}}</p>
                     <p class="li_right">{{item.num}}</p>
                 </li>
             </ul>
         </div>
         <div ref="content" :style="minHeight ? minHeight : ''">
			 <van-tabs class="body" v-model="active" title-inactive-color="#000000" title-active-color="#02BD72">
			        <van-tab class="tab_actived" title="激活">
			             <van-row>
			                <ul>
			                    <li v-for="(item,index) in p1List" @click="goToPage('/user/teamUserInfo',item.user_id)" :key="index">
			                        <div class="li_left">
			                            <img :src="item.avatarUrl" alt="">
			                        </div>
			                        <div class="li_right">
			                            <div class="li_right_top">
			                                <p>{{item.nickName}}</p>
			                                <van-tag :color="item.level.value == 30?dailishang:(item.level.value==20?jingxiao:fenxiao) " plain>{{item.level.level_name}}</van-tag>
			                            </div>
			                            <p>加入时间：{{item.create_time}}</p>
			                        </div>
			                    </li>
			                </ul>
			            </van-row>
			            
			        </van-tab>
			        <van-tab class="tab_noactived" title="非激活">
			            <van-row>
			                <ul>
			                    <li v-for="(item,index) in p2List" @click="goToPage('/user/teamUserInfo',item.user_id)" :key="index">
			                        <div class="li_left">
			                            <img :src="item.avatarUrl" alt="">
			                        </div>
			                        <div class="li_right">
			                            <div class="li_right_top">
			                                <p>{{item.nickName}}</p>
			                                <van-tag :color="item.level.value == 30?dailishang:(item.level.value==20?jingxiao:fenxiao) " plain>{{item.level.level_name}}</van-tag>
			                            </div>
			                            <p>加入时间：{{item.create_time}}</p>
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
import {getChild} from '../../api/data'
export default {
    name:"myInvitation",
    data(){
        return{
			title:"全部",
            num:12,
			minHeight: '100px',
			show:false,
			curIndex:0,
			titleList:[
				{
					title: '全部',
					num:10,
				},
				{
					title: '全部',
					num:10,
				},
				{
					title: '全部',
					num:10,
				}
			],
             p1List:[
                {
                    pic:"../../assets/images/user/my_ach.png",
                    name:"钱宇",
                    tag:"市级代理商",
                    time:"2019-03-15 15:05:21"
                },
                 {
                    pic:"../../assets/images/user/telephone.png",
                    name:"拉菲",
                    tag:"经销商",
                    time:"2019-03-15 15:05:21"
                },
                 {
                    pic:"../../assets/images/user/telephone.png",
                    name:"蒋丽",
                    tag:"分销商",
                    time:"2019-03-15 15:05:21"
                }
            ],
            p2List:[
                {
                    pic:"../../assets/images/user/my_ach.png",
                    name:"钱宇",
                    tag:"分销商",
                    time:"2019-03-15 15:05:21"
                },
                 {
                    pic:"../../assets/images/user/telephone.png",
                    name:"拉菲",
                    tag:"分销商",
                    time:"2019-03-15 15:05:21"
                },
                 {
                    pic:"../../assets/images/user/telephone.png",
                    name:"蒋丽",
                    tag:"分销商",
                    time:"2019-03-15 15:05:21"
                }
            ],
            dailishang:"#00BD71",
            jingxiao:"#39BAEF",
            fenxiao:"#FDC2A0"
        }
    },
	updated() {
		const self = this;
		console.log(self.$refs.content.offsetHeight)
		var height = window.innerHeight-self.$refs.content.offsetTop;
		self.minHeight = height+'px';
		
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
        goToPage(pageName,user_id) {
        const self = this;
        self.$router.push({
            path: pageName,
			query:{
				user_id:user_id
			}
        })
        },
		getData(level){
			getChild(level).then(res => {
				this.titleList = res.data.select
				this.p1List = res.data.child_list
				this.p2List = res.data.not_child_list
				if(level == ''){
					this.title = res.data.select[0].title;
					this.num = res.data.select[0].num;
				}
			})
		},
		chooseLi(index,item) {
			// 点击选项时默认不会关闭菜单，可以手动关闭
			this.show = false;
			this.title = item.title;
			this.p1List = [];
			this.num = item.num;
			this.curIndex = index;
			this.getData(item.level)
        },
    },
    created() {
    	this.getData('');
    }
}
</script>
<style lang="less" scoped>
    .actived{
        color: rgb(98, 203, 238);
    }
    #myInvitation{
        background: #FFFFFF;
        .nav_bar_title{
            margin: 0;
        }
        .li_class{
            background: #FFFFFF;
            z-index: 999;
            ul{
                li{
                    list-style: none;
                    display: flex;
                    justify-content: space-around;
                    p{
                        margin: 10px 0;
                        font-size: 16px;
                    }
                    .li_left{
                        flex: 1;
                        text-align: left;
                        padding-left: 30px;
                    }
                    .li_right{
                        flex: 1;
                        text-align: right;
                        padding-right: 30px;
                    }
                }
            }
        }
        .tab_actived,.tab_noactived{
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
                        .li_left{
                            width: 30px;
                            height: 30px;
                            img{
                                width: 100%;
                                height: 100%;
                            }
                            // border: 1px solid red;
                            margin: 0 20px;
                        }
                        .li_right{
                            .li_right_top{
                                p{
                                    margin: 5px 0;
                                }
                                display: flex;
                                align-items: center;
                                .van-tag{
                                    margin-left: 8px;
                                    border: 1px solid;
                                }
                            }
                            p:first-child{
                                font-size: 16px;

                            }
                            p:last-child{
                                margin: 5px 0;
                                font-size: 14px;
                                color: rgb(163, 163, 163);
                            }
                            
                        }
                    }
                }
            }
    }
</style>