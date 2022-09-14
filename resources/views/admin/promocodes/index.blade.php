@extends('admin/layout')

@section('content')
<script src="/dash/js/dtables.js?1" type="text/javascript"></script>
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">Промокоды</h3>
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
                    Список промокодов
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
            <table class="table table-striped- table-bordered table-hover table-checkable" id="promo">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Сумма</th>
                        <td>Активаций</td>
                        <td>Активаций осталось</td>
                        <td>Вагер</td>
                        <td>Тип</td>
                        <td>Комментарий</td>
                        <th>Действия</th>
                    </tr>
                </thead>
            </table>

            <table class="table table-striped- table-bordered table-hover table-checkable" id="dtables" style="display: block; overflow: auto; margin-top: 30px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <td>Комментарий</td>
                        <th>Название</th>
                        <th>Сумма</th>
                        <td>Активаций</td>
                        <td>Активаций осталось</td>
                        <td>Вагер</td>
                        <td>Тип</td>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $test = \App\Promocode::query()->groupBy('comment')->get();
                    //dd($test);
                    //$promos = \App\Promocode::query()->limit(250)->orderBy('id', 'desc')->get();
                    //$coll = collect($promos->toArray());
                    //$tyt = $coll->groupBy('comment');
                    //$tyt = \App\Promocode::query()->get()->groupBy('comment');
                    //dd($tyt->toArray());
                     ?>
                    @foreach($test->toArray() as $comment)
                        <tr>
                            <td></td>
                            <td>{{ $comment['comment'] }} @if($comment['comment']) <span style="color: red">[П]</span> @else Остальные @endif</td>
                            <td></td>
                            <td>{{ $comment['sum'] }}</td>
                            <td>{{ $comment['activation'] }}</td>
                            <td></td>
                            <td>{{ $comment['wager'] }}</td>
                            <td>{{ $comment['type'] == 'balance' ? 'Баланс' : 'Депозит' }}</td>
                            <td>
                                <p onclick="$('.'+{{$comment['id']}}).toggle()">Показать</p>
                            </td>
                        </tr>
                        @foreach(\App\Promocode::query()->where('comment', $comment['comment'])->get()->chunk(15) as $promoss)
                            @foreach($promoss as $promo)
                            <tr class="{{$comment['id']}}" style="display: none;">
                                <td>{{ $promo['id'] }}</td>
                                <td></td>
                                <td>{{ $promo['name'] }}</td>
                                <td>{{ $promo['sum'] }}</td>
                                <td></td>
                                <td>{{ $promo['activation'] - \App\PromocodeActivation::query()->where('promo_id', $promo['id'])->count('id') }}</td>
                                <td></td>
                                <td></td>
                                <td>
                                    <a href="{{ route('admin.promocodes.delete', ['id' => $promo['id']]) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Удалить"><i class="la la-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
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
                <h5 class="modal-title" id="exampleModalLongTitle">Добавление промокода</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="kt-form-new" method="post" action="{{ route('admin.promocodes.create') }}" id="save">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Комментарий:</label>
                        <input type="text" class="form-control" placeholder="" name="comment">
                    </div>
                    <div class="form-group">
                        <label for="name">Название:</label>
                        <input type="text" class="form-control" placeholder="" name="name">
                    </div>
                    <div class="form-group">
                        <label for="name">Сумма (Процент):</label>
                        <input type="text" class="form-control" placeholder="" name="sum" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Активаций:</label>
                        <input type="text" class="form-control" placeholder="" name="activation" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Вагер:</label>
                        <input type="text" class="form-control" placeholder="" name="wager" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Сгенерировать промокодов:</label>
                        <input type="number" class="form-control" placeholder="" name="count" value="1" min="1" max="100" required>
                    </div>
                    <label for="name">Тип:</label>
					<select class="form-control" name="type">
						<option value="balance">Баланс</option>
						<option value="deposit">Депозит</option>
					</select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(comment){
        
    });
</script>
@endsection
