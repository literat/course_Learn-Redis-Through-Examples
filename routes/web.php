<?php

use Illuminate\Support\Facades\Redis;

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
    $visits = Redis::incr('visits');

    return view('welcome')->withVisits($visits);
});

Route::get('videos/{id}', function ($id) {
    $downloads = Redis::get("videos.{$id}.downloads");

    return view('videos')->withDownloads($downloads);
});

Route::get('videos/{id}/download', function ($id) {
    Redis::incr("videos.{$id}.downloads");

    return back();
});
