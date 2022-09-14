<template>
    <div>

        <div class="card">
            <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                <div>
                    <h4 class="mg-b-5 ">Реферальная система</h4>
                    <p class="tx-15  mg-b-0 tx-normal">Делитесь ссылкой и получайте:</p>
                    <li class="tx-15  mg-b-0 tx-normal">5 на баланс за регистрацию приглашенного пользователя сразу</li>
                    <li class="tx-15  mg-b-0 tx-normal">15 на баланс за каждого активного приглашенного пользователя</li>
                    <li class="tx-15  mg-b-0 tx-normal">{{ ref_percent }}% от всех депозитов рефералов сразу на счет</li>
                    <router-link tag="a" :to="{name: 'faq'}" class="nav-link">Подробнее</router-link>
                </div>
                <div class="d-flex mg-t-20 mg-sm-t-0">
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
            </div>
            <div class="card-body pd-20 bd-b pd-b-20">
                <div class="row">
                    <div class="col-12">
                        <div class=" card-crypto">
                            <div class="card-header pd-y-8 d-sm-flex align-items-center justify-content-between"
                                 style="padding: 7px 0px;">
                                <nav class="nav nav-line ref-nav" style="letter-spacing: 0px;">
                                    <a class="nav-link active" style="padding: 0;"
                                       onclick="$('.ref-nav a').removeClass('active'); $(this).addClass('active'); $('.ref-block').hide(); $('#refStatBlock').show(); $('#dateRange').removeClass('dis-ra');">Статистика</a>
                                    <!-- <a class="nav-link" style="padding: 0;"
                                       onclick="$('.ref-nav a').removeClass('active'); $(this).addClass('active'); $('.ref-block').hide(); $('#dateRange').addClass('dis-ra'); $('#refListBlock').show();">Список</a> -->
                                </nav>
                                <div class="tx-12 tx-color-03 align-items-center d-none d-sm-block">
                                    <input id="dateRange" type="text" name="dates"
                                           class="d-inline form-control pull-right bd-0 "
                                           style="margin: -8px;background: transparent; cursor:pointer; color: #8392a5; width:170px"
                                           readonly>
                                </div>
                            </div>
                            <div id="refStatBlock" class="ref-block">
                                <div id="chart"></div>
                                <div class="card-footer pd-20">
                                    <div class="row row-md">
                                        <div class="col-6 col-lg-2 offset-lg-3">
                                            <h5 class="tx-normal tx-rubik mg-b-5">{{ referrals }}</h5>
                                            <p class="tx-10 tx-color-03 mg-b-0">Рефералов</p>
                                        </div>
                                        <div class="col-6 col-lg-2 ">
                                            <h5 class="tx-normal tx-rubik mg-b-5">{{ sum.toFixed(2) }}</h5>
                                            <p class="tx-10 tx-color-03 mg-b-0">Заработано всего</p>
                                        </div>
                                        <div class="col-6 col-lg-2">
                                            <h5 class="tx-normal tx-rubik mg-b-5" id="earnRange"></h5>
                                            <p class="tx-10 tx-color-03 mg-b-0">Заработано <u id="startEarn"></u> по <u
                                                id="endEarn"></u></p>
                                        </div>
                                    </div>
                                </div>
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
                ref_percent: 0,
                referrals: 0,
                sum: 0,
                //domain: "https://demoney.win",
                domain: window.location.origin
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

            var options = {
                title: {
                    text: "",
                    align: 'left',
                    margin: 10,
                    offsetX: 0,
                    offsetY: 0,
                    floating: false,
                    style: {
                        fontSize: '16px',
                        color: '#263238'
                    },
                },
                stroke: {
                    width: 3
                },
                markers: {
                    size: 0,
                },
                toolbar: {
                    show: false

                },
                colors: ['#0168fa', '#0168fa', '#0168fa'],
                chart: {
                    height: 380,
                    type: "area"
                },
                dataLabels: {
                    enabled: false
                },
                series: [{
                    name: "Новых рефералов",
                    data: []
                }],
                noData: {
                    text: 'Загрузка...'
                },

                fill: {

                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.6,
                        opacityTo: 0.9

                    }
                },
                tooltip: {
                    enabled: true,

                },

                xaxis: {
                    tooltip: {
                        enabled: false
                    },
                    categories: []
                }
            };

            const chart = new ApexCharts(document.querySelector("#chart"), options);

            chart.render();

            const token = this.$cookie.get('token');

            $(function () {
                moment.lang("ru");
                var start = moment().subtract(6, 'days');
                var end = moment();

                function cb(start, end) {

                    $.ajax({
                        type: 'POST',
                        url: '/referrals/getGraph',
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader("Authorization",
                                "Bearer " + token);
                        },
                        data: {
                            start: start.format('YYYY-MM-DD'),
                            end: end.format('YYYY-MM-DD')
                        },
                        success: function (obj) {
                            $("#startEarn").html(start.format('DD-MM-YYYY'));
                            $("#endEarn").html(end.format('DD-MM-YYYY'));
                            $("#earnRange").html(obj.er.toFixed(2));
                            chart.updateSeries([{
                                name: 'Заработок',
                                data: obj.chart
                            }])
                        }
                    });
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
                        this.ref_percent = res.data.ref_percent
                        this.referrals = res.data.referrals;
                        this.sum = res.data.sum;

                        this.$root.isLoading = false;
                    });
            }
        }
    }
</script>
