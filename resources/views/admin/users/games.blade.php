@extends('admin/layout')

@section('content')
<script src="/dash/js/dtables.js?v=2" type="text/javascript"></script>

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
                    История игр в Dice {{ $user->username }}
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="diceTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ставка</th>
                        <th>Шанс</th>
                        <th>Результат</th>
                        <th>Дата</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="kt-portlet__foot" style="min-width: 100%">
            <div class="kt-form__actions">
                <button type="button" class="btn btn-secondary" onclick="location.href='{{ route('admin.users.edit', ['id' => $user->id]) }}'">Назад</button>
            </div>
        </div>
    </div>

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-users"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    История игр в Mines
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="minesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ставка</th>
                        <th>Результат</th>
                        <th>Дата</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-users"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    История игр в Coinflip
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="coinTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ставка</th>
                        <th>Серия</th>
                        <th>Коэффицент</th>
                        <th>Результат</th>
                        <th>Дата</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-users"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    История игр в X50
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="x50Table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Game ID</th>
                        <th>Ставка</th>
                        <th>Цвет</th>
                        <th>Результат</th>
                        <th>Дата</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-users"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    История игр в Stairs
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="stairsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ставка</th>
                        <th>Результат</th>
                        <th>Дата</th>
                    </tr>
                </thead>
            </table>
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
