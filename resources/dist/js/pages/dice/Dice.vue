<template>
    <div>
        <div class="card">
            <div class="card-header pd-y-20 d-md-flex align-items-center justify-content-between d-none d-sm-block">
                <h4 class="mg-b-0">Dice</h4>
            </div>
            <div class="card-body " style="padding-bottom: 30px;">
                <div class="row ">
                    <div class="col-xs-12 col-lg-6 mg-b-20">
                        <div class="pd-dc">
                            <h5 style="    color: #38477c;" class="tx-normal tx-rubik tx-dark tx-60 tx-spacing--1 mg-b-0 d-flex justify-content-center d-md-none" id="diceResultMobile">{{ result }}</h5>
                            <p class="tx-13  tx-semibold tx-spacing-0 tx-color-03 d-flex justify-content-center tx-thin d-md-none">Возможный выигрыш</p>
                            <div class="row row-sm mg-t-10 ">
                                <div class="col-6 col-xs-6 col-md-6">
                                    <h6 style="    color: #38477c;" class="mg-b-15 h-mob-d">Cумма:</h6>
                                    <div class="input-group tx-light tx-24 dice-input">
                                        <input
                                        v-model="bet"
                                        id="diceGameAmount"
                                        style="border: 1px solid #d0d2dd;
    outline: none;
    font-family: rubikbold;
    font-size: 25px;
    color: #38477c;
    text-align: center;
    width: 204px;
    height: 60px;
    background: none;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;"
                                        @keyup="validateBet"
                                        class="tx-20 tx-center form-control tx-normal tx-rubik"
                                        placeholder="Сумма"
                                        autocomplete="off"
                                        >
                                    </div>
                                    <div style="margin-top: -1px;" class="btn-group tx-rubik d-flex justify-content-center">
                                        <button @click="typeBet('max')" style="border-top-left-radius: 0; padding: 0; color: #38477c;
    font-family: rubiklight;
  
    border: 1px solid #d0d2dd;
    background: none;
    display: flex;
    justify-content: center;
    align-items: center;
    border-top: 0;
    border-right: 0;
    cursor: pointer;
    transition: all .5s ease;
    -webkit-user-select: none;
    -ms-user-select: none;
    user-select: none;" class="tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines">Max</button>
                                        <button @click="typeBet('min')" style="border-top-right-radius: 0; padding: 0; color: #38477c;
    font-family: rubiklight;
   
    border: 1px solid #d0d2dd;
    background: none;
    display: flex;
    justify-content: center;
    align-items: center;
    border-top: 0;
    border-right: 0;
    cursor: pointer;
    transition: all .5s ease;
    -webkit-user-select: none;
    -ms-user-select: none;
    user-select: none;" class="tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines">Min</button>
                                        <button @click="typeBet('x2')" style="border-top-right-radius: 0; padding: 0; color: #38477c;
    font-family: rubiklight;
   
    border: 1px solid #d0d2dd;
    background: none;
    display: flex;
    justify-content: center;
    align-items: center;
    border-top: 0;
    border-right: 0;
    cursor: pointer;
    transition: all .5s ease;
    -webkit-user-select: none;
    -ms-user-select: none;
    user-select: none;" class="tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines">x2</button>
                                        <button @click="typeBet('/2')" style="border-top-right-radius: 0; padding: 4px 0; color: #38477c;
    font-family: rubiklight;
   
    border: 1px solid #d0d2dd;
    background: none;
    display: flex;
    justify-content: center;
    align-items: center;
    border-top: 0;
    border-right: 0;
    cursor: pointer;
    transition: all .5s ease;
    -webkit-user-select: none;
    -ms-user-select: none;
    user-select: none;" class="tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines">/2</button>
                                    </div>
                                </div>
                                <div class="col-6 col-xs-6  col-md-6">
                                    <h6 style="    color: #38477c;"  class="mg-b-15 h-mob-d">Шанс:</h6>
                                    <div class="input-group tx-thin tx-24 dice-input">
                                        <input
                                        v-model="chance"
                                        id="diceGamePercent"
                                        style="border-bottom-right-radius: 0;border-bottom-left-radius: 0;
                                        border: 1px solid #d0d2dd;
                                        background: none;
    outline: none;
    font-family: rubikbold;
    font-size: 25px;
    color: #38477c;
    text-align: center;
    width: 204px;
    height: 60px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    "
                                        @keyup="validateChance"
                                        class="tx-20 tx-center form-control tx-normal tx-rubik"
                                        placeholder="Шанс"
                                        autocomplete="off">
                                    </div>
                                    <div style="margin-top: -1px;" class="btn-group tx-rubik d-flex justify-content-center">
                                        <button @click="typeChance('max')" style="border-top-left-radius: 0; padding: 0;" class=" qwe tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines">Max</button>
                                        <button @click="typeChance('min')" style="border-top-right-radius: 0; padding: 0;" class="qwe tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines">Min</button>
                                        <button @click="typeChance('x2')" style="border-top-right-radius: 0; padding: 0;" class="qwe tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines ">x2</button>
                                        <button @click="typeChance('/2')" style="border-top-right-radius: 0; padding: 4px 0;" class="qwe tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines ">/2</button>
                                    </div>
                                </div>
                            </div>
                            <div class="divider-text hash-mob mg-t-20">Hash игры</div>
                            <div class="col-md-12  tx-xs-center tx-color-03 tx-thin d-none d-sm-block hash-mob" id="hashBet">
                                {{ game.hash }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 ">
                        <div class="col-lg-12 but-dice">
                            <h4 style="    color: #38477c;" class="tx-normal tx-rubik tx-dark tx-60 tx-spacing--1 mg-b-0 justify-content-center d-none d-sm-flex" id="diceResult">{{ result }}</h4>
                            <p class="tx-13  tx-semibold tx-spacing-0 tx-color-03 justify-content-center tx-thin d-none d-sm-flex" style="pointer-events: none">Возможный выигрыш</p>
                            <div class="row row-sm">
                                <div class="form-group col-6 col-md-6">
                                    <p class="mg-b-0 tx-color-03 tx-thin d-flex justify-content-center mb-2 h-mob-d" style="pointer-events: none">0 - <span id="minButton" class="pd-l-3">{{ min }}</span></p>
                                    <button @click="betDiceSocket('min')" id="buttonMin" :disabled="disableBtn" style="padding: 11px; background: #dce0ed; color: #38477c; width: 100%; height: 55px; margin-top: 12px; border: none;" class="btn btn-secondary btn-block tx-thin btn-la-mob btn-sel-d" @keypress.enter.prevent>Меньше</button>
                                </div>
                                <div class="form-group col-6 col-md-6 ">
                                    <p class="mg-b-0 tx-color-03 tx-thin d-flex justify-content-center mb-2 h-mob-d" style="pointer-events: none"><span id="maxButton" class="pd-r-3">{{ max }}</span> - 999999</p>
                                    <button @click="betDiceSocket('max')" id="buttonMax" :disabled="disableBtn" style="padding: 11px; background: #dce0ed; color: #38477c; width: 100%; height: 55px; margin-top: 12px; border: none;" class="btn btn-secondary btn-block tx-thin btn-la-mob btn-sel-d" @keypress.enter.prevent>Больше</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12 but-dice ht-30">
                            <center><div class="spinner-border" v-if="disableBtn && !success.show && !errors.show" id="betLoad" role="status" style="color: #7a86a1;
    border: .1em solid currentColor;
    border-right-color: transparente">
                                <span class="sr-only">Loading...</span>
                            </div></center>
                            <button id="succes_bet" v-if="success.show" style="padding: 11px;pointer-events: none;margin-top: 0px;" class="btn btn-block tx-medium btn-la-mob bg-success-dice tx-white bd-0 btn-sel-d ">
                                {{ success.message }}
                            </button>
                            <button id="error_bet" v-if="errors.show" style="padding: 11px; pointer-events:none;margin-top:0" class="btn btn-block tx-medium btn-la-mob bg-danger-dice tx-white bd-0 btn-sel-d">
                                {{ errors.message }}
                            </button>
                            <span id="checkBet" class="align-items-center link-03 justify-content-center mg-t-5" href="#checkModal" data-toggle="modal" style="display:none; cursor:pointer">Проверить игру</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <AllHistory :myGames="myGames" />
    </div>
</template>

<script>
    import AllHistory from "../../components/History/AllHistory";
    import md5 from 'blueimp-md5';

    export default {
        components: {
            AllHistory
        },
        data() {
            return {
                bet: 1,
                chance: 80,
                result: 1.25,
                min: 799999,
                max: 200000,
                game: {
                    hash: '',
                    hid: ''
                },
                errors: {
                    show: false,
                    message: ''
                },
                success: {
                    show: false,
                    message: ''
                },
                disableBtn: false,
                myGames: [],
            }
        },
        mounted() {
            this.$root.isLoading = true;

            this.getHash();
        },
        methods: {
            validateBet() {
                if (this.bet > 1000000) {
                    this.bet = 1000000;
                }

                //this.bet = (parseInt(this.bet * 100)) / 100;
                //this.bet = this.bet.toFixed(2);
                this.bet = this.bet.toString();

                this.bet = this.bet.replace(/[,]/g, '.').replace(/[^\d,.]*/g, '').replace(/([,.])[,.]+/g, '$1').replace(/^[^\d]*(\d+([.,]\d{0,2})?).*$/g, '$1');
                this.resultSize();
            },
            validateChance() {
                if (this.chance > 92) {
                    this.chance = 92;
                }
                //this.chance = (parseInt(this.chance * 100)) / 100;
                //this.chance = this.chance.toFixed(2);
                this.chance = this.chance.toString();

                this.chance = this.chance.replace(/.00/g, '').replace(/[,]/g, '.').replace(/[^\d,.]*/g, '').replace(/([,.])[,.]+/g, '$1').replace(/^[^\d]*(\d+([.,]\d{0,2})?).*$/g, '$1');
                //this.chance = parseInt(this.chance, 2);
                //this.chance = Number(this.chance);
                this.resultSize();
            },
            resultSize() {
                if (this.bet > 1000000) {
                    this.bet = 1000000;
                }
                console.log(this.chance);

                this.result = (parseInt(((97 / this.chance) * this.bet) * 100))/100;

                this.min = Math.floor((this.chance / 100) * 999999);
                this.max = 999999 - Math.floor((this.chance / 100) * 999999);
            },
            typeBet(type) {
                switch (type) {
                    case "max":
                        if (this.$root.user !== null) {
                            this.bet = this.$root.user.balance;
                            console.log(this.$root.user.balance)
                        } else {
                            this.bet = 0;
                        }
                        break;
                    case "min":
                        this.bet = 1;
                        break;
                    case "x2":
                        this.bet = (this.bet * 2).toFixed(2);
                        break;
                    case "/2":
                        this.bet = (this.bet / 2).toFixed(2);
                        break;
                }

                this.validateBet();
                this.resultSize();
            },
            typeChance(type) {
                switch (type) {
                    case "max":
                        this.chance = 92;
                        break;
                    case "min":
                        this.chance = 1;
                        break;
                    case "x2":
                        this.chance = (this.chance * 2).toFixed(2);
                        break;
                    case "/2":
                        this.chance = (this.chance / 2).toFixed(2);
                        break;
                }

                this.validateChance();
                this.resultSize();
            },
            betDiceSocket(type) {
                switch (type) {
                    case "max":
                        this.betType = 'max';
                    break;
                    case "min":
                        this.betType = 'min';
                    break;
                }
                this.errors.show = false;
                this.success.show = false;
                this.disableBtn = true;

                if (!this.$cookie.get('token')) {
                    this.errors = {
                        show: true,
                        message: 'Авторизуйтесь'
                    };

                    this.disableBtn = false;

                    return;
                }

                if (this.bet.length === 0) {
                    this.errors = {
                        show: true,
                        message: 'Введите ставку'
                    };

                    this.disableBtn = false;

                    return;
                }

                if (this.chance.length === 0) {
                    this.errors = {
                        show: true,
                        message: 'Введите шанс'
                    };

                    this.disableBtn = false;

                    return;
                }

                var test = md5(this.game.hid + this.$root.user.id + 'oxyeli_g@ndoni?' + this.game.hid);

                this.$socket.emit('diceBet', {
                    type: type,
                    bet: this.bet,
                    id: this.$root.user.id,
                    chance: this.chance,
                    hid: this.game.hid,
                    a: test
                }, (data) => {
                    if(!data.result) {
                        this.errors = {
                            show: true,
                            message: data.message
                        };
                        if (data.message === 'Игра с данным хешем уже была сыграна') {
                            this.getHash();
                        }

                        this.disableBtn = false;
                    } else {
                        this.$root.user.balance = data.balance;

                        if (data.success) {
                            this.success = {
                                show: true,
                                message: data.text
                            };
                        } else {
                            this.errors = {
                                show: true,
                                message: data.text
                            };
                        }

                        this.getHash().then(() => {
                            this.disableBtn = false;
                        });

                        this.addMyHistory(data);
                    }
                });
            },
            getHash() {
                return new Promise((resolve, reject) => {
                    if (!this.$cookie.get('token')) {
                        if (this.$cookie.get('game')) {
                            this.game = JSON.parse(this.$cookie.get('game'));
                            this.$root.isLoading = false;

                            resolve(true);
                        } else {
                            this.$root.graphql.getGameHashGuest().then(res => {
                                const game = res.GetHashGuest;
                                this.$cookie.set('game', JSON.stringify(game));

                                this.game = game;
                                this.$root.isLoading = false;

                                resolve(true);
                            })
                        }
                    } else {
                        this.$cookie.delete('game');

                        this.$root.graphql.getGameHash().then(res => {
                            this.game = res.GetHash;
                            this.$root.isLoading = false;

                            resolve(true);
                        })
                    }

                    this.showHash();
                });
            },
            showHash() {
                $('#hashBet').fadeOut('slow', function () {
                    $('#hashBet').fadeIn('slow', function () {
                    });
                });
            },
            addMyHistory(bet) {
                if (this.myGames.length >= 20) {
                    this.myGames.pop();
                }

                this.myGames.unshift({
                    game: 'dice',
                    id: bet.id,
                    username: this.$root.user.username,
                    bet: bet.bet,
                    chance: bet.chance,
                    win: bet.win
                });
            }
        }
    }
</script>

<style>
    .card{
        background: #fff;
        box-shadow: 0 5px 5px 1px rgb(53 102 251 / 8%);
   
    border-radius: 10px;
    }
    .qwe{
        color: #38477c;
    font-family: rubiklight;
    background: none;
    border: 1px solid #d0d2dd;
    
    display: flex;
    justify-content: center;
    align-items: center;
    border-top: 0;
    border-right: 0;
    cursor: pointer;
    transition: all .5s ease;
    -webkit-user-select: none;
    -ms-user-select: none;
    user-select: none;
    }
</style>