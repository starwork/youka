<template>
	<div class="main" :style="style">
		<van-nav-bar class="yg-nav-bar" title="关注公众号"/>
		<div class="title">亲，您还没有关注悠咖公众号哦</div>
		<div class="qrcode">
			<img src="../assets/qrcode.jpg" alt="">
		</div>
		<div class="footer">
			<img src="../assets/footer.png" alt="">
		</div>
	</div>
</template>

<script>
	import {Issubscribe} from '../api/data'
	export default{
		name: 'subscribe',
		data(){
			return {
				style:{height: '100px'},
			}
		},
		activated() {
			Issubscribe().then(res => {
				if(res.code == 1){
					this.$router.push({
						path: '/home'
					})
				}
			})
		},
		created(){
			var height = window.innerHeight-46;
			this.style.height = height+'px';
		}
	}
</script>
	
<style lang="less" scoped>
	body{
		font-family: '微软雅黑';
	}
	.main{
		background: #fff;
	}
	.title{
		margin-top: 30px;
		margin-bottom: 48px;
		font-size: 18px;
		color: #00BD71;
		text-align: center
	}
	.qrcode{
		text-align: center;
		img{
			width: 180px;
			height: 180px;
			text-align: center
		}
	}
	.footer{
		position: fixed;
		bottom: 0;
		img{
			width: 100%;
		}
	}
</style>
