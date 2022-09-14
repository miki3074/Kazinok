@extends('admin/layout')

@section('content')
<script src="/dash/js/dtables.js?v=11" type="text/javascript"></script>

<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">Пользователи</h3>
    </div>
</div>

<div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="kt-portlet kt-portlet--tabs">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-toolbar">
                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#userSettings" role="tab" aria-selected="true">
                            Даннные пользователя {{ $user->username }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#userProfits" role="tab" aria-selected="false">
                            Статистика
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/users/promocodes/{{$user->id}}">
                            История промокодов
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/users/games/{{$user->id}}">
                            История игр
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/users/tables/{{$user->id}}">
                            Таблицы
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/users/tablesref/{{$user->id}}">
                            Таблица рефералов
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <form class="kt-form" method="post" action="/admin/users/edit/{{ $user->id }}">
            <div class="kt-portlet__body">
                <div class="tab-content">
                    <div class="tab-pane active" id="userSettings" role="tabpanel">
                        <div class="kt-section">
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Логин:</label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="{{ $user->username }}" name="username" minlength="6" required />
                                </div>
                                <div class="col-lg-4">
                                    <label>Пароль:</label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="{{ $user->password }}" name="password" />
                                </div>
                                <div class="col-lg-4">
                                    <label>Баланс (₽):</label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="{{ $user->balance }}" name="balance" required />
                                </div>
                            </div>
                            <div class="form-group row">
                                @if ($user->old_username != null)
                                <div class="col-lg-4">
                                    <label>Старый логин:</label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="{{ $user->old_username }}" name="old_username" minlength="6" required />
                                </div>
                                @endif
                                <div class="col-lg-4">
                                    <label>VK Логин:</label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="@if($user->vk_username != NULL) {{ $user->vk_username }} @else Не привязан @endif" disabled />
                                </div>
                                <div class="col-lg-4">
                                    <label>VK Профиль: <!-- <span style="cursor: pointer;{{ !$user->is_vk ? 'display: none' : '' }}" class="kt-font-primary" id="vkDate" onclick="getRegDate({{ $user->vk_id }})">Показать дату</span> --></label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="@if($user->vk_id > 0) https://vk.com/id{{ $user->vk_id }} @else Не привязан @endif" disabled />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>TG Логин:</label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="@if($user->tg_username != NULL) {{ $user->tg_username }} @else Не привязан @endif" disabled />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>IP: <!-- <span style="cursor: pointer;" class="kt-font-primary" onclick="getCountry('{{ $user->used_ip }}', this)">Узнать город</span> --></label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="{{ $user->used_ip }}" disabled />
                                </div>
                                <div class="col-lg-4">
                                    <label>IP при регистрации: <!-- <span style="cursor: pointer;" class="kt-font-primary" onclick="getCountry('{{ $user->created_ip }}', this)">Узнать город</span> --></label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="{{ $user->created_ip }}" disabled />
                                </div>
                                <div class="col-lg-4">
                                    <label>Ключ доступа:</label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="{{ $user->api_token }}" disabled />
                                </div>
                            </div>
                            <div class="form-group row">
                                @if ($user->ref_perc == 0)
                                <div class="col-lg-4">
                                    <label>Заработал на реф.системе:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ $refearn }}"
                                        disabled
                                    />
                                </div>
                                @else
                                <div class="col-lg-4">
                                    <label>Заработал на реф.системе:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ round(\App\ReferralPayment::query()->where('referral_id', $user->id)->sum('sum') * ($user->ref_perc / 100) + ($user->ref_bonus_cnt * 5) + ($user->ref_active_cnt * 15), 2) }}"
                                        disabled
                                    />
                                </div>
                                @endif
                                <div class="col-lg-4">
                                    <label>Пополнил:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ $payments }} р."
                                        disabled
                                    />
                                </div>
                                <div class="col-lg-4">
                                    <label>Вывел:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ $withdraws }} р."
                                        disabled
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                @if ($user->referral_use)
                                <?php
                                    $refovod = App\User::select(['id', 'username'])->find($user->referral_use);
                                ?>
                                @if($refovod)
                                <div class="col-lg-4">
                                    <label>Рефовод:</label>
                                    <br>
                                    <a target="_blank" href="/admin/users/edit/{{ $refovod->id }}">{{ $refovod->username }}</a>
                                </div>
                                @endif
                                @endif
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Депозиты рефералов:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ $refdep }} р."
                                        disabled
                                    />
                                </div>
                                <div class="col-lg-4">
                                    <label>Выводы рефералов:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ $refwdw }} р."
                                        disabled
                                    />
                                </div>
                                <div class="col-lg-4">
                                    <label>Заработок сайта:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ $zarab }} р."
                                        disabled
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Переходов по реф.ссылке:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ $user->link_trans }}"
                                        disabled
                                    />
                                </div>
                                <div class="col-lg-4">
                                    <label>Регистраций по реф.ссылке:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ $user->link_reg }}"
                                        disabled
                                    />
                                </div>
                                <!-- <div class="col-lg-4">
                                    <label>Фейк рефов:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ $user->ref_fake_cnt }}"
                                        disabled
                                    />
                                </div> -->
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Кошелек QIWI:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ $user->wallet_qiwi }}"
                                        name="wallet_qiwi"
                                    />
                                </div>
                                <div class="col-lg-4">
                                    <label>Номер карты:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ $user->wallet_card }}"
                                        name="wallet_card"
                                    />
                                </div>
                                <div class="col-lg-4">
                                    <label>Кошелек FKWallet:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ $user->wallet_fk }}"
                                        name="wallet_fk"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <!-- <div class="col-lg-4">
                                    <label>Кошелек ЮMoney:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ $user->wallet_yoomoney }}"
                                        name="wallet_yoomoney"
                                    />
                                </div> -->
                                <div class="col-lg-4">
                                    <label>Кошелек Piastrix:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ $user->wallet_piastrix }}"
                                        name="wallet_piastrix" 
                                    />
                                </div>
				                <div class="col-lg-4">
                                    <label>Комментарий пользователя:</label>
                                    <input
                                        type="text"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ $user->comment }}"
                                        name="comment" 
                                    />
                                </div> 
                            </div>  
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Бонус N за каждого рефа включён?</label>
                                    <select class="form-control" name="is_ref_bonus">
                                        <option value="1" @if($user->is_ref_bonus == 1) selected @endif>Да</option>
                                        <option value="0" @if($user->is_ref_bonus == 0) selected @endif>Нет</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Заблокирован?</label>
                                    <select class="form-control" name="ban">
                                        <option value="1" @if($user->ban == 1) selected @endif>Да</option>
                                        <option value="0" @if($user->ban == 0) selected @endif>Нет</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label>Причина блокировки:</label>
                                    <input
                                        type="text"
                                        name="ban_reason"
                                        autocomplete="off"
                                        class="form-control"
                                        placeholder=""
                                        value="{{ $user->ban_reason }}"
                                    />
                                </div>
                            </div>
                            @if($u->is_admin)
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Администратор? <span class="kt-badge kt-badge--unified-danger kt-badge--inline kt-badge--pill">!</span></label>
                                    <select class="form-control" name="is_admin">
                                        <option value="1" @if($user->is_admin == 1) selected @endif>Да</option>
                                        <option value="0" @if($user->is_admin == 0) selected @endif>Нет</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label>Модератор? <span class="kt-badge kt-badge--unified-danger kt-badge--inline kt-badge--pill">!</span></label>
                                    <select class="form-control" name="is_moder">
                                        <option value="1" @if($user->is_moder == 1) selected @endif>Да</option>
                                        <option value="0" @if($user->is_moder == 0) selected @endif>Нет</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label>Стример? <span class="kt-badge kt-badge--unified-danger kt-badge--inline kt-badge--pill">!</span></label>
                                    <select class="form-control" name="is_youtuber">
                                        <option value="1" @if($user->is_youtuber == 1) selected @endif>Да</option>
                                        <option value="0" @if($user->is_youtuber == 0) selected @endif>Нет</option>
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Промокодер? <span class="kt-badge kt-badge--unified-danger kt-badge--inline kt-badge--pill">!</span></label>
                                    <select class="form-control" name="is_promocoder">
                                        <option value="1" @if($user->is_promocoder == 1) selected @endif>Да</option>
                                        <option value="0" @if($user->is_promocoder == 0) selected @endif>Нет</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label>Индивидуальный % реф.системы: <span class="kt-badge kt-badge--unified-danger kt-badge--inline kt-badge--pill">!</span></label>
                                    <input type="text" autocomplete="off" class="form-control" placeholder="" value="{{ $user->ref_perc }}" name="ref_perc" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="userProfits" role="tabpanel">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Профит Dice:</label>
                                <input
                                    type="text"
                                    autocomplete="off"
                                    class="form-control"
                                    placeholder=""
                                    value="{{ round($user->stat_dice, 2) }}"
                                    disabled
                                />
                            </div>
                            <div class="col-lg-4">
                                <label>Профит Mines:</label>
                                <input
                                    type="text"
                                    autocomplete="off"
                                    class="form-control"
                                    placeholder=""
                                    value="{{ round($user->mines, 2) }}"
                                    disabled
                                />
                            </div>
                            <div class="col-lg-4">
                                <label>Профит Coinflip:</label>
                                <input
                                    type="text"
                                    autocomplete="off"
                                    class="form-control"
                                    placeholder=""
                                    value="{{ round($user->coinflip, 2) }}"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Профит Wheel:</label>
                                <input
                                    type="text"
                                    autocomplete="off"
                                    class="form-control"
                                    placeholder=""
                                    value="{{ round($user->wheel, 2) }}"
                                    disabled
                                />
                            </div>
                            <div class="col-lg-4">
                                <label>Профит Stairs:</label>
                                <input
                                    type="text"
                                    autocomplete="off"
                                    class="form-control"
                                    placeholder=""
                                    value="{{ round($user->stairs, 2) }}"
                                    disabled
                                />
                            </div>
                            <div class="col-lg-4">
                                <label>Профит Slots:</label>
                                <input
                                    type="text"
                                    autocomplete="off"
                                    class="form-control"
                                    placeholder=""
                                    value="{{ round($user->slots, 2) }}"
                                    disabled
                                />
                            </div>
                            <div class="col-lg-4">
                                <label>Профит промо на деп:</label>
                                <input
                                    type="text"
                                    autocomplete="off"
                                    class="form-control"
                                    placeholder=""
                                    value="{{ round($user->promo_dep_sum, 2) }}"
                                    disabled
                                />
                            </div>
                            <div class="col-lg-4">
                                <label>Профит промо на баланс:</label>
                                <input
                                    type="text"
                                    autocomplete="off"
                                    class="form-control"
                                    placeholder=""
                                    value="{{ round($user->promo_bal_sum, 2) }}"
                                    disabled
                                />
                            </div>
                            <div class="col-lg-4">
                                <label>Профит еж. бонус:</label>
                                <input
                                    type="text"
                                    autocomplete="off"
                                    class="form-control"
                                    placeholder=""
                                    value="{{ round($user->daily_bonus, 2) }}"
                                    disabled
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot" style="min-width: 100%">
                <div class="kt-form__actions">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button type="button" class="btn btn-danger" onclick="deleteUserMult()">Удалить мульт. акки</button>
                    <button type="button" class="btn btn-secondary" onclick="deleteUser()">Удалить пользователя</button>
                    <div class="sm-btn-control d-flex flex-row">
                        <button type="button" class="btn btn-danger ml-5 fake" onclick="location.href = '{{ route('admin.users.createFake', ['type' => 'Pay', 'id' => $user->id]) }}'">Добавить пополнение</button>
                        <button type="button" class="btn btn-danger fake" onclick="location.href = '{{ route('admin.users.createFake', ['type' => 'Payout', 'id' => $user->id]) }}'">Добавить выплату</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <style>
        @media(max-width: 570px) {
            .ml-5.fake {
                margin-left: 0!important;
            }
            .fake {
                margin: 30px 0 0 5px;
                min-width: 49.5%;
            }
        }
        @media(min-width: 570px) {
            .sm-btn-control {
                float: right;
                display: inline-block!important;
            }
        }
    </style>
    <script>
        function deleteUser() {
            let permission = confirm('Вы уверены, что хотите удалить аккаунт пользователя {{ $user->username }}?');
            if(permission) {
                window.location.href = '{{ route('admin.users.delete', ['id' => $user->id]) }}'
            }
        }

        function deleteUserMult() {
            let permission = confirm('Вы уверены, что хотите удалить мульти аккаунты пользователя {{ $user->username }}?');
            if(permission) {
                window.location.href = '{{ route('admin.users.clearmult', ['id' => $user->id]) }}'
            }
        }

        function getRegDate(id) {
            $('#vkDate').html('...');
            $.post('/admin/getVKinfo', {
                vk_id: id
            })
            .then(res => {
                $('#vkDate').html(res).css('pointerEvents', 'none');
            })
        }

        function getCountry(ip, elem) {
            $(elem).html('...');
            $.post('/admin/getCountry', {
                user_ip: ip
            })
            .then(res => {
                $(elem).html(res).css('pointerEvents', 'none');
            })
        }
    </script>
    
    
    
</div>
<style>
    @media (max-width:1100px) {
        .col-lg-4 {
            margin-top: 20px;
        }
    }
</style>
@endsection
