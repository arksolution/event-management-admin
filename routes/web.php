<?php

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


use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', function () {
    return redirect('/event');
});

Route::get('/home', function () {
    return redirect('/event');
});

Route::resource('speaker', 'SpeakerController');

Route::resource('event', 'EventController');

Route::group(['prefix' => 'event/{event}'], function (){
   Route::resource('ticket', 'TicketController');
   Route::resource('session', 'SessionController');
   Route::resource('channel', 'ChannelController');
   Route::resource('room', 'RoomController');
   Route::get('report', 'EventController@report')->name('report');
});
