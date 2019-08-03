<?php

namespace Tungltdev\fakerUserSudoSu;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseServiceProvider;

class RouteServiceProvider extends BaseServiceProvider
{
    public function map()
    {
        Route::group([
            'prefix' => 'fakerUser',
            'namespace' => 'Tungltdev\fakerUserSudoSu\Controllers',
            'middleware' => [Config::get('faker_user.middleware')]
        ], function () {
            Route::post('fakerUser/login-as-user', 'FakerUserController@loginAsUser')
                ->name('fakerUser.login_as_user');

            Route::post('fakerUser/returnUser', 'FakerUserController@returnUser')
                ->name('fakerUser.return');
        });
    }
}
