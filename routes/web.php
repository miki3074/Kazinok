<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/r/{id}', 'IndexController@setRef');

Route::any('/payment/handle', 'PaymentController@handle');
Route::any('/payment/xmpay', 'PaymentController@xmHandle');
Route::any('/payment/swift', 'PaymentController@swiftHandle');
Route::any('/payment/piastrix', 'PaymentController@piastrixHandle');
Route::any('/payment/getpay', 'PaymentController@getpayHandle');
Route::get('/lastw', 'IndexController@getLast');
//Route::get('/updatepay', 'IndexController@updatePay');
Route::get('/updaterefer', 'IndexController@topRef');
Route::post('/wallets/save', 'IndexController@saveWallets');
//Route::get('/updateref', 'IndexController@updateRef');
//Route::get('/updateusr', 'IndexController@updateUser');
//Route::get('/deletepromo', 'IndexController@deletePromo');

Route::get('/stats/get', 'IndexController@getStats');
//Route::get('/getRefList/{id}', 'IndexController@getRefList');
Route::get('/stats/make/ef5we4f89we4w88f', 'IndexController@makeStats');
Route::get('/stats/clear/ef5we4f89we4w88f', 'IndexController@clearStats');
//Route::any('/create/ef5we4f89we4w88f', 'SocketWithdrawController@create');

Route::any('/payment/rub', 'PaymentController@RubPayHandle');
Route::get('/payment/get/{id}', 'PaymentController@card')->name('card'); //
Route::get('/payment/check/{id}', 'PaymentController@checkCard')->name('checkCard'); //
Route::get('/payment/cancel/{id}', 'PaymentController@cancelCard')->name('cancelCard'); //
Route::get('/payment', 'IndexController@payment')->name('pay'); //

Route::get('rnd', 'Auth\TelegramController@rnd');
Route::post('session', 'Auth\TelegramController@session');
Route::post(env('TELEGRAM_BOT_TOKEN') . '/webhook', 'Auth\TelegramController@handle');

Route::get('get-me', 'TelegramController@getMe');
Route::get('set-hook', 'TelegramController@setWebHook');

Route::any('/test', 'TelegramController@showMenu')->name('payment.test');
Route::any('/test1', 'PaymentController@handlePayment')->name('payment.testhandle');
Route::get('/save/{id}/{status}', 'PaymentController@setStatusTest')->name('admin.withdraws.test');


Route::any('/withdraw/result', 'WithdrawController@result');

Route::post('/getGroupVK', 'IndexController@getGroupVK');
Route::post('/getInfo', 'IndexController@getInfo');
Route::post('/getBalance', 'IndexController@getBalance');

Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function () {
    Route::post('/editUser', 'IndexController@editUser');
    Route::post('/setPass', 'IndexController@setPass');
});

Route::group(['prefix' => 'api', 'middleware' => 'secretKey'], function () {
    Route::post('/userSession', 'UserSessionsController@create');

    Route::post('/getTimer', function() {
        return \App\Setting::query()->find(1)->bot_timer;
    });
    Route::post('/fake', 'FakeController@fake');
    Route::get('/stats/make', 'IndexController@makeStats');

    Route::group(['prefix' => 'wheel'], function () {
        Route::post('/close', 'WheelController@close');
        Route::post('/end', 'WheelController@end');
        Route::post('/updateStatus', 'WheelController@updateStatus');
    });

    Route::group(['prefix' => 'dice'], function () {
        Route::post('/bet', 'DiceController@resolve');
    });

    Route::group(['prefix' => 'coin'], function () {
        Route::post('/create', 'CoinflipController@create');
        Route::post('/take', 'CoinflipController@take');
        Route::post('/bet', 'CoinflipController@bet');
    });

    Route::group(['prefix' => 'stairs'], function () {
        Route::post('/get', 'StairsController@resolveGet');
        Route::post('/create', 'StairsController@resolve');
        Route::post('/take', 'StairsController@resolveTake');
        Route::post('/bet', 'StairsController@resolvePath');
    });

    Route::group(['prefix' => 'mines'], function () {
        Route::post('/get', 'MinesController@resolveGet');
        Route::post('/create', 'MinesController@resolve');
        Route::post('/take', 'MinesController@resolveTake');
        Route::post('/bet', 'MinesController@resolvePath');
    });

    Route::group(['prefix' => 'promo'], function () {
        Route::post('/getPromo', 'PromoController@getPromo');
        Route::post('/setPromo', 'PromoController@setPromo');
    });

    Route::group(['prefix' => 'withdraw'], function () {
        Route::post('/create', 'SocketWithdrawController@create');
        Route::post('/decline', 'SocketWithdrawController@decline');
    });

    Route::group(['prefix' => 'jackpot'], function () {
        Route::post('/slider', 'JackpotController@getSlider');
        Route::post('/newGame', 'JackpotController@newGame');
        Route::post('/getGame', 'JackpotController@getGame');
        Route::post('/getStatus', 'JackpotController@getStatus');
    });
});

