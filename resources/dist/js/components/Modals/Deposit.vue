<template>
    <div class="modal fade" id="depositModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel4" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content tx-14">
                <div class="modal-header">
                    <h4 class="mg-b-5 ">Пополнение баланса</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mg-b-5">
                        <label class="tx-normal la-mob">Выберите платежную систему:</label>
                    </div>
                    <div class="d-none d-sm-flex flex-row justify-content-start">
                        <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'qiwi2'; comission = '5%'" onclick="$('#depositSystem').val('4'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="position: relative;">
                            
                            <img src="/img/payment/qiwi.png?v=1" style="max-width: 100px;transform:scale(0.6);">
                        </div>
                        <!-- XMPAY -->
                        <!-- <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'qiwi'; sum = 300; comission = '5%'" onclick="$('#depositSystem').val('4'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="position: relative;">
                            <div class="en-refill__payment-label">#2</div>
                            <img src="/img/payment/qiwi.png?v=1" style="max-width: 100px;transform:scale(0.6);">
                        </div> -->
                        <!-- XMPAY -->
                        <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'fkwallet'; comission = '4%'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                            <img src="/img/payment/fkwalletdark.png?v=1" style="max-width: 100px;">
                        </div>

                        <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'card'; sum = 300; comission = '5%'" onclick="$('#depositSystem').val('5'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="position: relative;">
                            <div class="en-refill__payment-label">#1</div>
                            <img src="/img/vmm.svg?v=1" style="max-width: 100px;transform:scale(0.6);">
                        </div>
                        <!-- <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'sber1'; sum = 400" onclick="$('#depositSystem').val('8'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                            <img src="/img/payment/sber.svg?v=1" style="max-width: 150px;transform:scale(0.6);">
                        </div> -->
                        <!-- <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 3" onclick="$('#depositSystem').val('3'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                            <img src="/img/payment/freekassa.png?v=1" style="max-width: 100px;">
                        </div> -->
                        <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'visafk'; sum = 1000; comission = '9,5% + 100 '" onclick="$('#depositSystem').val('5'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="position: relative;">
                            <div class="en-refill__payment-label">#2</div>
                            <img src="/img/vmm.svg?v=1" style="max-width: 100px;transform:scale(0.6);">
                        </div>
                    </div>
                    <div class="d-none d-sm-flex flex-row justify-content-start mt-2">
                        <!-- XMPAY -->
                        <!-- <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'visa'; sum = 300; comission = '5%'" onclick="$('#depositSystem').val('5'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="position: relative;">
                            <div class="en-refill__payment-label">#3</div>
                            <img src="/img/vmm.svg?v=1" style="max-width: 100px;transform:scale(0.6);">
                        </div> -->
                        <!-- XMPAY -->
                        <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'yoomoney'; sum = 500; comission = '13,5%'" onclick="$('#depositSystem').val('2'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                            <img src="/img/payment/yoomoney.png?v=1" style="max-width: 100px;">
                        </div>
                         <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'piastrix1'; comission = '4%'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                            <img src="/img/payment-piastrix.svg?v=1" style="max-width: 100px;">
                        </div>
                    </div>
                    <!-- <div class="d-none d-sm-flex flex-row justify-content-start mt-2"> -->
                        <!-- <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'mobile'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                            <img src="/img/payment/mobile.png?v=1" style="max-width: 100px;">
                        </div> -->
                        <!-- <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'vkpay'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                            <img src="/img/payment/vk_pay.png?v=1" style="max-width: 70px;">
                        </div> -->
                        <!-- <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'steam'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                            <img src="/img/payment/steam.png?v=1" style="max-width: 70px;">
                        </div> -->
                        <!-- <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'payeer'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                            <img src="/img/payment/payeer.png?v=1" style="max-width: 70px;">
                        </div> -->
                        
                        <!-- <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'piastrix'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                            <img src="/img/payment-piastrix.svg?v=1" style="max-width: 100px;">
                        </div> -->
                       <!--  <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'piastrix1'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                            <img src="/img/payment-piastrix.svg?v=1" style="max-width: 100px;">
                        </div> -->
                        
                        <!-- <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'alfa'" onclick="$('#depositSystem').val('6'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                            <img src="/img/payment/alfa.svg?v=1" style="max-width: 150px;transform:scale(0.6);">
                        </div>
                        <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'tinkoff'" onclick="$('#depositSystem').val('7'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                            <img src="/img/payment/tinkoff.svg?v=1" style="max-width: 150px;transform:scale(0.6);">
                        </div>-->
                        
                        
                        <!-- <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'vtb'" onclick="$('#depositSystem').val('9'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                            <img src="/img/payment/vtb.svg?v=1" style="max-width: 100px;transform:scale(0.6);">
                        </div> -->
                    <!-- </div> -->
                    <!-- <div class="d-none d-sm-flex flex-row justify-content-start mt-2">
                        <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'raif'" onclick="$('#depositSystem').val('10'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                            <img src="/img/payment/raif.svg?v=1" style="max-width: 150px;transform:scale(0.6);">
                        </div> 
                        <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'otkritie'" onclick="$('#depositSystem').val('11'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')">
                            <img src="/img/payment/otkritie.svg?v=1" style="max-width: 150px;transform:scale(1); margin-left: 20px;">
                        </div>
                    </div> -->
                    <div class="d-sm-none d-block">
                        <div class="row row-xs">
                            <!-- Данилка пэй -->
                            <div class="col-sm col-6 mg-t-10">
                                <div class="en-refill__payment-label">#1</div>
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'qiwi2'; comission = '5%'" onclick="$('#depositSystem').val('4'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment/qiwi.png?v=1" style="max-width: 100px;transform:scale(0.6);">
                                </div>
                            </div>
                            <!-- <div class="col-sm col-6 mg-t-10">
                                <div class="en-refill__payment-label">#2</div>
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'qiwi'; sum = 300; comission = '5%'" onclick="$('#depositSystem').val('4'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment/qiwi.png?v=1" style="max-width: 100px;transform:scale(0.6);">
                                </div>
                            </div> -->
                            <div class="col-sm col-6 mg-t-10">
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'fkwallet'; comission = '4%'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment/fkwallet.png?v=1" style="max-width: 100px;">
                                </div>
                            </div>
                            <div class="col-sm col-6 mg-t-10">
                                <div class="en-refill__payment-label">#1</div>
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'card'; sum = 300; comission = '5%'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/vmm.svg?v=1" style="max-width: 60px;">
                                </div>
                            </div>
                            <!-- <div class="col-sm col-6 mg-t-10">
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'sber1'" onclick="$('#depositSystem').val('8'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment/sber.svg?v=1" style="max-width: 150px;transform:scale(0.6);">
                                </div>
                            </div> -->
                            <div class="col-sm col-6 mg-t-10">
                                <div class="en-refill__payment-label">#2</div>
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'visafk'; sum = 300; comission = '9,5% + 100'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/vmm.svg?v=1" style="max-width: 60px;">
                                </div>
                            </div>

                            <!-- <div class="col-sm col-6 mg-t-10">
                                <div class="en-refill__payment-label">#3</div>
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'visa'; sum = 300; comission = '5%'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/vmm.svg?v=1" style="max-width: 60px;">
                                </div>
                            </div> -->
                            
                            <!-- Данилка пэй -->

                            
                            <div class="col-sm col-6 mg-t-10">
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'yoomoney'; sum = 500; comission = '13,5%'" onclick="$('#depositSystem').val('2'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment/yoomoney.png?v=1" style="max-width: 100px;">
                                </div>
                            </div>
                            <!-- <div class="col-sm col-6 mg-t-10">
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'piastrix'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment-piastrix.svg?v=1" style="max-width: 100px;">
                                </div>
                            </div> -->
                            <div class="col-sm col-6 mg-t-10">
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'piastrix1'; comission = '4%'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment-piastrix.svg?v=1" style="max-width: 100px;">
                                </div>
                            </div>
                            <!-- <div class="col-sm col-6 mg-t-10">
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'alfa'" onclick="$('#depositSystem').val('6'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment/alfa.svg?v=1" style="max-width: 150px;transform:scale(0.6);">
                                </div>
                            </div>
                            <div class="col-sm col-6 mg-t-10">
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'tinkoff'" onclick="$('#depositSystem').val('7'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment/tinkoff.svg?v=1" style="max-width: 150px;transform:scale(0.6);">
                                </div>
                            </div>-->
                            
                            <!-- <div class="col-sm col-6 mg-t-10">
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'vtb'" onclick="$('#depositSystem').val('9'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment/vtb.svg?v=1" style="max-width: 100px;transform:scale(0.6);">
                                </div>
                            </div>
                            <div class="col-sm col-6 mg-t-10">
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'raif'" onclick="$('#depositSystem').val('10'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment/raif.svg?v=1" style="max-width: 150px;transform:scale(0.6);">
                                </div>
                            </div>
                            <div class="col-sm col-6 mg-t-10">
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'otkritie'" onclick="$('#depositSystem').val('11'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment/otkritie.svg?v=1" style="max-width: 150px;transform:scale(1); margin-left: 20px;">
                                </div>
                            </div>  -->

                            <!-- <div class="col-sm col-6 mg-t-10">
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'mobile'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment/mobile.png?v=1" style="max-width: 100px;">
                                </div>
                            </div> -->
                            <!-- <div class="col-sm col-6 mg-t-10">
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'vkpay'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment/vk_pay.png?v=1" style="max-width: 70px;">
                                </div>
                            </div> -->
                            <!-- <div class="col-sm col-6 mg-t-10">
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'steam'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment/steam.png?v=1" style="max-width: 70px;">
                                </div>
                            </div> -->
                            <!-- <div class="col-sm col-6 mg-t-10">
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 'payeer'" onclick="$('#depositSystem').val('1'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment/payeer.png?v=1" style="max-width: 70px;">
                                </div>
                            </div> -->
                            <!-- <div class="col-sm col-6 mg-t-10">
                                <div class="wd-150 ht-65 bg-gray-100 mg-l-5 btn-deposit d-inline-flex align-items-center justify-content-center" @click="system = 3" onclick="$('#depositSystem').val('3'); $('.btn-deposit').removeClass('bd-gray-500 bd-1 bd') ;$(this).addClass('bd-gray-500 bd-1 bd')" style="height: 55px!important;width: 100%!important;">
                                    <img src="/img/payment/freekassa.png?v=1" style="max-width: 100px;">
                                </div>
                            </div> -->
                            <div class="row-md"></div>
                        </div>
                    </div>
                    <div class="form-group mg-t-15">
                        <label v-if="system == 'qiwi1'" class="tx-normal la-mob">Ваш номер телефона:</label>
                        <div v-if="system == 'qiwi1'" id="the-basics" class="input-group tx-light tx-16 col-6 pd-0 mb-2">
                            <input id="depositQiwiNumber" placeholder="+7XXXXXXXXX" @input="validateQiwi" autocomplete="off" class="form-control tx-normal " v-model="num" style="border-top-right-radius: 5px;border-bottom-right-radius: 5px;">
                        </div>
                        <label v-if="system == 'qiwi1'" class="tx-normal la-mob">Сумма: (мин: 1000)</label>
                        <label v-else-if="system == 'visafk'" class="tx-normal la-mob">Сумма: (мин: 1000)</label>
                        <label v-else-if="system == 'visa'" class="tx-normal la-mob">Сумма: (мин: 100 - макс: 5000)</label>
                        <label v-else-if="system == 'alfa' || system == 'tinkoff' || system == 'vtb' || system == 'otkritie' || system == 'raif' || system == 'sber' || system == 'sber1' || system == 'card'" class="tx-normal la-mob">Сумма: (мин: 300 - макс: 5000)</label>
                        <label v-else-if="system == 'yoomoney'" class="tx-normal la-mob">Сумма: (мин: 500)</label>
                        <label v-else-if="system == 'fkwallet' || system == 'piastrix' || system == 'piastrix1'" class="tx-normal la-mob">Сумма: (мин: 100)</label>
                        <label v-else-if="system == 'qiwi'" class="tx-normal la-mob">Сумма: (мин: 100 - макс: 5000)</label>
                        <label v-else-if="system == 'qiwi2'" class="tx-normal la-mob">Сумма: (мин: 100 - макс: 5000)</label>
                        <label v-else class="tx-normal la-mob">Сумма:</label>
                        <div id="the-basics" class="input-group tx-light tx-16 col-6 pd-0 mb-2">
                            <input id="depositSize" autocomplete="off" class="form-control tx-normal " v-model="sum" style="border-top-right-radius: 5px;border-bottom-right-radius: 5px;">
                            <input id="depositSystem" autocomplete="off" class="form-control tx-normal d-none" placeholder="">
                        </div>
                        <span class="nav-line-profile badge bt-com" style="margin-bottom:10px; background-color: #e5e9f2;color: #8392a5;" v-show="system">Комиссия: {{ comission }}</span><br v-show="system">
                        <div class="d-none d-sm-flex flex-row justify-content-start" style="margin-bottom: .5rem;">
                            <button id="depositSumButton300" @click="sum = 300" class="btn tx-normal btn-la-mob" style="margin-right: 10px; background-color: #394049; color: #fff; border-color: #3c434c; padding: 8px 18px;">300</button>
                            <button id="depositSumButton500" @click="sum = 500" class="btn tx-normal btn-la-mob" style="margin-right: 10px; background-color: #394049; color: #fff; border-color: #3c434c; padding: 8px 18px;">500</button>
                            <button id="depositSumButton700" @click="sum = 700" class="btn tx-normal btn-la-mob" style="margin-right: 10px; background-color: #394049; color: #fff; border-color: #3c434c; padding: 8px 18px;">700</button>
                            <button id="depositSumButton1000" @click="sum = 1000" class="btn tx-normal btn-la-mob" style="margin-right: 10px; background-color: #394049; color: #fff; border-color: #3c434c; padding: 8px 18px;">1000</button>
                        </div>

                        <label class="tx-normal la-mob">Промокод:</label>
                        <div id="the-basics" class="input-group tx-light tx-16 col-6 pd-0">
                            <input id="depositPromo" autocomplete="off" class="form-control tx-normal " v-model="promo" style="border-top-right-radius: 5px;border-bottom-right-radius: 5px;" value="">
                        </div>
                        <span class="nav-line-profile badge bt-com" style="margin-top:10px; background-color: #e5e9f2;color: #8392a5;display: none;">Комиссия: 0%</span>
                    </div>
                    <button id="depositButton" @click="deposit" class="btn btn-primary tx-normal btn-la-mob" style="padding: 8px 18px;">Далее</button>
                    <button id="error_deposit" style="margin-left: 7px; padding: 8px 22px; pointer-events: none; color: #fff !important;" v-if="errors.show"
                            class="btn tx-medium btn-la-mob bg-danger-dice tx-white bd-0 btn-sel-d ">
                        {{ errors.message }}
                    </button>
                    <div class="table-responsive mg-t-30 ">
                        <div class="table-responsive">
                            <table class="table table-sm mg-b-0 table-deposit">
                                <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Дата</th>
                                    <th scope="col">Сумма</th>
                                    <th scope="col">Статус</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="payment in payments">
                                        <th>{{ payment.id }}</th>
                                        <th>{{ payment.time }}</th>
                                        <th>{{ payment.sum }}</th>
                                        <th>{{ payment.status }}</th>
                                    </tr>
                                </tbody>
                            </table>
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
                num: '',
                sum: 200,
                promo: '',
                comission: '',
                system: 0,
                errors: {
                    show: false,
                    message: ''
                },
                disableBtn: false,
                payments: []
            }
        },
        mounted() {
            this.getPayments();
        },
        methods: {
            validateQiwi() {
                if (this.system == 'qiwi1') {                    
                    if (this.num.indexOf('8') == 0) {
                        this.num = this.num.replace('8', '7')
                    }
                    if (this.num.indexOf('+') == 0) {
                        this.num = this.num.replace('+', '')
                    }
                }
            },
            deposit() {
                var sum = Math.round(this.sum);
                this.sum = sum;
                if (this.disableBtn) {
                    return;
                }
                if(this.system == 0) {
                    return this.errors = {
                        show: true,
                        message: 'Выберите платежную систему'
                    }
                }
                if (this.system == 'qiwi1') { 
                    if (this.num.length <= 0) {
                        return this.errors = {
                            show: true,
                            message: 'Введите номер телефона'
                        }
                    } 
                }
                //if((this.system == 'qiwi' && this.num.length != 12) || (this.system == 'qiwi' && this.num.indexOf('9') != 2)) {
                //    return this.errors = {
                //        show: true,
                //        message: 'Проверьте правильность введенного номера'
                //    }
                //}
                this.disableBtn = true;
                this.errors.show = false;

                this.$root.axios.post('/payment/create', {
                    sum: this.sum,
                    system: this.system,
                    promo: this.promo,
                    num: this.num,
                    
                }).then(res => {
                    const data = res.data;

                    if (data.success) {
                        window.location.href = data.url;
                    } else {
                        this.errors = {
                            show: true,
                            message: data.message
                        }

                        this.disableBtn = false;
                    }
                });
            },
            getPayments() {
                this.$root.axios.post('/payment/get').then(res => {
                    this.payments = res.data;
                });
            }
        }
    }
</script>

<style type="text/css">
    .en-refill__payment-label {
        color: #fff !important;
        position: absolute;
        z-index: 1;
        top: 5px;
        right: 5px;
        padding: 4px 6px;
        font-size: 11px;
        background-color: rgba(58,61,82,.7);
        border-radius: 6px;
        transition: background-color .3s ease;
    }
    .en-refill__payment-btn--active .en-refill__payment-label, .en-refill__payment-btn:hover .en-refill__payment-label {
        background-color: rgba(73,76,100,.7);
    }
</style>