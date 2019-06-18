<template>
    <div class="wealthIncome">
        <van-nav-bar class="yg-nav-bar" title="奖励支出" left-arrow @click-left="onClickLeft"/>
        <div class="totalIncome">
            <p class="p_title">总金额支出</p>
            <p class="priceIncome">{{totalIncome}}</p>
        </div>
        <div class="monthIncome">
            <div class="month" @click=" show = true">{{format}}</div>
            <p class="p_title">支出金额(元)</p>
            <p class="priceIncome">{{monthIncome}}</p>
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
import {getOutPrice,formatTimeToStr} from '../../api/data'
export default {
    name:"wealthIncome",
    data(){
        return{
			show:false,
			currentDate: new Date(),
			monthDate: new Date(),
            totalIncome:0,
            monthIncome:0,
            list:[]
        }
    },
	created() {
		this.getData('');
	},
	computed:{
		format(){
			return formatTimeToStr(this.monthDate,"yyyy年MM月")
		}
	},
    methods:{
		 //金额变动动画
		numFun(startNum,maxNum) {
		  var that = this
		  let numText = startNum;
		  let golb; // 为了清除requestAnimationFrame
		  function numSlideFun(){ // 数字动画
			  numText+=5; // 速度的计算可以为小数 。数字越大，滚动越快
			  if(numText >= maxNum){
				  numText = maxNum;
				  cancelAnimationFrame(golb);
			  }else {
				  golb = requestAnimationFrame(numSlideFun);
			  }
			that.amount=numText
			// console.log(numText)
		  }
		   numSlideFun(); // 调用数字动画
		},
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
			getOutPrice(time).then(res => {
				console.log(res)
				this.totalIncome = Math.abs(res.data.total)
				this.monthIncome = Math.abs(res.data.month_total)
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
            self.price = self.recommentSelected.price;
        }
    }
}
</script>
<style lang="less">
	.month{
		margin-top: 10px;
		font-size: 14px;
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
   
</style>