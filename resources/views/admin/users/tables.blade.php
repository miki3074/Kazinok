@extends('admin/layout')

@section('content')
<script src="/dash/js/dtables.js?v=1" type="text/javascript"></script>

<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">Пользователи</h3>
    </div>
</div>

<div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-users"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Список мульти-аккаунтов
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dtable3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Логин</th>
                        <th>Баланс</th>
                        <td>Регистрация</td>
                        <td>Активность</td>
                        <td>IP при регистрации</td>
                        <td>IP при входе</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mults as $mult)
                    <tr>
                        <td>{{ $mult['id'] }}</td>
                        <td><a href="/admin/users/edit/{{ $mult['id'] }}">{{ $mult['username'] }}</a></td>
                        <td>{{ $mult['balance'] }}</td>
                        <td>{{ $mult['created_at'] }}</td>
                        <td>{{ $mult['updated_at'] }}</td>
                        <td>{{ $mult['created_ip'] }}</td>
                        <td>{{ $mult['used_ip'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-information"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Список выплат
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dtable5">
                <thead>
                    <tr>                     
                        <th>Сумма</th>
                        <td>Статус</td> 
                        <td>Система</td>
                        <th>ID</th>
                        <td>Кошелек</td>
                        <td>Совпадений</td>
                        <td>Дата</td>
                    </tr>
                </thead>
                <tbody>
                @foreach(\App\Withdraw::query()->where([['user_id', $user->id], ['fake', 0]])->limit(50)->get() as $withdraw)
                    <tr>
                        <td>{{ $withdraw->sum }}р. ({{ $withdraw->sumNoCom }}р.)</td>
                        <td>@if($withdraw->status == 0) Ожидает @elseif($withdraw->status == 1) Оплачен @elseif($withdraw->status == 3) В обработке @else Отменен @endif</td>
                        <td>{{ \App\Http\Controllers\SocketWithdrawController::SYSTEMS[$withdraw->system]['title'] }}</td>
                        <td>{{ $withdraw->id }}</td>
                        <td>{{ $withdraw->wallet }}</td>
                        <td>{{ \App\Withdraw::query()->where([['fake', 0], ['wallet', 'like', '%' . $withdraw->wallet . '%'], ['user_id', '!=', $withdraw->user_id], ['created_at', '>=', date('2022-05-10').' 10:00:00']])->groupBy('user_id')->get()->count('user_id') + \App\Payment::query()->where([['fake', 0], ['from_wallet', 'like', '%' . $withdraw->wallet . '%'], ['user_id', '!=', $withdraw->user_id], ['created_at', '>=', date('2022-05-10').' 10:00:00']])->groupBy('user_id')->get()->count('user_id') }}</td>
                        <td data-order="{{ $withdraw->created_at->format('Y-m-d H:i:s') }}">{{ $withdraw->created_at->format('d-m-Y H:i:s') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-information"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Список депозитов
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dtable6">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Сумма</th>
                        <th>Система</th>
                        <th>Мерчант ID</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                <tbody>
                @foreach(\App\Payment::query()->where([['user_id', $user->id], ['status', 1]])->orWhere([['user_id', $user->id], ['system', 'rubpay - sber']])->orWhere([['user_id', $user->id], ['system', 'swiftpay']])->orderBy('updated_at', 'desc')->limit(50)->get() as $deposit)
                    <tr>
                        <td>{{ $deposit->id }}</td>
                        <td>{{ $deposit->sum }}</td>
                        <td>@if ($deposit->system == null) Админ @else{{ $deposit->system }} @endif</td>
                        <td>{{ $deposit->rubpay_id }}</td>
                        <!-- <td>@if($deposit->status == 0) Ожидает @elseif($deposit->status == 1) Оплачен @else Отменен @endif</td> -->
                        <td data-order="{{ $deposit->created_at->format('Y-m-d H:i:s') }}">{{ $deposit->created_at->format('d-m-Y H:i:s') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!--end: Datatable -->
        </div>
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
