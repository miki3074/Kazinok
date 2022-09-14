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
                    <i class="kt-font-brand flaticon-information"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Топ депозитов
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dtable6">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя пользователя</th>
                        <th>Общая сумма</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($top as $usr)
                    <tr>
                        <td>{{ $usr->user_id }}</td>
                        <td><a href="/admin/users/edit/{{ $usr->user_id }}">{{ $usr->username }}</a></td>
                        <td data-order="{{ $usr->sum }}">{{ $usr->sum }}</td>
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
