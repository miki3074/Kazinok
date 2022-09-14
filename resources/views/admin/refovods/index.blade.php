@extends('admin/layout')

@section('content')
<script src="/dash/js/dtables.js?v=39" type="text/javascript"></script>
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">Топ рефоводов</h3>
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
                    Топ рефоводов
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="refovod">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Пользователь</th>
                        <th>Сумма</th>
                        <th>Количество рефералов</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <!--end: Datatable -->
        </div>
    </div>

</div>

@endsection
