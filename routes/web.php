<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('hihi', function () {
    return 'hihiGet';
});
Route::get('param/{name}', function ($name) {
    return 'hihiParam'.$name;
});
Route::post('hihi', function () {
    return 'hihiPost';
});
Route::any('any', function () {
    return 'hihiAny';
});
Route::match(['get', 'post'],'match', function () {
    return 'hihiMatch';
});

Route::get('testctrl/{id}', [TestController::class, 'read']); // 得use

// Route::get('testctrl２/{id}', 'App\Http\Controllers\TestController@read'); // 免use
// 一個方法只能註冊一個路由？！