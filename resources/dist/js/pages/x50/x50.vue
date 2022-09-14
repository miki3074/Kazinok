<template>
    <div>
        <div class="card">
            <div class="card-header pd-y-20 d-md-flex align-items-center justify-content-between d-none d-sm-block">
                <h4 class="mg-b-0">Roulette</h4>
            </div>
            <div class="card-body" style="padding-bottom: 30px;">
                <div class="row" style="margin-top: 10px;">
                    <div class="col-xs-12 col-lg-6 betSide">
                        <div class="col-lg-12">
                            <div class="right-side">
                                <div class="row row-sm">
                                    <div class="col-12 col-xs-12 col-md-12">
                                        <label for="full-name" class="input-item-label text-center">Сумма</label>
                                        <div class="input-group tx-light tx-24 dice-input">
                                            <input
                                                maxlength="15"
                                                id="amountBetInputWheelGame"
                                                autocomplete="off"
                                                style="border-bottom-right-radius: 0; border-bottom-left-radius: 0;"
                                                class="tx-20 tx-center form-control tx-normal tx-rubik"
                                                placeholder="Сумма"
                                                v-model="bet"
                                            />
                                        </div>
                                        <div style="margin-top: -1px;" class="btn-group tx-rubik d-flex justify-content-center">
                                            <button @click="typeBet('max')" style="border-top-left-radius: 0; padding: 0;" class="tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines">Max</button>
                                            <button @click="typeBet('min')" style="border-top-right-radius: 0; padding: 0;" class="tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines">Min</button>
                                            <button @click="typeBet('x2')" style="border-top-right-radius: 0; padding: 0;" class="tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines">x2</button>
                                            <button @click="typeBet('/2')" style="border-top-right-radius: 0; padding: 4px 0;" class="tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines">/2</button>
                                        </div>
                                    </div>
                                </div>
                                <br />


                                <div class="row row-sm" style="display: flex; justify-content: center;">
                                    <div class="form-group col-8 col-md-4" >
                                        <button class="btn btn-dark btn-block tx-thin btn-la-mob btn-sel-d wheel--x2 sizesq"  @click="createBet('black')">
                                         <span style="float: left;">{{ (bank.black).toFixed(2) }}</span>  <span style="float: right;">x2</span>  
                                        </button>
                                    </div>
                                    <div class="form-group col-8 col-md-4">
                                        <button class="btn btn-warning btn-block tx-thin btn-la-mob btn-sel-d wheel--x50 sizesw" @click="createBet('green')">
                                         <span style="float: left;">{{ (bank.green).toFixed(2) }}</span>   <span style="float: right;">x13</span> 
                                        </button>
                                    </div>
                                    <div class="form-group col-8 col-md-4">
                                        <button class="btn btn-info btn-block tx-thin btn-la-mob btn-sel-d wheel--x5 sizes" @click="createBet('red')">
                                          <span style="float: left;">{{ (bank.red).toFixed(2) }}</span>  <span style="float: right;">x2</span>
                                        </button>
                                    </div>
                                </div>
                                <button id="error_bet" v-if="errors.show" style="padding: 11px; pointer-events:none;margin-top:0" class="btn btn-block tx-medium btn-la-mob bg-danger-dice tx-white bd-0 btn-sel-d">
                                    {{ errors.message }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-6 mg-b-20 wheelSide">
                        <div class="pd-dc wrapper">
                            <svg class="game-roller" id="wheel_svg" width="400" height="400" style="transform: rotateZ(-168.08deg); transition: transform 5s linear ease 0s;" viewBox="0 0 596 596" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M116.7 78C123.5 72.4 130.5 67.1 137.8 62.1L130.7 51.3C123 56.4 115.6 62.1 108.4 68L116.7 78Z" fill="#BF526F"/>
                                <path d="M92.4 100.5C98.6 94 105 87.9 111.7 82.2L103.5 72.4C96.4 78.5 89.7 84.9 83 91.7L92.4 100.5Z" fill="#272D3C"/>
                                <path d="M70.8 125.8C76.1 118.8 81.9 112 88 105.4L78.6 96.6C72.2 103.5 66.1 110.8 60.5 118.2L70.8 125.8Z" fill="#BF526F"/>
                                <path d="M172 42.4C175.4 40.8 178.9 39.1 182.3 37.6C187.1 35.5 192.1 33.5 197 31.6L192.6 19.5C183.7 22.8 174.9 26.7 166.2 30.9L172 42.4Z" fill="#BF526F"/>
                                <path d="M52.5 153C56.9 145.5 61.8 138 67 130.9L56.7 123.2C51.2 130.8 46.1 138.5 41.5 146.5L52.5 153Z" fill="#272D3C"/>
                                <path d="M143.2 58.6C150.7 53.8 158.3 49.3 166.2 45.2L160.4 33.6C152.1 37.9 143.9 42.6 136.1 47.7L143.2 58.6Z" fill="#272D3C"/>
                                <path d="M203 29.3C211.4 26.3 219.9 23.8 228.5 21.7L225.5 9.20001C216.4 11.4 207.4 14.1 198.5 17.3L203 29.3Z" fill="#272D3C"/>
                                <path d="M16.8 344.9C15.3 336 14.3 327 13.7 318.1L0.800003 318.8C1.4 328.2 2.6 337.8 4.2 347.1L16.8 344.9Z" fill="#272D3C"/>
                                <path d="M24.1 377.1C22.5 371.8 21.2 366.4 20 361C19.2 357.8 18.6 354.4 17.9 351.1L5.3 353.3C7 362.5 9.2 371.6 11.8 380.7L24.1 377.1Z" fill="#BF526F"/>
                                <path d="M13.6 278.5C14.2 269.7 15.2 261 16.6 252.3L3.9 250.1C2.4 259.3 1.3 268.5 0.699997 277.8L13.6 278.5Z" fill="#272D3C"/>
                                <path d="M17.7 245.9C19.3 237.2 21.4 228.5 23.7 220L11.3 216.4C8.79999 225.5 6.49999 234.6 4.89999 243.9L17.7 245.9Z" fill="#BF526F"/>
                                <path d="M25.6 213.8C27.1 208.9 28.8 203.9 30.6 199.1C31.9 195.5 33.3 192 34.8 188.4L23 183.3C19.4 192.1 16.1 201 13.3 210.1L25.6 213.8Z" fill="#272D3C"/>
                                <path d="M37.3 182.4C41 174.2 44.9 166.3 49.3 158.6L38.1 152.2C33.5 160.4 29.3 168.9 25.4 177.4L37.3 182.4Z" fill="#BF526F"/>
                                <path d="M463.7 50.8L456.6 61.6C463.9 66.6 471 71.9 478 77.6L486.3 67.7C478.9 61.6 471.4 56 463.7 50.8Z" fill="#272D3C"/>
                                <path d="M288.8 13.3C290.5 13.2 292.2 13.2 293.9 13.1L293.8 0.299988C284.5 0.399988 275 0.999986 265.7 1.89999L267.2 14.7C274.3 14 281.5 13.5 288.8 13.3Z" fill="#24FF00"/>
                                <path d="M13.2 311.6C13.1 310 13.1 308.5 13 306.8C12.8 299.5 12.9 292.1 13.2 284.8L0.400006 284.1C6.10948e-06 293.6 6.10948e-06 303 0.400006 312.3L13.2 311.6Z" fill="#24FF00"/>
                                <path d="M306.7 582.5C305 582.6 303.3 582.6 301.6 582.7V595.5C310.9 595.4 320.4 594.8 329.7 593.9L328.2 581.1C321 581.9 313.8 582.3 306.7 582.5Z" fill="#BF526F"/>
                                <path d="M102.8 505.5C99.6 502.5 96.3 499.3 93.3 496.1L83.9 505C87.2 508.4 90.5 511.6 94 514.9C97.4 518.1 100.7 521.1 104.2 524L112.5 514.1C109.2 511.4 105.9 508.5 102.8 505.5Z" fill="#272D3C"/>
                                <path d="M582.1 284.3C582.2 285.9 582.2 287.4 582.3 289.1C582.5 296.4 582.4 303.8 582.1 311.1L594.9 311.8C595.4 302.4 595.3 293 594.9 283.7L582.1 284.3Z" fill="#272D3C"/>
                                <path d="M492.5 90.3C495.7 93.3 499 96.5 502 99.7L511.4 90.8C508.1 87.4 504.8 84.2 501.3 80.9C497.9 77.7 494.6 74.7 491.1 71.8L482.8 81.7C486.2 84.5 489.4 87.4 492.5 90.3Z" fill="#BF526F"/>
                                <path d="M35 408.1C31.6 399.9 28.6 391.7 26 383.3L13.5 387C16.3 395.8 19.5 404.5 23 413.2L35 408.1Z" fill="#272D3C"/>
                                <path d="M333.6 15.4C336.9 15.8 340 16.3 343.2 16.8C348.8 17.7 354.3 18.7 359.8 20L362.8 7.4C353.6 5.4 344.4 3.70001 335 2.60001L333.6 15.4Z" fill="#272D3C"/>
                                <path d="M366.2 21.5C374.7 23.6 383.2 26.1 391.5 29L395.9 16.9C387.1 13.8 378.2 11.2 369.1 8.89999L366.2 21.5Z" fill="#BF526F"/>
                                <path d="M300.3 13.2C309.3 13.2 318.2 13.8 327.3 14.7L328.7 1.89999C319.2 0.899986 309.7 0.399988 300.3 0.299988V13.2Z" fill="#BF526F"/>
                                <path d="M234.9 20.2C243.4 18.3 252.2 16.7 260.8 15.6L259.3 2.79999C250.1 3.99999 240.9 5.69998 231.9 7.69998L234.9 20.2Z" fill="#BF526F"/>
                                <path d="M427.9 44.5C436 48.6 443.7 53.1 451.3 58L458.4 47.2C450.3 42 442.1 37.3 433.7 33L427.9 44.5Z" fill="#BF526F"/>
                                <path d="M397.6 31.1C406 34.2 414.1 37.8 422.2 41.6L427.9 30.1C419.4 26 410.8 22.3 402 19L397.6 31.1Z" fill="#272D3C"/>
                                <path d="M524.6 470.1C519.3 477.1 513.5 483.9 507.5 490.5L516.9 499.3C523.3 492.4 529.4 485.1 535 477.7L524.6 470.1Z" fill="#272D3C"/>
                                <path d="M569.7 382C568.2 386.9 566.5 391.9 564.7 396.7C563.4 400.3 562 403.8 560.5 407.4L572.3 412.5C575.9 403.7 579.2 394.8 582 385.7L569.7 382Z" fill="#BF526F"/>
                                <path d="M558 413.4C554.3 421.6 550.4 429.5 546 437.2L557.2 443.6C561.8 435.4 566 426.9 569.9 418.4L558 413.4Z" fill="#272D3C"/>
                                <path d="M577.6 349.9C576 358.6 573.9 367.3 571.6 375.8L584 379.4C586.5 370.3 588.8 361.2 590.4 351.9L577.6 349.9Z" fill="#272D3C"/>
                                <path d="M502.6 495.6C496.4 502 490 508.2 483.2 513.9L491.4 523.7C498.5 517.6 505.2 511.2 511.9 504.4L502.6 495.6Z" fill="#BF526F"/>
                                <path d="M542.9 442.9C538.5 450.4 533.6 457.9 528.4 465L538.7 472.7C544.2 465.1 549.3 457.4 553.9 449.4L542.9 442.9Z" fill="#BF526F"/>
                                <path d="M581.7 317.3C581.1 326.1 580.1 334.8 578.7 343.5L591.4 345.7C592.9 336.5 594 327.3 594.5 318L581.7 317.3Z" fill="#BF526F"/>
                                <path d="M578.5 251C580 259.9 581 268.9 581.6 277.8L594.5 277.1C593.9 267.7 592.7 258.1 591.1 248.8L578.5 251Z" fill="#24FF00"/>
                                <path d="M478.3 518.1C471.5 523.7 464.4 529 457.2 533.9L464.3 544.7C472 539.6 479.4 534 486.6 528L478.3 518.1Z" fill="#272D3C"/>
                                <path d="M506.6 104.4C512.7 110.9 518.4 117.7 523.9 124.7L534.2 117C528.5 109.6 522.4 102.4 516 95.5L506.6 104.4Z" fill="#272D3C"/>
                                <path d="M545.7 158.1C550.1 165.8 554 173.8 557.6 182L569.4 176.8C565.5 168.2 561.4 159.9 556.8 151.7L545.7 158.1Z" fill="#272D3C"/>
                                <path d="M527.7 130C533 137.2 537.9 144.8 542.5 152.6L553.6 146.2C548.8 138.1 543.5 130.1 537.9 122.5L527.7 130Z" fill="#BF526F"/>
                                <path d="M571.2 218.8C572.8 224.1 574.1 229.5 575.3 234.9C576.1 238.1 576.7 241.5 577.4 244.8L590 242.6C588.3 233.4 586.1 224.3 583.5 215.2L571.2 218.8Z" fill="#272D3C"/>
                                <path d="M560.4 187.8C563.8 196 566.8 204.2 569.4 212.6L581.8 208.8C579 200 575.8 191.3 572.3 182.6L560.4 187.8Z" fill="#BF526F"/>
                                <path d="M392.3 566.6C383.9 569.6 375.4 572.1 366.8 574.2L369.8 586.7C378.9 584.5 387.9 581.8 396.8 578.6L392.3 566.6Z" fill="#BF526F"/>
                                <path d="M229.1 574.3C220.6 572.2 212.1 569.7 203.9 566.8L199.5 578.9C208.3 582 217.2 584.6 226.2 586.9L229.1 574.3Z" fill="#272D3C"/>
                                <path d="M167.5 551.3C159.4 547.2 151.7 542.7 144.1 537.8L137 548.6C145.1 553.8 153.3 558.5 161.7 562.8L167.5 551.3Z" fill="#272D3C"/>
                                <path d="M197.8 564.6C189.4 561.5 181.3 557.9 173.2 554.1L167.5 565.6C176 569.7 184.6 573.4 193.4 576.7L197.8 564.6Z" fill="#BF526F"/>
                                <path d="M138.7 534.2C131.4 529.2 124.3 523.9 117.3 518.2L109 528.1C116.4 534.1 123.8 539.7 131.6 544.9L138.7 534.2Z" fill="#BF526F"/>
                                <path d="M67.6 465.9C62.3 458.7 57.4 451.1 52.8 443.3L41.6 449.7C46.4 457.8 51.7 465.8 57.3 473.4L67.6 465.9Z" fill="#272D3C"/>
                                <path d="M88.8 491.5C82.7 485 77 478.2 71.5 471.2L61.2 478.9C66.9 486.3 73 493.5 79.4 500.4L88.8 491.5Z" fill="#BF526F"/>
                                <path d="M295 582.7C286 582.7 277.1 582.1 268.1 581.2L266.7 594C276.2 595 285.7 595.5 295.1 595.6L295 582.7Z" fill="#24FF00"/>
                                <path d="M261.7 580.5C258.5 580.1 255.3 579.6 252.1 579.2C246.5 578.3 241 577.3 235.5 576L232.5 588.6C241.7 590.7 251 592.3 260.3 593.5L261.7 580.5Z" fill="#BF526F"/>
                                <path d="M423 553.8C419.7 555.4 416.5 556.9 413 558.5C408.2 560.6 403.2 562.6 398.3 564.5L402.7 576.6C411.5 573.3 420.2 569.5 428.8 565.3L423 553.8Z" fill="#272D3C"/>
                                <path d="M49.6 437.8C45.2 430.1 41.3 422.1 37.7 413.9L25.9 419.1C29.8 427.7 33.9 436 38.5 444.2L49.6 437.8Z" fill="#BF526F"/>
                                <path d="M451.8 537.4C444.3 542.2 436.7 546.7 428.7 550.7L434.5 562.3C442.8 558 451 553.3 458.8 548.2L451.8 537.4Z" fill="#BF526F"/>
                                <path d="M360.5 575.7C352 577.6 343.2 579.2 334.6 580.3L336.1 593.1C345.3 591.9 354.5 590.2 363.5 588.2L360.5 575.7Z" fill="#272D3C"/>
                            </svg>
                            <div class="info">
                                <div class="pointer b"></div>
                                <span class="info__text">До начала игры</span>
                                <div class="timer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="timer__icon svg-icon svg-icon--clock">
                                        <use xlink:href="/icons.svg#clock" xmlns:xlink="http://www.w3.org/1999/xlink"></use>
                                    </svg>
                                    <span class="timer__value">{{timer}}.{{smallTimer}}</span>
                                </div>
                                <div class="games-history game-history-lines">
                                    <div class="line" v-for="color in history" :class="'history__' + color"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bets wrapper-cont">
            <div class="base-card-cont bet-list-cont bet-list">
                <svg xmlns="http://www.w3.org/2000/svg" class="crown-winner svg-icon svg-icon--crown" v-if="lastColor == 'black'">
                    <use xlink:href="/icons.svg#crown" xmlns:xlink="http://www.w3.org/1999/xlink"></use>
                </svg>
                <div class="head head--x2">
                    <span class="x-rate"> x2</span>
                    <div class="amount-cont">
                        <span class="amount" data-color="black">{{ bank.black }}</span>
                    </div>
                </div>
                <div class="user-bets">
                    <div class="user-bet" style="opacity: 1;" v-for="bet in bets.black">
                        <div class="user" :class="'bet_' + bet.user_id + '_' +  bet.color">
                            <!-- <div class="avatar avatar d-sm-flex">
                                <span class="avatar-initial rounded-circle bg-dark">{{ bet.username.substr(0, 2) }}</span>
                            </div> -->
                            <span class="user-name"> {{ bet.username }} </span>
                        </div>
                        <div class="bet-cont">
                            <span class="bet">{{ bet.sum }}</span>
                        </div>
                    </div>
                </div>
                <p class="info-text info-text--no-bets" v-show="bets.black.length == 0">Нет ставок</p>
            </div>
            <div class="base-card-cont bet-list-cont bet-list">
                <svg xmlns="http://www.w3.org/2000/svg" class="crown-winner svg-icon svg-icon--crown" v-if="lastColor == 'red'">
                    <use xlink:href="/icons.svg#crown" xmlns:xlink="http://www.w3.org/1999/xlink"></use>
                </svg>
                <div class="head head--x5">
                    <span class="x-rate"> x2</span>
                    <div class="amount-cont">
                        <span class="amount" data-color="red">{{ bank.red }}</span>
                    </div>
                </div>
                <div class="user-bets">
                    <div class="user-bet" style="opacity: 1;" v-for="bet in bets.red">
                        <div class="user" :class="'bet_' + bet.user_id + '_' +  bet.color">
                            <!-- <div class="avatar avatar d-sm-flex">
                                <span class="avatar-initial rounded-circle bg-dark">{{ bet.username.substr(0, 2) }}</span>
                            </div> -->
                            <span class="user-name"> {{ bet.username }} </span>
                        </div>
                        <div class="bet-cont">
                            <span class="bet">{{ bet.sum }}</span>
                        </div>
                    </div>
                </div>
                <p class="info-text info-text--no-bets" v-show="bets.red.length == 0">Нет ставок</p>
            </div>
            <div class="base-card-cont bet-list-cont bet-list">
                <svg xmlns="http://www.w3.org/2000/svg" class="crown-winner svg-icon svg-icon--crown" v-if="lastColor == 'green'">
                    <use xlink:href="/icons.svg#crown" xmlns:xlink="http://www.w3.org/1999/xlink"></use>
                </svg>
                <div class="head head--x50">
                    <span class="x-rate"> x13</span>
                    <div class="amount-cont">
                        <span class="amount" data-color="green">{{ bank.green }}</span>
                    </div>
                </div>
                <div class="user-bets">
                    <div class="user-bet" style="opacity: 1;" v-for="bet in bets.green">
                        <div class="user" :class="'bet_' + bet.user_id + '_' +  bet.color">
                            <!-- <div class="avatar avatar d-sm-flex">
                                <span class="avatar-initial rounded-circle bg-dark">{{ bet.username.substr(0, 2) }}</span>
                            </div> -->
                            <span class="user-name"> {{ bet.username }} </span>
                        </div>
                        <div class="bet-cont">
                            <span class="bet">{{ bet.sum }}</span>
                        </div>
                    </div>
                </div>
                <p class="info-text info-text--no-bets" v-show="bets.green.length == 0">Нет ставок</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                bet: 1,
                errors: {
                    show: false,
                    message: ''
                },
                bets: {
                    black: [],
                    yellow: [],
                    red: [],
                    green: []
                },
                bank: {
                    black: 0,
                    yellow: 0,
                    red: 0,
                    green: 0
                },
                history: [],
                timer: 20,
                smallTimer: 0,
                started: 0,
                lastColor: null,
                interval: null,
            }
        },
        mounted() {
            this.$root.isLoading = true;
            this.getBets();
        },
        methods: {
            getBets() {
                this.$root.axios.post('/wheel/get')
                    .then(res => {
                        const data = res.data;

                        this.$root.isLoading = false;
                        this.bets.black = data.players.black;
                        this.bets.yellow = data.players.yellow;
                        this.bets.red = data.players.red;
                        this.bets.green = data.players.green;

                        this.bank.black = data.bank.black;
                        this.bank.yellow = data.bank.yellow;
                        this.bank.red = data.bank.red;
                        this.bank.green = data.bank.green;
                        this.history = data.history;
                    })
                    .catch(e => {

                    })
            },
            createBet(color) {
                this.errors.show = false;

                this.bet = this.bet.toString();

                this.bet = this.bet.replace(/[,]/g, '.').replace(/[^\d,.]*/g, '').replace(/([,.])[,.]+/g, '$1').replace(/^[^\d]*(\d+([.,]\d{0,2})?).*$/g, '$1');
               
                this.$root.axios.post('/wheel/bet/' + color, {
                    token: this.$cookie.get('token'),
                    bet: this.bet
                })
                    .then(res => {
                        const data = res.data;
                        if(data.error) {
                            this.errors = {
                                show: true,
                                message: data.message
                            };
                        } else {
                            this.$root.user.balance = data.balance;
                        }
                    })
                    .catch(e => {

                    })
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
        },
        watch: {
            history: function () {
                this.lastColor = this.history[0];
            },
            smallTimer: function (value, oldValue) {
                if (value <= 0) return;
                if (++value == oldValue || oldValue <= 0) setTimeout(() => --this.smallTimer, 1000/10);
            }
        },
        sockets: {
            add_wheel(data) {
                this.bets.black = data.players.black;
                // this.bets.yellow = data.players.yellow;
                this.bets.red = data.players.red;
                this.bets.green = data.players.green;
                this.bank.black = data.color.black;
                // this.bank.yellow = data.color.yellow;
                this.bank.red = data.color.red;
                this.bank.green = data.color.green;
            },
            wheel_clear(data) {
                this.bets.black = [];
                this.bets.yellow = [];
                this.bets.red = [];
                this.bets.green = [];

                this.bank.black = 0;
                this.bank.yellow = 0;
                this.bank.red = 0;
                this.bank.green = 0;

                this.timer = 20;
                this.started = 0;

                if (this.history.length >= 25) {
                    this.history.pop();
                }
                this.history.unshift(data.last.data);
                this.updateBalance();
            },
            wheel_roll(data) {
                //$('.info__text').html('Прокрутка');
                if(this.started == 0) {
                    $('.game-roller').css({
                        transition: 'all 0s cubic-bezier(0.15, 0.02, 0.22, 1)',
                        transform: 'rotate(0deg)'
                    });
                    setTimeout(() => {
                        $('.game-roller').css({
                            transition: 'all 7s cubic-bezier(0.15, 0.02, 0.22, 1)',
                            transform: 'rotate('+data.roll.data+'deg)'
                        });
                    }, 100);
                    this.started += 1;
                }
                this.timer = 0;
            },
            wheel_start(data) {
                //$('.info__text').html('До начала игры');

                if(data.timer <= 19 && data.timer >= 0) {
                    this.smallTimer = 9;
                }
                this.timer = data.timer;
            }
        }
    }
