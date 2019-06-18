import Vue from 'vue'

import Vant from 'vant';
import 'vant/lib/index.css';

import App from './App.vue'
import router from './router'
import store from './store'
import './registerServiceWorker'

import 'amfe-flexible';
import './assets/style/variable.less';

import './permission';

import echarts from 'echarts';

Vue.use(Vant);

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app');
