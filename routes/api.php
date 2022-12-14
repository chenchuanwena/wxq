<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//微信服务器接入
Route::group(['namespace' => 'Api', 'prefix' => 'mp'], function () {
  Route::any('/{api_token}', 'MpController@serveHandler')->name('api.mp.event.api');
  Route::get('/token/{api_token}', 'MpController@accessTokenGet')->name('api.mp.token');
});

//企业微信服务接入
Route::group(['namespace' => 'Api', 'prefix' => 'work'], function () {
  Route::any('/{api_token}', 'WorkController@serveHandler')->name('api.work.event.api');
  Route::get('/token/{api_token}', 'WorkController@accessTokenGet')->name('api.work.token');
});

//吉他网站接入
Route::group(['namespace' => 'Api', 'prefix' => 'guitar'], function () {
  Route::any('/{api_token}', 'GuitarController@getQrcode')->name('api.guitar.event.api');
  Route::get('/token/{api_token}', 'GuitarController@accessTokenGet')->name('api.guitar.token');
  //测试推送
  Route::any('/test/push', 'GuitarController@testPush')->name('api.guitar.event.api.testPush');
});
