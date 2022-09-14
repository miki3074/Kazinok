<template>
    <div class="card mg-b-10 mg-t-10 hash-mob">
        <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
            <div>
                <h6 class="mg-b-5" style="font-weight: 900; color: blue; font-size: 21px;">Последние игры </h6>
                <p class="tx-13 tx-color-03 mg-b-0"></p>
            </div>
            <div class="d-flex mg-t-20 mg-sm-t-0">
                <div class="btn-group flex-fill">
                    <button class="btn btn-white btn-xs bt-table" :class="[activeTab === 'all' ? 'active' : '']"
                            @click="selectTab('all')">
                        Все игры
                    </button>
                    <button id="mob-wdw" class="btn btn-white btn-xs bt-table" :class="[activeTab === 'wdw' ? 'active' : '']"
                            @click="selectTab('wdw')">
                        Выплаты
                    </button>
                    <button class="btn btn-white btn-xs bt-table" :class="[activeTab === 'my' ? 'active' : '']"
                            @click="selectTab('my')">
                        Мои игры
                    </button>
                </div>
            </div>
        </div>
        <div class="table-responsive mg-t-20 mg-b-15" v-show="activeTab === 'wdw'">
            <table class="table table-dashboard mg-b-0 table-live">
                <thead>
                <tr>
                    <th class="text-center wd-10p">Логин</th>
                    <th class="text-center wd-20p">Метод</th>
                    <th class="text-center wd-20p">Реквизиты</th>
                    <th class="text-center wd-25p">Сумма</th>
                    <th class="text-center wd-25p">Время</th>
                </tr>
                </thead>
                <tbody id="response">
                <tr v-for="wdw in wdws">
                    <!-- <td class="text-center">
                        <svg v-if="game.game === 'dice'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-box" style="    margin-top: -4px;margin-right: 3px;">
                            <path
                                d="M12.89 1.45l8 4A2 2 0 0 1 22 7.24v9.53a2 2 0 0 1-1.11 1.79l-8 4a2 2 0 0 1-1.79 0l-8-4a2 2 0 0 1-1.1-1.8V7.24a2 2 0 0 1 1.11-1.79l8-4a2 2 0 0 1 1.78 0z"></path>
                            <polyline points="2.32 6.16 12 11 21.68 6.16"></polyline>
                            <line x1="12" y1="22.76" x2="12" y2="11"></line>
                        </svg>
                        <i class="ion ion-md-grid tx-16" v-else
                           style="margin-top: -4px;margin-right: 3px;"></i>
                    </td> -->
                    <td class="text-center">{{ wdw.username }}</td>
                    <td class="text-center">{{ wdw.method }}</td>
                    <td class="text-center">{{ wdw.wallet }}</td>
                    <td class="text-center">{{ parseFloat(wdw.sum).toFixed(2) }}</td>
                    <td class="text-center">{{ wdw.date }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="table-responsive mg-t-20 mg-b-15" v-show="activeTab !== 'wdw'">
            <table class="table table-dashboard mg-b-0 table-live">
                <thead>
                <tr>
                    <th class="text-center wd-10p">Игра</th>
                    <th class="text-center wd-20p">Игрок</th>
                    <th class="text-center wd-25p">Ставка</th>
                    <th class="text-center wd-20p">Множитель</th>
                    <th class="text-center wd-25p">Выйгрыш</th>
                </tr>
                </thead>
                <tbody id="response">
                <tr v-for="game in games" @click="showGame(game.id)" v-show="activeTab === 'all'" :class="[game.id > 0 ? '' : 'emptyGame']">
                    <td class="text-center">
                        <svg v-if="game.game === 'dice'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-box" style="    margin-top: -4px;margin-right: 3px;">
                            <path
                                d="M12.89 1.45l8 4A2 2 0 0 1 22 7.24v9.53a2 2 0 0 1-1.11 1.79l-8 4a2 2 0 0 1-1.79 0l-8-4a2 2 0 0 1-1.1-1.8V7.24a2 2 0 0 1 1.11-1.79l8-4a2 2 0 0 1 1.78 0z"></path>
                            <polyline points="2.32 6.16 12 11 21.68 6.16"></polyline>
                            <line x1="12" y1="22.76" x2="12" y2="11"></line>
                        </svg>
                        <img style="margin-top: -4px;margin-right: 3px;" v-else-if="game.game === 'coin'" src="/coinhis1.png" width="14" height="14">
                        <i class="ion ion-md-grid tx-16" v-else
                           style="margin-top: -4px;margin-right: 3px;"></i>
                    </td>
                    <td class="text-center">{{ game.username }}</td>
                    <td class="text-center">{{ parseFloat(game.bet).toFixed(2) }}</td>
                    <td class="text-center" v-if="game.game === 'dice'">x{{ (95.5 / game.chance).toFixed(2)}}</td>
                    <td class="text-center" v-else>x{{ (game.chance).toFixed(2)}}</td>
                    <td class="text-center tx-semibold" :class="[game.win > 0 ? 'tx-success' : '']">{{
                        parseFloat(game.win).toFixed(2) }}
                    </td>
                </tr>
                <tr v-for="game in myGames" @click="showGame(game.id)" v-show="activeTab === 'my'">
                    <td class="text-center">
                        <svg v-if="game.game === 'dice'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-box" style="    margin-top: -4px;margin-right: 3px;">
                            <path
                                d="M12.89 1.45l8 4A2 2 0 0 1 22 7.24v9.53a2 2 0 0 1-1.11 1.79l-8 4a2 2 0 0 1-1.79 0l-8-4a2 2 0 0 1-1.1-1.8V7.24a2 2 0 0 1 1.11-1.79l8-4a2 2 0 0 1 1.78 0z"></path>
                            <polyline points="2.32 6.16 12 11 21.68 6.16"></polyline>
                            <line x1="12" y1="22.76" x2="12" y2="11"></line>
                        </svg>
                        <i class="ion ion-md-grid tx-16" v-else
                           style="margin-top: -4px;margin-right: 3px;"></i>
                    </td>
                    <td class="text-center">{{ game.username }}</td>
                    <td class="text-center">{{ parseFloat(game.bet).toFixed(2) }}</td>
                    <td class="text-center" v-if="game.game === 'dice'">x{{ (100 / game.chance).toFixed(2)}}</td>
                    <td class="text-center" v-else>x{{ (game.chance).toFixed(2)}}</td>
                    <td class="text-center tx-semibold" :class="[game.win > 0 ? 'tx-success' : '']">{{
                        parseFloat(game.win).toFixed(2) }}
                    </td>
                </tr>
                </tbody>
            </table>
            <div
            class="card-footer text-center mg-t-1 link-03"
            style="cursor:pointer;height: 48px;"
            @click="tableLength = 20"
            v-show="tableLength == 10">
                <a class="link-03 tx-medium tx-spacing--0 tx-10" v-show="!loader">
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq14">
                        <circle cx="12" cy="12" r="1"></circle>
                        <circle cx="19" cy="12" r="1"></circle>
                        <circle cx="5" cy="12" r="1"></circle>
                    </svg>
                </a>
                <div class="spinner-border spinner-border-sm link-03 tx-medium tx-spacing--0 tx-10 mg-3" v-show="loader"></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['myGames'],
        data() {
            return {
                games: [],
                wdws: [],
                activeTab: 'all',
                tableLength: 10,
            }
        },
        methods: {
            fillTable() {
                this.$root.axios.get('/lastw')
                    .then(res => {
                        const data = res.data;
                        this.wdws = data;
                    });

                //var i;
                // for(i = 1; i <= this.tableLength; i++) {
                //     this.games.unshift(
                //     {
                //         "id": 0,
                //         "game": "dice",
                //         "bet": "1",
                //         "chance": "80",
                //         "username": "Login123",
                //         "win": "1.55"
                //     }
                //     );
                // }
            },
            showGame(id) {
                this.$root.$emit('showGame', id);
            },
            selectTab(tab) {
                if (tab === this.activeTab) {
                    return;
                }

                this.activeTab = tab;
            }
        },
        beforeMount() {
            this.fillTable()
        },
        sockets: {
            newGame(game) {
                if(game.game != 'dice' && game.game != 'mines' && game.game != 'coin') return;
                if (this.games.length >= this.tableLength) {
                    this.games.pop();
                }

                this.games.unshift(game);
            },
            newWithdraw(wdw) {
                if (this.wdws.length >= 20) {
                    this.wdws.pop();
                }

                this.wdws.unshift(wdw);
            }
        }
    }
</script >
<style scoped>
    @media (max-width: 1023px) {
        #mob-wdw {
            display: none !important;
        }
    }
    .card{
        background: #ebf1f7f0;
    }
</style>