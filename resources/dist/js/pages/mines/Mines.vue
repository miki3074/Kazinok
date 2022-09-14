<template>
    <div>
        <div class="card pd-b-15">
            <div class="card-header pd-y-20 d-md-flex align-items-center justify-content-between d-none d-sm-block">
                <h4 class="mg-b-0">Mines1111</h4>
            </div>
            <div class="row row-xs pd-20 mob-min">
                <div class="col-xs-12 col-lg-5">
                    <div class="tab-content ">
                        <div class="tab-pane fade show active " id="handMines" role="tabpanel"
                             aria-labelledby="home-tab5">
                            <div class=" d-none d-sm-block" :class="[(game.active || disableBtn) ? 'disabled' : '']" id="mines">
                                <h6 class="mg-b-15 ">Количество бомб:</h6>
                                <input class="js-range-slider mg-t-10 col-xs-6 irs-hidden-input" tabindex="-1" :disabled="(game.active || disableBtn)"
                                       readonly="">
                                <h6 class="mg-t-20">Размер игры:</h6>
                                <div
                                    class=" d-flex flex-row justify-content-center mg-b-15 mg-t-15 bg-gray-200 rounded-5 ">
                                    <div class="pd-10 bg-gray-200 "
                                         style="border-top-left-radius:5px; border-bottom-left-radius:5px">
                                        <button class="btn btn-secondary btn-icon tx-11 mg-t-3" @click="typeBet('max')">
                                            MAX
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-icon tx-11 mg-t-3"
                                                @click="typeBet('min')">MIN
                                        </button>
                                    </div>
                                    <div class="pd-10 bg-gray-200"><input v-model="bet" @keyup="validateBet"
                                                                          :disabled="(game.active || disableBtn)"
                                                                          id="minesAmount"
                                                                          style="max-width:133px"
                                                                          class="form-control justify-content-center align-self-center text-center "
                                                                          autocomplete="off" placeholder="Сумма"></div>
                                    <div class="pd-10 bg-gray-200"
                                         style="border-top-right-radius:5px; border-bottom-right-radius:5px">
                                        <button type="button" class="btn btn-secondary btn-icon tx-11 mg-t-3"
                                                @click="typeBet('x2')">x2
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-icon tx-11 mg-t-3"
                                                @click="typeBet('/2')">/2
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="d-none d-sm-block minesPanel">
                                <a id="buttonStartMines" href="#" @click="startGameSocket" v-if="!game.active"
                                   class="btn btn-secondary btn-block pd-t-10 pd-b-10 tx-15 " style="">Начать игру</a>
                                <a id="buttonFinishMines" href="#" v-else-if="game.active && game.active_path <= 1"
                                   class="btn btn-secondary btn-block pd-t-10 pd-b-10 tx-15 "
                                   style="max-width: 68%"
                                   :class="[game.active ? 'disabled': '']">Выберите ячейку
                                </a>
                                <a id="buttonFinishMines" href="#" v-else-if="game.active_path > 1 && game.win && !cashingOut"
                                   class="btn btn-secondary btn-block pd-t-10 pd-b-10 tx-15 "
                                   style="max-width: 68%;"
                                   :class="[(loadBomb > 0 || disableBtnCashout) ? 'disabled': '']" @click="cashOut">Забрать <u>{{ (bet *
                                    getActiveCoef()).toFixed(2)}}</u>
                                </a>
                                <a id="buttonFinishMines" href="#" v-else-if="game.active_path > 1 && game.win && cashingOut"
                                   class="btn btn-secondary btn-block pd-t-10 pd-b-10 tx-15 "
                                   style="max-width: 68%;"
                                   :class="[(loadBomb > 0 || disableBtnCashout) ? 'disabled': '']">Забрать <u>{{ (bet *
                                    getActiveCoef()).toFixed(2)}}</u>
                                </a>
                                <a id="buttonAutoSelect" href="#" v-if="game.active"
                                   class="btn btn-secondary btn-block pd-t-10 pd-b-10 tx-15 "
                                   style="max-width: 30%;"
                                   :class="[(loadBomb > 0 || disableBtnCashout) ? 'disabled': '']" @click="autoSelect">Авто
                                </a>
                            </div>
                            <button id="errorMines"
                                    style="padding: 11px; pointer-events: none; margin-top: 15px;" v-if="errors.show"
                                    class="btn btn-block tx-medium btn-la-mob bg-danger-dice tx-white bd-0 btn-sel-d mg-b-15 ">
                                {{ errors.message }}
                            </button>
                        </div>
                        <div id="minesHashBlock" v-if="game.active">
                            <div class="divider-text hash-mob mg-t-25">Hash игры</div>
                            <div class="tx-color-03 tx-thin text-center hash-mob" id="minesHash">{{ game.hash }}</div>
                        </div>
                        <div v-else-if="!game.active && lastLoseGame.active">
                            <div id="minesHashBlock">
                                <div class="divider-text hash-mob mg-t-25">Hash игры</div>
                                <div class="tx-color-03 tx-thin text-center hash-mob" id="minesHash">{{
                                    lastLoseGame.hash }}
                                </div>
                            </div>
                            <div id="checkMines" style="">
                                <div class="justify-content-center mg-t-20 d-none d-sm-flex">
                                    <button id="checkBet" class="btn btn-outline-secondary justify-content-center" @click="$root.$emit('showGame', lastLoseGame.id)" data-toggle="modal">Проверить игру
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-lg-7 ">
                    <div id="mbl" :class="[!game.active ? 'disabled' : '']">
                        <div class="d-flex justify-content-center" v-for="blockKey in 5"
                             :class="[blockKey > 1 ? 'mg-t-10' : '']">
                            <div v-for="bombKey in 5">
                                <div
                                    class="wd-65 ht-65 bg-gray-100 bd bd-1 bd-success tx-success text-center rounded-lg"
                                    :class="[bombKey > 1 ? 'mg-l-10' : '']"
                                    style="background:#0cc95f69;opacity: 0.25"
                                    v-if="game.bombs[setCounterBombKeys(`${blockKey}_${bombKey}`)] === 0 && typeof game.selected_bombs[setCounterBombKeys(`${blockKey}_${bombKey}`)] === 'undefined'"
                                >
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                         stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                         class="css-i6dzq1 mg-t-20 ">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </div>
                                <div
                                    class="wd-65 ht-65 bg-gray-100 bd bd-1 rounded-lg tx-danger text-center bd-danger"
                                    :class="[bombKey > 1 ? 'mg-l-10' : '']"
                                    style="background:#dc354575;opacity: 0.25"
                                    v-else-if="game.bombs[setCounterBombKeys(`${blockKey}_${bombKey}`)] === 1 && typeof game.selected_bombs[setCounterBombKeys(`${blockKey}_${bombKey}`)] === 'undefined'"
                                >
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                         stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                         class="css-i6dzq1 mg-t-20">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </div>
                                <div class="wd-65 ht-65 bg-gray-100 bd bd-1 rounded-lg mines-sq text-center"
                                     :class="[bombKey > 1 ? 'mg-l-10' : '']"
                                     v-else-if="typeof game.selected_bombs[setCounterBombKeys(`${blockKey}_${bombKey}`)] === 'undefined'"
                                     @click="setBomb(setCounterBombKeys(`${blockKey}_${bombKey}`))"
                                >
                                    <div v-if="loadBomb === setCounterBombKeys(`${blockKey}_${bombKey}`)" role="status"
                                         style="color:#dbe0e9" class="spinner-grow-sm spinner-grow mg-t-25">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <div
                                    class="wd-65 ht-65 bg-gray-100 bd bd-1  bd-success tx-success text-center rounded-lg"
                                    :class="[bombKey > 1 ? 'mg-l-10' : '']"
                                    style="background:#0cc95f69;"
                                    v-else-if="game.selected_bombs[setCounterBombKeys(`${blockKey}_${bombKey}`)] === 1"
                                >
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                         stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                         class="css-i6dzq1 mg-t-20 ">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </div>
                                <div
                                    class="wd-65 ht-65 bg-gray-100 bd bd-1 rounded-lg tx-danger text-center bd-danger"
                                    :class="[bombKey > 1 ? 'mg-l-10' : '']"
                                    style="background:#dc354575"
                                    v-else-if="game.selected_bombs[setCounterBombKeys(`${blockKey}_${bombKey}`)] === 0"
                                >
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                         stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                         class="css-i6dzq1 mg-t-20">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center ">
                        <ul class="steps mg-t-20 ">
                            <svg href="#carousel" data-slide="prev" viewBox="0 0 24 24" width="24" height="24"
                                 stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"
                                 stroke-linejoin="round" class="carousel-control-prev tx-gray-400 css-i6dzq1 mg-t-20 ">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            <div id="carousel" class="carousel slide " data-ride="carousel" data-interval="false">
                                <div class="carousel-inner pd-t-4 pd-b-2" id="minesRate">
                                    <div class="carousel-item justify-content-center"
                                         v-for="(totalMines, key) in mines[bomb - 2]"
                                         :class="[((key === 0 && !game.active && !lastLoseGame.active) || (Math.ceil(game.active_path / 2) === key + 1) || ((key === mines[bomb - 2].length - 1) && game.active_path === (25 - (bomb - 1)))) ? 'active' : '']">
                                        <li class="step-item bd bd-1 pd-10 rounded-5"
                                            :class="[(game.active_path === ((key * 2) + (counter + 1)) && game.win && !changeBombs) ? 'bd-primary' : '', (((key * 2) + (counter + 1)) < game.active_path && !changeBombs) ? 'bd-success' : '', (game.active_path === ((key * 2) + (counter + 1)) && !game.win && !changeBombs) ? 'lose-bl' : '']"
                                            v-for="(mine, counter) in totalMines"><span
                                            class="st-bl"
                                            style="position:absolute; margin-top: -18px; margin-left: 4px; font-size: 10px; background:#fff; padding:2px 7px;">Шаг {{ (key * 2) + (counter + 1) }}</span><a
                                            class="step-link">
                                            <div style="margin-left:0;width:110px;align-items: center;"><span
                                                class="step-title" style="">{{ (bet * mine).toFixed(2) }}</span><span
                                                class="step-desc">x{{ mine }}</span></div>
                                        </a></li>
                                    </div>
                                </div>
                            </div>
                            <svg href="#carousel" data-slide="next" viewBox="0 0 24 24" width="24" height="24"
                                 stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"
                                 stroke-linejoin="round" class="carousel-control-next tx-gray-400 css-i6dzq1 mg-t-20">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </ul>
                    </div>
                    <div class="col-12  d-block d-sm-none" style="padding:0">
                        <div class="row row-xs mg-b-10" id="minesMobile" :class="[game.active ? 'disabled' : '']">
                            <div class="col-6">
                                <span class="tx-11 ">Бомбы</span>
                                <select class="custom-select text-center tx-20  mg-t-5" id="countBombMobile" @change="getMinesRateMobile(bomb)" v-model="bomb"
                                        style="border-bottom-right-radius: 0;border-bottom-left-radius: 0;">
                                    <option v-for="i in 23" :value="i + 1" :selected="bomb === (i + 1)">{{ i + 1 }}
                                    </option>
                                </select>
                                <div style="margin-top: -1px; "
                                     class="btn-group tx-rubik d-flex justify-content-center ">
                                    <button
                                        @click="getMinesRateMobile(2)"
                                        style="border-top-left-radius: 0; padding: 0;"
                                        class="tx-gray-600 btn btn-xs btn-white tx-13 mb-mines">2
                                    </button>
                                    <button
                                        @click="getMinesRateMobile(5)"
                                        style="border-top-right-radius: 0; padding: 0;"
                                        class="tx-gray-600 btn btn-xs btn-white tx-13 mb-mines">5
                                    </button>
                                    <button
                                        @click="getMinesRateMobile(14)"
                                        style="border-top-right-radius: 0; padding: 0;"
                                        class="tx-gray-600 btn btn-xs btn-white tx-13 mb-mines">14
                                    </button>
                                    <button
                                        @click="getMinesRateMobile(21)"
                                        style="border-top-right-radius: 0; padding: 4px 0;"
                                        class="tx-gray-600 btn btn-xs btn-white tx-13 mb-mines">21
                                    </button>
                                </div>
                            </div>
                            <div class="col-6">
                                <span class="tx-11 ">Размер игры</span>
                                <div class="input-group mg-t-5">
                                    <input value="1" id="minesAmountMobile"
                                           class="form-control tx-20 justify-content-center align-self-center text-center "
                                           autocomplete="off" placeholder="Сумма" v-model="bet" @keyup="validateBet"
                                           style="border-bottom-right-radius: 0;border-bottom-left-radius: 0">
                                </div>
                                <div style="margin-top: -1px; "
                                     class="btn-group tx-rubik d-flex justify-content-center ">
                                    <button
                                        @click="typeBet('max')"
                                        style="border-top-left-radius: 0; padding: 0;"
                                        class="tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines">Max
                                    </button>
                                    <button
                                        @click="typeBet('min')"
                                        style="border-top-right-radius: 0; padding: 0;"
                                        class="tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines">Min
                                    </button>
                                    <button
                                        @click="typeBet('x2')"
                                        style="border-top-right-radius: 0; padding: 0;"
                                        class="tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines">x2
                                    </button>
                                    <button
                                        @click="typeBet('/2')"
                                        style="border-top-right-radius: 0; padding: 4px 0;"
                                        class="tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines">/2
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <a id="buttonStartMinesMobile" v-if="!game.active" href="#" @click="startGameSocket"
                            class="btn btn-secondary btn-block  tx-12 " style=""
                            >Начать игру</a>
                            <a id="buttonFinishMinesMobile" v-else-if="game.active && game.active_path <= 1" href="#"
                                style="max-width: 68%"
                            :class="[game.active ? 'disabled': '']" class="btn btn-secondary btn-block   tx-12 ">
                                Выберите ячейку
                            </a>
                            <a id="buttonFinishMinesMobiles" v-else-if="game.active_path > 1 && game.win && !cashingOut" href="#"  @click="cashOut"
                                style="max-width: 68%;padding: 10px;margin-top: 13px!important;margin-bottom: 5px;"
                            :class="[(loadBomb > 0 || disableBtnCashout) ? 'disabled': '']" class="btn btn-secondary btn-block   tx-12 ">Забрать <u>{{ (bet *
                                getActiveCoef()).toFixed(2)}}</u>
                            </a>
                            <a id="buttonAutoSelect" href="#" v-if="game.active"
                                class="btn btn-secondary btn-block tx-12"
                                style="max-width: 30%;padding: 10px;margin-top: 13px!important;margin-bottom: 5px;"
                                :class="[(loadBomb > 0 || disableBtnCashout) ? 'disabled': '']" @click="autoSelect">Авто
                            </a>
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

    export default {
        components: {
            AllHistory
        },
        data() {
            return {
                bet: 1,
                bomb: 3,
                mines: [
                    [[1.06, 1.15], [1.26, 1.39], [1.53, 1.70], [1.90, 2.14], [2.42, 2.77], [3.20, 3.73], [4.41, 5.29], [6.47, 8.08], [10.39, 13.86], [19.40, 29.10], [48.50, 96], [271]],
                    [[1.11, 1.26], [1.45, 1.68], [1.96, 2.30], [2.74, 3.28], [3.99, 4.90], [6.13, 7.80], [10.14, 13.52], [18.59, 26.56], [39.84, 63.74], [109.55, 223.10], [517.75, 2111]],
                    [[1.15, 1.39], [1.68, 2.05], [2.53, 3.16], [4.01, 5.16], [6.74, 8.99], [12.26, 17.16], [24.79, 37.18], [58.43, 97.39], [162.29, 330.59], [718.03, 2224.10], [9270.50]],
                    [[1.21, 1.53], [1.96, 2.53], [3.33, 4.43], [6.01, 8.33], [11.80, 17.16], [25.74, 40.04], [65.07, 111.55], [201.51, 379.02], [820.29, 2154.10], [7289.35, 38536.10]],
                    [[1.28, 1.70], [2.30, 3.16], [4.43, 6.33], [9.25, 13.88], [21.46, 34.32], [57.20, 100.11], [175.92, 321.83], [708.03, 1845.08], [5235.25, 20541], [131787]],
                    [[1.35, 1.90], [2.74, 4.01], [6.01, 9.25], [14.65, 23.98], [40.76, 72.46], [135.86, 221.73], [538.73, 1112.97], [2985.66, 10952.20], [39284.88, 266279]],
                    [[1.43, 2.14], [3.28, 5.16], [8.33, 13.88], [23.98, 43.16], [81.52, 161.04], [329.36, 775.17], [1919.45, 5458.35], [20313.95, 76569.75], [649127.75]],
                    [[1.51, 2.42], [3.99, 6.74], [11.80, 21.46], [40.76, 81.52], [163.22, 355.94], [849.86, 2371.59], [7607.66, 26030.65], [108168.57, 981685.75]],
                    [[1.62, 2.77], [4.90, 8.99], [17.16, 34.32], [72.46, 163.04], [355.94, 915.85], [2767.53, 9086.35], [32040.87, 218245.20], [1370697.20]],
                    [[1.74, 3.20], [6.13, 12.26], [25.74, 57.20], [135.86, 309.36], [889.86, 2867.53], [9878.23, 35431.77], [190306.50, 2023678]],
                    [[1.86, 3.73], [7.80, 17.16], [40.04, 100.11], [251.73, 715.17], [2171.59, 9086.35], [35431.77, 188022.39], [2344291]],
                    [[2.02, 4.41], [10.14, 24.79], [65.07, 185.92], [528.73, 1919.45], [7007.66, 38040.87], [180649.64, 2344291]],
                    [[2.20, 5.29], [13.52, 37.18], [111.55, 331.83], [1112.97, 5358.35], [26030.65, 198245.20], [1823678]],
                    [[2.42, 6.47], [18.59, 58.43], [200.51, 718.03], [3385.66, 18313.95], [98168.57, 1170697.20]],
                    [[2.70, 8.08], [26.56, 97.39], [379.02, 1845.08], [10952.20, 56569.75], [981685.75]],
                    [[3.03, 10.39], [39.84, 171.29], [720.29, 5135.25], [38284.88, 449127.75]],
                    [[3.46, 13.86], [63.74, 320.59], [2054.10, 19541], [166279]],
                    [[4.04, 19.40], [107.55, 718.03], [6589.35, 31787]],
                    [[4.85, 29.10], [203.10, 2154.10], [11536.10]],
                    [[6.06, 48.5], [457.75, 9270.50]],
                    [[8.08, 97.0], [1931]],
                    [[12.12, 271]],
                    [[24.25]]
                ],
                errors: {
                    show: false,
                    message: ''
                },
                disableBtn: false,
                cashingOut: false,
                game: {
                    active: false,
                    hash: '',
                    selected_bombs: [],
                    active_path: 0,
                    bombs: [],
                    win: true
                },
                counterBombsBlock: [],
                counterBombs: 0,
                disableBtnBomb: false,
                loadBomb: 0,
                lastLoseGame: {
                    active: false,
                    hash: '',
                    id: 0
                },
                changeBombs: false,
                disableBtnCashout: false,
                myGames: [],
                user: null
            }
        },
        mounted() {
            //this.$root.isLoading = true;
            this.getUser();

            $('[data-toggle="tooltip"]').tooltip();

            $(".js-range-slider").ionRangeSlider({
                skin: "round",
                min: 2,
                max: 24,
                from: 3,
                grid: true,
                onChange: data => {
                    this.getMinesRate();
                }
            });

            $(".js-range-slider").addClass("irs-hidden-input");
            //console.log("neRoot " + this.user);
            //console.log("Root " + this.$root.user);
            if (this.$cookie.get('token')) {
                this.getGame();
            } else {
                this.$root.isLoading = false;
            }
        },
        methods: {
            getUser() {
                this.$root.axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$cookie.get('token');

                this.$root.graphql.getUser().then(res => {
                    this.$root.user = res.User;
                    //console.log("neRoot " + this.user);
                    //console.log("Root " + this.$root.user);

                }).catch(error => {
                    this.$cookie.delete('token');
                });
            },
            autoSelect() {
                var notActive = $('.bd-1.mines-sq');
                $(notActive[Math.floor(Math.random()*notActive.length)]).click()
            },
            validateBet() {
                if (this.bet > 1000000) {
                    this.bet = 1000000;
                }

                this.changeBombs = true;
                this.bet = this.bet.toString();
                this.bet = this.bet.replace(/[,]/g, '.').replace(/[^\d,.]*/g, '').replace(/([,.])[,.]+/g, '$1').replace(/^[^\d]*(\d+([.,]\d{0,2})?).*$/g, '$1');
            },
            getMinesRate() {
                if (this.bet > 1000000) {
                    this.bet = 1000000;
                }

                this.changeBombs = true;
                this.bomb = $(".js-range-slider").prop("value");
                $('.carousel-item.active').removeClass('active');
                $('.carousel-item').first().addClass('active');
            },
            getMinesRateMobile(bomb) {
                if (this.bet > 1000000) {
                    this.bet = 1000000;
                }

                this.changeBombs = true;
                this.bomb = bomb;
                $('.carousel-item.active').removeClass('active');
                $('.carousel-item').first().addClass('active');
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
            startGameSocket() {
                if (this.disableBtn || this.game.active) {
                    return;
                }



                this.errors.show = false;
                this.disableBtn = true;
                this.disableBtnBomb = false;
                this.disableBtnCashout = false;

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

                if (this.bomb.length === 0) {
                    this.errors = {
                        show: true,
                        message: 'Укажите кол-во бомб'
                    };

                    this.disableBtn = false;

                    return;
                }

                this.$socket.emit('minesCreate', {
                    bomb: this.bomb,
                    bet: this.bet,
                    id: this.$root.user.id
                }, (data) => {
                    if(!data.success) {
                        this.errors = {
                            show: true,
                            message: data.message
                        };
                    } else {
                        this.$root.user.balance = data.balance;
                        this.lastLoseGame.active = false;

                        this.game = {
                            active: true,
                            hash: data.hash,
                            selected_bombs: [],
                            active_path: 1,
                            bombs: [],
                            win: true
                        };

                        this.changeBombs = false;
                    }
                });
            },
            setBomb(bomb) {
                if (this.disableBtnBomb) {
                    return;
                }

                this.loadBomb = bomb;
                this.disableBtnBomb = true;
                this.errors.show = false;

                //console.log("bet");

                this.$socket.emit('minesBet', {
                    bomb: bomb,
                    id: this.$root.user.id
                }, (data) => {
                    if(!data.success) {
                        this.errors = {
                            show: true,
                            message: data.message
                        };
                    } else {
                        const selectedBombs = JSON.parse(data.selected_bombs);

                        //console.log(data);

                        this.game.selected_bombs = selectedBombs;
                        this.loadBomb = 0;

                        if (data.win) {
                            this.game.active_path = Object.keys(selectedBombs).length + 1;

                            if (data.insta_win) {
                                this.$root.user.balance += data.win_sum;
                                this.endGame(data.history);
                            } else {
                                this.disableBtnBomb = false;
                            }
                        } else {
                            this.game.bombs = JSON.parse(data.bombs);
                            this.game.win = false;
                            this.game.active = false;

                            this.lastLoseGame = {
                                active: true,
                                hash: this.game.hash,
                                id: data.id
                            };

                            this.addMyHistory(data.history);

                            this.disableBtn = false;
                        }
                    }
                });
            },
            setCounterBombKeys(key) {
                if (typeof this.counterBombsBlock[key] === 'undefined') {
                    this.counterBombs++

                    this.counterBombsBlock[key] = this.counterBombs;

                    return this.counterBombsBlock[key];
                } else {
                    return this.counterBombsBlock[key];
                }
            },
            getActiveCoef() {
                let index = 0;
                if ((this.game.active_path - 1) % 2 === 0) index = 1;

                return this.mines[this.bomb - 2][Math.floor((this.game.active_path) / 2) - 1][index];
            },
            cashOut() {
                if (this.disableBtnCashout || this.cashingOut) {
                    return;
                }

                this.disableBtnCashout = true;
                this.cashingOut = true;

                this.$socket.emit('minesCashout', {
                    id: this.$root.user.id
                }, (data) => {
                    if(!data.success) {
                        this.errors = {
                            show: true,
                            message: data.message
                        };
                        this.disableBtnCashout = false;
                        this.cashingOut = false;
                    } else {
                        this.$root.user.balance = data.balance;

                        this.endGame(data.history);

                        this.disableBtnCashout = false;
                        this.cashingOut = false;
                    }
                });
            },
            endGame(game) {
                this.errors = {
                    show: false,
                    message: ''
                };
                this.disableBtn = false;
                this.game = {
                    active: false,
                    hash: '',
                    selected_bombs: [],
                    active_path: 0,
                    bombs: [],
                    win: true
                }
                this.counterBombsBlock = [];
                this.counterBombs = 0;
                this.disableBtnBomb = false;
                this.loadBomb = 0;
                this.lastLoseGame = {
                    active: false,
                    hash: '',
                    id: 0
                };
                this.changeBombs = false;
                this.addMyHistory(game);
            },
            getGame() {
                this.$root.graphql.getMinesGame().then(res => {
                    const game = res.MinesGame;

                    if (game.success) {
                        this.disableBtn = true;
                        this.lastLoseGame.active = false;
                        this.changeBombs = false;

                        this.bet = game.bet;
                        this.bomb = game.bomb;

                        $(".js-range-slider").data('ionRangeSlider').update({from: this.bomb});

                        this.game = {
                            active: true,
                            hash: game.hash,
                            selected_bombs: JSON.parse(game.selected_bombs),
                            active_path: game.active_path,
                            bombs: [],
                            win: true
                        }
                    }
                    this.$root.isLoading = false;
                }).catch(error => {
                    error = this.$root.graphql.getError(error);

                    this.errors = {
                        show: true,
                        message: error.debugMessage
                    };
                });
            },
            getGame1() {
                console.log("neRoot " + this.user);
                console.log("Root " + this.$root.user);
                this.$socket.emit('minesGet', {
                    id: this.$root.user.id
                }, (data) => {
                    if (data.success) {
                        this.disableBtn = true;
                        this.lastLoseGame.active = false;
                        this.changeBombs = false;

                        this.bet = data.bet;
                        this.bomb = data.bomb;

                        $(".js-range-slider").data('ionRangeSlider').update({from: this.bomb});

                        this.game = {
                            active: true,
                            hash: data.hash,
                            selected_bombs: JSON.parse(data.selected_bombs),
                            active_path: data.active_path,
                            bombs: [],
                            win: true
                        }
                    }
                    this.$root.isLoading = false;
                });
            },
            addMyHistory(bet) {
                bet = JSON.parse(bet);

                if (this.myGames.length >= 20) {
                    this.myGames.pop();
                }

                this.myGames.unshift({
                    game: 'mines',
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

<style scoped>
@media(min-width: 824px) {
    .minesPanel {
        display: flex!important;
        justify-content: space-between;
    }
}

.btn-block + .btn-block {
    margin-top: 0px!important;
}
</style>