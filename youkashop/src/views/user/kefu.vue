<template>
  <div id="webId">
    <!-- 如果碰到滑动问题，1.1 请检查这里是否属于同一点。 -->
    <!-- 悬浮的HTML -->
    <div
      
      class="xuanfu"
      id="moveDiv"
      @mousedown.stop="down"
      @touchstart.stop="down"
      @mousemove.stop="move"
      @touchmove.stop="move"
      @mouseup.stop="end"
      @touchend.stop="end"
       @click="goToPage('/helper')"
    >
      <div class="yuanqiu">
        <img src="data:image/png;base64,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" alt="kefu">
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "kefu",
  data() {
    return {
      isShow:"",
      flags: false,
      position: { x: 0, y: 0 },
      nx: "",
      ny: "",
      dx: "",
      dy: "",
      xPum: "",
      yPum: ""
    };
  },

  methods: {
      // 页面跳转
    goToPage(pageName) {
      const self = this;
      self.$router.push({
        path: pageName
      })
    },
    // 实现移动端拖拽
    down() {
      this.flags = true;
      var touch;
      if (event.touches) {
        touch = event.touches[0];
      } else {
        touch = event;
      }
      this.position.x = touch.clientX;
      this.position.y = touch.clientY;
      this.dx = moveDiv.offsetLeft;
      this.dy = moveDiv.offsetTop;
    },
    move() {
      if (this.flags) {
        var touch;
        if (event.touches) {
          touch = event.touches[0];
        } else {
          touch = event;
        }
        // 超出判断
        if(touch.clientX < 20 ){
            touch.clientX = 20;
        }else if(touch.clientX > window.screen.width -20){
            touch.clientX = window.screen.width -20;
        }
        if(touch.clientY < 20 ){
            touch.clientY = 20;
        }else if(touch.clientY > window.screen.height -20){
            touch.clientY = window.screen.height -20;
        }

        this.nx = touch.clientX - this.position.x;
        this.ny = touch.clientY - this.position.y;
        this.xPum = this.dx + this.nx;
        this.yPum = this.dy + this.ny;
        moveDiv.style.left = this.xPum + "px";
        moveDiv.style.top = this.yPum + "px";

        //阻止页面的滑动默认事件；如果碰到滑动问题，1.2 请注意是否获取到 touchmove
        document.addEventListener(
          "touchmove",
          function() {
            event.preventDefault();
          },
          false
        );
      }
    },
    //鼠标释放时候的函数
    end() {
      this.flags = false;
    }
  }
};
</script>
<style>
.xuanfu {
  height: 0;
  width: 0;
  /* 如果碰到滑动问题，1.3 请检查 z-index。z-index需比web大一级*/
  z-index: 999;
  position: fixed;
  top: 50%;
  right: 13%;
  border-radius: 0.8rem;
  background-color: rgba(0, 0, 0, 0.55);
}
.yuanqiu {
  height: 0;
  width: 0;
  border: 0 solid rgba(140, 136, 136, 0.5);
  margin: 0.65rem auto;
  color: #000000;
  font-size: 1.6rem;
  line-height: 2.7rem;
  text-align: center;
  border-radius: 100%;
  background-color: #ffffff;
}
.yuanqiu img{
    width: 40px;
}
</style>