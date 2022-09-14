@extends('admin/layout')

@section('content')

<script src="/dash/js/dtables.js" type="text/javascript"></script>
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
                    <i class="kt-font-brand flaticon2-menu-2"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Список слотов
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dtable4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Номер игры</th>
                        <th>Иконка</th>
                        <th>Название</th>
                        <th>В приоритете?</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Slots::query()->get() as $game)
                        <tr>
                            <td>{{ $game->id }}</td>
                            <td>{{ $game->game_id }}</td>
                            <td>{{ $game->title }}</td>
                            <td><img src="{{ $game->icon }}" style="width: 24px"></td>

                            <td><span class="kt-badge kt-badge--{{$game->priority == 1 ? 'success' : 'warning'}} kt-badge--inline kt-badge--pill">{{$game->priority == 0 ? 'Нет' : 'Да'}}</span></td>
                            <td>
                                <a href="{{ route('admin.slots.edit', ['id' => $game->id]) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Редактировать"><i class="la la-edit"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>
@endsection
