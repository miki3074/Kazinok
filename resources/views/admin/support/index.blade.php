@extends('admin/layout')

@section('content')
<script src="/dash/js/dtables.js?v=1" type="text/javascript"></script>
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">Поддержка</h3>
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
                    Список обращений
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dtable">
                <thead>
                    <tr>
                        <th>Пользователь</th>
                        <th>Последнее сообщение</th>
                        <th>Последнее сообщение от админа (да/нет)</th>
                        <th>Дата последнего сообщения</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($supports as $support)
                    @if (\App\User::query()->find($support->user_id))
                        <?php
                            $lastMessage = \App\Support::query()->where('user_id', $support->user_id)->orderBy('created_at', 'desc')->first();
                        ?>
                        <tr>
                            <td><a href="/admin/users/edit/{{ \App\User::query()->find($support->user_id)->id }}">{{ \App\User::query()->find($support->user_id)->username }}</a></td>
                            <td>{{ $lastMessage->message }}</td>
                            @if ($lastMessage->is_admin)
                                <td style="color: #90EE90">Да</td>
                            @else
                                <td style="color: #FF0000">Нет</td>
                            @endif
                            <td>{{ $lastMessage->created_at->format('d-m-Y h:i:s') }}</td>
                            <td>
                                <a href="{{ route('admin.support.chat', ['id' => $support->id]) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Открыть чат"><i class="la la-external-link"></i></a>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>

            <!--end: Datatable -->
        </div>
    </div>
</div>

@endsection
