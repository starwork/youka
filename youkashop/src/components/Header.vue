<template>
  <div class="header">
    <van-nav-bar>
      <div slot="title" class="yg-header-title">
        悠咖
      </div>
<!--      <van-icon name="wap-nav" slot="left" size="0.75rem" color="#5b5b5b" @click="onClickLeft"/>-->
      <van-icon name="search" slot="right" size="0.75rem" color="#5b5b5b" @click="onClickRight"/>
    </van-nav-bar>
  </div>
</template>

<script>
export default {
  name: 'Header',
  components: {
  },
  methods: {
    onClickLeft() {
      const self = this;
      self.$toast('菜单');
    },
    onClickRight() {
      const self = this;
      self.$router.push({
        path: '/list'
      })
    },
  },
}
</script>
<style lang="less">
.yg-header-title {
  color: @mainColor;
}
</style>
