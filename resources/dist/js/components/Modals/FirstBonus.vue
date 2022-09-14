<template>
    <div>
        <div class="modal fade" id="firstBonus" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered wd-sm-400" role="document">
                <div class="modal-content">
                    <div class="text-center" v-show="isLoading">
                        <div class="spinner-border"></div>
                    </div>
                    <div class="modal-body pd-20 pd-sm-40" v-if="!isLoading">
                        <a href="" role="button" class="close pos-absolute t-15 r-15" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </a>
                        <div id="wizard1" role="application" class="wizard clearfix">
                            <div class="steps clearfix align-items-center justify-content-center">
                                <ul role="tablist">
                                    <li role="tab" class="first" :class="[$root.user.is_vk !== false ? 'done' : 'current']" aria-disabled="false" aria-selected="true">
                                        <a id="wizard1-t-0" href="#wizard1-h-0" aria-controls="wizard1-p-0">
                                            <span class="current-info audible">current step: </span>
                                            <span class="number">1</span>
                                        </a>
                                    </li>
                                    <li role="tab" class="" :class="[!$root.user.is_vk ? 'disabled' : 'current']" aria-disabled="true">
                                        <a id="wizard1-t-1" href="#wizard1-h-1" aria-controls="wizard1-p-1">
                                            <span class="number">2</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="content clearfix" style="min-height:0!important" v-if="!$root.user.is_vk">
                                <h3 id="wizard1-h-0" tabindex="-1" class="title current tx-center">Привяжите аккаунт Вконтакте</h3>
                            </div>
                            <div class="content clearfix" style="min-height:0!important" v-else>
                                <h3 id="wizard1-h-0" tabindex="-1" class="title current tx-center ">Подпишитесь на <a :href="groupLink" target="_blank" style="text-decoration: underline;">нашу группу</a></h3>
                                <center class="tx-danger" v-if="errors.show">{{ errors.message }}</center>
                            </div>
                            <div class="actions" v-if="!$root.user.is_vk">
                                <a @click="$root.linkVK" class="tx-center" style="cursor: pointer"><i class="ion ion-logo-vk"></i> Привязать</a>
                            </div>
                            <div class="actions" v-else>
                                <a href="#" id="bonBut" @click="getBonus" class="tx-center">Получить</a>
                            </div>
                        </div>
                        <center id="xrqexr" style="font-size:11px;margin-top: 15px;opacity:0.5;cursor:pointer;" @click="hideBonus">Больше не показывать предложение</center>
                    </div>
                    <!-- modal-body -->
                </div>
                <!-- modal-content -->
            </div>
            <!-- modal-dialog -->
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                isLoading: true,
                groupLink: '',
                errors: {
                    show: false,
                    message: ''
                }
            }
        },
        methods: {
            getInfoVK() {
                this.$root.axios.post('/getGroupVK')
                .then(res => {
                    this.groupLink = res.data;
                    this.isLoading = false;
                })
            },
            hideBonus() {
                this.$root.axios.post('/promo/vk/off')
                .then(res => {
                    const data = res.data;

                    if(data.success) {
                        window.location.reload();
                    } else {
                        this.errors = {
                            show: true,
                            message: data.message
                        };
                    }

                })
            },
            getBonus() {
                this.$root.axios.post('/promo/vk')
                .then(res => {
                    const data = res.data;

                    if(data.success) {
                        this.$root.user.balance += data.sum;
                        this.errors = {
                            show: true,
                            message: ''
                        };
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        this.errors = {
                            show: true,
                            message: data.message
                        };
                    }

                })
            }
        },
        mounted() {
            this.getInfoVK()
        }
    }
</script>
