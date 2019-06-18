<template>
  <div class="yg-helper">
    <van-nav-bar class="yg-nav-bar" title="服务协议" left-arrow @click-left="onClickLeft"/>
    <div class="user-helper-content" ref="userInviteRef">
      <div class="help-3" v-html="content">
      </div>
    </div>
  </div>
</template>

<script>
import {getServer} from "../../api/data"
export default {
  name: "server",
  components: {
  },
  data() {
    return {
		content:''
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
  },
  mounted() {
    const self = this;
    self.$refs.userInviteRef.style.min_height = window.screen.height - 47 - 4 + 'px';
		getServer().then(res => {
			this.content = res.data.content
		})
  }
}
</script>

<style lang="less">
p {
	margin: 0;
	font-size: 12px;
	color: #666;
	line-height: 20px;
}
.yg-helper {
  .user-helper-content {
    background-color: #fff;
    padding: 0.25rem;
    .help-3 {
      padding-top: 0.2rem;
      .title {
        font-size: 14px;
        color: #333;
        padding: 0.25rem 0 0.2rem 0;
      }
      p {
        margin: 0;
        font-size: 16px;
        color: #666;
        line-height: 24px;
      }
    }
  }
}
</style>
