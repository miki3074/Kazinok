@extends('admin/layout')

@section('content')
<style>
    .btn.admin-msg {
        background-color: rgb(255 193 7 / 30%);
        color: #000;
    }
    .btn.system-msg {
        color: #fd397a;
        background: rgba(253, 57, 122, 0.1);
        cursor: pointer;
    }
    .kt-widget3__username {
        cursor: pointer;
    }
    .kt-widget3 .kt-widget3__item:last-child {
        border-bottom: 0.07rem dashed #fff;
    }
</style>
<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">			
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Поддержка</h3>
        </div>
    </div>

    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">
            <div class="m-auto col-xl-6">

                <!--begin:: Widgets/Support Tickets -->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label" style="min-width: 100%;justify-content: space-between;">
                            <h3 class="kt-portlet__head-title">
                                Диалог с пользователем
                            </h3>
                            <button class="btn btn-sm btn-label-brand btn-bold system-msg" onclick="location.href = '{{ route('admin.support.delete', ['id' => $id]) }}'">Удалить</button>
                        </div>
                    </div>
                    <div class="kt-portlet__body" id="chat_app">
                      <div class="kt-widget3 chatScroll" data-scroll="true" data-height="375" style="height: 375px; overflow: hidden;">
                            @foreach($messages as $msg)
                                <div class="kt-widget3__item chat_mes" id="chatm_{{ $msg['id'] }}">
                                    <div class="kt-widget3__header">
                                        <div class="kt-widget3__user-img">
                                            <img class="kt-widget3__img" src="@if ($msg['is_admin']) /img/admin_avatar.png @else /img/user_avatar.png @endif" alt="">
                                        </div>
                                        <div class="kt-widget3__info">
                                            <span class="kt-widget3__username" onclick="location.href='/admin/users/edit/{{ $msg['user_id'] }}'"> {{ $msg['username'] }} </span>
                                                <br>
                                                <span class="kt-widget3__time"> {{ $msg['time'] }} </span>
                                                </div>
                                                <span class="kt-widget3__status">
                                                @if (!$msg['is_admin'])
                                                    <button class="btn btn-sm btn-label-brand btn-bold" style="pointer-events: none">Пользователь</button>
                                                @else
                                                    <button class="btn btn-sm btn-label-brand btn-bold admin-msg" style="pointer-events: none">Администратор</button>
                                                @endif
                                            </span>
                                    </div>
                                    <div class="kt-widget3__body">
                                        <p class="kt-widget3__text"> {{ $msg['message'] }} </p>
                                    </div>
                                </div>
                            @endforeach
                    </div>
                    <div class="kt-portlet__foot">
                        <form action="/admin/support/sendMessage/{{ $user->id }}" method="POST" style="align-items: center; display: flex;">
                            @csrf
                            <input type="text" class="form-control" placeholder="Введите текст" autocomplete="off" name="message">
                            <button type="submit" class="btn btn-primary" id="chatsend" style="margin-left: 2%;">Отправить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
<script>
    $('.chatScroll').scrollTop(9999999);
</script>
@endsection

