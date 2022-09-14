<template>
    <div style="height: 81vh;">
        <div class="card">
            <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                <div>
                    <h4 class="mg-b-5 ">Ответы на вопросы</h4>
                </div>
            </div>
            <div class="card-body pd-20 bd-b pd-b-20">
                <div class="row">
                    <div class="col-12">
                        <div class=" card-crypto">
                            <div id="accordion2" class="accordion ui-accordion ui-widget ui-helper-reset" role="tablist">
                                <h6 class="accordion-title ui-accordion-header ui-corner-top ui-state-default ui-accordion-header-active ui-state-active ui-accordion-icons" role="tab" id="ui-id-1" aria-controls="ui-id-2" aria-selected="true" aria-expanded="true" tabindex="0"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s"></span>Через сколько приходит выплата?</h6>
                                <div class="accordion-body ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content ui-accordion-content-active" style="" id="ui-id-2" aria-labelledby="ui-id-1" role="tabpanel" aria-hidden="false">Вывод средств осуществляется в 2 этапа. На 1 этапе вывод имеет статус ожидание до 72 часов. На 2 этапе вывод меняет статус на "в обработке", который длится до 72 часов. Очень редко, но бывает, что при статусе "выплачено" деньги не пришли, такие выводы приходят в течение до 24 часов.</div>
                                <h6 class="accordion-title ui-accordion-header ui-corner-top ui-accordion-header-collapsed ui-corner-all ui-state-default ui-accordion-icons" role="tab" id="ui-id-3" aria-controls="ui-id-4" aria-selected="false" aria-expanded="false" tabindex="-1"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Что такое реферальная система?</h6>
                                <div class="accordion-body ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content" style="display: none;" id="ui-id-4" aria-labelledby="ui-id-3" role="tabpanel" aria-hidden="true">Реферальная система - это система вознаграждения участников за привлечение новых клиентов. Наличие этой системы позволяет Вам приводить людей по своей уникальной ссылке и получать от <b>{{ refshare }}%</b> суммы прибыли казино с каждого реферала <b>сразу на счет</b>.</div>
                             <!--   <h6 class="accordion-title ui-accordion-header ui-corner-top ui-accordion-header-collapsed ui-corner-all ui-state-default ui-accordion-icons" role="tab" id="ui-id-8" aria-controls="ui-id-8" aria-selected="false" aria-expanded="false" tabindex="-1"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Реферал зарегистрировался по моей ссылке, но 30 не начислилось</h6> 
                                <div class="accordion-body ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content" style="display: none;" id="ui-id-8" aria-labelledby="ui-id-8" role="tabpanel" aria-hidden="true">За регистрацию реферала вы получаете сразу 5 на баланс. Начисление 25 происходит только за активного реферала. Активный реферал - это реферал, который сделал хотя бы 1 депозит. Соответственно, начисление происходит следующим образом: после депозита реферала вы получаете 15 сразу +10% от его пополнения. Минимальное пополнение 100, поэтому вы получаете минимум 25 за активного реферала.</div> -->
                                <h6 class="accordion-title ui-accordion-header ui-corner-top ui-accordion-header-collapsed ui-corner-all ui-state-default ui-accordion-icons" role="tab" id="ui-id-5" aria-controls="ui-id-6" aria-selected="false" aria-expanded="false" tabindex="-1"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Реферал зарегистрировался по моей ссылке и не появился в списке</h6>
                                <div class="accordion-body ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content" style="display: none;" id="ui-id-6" aria-labelledby="ui-id-5" role="tabpanel" aria-hidden="true">Значит пользователь ранее переходил по чужой реферальной ссылке.</div>
                                <h6 class="accordion-title ui-accordion-header ui-corner-top ui-accordion-header-collapsed ui-corner-all ui-state-default ui-accordion-icons" role="tab" id="ui-id-7" aria-controls="ui-id-8" aria-selected="false" aria-expanded="false" tabindex="-1"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Что делать, если введены неверные реквизиты?</h6>
                                <div class="accordion-body ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content" style="display: none;" id="ui-id-8" aria-labelledby="ui-id-7" role="tabpanel" aria-hidden="true">Если такие реквизиты не существуют в выбранной платежной системе, то средства вернуться на Ваш баланс автоматически. </div>
                                <h6 class="accordion-title ui-accordion-header ui-corner-top ui-accordion-header-collapsed ui-corner-all ui-state-default ui-accordion-icons" role="tab" id="ui-id-9" aria-controls="ui-id-10" aria-selected="false" aria-expanded="false" tabindex="-1"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Как отменить выплату?</h6>
                                <div class="accordion-body ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content" style="display: none;" id="ui-id-10" aria-labelledby="ui-id-9" role="tabpanel" aria-hidden="true">Для отмены выплаты нажмите "Отмена" в разделе "Вывод". Баланс аккаунта мгновенно пополнится на сумму отмененной заявки.</div>
                                <!-- <h6 class="accordion-title ui-accordion-header ui-corner-top ui-accordion-header-collapsed ui-corner-all ui-state-default ui-accordion-icons" role="tab" id="ui-id-11" aria-controls="ui-id-12" aria-selected="false" aria-expanded="false" tabindex="-1"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Где найти промокоды?</h6>
                                <div class="accordion-body ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content" style="display: none;" id="ui-id-12" aria-labelledby="ui-id-11" role="tabpanel" aria-hidden="true">Промокоды можно найти в нашей группе Вк: <a href="https://vk.com/demoney_official" target="_blank">https://vk.com/demoney_official</a></div> -->
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
                ref_percent: 0
            }
        },
        mounted() {
            this.$root.isLoading = true;
            $('#accordion2').accordion({
                heightStyle: 'content',
                collapsible: true
            });
            this.getInfo()
        },
        methods: {
            getInfo() {
                this.$root.axios.post('/getInfo')
                    .then(res => {
                        this.refshare = res.data.refshare;
                        this.$root.isLoading = false;
                    });
            }
        }
    }
</script>
