import Vue from 'vue';
import axios from 'axios';
import router from './router';
import VueCookie from 'vue-cookie';
import VueSocketIO from 'vue-socket.io';
import VueAxios from 'vue-axios';
import GraphQL from "./helpers/graphql";
import VueFullscreen from 'vue-fullscreen';

Vue.use(VueFullscreen);
Vue.use(VueAxios, axios);
Vue.use(VueCookie);

let port = '8443';

Vue.use(new VueSocketIO({
    connection: `${window.location.protocol}//${window.location.hostname}:${port}`
}));

axios.defaults.baseURL = '/';

import Layout from "./components/Layout";

const app = new Vue({
    el: '#app',
    data: {
        graphql: new GraphQL(),
        user: null,
        darkTheme: false,
        isLoading: false
    },
    created() {
        if (this.$cookie.get('token')) {
            this.getUser();
        }

        if (this.$cookie.get('darkTheme') == 1) { // темная тема
            this.darkTheme = true;
        } else if(this.$cookie.get('darkTheme') == 0) { // светлая тема 
        } else {
            this.$cookie.set('darkTheme', 1);
            this.darkTheme = true;
        }

        this.setDarkTheme();

        window.addEventListener('beforeunload', this.userLog());
        
    },
    watch: {
        'user.balance'(newBalance, oldBalance) {
            if (typeof oldBalance !== "undefined" && typeof newBalance !== "undefined") {
                this.updateBalance(newBalance, oldBalance);
            }
        }
    },
    methods: {
        userLog() {
            if (this.user != null) {
                this.$socket.emit('userSession', {
                    type: "Вышел",
                    id: this.user.id
                });
            }
        },
        getUser() {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$cookie.get('token');

            this.$root.graphql.getUser().then(res => {
                this.user = res.User;

                setTimeout(() => {
                    this.updateBalance(this.user.balance, this.user.balance);
                }, 100);

                if (this.user != null) {
                    this.$socket.emit('userSession', {
                        type: "Вошел",
                        id: this.user.id
                    });
                }

            }).catch(error => {
                this.$cookie.delete('token');
            });
        },
        getUserTG() {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$cookie.get('token');

            this.$root.graphql.getUser().then(res => {
                this.user = res.User;

                setTimeout(() => {
                    this.updateBalance(this.user.balance, this.user.balance);
                }, 100);

                if (this.user != null) {
                    this.$socket.emit('userSession', {
                        type: "Вошел",
                        id: this.user.id
                    });
                }
                
            }).catch(error => {
                //this.$cookie.delete('token');
            });
        },
        setDarkTheme() {
            if (this.darkTheme) {
                $("head link[rel='stylesheet']").last().after("<link id='darkTheme' rel='stylesheet' href='/css/charde.css?v=2' type='text/css'>");
            } else {
                $('#darkTheme').remove();
            }
        },
        linkVK() { 
            this.graphql.getLinkUserVK().then(res => {
                if (res.LinkUserVK.url) {
                    let width = 860;
                    let height = 500;
                    let left = (screen.width / 2) - (width / 2);
                    let top = (screen.height / 2) - (height / 2);
                    let windowOptions = `menubar=no,location=no,resizable=no,scrollbars=no,status=no, width=${width}, height=${height}, top=${top}, left=${left}`;
                    let type = 'auth';

                    setTimeout(() => {
                        window.open(res.LinkUserVK.url, type, windowOptions);
                    })

                    window.addEventListener("message", this.initToken, false);
                }
            })
        },
        IphoneGovno() {
                this.$root.graphql.getLinkUserVK();
                let width = 860;
                let height = 500;
                let left = (screen.width / 2) - (width / 2);
                let top = (screen.height / 2) - (height / 2);
                let windowOptions = `menubar=no,location=no,resizable=no,scrollbars=no,status=no, width=${width}, height=${height}, top=${top}, left=${left}`;
                let type = 'auth';
                
                window.open("http://oauth.vk.com/authorize?client_id=8150300&redirect_uri=https://demoney.bid/auth/vk/handle&response_type=code", type, windowOptions);
                window.addEventListener("message", this.initToken, false);
        },
        initToken(event) {
            if (event.data.length > 0) {
                const token = event.data.slice(7);

                if (token !== 'null') {
                    this.getUser();
                }
            }
        },
        updateStats1(newUsers, oldUsers) {
            const el = document.getElementById('statsAllUsers');
            const od = new Odometer({el: el, value: oldUsers});
            od.update(newUsers);
        },
        updateStats2(newGames, oldGames) {
            const el = document.getElementById('statsAllGames');
            const od = new Odometer({el: el, value: oldGames});
            od.update(newGames);
        },
        updateStats3(newToday, oldToday) {
            const el = document.getElementById('statsTodayWin');
            const od = new Odometer({el: el, value: oldToday});
            od.update(newToday);
        },
        updateBalance(newBalance, oldBalance) {
            const el = document.getElementById('userBalance');
            const od = new Odometer({el: el, value: oldBalance});
            od.update(newBalance);

            const elMobile = document.getElementById('userBalanceMobile');
            const odMobile = new Odometer({el: elMobile, value: oldBalance});
            odMobile.update(newBalance);
        }
    },
    router,
    components: {
        Layout
    }
});