Route::group(['prefix' => 'bot'], function () {
    Route::group(['prefix' => 'vk'], function () {
        Route::any('/handle', 'VkController@handle');
    });
});

Route::group(['prefix' => 'auth'], function () {
    Route::group(['prefix' => 'vk'], function () {
        Route::get('/handle', 'Auth\VkController@handle');
    });
    Route::group(['prefix' => 'tg'], function () {
        Route::post('/handle', 'Auth\TelegramController@handle');
    });
});

Route::group(['prefix' => 'coin'], function () {
    Route::post('/get', 'CoinflipController@get');
    Route::post('/create', 'CoinflipController@create');
    Route::post('/take', 'CoinflipController@take');
    Route::post('/bet', 'CoinflipController@bet');
});

Route::group(['prefix' => 'wheel'], function () {
    Route::post('/get', 'WheelController@get');
    Route::post('/bet/{color}', 'WheelController@betWheel');
});

Route::group(['prefix' => 'jackpot'], function () {
    Route::post('/init', 'JackpotController@initRoom');
    Route::post('/bet', 'JackpotController@newBet');
});

Route::group(['prefix' => 'slots'], function () {
    Route::any('/getGamesApi', 'SlotsController@getGamesApi');
    Route::any('/getGames', 'SlotsController@getGames');
    Route::any('/getUrl', 'SlotsController@getGameURI');
    Route::any('/callback', 'SlotsController@callback');
});

Route::group(['prefix' => 'referrals', 'middleware' => 'auth:api'], function () {
    Route::post('/get', 'ReferralController@getReferrals');
    Route::post('/getGraph', 'ReferralController@getGraph');
    Route::post('/getInfo', 'ReferralController@getInfo');
    Route::post('/take', 'ReferralController@take');
});

Route::group(['prefix' => 'promo', 'middleware' => 'auth:api'], function () {
    Route::post('/vk', 'PromoController@vk_bonus');
    //Route::post('/getPromo', 'PromoController@getPromo');
    //Route::post('/setPromo', 'PromoController@setPromo');
    Route::post('/get', 'PromoController@get');
});

Route::group(['prefix' => 'support', 'middleware' => 'auth:api'], function () {
    Route::post('/sendMessage', 'SupportController@sendMessage');
    Route::post('/getMessages', 'SupportController@getMessages');
});

Route::group(['prefix' => 'withdraw', 'middleware' => 'auth:api'], function () {
    //Route::post('/create', 'WithdrawController@create');
    Route::post('/get', 'SocketWithdrawController@getWithdraws');
    //Route::post('/decline', 'WithdrawController@decline');
});

