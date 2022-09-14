import Vue from 'vue';
import axios from 'axios';
import router from './router';
import VueCookie from 'vue-cookie';
import VueSocketIO from 'vue-socket.io';
import VueAxios from 'vue-axios';
import VueFullscreen from 'vue-fullscreen';

Vue.use(VueFullscreen);
Vue.use(VueAxios, axios);
Vue.use(VueCookie);

let port = '8443';

Vue.use(new VueSocketIO({
    connection: `${window.location.protocol}//${window.location.hostname}:${port}`
}));

axios.defaults.baseURL = '/';

import Pay from "./components/Payment";

const app = new Vue({
    el: '#app',
    data: {
            
    },
    methods: {

    },
    router,
    components: {
        Pay
    }
});
