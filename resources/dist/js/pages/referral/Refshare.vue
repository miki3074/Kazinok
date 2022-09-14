<template>
    <div>

        <div class="card">
            <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                <div>
                    <h4 class="mg-b-5 ">Реферальная система</h4>
                    <p class="tx-15  mg-b-0 tx-normal">Делитесь ссылкой и зарабатывайте <u>{{ refshare }}%</u> от преимущества казино</p>
                </div>
                <div class="d-flex mg-t-20 mg-sm-t-0" v-if="showList">
                    <div class="input-group wd-300 mg-t-10">
                        <input v-if="$root.user !== null" type="text" class="form-control" id="refid" :value="domain +'/r/' + $root.user.id"
                               readonly="">
                        <div class="input-group-append">
                            <button data-clipboard-action="copy" data-clipboard-target="#refid"
                                    class="btn btn-outline-light isooa" type="button" id="button-addon2">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                                     fill="none" stroke-linecap="round" stroke-linejoin="round" class="">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="d-flex mg-t-20 mg-sm-t-0" v-else>
                    <div 
                        class="btn btn-white" 
                        style="cursor: pointer;border-color: rgb(37, 118, 234);background: linear-gradient(45deg, rgb(28, 101, 201) 0%, rgb(44, 128, 255) 100%);" 
                        @click="takeRef"
                    >
                    Забрать {{ Number(stats.available).toFixed(2) }} ₽
                    </div>
                </div>
            </div>
            <div class="card-body pd-20 bd-b pd-b-20">
                <div class="row">
                    <div class="col-12">
                        <div class=" card-crypto">
                            <div class="card-header pd-y-8 d-sm-flex align-items-center justify-content-between"
                                 style="padding: 7px 0px;">
                                <nav class="nav nav-line ref-nav" style="letter-spacing: 0px;">
                                    <a class="nav-link active" style="padding: 0;"
                                       onclick="$('.ref-nav a').removeClass('active'); $(this).addClass('active'); $('.ref-block').hide(); $('#refListBlock').show();"
                                       @click="showList = true"
                                       >
                                       Список
                                    </a>
                                    <a class="nav-link" style="padding: 0;"
                                       onclick="$('.ref-nav a').removeClass('active'); $(this).addClass('active'); $('.ref-block').hide(); $('#refEarnBlock').show();"
                                       @click="showList = false"
                                    >
                                       RevShare
                                    </a>
                                </nav>
                                <div class="tx-12 tx-color-03 align-items-center d-none d-sm-block">
                                    <input id="dateRange" type="text" name="dates"
                                           class="d-inline form-control pull-right bd-0 "
                                           style="margin: -8px;background: transparent; cursor:pointer; color: #8392a5; width:170px"
                                           readonly>
                                </div>
                            </div>
                            <div id="refListBlock" class="ref-block mg-t-20">
                                <table id="refList" class="table" style="width:100%;">
                                    <thead>
                                    <tr>
                                        <th class="wd-20p">ID</th>
                                        <th class="wd-20p">Логин</th>
                                        <th class="wd-20p">Регистрация</th>
                                        <th class="wd-20p">Заработок</th>
                                        <th class="wd-20p">Активность</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="refEarnBlock" class="ref-block mg-t-20" style="display: none;">
                                <table id="earnList" class="table" style="width:100%;">
                                    <thead>
                                    <tr>
                                        <th class="wd-20p">Заработано всего</th>
                                        <th class="wd-20p">Заработано за месяц</th>
                                        <th class="wd-20p">Заработано за неделю</th>
                                        <th class="wd-20p">Заработано за сутки</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr role="row" class="odd">
                                            <td>{{ Number(stats.all).toFixed(2) }} ₽</td>
                                            <td>{{ Number(stats.month).toFixed(2) }} ₽</td>
                                            <td>{{ Number(stats.week).toFixed(2) }} ₽</td>
                                            <td>{{ Number(stats.today).toFixed(2) }} ₽</td>
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
                refshare: 0,
                referrals: 0,
                sum: 0,
                domain: window.location.origin,
                showList: true,
                stats: {
                    all: 0,
                    available: 0,
                    month: 0,
                    week: 0,
                    today: 0
                } 
            }
        },
        mounted() {
            this.$root.isLoading = true;

            if (!this.$cookie.get('token')) {
                this.$root.isLoading = false;
                this.$router.go(-1);
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const token = this.$cookie.get('token');

            $('#refList').DataTable({
                "language": {
                    "emptyTable": "У вас ещё нет ни одного реферала",
                        "info": "Количество рефералов: _TOTAL_",
                        "infoEmpty": "",
                },
                "type": "POST",
                "ajax": {
                    "url": '/referrals/get',
                    "dataType": 'json',
                    'type': 'POST',
                    "beforeSend": function (xhr) {
                        xhr.setRequestHeader("Authorization",
                            "Bearer " + token);
                    }
                },
                "order": [[0, "desc"]]
            });
            $('#earnList').DataTable({
                "bPaginate": false,
                "ordering": false,
                initComplete: function( settings, json ) {
                    $("th").removeClass('sorting_desc'); //remove sorting_desc class
                }
            });
            $(function () {
                moment.lang("ru");
                var start = moment().subtract(6, 'days');
                var end = moment();

                function cb(start, end) {
                }

                $('#dateRange').daterangepicker({
                    autoApply: true,
                    locale: {
                        format: 'DD.MM.YYYY'
                    },
                    "maxSpan": {
                        "days": 30
                    },
                    startDate: moment().subtract(6, 'days'),
                    endDate: moment(),
                    ranges: {
                        'Сегодня': [moment(), moment()],
                        'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Неделя': [moment().subtract(6, 'days'), moment()],
                        'Месяц': [moment().startOf('month'), moment().endOf('month')],
                    }
                }, cb);

                cb(start, end);

            });

            $('#daterange').on('apply.daterangepicker', function (ev, picker) {
                console.log(picker.startDate.format('YYYY-MM-DD'));
                console.log(picker.endDate.format('YYYY-MM-DD'));
            });

            var clipboard = new ClipboardJS(".isooa");

            this.getInfo();
        },
        methods: {
            getInfo() {
                this.$root.axios.post('/referrals/getInfo')
                    .then(res => {
                        this.refshare = res.data.refshare;
                        this.referrals = res.data.referrals;
                        this.sum = res.data.sum;

                        this.$root.isLoading = false;

                        this.stats.all = res.data.all;
                        this.stats.available = res.data.available;
                        this.stats.month = res.data.month;
                        this.stats.week = res.data.week;
                        this.stats.today = res.data.today;
                    });
            },
            takeRef() {
                this.$root.axios.post('/referrals/take')
                    .then(res => {
                        const data = res.data;

                        if(data.error) {
                            return alert(data.msg);
                        }

                        this.stats.available = 0;
                        this.$root.user.balance = data.balance;
                    });
            }
        }
    }
</script>

<style>
.dataTables_length, .dataTables_filter {
    display: none;
}
</style>