Route::group(['prefix' => 'payment', 'middleware' => 'auth:api'], function () {
    Route::post('/create', 'PaymentController@create');
    Route::post('/get', 'PaymentController@get');
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {

    //Route::get('/balanceda', 'IndexController@getBalance');
    Route::post('/getfromdate', 'IndexController@getFromDate');

    Route::group(['middleware' => 'auth', 'middleware' => 'access:promocoder'], function () {
        Route::group(['prefix' => 'promocodes'], function () {
            Route::get('/', 'PromocodeController@index')->name('admin.promocodes');
            Route::get('/created', 'PromocodeController@create')->name('admin.promocodes.created');
            Route::post('/create', 'PromocodeController@createPost')->name('admin.promocodes.create');
            Route::get('/delete/{id}', 'PromocodeController@delete')->name('admin.promocodes.delete');
        });
    });

    Route::group(['middleware' => 'auth', 'middleware' => 'access:moder'], function () {
        Route::post('/load', 'IndexController@load');

        Route::group(['prefix' => 'users'], function () {
            Route::get('/', 'UsersController@index')->name('admin.users');
            Route::get('/promocodes/{id}', 'UsersController@promocodes')->name('admin.users.promocodes');
            Route::get('/games/{id}', 'UsersController@games')->name('admin.users.games');
            Route::get('/tables/{id}', 'UsersController@tables')->name('admin.users.tables');
            Route::get('/tablesref/{id}', 'UsersController@tablesRef')->name('admin.users.tablesref');
            Route::get('/mtop', 'UsersController@makeTop')->name('admin.users.top');
            Route::get('/top', 'UsersController@indexTop')->name('admin.users.tops');
            Route::get('/edit/{id}', 'UsersController@edit')->name('admin.users.edit');
            Route::get('/edit/{id}/clearmult', 'UsersController@clearmult')->name('admin.users.clearmult');
            Route::get('/delete/{id}', 'UsersController@delete')->name('admin.users.delete');
            Route::get('/create/{type}/{id}', 'UsersController@createFake')->name('admin.users.createFake');
            Route::post('/create/{type}/{id}', 'UsersController@addFake');
            Route::post('/edit/{id}', 'UsersController@editPost');
            Route::get('/wallet/save/{id}', 'UsersController@editWallet')->name('admin.userswallet.save');
        });
    });

    Route::group(['middleware' => 'auth', 'middleware' => 'access:admin'], function () {
        Route::get('/', 'IndexController@index')->name('admin.index');
        Route::get('/bots', 'BotsController@index')->name('admin.bots');
        Route::get('/support', 'SupportController@index')->name('admin.support');
        Route::get('/admins', 'AdminController@index')->name('admin.admins');
        Route::get('/logs', 'IndexController@indexAdminLogs')->name('admin.adminlogs');
        Route::post('/versionUpdate', 'AdminController@versionUpdate');
        Route::post('/getUserByMonth', 'IndexController@getUserByMonth');
        Route::post('/getDepsByMonth', 'IndexController@getDepsByMonth');
        Route::post('/getVKinfo', 'IndexController@getVK');
        Route::post('/getCountry', 'IndexController@getCountry');

        Route::group(['prefix' => 'slots'], function () {
            Route::get('/', 'SlotsController@index')->name('admin.slots');
            Route::get('/update', 'SlotsController@update');
            Route::get('/edit/{id}', 'SlotsController@edit')->name('admin.slots.edit');
            Route::post('/edit/{id}', 'SlotsController@save');
        });


        Route::group(['prefix' => 'bots'], function () {
            Route::get('/create', 'BotsController@create')->name('admin.bots.create');
            Route::post('/create', 'BotsController@createPost');
            Route::get('/edit/{id}', 'BotsController@edit')->name('admin.bots.edit');
            Route::post('/edit/{id}', 'BotsController@editPost');
            Route::get('/delete/{id}', 'BotsController@delete')->name('admin.bots.delete');
        });

        Route::group(['prefix' => 'settings'], function () {
            Route::get('/', 'SettingsController@index')->name('admin.settings');
            Route::post('/', 'SettingsController@save');
        });

        Route::group(['prefix' => 'allwithdraws'], function () {
            Route::get('/', 'WithdrawsController@allIndex')->name('admin.fullwithdraws');
        });

        Route::group(['prefix' => 'refovods'], function () {
            Route::get('/', 'IndexController@refovods')->name('admin.refovods');
        });

        Route::group(['prefix' => 'withdraws'], function () {
            Route::get('/', 'WithdrawsController@index')->name('admin.withdraws');
            Route::get('/save/{id}/{status}', 'WithdrawsController@setStatus')->name('admin.withdraws.save');
        });
        Route::group(['prefix' => 'deposits'], function () {
            Route::get('/', 'DepositsController@index')->name('admin.deposits');
        });

        Route::group(['prefix' => 'support'], function () {
            Route::get('/chat/{id}', 'SupportController@chat')->name('admin.support.chat');
            Route::post('/sendMessage/{id}', 'SupportController@sendMessage');
            Route::get('/delete/{id}', 'SupportController@delete')->name('admin.support.delete');
        });
        Route::post('/getMerchant', 'IndexController@getMerchant');
        Route::post('/getMerchantFK', 'IndexController@getMerchantFK');
        Route::post('/getMerchantGet', 'IndexController@getMerchantGet');
        Route::post('/getMerchantPias', 'IndexController@getMerchantPias');
    });
});

Route::get('/{any}', 'IndexController@index')->where('any', '.*')->name('index');
