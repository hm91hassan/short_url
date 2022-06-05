import './bootstrap';

window.Vue = require('vue').default;
import Notifications from 'vue-notification'
Vue.use(Notifications)

Vue.component('url-component', require('./components/UrlComponent.vue').default);

const app = new Vue({
    el: '#app',
});
