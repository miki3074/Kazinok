<template>
    <div class="modal fade" id="modalSignIn" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered wd-sm-400" role="document">
            <div class="modal-content">
                <div class="modal-body pd-20 pd-sm-40">
                    <a href="" role="button" class="close pos-absolute t-15 r-15" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </a>
                    <div>
                        <h4>Авторизация</h4>
                        <p class="tx-color-03 tx-thin"></p>
                        <!-- <vue-recaptcha sitekey="6Ldj8XQfAAAAAAQPKOb-nR7eJ6y_smD4HwO8sNKW"> -->
                            <button @click="IphoneGovno" href="#" class="btn btn-outline-facebook btn-block mg-b-15"><i class="ion ion-logo-vk"></i> Войти через Вконтакте</button>
                        <!-- </vue-recaptcha>
                        <vue-recaptcha sitekey="6Ldj8XQfAAAAAAQPKOb-nR7eJ6y_smD4HwO8sNKW"> -->
                            <button @click="TelegramAuth" href="#" class="btn btn-outline-facebook btn-block mg-b-15 tg-button"><svg class="tg-logo" fill="#4064ac" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path d="M248,8C111.033,8,0,119.033,0,256S111.033,504,248,504,496,392.967,496,256,384.967,8,248,8ZM362.952,176.66c-3.732,39.215-19.881,134.378-28.1,178.3-3.476,18.584-10.322,24.816-16.948,25.425-14.4,1.326-25.338-9.517-39.287-18.661-21.827-14.308-34.158-23.215-55.346-37.177-24.485-16.135-8.612-25,5.342-39.5,3.652-3.793,67.107-61.51,68.335-66.746.153-.655.3-3.1-1.154-4.384s-3.59-.849-5.135-.5q-3.283.746-104.608,69.142-14.845,10.194-26.894,9.934c-8.855-.191-25.888-5.006-38.551-9.123-15.531-5.048-27.875-7.717-26.8-16.291q.84-6.7,18.45-13.7,108.446-47.248,144.628-62.3c68.872-28.647,83.183-33.623,92.511-33.789,2.052-.034,6.639.474,9.61,2.885a10.452,10.452,0,0,1,3.53,6.716A43.765,43.765,0,0,1,362.952,176.66Z"/></svg> Войти через Телеграм</button>
                        <!-- </vue-recaptcha> -->
                        <div class="form-group">
                            <label>Логин</label>
                            <input v-model="username" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between mg-b-5">
                                <label class="mg-b-0-f">Пароль</label>
                            </div>
                            <input v-model="password" type="password" class="form-control" placeholder="">
                        </div>
<!--                        <div style="transform: scale(0.75);margin-top: -17px;" class="g-recaptcha justify-content-center align-self-center" data-sitekey="6LeelqAUAAAAANC5GR_WWHaMeDH45EPA6gTZ1WAk"><div style="width: 304px; height: 78px;"><div><iframe src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6LeelqAUAAAAANC5GR_WWHaMeDH45EPA6gTZ1WAk&amp;co=aHR0cHM6Ly9jYWJ1cmEuYmFyOjQ0Mw..&amp;hl=ru&amp;v=wk6lx42JIeYmEAQSHndnyT8Q&amp;size=normal&amp;cb=v90c45qdstqn" width="304" height="78" role="presentation" name="a-hjbnt39p2230" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox"></iframe></div><textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea></div></div>-->
                        <div v-if="errors.show" id="error_register" class="alert alert-danger tx-center tx-red" role="alert" style="color: rgb(219, 52, 69);padding: 9px;">
                            {{ errors.message }}
                        </div>
                        <button id="butEnter" class="btn btn-primary btn-block" @click="login" :disabled="disableBtn">
                            <span v-if="!disableBtn">Войти</span>
                            <div v-else class="spinner-border spinner-border-sm tx-white link-03 tx-medium tx-spacing--0 tx-10"></div>
                        </button>
                        <div class="tx-13 mg-t-20 tx-center">Нет аккаунта? <a data-dismiss="modal" aria-label="Close" href="#modalSignUp" data-toggle="modal">Создать аккаунт</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style type="text/css">
    .tg-button:hover > .tg-logo {
        fill: #fff;
    }
</style>

