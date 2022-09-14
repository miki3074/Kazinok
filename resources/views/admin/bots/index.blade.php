@extends('admin/layout')

@section('content')

<script src="/dash/js/dtables.js" type="text/javascript"></script>
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">Боты</h3>
    </div>
</div>

<div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-user"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Список ботов
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a data-toggle="modal" href="#new" class="btn btn-success btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            Добавить
                        </a>
                    </div>  
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dtable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Логин</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\User::query()->where('is_bot', 1)->get() as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->username }}</td>
                            <td>
                                <a href="{{ route('admin.bots.edit', ['id' => $user->id]) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Редактировать"><i class="la la-edit"></i></a>
                                <a href="{{ route('admin.bots.delete', ['id' => $user->id]) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Удалить"><i class="la la-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="newLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Добавление бота</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="kt-form-new" method="post" action="{{ route('admin.bots.create') }}" id="save">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Логин бота:</label>
                        <input type="text" class="form-control" placeholder="" name="username" minlength="6" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
