<template>
    <div class="wealthIncome">
        <van-nav-bar class="yg-nav-bar" title="奖励收入" left-arrow @click-left="onClickLeft"/>
        <div class="totalIncome">
            <p class="p_title">总奖金收入</p>
            <p class="priceIncome">{{totalIncome}}</p>
        </div>
        <div class="monthIncome">
			<div class="month" @click=" show = true">{{format}}</div>
            <!-- <select v-model="couponSelected" @change="getCouponSelected">                                        
                <option :value="coupon.total" v-for="(coupon,index) in couponList" :key="index" >{{coupon.name}}</option>                                    
            </select> -->
            <p class="p_title">收入金额(元)</p>
            <p class="priceIncome">{{monthIncome}}</p>
        </div>
        <!-- <div class="recomment">
           <select v-model="recommentSelected" @change="getRecommentSelected">                                        
                <option :value="item.amount" v-for="(item,index) in recommentList" :key="index" >{{item.name}}</option>                                    
            </select>
            <div class="recomment_con">
                <div class="con_left">
                    <p class="p_title">单品数量(件)<router-link to="/user/aloneAmount"> <van-icon name="arrow"/></router-link></p>
                    <p class="priceIncome1">{{recommentAmount}}</p>
                </div>
                 <div class="con_right">
                    <p class="p_title">奖励(元)</p>
                    <p class="priceIncome2">{{price}}</p>
                </div>
            </div>
        </div> -->
		
		<div class="aloneDetail">
		    <div class="al_con">
		        <div class="al_title">
		            <p class="al_type">商品名称</p>
		            <p class="al_amount">单品业绩</p>
		            <p class="al_price">奖金(元)</p>
		        </div>
		        <div class="al_content">
		            <ul>
		                <li v-for="(item,index) in list" :key="index">
		                    <p>{{item.goods_name}}</p>
		                    <p>{{item.goods_num}}</p>
		                    <p>{{item.price}}</p>
		                </li>
		            </ul>
		        </div>
		    </div>
		</div>
		
		<van-popup position="bottom" v-model="show">
			<van-datetime-picker
			  v-model="monthDate"
			  :max="currentDate"
			  :formatter="formatter"
			  type="year-month"
			  @confirm = "ChangeMonthDate"
			  @cancel = "show = false"
			/>
		</van-popup>
    </div>
</template>
<script>
import {getMonthPrice,formatTimeToStr} from '../../api/data'
export default {
    name:"wealthIncome",
    data(){
        return{
			show:false,
			currentDate: new Date(),
			monthDate: new Date(),
            totalIncome:12421.4,
            monthIncome:123.44,
            recommentAmount:231,
            price:1200,
            couponList:[
                    {
                        id: '0',
                        name: '2019年03月',
                        income:2000
                    },
                    {
                        id: '1',
                        name: '2019年04月',
                        income:3000
                    },
                    {
                        id: '2',
                        name: '2019年05月',
                        income:4000
                    },
                     {
                        id: '3',
                        name: '2019年06月',
                        income:5000
                    }
                ],
                recommentList:[
                    {
                        id: '0',
                        name: '2019年03月',
                        amount:{
                            count:231,
                            price:1200
                        }
                    },
                    {
                        id: '1',
                        name: '2019年04月',
                        amount:{
                            count:330,
                            price:1300
                        }
                    },
                    {
                        id: '2',
                        name: '2019年05月',
                       amount:{
                            count:460,
                            price:1400
                        }
                    },
                     {
                        id: '3',
                        name: '2019年06月',
                        amount:{
                            count:550,
                            price:1500
                        }
                    }
                ],
                couponSelected: '',
                recommentSelected: '',
        }
    },
	computed:{
		format(){
			return formatTimeToStr(this.monthDate,"yyyy年MM月")
		}
	},
    methods:{
		formatter(type, value) {
		  if (type === 'year') {
			return `${value}年`;
		  } else if (type === 'month') {
			return `${value}月`
		  }
		  return value;
		},
		ChangeMonthDate(value){
			console.log(value)
			this.monthDate = value
			this.getData(formatTimeToStr(this.monthDate,"yyyy-MM-01"))
			this.show = false;
		},
		getData(time){
			getMonthPrice(time).then(res => {
				console.log(res)
				this.totalIncome = res.data.total
				this.monthIncome = res.data.month_total
				this.list = res.data.list
			})
		},
        /**
     * 返回
     */
    onClickLeft() {
      const self = this;
      self.$router.go(-1);
    },
    // 收入选择月份
        getCouponSelected(item){
            const self = this;
            self.monthIncome = self.couponSelected;
        },
        // 分销商选择月份
        getRecommentSelected(item){
            const self = this;
            // console.log(self.recommentSelected);
            self.recommentAmount = self.recommentSelected.count;
            self.price = self.recommentSelected.total;
        }
    },
	created() {
		this.getData('');
	}
}
</script>
<style lang="less">
	.aloneDetail{
	    display: flex;
	    flex-direction: column;
	    align-items: center;
	    background: #FFFFFF;
	    margin: 10px 0;
	    select{
	        border: none;
	        margin-top: 10px;
	    }
	    .al_con{
	        display: flex;
	        flex-direction: column;
	        width: 100%;
	        .al_title{
	            display: flex;
	            background: rgb(175, 174, 174);
	            justify-content: center;
	            margin-top: 10px;
	            p{
	                flex: 1 1 33.33%;
	                text-align: center;
	                padding: 0;
	                font-size: 14px;
	            }
	        }
	        .al_content{
	            display: flex;
	            justify-content: center;
	            ul{
	                 width: 100%;   
	                li{
	                    display: flex;
	                    
	                    p{
	                        flex: 1 1 33.33%;
	                        text-align: center;
	                        padding: 0;
	                        font-size: 14px;
	                    }
	                }
	            }
	            
	           
	        }
	    }
	}
    .totalIncome{
        display: flex;
        flex-direction: column;
        align-items: center;
        background: #FFFFFF;
        margin: 10px 0;
        p{
            padding: 8px;
            margin: 0;
        }
        .p_title{
            font-size: 14px;
        }
        p:last-child{
            font-size: 27px;
            font-weight: 600;
            color: #00BD71;
        }
        
    }
	.month{
		margin-top: 10px;
		font-size: 14px;
	}
    .monthIncome{
        display: flex;
        flex-direction: column;
        align-items: center;
        background: #FFFFFF;
        margin: 10px 0;
        select{
            border: none;
            margin-top: 10px;
        }
        p{
            padding: 8px;
            margin: 0;
        }
        .p_title{
            font-size: 14px;
            margin-top: 5px;
        }
        p:last-child{
            font-size: 27px;
            font-weight: 600;
            color: #00BD71;
        }
    }
    .recomment{
        display: flex;
        flex-direction: column;
        align-items: center;
        background: #FFFFFF;
        margin: 10px 0;
        select{
            border: none;
            margin-top: 10px;
        }
        .recomment_con{
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            width: 100%;
            .con_left{
                display: flex;
                flex-direction: column;
                align-items: center;
                // margin-right: 20px;
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
            }
            .priceIncome2{
                font-size: 27px;
                font-weight: 600;
                color: #00BD71;

            }
        }
    }
</style>