<script>
    //import {vueTelegramLogin} from 'vue-telegram-login'
    export default {
        //components: {vueTelegramLogin},
        data() {
            return {
                username: '',
                password: '',
                errors: {
                    show: false,
                    message: ''
                },
                disableBtn: false,
                vktest: '',
            }
        },
        methods: {
            TelegramAuth() {
                this.$root.axios.get('rnd').then((response) => {
                    const token = response.data;

                    this.$cookie.set('token', token);
                    this.$root.axios.post('session', {token: token});

                    setTimeout(() => {
                        window.open("https://t.me/demoney_win_bot?start=" + token, '_blank');
                    }) //iphone govno

                    //window.open("tg://resolve?domain=demoney_one_bot&start="+ token, '_blank'); //переход на бота
                    //window.open("https://t.me/demoney_one_bot?start=" + token, '_blank'); //переход на бота
                   
                        var timerId = setInterval(function () {
                            this.$root.getUserTG();
                            if (this.$root.user === null) {

                            } else {
                                clearInterval(timerId);
                            }
                            $('#modalSignUp').modal('hide');
                            $('#modalSignIn').modal('hide');

                        }.bind(this), 3000);                 
                })
            },
            login() {
                if (!this.username) {
                    return this.errors = {
                        show: true,
                        message: 'Введите имя пользователя'
                    }
                }

                if (!this.password) {
                    return this.errors = {
                        show: true,
                        message: 'Введите пароль'
                    }
                }

                this.disableBtn = true;

                this.$root.graphql.getLoginUser({
                    username: this.username,
                    password: this.password
                }).then(res => {
                    const token = res.LoginUser.token;

                    if (token) {
                        this.$cookie.set('token', token, { expires: "168h" });
                        this.$root.getUser();
                    }

                    this.disableBtn = false;
                    $('#modalSignIn').modal('hide');
                }).catch(error => {
                    error = this.$root.graphql.getError(error);

                    let errorText = '';

                    if (error.message === 'validation') {
                        for (const type in error.extensions.validation) {
                            errorText = error.extensions.validation[type][0];
                            break;
                        }
                    } else {
                        errorText = error.debugMessage;
                    }

                    this.disableBtn = false;

                    this.errors = {
                        show: true,
                        message: errorText
                    }
                });
            },
            signInVK() {               

                this.$root.graphql.getCreateUserVK().then(res => {
                    if (res.CreateUserVK.url) {
                        this.vktest = res.CreateUserVK.url;
                        //let width = 860;
                        //let height = 500;
                        //let left = (screen.width / 2) - (width / 2);
                        //let top = (screen.height / 2) - (height / 2);
                        //let windowOptions = `menubar=no,location=no,resizable=no,scrollbars=no,status=no, width=${width}, height=${height}, top=${top}, left=${left}`;
                        //let type = 'auth';

                        //window.open(res.CreateUserVK.url, type, windowOptions);

                        //window.addEventListener("message", this.initToken, false);
                        this.IphoneGovno();
                    }
                })
                //window.open(this.vktest, type, windowOptions);
                //window.addEventListener("message", this.initToken, false);
            },
            onTelegramAuth(user){
                this.$root.axios.post('auth/tg/handle', user).then((response) => {
                    const token = response.data.token;

                    if (token !== 'null') {
                        this.$cookie.set('token', token, { expires: "168h" });
                        this.$root.getUser();
                        $('#modalSignIn').modal('hide');
                        $('#userSettings').modal('show');
                    } else {
                        return this.errors = {
                            show: true,
                            message: 'Аккаунт не привязан'
                        }
                    }
                }).catch(error => {

                    console.log(error);

                    let errorText = 'Произошла ошибка, повторите запрос позже';

                    this.errors = {
                        show: true,
                        message: errorText
                    }
                });
            },
            IphoneGovno() {                
                let width = 860;
                let height = 500;
                let left = (screen.width / 2) - (width / 2);
                let top = (screen.height / 2) - (height / 2);
                let windowOptions = `menubar=no,location=no,resizable=no,scrollbars=no,status=no, width=${width}, height=${height}, top=${top}, left=${left}`;
                let type = 'auth';
                
                window.open("http://oauth.vk.com/authorize?client_id=8150300&redirect_uri=https://demoney.bid/auth/vk/handle&response_type=code", type, windowOptions);
                //window.addEventListener("message", this.initToken, false);
                //window.open(this.vktest, type, windowOptions);
                window.addEventListener("message", this.initToken, false);
            },
            initToken(event) {
                if (event.data.length > 0) {
                    const token = event.data.slice(7);

                    if (token !== 'null') {
                        if (this.$root.user === null) {
                            this.$cookie.set('token', token, { expires: "168h" });
                            this.$root.getUser();
                            $('#modalSignIn').modal('hide');
                            //window.removeEventListener("message", this.initToken, false);
                        }
                    } else {
                        return this.errors = {
                            show: true,
                            message: 'Аккаунт не привязан'
                        }
                    }
                }
            }
        }
    }
</script>