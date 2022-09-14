@extends('admin/layout')

@section('content')
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">Настройки</h3>
    </div>
</div>

<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--tabs">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-toolbar">
                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#site" role="tab" aria-selected="true">
                            Настройки сайта
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#classic" role="tab" aria-selected="true">
                            Настройки Classic
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <form class="kt-form" method="post" action="{{ route('admin.settings') }}">
            <div class="kt-portlet__body">
                <div class="tab-content">
                    <div class="tab-pane active" id="site" role="tabpanel">
                        <div class="kt-section">
                            <h3 class="kt-section__title">
                                Общие настройки:
                            </h3>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Платежи(вкл\выкл):</label>
                                    <select class="form-control" name="payments_on">
                                        <option value="1" @if($settings->payments_on == 1) selected @endif>Включены</option>
                                        <option value="0" @if($settings->payments_on == 0) selected @endif>Отключены</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label>Выплаты(вкл\выкл):</label>
                                    <select class="form-control" name="withdraws_on">
                                        <option value="1" @if($settings->withdraws_on == 1) selected @endif>Включены</option>
                                        <option value="0" @if($settings->withdraws_on == 0) selected @endif>Отключены</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label>Деп и выводы только через FK(вкл\выкл):</label>
                                    <select class="form-control" name="fkwallet_only">
                                        <option value="1" @if($settings->fkwallet_only == 1) selected @endif>Включены</option>
                                        <option value="0" @if($settings->fkwallet_only == 0) selected @endif>Отключены</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Деп и вывод через киви и карты XMPay:</label>
                                    <select class="form-control" name="xmpay_off">
                                        <option value="1" @if($settings->xmpay_off == 1) selected @endif>Отключены</option>
                                        <option value="0" @if($settings->xmpay_off == 0) selected @endif>Включены</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Заголовок сайта (титул):</label>
                                    <input type="text" class="form-control" placeholder="sitename.ru - краткое описание" value="{{$settings->title}}" name="title">
                                </div>
                                <div class="col-lg-4">
                                    <label>Описание для поисковых систем:</label>
                                    <input type="text" class="form-control" placeholder="Описание для сайта..." value="{{$settings->description}}" name="description">
                                </div>
                                <div class="col-lg-4">
                                    <label>Ключевые слова для поисковых систем:</label>
                                    <input type="text" class="form-control" placeholder="сайт, имя, домен и тд..." value="{{$settings->keywords}}" name="keywords">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Бонус за привязку VK:</label>
                                    <input type="text" class="form-control" placeholder="3..." value="{{$settings->connect_bonus}}" name="connect_bonus">
                                </div>
                            </div>
                        </div>
                        <div class="kt-section">
                            <h3 class="kt-section__title">
                                Настройки реферальной системы:
                            </h3>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>% от депозита:</label>
                                    <input type="text" class="form-control" placeholder="Введите процент" value="{{$settings->ref_perc}}" name="ref_perc">
                                </div>
                                <div class="col-lg-4">
                                    <label>Сумма за 1 рефа:</label>
                                    <input type="text" class="form-control" placeholder="Введите сумму" value="{{$settings->ref_bonus}}" name="ref_bonus">
                                </div>
                            </div>
                        </div>
                        <div class="kt-section">
                            <h3 class="kt-section__title">
                                Остальные настройки:
                            </h3>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Минимальная сумма пополнения:</label>
                                    <input type="text" class="form-control" placeholder="Введите сумму" value="{{$settings->min_payment_sum}}" name="min_payment_sum">
                                </div>
                                <div class="col-lg-4">
                                    <label>Сумма пополнений для получения бонуса:</label>
                                    <input type="text" class="form-control" placeholder="Введите сумму" value="{{$settings->min_bonus_sum}}" name="min_bonus_sum">
                                </div>
                                <div class="col-lg-4">
                                    <label>Раз в N минут бот сыграет игру:</label>
                                    <input type="text" class="form-control" placeholder="Введите число" value="{{$settings->bot_timer}}" name="bot_timer">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Сумма пополнений для совершения вывода:</label>
                                    <input type="text" class="form-control" placeholder="Введите сумму" value="{{$settings->min_dep_withdraw}}" name="min_dep_withdraw">
                                </div>
                                <div class="col-lg-4">
                                    <label>Минимальная сумма для вывода:</label>
                                    <input type="text" class="form-control" placeholder="Введите сумму" value="{{$settings->min_withdraw_sum}}" name="min_withdraw_sum">
                                </div>
                                <div class="col-lg-4">
                                    <label>Максимальное кол-во выплат в ожидании:</label>
                                    <input type="text" class="form-control" placeholder="Введите кол-во" value="{{$settings->withdraw_request_limit}}" name="withdraw_request_limit">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Максимальная сумма для вывода на QIWI, Yoo, Card:</label>
                                    <input type="text" class="form-control" placeholder="Введите сумму" value="{{$settings->max_qyc_withdraw_sum}}" name="max_qyc_withdraw_sum">
                                </div>
                                <div class="col-lg-4">
                                    <label>Максимальная сумма для вывода на остальные:</label>
                                    <input type="text" class="form-control" placeholder="Введите сумму" value="{{$settings->max_withdraw_sum}}" name="max_withdraw_sum">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Сумма депозита для вывода за N дней:</label>
                                    <input type="text" class="form-control" placeholder="Введите сумму" value="{{$settings->deposit_sum_n}}" name="deposit_sum_n">
                                </div>
                                <div class="col-lg-4">
                                    <label>Каждые N дней депозит:</label>
                                    <input type="text" class="form-control" placeholder="Введите сумму" value="{{$settings->deposit_per_n}}" name="deposit_per_n">
                                </div>
                            </div>
                        </div>
                        <div class="kt-section">
                            <h3 class="kt-section__title">
                                Настройки антиминуса:
                            </h3>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Антиминус:</label>
                                    <select class="form-control" name="antiminus">
                                        <option value="1" @if($settings->antiminus == 1) selected @endif>Включен</option>
                                        <option value="0" @if($settings->antiminus == 0) selected @endif>Отключен</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label>Банк в Dice:</label>
                                    <input type="text" class="form-control" placeholder="" value="{{\App\Profit::query()->find(1)->bank_dice}}" name="bank_dice">
                                </div>
                                <div class="col-lg-4">
                                    <label>Банк в Mines:</label>
                                    <input type="text" class="form-control" placeholder="" value="{{\App\Profit::query()->find(1)->bank_mines}}" name="bank_mines">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Банк в Coinflip:</label>
                                    <input type="text" class="form-control" placeholder="" value="{{\App\Profit::query()->find(1)->bank_coinflip}}" name="bank_coinflip">
                                </div>
                                <div class="col-lg-4">
                                    <label>Банк в Wheel:</label>
                                    <input type="text" class="form-control" placeholder="" value="{{\App\Profit::query()->find(1)->bank_wheel}}" name="bank_wheel">
                                </div>                               
                                <div class="col-lg-4">
                                    <label>Комиссия в банк сайта:</label>
                                    <input type="text" class="form-control" placeholder="" value="{{\App\Profit::query()->find(1)->comission}}" name="comission">
                                </div>
                            </div>
                        </div>
                        <div class="kt-section">
                            <h3 class="kt-section__title">
                                Настройки группы VK:
                            </h3>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Ссылка на группу VK:</label>
                                    <input type="text" class="form-control" placeholder="https://vk.com/..." value="{{$settings->vk_url}}" name="vk_url">
                                </div>
                                <div class="col-lg-4">
                                    <label>ID Группы VK:</label>
                                    <input type="text" class="form-control" placeholder="192337402..." value="{{$settings->vk_id}}" name="vk_id">
                                </div>
                                <div class="col-lg-4">
                                    <label>Ключ доступа:</label>
                                    <input type="text" class="form-control" placeholder="1f27230c1f27230c1f27230c841..." value="{{$settings->vk_token}}" name="vk_token">
                                </div>
                            </div>
                        </div>
                        <div class="kt-section">
                            <h3 class="kt-section__title">
                                Настройки платежной системы FreeKassa:
                            </h3>
                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <label>ID Магазина:</label>
                                    <input type="text" class="form-control" placeholder="xxxxxx" value="{{$settings->kassa_id}}" name="kassa_id">
                                </div>
                                <div class="col-lg-3">
                                    <label>Секрет 1:</label>
                                    <input type="text" class="form-control" placeholder="xxxxxxx" value="{{$settings->kassa_secret1}}" name="kassa_secret1">
                                </div>
                                <div class="col-lg-3">
                                    <label>Секрет 2:</label>
                                    <input type="text" class="form-control" placeholder="xxxxxxx" value="{{$settings->kassa_secret2}}" name="kassa_secret2">
                                </div>
                                <div class="col-lg-3">
                                    <label>API ключ:</label>
                                    <input type="text" class="form-control" placeholder="xxxxxxx" value="{{$settings->kassa_key}}" name="kassa_key">
                                </div>
                            </div>
                        </div>
                        <div class="kt-section">
                            <h3 class="kt-section__title">
                                Настройки платежной системы SwiftPay:
                            </h3>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>ID Магазина:</label>
                                    <input type="text" class="form-control" placeholder="xxxxxx" value="{{$settings->swift_shop}}" name="swift_shop">
                                </div>
                                <div class="col-lg-4">
                                    <label>API ключ:</label>
                                    <input type="text" class="form-control" placeholder="xxxxxxx" value="{{$settings->swift_api}}" name="swift_api">
                                </div>
                            </div>
                        </div>
                        <div class="kt-section">
                            <h3 class="kt-section__title">
                                Настройки платежной системы XMPay:
                            </h3>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>ID Магазина:</label>
                                    <input type="text" class="form-control" placeholder="xxxxxx" value="{{$settings->xmpay_id}}" name="xmpay_id">
                                </div>
                                <div class="col-lg-4">
                                    <label>Секрет 1:</label>
                                    <input type="text" class="form-control" placeholder="xxxxxxx" value="{{$settings->xmpay_public}}" name="xmpay_public">
                                </div>
                                <div class="col-lg-4">
                                    <label>Секрет 2:</label>
                                    <input type="text" class="form-control" placeholder="xxxxxxx" value="{{$settings->xmpay_secret}}" name="xmpay_secret">
                                </div>
                            </div>
                        </div>
                        <div class="kt-section">
                            <h3 class="kt-section__title">
                                Настройки платежной системы RubPay:
                            </h3>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>ID Магазина:</label>
                                    <input type="text" class="form-control" placeholder="xxxxxx" value="{{$settings->rubpay_id}}" name="rubpay_id">
                                </div>
                                <div class="col-lg-4">
                                    <label>API ключ:</label>
                                    <input type="text" class="form-control" placeholder="xxxxxxx" value="{{$settings->rubpay_api}}" name="rubpay_api">
                                </div>
                            </div>
                        </div>
                        <div class="kt-section">
                            <h3 class="kt-section__title">
                                Настройки автовыплат:
                            </h3>
                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <label>Кошелек FkWallet:</label>
                                    <input type="text" class="form-control" placeholder="xxxxxx" value="{{$settings->wallet_id}}" name="wallet_id">
                                </div>
                                <div class="col-lg-3">
                                    <label>Ключ кошелька:</label>
                                    <input type="text" class="form-control" placeholder="xxxxxxx" value="{{$settings->wallet_secret}}" name="wallet_secret">
                                </div>
                                <div class="col-lg-3">
                                    <label>Примечание:</label>
                                    <input type="text" class="form-control" placeholder="xxxxxxx" value="{{$settings->wallet_desc}}" name="wallet_desc">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="classic" role="tabpanel">
                        <div class="kt-section">
                            <h3 class="kt-section__title">
                                Общие настройки:
                            </h3>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Комиссия в Classic:</label>
                                    <input type="text" class="form-control" placeholder="10" value="{{\App\Profit::query()->find(1)->jackpot_comission}}" name="jackpot_comission">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
</div>
<style>
    @media (max-width:1100px) {
        .col-lg-4 {
            margin-top: 20px;
        }
    }
</style>
@endsection
