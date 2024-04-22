<?php

use Illuminate\Routing\Router;
use App\Admin\Controllers\MyController;
// https://stackoverflow.com/questions/67721522/laravel-8-admin-controller-dont-work-in-what-problem

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    // 加路由的方法 https://laravel-admin.org/docs/zh/1.x/quick-start
    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('test', MyController::class);

});
