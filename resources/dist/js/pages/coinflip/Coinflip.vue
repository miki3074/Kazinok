<template>
    <!-- <div v-if="$root.user !== null && $root.user.is_admin == 1 || $root.user.id == 20"> -->
    <div>
        <div class="card">
            <div class="card-header pd-y-20 d-md-flex align-items-center justify-content-between d-none d-sm-block">
			    <h4 class="mg-b-0">Coin</h4> 
            </div>
            <div class="card-body" style="padding-bottom: 30px;">
                <div class="row">
                    <div class="col-xs-6 col-lg-6 mg-b-12" style="height: 250px;">
                            <br />
                                <div id="coin" :class="[drop]">
                                    <div class="side-a"><img style="width: 206px; height: 206px;" src="/img/head.png" /></div>
                                    <div class="side-b"><img style="width: 206px; height: 206px;" src="/img/tail.png" /></div>
                                </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="col-lg-12 but-dice">
                            <div class="right-side">
                                <br />
                                <div style="position: relative; top: -10px; margin-left: auto; margin-right: auto;" class="d-flex justify-content-center">
                                    <div class="d-flex justify-content-center">
                                        <ul class="steps mg-t-20">
                                            <div id="carousel" class="carousel slide" data-ride="carousel" data-interval="false">
                                                <div class="carousel-inner pd-t-4 pd-b-2" id="minesRate">
                                                    <div class="1 carousel-item active justify-content-center">
                                                        <li class="1 step-item bd bd-1 pd-10 rounded-5">
                                                            <span class="st-bl" style="position: absolute; margin-top: -18px; margin-left: 4px; font-size: 10px; background: #fff; padding: 2px 7px;">Коэффициент</span>
                                                            <a class="step-link">
                                                                <div style="margin-left: 0; width: 110px; align-items: center;">
                                                                    <span class="step-title tx-rubik tx-spacing--1">{{ Number(coef).toFixed(2) }}</span><span class="step-desc"></span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li class="2 step-item bd bd-1 pd-10 rounded-5">
                                                            <span class="st-bl" style="position: absolute; margin-top: -18px; margin-left: 4px; font-size: 10px; background: #fff; padding: 2px 7px;">След. коэффициент</span>
                                                            <a class="step-link">
                                                                <div style="margin-left: 0; width: 110px; align-items: center;"><span class="step-title tx-rubik tx-spacing--1">{{ Number(nextcoef).toFixed(1) }}</span><span class="step-desc"></span></div>
                                                            </a>
                                                        </li>
                                                    </div>
                                                </div>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row row-sm mt-2">
                                    <div class="col-12 col-xs-12 col-md-12">
                                        <div class="input-group tx-light tx-24 dice-input">
                                            <input placeholder="Сумма" autocomplete="off" class="tx-20 tx-center form-control tx-normal tx-rubik" style="border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;" v-model="bet"/>
                                        </div>
                                        <div class="btn-group tx-rubik d-flex justify-content-center" style="margin-top: -1px;">
                                            <button @click="typeBet('max')" class="tx-gray-600 btn btn-xs btn-white tx-13 mb-mines" style="border-top-left-radius: 0px; padding: 0px;">Max</button>
                                            <button @click="typeBet('min')" class="tx-gray-600 btn btn-xs btn-white tx-13 mb-mines" style="border-top-right-radius: 0px; padding: 0px;">Min</button>
                                            <button @click="typeBet('x2')" class="tx-gray-600 btn btn-xs btn-white tx-13 mb-mines" style="border-top-right-radius: 0px; padding: 0px;">x2</button>
                                            <button @click="typeBet('/2')" class="tx-gray-600 btn btn-xs btn-white tx-13 mb-mines" style="border-top-right-radius: 0px; padding: 4px 0px;">/2</button>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div id="coinButton" class="row row-sm" v-show="game">
                                    <div class="form-group col-6 col-md-6">
                                        <button
                                            class="btn btn-secondary btn-block tx-thin"
                                            id="buttonMin"
                                            @click="coinBet(1)"
                                            style="padding: 11px;"
                                            :disabled="disableBtnGame"
                                        >
                                            Demon
                                        </button>
                                    </div>
                                    <div class="col-6 col-md-6 form-group">
                                        <button
                                            class="btn btn-secondary btn-block tx-thin"
                                            id="buttonMax"
                                            @click="coinBet(2)"
                                            style="padding: 11px;"
                                            :disabled="disableBtnGame"
                                        >
                                            Spades
                                        </button>
                                    </div>
                                </div>
                                <div class="row-sm">
                                    <center><div class="form-group col-12 col-md-12" style="padding: 0 10px;">
                                        <button
                                            class="btn btn-secondary btn-block tx-thin"
                                            id="betCoin"
                                            @click="startCoin()"
                                            style="padding: 11px;"
                                            v-show="!game"
                                            :disabled="disableBtn"
                                        >
                                            Начать играть
                                        </button>
                                    </div>
                                </center>
                                    <div class="form-group col-12 col-md-12">
                                        <button
                                            @click="finishCoin();"
                                            style="position: relative; top: -20px;padding: 11px;"
                                            class="btn btn-secondary btn-block tx-thin"
                                            v-if="game && !cashingOut"
                                            :disabled="disableBtn"
                                        >
                                            Забрать {{ win }}
                                        </button>
                                        <button
                                            style="position: relative; top: -20px;padding: 11px;"
                                            class="btn btn-secondary btn-block tx-thin"
                                            v-if="game && cashingOut"
                                            :disabled="disableBtn"
                                        >
                                            Забрать {{ win }}
                                        </button>
                                    </div>
                                </div>
                                <button id="error_bet" 
                                style="padding: 11px; pointer-events: none; margin-top: 0px;" 
                                class="btn btn-block tx-medium btn-la-mob bg-danger-dice tx-white bd-0 btn-sel-d"
                                v-show="errors.show"
                                >
                                {{ errors.message }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <AllHistory :myGames="myGames" />
    </div>
    <!-- <div v-else>        
        <p>Тех. работы</p>
    </div> -->
</template>

<script>
    import AllHistory from "../../components/History/AllHistory";

    export default {
        components: {
            AllHistory
        },
        data() {
            return {
                game: false,
                bet: 1,
                coef: 1.85,
                win: 0,
                drop: 'noDrop',
                nextcoef: 3.6,
                errors: {
                    show: false,
                    message: ''
                },
                disableBtn: false,
                disableBtnGame: false,
                cashingOut: false,
                myGames: []
            }
        },
        mounted() {
            this.$root.isLoading = true;

            if (this.$cookie.get('token')) {
                this.getGame();
            } else {
                this.$root.isLoading = false;
            }
        },
        methods: {
            startCoin() {
                this.drop = 'noDrop';
                this.errors = {
                    show: false,
                    message: ''
                };
                
                this.bet = this.bet.toString();

                this.bet = this.bet.replace(/[,]/g, '.').replace(/[^\d,.]*/g, '').replace(/([,.])[,.]+/g, '$1').replace(/^[^\d]*(\d+([.,]\d{0,2})?).*$/g, '$1');
                this.bet = parseInt(this.bet, 10);

                this.$socket.emit('coinCreate', {
                    bet: this.bet,
                    id: this.$root.user.id
                }, (data) => {
                    if(!data.success) {
                        this.errors = {
                            show: true,
                            message: data.message
                        };
                    } else {
                        this.coef = 1.85;
                        this.nextcoef = 3.6;
                        this.game = true;
                        this.win = Number(data.bet).toFixed(2);
                        this.$root.user.balance = data.balance;
                    }
                });
            },
            finishCoin() {
                if (this.cashingOut) {
                    return;
                }
                this.cashingOut = true;
                this.drop = 'noDrop';
                this.errors = {
                    show: false,
                    message: ''
                };
                this.bet = parseInt(this.bet, 10);
                this.$socket.emit('coinTake', {
                    bet: this.bet,
                    id: this.$root.user.id
                }, (data) => {

                    if(data.balance !== null) {
                        this.$root.user.balance = data.balance;
                    }
                    if(!data.success) {
                        this.errors = {
                            show: true,
                            message: data.message
                        };
                        this.cashingOut = false;
                    } else {
                        this.game = false;
                        this.win = 0;
                        this.cashingOut = false;
                    }
                });
            },
            getGame() {
                this.axios.post('/coin/get')
                .then(res => {
                    this.$root.isLoading = false;
                    const data = res.data;

                    if(data.success) {
                        this.game = true;
                        this.bet = data.bet;
                        this.win = Number(data.coef * data.bet).toFixed(2);
                        this.coef = data.coef;
                        this.nextcoef = data.nextcoef;
                    }
                }).catch(error => {
                    this.errors = {
                        show: true,
                        message: 'Произошла ошибка'
                    };
                });
            },
            coinBet(type) {

                if (this.disableBtnGame) {
                    return;
                }
                this.disableBtnGame = true;

                //this.drop = 'noDrop';
                this.errors = {
                    show: false,
                    message: ''
                };
                this.$socket.emit('coinBet', {
                    type: type,
                    id: this.$root.user.id
                }, (data) => {

                    if(!data.success) {
                        this.errors = {
                            show: true,
                            message: data.message
                        };
                    } else {
                        this.disableBtn = true;

                        this.drop = 'repeat';
                        setTimeout(() => {
                        this.drop = (data.drop == 1) ? 'heads' : 'tails';}, 500);

                        //if (data.drop == 1 && this.drop == 'heads') {
                        //    this.drop = 'repeat';
                        //    setTimeout(() => {
                        //    this.drop = 'heads';}, 500);
                        //} else if (data.drop == 1 && this.drop == 'repeat') {
                        //    this.drop = 'repeat';
                        //    setTimeout(() => {
                        //    this.drop = 'heads';}, 500);
                        //} else if (data.drop == 2 && this.drop == 'tails') {
                        //    this.drop = 'repeat';
                        //    setTimeout(() => {
                        //    this.drop = 'tails';}, 500);
                        //} else if (data.drop == 2 && this.drop == 'repeat') {
                        //    this.drop = 'repeat';
                        //    setTimeout(() => {
                        //    this.drop = 'tails';}, 500);
                        //} else {this.drop = (data.drop == 1) ? 'heads' : 'tails';}
                        //this.drop = (data.drop == 1) ? 'heads' : 'tails';
                        setTimeout(() => {
                            this.disableBtn = false;
                            this.win = Number(data.bet * data.coef).toFixed(2);
                            this.coef = data.coef;
                            this.nextcoef = data.nextcoef;
                            if(data.nextcoef == 0) {
                                this.game = false;
                                this.coef = 1;
                                this.nextcoef = 1.85;
                            }
                            this.disableBtnGame = false;
                        }, 2000);
                    }
                });
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
            },
            validateBet() {
                if(Number(this.bet) && this.bet < 1) {
                    this.bet = 1;
                }
            }
        }
    }
</script>

<style scoped>
.side-a > img, .side-b > img {
    position: absolute;
    left: -3px; 
    right: 0; 
    margin-left: auto; 
    margin-right: auto;
}
/*#coin div {
    position: absolute;
    width: 100%;
    height: 100%;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
    -webkit-box-shadow: inset 0 0 45px rgba(255, 255, 255, 0.3), 0 12px 20px -10px rgba(0, 0, 0, 0.4);
    -moz-box-shadow: inset 0 0 45px rgba(255, 255, 255, 0.3), 0 12px 20px -10px rgba(0, 0, 0, 0.4);
    box-shadow: inset 0 0 45px rgba(255, 255, 255, 0.3), 0 12px 20px -10px rgba(0, 0, 0, 0.4);
    -webkit-backface-visibility: hidden;
}*/

#coin div {
    position: absolute;
    width: 100%;
    height: 100%;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
    -webkit-backface-visibility: hidden;
}

