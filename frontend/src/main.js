import Vue from 'vue';
import './plugins/axios';
import App from './App.vue';
import router from './router';
import infiniteScroll from 'vue-infinite-scroll';
import store from './store';
import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/zh-CN';
import './styles.scss';
import '../common/scss/common.scss';

Vue.use(infiniteScroll);
Vue.use(ElementUI, { locale });

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app');
