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

Route::group(['prefix' => 'v1'], function (){
   Route::get('events' , 'Api\ApiController@events');
   Route::get('speaker/{speaker_id}' , 'Api\ApiController@speaker');
   Route::get('registrations' , 'Api\ApiController@registrations');
   Route::get('organizers/{organizer_slug}/events/{event_slug}' , 'Api\ApiController@detail');
   Route::post('login', 'Api\ApiController@login');
   Route::post('register', 'Api\ApiController@registerAccount');
   Route::post('logout', 'Api\ApiController@logout');
   Route::post('organizers/{organizer_slug}/events/{event_slug}/registration', 'Api\ApiController@registration');
});
