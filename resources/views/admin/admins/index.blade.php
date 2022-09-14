@extends('admin/layout')

@section('content')
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"  rel="stylesheet">
    <main class='main-content bgc-grey-100'>
        <div id='mainContent'>
            <div class="container-fluid">
                <h4 class="c-grey-900 mT-10 mB-30">Админ пользователи</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="bgc-white bd bdrs-3 p-20 mB-20">
                            <h4 class="c-grey-900 mB-20">Все пользователи</h4>
                            <button type="button" class="btn cur-p btn-primary" onclick="window.location.href = '{{ route('admin.admins.create') }}'">Добавить админа</button>
                            <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Имя</th>
                                    <th>Последняя авторизация</th>
                                    <th>Действие</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Имя</th>
                                    <th>Последняя авторизация</th>
                                    <th>Действие</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach(\App\Admin::query()->get() as $user)
                                    <tr>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->updated_at }}</td>
                                        <td>
                                            <a href="{{ route('admin.admins.edit', ['id' => $user->id]) }}">Редактировать</a>
                                            /
                                            <a href="{{ route('admin.admins.delete', ['id' => $user->id]) }}">Удалить</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
