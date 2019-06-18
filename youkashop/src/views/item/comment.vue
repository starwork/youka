<template>
	<div>
		<van-nav-bar class="yg-nav-bar" title="全部评价" left-arrow @click-left="onClickLeft"/>
		<div ref="mescroll" class="mescroll">
			<div class="title">宝贝评价（{{total}}）</div>
			<div id="content">
				<div class="item" v-for="item in dataList">
					<div class="head">
						<img :src="item.user.avatarUrl" alt="">
						<div class="head-content">
							<div class="name">{{item.user.nickName}} <span>{{item.user.level.level_name}}</span></div>
							<div class="time">{{item.create_time}}</div>
						</div>
					</div>
					<div class="content">{{item.content}}</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import {getCommentList} from '../../api/goods'
	import MeScroll from 'mescroll.js'
	import 'mescroll.js/mescroll.min.css'
	export default{
		name : 'comment_list',
		data(){
			return {
				total:0,
				mescroll: null, //mescroll实例对象
				dataList:[]	//列表数据
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
			upCallback(page) {
				getCommentList(page.goods_id,page.num).then(res => {
					if(res.code == 1){
						this.total = res.data.total
						let arr = res.data.data;
						if (page.num == 1) this.dataList = [];
						this.dataList = this.dataList.concat(arr);
						this.$nextTick(() => {
						  this.mescroll.endSuccess(arr.length);
						})
					}
				}).catch((e)=> {
			        //联网失败的回调,隐藏下拉刷新和上拉加载的状态;
			        this.mescroll.endErr();
				})
			},
		},
		mounted() {
			var goods_id = this.$route.query.goods_id;
			this.mescroll = new MeScroll(this.$refs.mescroll,{
				up: {
					callback: this.upCallback,
		        	// 以下是一些常用的配置,当然不写也可以的.
					page: {
						num: 0, //当前页 默认0,回调之前会加1; 即callback(page)会从1开始
						goods_id: goods_id,
					},
					htmlNodata: '<p class="upwarp-nodata">-- END --</p>',
					noMoreSize: 5, //如果列表已无数据,可设置列表的总数量要大于5才显示无更多数据;避免列表数据过少(比如只有一条数据),显示无更多数据会不好看
								
					toTop: {
						//回到顶部按钮
						src: "./static/mescroll/mescroll-totop.png", //图片路径,默认null,支持网络图
						offset: 1000, //列表滚动1000px才显示回到顶部按钮
					},
					empty: {
						//列表第一页无任何数据时,显示的空提示布局; 需配置warpId才显示
						warpId:	"content", //父布局的id (1.3.5版本支持传入dom元素)
						//icon: "./static/mescroll/mescroll-empty.png", //图标,默认null,支持网络图
						tip: "暂无相关数据~", //提示
					}
				}
			});
		}
	}
</script>

<style lang="less" scoped>
	/*通过fixed固定mescroll的高度*/
  .mescroll {
	background: #fff;
	position: fixed;
	top: 44px;
	bottom: 0;
	height: auto;
  }
  .title{
	 margin-top: 15px;
	 font-size: 16px;
	 margin-left: 17px;
  }
  .item{
	  border-bottom: 1px solid #eee;
	  padding: 13px 0;
	  margin: 0 17px;
	  .head{
		  display: flex;
		  img{
			  width: 28px;
			  height: 28px;
			  border-radius: 50%;
			  margin-right: 6px;
		  }
		  .head-content{
			  .name{
				  font-size: 12px;
				  span{
					  display: inline-block;
					  border: 1px solid #FB8642;
					  color: #FB8642;
					  height: 15px;
					  line-height: 15px;
					  font-size: 10px;
					  padding: 1px 4px;
				  }
			  }
			  .time{
				  font-size: 10px;
				  color: #d8d8d8;
			  }
		  }
	  }
	  .content{
		  margin-left: 31px;
		  font-size: 14px;
		  line-height: 32px;
	  }
  }
</style>
