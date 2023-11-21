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
// Route::redirect('hihi', 'any', 301); 9代這寫法不作用啊

// 基礎篇
Route::get('/', function () {
    return view('welcome');
});

Route::get('hihi', function () {
    return 'hihiGet';
});
Route::get('param/{numId}', function ($numId) {
    return 'hihiParam'.$numId;
})->where('numId', '.*');
Route::post('hihi', function () { // 避免419 前端要做CSRF令牌防護，除非後端關掉
    return 'hihiPost';
});
Route::any('any', function () {
    return 'hihiAny';
});
Route::match(['get', 'post'],'match', function () {
    return 'hihiMatch';
});

// 正則篇
Route::get('testctrl/{id}', [TestController::class, 'read'])->where('id', '[0-9]+'); // 得use
// Route::get('testctrl/{id}', [TestController::class, 'read'])->where(['id'=>'[0-9]+']); // 數組

// Route::get('testctrl２/{id}', 'App\Http\Controllers\TestController@read'); // 免use
// 一個方法只能註冊一個路由？！

// 命名篇
Route::get('testctrl/{n}', [TestController::class, 'ro'])->name('nt');
// 重定向先略

// 分組篇
Route::group(['prefix'=>'inner'], function () {
  Route::get('member', function () {
     return 'hihiMember';
  });
});

// 等同
// Route::prefix('inner')->group(function () {
//   Route::get('member', function () {
//      return 'hihiMember';
//   });
// });

// 中間件
// Route::middleware(['web.filter'])->group(function () {
//   Route::get('member', function () {
//      return 'hihiMember';
//   });
// });

// 等同
// Route::group(['middleware'=>'web.filter'], function () {
//   Route::get('member', function () {
//      return 'hihiMember';
//   });
// });

// 還有子域名domain 命名空間等
// as？

// 回退

Route::get('autoj', [TestController::class, 'autoJson']);

// 資源控制器略
// Route::resource('resource', 'App\Http\Controllers\ResourceController');