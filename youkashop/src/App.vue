<template>
  <div id="app">
    <transition name="fade" mode="out-in" v-if="$route.meta.keepAlive">
			<keep-alive >
				<router-view class="view"/> 
			</keep-alive>
			<router-view class="view" v-if="$route.meta.keepAlive == false"/> 
    </transition>
	<transition name="fade" mode="out-in" v-else>
			<router-view class="view"/> 
	</transition>
  </div>
</template>
<script>
export default {
  data () {
    return {
      transitionName: 'slide-left'
    }
  },
  beforeRouteUpdate (to, from, next) {
    const toDepth = to.path.split('/').length;
    const fromDepth = from.path.split('/').length;
    this.transitionName = toDepth < fromDepth ? 'slide-right' : 'slide-left';
    next();
  },
}
</script>
<style>
	.loading{
		width: 100%;
		text-align: center;
	}
  .flex-row{
    display: flex;
  }
  .between{
    justify-content: space-between;
  }
</style>