</script>

<style scoped>
.history__black {
    height: 24px !important;
}
    .user > .user-name {
        display: block;
    }

 .sizesq{
             margin-bottom: -11px;
     }
     .sizes{
             margin-bottom: -11px;
     }

    @media(max-width: 992px) {
        .game-roller {
            width: 290px;
            height: 290px;
        }
        .wheelSide {
            order: 0;
        }
        .betSide {
            order: 1;
        }
     /* .sizes{
         margin-left:5px;
     } */
     /* .sizesq{
         margin-bottom: -11px;
     
      .sizesw{
         margin-left:28px;
     } */
    
    }
    .history__green {
        background: rgb(2, 190, 122); height: 34px;
    }
    .history__red {
        background: rgb(242, 78, 78); height: 24px;
    }
    .history__yellow {
        background: rgb(242, 157, 78); height: 18px;
    }
    .history__black {
        background: var(--black-2x); height: 12px;
    }
    .coin-mini {
        width: 24px;
    }
    .wheel--x2, .wheel--x3, .wheel--x5, .wheel--x50 {
        padding: 4px;
        background: none;
        margin-bottom: 20px;
        font-weight: 600;
        font-size: 0.95rem;
        border-width: 2px;
    }
    .wheel--x2 {
        border-color: var(--x2)!important;
        color: var(--x2);
    }
    .wheel--x3 {
        border-color: var(--x3)!important;
        color: var(--x3);
    }
    .wheel--x5 {
        border-color: var(--x5)!important;
        color: var(--x5);
    }
    .wheel--x50 {
        border-color: var(--x50)!important;
        color: var(--x50);
    }
    .wheel--x2:hover {
        background: var(--x2);
        color: #fff;
    }
    .wheel--x3:hover {
        background: var(--x3);
        color: #fff;
    }
    .wheel--x5:hover {
        background: var(--x5);
        color: #fff;
    }
    .wheel--x50:hover {
        background: var(--x50);
        color: #fff;
    }
    .base-card-cont {
        border-radius: 20px;
        background: #fff;
    }

    .user > .avatar {
        position: relative;
        width: 32px;
        border-radius: 0px;
        height: 32px;
    }
    .user > .avatar > .rounded-circle {
        border-radius: 5px!important;
    }
    .user-name {
        color: #a9a9a9;
    }
    .user-name, .bet, .x-rate, .amount-cont, .info__text, .timer__value {
        font-weight: 600;
        font-family: Exo\ 2,-apple-system,BlinkMacSystemFont,Segoe UI,Helvetica,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol;
    }
    .x-rate, .amount-cont {
        font-size: 15px;
    }

    @media(max-width: 360px) {
        .game-wrapper-cont {
            margin:2.5rem -15px 0 -15px
        }
    }

    .bet-list-cont {
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        width: 100%;
        padding: 0;
        margin-bottom: 0;
        font-weight: 600
    }

    @media(max-width: 1200px) {
        .bet-list-cont {
            min-height:28.5rem
        }
    }

    @media(max-width: 600px) {
        .bet-list-cont {
            min-height:30rem
        }
    }

    .coin-middle {
        height: 1.5rem
    }

    @media(max-width: 360px) {
        .coin-middle {
            width:1.25rem;
            height: 1.25rem
        }
    }

    .head {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        padding: 1.312rem 1.05rem;
        font-size: 1.25rem;
        color: var(--white);
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        border-radius: 20px 20px 0 0
    }

    .head--x2 {
        background-color: var(--x2)
    }

    .head--x3 {
        background-color: var(--x3)
    }

    .head--x5 {
        background-color: var(--x5)
    }

    .head--x50 {
        background-color: var(--x50)
    }

    @media(max-width: 360px) {
        .head {
            padding:1.25rem .75rem;
            font-size: 1rem
        }
    }

    .crown-winner {
        position: absolute;
        top: 0;
        left: 0;
        width: 60px;
        height: 60px;
        color: var(--crown);
        -webkit-transform: translate(-40%,-55%) rotate(10deg);
        transform: translate(-40%,-55%) rotate(10deg);
        -webkit-animation: crown-animation 1s;
        animation: crown-animation 1s;
        will-change: transform,opacity
    }

    @media(max-width: 1441px) {
        .crown-winner {
            width:50px;
            height: 50px
        }
    }

    @media(max-width: 410px) {
        .crown-winner {
            top:5px;
            left: 5px;
            width: 40px;
            height: 40px
        }
    }

    @-webkit-keyframes crown-animation {
        0% {
            opacity: .25;
            -webkit-transform: translate(-40%,-55%) scale3d(2.5,2.5,2.5) rotate(10deg);
            transform: translate(-40%,-55%) scale3d(2.5,2.5,2.5) rotate(10deg)
        }

        to {
            opacity: 1;
            -webkit-transform: translate(-40%,-55%) scaleX(1) rotate(10deg);
            transform: translate(-40%,-55%) scaleX(1) rotate(10deg)
        }
    }

    @keyframes crown-animation {
        0% {
            opacity: .25;
            -webkit-transform: translate(-40%,-55%) scale3d(2.5,2.5,2.5) rotate(10deg);
            transform: translate(-40%,-55%) scale3d(2.5,2.5,2.5) rotate(10deg)
        }

        to {
            opacity: 1;
            -webkit-transform: translate(-40%,-55%) scaleX(1) rotate(10deg);
            transform: translate(-40%,-55%) scaleX(1) rotate(10deg)
        }
    }

    .user {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center
    }

    .user-bets {
        -webkit-box-sizing: content-box;
        box-sizing: content-box;
        padding: 0 1rem 1rem 1.2rem
    }

    @media(max-width: 1600px)and (min-width:1401px) {
        .user-bets {
            padding:0 1rem 1rem .55rem
        }
    }

    .user-bet {
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        padding-top: 1.25rem;
        overflow: visible
    }

    .user-bet.bet-move {
        -webkit-transition: -webkit-transform .3s;
        transition: -webkit-transform .3s;
        transition: transform .3s;
        transition: transform .3s,-webkit-transform .3s
    }

    .user-bet:last-of-type:after {
        background-color: transparent!important
    }

    .user-bet.last-own-bet:after {
        position: absolute;
        bottom: -.75rem;
        height: 2px;
        content: "";
        background-color: var(--primary);
        -webkit-animation: separeateOwnBets .3s forwards;
        animation: separeateOwnBets .3s forwards;
        -webkit-animation-delay: .5s;
        animation-delay: .5s;
        will-change: width
    }

    @-webkit-keyframes separeateOwnBets {
        0% {
            width: 0
        }

        to {
            width: 100%
        }
    }

    @keyframes separeateOwnBets {
        0% {
            width: 0
        }

        to {
            width: 100%
        }
    }

    .bet-cont {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        margin-left: .8rem
    }

    .bet {
        margin-left: .5rem
    }

    .amount-cont {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center
    }

    .amount {
        margin-left: .5rem
    }

    @media(max-width: 360px) {
        .amount {
            margin-left:.25rem
        }
    }

    .user-name {
        max-width: 8.25rem;
        margin-left: .85rem;
        overflow: hidden;
        font-size: .875rem;
        color: var(--text-fourth);
        text-overflow: ellipsis;
        white-space: nowrap
    }

    @media (max-width: 768px)
    .btn-la-mob {
    font-size: 13px !important;
    width: 58px;
}

    @media(max-width: 1900px) {
        .user-name {
            max-width:7rem
        }
    }

    @media(max-width: 1599px) {
        .user-name {
            max-width:6rem
        }
    }

    @media(max-width: 1400px) {
        .user-name {
            max-width:10rem
        }
    }

    @media(max-width: 600px) {
        .user-name {
            display:none
        }
    }

    @media(max-width: 1200px) {
        .user-name {
            font-size:12px
        }
    }

    .info-text {
        --border-color: var(--gray-1);
        width: 100%;
        padding: .75em .5em;
        margin-top: auto;
        margin-bottom: 0;
        font-size: 1.125rem;
        font-weight: 600;
        text-align: center;
        border-top: 1px solid var(--border-color)
    }

    .info-text.dark {
        --border-color: var(--black-7)
    }

    @media(max-width: 600px) {
        .info-text {
            display:-webkit-inline-box;
            display: -ms-inline-flexbox;
            display: inline-flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            text-align: center
        }
    }

    .info-text--no-bets {
        opacity: .7;
        margin-top: 0;
        border: none;
        font-weight: 300;
    }

    .info-text-coin__img {
        position: relative;
        top: 2px;
        width: 1rem;
        height: 1rem;
        -o-object-fit: contain;
        object-fit: contain
    }

    @media(max-width: 600px) {
        .info-text-coin__img {
            margin:0
        }
    }

    @media(max-width: 600px) {
        .info-text-coin {
            width:100%;
            margin-top: 5px
        }
    }

    .games-history {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        min-height: 34px;
        margin-top: 2rem
    }

    .line {
        width: 4px;
        margin: 0 2px;
        border-radius: 2px
    }

    .wrapper {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
    }

    @media(max-width: 900px) {
        .wrapper {
            padding:75px 20px 55px
        }
    }

    @media(max-width: 735px) {
        .wrapper {
            padding:60px 20px 45px
        }
    }

    @media(max-width: 560px) {
        .wrapper {
            padding:45px 20px 45px;
        }
        .form-group.col-8.col-md-4 {
            max-width: 100% !important;
            flex: 100% !important;
        }
    }

    .game-roller {
        position: absolute;
        width: 305px;
        height: 305px;
        will-change: transform;
        -webkit-transition-timing-function: cubic-bezier(.51,.18,.22,1);
        transition-timing-function: cubic-bezier(.51,.18,.22,1)
    }

    @media(max-width: 760px) {
        .game-roller {
            width:340px;
            height: 340px
        }
    }

    @media(max-width: 400px) {
        .game-roller {
            width:265px;
            height: 265px
        }
    }

    .game-roller__x2 {
        fill: var(--x2)
    }

    .game-roller__x3 {
        fill: var(--x3)
    }

    .game-roller__x5 {
        fill: var(--x5)
    }

    .game-roller__x50 {
        fill: var(--x50)
    }

    .info {
        --border-color: var(--gray-2);
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        width: 270px;
        height: 270px;
        padding: 20px;
        border: 1px solid var(--border-color);
        border-radius: 50%
    }

    .info.dark {
        --border-color: var(--black-4)
    }

    @media(max-width: 760px) {
        .info {
            width:280px;
            height: 280px
        }
    }

    @media(max-width: 400px) {
        .info {
            width:240px;
            height: 240px
        }
    }

    .info__text {
        font-weight: 600;
        color: var(--text-info)
    }

    .pointer {
        position: absolute;
        top: 1px;
        left: 50%;
        width: 0;
        height: 0;
        border-color: transparent transparent var(--primary) transparent;
        border-style: solid;
        border-width: 0 10px 20px 10px;
        -webkit-transition: border-color .3s;
        transition: border-color .3s;
        -webkit-transform: translateX(-10px);
        transform: translateX(-10px)
    }

    .pointer.b {
        border-color: transparent transparent var(--x2) transparent
    }

    .pointer.y {
        border-color: transparent transparent var(--x3) transparent
    }

    .pointer.r {
        border-color: transparent transparent var(--x5) transparent
    }

    .pointer.g {
        border-color: transparent transparent var(--x50) transparent
    }

    @media(max-width: 800px) {
        .pointer {
            top: -3px;
            border-width: 0 7px 14px 7px;
            -webkit-transform: translateX(-7px);
            transform: translateX(-7px)
        }
    }

    .timer {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: start;
        -ms-flex-align: start;
        align-items: flex-start;
        margin-top: 10px;
        color: #c4c4c4;
        text-align: center;
    }

    .timer__icon {
        width: 24px;
        height: 24px;
        margin-right: 5px;
        margin-top: -0.1px;
        color: #ffb400;
    }

    .timer__value {
        margin-top: -5.9px;
        font-size: 1.4rem;
        font-weight: 500;
        color: #716f6f;
    }
    .svg-icon {
        fill: currentColor;
    }
    .game-history-lines {
        --black-2x: #716f6f
    }

    .dark-lines {
        --black-2x: #716f6f
    }

    .bid-block-cont {
        z-index: 0;
        width: 373px;
        max-width: 373px;
        height: 100%;
        padding: 2.3rem 2.062rem 3rem 1.812rem
    }

    .name {
        margin-bottom: 1.2rem;
        font-size: 20px;
        font-weight: 600
    }

    .input-container {
        position: relative;
        margin-bottom: 1rem
    }

    .input {
        width: 100%
    }

    .bid-cont {
        margin-bottom: 1rem
    }

    .bid-cont,.double-btns {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between
    }

    .mini-btn-double {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        width: calc(25% - .5rem);
        height: 2.312rem;
        font-size: 1.375rem;
        font-weight: 600;
        color: var(--gray-5);
        color: var(--color);
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background: none;
        border: solid 2px var(--color);
        border-radius: 5px;
        outline: none;
        -webkit-transition: all .3s ease;
        transition: all .3s ease
    }

    .mini-btn-double--x2 {
        --color: var(--x2)
    }

    .mini-btn-double--x3 {
        --color: var(--x3)
    }

    .mini-btn-double--x5 {
        --color: var(--x5)
    }

    .mini-btn-double--x50 {
        --color: var(--x50)
    }

    .mini-btn-double:focus,.mini-btn-double:hover {
        color: var(--white);
        background: var(--color)
    }

    .button {
        width: 100%;
        margin-top: 1rem
    }

    .coin {
        width: 2.4rem;
        -webkit-transform: rotate(-25deg);
        transform: rotate(-25deg)
    }

    .text {
        margin-bottom: 1.2rem;
        font-size: 20px;
        font-weight: 600
    }

    .bet-text {
        margin-top: .9rem
    }

    .my-rate {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        font-size: 1.25rem;
        font-weight: 600
    }

    .dragon-page {
        color: var(--gray-2)
    }

    @media(max-width: 450px) {
        .bid-block-cont {
            width:100%;
            padding: 2.3rem 1.5rem 2.5rem 1.5rem
        }

        .name,.text {
            text-align: center
        }

        .my-rate-cont {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center
        }
    }

    .wrapper-cont {
        padding-bottom: 60px
    }

    @media(max-width: 600px) {
        .wrapper-cont {
            padding-bottom:10px
        }
    }

    .bets {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        margin-top: .5rem
    }

    @media(min-width: 1401px) {
        .bets {
            -webkit-box-align:start;
            -ms-flex-align: start;
            align-items: flex-start;
            min-height: 610px
        }
    }

    .bet-list {
        width: calc(33% - .5rem)
    }

    @media(max-width: 1400px) {
        .bet-list {
            width:calc(50% - .5rem);
            margin-top: 2rem
        }
    }

    @media(max-width: 400px) {
        .bet-list {
            width:calc(50% + 4px);
            margin-right: -8px;
            margin-left: -8px
        }
    }
</style>
