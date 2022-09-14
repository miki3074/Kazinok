@extends('admin/layout')

@section('content')
<script src="/dash/js/dtables.js?v=11" type="text/javascript"></script>
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">Логи админки</h3>
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
                    Все логи
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dtable">
                <thead>
                    <tr>
                        <th>Ник</th>
                        <th>Действие</th>
                        <th>Роль</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                <tbody>
                @foreach(\App\AdminLogs::query()->orderBy('id', 'desc')->limit(500)->get(['user_id', 'action', 'role', 'created_at']) as $log)
                    <?php
                        $user = \App\User::query()->find($log->user_id);
                    ?>
                    <tr>
                        <td>{{ $user->username }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->role }}</td>
                        <td>{{ $log->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!--end: Datatable -->
        </div>
    </div>

</div>

@endsection