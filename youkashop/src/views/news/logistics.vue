<template>
  <div class="yg-logistics">
    <van-nav-bar class="yg-nav-bar" title="物流信息" left-arrow @click-left="onClickLeft"/>
    <div class="logistics-content">
      <div class="info">
        <van-row>
          <van-col span="6" class="text-center">
            <img class="info-header" alt="头像" src="data:image/png;base64,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"/>
          </van-col>
          <van-col span="14" class="text-left">
            <div class="name">{{ren.name}}</div>
            <div class="rate">平均分 <van-rate v-model="rateValue" /></div>
          </van-col>
          <van-col span="4" class="text-center">
            <div class="tel-img"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAEEElEQVRYhcWZeagVZRjGf55TtEB1syDRVkvaqMwsBQNrIgMJS6OMFluk7IZidDQz8oracsum5LpxiUIFKxVcgooij9hCfyS2WRFJaGoqJaKSlTkl7/BMfA4zc2fubc594PC938z7zTzzzbzr6VFZ+zgpaAHuA44H5gCz0xS7gsDzM1cfk3DsbGAN0N859iKwFVheBsksVGLn+gJfOOSmAiskLwGO606CpwOfAz2BQ8B1QCtwl70JkXu2OwmuFzkjM0hzw2FghuQacEp3EJwPXCL5RuDLmN4sYJ/k6Q3kFxLsDTyq+VPAuhTd6PVOBE5uEL+Q4BjJu4HnM3TbgD+0ZnKD+IU3GyJ5ZQe6fwEvSzbneWzJ3EJUZL2GH3PovwD8A5wINJfMLUTFcdYHc+gfANolm48sHRW5EcNJOW/2nMZewB2NILhDcr+ca7YDb0o2Y+lRErcQRvAbyYMKrGvTeBVQLYHXfzCCH2lyhV5bHkQGskWRp1SCnzgGMirHmpGO73wa+LdEfiHBv52M5aEO9E8Alkp+H3ijTHI4sXieRkuzrszQf0sk/wRuL5scDsENwCbJrSm6FwEjJN8mn1g63HRrisZhKbu4z5F3N4IcMYLvOi5ncYLuTiflX9UAbiHiKf+DGi8DHknQf0zjWcDqkrmFiBPcoNrDsDDBL9ou3iP5FsdhN4ygYSywV/J7CeeXOiXoBKccKAVJBA9rd5DbmZug8wSwSHJLzpp5mK7bs8iDJBE0fOzszHjtahwPOE57knxkUp1tNc73cuyrVV+/CgzOQzCrs2B4Bxgu+QagnqDTpldt+EEP9KHmrziGhXynm9ZZHvCaPWjg+YkxvSOChu+AiyUPUGEfx4SYwbwtS4/86acKo9uA+4F7gWsc/WhX2wPP/60owdOAb4EzVJfYhb9O0OuvHs7Q2PEpap3EcT0wDhjtHD8o7/FS4Pm78hI0nAdsBJoUh+37+SpF92Z1Iyxmz0zZcRfnAw/r16TjvwNPBp4/Ly9BwwVqjTTJ0s0q02ro3Ii6W9V67VR9v273ojnNipOwGbga2CNrNYMZU2B9JgLP3xt4/ix116KQOqkIQUTycrkNg8Xs7AZfcaL7laMafi1K0PCLrNMsFRXxn6lk6DKq9ZqVs3frOq2dIYis2aLCTM0Hq+E0oysdh2q91uyUtcsCz1/TWYIRpsuBb9a8RR2K8bLiIuSscbVA03WB599JRqgrgrqy7WdU4Z2j+P2T/OKQjmrnar3mO92zDwLP96Jz/wdBRGyaXFG7PvJeatVZ1fiz+t6znUTExbmSXw88/yb3RFJw7wqsTrZE19yFhbRbgYHAmfoZLLHoI2OLYI790sDzLRgchSKOurOwzu21svILlfRa1LBeY/bfEMARpajizBZ+jPEAAAAASUVORK5CYII=" alt="电话"></div>
            <div class="tel">电话</div>
          </van-col>
        </van-row>
      </div>

      <div class="now">
        <van-steps direction="vertical" :active="0">
          <van-step v-for="item in list">
            <!-- <h3>【城市】物流状态1</h3> -->
            <p>{{item.AcceptStation}}</p>
            <p>{{item.AcceptTime}}</p>
          </van-step>
        </van-steps>
      </div>
    </div>
  </div>
</template>

<script>
import {Toast} from 'vant';
import {ExpressOrder} from '../../api/order'

export default {
  name: "logistics",
  components: {
  },
  data() {
    return {
			list:[],
			ren:{},
      rateValue: 3
    }
  },
  methods: {
    onClickLeft() {
      const self = this;
      self.$router.go(-1);
    },
  },
	mounted() {
		var order_id= this.$route.query.order_id
		ExpressOrder(order_id).then(res => {
			console.log(res)
			this.list = res.data.list
			this.ren = res.data.ren
		})
	}
}
</script>

<style lang="less">
.yg-logistics {
  .logistics-content {
    .info {
      background-color: #fff;
      padding: 0.5rem 0.25rem;
      .info-header {
        width: 46px;
      }
      .name {
        font-size: 20px;
        color: #333;
        padding-bottom: 0.15rem;
      }
      .rate {
        font-size: 16px;
        color: #999;
        display: flex;
        i {
          font-size: 16px!important;
        }
      }
      .tel-img {
        img {
          width: 30px;
        }
      }
      .tel {
        font-size: 12px;
        color: #999;
      }
    }
    .now {
      h3 {
        margin: 0;
      }
      p {
        margin: 0.15rem 0;
      }
    }
  }
}
</style>
