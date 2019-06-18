<template>
    <div class="weathManagement">
        <van-nav-bar class="yg-nav-bar" title="财富管理" left-arrow @click-left="onClickLeft"/>
    <!-- 图表 -->
    <div class="vol-data" ref="body">
      <div class="charts" style="width:'100%',height:'6.54rem'">
        <div class="charts-title" ref="header">
            <div class="charts-title-center">
              <span @click="changeWeek(index+1)" :class="{isActive:checkedIndex == index}" v-for="(item,index) in weeksList" :key="index">{{item}}</span>
            </div>
        </div>
        <div id="myChart" :style="chartStyle">
        </div>
      </div>
    </div>
    <div class="wealthLink" ref="footer">
        <div @click="goToPage('/user/wealthExpenditure')" class="w-banner1">
              <img src="../../assets/images/wealth/1.png" alt="">
              <p>收支流水</p>
          </div>
          <div @click="goToPage('/user/wealthIncome')" class="w-banner1">
              <img src="../../assets/images/wealth/2.png" alt="">
              <p>奖励收入</p>
          </div>
          <div @click="goToPage('/user/wealthOutcome')" class="w-banner1">
              <img src="../../assets/images/wealth/3.png" alt="">
              <p>奖励支出</p>
          </div>
    </div>
    </div>
</template>
<script>
import {getWealth} from '../../api/data'
import echarts from 'echarts'
export default {
  name: 'WealthManage',
  data () {
    return {
			chartStyle:{width:'100%',height:'500px'},
      checkedIndex:0,
      weeksList:['7天','15天','30天'],
      week:['04.01', '04.02', '04.03', '04.04', '04.05', '04.06', '04.07'],
      week7:['04.01', '04.02', '04.03', '04.04', '04.05', '04.06', '04.07'],
      week15:['04.01', '04.03', '04.05', '04.07', '04.09', '04.11', '04.13', '04.15'],
      week30:['04.01', '04.05', '04.10', '04.15', '04.20', '04.25', '04.30','04.01', '04.05', '04.10', '04.15', '04.20', '04.25', '04.30'],
      datainit:[11, 11, 15, 13, 12, 13, 10],
      datainit7:[11, 78, 15, 36, 12, 13, 45],
      datainit15:[11, 30, 15, 20, 12, 52, 10,30],
      datainit30:[11, 84, 15, 13, 64, 13, 23,11, 84, 15, 13, 64, 13, 23]
    }
  },
  mounted () {
    const self = this;
	console.log(this.$refs.header.offsetHeight);
	console.log(this.$refs.footer.offsetHeight);
	console.log(window.screen.height)
	console.log(window.screen.height-46-this.$refs.header.offsetHeight-this.$refs.footer.offsetHeight)
	this.chartStyle.height = window.innerHeight-66-this.$refs.header.offsetHeight-this.$refs.footer.offsetHeight+'px';
	self.changeCharts();
	setTimeout(function(){
		self.myChart.resize();
	},200)

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
    // 修改天数显示
    changeWeek(item){
      const self = this;
      self.checkedIndex = item-1;

      console.log(self.checkedIndex)
      switch(item){
        case 1:
          self.week = self.week7;
          self.datainit = self.datainit7;
          self.changeCharts();
          break;
        case 2:
          self.week = self.week15;
          self.datainit = self.datainit15;
          self.changeCharts();
          break;
        case 3:
          self.week = self.week30;
          self.datainit = self.datainit30;
          self.changeCharts();
          break;  
      }
    },
	
	getData(){
		
	},
    changeCharts(){
      const self = this;
      /*ECharts图表*/
      var myChart = echarts.init(document.getElementById('myChart'));
      myChart.setOption({
      
      xAxis: {
       type: 'category',
       data: self.week,
       axisLine:{
         lineStyle:{
           color:"#FFFFFF"
         }
       },
       splitLine:{
         show:true
       }
       },
       yAxis: {
           type: 'value',
           name:"近期业务",
            nameTextStyle:{
              color:"#FFFFFF"
            },
            axisLine:{
         lineStyle:{
           color:"#FFFFFF"
         }
       }
       },
       series : [
        {
            name:'财富管理',
            type:'line',
            itemStyle : {
                normal : {
                    color:'#FFFFFF',   // 设置折线的颜色
                }
            },
            // 高亮样式。
            emphasis: {
                itemStyle: {
                    // 高亮时点的颜色。
                    color: 'red'
                },
                label: {
                    show: true,
                    // 高亮时标签的文字。
                    formatter: self.datainit
                }
            },
            smooth: true,          // 设置折线的平滑
            data:self.datainit
            
        }
    ]
     }),
	 self.myChart = myChart;
    }
  },
  created() {
	  const self = this;
	  getWealth().then(res => {
	  	console.log(res)
	  	self.week = res.data.day7.keys
	  	self.week7 = res.data.day7.keys
	  	self.week15 = res.data.day15.keys
	  	self.week30 = res.data.day30.keys
	  	
	  	self.datainit = res.data.day7.data
	  	self.datainit7 = res.data.day7.data
	  	self.datainit15 = res.data.day15.data
	  	self.datainit30 = res.data.day30.data
	  	self.changeCharts();
	  })
  	
  }
}
</script>
<style lang="less">
	
  .charts{
    background: linear-gradient(to right,#FF6E6C,#FD3F41);
    .charts-title{
      
        display: flex;
        justify-content: space-around;
        .charts-title-center{
          span{
            color: #FEBFBE;
            padding: 10px 15px;
            font-size: 18px;
            
          }
          .isActive{
              color: #FFFFFF;
            }
        }
    }
  }
  .wealthLink{
    margin-top: 20px;
    display: flex;
    // justify-content: center;
    .w-banner1{
      margin: 0 10px;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      align-items: center;
      img{
        width: 25px;
				height: 26px;
      }
      p{
        font-size: 14px;
      }
    }
  }
    
</style>
