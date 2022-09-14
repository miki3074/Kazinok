<template>
    <div>
        <div class="modal fade" id="userSettings" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered " role="document">
                <div class="modal-content">
                    <div class="modal-body pd-x-25 pd-sm-x-30 pd-t-40 pd-sm-t-20 pd-b-15 pd-sm-b-20">
                        <a href="" role="button" class="close pos-absolute t-15 r-15" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </a>
                        <div class="nav-wrapper mg-b-20 tx-13">
                            <div>
                                <nav class="nav nav-line tx-medium">
                                    <a href="#performance" class="nav-link active" data-toggle="tab">Настройки</a>
                                </nav>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div id="performance" class="tab-pane fade active show">
                                <h6>Темная тема</h6>
                                <div class="row row-xs mg-t-15">
                                    <div class="col-lg-4">
                                        <div class="d-md-flex">
                                            <div id="fnWrapper" class="parsley-input">
                                                <div class="custom-control custom-switch">
                                                    <input autocomplete="off" type="checkbox" class="custom-control-input" id="customSwitch1" v-model="$root.darkTheme" v-on:change="changeTheme"><label class="custom-control-label" for="customSwitch1" style="color:transparent">A</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <hr>
                                <h6>Смена ника</h6>
                                <div class="row row-xs mg-t-15">
                                    <div class="col-lg-4">
                                        <div class="d-md-flex ">
                                            <div id="fnWrapper" class="parsley-input">
                                                <input type="text" v-model="username" name="username" class="form-control " placeholder="Новый ник" autocomplete="off" data-parsley-class-handler="#fnWrapper" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <button @click="editUser" class="btn btn-block btn-primary tx-normal rpbss" :disabled="disableBtn1">Сменить</button>
                                    </div>
                                    <span id="error_resetPass" class="tx-danger mg-t-15" v-if="errors1.show">
                                        {{ errors1.message }}
                                    </span>
                                    <span id="succes_resetPass" class="tx-success mg-t-15" v-if="successChangeNick">Ник изменен</span>
                                </div>
                                <h6 style="margin-top: 15px;" v-if="( this.$root.user.vk_only == 0 && this.$root.user.is_tg == 1 ) || ( this.$root.user.tg_only == 0 && this.$root.user.is_vk == 1 )">Смена пароля</h6>
                                <div class="row row-xs mg-t-15" v-if="( this.$root.user.vk_only == 0 && this.$root.user.is_tg == 1 ) || ( this.$root.user.tg_only == 0 && this.$root.user.is_vk == 1 )">
                                    <div class="col-lg-4">
                                        <div class="d-md-flex ">
                                            <div id="fnWrapper" class="parsley-input">
                                                <input type="password" v-model="oldPassword" name="firstname" class="form-control " placeholder="Старый пароль" autocomplete="off" data-parsley-class-handler="#fnWrapper" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="d-md-flex ">
                                            <div id="lnWrapper" class="parsley-input ">
                                                <input type="password" v-model="newPassword" name="lastname" class="form-control " placeholder="Новый пароль" autocomplete="off" data-parsley-class-handler="#lnWrapper" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <button @click="resetPassword" class="btn btn-block btn-primary tx-normal rpbss" :disabled="disableBtn">Сохранить</button>
                                    </div>
                                    <span id="error_resetPass" class="tx-danger mg-t-15" v-if="errors.show">
                                        {{ errors.message }}
                                    </span>
                                    <span id="succes_resetPass" class="tx-success mg-t-15" v-if="successChangePassword">Пароль изменен</span>
                                </div>
                                <h6 style="margin-top: 15px;" v-if="this.$root.user.vk_only == 1 || this.$root.user.tg_only == 1">Установить пароль</h6>
                                <div class="row row-xs mg-t-15" v-if="this.$root.user.vk_only == 1 || this.$root.user.tg_only == 1">
                                    <div class="col-lg-4">
                                        <div class="d-md-flex ">
                                            <div id="fnWrapper" class="parsley-input">
                                                <input type="password" v-model="setPass" name="firstname" class="form-control " placeholder="Новый пароль" autocomplete="off" data-parsley-class-handler="#fnWrapper" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <button @click="setPassword" class="btn btn-block btn-primary tx-normal rpbss" :disabled="disableBtn2">Сохранить</button>
                                    </div>
                                    <span id="error_resetPass" class="tx-danger mg-t-15" v-if="errors2.show">
                                        {{ errors2.message }}
                                    </span>
                                    <span id="succes_resetPass" class="tx-success mg-t-15" v-if="successSetPass">Пароль установлен</span>
                                </div>
                                <hr>
                                <h6>Аккаунт Вконтакте</h6>
                                <div class="row row-xs mg-t-15">
                                    <div class="col-lg-4">
                                        <div class="d-md-flex">
                                            <div id="fnWrapper" class="parsley-input">
                                                <button @click="$root.IphoneGovno" class="btn btn-outline-primary btn-block tx-normal" style="color: #0168fa;text-decoration: none;background-color: transparent;cursor: pointer;" v-if="!$root.user.is_vk">Привязать</button>
                                                <a :href="'https://vk.com/id' + $root.user.vk_id" target="_blank" v-if="$root.user.is_vk">{{ $root.user.vk_username }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h6>Аккаунт Телеграм</h6>
                                <div class="row row-xs mg-t-15">
                                    <div class="col-lg-4">
                                        <div class="d-md-flex">
                                            <div id="fnWrapper" class="parsley-input">
                                                <button @click="TelegramLink" class="btn btn-outline-primary btn-block tx-normal" style="color: #0168fa;text-decoration: none;background-color: transparent;cursor: pointer;" v-if="!$root.user.is_tg">Привязать</button>
                                                <a :href="'https://t.me/' + $root.user.tg_username" target="_blank" v-if="$root.user.is_tg">{{ $root.user.tg_username }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row row-xs mg-t-15">
                                    <div class="col-lg-12">
                                        <div class="d-md-flex">
                                            <button type="submit" class="btn btn-outline-primary btn-block tx-normal" @click="logout">Выйти из аккаунта</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                oldPassword: '',
                newPassword: '',
                username: '',
                setPass: '',
                errors: {
                    show: false,
                    message: ''
                },
                errors1: {
                    show: false,
                    message: ''
                },
                errors2: {
                    show: false,
                    message: ''
                },
                disableBtn: false,
                successChangePassword: false,
                disableBtn1: false,
                successChangeNick: false,
                disableBtn2: false,
                successSetPass: false
            }
        },
        methods: {
            TelegramLink() {
                this.$root.graphql.getLinkUserVK();
                this.$root.axios.get('rnd').then((response) => {
                    const token = response.data;
                    this.$cookie.set('token', token, { expires: "10y" });
                    this.$root.axios.post('session', {token: token});

                    setTimeout(() => {
                        window.open("https://t.me/demoney_win_bot?start=" + token, '_blank');
                    })

                    //window.open("tg://resolve?domain=t.me/demoney_win_bot&start="+ token, '_blank');
                    //window.open("https://t.me/demoney_one_bot?start=" + token, '_blank'); //переход на бота

                    var timerId = setInterval(function () {
                        this.$root.getUser();
                        if (this.$root.user === null) {

                        } else {
                            clearInterval(timerId);
                        }
                        $('#userSettings').modal('hide');

                    }.bind(this), 3000);
                })
            },
            changeTheme() {
                if (this.$root.darkTheme) {
                    this.$cookie.set('darkTheme', 1);
                } else {
                    this.$cookie.set('darkTheme', 0);
                }

                this.$root.setDarkTheme();
            },
            logout() {
                this.$root.user = null;
                this.$cookie.delete('token');
                this.$router.push({ name: 'index' });
                $('#userSettings').modal('hide');
            },
            resetPassword() {
                if (!this.oldPassword) {
                   return this.errors = {
                       show: true,
                       message: 'Введите старый пароль'
                   }
                }

                if (!this.newPassword) {
                    return this.errors = {
                        show: true,
                        message: 'Введите новый пароль'
                    }
                }

                this.disableBtn = true;

                if (this.errors.show) {
                    this.errors.show = false;
                }

                this.$root.graphql.getResetPassword({
                    old_password: this.oldPassword,
                    new_password: this.newPassword
                }).then(res => {
                    if (res.ResetPassword.success) {
                        this.disableBtn = false;
                        this.successChangePassword = true;
                    }
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
            editUser() {
                if (!this.username) {
                    return this.errors1 = {
                        show: true,
                        message: 'Введите новый ник'
                    }
                }

                this.disableBtn1 = true;

                const data = new FormData()

                data.append('username', this.username)

                this.$root.axios.post('user/editUser', data).then((response) => {
                    if (data.success) {
                        this.disableBtn1 = false;
                        this.successChangeNick = true;
                        this.$root.user.username = this.username
                    } else {
                        this.errors1 = {
                            show: true,
                            message: data.message
                        }
                    }
                }).catch(error => {

                    console.log(error);

                    let errorText = 'Никнейм уже занят';

                    this.disableBtn1 = false;

                    this.errors1 = {
                        show: true,
                        message: errorText
                    }
                });
            },
            setPassword() {
                if (!this.setPass) {
                    return this.errors2 = {
                        show: true,
                        message: 'Введите новый пароль'
                    }
                }

                this.disableBtn2 = true;

                const data = new FormData()

                data.append('password', this.setPass)

                this.$root.axios.post('user/setPass', data).then((response) => {
                    this.disableBtn2 = false;
                    this.successSetPass = true;
                    this.$root.user.vk_only = 0
                })
            }
        }
    }
</script>
