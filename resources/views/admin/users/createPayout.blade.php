@extends('admin/layout')

@section('content')
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">Пользователи</h3>
    </div>
</div>

<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--tabs">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-toolbar">
                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#userSettings" role="tab" aria-selected="true">
                            Добавление выплаты для {{ $user->username }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <form class="kt-form" method="post" action="/admin/users/create/Payout/{{ $user->id }}">
            <div class="kt-portlet__body">
                <div class="tab-content">
                    <div class="tab-pane active" id="userSettings" role="tabpanel">
                        <div class="kt-section">
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Платежная система:</label>
                                    <select class="form-control" name="system">
                                        <option selected disabled>Не выбрано</option>
                                        <option value="4">Qiwi</option>
                                        <option value="2">Payeer</option>
                                        <option value="12">FKWallet</option>
                                        <option value="1">ЮMoney</option>
                                        <option value="9">Visa</option>
                                        <option value="10">MasterCard</option>
                                        <option value="5">Beeline</option>
                                        <option value="6">Megafon</option>
                                        <option value="7">MTS</option>
                                        <option value="11">Tele2</option>
                                    </select>                                
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Кошелек:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        name="wallet"
                                        class="form-control"
                                        placeholder=""
                                        value=""
                                    />                            
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Сумма:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        name="amount"
                                        class="form-control"
                                        placeholder=""
                                        value=""
                                    />                            
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Статус:</label>
                                    <select class="form-control" name="status">
                                        <option selected disabled>Не выбрано</option>
                                        <option value="1">Выплачено</option>
                                        <option value="2">Отклонено</option>
                                    </select>                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href = '{{ route('admin.users.edit', ['id' => $user->id]) }}'">Назад</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
