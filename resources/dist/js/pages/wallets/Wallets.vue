<template>
    <div style="margin-bottom:100px">
        <div class="card">
            <div class="card-header pd-t-20 pd-b-0 bd-b-0">
                <h4 class="mg-b-5 ">Реквизиты для вывода</h4>
                <p class="tx-13 mg-b-0 tx-light">Введите реквизиты для вывода средств</p>
            </div>
            <div class="card-body pd-20 bd-b pd-b-20">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-xs-12 col-lg-6">
                                <div class="form-group">
                                    <label for="inputAddress" class="tx-normal typeahead la-mob"><span id="nameWt">Кошелек Qiwi:</span>
                                        <!-- <span class="tx-danger">*</span> --></label>
					<div class="row">
                                    <input type="text" autocomplete="false_disabled_hack@#!@#$" class="form-control tx-16 tx-normal col-6" id="walletNumber"
                                           v-model="walletQiwi" placeholder="" @input="validateQiwi">
                                    
						<button id="btnwt" @click="saveQiwi()" class="col-6 btn btn-primary tx-normal btn-la-mob"
                                style="border-color: #2576ea;background: linear-gradient(45deg, #1c65c9 0%, #2c80ff 100%);">
                            Сохранить
                        </button> </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputAddress" class="tx-normal typeahead la-mob"><span id="nameWt">Номер карты:</span>
                                        <!-- <span class="tx-danger">*</span> --></label>
					<div class="row"> 
                                    <input type="text" autocomplete="false_disabled_hack@#!@#$" class="form-control tx-16 tx-normal col-6" id="walletNumber1"
                                           v-model="walletCard" placeholder="">
                                    
					<button id="btnwt" @click="saveCard()" class="col-6 btn btn-primary tx-normal btn-la-mob"
                                style="border-color: #2576ea;background: linear-gradient(45deg, #1c65c9 0%, #2c80ff 100%);">
                            Сохранить
                        </button>  </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputAddress" class="tx-normal typeahead la-mob"><span id="nameWt">FKWallet кошелек:</span>
                                        <!-- <span class="tx-danger">*</span> --></label>
					<div class="row">
                                    <input type="text" autocomplete="false_disabled_hack@#!@#$" class="col-6 form-control tx-16 tx-normal" id="walletNumber2"
                                           v-model="walletFK" placeholder="">
                                    
				<button id="btnwt" @click="saveFK()" class="col-6 btn btn-primary tx-normal btn-la-mob"
                                style="border-color: #2576ea;background: linear-gradient(45deg, #1c65c9 0%, #2c80ff 100%);">
                            Сохранить
                        </button> </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label for="inputAddress" class="tx-normal typeahead la-mob"><span id="nameWt">ЮMoney кошелек:</span>
                                        </label>
					<div class="row">
                                    <input type="text" autocomplete="false_disabled_hack@#!@#$" class="col-6 form-control tx-16 tx-normal" id="walletNumber3"
                                           v-model="walletYoo" placeholder="">
                                    
				<button id="btnwt" @click="saveYoo()" class="col-6 btn btn-primary tx-normal btn-la-mob"
                                style="border-color: #2576ea;background: linear-gradient(45deg, #1c65c9 0%, #2c80ff 100%);">
                            Сохранить
                        </button> </div>
                                </div> -->
                                <div class="form-group">
                                    <label for="inputAddress" class="tx-normal typeahead la-mob"><span id="nameWt">Piastrix кошелек:</span>
                                        <!-- <span class="tx-danger">*</span> --></label>
					<div class="row">
                                    <input type="text" autocomplete="false_disabled_hack@#!@#$" class="col-6 form-control tx-16 tx-normal" id="walletNumber4"
                                           v-model="walletPias" placeholder="">
                                    
					<button id="btnwt" @click="savePias()" class="col-6 btn btn-primary tx-normal btn-la-mob"
                                style="border-color: #2576ea;background: linear-gradient(45deg, #1c65c9 0%, #2c80ff 100%);">
                            Сохранить
                        </button> </div>
                                </div>
                            </div>
                        </div>
                        <!-- <button id="btnwt" @click="withdraw" class="btn btn-primary tx-normal btn-la-mob"
                                style="border-color: #2576ea;background: linear-gradient(45deg, #1c65c9 0%, #2c80ff 100%);">
                            Создать
                        </button> -->
                        
                        <button id="error_withdraw" v-if="errors.show"
                                style="margin-left: 7px; padding: 8px 22px; pointer-events: none; color: #fff !important;"
                                class="btn tx-medium btn-la-mob bg-danger-dice tx-white bd-0 btn-sel-d ">
                            {{ errors.message }}
                        </button>
                        <button id="succes_promo" style="padding: 9px 26px; pointer-events: none;" v-if="success.show" class="btn tx-medium btn-la-mob bg-success-dice tx-white bd-0 btn-sel-d">{{ success.message }}</button>
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
                loader: false,
                walletQiwi: null,
                walletFK: null,
                walletYoo: null,
                walletCard: null,
                user: '',
                walletPias: null,
                errors: {
                    show: false,
                    message: ''
                },
                success: {
                    show: false,
                    message: ''
                },
            }
        },
        mounted() {
            //this.$root.isLoading = true;

            if (!this.$cookie.get('token')) {
                this.$root.isLoading = false;
                this.$router.go(-1);
            }
            this.getUser();

            //console.log(this.user.id);
            /*if (this.$root.user.wallet_qiwi != null) {this.walletQiwi = this.$root.user.wallet_qiwi;}
            if (this.$root.user.wallet_fk != null) {this.walletFK = this.$root.user.wallet_fk;}
            if (this.$root.user.wallet_card != null) {this.walletCard = this.$root.user.wallet_card;}
            if (this.$root.user.wallet_yoomoney != null) {this.walletYoo = this.$root.user.wallet_yoomoney;}
            if (this.$root.user.wallet_piastrix != null) {this.walletPias = this.$root.user.wallet_piastrix;}*/

        },
        methods: {
            getUser() {
                this.$root.axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$cookie.get('token');

                this.$root.graphql.getUser().then(res => {
                    this.user = res.User;
                    this.walletQiwi = this.user.wallet_qiwi;
                    this.walletFK = this.user.wallet_fk;
                    this.walletCard = this.user.wallet_card;
                    this.walletYoo = this.user.wallet_yoomoney;
                    this.walletPias = this.user.wallet_piastrix;

                }).catch(error => {
                    this.$cookie.delete('token');
                });
            },
            validateQiwi() {
                    if (this.walletQiwi.indexOf('8') == 0) {
                        this.walletQiwi = this.walletQiwi.replace('8', '7')
                    }
                    if (this.walletQiwi.indexOf('+') == 0) {
                        this.walletQiwi = this.walletQiwi.replace('+', '')
                    }
            },
            save() {
                if (this.disableBtn) {
                    return;
                }

                if (this.walletQiwi != null) {this.walletQiwi = this.walletQiwi.replace(/ /g,'');}                
                if (this.walletCard != null) {this.walletCard = this.walletCard.replace(/ /g,'');}
                if (this.walletFK != null) {this.walletFK = this.walletFK.replace(/ /g,'');}
                if (this.walletYoo != null) {this.walletYoo = this.walletYoo.replace(/ /g,'');}
                if (this.walletPias != null) {this.walletPias = this.walletPias.replace(/ /g,'');}

                this.errors.show = false;
                this.disableBtn = true;

                this.$root.axios.post('/wallets/save', {
                    walletQiwi: this.walletQiwi,
                    walletCard: this.walletCard,
                    walletFK: this.walletFK,
                    walletYoo: this.walletYoo,
                    walletPias: this.walletPias,
                    id: this.$root.user.id
                }).then(res => {
                    const data = res.data;

                    if (data.success) {
                        this.success.show = true;
                        this.success.message = data.message;

                        this.errors.show = false;

                        this.disableBtn = false;
                    } else {
                        this.errors = {
                            show: true,
                            message: data.message
                        };

                        this.disableBtn = false;
                    }
                });
            },
            saveQiwi() {
                if (this.disableBtn) {
                    return;
                }

                if (this.walletQiwi.indexOf('8') == 0) {
                    this.walletQiwi = this.walletQiwi.replace('8', '7')
                }
                if (this.walletQiwi.indexOf('+') >= 0) {
                    this.walletQiwi = this.walletQiwi.replace('+', '')
                }

                if (this.walletQiwi.indexOf('+') >= 0) {
                    this.errors = {
                        show: true,
                        message: 'Без "+"'
                    };
                }

        		if ( this.user.wallet_qiwi != null) {
        			this.errors = {
                        show: true,
                        message: 'Смена реквизитов через техподдержку'
                    };
        		} 

                if (this.walletQiwi != null) {this.walletQiwi = this.walletQiwi.replace(/ /g,'');}                
                
                this.errors.show = false;
                this.disableBtn = true;

                this.$root.axios.post('/wallets/save', {
                    walletQiwi: this.walletQiwi,
                    walletCard: null,
                    walletFK: null,
                    walletYoo: null,
                    walletPias: null,
                    id: this.$root.user.id
                }).then(res => {
                    const data = res.data;

                    if (data.success) {
                        this.success.show = true;
                        this.success.message = data.message;

                        this.errors.show = false;

                        this.disableBtn = false;
			this.getUser();
                    } else {
                        this.errors = {
                            show: true,
                            message: data.message
                        };

                        this.disableBtn = false;
                    }
                });
            },
saveCard() {
                if (this.disableBtn) {
                    return;
                }

		//if ( this.user.wallet_card != null) {
		//	this.errors = {
                   //         show: true,
                  //          message: 'Смена реквизитов через техподдержку'
                  //      };
		//} 
                
                if (this.walletCard != null) {this.walletCard = this.walletCard.replace(/ /g,'');}
                

                this.errors.show = false;
                this.disableBtn = true;

                this.$root.axios.post('/wallets/save', {
                    walletQiwi: null,
                    walletCard: this.walletCard,
                    walletFK: null,
                    walletYoo: null,
                    walletPias: null,
                    id: this.$root.user.id
                }).then(res => {
                    const data = res.data;

                    if (data.success) {
                        this.success.show = true;
                        this.success.message = data.message;

                        this.errors.show = false;

                        this.disableBtn = false;
			this.getUser();
                    } else {
                        this.errors = {
                            show: true,
                            message: data.message
                        };

                        this.disableBtn = false;
                    }
                });
            },
saveFK() {
                if (this.disableBtn) {
                    return;
                }
                
                if (this.walletFK != null) {this.walletFK = this.walletFK.replace(/ /g,'');}
                
                this.errors.show = false;
                this.disableBtn = true;

                this.$root.axios.post('/wallets/save', {
                    walletQiwi: null,
                    walletCard: null,
                    walletFK: this.walletFK,
                    walletYoo: null,
                    walletPias: null,
                    id: this.$root.user.id
                }).then(res => {
                    const data = res.data;

                    if (data.success) {
                        this.success.show = true;
                        this.success.message = data.message;

                        this.errors.show = false;

                        this.disableBtn = false;
                    } else {
                        this.errors = {
                            show: true,
                            message: data.message
                        };

                        this.disableBtn = false;
			this.getUser();
                    }
                });
            },
saveYoo() {
                if (this.disableBtn) {
                    return;
                }

                if (this.walletYoo != null) {this.walletYoo = this.walletYoo.replace(/ /g,'');}

                this.errors.show = false;
                this.disableBtn = true;

                this.$root.axios.post('/wallets/save', {
                    walletQiwi: null,
                    walletCard: null,
                    walletFK: null,
                    walletYoo: this.walletYoo,
                    walletPias: null,
                    id: this.$root.user.id
                }).then(res => {
                    const data = res.data;

                    if (data.success) {
                        this.success.show = true;
                        this.success.message = data.message;

                        this.errors.show = false;

                        this.disableBtn = false;
			this.getUser();
                    } else {
                        this.errors = {
                            show: true,
                            message: data.message
                        };

                        this.disableBtn = false;
                    }
                });
            },
savePias() {
                if (this.disableBtn) {
                    return;
                }

                if (this.walletPias != null) {this.walletPias = this.walletPias.replace(/ /g,'');}

                this.errors.show = false;
                this.disableBtn = true;

                this.$root.axios.post('/wallets/save', {
                    walletQiwi: null,
                    walletCard: null,
                    walletFK: null,
                    walletYoo: null,
                    walletPias: this.walletPias,
                    id: this.$root.user.id
                }).then(res => {
                    const data = res.data;

                    if (data.success) {
                        this.success.show = true;
                        this.success.message = data.message;

                        this.errors.show = false;

                        this.disableBtn = false;
			this.getUser();
                    } else {
                        this.errors = {
                            show: true,
                            message: data.message
                        };

                        this.disableBtn = false;
                    }
                });
            },
        }
    }
</script>

<style type="text/css">
    @media (max-width: 768px) {
        #vmm {
            transform: scale(0.6) !important;
        }
        #mob-badge {
            z-index: 1;
            top: 0px;
            right: 3px;
            padding: 1px 1px;
            background-color: rgba(58,61,82);
        }
       #btnwt {width: 100% !important;} 
    }
</style>
