@extends('admin/layout')

@section('content')
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">Настройки</h3>
    </div>
</div>

<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--tabs">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-toolbar">
                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#slotsSettings" role="tab" aria-selected="true">
                            Настройки слота {{ $game->title }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <form class="kt-form" method="post" action="/admin/slots/edit/{{ $game->id }}">
            <div class="kt-portlet__body">
                <div class="tab-content">
                    <div class="tab-pane active" id="slotsSettings" role="tabpanel">
                        <div class="kt-section">
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Название:</label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="{{ $game->title }}" name="title" minlength="3" required>
                                </div>
                                <div class="col-lg-4">
                                    <label>В приоритете?</label>
                                    <select class="form-control" name="priority">
                                        <option value="1" @if($game->priority == 1) selected @endif>Да</option>
                                        <option value="0" @if($game->priority == 0) selected @endif>Нет</option>
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
                    <button type="button" class="btn btn-secondary" onclick="window.location.href = '{{ route('admin.slots') }}'">Назад</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
