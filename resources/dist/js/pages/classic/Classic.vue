<template>
    <p>Тех. работы</p>
</template>
<!-- <template>
    <div style="overflow: hidden;">
        <div class="row">
            <div class="col-md-4 pt-0 bet-block">
                <div class="card border-0 mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Ставка</h5>
                            <div class="row row-sm">
                                <div class="col-12 col-xs-12 col-md-12">
                                    <label for="full-name" class="input-item-label text-center">Сумма</label>
                                    <div class="input-group tx-light tx-24 dice-input">
                                        <input
                                            maxlength="7"
                                            id="amountBetInputWheelGame"
                                            autocomplete="off"
                                            style="border-bottom-right-radius: 0; border-bottom-left-radius: 0;"
                                            class="tx-20 tx-center form-control tx-normal tx-rubik"
                                            placeholder="Сумма"
                                            v-model="bet"
                                        />
                                    </div>
                                <div style="margin-top: -1px;" class="btn-group tx-rubik d-flex justify-content-center">
                                    <button @click="typeBet('max')" style="border-top-left-radius: 0; padding: 0;" class="tx-gray-600 btn btn-xs btn-white tx-13 mb-mines">Max</button>
                                    <button @click="typeBet('min')" style="border-top-right-radius: 0; padding: 0;" class="tx-gray-600 btn btn-xs btn-white tx-13 mb-mines">Min</button>
                                    <button @click="typeBet('x2')" style="border-top-right-radius: 0; padding: 0;" class="tx-gray-600 btn btn-xs btn-white tx-13 mb-mines">x2</button>
                                    <button @click="typeBet('/2')" style="border-top-right-radius: 0; padding: 4px 0;" class="tx-gray-600 btn btn-xs btn-white tx-13 mb-mines">/2</button>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-secondary btn-block pd-t-10 pd-b-10 tx-15 mt-2" @click="createBet">Поставить</button>
                        <button id="error_bet" v-if="errors.show" style="padding: 11px; pointer-events:none;margin-top:10px" class="btn btn-block tx-medium btn-la-mob bg-danger-dice tx-white bd-0 btn-sel-d">
                            {{ errors.message }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-8 chart-block">
                <div class="card mb-3 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Джекпот</h5>
                            </div>
                            <div class="bankbox">
                                <h5 class="card-title">Банк игры:</h5>
                                <h6 class="card-subtitle text-muted" style="text-align: right;">{{ bank }}</h6>
                            </div>
                        </div>
                        <div class="chartbox">
                            <div class="ar01">
                                <i class="fas fa-angle-up"></i>
                            </div>
                            <canvas id="circle" class="circle_jackpot"></canvas>
                            <div class="timer"><span>00</span>:<span id="seconds">{{ timer }}</span></div>
                        </div>
                    </div>
                </div>
                <div class="card border-0 mg-b-sm-10" id="playersBlock" v-show="players.length >= 1">
                    <div class="card-body">
                        <div id="players">
                            <div class="player" v-for="player in players">
                                <div class="avatar avatar d-sm-flex img-fluid" style="width:78px;height:78px;">
                                    <span class="avatar-initial bg-dark rounded" style="font-size: 20px;" :style="'borderBottom: 4px solid #' + player.color">{{player.username.substr(0, 2)}}</span>
                                </div>
                                <div class="bilet">{{Number(player.chance).toFixed(2)}}%</div>
                                <span class="badge badge-primary">{{Number(player.sum).toFixed(2)}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template> -->

<script>
    export default {
        data() {
            return {
                bet: 1,
                errors: {
                    show: false,
                    message: ''
                },
                bank: 0,
                timer: 15,
                canvas: null,
                chart: null,
                players: []
            }
        },
        mounted() {
            this.$root.isLoading = false; //true
            this.canvas = document.getElementById('circle').getContext('2d');
            this.canvas.canvas.width = 100;
            this.canvas.canvas.height = 100;

            this.chartClear();
            this.getGame();
        },
        methods: {
            getGame() {
                this.$root.axios.post('/jackpot/init', {
                    room: 'easy'
                })
                .then(res => {
                    const data = res.data;
                    this.$root.isLoading = false;
                    this.JackpotParse(data.data);
                })
            },
            createBet() {
                this.errors.show = false;
                this.$root.axios.post('/jackpot/bet', {
                    room: 'easy',
                    bet: this.bet
                })
                .then(res => {
                    const data = res.data;
                    if(!data.success) {
                        this.errors = {
                            show: true,
                            message: data.msg
                        };
                    } else {
                        this.$root.user.balance = data.balance;
                    }
                })
            },
            JackpotParse(res) {
                this.players = [];

                let data = [], avatars = [], colors = [];

                let bets = '';
                let chances = '';
                res.chances.forEach(chance => {
                    this.players.push({
                        username: chance.user.username, 
                        chance: chance.user.avatar, 
                        color: chance.color, 
                        chance: chance.chance,
                        sum: chance.sum
                    });
                    data.push(parseFloat(chance.chance));
                    colors.push('#' + chance.color);
                });

                this.bank = (res.amount).toFixed(2);
                if(data.length == 0) {
                    this.chart.data.datasets[0].data = [100];
                    this.chart.data.datasets[0].backgroundColor = ['#38c172'];
                    this.chart.update();
                } else {
                    this.chart.data.datasets[0].data = data;
                    this.chart.data.datasets[0].backgroundColor = colors;
                    this.chart.update();
                }
            },
            chartClear() {
                this.chart = new Chart(this.canvas, {
                    type: "doughnut",
                    data: {
                        datasets: [
                            {
                                label: "",
                                data: [1],
                                backgroundColor: ["#38c172"],
                                borderWidth: 0,
                            },
                        ],
                    },
                    options: {
                        responsive: 1,
                        cutoutPercentage: 90,
                        legend: {
                            display: 0,
                        },
                        tooltips: {
                            enabled: 0,
                        },
                        hover: {
                            mode: null,
                        },
                        plugins: {
                            labels: {
                                render: "image",
                                images: [],
                            },
                        },
                    },
                });
            },
            updateBalance() {
                this.$root.axios.post('/getBalance', {
                    token: this.$cookie.get('token')
                })
                    .then(res => {
                        const data = res.data;
                        this.$root.user.balance = data.balance;
                    })
            },
            typeBet(type) {
                switch (type) {
                    case "max":
                        if (this.$root.user !== null) {
                            this.bet = this.$root.user.balance;
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
        },
        sockets: {
            jackpot(r) {
                if(r.type == 'timer') {
                    var time = r.data.sec;
                    if(time < 10) time = '0'+r.data.sec;
                    else time = r.data.sec;

                    this.timer = time;
                }
                if(r.type == 'update') {
                    this.JackpotParse(r.data.data);
                }
                if(r.type == 'slider') {
                    var cldn = 0,
                    spin = 0,
                    cords = 0,
                    rotate = [];
                    cords = r.data.cords;
                    let timer = setInterval(() => {
                        if(cldn >= 6) {
                            rotate[r.room] = {spin: cords, time: 0};
                            clearInterval(timer);
                            return;
                        }
                        cldn++;
                        spin = (cords/6)*cldn;
                        rotate[r.room] = {spin: spin, time: (6-cldn)*1000};
                    }, 1*1000)

                    $('#circle').css({
                        transition: 'all 7.5s cubic-bezier(0.15, 0.15, 0, 1) 0s',
                        transform: 'rotate('+ -Math.abs(r.data.cords) +'deg)'
                    })
                }

                if(r.type == 'newGame') {
                    this.chartClear();
                    this.updateBalance();

                    this.players = [];
                    this.timer = 15;
                    this.bank = 0;

                    $('#circle').css({
                        transition: 'all 0s cubic-bezier(0, 0.49, 0, 1) -7ms',
                        transform: 'rotate(0deg)'
                    });
                }
            }
        }
    }
</script>

<style scoped>
.badge-primary {
    background-color: #313f53;
}
.rounded {
    border-radius: 0.25rem 0.25rem 0 0!important;
}
#players {
    display: -webkit-box;
    margin: 0 auto;
    overflow-x: auto;
}
.player {
    position: relative;
    width: 78px;
    margin-right: 15px;
}
.player > span {
    position: absolute;
    top: 0px;
    right: 0px;
}
.player > .bilet {
    position: absolute;
    color: #fff;
    background: rgba(0,0,0,.68);
    padding: 2px 7px;
    border-radius: 4px;
    opacity: 1!important;
    text-shadow: 0 0 12px #969696;
    text-align: center;
    left: 50%;
    top: 76%;
    transform: translateX(-50%) translateY(-50%);
    width: min-content;
}
@media(max-width: 992px) {
    .bet-block {
        order: 1;
    }
    .chart-block {
        order: 0;
    }
}
.chartbox {
    display: flex;
    align-items: center;
    justify-content: center;
}
.circle_jackpot {
    max-width: 300px;
    max-height: 300px;
    margin: 0 auto;
    display: block;
    width: 300px;
    height: 300px;
    transition: all 0 ease 0;
    transform: rotate(0deg)
}
.ar01 {
    left: 0;
    right: 0;
    text-align: center;
    position: absolute;
    font-size: 30px;
    z-index: 11;
    top: 68px;
}

.timer {
    font-size: 44px;
    color: #38c172;
    font-family: Arial;
    font-weight: 700;
}

.timer {
    position: absolute;
}

.timer > span {
    border-radius: 5px;
    font-size: 50px;
    letter-spacing: 1px;
    box-shadow: 4px 4px 6px rgb(0 0 0 / 8%);
    text-shadow: 4px 4px 6px rgb(0 0 0 / 8%);
    font-weight: 700;
    overflow: hidden;
    padding: 5px 10px;
    color: #fff;
    position: relative;
    margin: 0 5px;
    background: #38c172;
}
@media (max-width: 500px) {
    .timer > span {
        font-size: 30px;
    }
}
.card {
    border-radius: 10px;
}
</style>