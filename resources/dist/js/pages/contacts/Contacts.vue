<template>
    <div>
        <div class="card mg-b-500">
            <div class="card-header bd-b-0">
                <h4 class="mg-b-5 tx-normal">Служба поддержки</h4>
            </div>
            <div class="pos-relative ht-600 " id="scrollChat">
                <div class="chat-group" style="justify-content: flex-start;">
                    <div v-for="(group, key) in messages">
                        <div class="chat-group-divider">{{ key }}</div>
                        <div class="media" v-for="mes in group.messages">
                            <div class="avatar avatar-sm "><span class="avatar-initial bg-dark rounded-circle">{{ mes.username.substr(0, 2) }}</span>
                            </div>
                            <div class="media-body">
                                <h6><span :style="[mes.is_admin ? {'color': '#FF0000'} : {}]">{{ mes.username }}</span> <small>в {{ mes.time }}</small></h6>
                                <p>{{ mes.message }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ps__rail-x" style="left: 0px; top: 0px;">
                    <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                </div>
                <div class="ps__rail-y" style="top: 0px; height: 598px; right: 0px;">
                    <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 596px;"></div>
                </div>
            </div>
            <div class="align-self-center tx-danger" id="errorMesSupport" v-if="errors.show">
                {{ errors.message }}
            </div>
            <div class="chat-content-footer mg-t-20" style="position:relative!important">
                <input id="mesSupport" type="text" class="form-control align-self-center" v-on:keyup.enter="sendMessage"
                       style="padding: 20px !important;" v-model="message" placeholder="Введите сообщение"
                       autocomplete="off">
                <nav class="tx-gray-600" style="cursor:pointer" @click="sendMessage">
                    Отправить
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                message: '',
                messages: [],
                errors: {
                    show: false,
                    message: ''
                },
                scroll: null,
                sendingMessage: false
            }
        },
        mounted() {
            this.$root.isLoading = true;

            if (!this.$cookie.get('token')) {
                this.$root.isLoading = false;
                this.$router.go(-1);
            }

            this.getMessages();

            this.scroll = new PerfectScrollbar('#scrollChat', {
                suppressScrollX: true
            });
        },
        methods: {
            sendMessage() {
                if (this.sendingMessage) {
                    return;
                }

                this.errors.show = false;
                this.sendingMessage = true;

                this.$root.axios.post('/support/sendMessage', {
                    message: this.message
                }).then(res => {
                    this.message = '';
                    this.messages = res.data;
                    this.sendingMessage = false;

                    this.updateScroll();
                }).catch(error => {
                    error = error.response.data;

                    this.errors = {
                        show: true,
                        message: error.errors.message[0]
                    }

                    this.sendingMessage = false;
                });
            },
            getMessages() {
                this.$root.axios.post('/support/getMessages').then(res => {
                    this.messages = res.data;

                    this.updateScroll();

                    this.$root.isLoading = false;
                })
            },
            updateScroll() {
                setTimeout(() => {
                    $("#scrollChat").scrollTop(999999999);

                    this.scroll.update();
                }, 100);
            }
        }
    }
</script>
