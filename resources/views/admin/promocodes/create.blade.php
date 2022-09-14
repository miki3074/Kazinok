@extends('admin/layout')

@section('content')
    <main class='main-content bgc-grey-100'>
        <div id='mainContent'>
            <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-menu-2"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Список промокодов
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dtablee">
                <thead>
                    <tr>
                        <th>Название</th>
                        <th>Сумма</th>
                        <td>Активаций</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($promocodes as $promocode)
                        <tr>
                            <td>{{ $promocode['name'] }}</td>
                            <td>{{ $promocode['sum'] }}</td>
                            <td>{{ $promocode['activation'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>
        </div>
    </main>
@endsection
