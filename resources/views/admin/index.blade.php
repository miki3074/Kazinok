@extends('admin/layout')

@section('content')
<script type="text/javascript" src="/dash/js/chart.min.js"></script>
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">Статистика</h3>
    </div>
</div>

<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">

                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::Total Profit-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Пополнений на
                                </h4>
                                <span class="kt-widget24__desc">
                                    за сегодня
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-success">
                                {{ App\Payment::query()->where('fake', 0)->where([['created_at', '>=', \Carbon\Carbon::today()], ['status', 1]])->sum('sum')  }}<i class="la la-rub"></i>
                            </span>  
                        </div>                                
                    </div>
                    <!--end::Total Profit-->
                </div>

                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::New Feedbacks-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Пополнений на
                                </h4>
                                <span class="kt-widget24__desc">
                                    за 7 дней
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-success">
                               {{ App\Payment::query()->where('fake', 0)->where([['created_at', '>=', \Carbon\Carbon::today()->subDays(7)], ['status', 1]])->sum('sum')  }}<i class="la la-rub"></i>
                            </span>  
                        </div>                            
                    </div>              
                    <!--end::New Feedbacks--> 
                </div>

                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::New Orders-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Пополнений на
                                </h4>
                                <span class="kt-widget24__desc">
                                    за месяц
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-success">
                                {{ App\Payment::query()->where('fake', 0)->where([['created_at', '>=', \Carbon\Carbon::today()->subMonth()], ['status', 1]])->sum('sum')  }}<i class="la la-rub"></i>
                            </span>  
                        </div>                            
                    </div>              
                    <!--end::New Orders--> 
                </div>

                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::New Users-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Пополнений на
                                </h4>
                                <span class="kt-widget24__desc">
                                    за все время
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-success">
                                {{ App\Payment::query()->where('fake', 0)->where('status', 1)->sum('sum')  }}<i class="la la-rub"></i>
                            </span>  
                        </div>                        
                    </div>              
                    <!--end::New Users--> 
                </div>

            </div>
        </div>
    </div>
    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">

                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::Total Profit-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Выплат на
                                </h4>
                                <span class="kt-widget24__desc">
                                    за сегодня
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-success">
                                {{ App\Withdraw::query()->where([['fake', 0], ['created_at', '>=', \Carbon\Carbon::today()], ['status', 1]])->orWhere([['fake', 0], ['created_at', '>=', \Carbon\Carbon::today()], ['status', 3]])->sum('sum')  }}<i class="la la-rub"></i>
                            </span>  
                        </div>                                
                    </div>
                    <!--end::Total Profit-->
                </div>

                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::New Feedbacks-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Выплат на
                                </h4>
                                <span class="kt-widget24__desc">
                                    за 7 дней
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-success">
                               {{ App\Withdraw::query()->where('fake', 0)->where([['created_at', '>=', \Carbon\Carbon::today()->subDays(7)], ['status', 1]])->orWhere([['fake', 0], ['created_at', '>=', \Carbon\Carbon::today()->subDays(7)], ['status', 3]])->sum('sum')  }}<i class="la la-rub"></i>
                            </span>  
                        </div>                            
                    </div>              
                    <!--end::New Feedbacks--> 
                </div>

                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::New Orders-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Выплат на
                                </h4>
                                <span class="kt-widget24__desc">
                                    за месяц
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-success">
                                {{ App\Withdraw::query()->where('fake', 0)->where([['created_at', '>=', \Carbon\Carbon::today()->subMonth()], ['status', 1]])->orWhere([['fake', 0], ['created_at', '>=', \Carbon\Carbon::today()->subMonth()], ['status', 3]])->sum('sum')  }}<i class="la la-rub"></i>
                            </span>  
                        </div>                            
                    </div>              
                    <!--end::New Orders--> 
                </div>

                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::New Users-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Выплат на
                                </h4>
                                <span class="kt-widget24__desc">
                                    за все время
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-success">
                                {{ App\Withdraw::query()->where('fake', 0)->where('status', 1)->orWhere([['fake', 0], ['status', 3]])->sum('sum')  }}<i class="la la-rub"></i>
                            </span>  
                        </div>                        
                    </div>              
                    <!--end::New Users--> 
                </div>

            </div>
        </div>
    </div>

    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">

                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::Total Profit-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Пополнений на
                                </h4>
                                <span class="kt-widget24__desc">
                                    за вчера
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-success">
                                {{ App\Payment::query()->where('fake', 0)->where([['created_at', '>=', \Carbon\Carbon::yesterday()], ['created_at', '<', \Carbon\Carbon::today()], ['status', 1]])->sum('sum')  }}<i class="la la-rub"></i>
                            </span>  
                        </div>                                
                    </div>
                    <!--end::Total Profit-->
                </div>

                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::New Feedbacks-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Выплат на
                                </h4>
                                <span class="kt-widget24__desc">
                                    за вчера
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-success">
                               {{ App\Withdraw::query()->where('fake', 0)->where([['created_at', '>=', \Carbon\Carbon::yesterday()], ['created_at', '<', \Carbon\Carbon::today()], ['status', 1]])->orWhere([['fake', 0], ['created_at', '>=', \Carbon\Carbon::yesterday()], ['created_at', '<', \Carbon\Carbon::today()], ['status', 3]])->sum('sum')  }}<i class="la la-rub"></i>
                            </span>  
                        </div>                            
                    </div>              
                    <!--end::New Feedbacks--> 
                </div>
            </div>
        </div>
    </div>

    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">

                <div class="col-md-12 col-lg-6 col-xl-4">
                    <!--begin::Total Profit-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Пользователей
                                </h4>
                                <span class="kt-widget24__desc">
                                    всего
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-brand">
                                 {{ App\User::query()->count('id')  }}<i class="la la-user"></i>
                            </span>  
                        </div>                                
                    </div>
                    <!--end::Total Profit-->
                </div>

                <div class="col-md-12 col-lg-6 col-xl-4">
                    <!--begin::New Orders-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    На вывод
                                </h4>
                                <span class="kt-widget24__desc">
                                    общая сумма
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-danger">
                                {{ App\Withdraw::query()->where('status', 0)->sum('sum') }}<i class="la la-rub"></i>
                            </span>  
                        </div>                            
                    </div>              
                    <!--end::New Orders--> 
                </div>
                <div class="col-md-12 col-lg-6 col-xl-4">
                    <!--begin::New Users-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Баланс мерчанта
                                </h4>
                                <span class="kt-widget24__desc">
                                    XMPay RUB
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-warning">
                                <span id="xmpayBal"><img src="https://i1.wp.com/caringo.com/wp-content/themes/bootstrap/wwwroot/img/spinning-wheel-1.gif" height="26px"></span><i class="la la-rub"></i>
                            </span>  
                        </div>                        
                    </div>              
                    <!--end::New Users--> 
                </div>
                <div class="col-md-12 col-lg-6 col-xl-4">
                    <!--begin::New Users-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Баланс мерчанта
                                </h4>
                                <span class="kt-widget24__desc">
                                    FK Wallet
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-warning">
                                <span id="fkBal"><img src="https://i1.wp.com/caringo.com/wp-content/themes/bootstrap/wwwroot/img/spinning-wheel-1.gif" height="26px"></span><i class="la la-rub"></i>
                            </span>  
                        </div>                        
                    </div>              
                    <!--end::New Users--> 
                </div>
                <div class="col-md-12 col-lg-6 col-xl-4">
                    <!--begin::New Users-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Баланс мерчанта
                                </h4>
                                <span class="kt-widget24__desc">
                                    GetPay
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-warning">
                                <span id="getpayBal"><img src="https://i1.wp.com/caringo.com/wp-content/themes/bootstrap/wwwroot/img/spinning-wheel-1.gif" height="26px"></span><i class="la la-rub"></i>
                            </span>  
                        </div>                        
                    </div>              
                    <!--end::New Users--> 
                </div>
                <div class="col-md-12 col-lg-6 col-xl-4">
                    <!--begin::New Users-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Баланс мерчанта
                                </h4>
                                <span class="kt-widget24__desc">
                                    Piastrix
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-warning">
                                <span id="piasBal"><img src="https://i1.wp.com/caringo.com/wp-content/themes/bootstrap/wwwroot/img/spinning-wheel-1.gif" height="26px"></span><i class="la la-rub"></i>
                            </span>  
                        </div>                        
                    </div>              
                    <!--end::New Users--> 
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet">
        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит Dice</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($profitDice >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($profitDice, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит Mines</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($profitMines >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($profitMines, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит Coinflip</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($profitCoinflip >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($profitCoinflip, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит Roulette</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($profitWheel >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($profitWheel, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит Slots</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($profitSlots >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($profitSlots, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит Stairs</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($profitStairs >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($profitStairs, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Общий профит</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($profitDice + $profitMines + $profitWheel + $profitCoinflip + $profitSlots + $profitStairs >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($profitDice + $profitMines + $profitCoinflip + $profitWheel + $profitSlots + $profitStairs, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet">
        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит за вчера Dice</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($oldprofitDice >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($oldprofitDice, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит за вчера Mines</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($oldprofitMines >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($oldprofitMines, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит за вчера Coinflip</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($oldprofitCoinflip >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($oldprofitCoinflip, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит за вчера Roulette</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($oldprofitWheel >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($oldprofitWheel, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит за вчера Slots</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($oldprofitSlots >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($oldprofitSlots, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит за вчера Stairs</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($oldprofitStairs >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($oldprofitStairs, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Общий профит за вчера</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($oldprofitDice + $oldprofitMines + $oldprofitWheel + $oldprofitCoinflip + $oldprofitSlots + $oldprofitStairs >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($oldprofitDice + $oldprofitMines + $oldprofitCoinflip + $oldprofitWheel + $oldprofitSlots + $oldprofitStairs, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet">
        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит за все время Dice</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($allprofitDice >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($allprofitDice, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит за все время Mines</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($allprofitMines >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($allprofitMines, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит за все время Coinflip</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($allprofitCoinflip >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($allprofitCoinflip, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит за все время Roulette</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($allprofitWheel >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($allprofitWheel, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит за все время Slots</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($allprofitSlots >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($allprofitSlots, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Профит за все время Stairs</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($allprofitStairs >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($allprofitStairs, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-md-12 col-lg-12 col-xl-3">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="kt-widget1">
                        <div class="kt-widget1__item">
                            <div class="kt-widget1__info">
                                <h3 class="kt-widget1__title">Общий профит за все время</h3>
                            </div>
                            <span class="kt-widget1__number {{ ($allprofitDice + $allprofitMines + $allprofitWheel + $allprofitCoinflip + $allprofitSlots + $allprofitStairs >= 0) ? 'kt-font-success' : 'kt-font-danger' }}">{{ round($allprofitDice + $allprofitMines + $allprofitCoinflip + $allprofitWheel + $allprofitSlots + $allprofitStairs, 2) }}<i class="la la-rub"></i></span>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            График регистраций за текущий месяц
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body kt-portlet__body--fluid">
                    <div class="kt-widget12">
                        <div class="kt-widget12__chart" style="height:250px;">
                            <canvas id="authChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            График пополнений за текущий месяц
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body kt-portlet__body--fluid">
                    <div class="kt-widget12">
                        <div class="kt-widget12__chart" style="height:250px;">
                            <canvas id="depsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $.ajax({
        method: 'POST',
        url: '/admin/getUserByMonth',
        success: function (res) {
            var authChart = 'authChart';
            if ($('#'+authChart).length > 0) {
                var months = [];
                var users = [];

                $.each(res, function(index, data) {
                    months.push(data.date);
                    users.push(data.count);
                });

                var lineCh = document.getElementById(authChart).getContext("2d");

                var chart = new Chart(lineCh, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: "",
                            tension:0.4,
                            backgroundColor: 'transparent',
                            borderColor: '#2c80ff',
                            pointBorderColor: "#2c80ff",
                            pointBackgroundColor: "#fff",
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: "#2c80ff",
                            pointHoverBorderWidth: 2,
                            pointRadius: 6,
                            pointHitRadius: 6,
                            data: users,
                        }]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        maintainAspectRatio: false,
                        tooltips: {
                            callbacks: {
                                title: function(tooltipItem, data) {
                                    return 'Дата : ' + data['labels'][tooltipItem[0]['index']];
                                },
                                label: function(tooltipItem, data) {
                                    return data['datasets'][0]['data'][tooltipItem['index']] + ' чел.' ;
                                }
                            },
                            backgroundColor: '#eff6ff',
                            titleFontSize: 13,
                            titleFontColor: '#6783b8',
                            titleMarginBottom:10,
                            bodyFontColor: '#9eaecf',
                            bodyFontSize: 14,
                            bodySpacing:4,
                            yPadding: 15,
                            xPadding: 15,
                            footerMarginTop: 5,
                            displayColors: false
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontSize:12,
                                    fontColor:'#9eaecf',
                                    stepSize: Math.ceil(users/5)
                                },
                                gridLines: { 
                                    color: "#e5ecf8",
                                    tickMarkLength:0,
                                    zeroLineColor: '#e5ecf8'
                                },

                            }],
                            xAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontSize:12,
                                    fontColor:'#9eaecf',
                                    source: 'auto',
                                },
                                gridLines: {
                                    color: "transparent",
                                    tickMarkLength:20,
                                    zeroLineColor: '#e5ecf8',
                                },
                            }]
                        }
                    }
                });
            }
        }
    });
    $.ajax({
        method: 'POST',
        url: '/admin/getDepsByMonth',
        success: function (res) {
            var depsChart = 'depsChart';
            if ($('#'+depsChart).length > 0) {
                var months = [];
                var deps = [];

                $.each(res, function(index, data) {
                    months.push(data.date);
                    deps.push(data.sum);
                });

                var lineCh = document.getElementById(depsChart).getContext("2d");

                var chart = new Chart(lineCh, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: "",
                            tension:0.4,
                            backgroundColor: 'transparent',
                            borderColor: '#2c80ff',
                            pointBorderColor: "#2c80ff",
                            pointBackgroundColor: "#fff",
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: "#2c80ff",
                            pointHoverBorderWidth: 2,
                            pointRadius: 6,
                            pointHitRadius: 6,
                            data: deps,
                        }]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        maintainAspectRatio: false,
                        tooltips: {
                            callbacks: {
                                title: function(tooltipItem, data) {
                                    return 'Дата : ' + data['labels'][tooltipItem[0]['index']];
                                },
                                label: function(tooltipItem, data) {
                                    return data['datasets'][0]['data'][tooltipItem['index']] + ' руб.' ;
                                }
                            },
                            backgroundColor: '#eff6ff',
                            titleFontSize: 13,
                            titleFontColor: '#6783b8',
                            titleMarginBottom:10,
                            bodyFontColor: '#9eaecf',
                            bodyFontSize: 14,
                            bodySpacing:4,
                            yPadding: 15,
                            xPadding: 15,
                            footerMarginTop: 5,
                            displayColors: false
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true,
                                    fontSize:12,
                                    fontColor:'#9eaecf',
                                    stepSize: Math.ceil(deps/5)
                                },
                                gridLines: { 
                                    color: "#e5ecf8",
                                    tickMarkLength:0,
                                    zeroLineColor: '#e5ecf8'
                                },

                            }],
                            xAxes: [{
                                ticks: {
                                    fontSize:12,
                                    fontColor:'#9eaecf',
                                    source: 'auto',
                                },
                                gridLines: {
                                    color: "transparent",
                                    tickMarkLength:20,
                                    zeroLineColor: '#e5ecf8',
                                },
                            }]
                        }
                    }
                });
            }
        }
    });
});
</script>
@endsection
