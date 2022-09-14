<template>
    <div class="card mg-b-10 mg-t-10 hash-mob" style="display: block;">
        <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
            <div>
                <h6 class="mg-b-5">История выплат</h6>
                <p class="tx-13 tx-color-03 mg-b-0"></p>
            </div>
        </div>
        <div class="table-responsive mg-t-20 mg-b-15">
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
    </div>
</template>

<script>
    export default {
        data() {
            return {
                wdws: [],
                tableLength: 20,
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
        },
        beforeMount() {
            this.fillTable()
        },
        sockets: {
            newWithdraw(wdw) {
                if (this.wdws.length >= 20) {
                    this.wdws.pop();
                }

                this.wdws.unshift(wdw);
            }
        }
    }
</script>
