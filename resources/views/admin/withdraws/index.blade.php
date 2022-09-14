@extends('admin/layout')

@section('content')
<script src="/dash/js/dtables.js?v=2" type="text/javascript"></script>
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">Выплаты</h3>
    </div>
</div>

<div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-information"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Активные выплаты
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dtable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Сумма</th>
                        <td>Система</td>
                        <td>Кошелек</td>
                        <th>Пользователь</th>
                       <td style="max-width: 80px;">Совпадений</td>
                        <td>Причина</td>
                        <td>Инфо</td>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Withdraw::query()->where([['status', 0], ['fake', 0]])->limit(250)->get() as $withdraw)
                        @if (\App\User::query()->find($withdraw->user_id))
                        <?php
                            $user = \App\User::query()->find($withdraw->user_id);

                          //  $cnt = 0;
                            
                          $iskl = [];
                   //         foreach (\App\Wallets::query()->where([['wallet', 'like', '%' . $withdraw->wallet . '%'], ['user_id', '!=', $withdraw->user_id], ['is_included', 0]])->get() as $isk) {
                       //         $userban = \App\User::query()->find($isk->user_id);
                        //        if ($userban->ban != 1) {
                           //         $iskl[] = intval($isk->user_id);
                //                }                                
                     //       }
                     //       $wdrw = \App\Withdraw::query()->where([['fake', 0], ['wallet', 'like', '%' . $withdraw->wallet . '%'], ['user_id', '!=', $withdraw->user_id], ['created_at', '>=', date('2022-05-10').' 00:00:00'] ])->whereNotIn('user_id', $iskl)->groupBy('user_id')->get()->count('user_id');

                        ?>
                            <tr>                       
                                <td>{{ $withdraw->id }}</td>

                                <td>
                                @if ($withdraw->system == 14 || $withdraw->system == 21)
                                    {{ round($withdraw->sum * 0.97, 0) }}
                                @elseif ($withdraw->system == 12) 
                                    {{round($withdraw->sum, 0)}}
                                @elseif ($withdraw->system == 9) 
                                    {{round($withdraw->sum, 0)}}
                                @elseif ($withdraw->system == 4) 
                                    {{round($withdraw->sum, 0)}}
                                @elseif ($withdraw->system == 20) 
                                    {{round($withdraw->sum * 0.98, 0)}}
                                @elseif ($withdraw->system == 15) 
                                    {{round($withdraw->sum * 0.96, 0)}}
                                @elseif ($withdraw->system == 16)
                                    {{$withdraw->sum}}
                                @elseif ($withdraw->system == 1) 
                                    {{ round($withdraw->sum, 0) }}
                                @else {{ round($withdraw->sum, 0) }}
                                @endif р. ({{ $withdraw->sumNoCom }}р.)</td>

                                <td>{{ \App\Http\Controllers\SocketWithdrawController::SYSTEMS[$withdraw->system]['title'] }}</td>
                                <td>{{ $withdraw->wallet }}</td>
                                <td><a href="/admin/users/edit/{{$withdraw->user_id}}">{{ $user->username }} @if($user->is_youtuber) <span style="color: red">[YT]</span> @endif</a></td>
                                <td>
                                    <li style="list-style: circle;">Совпадения реквизитов: {{ \App\Withdraw::query()->where([['fake', 0], ['wallet', 'like', '%' . $withdraw->wallet . '%'], ['user_id', '!=', $withdraw->user_id], ['created_at', '>=', date('2022-05-10').' 10:00:00']])->groupBy('user_id')->get()->count('user_id') + \App\Payment::query()->where([['fake', 0], ['from_wallet', 'like', '%' . $withdraw->wallet . '%'], ['user_id', '!=', $withdraw->user_id], ['created_at', '>=', date('2022-05-10').' 10:00:00']])->groupBy('user_id')->get()->count('user_id')}}</li>
                                </td>
                                <td>@if ($withdraw->reason !== null) @foreach($withdraw->reason as $reason) <li style="list-style: circle;">{{ $reason }}</li> @endforeach @endif</td>
                                <td>@if ($withdraw->info !== null) @foreach(json_decode($withdraw->info) as $info) <li style="list-style: circle;">{{ $info }}</li> @endforeach @endif</td>
                                @if ($withdraw->status === 0 && $withdraw->id !== 9100)
                                <td>                                
                                <!-- <a href="{{ route('admin.withdraws.test', ['id' => $withdraw->id, 'status' => 1]) }}" class="btn btn-sm btn-success btn-sm btn-icon btn-icon-md" title="Выплатить"><i class="la la-check"></i>Тестовый</a> -->
                                <a href="{{ route('admin.withdraws.save', ['id' => $withdraw->id, 'status' => 1]) }}" class="btn btn-sm btn-success btn-sm btn-icon btn-icon-md" title="Выплатить"><i class="la la-check"></i></a>
                                <a href="{{ route('admin.withdraws.save', ['id' => $withdraw->id, 'status' => 2]) }}" class="btn btn-sm btn-danger btn-sm btn-icon btn-icon-md" title="Удалить"><i class="la la-trash"></i></a>
                                </td>
                                @endif
                            </tr>
                            <!-- <p>{{ var_dump($withdraw->info) }}</p>
                            <p>{{ var_dump($withdraw->reason) }}</p> -->
                        @endif
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
                    <i class="kt-font-brand flaticon2-checkmark"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Со вчера по сегодня
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="row" style="margin-right: 0; margin-left: auto; margin-bottom: 15px;">
                <p style="margin-top: 5px;margin-right: 10px;">С </p><input id="start" type="date" style="margin-right: 10px;"><p style="margin-top: 5px;margin-right: 10px;">По </p><input id="end" type="date" style="margin-right: 10px;"> <button id="show" class="btn btn-success">Показать</button>
            </div>

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dtable5">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Пользователь</th>
                        <th>Сумма</th>
                        <td>Кошелек</td>
                        <td>Система</td>
                        <td>FAKE</td>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $yest = Carbon\Carbon::yesterday();
                    ?>
                @foreach(\App\Withdraw::query()->where([['status', 1], ['fake', 0], ['created_at', '>', $yest]])->orWhere([['status', 3], ['fake', 0], ['created_at', '>', $yest]])->get() as $withdraw1)
                    @if (\App\User::query()->find($withdraw1->user_id))
                    <?php
                        $user = \App\User::query()->find($withdraw1->user_id);
                    ?>
                        <tr>
                            <td>{{ $withdraw1->id }}</td>
                            <td><a target="_blank" href="/admin/users/edit/{{$withdraw1->user_id}}">{{ $user->username }} @if($user->is_youtuber) <span style="color: red">[YT]</span> @endif</a></td>
                            <td>
                                @if ($withdraw1->system == 14 || $withdraw1->system == 21)
                                    {{ round($withdraw1->sum * 0.97, 0) }}
                                @elseif ($withdraw1->system == 12) 
                                    {{round($withdraw1->sum, 0)}}
                                @elseif ($withdraw1->system == 9) 
                                    {{round($withdraw1->sum, 0)}}
                                @elseif ($withdraw1->system == 4) 
                                    {{round($withdraw1->sum, 0)}}
                                @elseif ($withdraw1->system == 20) 
                                    {{round($withdraw1->sum * 0.98, 0)}}
                                @elseif ($withdraw1->system == 15) 
                                    {{round($withdraw1->sum * 0.96, 0)}}
                                @elseif ($withdraw1->system == 16)
                                    {{$withdraw1->sum}}
                                @elseif ($withdraw1->system == 1) 
                                    {{ round($withdraw1->sum, 0) }}
                                @else {{ round($withdraw1->sum, 0) }}
                                @endif
                            </td>
                            
                            <td>{{ $withdraw1->wallet }}</td>
                            <td>{{ \App\Http\Controllers\SocketWithdrawController::SYSTEMS[$withdraw1->system]['title'] }}</td>
                            <td>@if($user->is_youtuber) <span style="color: red">[YT]</span> @endif</td>
                        </tr>
                    @endif
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
                    <i class="kt-font-brand flaticon2-checkmark"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Авто выплаты
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dtable4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Пользователь</th>
                        <th>Сумма</th>
                        <td>Кошелек</td>
                        <td>Система</td>
                        <td>FAKE</td>
                    </tr>
                </thead>
                <tbody>
                @foreach(\App\Withdraw::query()->orderBy('id', 'desc')->where([['status', '!=', 0], ['is_auto', 1]])->limit(50)->get() as $withdraw)
                    @if (\App\User::query()->find($withdraw->user_id))
                    <?php
                        $user = \App\User::query()->find($withdraw->user_id);
                    ?>
                        <tr>
                            <td>{{ $withdraw->id }}</td>
                            <td><a href="/admin/users/edit/{{$withdraw->user_id}}">{{ $user->username }} @if($user->is_youtuber) <span style="color: red">[YT]</span> @endif</a></td>
                            <td>@if ($withdraw->system == 14 || $withdraw->system == 21)
                                    {{ round($withdraw->sum * 0.97, 0) }}
                                @elseif ($withdraw->system == 12) 
                                    {{round($withdraw->sum, 0)}}
                                @elseif ($withdraw->system == 9) 
                                    {{round($withdraw->sum, 0)}}
                                @elseif ($withdraw->system == 4) 
                                    {{round($withdraw->sum, 0)}}
                                @elseif ($withdraw->system == 20) 
                                    {{round($withdraw->sum * 0.98, 0)}}
                                @elseif ($withdraw->system == 15) 
                                    {{round($withdraw->sum * 0.96, 0)}}
                                @elseif ($withdraw->system == 16)
                                    {{$withdraw->sum}}
                                @elseif ($withdraw->system == 1) 
                                    {{ round($withdraw->sum, 0) }}
                                @else {{ round($withdraw->sum, 0) }}
                                @endif р. ({{ $withdraw->sumNoCom }}р.)</td>
                            <td>{{ $withdraw->wallet }}</td>
                            <td>{{ \App\Http\Controllers\SocketWithdrawController::SYSTEMS[$withdraw->system]['title'] }}</td>
                            <td>@if($user->is_youtuber) <span style="color: red">[YT]</span> @endif</td>
                        </tr>
                    @endif
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
                    <i class="kt-font-brand flaticon2-checkmark"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Обработанные запросы
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dtable2">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Пользователь</th>
                        <th>Сумма</th>
                        <td>Кошелек</td>
                        <td>Система</td>
                    </tr>
                </thead>
                <tbody>
                @foreach(\App\Withdraw::query()->orderByDesc('id')->where([['status', 1], ['fake', 0]])->limit(50)->get()->sort() as $withdraw)
                    @if (\App\User::query()->find($withdraw->user_id))
                        <tr>
                            <td>{{ $withdraw->id }}</td>
                            <td><a href="/admin/users/edit/{{$withdraw->user_id}}">{{ \App\User::query()->find($withdraw->user_id)->username }}</a></td>
                            <td>{{ $withdraw->sum }}р.</td>
                            <td>{{ $withdraw->wallet }}</td>
                            <td>{{ \App\Http\Controllers\SocketWithdrawController::SYSTEMS[$withdraw->system]['title'] }}</td>
                        </tr>
                    @endif
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
                    <i class="kt-font-brand flaticon2-close-cross"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Отклоненные запросы
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dtable3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Пользователь</th>
                        <th>Сумма</th>
                        <td>Кошелек</td>
                        <td>Система</td>
                    </tr>
                </thead>
                <tbody>
                @foreach(\App\Withdraw::query()->orderByDesc('id')->where([['status', 2], ['fake', 0]])->limit(50)->get()->sort() as $withdraw)
                    @if (\App\User::query()->find($withdraw->user_id))
                        <tr>
                            <td>{{ $withdraw->id }}</td>
                            <td><a href="/admin/users/edit/{{$withdraw->user_id}}">{{ \App\User::query()->find($withdraw->user_id)->username }}</a></td>
                            <td>{{ $withdraw->sum }}р.</td>
                            <td>{{ $withdraw->wallet }}</td>
                            <td>{{ \App\Http\Controllers\SocketWithdrawController::SYSTEMS[$withdraw->system]['title'] }}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>

<style type="text/css">
    @media(max-width:  425px) {
        #show {
            margin: auto;
            margin-top: 10px;
            width: 130px;
        }
    }
</style>

<script type="text/javascript">
    $('#show').click(function(){
        $.ajax({
            type: "POST",
            url: "/admin/getfromdate",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                startDate: $('#start').val(),
                endDate: $('#end').val()
            }
        })
        .done(function( res ) {
            $('#dtable5').html(res)
        });
    });
</script>
@endsection