<template>
  <div>
    <transition name="fade" mode="out-in">
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
