    <template>
    <div style="margin-bottom:100px">
        <div class="card">
            <div class="card-header pd-t-20 pd-b-0 bd-b-0">
                <h4 class="mg-b-5 ">Вывод средств</h4>
                <p class="tx-13 mg-b-0 tx-light">Выберите платежную систему, укажите реквизиты и сумму</p>
            </div>
            <div class="card-body pd-20 bd-b pd-b-20">
                <div class="row">
                    <div class="col-12">
                        <div class="mg-b-20 systems-wt" style="margin-left:-5px;margin-top:-15px; overflow: auto;">
                            <div
                                class="mg-l-5 btn-withdraw mg-t-5 wd-65 ht-65 bg-gray-100 rounded d-inline-flex align-items-center justify-content-center"
                                @click="system = 20; comission = '5%';"
                                onclick="$('#walletNumber').attr('placeholder','7XXXXXXXXX'); $('#nameWt').html('Qiwi кошелек');$('#withdrawSystem').val('4'); $('.btn-withdraw').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="position: relative;">
                                <div id="mob-badge" class="en-refill__payment-label">#1</div>
                                <img class="logo-wt" alt="qiwi" src="/img/qiwi.svg?" width="35">
                            </div>
                            <!-- <div
                                class="mg-l-5 btn-withdraw mg-t-5 wd-65 ht-65 bg-gray-100 rounded d-inline-flex align-items-center justify-content-center"
                                @click="system = 15; comission = '4%';"
                                onclick="$('#walletNumber').attr('placeholder','7XXXXXXXXX'); $('#nameWt').html('Qiwi кошелек');$('#withdrawSystem').val('4'); $('.btn-withdraw').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="position: relative;">
                                <div id="mob-badge" class="en-refill__payment-label">#2</div>
                                <img class="logo-wt" alt="qiwi" src="/img/qiwi.svg?" width="35">
                            </div> -->
                            <div
                                class="mg-l-5 mg-t-10 btn-withdraw wd-65 ht-65 bg-gray-100 rounded d-inline-flex align-items-center justify-content-center"
                                @click="system = 9; comission = '5%';"
                                onclick="$('#walletNumber').attr('placeholder','4539XXXXXXXX1190');$('#withdrawSystem').val('9'); $('#nameWt').html('Номер карты');$('.btn-withdraw').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="position: relative;">
                                <div id="mob-badge" class="en-refill__payment-label">#1</div>
                                <img id="vmm" class="logo-wt" alt="visa" src="/img/vmm.svg" style="width: 65px!important; transform: scale(0.8);">
                            </div>
                            <!-- <div
                                class="mg-l-5 mg-t-10 btn-withdraw wd-65 ht-65 bg-gray-100 rounded d-inline-flex align-items-center justify-content-center"
                                @click="system = 16; comission = '3% + 50р';"
                                onclick="$('#walletNumber').attr('placeholder','4539XXXXXXXX1190');$('#withdrawSystem').val('9'); $('#nameWt').html('Номер карты');$('.btn-withdraw').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="position: relative;">
                                <div id="mob-badge" class="en-refill__payment-label">#2</div>
                                <img id="vmm" class="logo-wt" alt="visa" src="/img/vmm.svg" style="width: 65px!important; transform: scale(0.8);">
                            </div> -->
                            <!-- <div
                                class="mg-l-5 mg-t-10 btn-withdraw wd-65 ht-65 bg-gray-100 rounded d-inline-flex align-items-center justify-content-center"
                                @click="system = 10; comission = '4% + 50р';"
                                onclick="$('#walletNumber').attr('placeholder','5190XXXXXXXX4130');$('#withdrawSystem').val('10'); $('#nameWt').html('Номер карты Mastercard'); $('.btn-withdraw').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                                <img class="logo-wt" alt="Mastercard" src="/img/mcicon.png" width="40">
                            </div> -->
                            <!-- <div
                                class="mg-l-5 mg-t-10 btn-withdraw wd-65 ht-65 bg-gray-100 rounded d-inline-flex align-items-center justify-content-center"
                                @click="system = 1; comission = '5%'"
                                onclick="$('#walletNumber').attr('placeholder','41001XXXXXXXXXX');$('#withdrawSystem').val('1'); $('#nameWt').html('ЮMoney кошелек'); $('.btn-withdraw').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                                <img class="logo-wt" alt="ЮMoney" src="/img/yoomoney.png" width="40">
                            </div> -->
                            <div
                                class="btn-withdraw mg-t-10 mg-l-5 wd-65 ht-65 bg-gray-100 rounded d-inline-flex align-items-center justify-content-center"
                                @click="system = 12; comission = '3%'"
                                onclick="$('#walletNumber').attr('placeholder','F100000');$('#nameWt').html('FKWallet кошелек'); $('#withdrawSystem').val('12'); $('.btn-withdraw').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                                <img class="logo-wt" alt="FKWallet" src="/img/fkwallet.png?2" width="40">
                            </div>
                            <div
                                class="mg-l-5 mg-t-10 btn-withdraw wd-65 ht-65 bg-gray-100 rounded d-inline-flex align-items-center justify-content-center"
                                @click="system = 21; comission = '3%'"
                                onclick="$('#walletNumber').attr('placeholder','1000000000'); $('#withdrawSystem').val('7'); $('#nameWt').html('Piastrix кошелек'); $('.btn-withdraw').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                                <img class="logo-wt" alt="piastrix" src="/img/piastrix.png" width="40">
                            </div>
                            <!-- <div
                                class="btn-withdraw mg-t-10 mg-l-5 wd-65 ht-65 bg-gray-100 rounded d-inline-flex align-items-center justify-content-center"
                                @click="system = 2; comission = '0%'"
                                onclick="$('#walletNumber').attr('placeholder','P100000000');$('#nameWt').html('Payeer кошелек'); $('#withdrawSystem').val('2'); $('.btn-withdraw').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                                <img class="logo-wt" alt="payeer" src="/img/payeericon.png" width="40">
                            </div>
                            <div
                                class="btn-withdraw mg-t-10 mg-l-5 wd-65 ht-65 bg-gray-100 rounded d-inline-flex align-items-center justify-content-center"
                                @click="system = 11; comission = '0%'"
                                onclick="$('#walletNumber').attr('placeholder','+7XXXXXXXXXX');$('#nameWt').html('Номер телефона Tele2'); $('#withdrawSystem').val('11'); $('.btn-withdraw').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                                <img class="logo-wt" alt="tele2" src="/img/tele2.png" width="40">
                            </div>
                            <div
                                class="mg-l-5 mg-t-10 btn-withdraw wd-65 ht-65 bg-gray-100 rounded d-inline-flex align-items-center justify-content-center"
                                @click="system = 5; comission = '0%'"
                                onclick="$('#walletNumber').attr('placeholder','+7XXXXXXXXXX'); $('#withdrawSystem').val('5'); $('#nameWt').html('Номер телефона Beeline'); $('.btn-withdraw').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                                <img class="logo-wt" alt="beeline" src="/img/beelineicon.png" width="40">
                            </div>
                            <div
                                class="mg-l-5 mg-t-10 btn-withdraw wd-65 ht-65 bg-gray-100 rounded d-inline-flex align-items-center justify-content-center"
                                @click="system = 6; comission = '0%'"
                                onclick="$('#walletNumber').attr('placeholder','+7XXXXXXXXXX'); $('#withdrawSystem').val('6'); $('#nameWt').html('Номер телефона Megafon'); $('.btn-withdraw').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                                <img class="logo-wt" alt="megafon" src="/img/megafonicon.png" width="40">
                            </div>
                            <div
                                class="mg-l-5 mg-t-10 btn-withdraw wd-65 ht-65 bg-gray-100 rounded d-inline-flex align-items-center justify-content-center"
                                @click="system = 7; comission = '0%'"
                                onclick="$('#walletNumber').attr('placeholder','+7XXXXXXXXXX'); $('#withdrawSystem').val('7'); $('#nameWt').html('Номер телефона Mts'); $('.btn-withdraw').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                                <img class="logo-wt" alt="mts" src="/img/mtsicon.png" width="40">
                            </div> -->
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xs-12 col-lg-6">
                                <!-- <div class="form-group">
                                    <label for="inputAddress" class="tx-normal typeahead la-mob"><span id="nameWt">Кошелек:</span>
                                        <span class="tx-danger">*</span></label>
                                    <input type="text" class="form-control tx-16 tx-normal" id="walletNumber"
                                           v-model="wallet" placeholder="" @input="validateQiwi">
                                </div> -->
                                <div class="form-group d-none">
                                    <input type="number" class="form-control tx-light tx-16 tx-normal"
                                           id="withdrawSystem">
                                    <input type="number" class="form-control tx-light tx-16 tx-normal" id="withdrawOf"
                                           value="7">
                                </div>
                                <div class="form-group">
                                    <label v-if="system == 16" for="inputAddress" class="tx-normal la-mob">Сумма: (мин: 1000 - макс: 10000)</label>
                                    <label v-else-if="system == 15" for="inputAddress" class="tx-normal la-mob">Сумма: (мин: 1000 - макс: 5000)</label>
                                    <label v-else-if="system == 4" for="inputAddress" class="tx-normal la-mob">Сумма: (мин: 150 - макс: 1000)</label>
                                    <label v-else-if="system == 9" for="inputAddress" class="tx-normal la-mob">Сумма: (мин: 1000 - макс: 5000) <br> Внимание! Выводить на карту можно не более 20000 в месяц</label>
                                    <label v-else-if="system == 20" for="inputAddress" class="tx-normal la-mob">Сумма: (мин: 150 - макс: 5000)</label>
                                    <label v-else for="inputAddress" class="tx-normal la-mob">Сумма: (мин: 150)</label>
                                    <div id="the-basics" class="input-group tx-light tx-16 ">
                                        <input id="WithdrawSize" readonly="readonly" onfocus="this.removeAttribute('readonly');" autocomplete="false_disabled_hack@#!@#$" class="form-control tx-normal "
                                               v-model="sum" placeholder="" @input="validateSum">
                                    </div>
                                    <span class="nav-line-profile badge bt-com"
                                          style="margin-top:10px; background-color: #e5e9f2;color: #8392a5;" v-show="system">Комиссия: {{ comission }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                            </div>
                        </div>
                        <!-- <button id="btnwt" @click="withdraw" class="btn btn-primary tx-normal btn-la-mob"
                                style="border-color: #2576ea;background: linear-gradient(45deg, #1c65c9 0%, #2c80ff 100%);">
                            Создать
                        </button> -->
                        <button v-if="!disableBtn" id="btnwt" @click="withdrawSock" class="btn btn-primary tx-normal btn-la-mob"
                                style="border-color: #2576ea;background: linear-gradient(45deg, #1c65c9 0%, #2c80ff 100%);">
                            Создать
                        </button>
                        <a class="link-03 tx-medium tx-spacing--0 tx-10" v-if="disableBtn">
                            <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq14">
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="19" cy="12" r="1"></circle>
                                <circle cx="5" cy="12" r="1"></circle>
                            </svg>
                        </a>
                        <button id="error_withdraw" v-if="errors.show"
                                style="margin-left: 7px; padding: 8px 22px; pointer-events: none; color: #fff !important;"
                                class="btn tx-medium btn-la-mob bg-danger-dice tx-white bd-0 btn-sel-d ">
                            {{ errors.message }}
                        </button>
                    </div>
                </div>
            </div>
            <ul class="list-unstyled mg-b-0 bd-0 bd-t table-responsive" id="wtBl">
                <div v-for="(group, key) in withdraws">
                    <li class="list-label bd-t">{{ key }}</li>
                    <li v-for="withdraw in group.withdraws" class="list-item d-flex bd-t bd-r-0 bd-l-0">
                        <div class="d-flex align-self-center">
                            <div class="wd-100 can-wt">
                                <p class="tx-medium mg-b-0 tx-15 id-mob">ID {{ withdraw.id }}</p>
                                <!-- <p class="tx-medium mg-b-0 tx-12 id-mob" style="color: #7f7f7f;cursor: pointer;" v-if="withdraw.status === 0" @click="cancelWithdraw(withdraw.id)"><i class="ion ion-md-swap"></i> Отменить</p> -->
                                <p class="tx-medium mg-b-0 tx-12 id-mob" style="color: #7f7f7f;cursor: pointer;" v-if="withdraw.status === 0" @click="cancelWithdrawSocket(withdraw.id)"><i class="ion ion-md-swap"></i> Отменить</p>
                            </div>
                        </div>
                        <div class="d-flex align-self-center">
                            <div class="avatar wt-ava1 mg-l-wallet-10">
                                <span class="avatar-initial rounded-circle bg-gray-100 wt-ava">
                                    <img :src="'/img/' + withdraw.icon " style="height:23px; width:23px">
                                </span>
                            </div>
                            <div class="pd-l-10 pd-l-wallet-5">
                                <p class="tx-medium mg-b-0 tx-15 id-mob wallet-mob">{{ withdraw.wallet }}</p>
                                <small class="tx-12 tx-color-03 mg-b-0 tx-light id-mob">{{ withdraw.date }}</small>
                            </div>
                        </div>
                        <div class="mg-l-auto text-right amo-mob">
                            <p v-if="withdraw.system == 9" class="tx-medium mg-b-0 tx-16 id-mob wallet-mob">{{ withdraw.sum.toFixed(2) }}
                                <span v-if="withdraw.status === 1">
                                    <span
                                        class="badge badge-success bg-success-light tx-success tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-checkmark"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 2">
                                    <span
                                        class="badge badge-danger bg-danger-light tx-danger tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-close"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 0 || withdraw.status === 3">
                                    <span
                                        class="badge badge-warning bg-warning-light tx-warning tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-time"></i>
                                    </span>
                                </span>
                            </p>
                            <p v-else-if="withdraw.system == 16" class="tx-medium mg-b-0 tx-16 id-mob wallet-mob">{{ withdraw.sum.toFixed(2) }}
                                <span v-if="withdraw.status === 1">
                                    <span
                                        class="badge badge-success bg-success-light tx-success tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-checkmark"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 2">
                                    <span
                                        class="badge badge-danger bg-danger-light tx-danger tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-close"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 0 || withdraw.status === 3">
                                    <span
                                        class="badge badge-warning bg-warning-light tx-warning tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-time"></i>
                                    </span>
                                </span>
                            </p>
                            <p v-else-if="withdraw.system == 12 || withdraw.system == 14" class="tx-medium mg-b-0 tx-16 id-mob wallet-mob">{{ withdraw.sum.toFixed(2) }}
                                <span v-if="withdraw.status === 1">
                                    <span
                                        class="badge badge-success bg-success-light tx-success tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-checkmark"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 2">
                                    <span
                                        class="badge badge-danger bg-danger-light tx-danger tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-close"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 0 || withdraw.status === 3">
                                    <span
                                        class="badge badge-warning bg-warning-light tx-warning tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-time"></i>
                                    </span>
                                </span>
                            </p>
                            <p v-else-if="withdraw.system == 5" class="tx-medium mg-b-0 tx-16 id-mob wallet-mob">{{ ( Math.round(withdraw.sum - withdraw.sum / 100 * 4) ).toFixed(2) }}
                                <span v-if="withdraw.status === 1">
                                    <span
                                        class="badge badge-success bg-success-light tx-success tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-checkmark"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 2">
                                    <span
                                        class="badge badge-danger bg-danger-light tx-danger tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-close"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 0 || withdraw.status === 3">
                                    <span
                                        class="badge badge-warning bg-warning-light tx-warning tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-time"></i>
                                    </span>
                                </span>
                            </p>
                            <p v-else-if="withdraw.system == 15" class="tx-medium mg-b-0 tx-16 id-mob wallet-mob">{{ withdraw.sum.toFixed(2) }}
                                <span v-if="withdraw.status === 1">
                                    <span
                                        class="badge badge-success bg-success-light tx-success tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-checkmark"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 2">
                                    <span
                                        class="badge badge-danger bg-danger-light tx-danger tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-close"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 0 || withdraw.status === 3">
                                    <span
                                        class="badge badge-warning bg-warning-light tx-warning tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-time"></i>
                                    </span>
                                </span>
                            </p>
                            <p v-else-if="withdraw.system == 5" class="tx-medium mg-b-0 tx-16 id-mob wallet-mob">{{ ( Math.round(withdraw.sum - withdraw.sum / 100 * 4) ).toFixed(2) }}
                                <span v-if="withdraw.status === 1">
                                    <span
                                        class="badge badge-success bg-success-light tx-success tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-checkmark"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 2">
                                    <span
                                        class="badge badge-danger bg-danger-light tx-danger tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-close"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 0 || withdraw.status === 3">
                                    <span
                                        class="badge badge-warning bg-warning-light tx-warning tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-time"></i>
                                    </span>
                                </span>
                            </p>
                            <p v-else-if="withdraw.system == 4 || withdraw.system == 1" class="tx-medium mg-b-0 tx-16 id-mob wallet-mob">{{ withdraw.sum.toFixed(2) }}
                                <span v-if="withdraw.status === 1">
                                    <span
                                        class="badge badge-success bg-success-light tx-success tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-checkmark"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 2">
                                    <span
                                        class="badge badge-danger bg-danger-light tx-danger tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-close"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 0 || withdraw.status === 3">
                                    <span
                                        class="badge badge-warning bg-warning-light tx-warning tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-time"></i>
                                    </span>
                                </span>
                            </p>
                            <p v-else-if="withdraw.system == 20" class="tx-medium mg-b-0 tx-16 id-mob wallet-mob">{{ withdraw.sum.toFixed(2) }}
                                <span v-if="withdraw.status === 1">
                                    <span
                                        class="badge badge-success bg-success-light tx-success tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-checkmark"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 2">
                                    <span
                                        class="badge badge-danger bg-danger-light tx-danger tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-close"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 0 || withdraw.status === 3">
                                    <span
                                        class="badge badge-warning bg-warning-light tx-warning tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-time"></i>
                                    </span>
                                </span>
                            </p>

                            <p v-else class="tx-medium mg-b-0 tx-16 id-mob wallet-mob">{{ withdraw.sum.toFixed(2) }}
                                <span v-if="withdraw.status === 1">
                                    <span
                                        class="badge badge-success bg-success-light tx-success tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-checkmark"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 2">
                                    <span
                                        class="badge badge-danger bg-danger-light tx-danger tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-close"></i>
                                    </span>
                                </span>
                                <span v-if="withdraw.status === 0 || withdraw.status === 3">
                                    <span
                                        class="badge badge-warning bg-warning-light tx-warning tx-16 tx-bold mg-l-5 d-none d-sm-inline-block">
                                        <i class="icon ion-md-time"></i>
                                    </span>
                                </span>
                            </p>
                            <small class="tx-12 tx-success mg-b-0 id-mob" v-if="withdraw.status === 1">
                                <i class="icon ion-md-checkmark d-inline-flex d-sm-none"></i>
                                Выплачено
                            </small>
                            <small class="tx-12 tx-danger mg-b-0 id-mob" v-if="withdraw.status === 2">
                                <i class="icon ion-md-close d-inline-flex d-sm-none"></i>
                                Отменен
                            </small>
                            <small class="tx-12 tx-orange mg-b-0 id-mob" v-if="withdraw.status === 0">
                                <i class="icon ion-md-arrow-down d-inline-flex d-sm-none"></i>
                                Ожидание
                            </small>
                            </small>
                            <small class="tx-12 tx-orange mg-b-0 id-mob" v-if="withdraw.status === 3">
                                <i class="icon ion-md-arrow-down d-inline-flex d-sm-none"></i>
                                В обработке
                            </small>
                        </div>
                        <div class="text-right">
                            <p class="tx-medium mg-b-0 tx-15 mg-t-8"></p>
                        </div>
                    </li>
                </div>
            </ul>
            <div
            class="card-footer text-center mg-t-1 link-03"
            style="cursor:pointer;height: 48px;"
            @click="getMoreWithdraws"
            v-show="countWithdraws >= 8">
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
    import md5 from 'blueimp-md5';

    export default {
        data() {
            return {
                loader: false,
                sum: '',
                wallet: '',
                system: 0,
                comission: '',
                page: 1,
                errors: {
                    show: false,
                    message: ''
                },
                disableBtn: false,
                disableBtnCancel: false,
                withdraws: [],
                countWithdraws: 0,
                user: '',
            }
        },
        mounted() {
            this.$root.isLoading = true;

            if (!this.$cookie.get('token')) {
                this.$root.isLoading = false;
                this.$router.go(-1);
            }

            this.getWithdraws();
            this.getUser();
        },
        methods: {
	    validateSum() {
            this.sum = this.sum.toString();
			this.sum = this.sum.replace(/[,]/g, '.').replace(/[^\d,.]*/g, '').replace(/([,.])[,.]+/g, '$1').replace(/^[^\d]*(\d+([.,]\d{0,2})?).*$/g, '$1');
		}, 
            getUser() {
                this.$root.axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$cookie.get('token');

                this.$root.graphql.getUser().then(res => {
                    this.user = res.User;
                }).catch(error => {
                    this.$cookie.delete('token');
                });
            },
            validateQiwi() {
                if ( this.system == 15 || this.system == 4 || this.system == 17) {
                    if (this.wallet.indexOf('8') == 0) {
                        this.wallet = this.wallet.replace('8', '7')
                    }
                    if (this.wallet.indexOf('+') == 0) {
                        this.wallet = this.wallet.replace('+', '')
                    }
                }
            },
            withdrawSock() {
                var sum = Math.round(this.sum);
                this.sum = sum;

                if (this.system == 1 && this.user.wallet_yoomoney == null) {
                    return this.errors = {
                        show: true,
                        message: 'Перейдите во вкладку "Реквизиты" и заполните данные'
                    };
                }

                if (this.system == 12 && this.user.wallet_fk == null) {
                    return this.errors = {
                        show: true,
                        message: 'Перейдите во вкладку "Реквизиты" и заполните данные'
                    };
                }

                if (this.system == 14 && this.user.wallet_piastrix == null || this.system == 21 && this.user.wallet_piastrix == null) {
                    return this.errors = {
                        show: true,
                        message: 'Перейдите во вкладку "Реквизиты" и заполните данные'
                    };
                }

                if (this.system == 9 && this.user.wallet_card == null || this.system == 16 && this.user.wallet_card == null || this.system == 19 && this.user.wallet_card == null) {
                    return this.errors = {
                        show: true,
                        message: 'Перейдите во вкладку "Реквизиты" и заполните данные'
                    };
                }

                if (this.system == 4 && this.user.wallet_qiwi == null || this.system == 15 && this.user.wallet_qiwi == null || this.system == 20 && this.user.wallet_qiwi == null || this.system == 17 && this.user.wallet_qiwi == null) {
                    return this.errors = {
                        show: true,
                        message: 'Перейдите во вкладку "Реквизиты" и заполните данные'
                    };
                }

                if (this.system == 1) {
                    this.wallet = this.user.wallet_yoomoney;
                }

                if (this.system == 12) {
                    this.wallet = this.user.wallet_fk;
                }

                if (this.system == 14 || this.system == 21) {
                    this.wallet = this.user.wallet_piastrix;
                }

                if (this.system == 9 || this.system == 16 || this.system == 19) {
                    this.wallet = this.user.wallet_card;
                }

                if ( this.system == 15 || this.system == 4 || this.system == 20 || this.system == 17) {
                    this.wallet = this.user.wallet_qiwi;
                }
                
                if (this.disableBtn) {
                    return;
                }
                if(this.system == 0 || this.sum < 0 || !this.wallet) {
                    return this.errors = {
                        show: true,
                        message: 'Заполните все поля'
                    };
                }

                this.wallet = this.wallet.replace(/ /g,'');

                if (this.system == 9 && this.wallet.length != 16 || this.system == 16 && this.wallet.length != 16) {
                    return this.errors = {
                        show: true,
                        message: "Проверьте правильность введенного номера. 16 цифр"
                    };
                }

                /*if (this.system == 4 || this.system == 11 || this.system == 5 || this.system == 6 || this.system == 7) {
                    if (this.wallet.indexOf('+') != 0) {
                        return this.errors = {
                            show: true,
                            message: "Проверьте правильность введенного номера"
                        };
                    }
                }*/

                if (this.system == 15 || this.system == 4) {
                    if (this.wallet.indexOf('+') == 0) {
                        return this.errors = {
                            show: true,
                            message: "Проверьте правильность введенного номера. Без '+'"
                        };
                    }
                }


                this.errors.show = false;
                this.disableBtn = true;
                this.sum = parseInt(this.sum, 10);
                if (this.sum == 0) {
                    return this.errors = {
                        show: true,
                        message: "Сумма должна быть > 0"
                    };
                }

                this.$socket.emit('withdraw', {
                    sum: this.sum,
                    wallet: this.wallet,
                    system: this.system,
                    page: this.page,
                    id: this.$root.user.id

                }, (data) => {
                    if (data.success) {
                        //if (this.$root.user.is_youtuber) {
                        //    setTimeout(console.log(''), 12000)
                        //}
                        this.$root.user.balance -= this.sum;
                        this.withdraws = data.withdraws.list;
                        this.countWithdraws = data.withdraws.count;
                        this.errors.show = false;

                        this.disableBtn = false;
                    } else {
                        
                        this.errors = {
                            show: true,
                            message: data.message
                        };
                        this.disableBtn = false;
                    }
                });
            },
            /*withdraw() {
                if (this.disableBtn) {
                    return;
                }
                if(this.system == 0 || this.sum < 0 || !this.wallet) {
                    return this.errors = {
                        show: true,
                        message: 'Заполните все поля'
                    };
                }

                this.wallet = this.wallet.replace(/ /g,'');

                if (this.system == 4 || this.system == 11 || this.system == 5 || this.system == 6 || this.system == 7) {
                    if (this.wallet.indexOf('+') != 0) {
                        return this.errors = {
                            show: true,
                            message: "Проверьте правильность введенного номера"
                        };
                    }
                }

                this.errors.show = false;
                this.disableBtn = true;

                var currentTime = Date.now();

                var test = md5(this.sum + this.$root.user.id + 'oxyeli_g@ndoni?' + this.sum + currentTime);

                this.$root.axios.post('/withdraw/create', {
                    sum: this.sum,
                    wallet: this.wallet,
                    system: this.system,
                    page: this.page,
                    a: test,
                    time: currentTime
                }).then(res => {
                    const data = res.data;

                    if (data.success) {
                        this.$root.user.balance -= this.sum;
                        this.withdraws = data.withdraws.list;
                        this.countWithdraws = data.withdraws.count;
                        this.errors.show = false;

                        this.disableBtn = false;
                    } else {
                        this.errors = {
                            show: true,
                            message: data.message
                        };

                        this.disableBtn = false;
                    }
                });
            },*/
            getWithdraws() {
                this.$root.axios.post('/withdraw/get', {
                    page: this.page
                }).then(res => {
                    this.withdraws = res.data.list;
                    this.countWithdraws = res.data.count;
                    this.$root.isLoading = false;
                    this.loader = false;
                });
            },
            getMoreWithdraws() {
                this.page += 1;
                this.loader = true;
                this.getWithdraws();
            },
            cancelWithdrawSocket(id) {
                if (this.disableBtnCancel) {
                    return;
                }

                this.disableBtnCancel = true;

                this.$socket.emit('withdrawDecline', {
                    id: id,
                    user_id: this.$root.user.id
                }, (data) => {
                    if (data.success) {
                        this.$root.user.balance += data.sum;

                        this.withdraws = data.withdraws.list;
                        this.countWithdraws = data.withdraws.count;

                        this.disableBtnCancel = false;
                    } else {
                        this.errors = {
                            show: true,
                            message: data.message
                        };

                        this.disableBtnCancel = false;
                    }
                });
            },
            /*cancelWithdraw(id) {
                if (this.disableBtnCancel) {
                    return;
                }

                this.disableBtnCancel = true;

                this.$root.axios.post('/withdraw/decline', {
                    id: id
                }).then(res => {
                    const data = res.data;

                    if (data.success) {
                        this.$root.user.balance += data.sum;

                        this.withdraws = data.withdraws.list;
                        this.countWithdraws = data.withdraws.count;

                        this.disableBtnCancel = false;
                    } else {
                        this.errors = {
                            show: true,
                            message: data.message
                        };

                        this.disableBtnCancel = false;
                    }
                });
            }*/
        }
    }
</script>

<style type="text/css">
    @media (max-width: 768px) {
        #vmm {
            transform: scale(0.6) !important;
        }
        #mob-badge {
            color: white !important;
            z-index: 1;
            top: 0px;
            right: 3px;
            padding: 1px 1px;
            background-color: rgba(58,61,82);
        }
    }
</style>
