<template>
    <div class="wealthExpen">
        <van-nav-bar class="yg-nav-bar" title="收支流水" left-arrow @click-left="onClickLeft"/>
        <div class="expen">
            <div class="recomment_con">
            <div class="con_left">
                <p class="p_title">收入(元)</p>
                <p class="priceIncome1">{{inMoney}}</p>
            </div>
                <div class="con_right">
                <p class="p_title">支出(元)</p>
                <p class="priceIncome2">{{outMoney}}</p>
            </div>
        </div>
        </div>
        <div class="expen2">

            <ul ref="content">
                <li v-for="(item,index) in ArrList" :key="index" >
                    <div class="con_left">
                        <p class="con_top">{{item.text}}</p>
                        <p class="con_bottom">{{item.create_time}}</p>
                    </div>
                    <div class="con_right">
                        <p class="con_mid" :class="{isFlu:item.price < 0}">{{item.price}}</p>
                    </div>
                </li>
            </ul>
        </div>
		
    </div>
</template>

<script>
import {getPriceList} from '../../api/data'
export default {
    name:"wealthExpen",
    data(){
        return {
			
            inMoney:0,
            outMoney:0,
            ArrList:[
                
            ]
        }
    },
	updated() {
		const self = this;
		console.log(self.$refs.content.offsetTop)
		var height = window.innerHeight-self.$refs.content.offsetTop;
		console.log(height)
		if(self.$refs.content.offsetHeight < height){
			self.$refs.content.style.height = height+'px';
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
	mounted() {
		const self = this;
		getPriceList().then(res => {
			self.inMoney = res.data.inMoney
			self.outMoney = res.data.outMoney
			self.ArrList = res.data.list
		})
	}
}
</script>

<style lang="less">
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
            }
            .priceIncome1{
                font-size: 27px;
                font-weight: 600;
                color: #00BD71;
            }
            .priceIncome2{
                font-size: 27px;
                font-weight: 600;
                color: #FA4E1B;

            }
        }
    }
    .expen2{
        background: #FFFFFF;
        margin-top: 10px;
        
        ul{
            width:100%;
            li{
                border-bottom: 1px solid rgb(196, 194, 194);
                display: flex;
                p{
                    margin: 5px 0;
                    padding: 0;
                }
                .con_left{
                    display: flex;
                    flex: 1;
                    flex-direction: column;
                    justify-content: center;
                    align-items: flex-start;
                    margin-left: 20px;
                    .con_top{
                        font-size: 15px;
                    }
                    .con_bottom{
                        font-size: 14px;
                        color: gray;
                    }
                }
                .con_right{
                    display: flex;
                    flex: 1;
                    justify-content: flex-end;
                    margin-right: 20px;
                    align-items: center;
                    font-size: 18px;
                    font-weight: 600;
                    .con_mid{

                        color: #00BD71;
                    }
                }
            }
        }
        .isFlu{
            color: #FA4E1B!important;
        }
    }
</style>