#coin {
    position: relative;
    margin: 0 auto;
    width: 200px;
    height: 190px;
    cursor: pointer;
    transition: -webkit-transform 1s ease-in;
    -webkit-transform-style: preserve-3d;
}

.side-a {
    z-index: 100;
}
.side-b {
    -webkit-transform: rotateY(-180deg);
}

#coin.heads {
    -webkit-animation: flipHeads 2s ease-out forwards;
    -moz-animation: flipHeads 2s ease-out forwards;
    -o-animation: flipHeads 2s ease-out forwards;
    animation: flipHeads 2s ease-out forwards;
}
#coin.tails {
    -webkit-animation: flipTails 2s ease-out forwards;
    -moz-animation: flipTails 2s ease-out forwards;
    -o-animation: flipTails 2s ease-out forwards;
    animation: flipTails 2s ease-out forwards;
}

#coin.repeat {
    -webkit-animation: NoFlip 0.01s ease-out forwards;
    -moz-animation: NoFlip 0.01s ease-out forwards;
    -o-animation: NoFlip 0.01s ease-out forwards;
    animation: NoFlip 0.01s ease-out forwards;
}

.side-a {
    z-index: 100;
}
.side-b {
    -webkit-transform: rotateY(-180deg);
}

@media screen and (max-width: 557px) {
    .side-a {
        width: 150px;
        height: 150px;
    }
    .side-b {
        width: 150px;
        height: 150px;
    }
}

@media screen and (max-width: 300px) {
    .side-a {
        width: 100px;
        height: 100px;
    }
    .side-b {
        width: 100px;
        height: 100px;
    }
}
@-webkit-keyframes NoFlip {
    from {
        -webkit-transform: rotateY(0);
        -moz-transform: rotateY(0);
        transform: rotateY(0);
    }
    to {
        -webkit-transform: rotateY(0);
        -moz-transform: rotateY(0);
        transform: rotateY(0);
    }
}
@-webkit-keyframes flipHeads {
    from {
        -webkit-transform: rotateY(0);
        -moz-transform: rotateY(0);
        transform: rotateY(0);
    }
    to {
        -webkit-transform: rotateY(1800deg);
        -moz-transform: rotateY(1800deg);
        transform: rotateY(1800deg);
    }
}
@-webkit-keyframes flipTails {
    from {
        -webkit-transform: rotateY(0);
        -moz-transform: rotateY(0);
        transform: rotateY(0);
    }
    to {
        -webkit-transform: rotateY(1980deg);
        -moz-transform: rotateY(1980deg);
        transform: rotateY(1980deg);
    }
}

</style>