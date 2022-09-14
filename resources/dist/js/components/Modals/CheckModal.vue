<template>
    <div>
        <div class="modal fade" id="checkModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true"
             onclick="22">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body pd-x-25 pd-sm-x-30 pd-t-40 pd-sm-t-20 pd-b-15 pd-sm-b-20">
                        <div class="text-center" v-show="isLoading">
                            <div class="spinner-border"></div>
                        </div>
                        <div v-if="!isLoading">
                            <a href="" role="button" class="close pos-absolute t-15 r-15" data-dismiss="modal"
                               aria-label="Close">
                                <span aria-hidden="true">×</span></a>
                            <div class="d-flex align-items-baseline tx-rubik mg-b-15">
                                <h5 class="tx-15 lh-1 tx-normal  mg-b-5 mg-r-5">Игра #<span
                                    id="modalGameId">{{ game.id }}</span></h5>
                            </div>

                            <div v-if="game.game === 'dice'">
                                <hr>
                                <div class="row row-xs">
                                    <div class="col-4 col-lg">
                                        <div class="d-flex justify-content-center">
                                            <span
                                                class="d-block tx-10 tx-uppercase tx-medium tx-spacing-1 tx-color-03 mg-l-7">Размер игры</span>
                                        </div>
                                        <h4 class="d-flex justify-content-center tx-normal tx-rubik tx-spacing--1 mg-l-15 mg-b-0">
                                            {{ game.bet.toFixed(2) }}</h4>
                                    </div>
                                    <div class="col-4 col-lg">
                                        <div class="d-flex justify-content-center">
                                            <span
                                                class="d-block tx-10 tx-uppercase tx-medium tx-spacing-1 tx-color-03 mg-l-7">Коэффициент</span>
                                        </div>
                                        <h4 class="d-flex justify-content-center tx-normal tx-rubik tx-spacing--1 mg-l-15 mg-b-0">
                                            x{{ (100 / game.chance).toFixed(2) }}</h4>
                                    </div>
                                    <div class="col-4 col-lg">
                                        <div class="d-flex justify-content-center">
                                            <span
                                                class="d-block tx-10 tx-uppercase tx-medium tx-spacing-1 tx-color-03 mg-l-7">Результат</span>
                                        </div>
                                        <h4 class="d-flex justify-content-center tx-normal tx-rubik tx-spacing--1 mg-l-15 mg-b-0 ">
                                            {{ game.win.toFixed(2) }}</h4>
                                    </div>
                                </div>
                                <hr>
                                <div v-if="game.type === 'min'">
                                    <div class="show-container-m" style="margin-top:50px">
                                        <div class="progress-m" id="progressBar">
                                            <div
                                                :class="[game.win > 0 ? 'tx-success' : '', game.win > 0 ? 'progress-number-m-win' : 'progress-number-m']"
                                                :aria-valuenow="game.dice.random"
                                                :style="'left: '+ game.dice.random / 9999.99 +'%;'"></div>
                                            <div class="rounded-m">
                                                <div class="progress-bar-m" :style="'width: '+ width +'%;'"
                                                     aria-valuenow="0"
                                                     aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="show-container-m" style="margin-top:50px">
                                        <div class="progress-m-max" id="progressBar">
                                            <div
                                                :class="[game.win > 0 ? 'tx-success' : '', game.win > 0 ? 'progress-number-m-win' : 'progress-number-m']"
                                                :aria-valuenow="game.dice.random"
                                                :style="'left: '+ game.dice.random / 9999.99 +'%;'"></div>
                                            <div class="rounded-m-max">
                                                <div class="progress-bar-m-max" :style="'width: '+ width +'%;'"
                                                     aria-valuenow="0"
                                                     aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-sm tx-13 mg-t-30">
                                    <tbody>
                                    <tr>
                                        <td class="tx-medium fsh" data-clipboard-action="copy"
                                            data-clipboard-target="#fullStringDice" style="cursor:pointer"><u
                                            class="link-03">Full string</u></td>
                                        <td class="pd-r-0-f" style="word-wrap: anywhere;" id="fullStringDice">
                                            {{ game.dice.string }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tx-medium">Hash</td>
                                        <td class="pd-r-0-f" style="word-wrap: anywhere;">
                                            {{ game.dice.hash }}<br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tx-medium">Salt1</td>
                                        <td class="pd-r-0-f" style="word-wrap: anywhere;">{{ game.dice.salt1 }}</td>
                                    </tr>
                                    <tr>
                                        <td class="tx-medium">Number</td>
                                        <td class="pd-r-0-f" style="word-wrap: anywhere;"><b>{{ game.dice.random }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tx-medium">Salt2</td>
                                        <td class="pd-r-0-f" style="word-wrap: anywhere;">{{ game.dice.salt2 }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else-if="game.game === 'coin'">
                                <hr>
                                <div class="row row-xs">
                                    <div class="col-4 col-lg">
                                        <div class="d-flex justify-content-center">
                                            <span
                                                class="d-block tx-10 tx-uppercase tx-medium tx-spacing-1 tx-color-03 mg-l-7">Размер игры</span>
                                        </div>
                                        <h4 class="d-flex justify-content-center tx-normal tx-rubik tx-spacing--1 mg-l-15 mg-b-0">
                                            {{ game.bet.toFixed(2) }}</h4>
                                    </div>
                                    <div class="col-4 col-lg">
                                        <div class="d-flex justify-content-center">
                                            <span
                                                class="d-block tx-10 tx-uppercase tx-medium tx-spacing-1 tx-color-03 mg-l-7">Коэффициент</span>
                                        </div>
                                        <h4 class="d-flex justify-content-center tx-normal tx-rubik tx-spacing--1 mg-l-15 mg-b-0">
                                            x{{ (game.chance).toFixed(2) }}</h4>
                                    </div>
                                    <div class="col-4 col-lg">
                                        <div class="d-flex justify-content-center">
                                            <span
                                                class="d-block tx-10 tx-uppercase tx-medium tx-spacing-1 tx-color-03 mg-l-7">Результат</span>
                                        </div>
                                        <h4 class="d-flex justify-content-center tx-normal tx-rubik tx-spacing--1 mg-l-15 mg-b-0 ">
                                            {{ game.win.toFixed(2) }}</h4>
                                    </div>
                                </div>
                                <hr>
                                <div>
                                    <div class="show-container-m" style="margin-top:50px">
                                        <div class="progress-m" id="progressBar">
                                            <div
                                                :class="[game.win > 0 ? 'tx-success' : '', game.win > 0 ? 'progress-number-m-win' : 'progress-number-m']"
                                                :aria-valuenow="game.dice.random"
                                                :style="'left: 50%;'"></div>
                                            <div class="rounded-m">
                                                <div class="progress-bar-m" :style="'width: 50%;'"
                                                     aria-valuenow="0"
                                                     aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <table class="table table-sm tx-13 mg-t-30">
                                    <tbody>
                                    <tr>
                                        <td class="tx-medium fsh" data-clipboard-action="copy"
                                            data-clipboard-target="#fullStringDice" style="cursor:pointer"><u
                                            class="link-03">Full string</u></td>
                                        <td class="pd-r-0-f" style="word-wrap: anywhere;" id="fullStringDice">
                                            {{ game.dice.string }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tx-medium">Hash</td>
                                        <td class="pd-r-0-f" style="word-wrap: anywhere;">
                                            {{ game.dice.hash }}<br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tx-medium">Salt1</td>
                                        <td class="pd-r-0-f" style="word-wrap: anywhere;">{{ game.dice.salt1 }}</td>
                                    </tr>
                                    <tr>
                                        <td class="tx-medium">Number</td>
                                        <td class="pd-r-0-f" style="word-wrap: anywhere;"><b>{{ game.dice.random }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tx-medium">Salt2</td>
                                        <td class="pd-r-0-f" style="word-wrap: anywhere;">{{ game.dice.salt2 }}</td>
                                    </tr>
                                    </tbody>
                                </table> -->
                            </div>
                            <div v-else>
                                <hr>
                                <div class="row row-xs">
                                    <div class="col-4 col-lg">
                                        <div class="d-flex justify-content-center">
                                            <span
                                                class="d-block tx-10 tx-uppercase tx-medium tx-spacing-1 tx-color-03 mg-l-7">Размер игры</span>
                                        </div>
                                        <h4 class="d-flex justify-content-center tx-normal tx-rubik tx-spacing--1 mg-l-15 mg-b-0">
                                            {{ game.bet.toFixed(2) }}</h4>
                                    </div>
                                    <div class="col-4 col-lg">
                                        <div class="d-flex justify-content-center">
                                            <span
                                                class="d-block tx-10 tx-uppercase tx-medium tx-spacing-1 tx-color-03 mg-l-7">Коэффициент</span>
                                        </div>
                                        <h4 class="d-flex justify-content-center tx-normal tx-rubik tx-spacing--1 mg-l-15 mg-b-0">
                                            x{{ game.chance.toFixed(2) }}</h4>
                                    </div>
                                    <div class="col-4 col-lg">
                                        <div class="d-flex justify-content-center">
                                            <span
                                                class="d-block tx-10 tx-uppercase tx-medium tx-spacing-1 tx-color-03 mg-l-7">Результат</span>
                                        </div>
                                        <h4 class="d-flex justify-content-center tx-normal tx-rubik tx-spacing--1 mg-l-15 mg-b-0 ">
                                            {{ game.win.toFixed(2) }}</h4>
                                    </div>
                                </div>
                                <hr>
                                <div id="checkMinesBlocks" class="mg-t-40">
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
                                <table class="table table-sm tx-13 mg-t-40">
                                    <tbody>
                                    <tr>
                                        <td class="tx-medium fsh" data-clipboard-action="copy" data-clipboard-target="#fullStringBomb" style="cursor:pointer"><u class="link-03">Full string<u> </u></u></td>
                                        <td class="pd-r-0-f" style="word-wrap: anywhere;" id="fullStringBomb">{{ game.fair.string }}</td>
                                    </tr>
                                    <tr>
                                        <td class="tx-medium">Hash</td>
                                        <td class="pd-r-0-f" style="word-wrap: anywhere;">{{ game.fair.hash }}</td>
                                    </tr>
                                    <tr>
                                        <td class="tx-medium">Salt</td>
                                        <td class="pd-r-0-f" style="word-wrap: anywhere;">{{ game.fair.salt }}</td>
                                    </tr>
                                    </tbody>
                                </table>
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
                isLoading: true,
                clipboard: null,
                game: null,
                width: 0,
                counterBombs: 0,
                counterBombsBlock: []
            }
        },
        mounted() {
            this.$root.$on('showGame', id => {
                this.isLoading = true;

                $('#checkModal').modal('show');

                this.getGame(id);
            });
        },
        methods: {
            getGame(id) {
                this.clipboard = new ClipboardJS('.fsh');

                this.$root.graphql.getGameHistory({
                    id: id
                }).then(res => {
                    this.game = res.GetGame;

                    if (this.game.game === 'dice') {
                        if (this.game.type === 'min') {
                            this.width = this.game.chance;
                        } else {
                            this.width = 100 - this.game.chance;
                        }
                    } else if (this.game.game === 'coin') {
                        
                    } else {
                        const game = JSON.parse(this.game.mine);

                        this.game.bombs = game['bombs'];
                        this.game.selected_bombs = game['used_positions'];
                        this.game.fair = game.fair;
                    }

                    this.isLoading = false;
                }).catch(error => {
                    $('#checkModal').modal('hide');

                    this.isLoading = false;
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
            }
        }
    }
</script>
