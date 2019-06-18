<template>
  <div class="yg-evaluation">
    <van-nav-bar class="yg-nav-bar" title="发布评价" left-arrow @click-left="onClickLeft" right-text="发布" @click-right="onClickRight"/>
		<div class="item" v-for="item in order.goods">
			<div class="row">
				<img :src="item.image.file_path" alt="">
				<div class="name">{{item.goods_name}}</div>
			</div>
			<van-cell-group class="evaluation-content">
			  <van-field v-model="item.content" type="textarea" placeholder="请输入评价内容" rows="3" :autosize="{minHeight: 50 }"/>
			</van-cell-group>
		</div>
  </div>
</template>

<script>
import {getOrderDetail,Comment} from '../../api/order'
export default {
  name: "evaluation",
  components: {
  },
  data() {
    return {
			order:{},
      evaluationValue: '',
    }
  },
  methods: {
    /**
     * 返回
     */
    onClickLeft() {
      const self = this;
      self.$router.go(-1);
    },
    /**
     * 发布评价
     */
    onClickRight() {
      const self = this;
      console.log('评价内容');
			Comment(self.order.goods,self.order.order_id).then(res => {
				if(res.code == 1){
					self.onClickLeft()
				}
			})
    },
		getDetail(order_id){
			getOrderDetail(order_id).then(res => {
				this.order = res.data.order
				this.show = true
			})
		},
  },
	created() {
		var order_id= this.$route.query.order_id
		this.getDetail(order_id);
	}
}
</script>

<style lang="less">
	.row{
		background: #fff;
		display: flex;
		align-items: center;
		padding: 10px 17px;
		img{
			width: 30px;
			height: 30px;
			margin-right: 10px;
		}
		.name{
			font-size: 16px;
		}
	}
.yg-evaluation {
  .evaluation-content {

  }
}
</style>
