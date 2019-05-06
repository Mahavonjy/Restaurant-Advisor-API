<?php

use Illuminate\Http\Request;

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

/* ALL ROUTES FOR AUTHENTIFICATE */

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Authentification\AuthController@login');
    Route::post('signup', 'Authentification\AuthController@signup');

    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'Authentification\AuthController@logout');
    });
});

/* SOCIAL NETWORK */

Route::group(['middleware' => ['web']], function () {
    Route::get('auth/{provider}', 'Authentification\AuthController@redirectToProvider');
    Route::get('auth/{provider}/callback', 'Authentification\AuthController@handleProviderCallback');
});

/* RESET PASSWORD */

Route::group([
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'Authentification\PasswordResetController@create');
    Route::get('find/{token}', 'Authentification\PasswordResetController@find');
    Route::post('reset', 'Authentification\PasswordResetController@reset');
});

/* USER PROFIL HERE */

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'profils'
], function () {
    Route::get('show', 'Profil\ProfilController@show');
    Route::get('showid/{id}', 'Profil\ProfilController@showid');
    Route::put('update', 'Profil\ProfilController@update');
    Route::delete('destroy', 'Profil\ProfilController@destroy');
});

/* RESTAURANTS */

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'restaurants'
], function () {
    Route::post('store', 'Restaurants\RestaurantsController@store');
    Route::put('UpdateNote/{id}', 'Restaurants\RestaurantsController@UpdateNote');
    Route::put('update/{id}', 'Restaurants\RestaurantsController@update');
    Route::delete('destroy/{id}', 'Restaurants\RestaurantsController@destroy');
});

Route::group([
    'prefix' => 'restaurant'
], function () {
    Route::get('show', 'Restaurants\RestaurantsController@show');
    Route::get('idshow/{id}', 'Restaurants\RestaurantsController@IdShow');
    Route::get('nameshow/{name}', 'Restaurants\RestaurantsController@NameShow');
    Route::get('mostvisited', 'Restaurants\RestaurantsController@getmostvisited');
    Route::get('getbestnote', 'Restaurants\RestaurantsController@getbestnote');
    Route::get('getnote', 'Restaurants\RestaurantsController@getnote');
    Route::get('getprice', 'Restaurants\RestaurantsController@getprice');
    Route::get('getrecentadd', 'Restaurants\RestaurantsController@getrecentadd');
});

/* RESTAURANTS MENUS */

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'menus'
], function () {
    Route::post('store/{id}', 'Restaurants\MenuController@store');
    Route::put('update/{id}', 'Restaurants\MenuController@update');
    Route::delete('destroy/{id}', 'Restaurants\MenuController@destroy');
});

Route::group([
    'prefix' => 'menu'
], function () {
    Route::get('show/{id}', 'Restaurants\MenuController@show');
});

/* RESTAURANTS DAYS */

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'days'
], function () {
    Route::put('update/{id}', 'Restaurants\DaysController@update');
});

/* MENUS AVIS */

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'avis'
], function () {
    Route::post('storeMenu/{id}', 'Restaurants\AvisController@storeMenu');
    Route::put('update/{id}', 'Restaurants\AvisController@update');
    Route::delete('destroyMenu/{id}', 'Restaurants\AvisController@destroyMenu');
    Route::post('storeRestau/{id}', 'Restaurants\AvisController@storeRestau');
});

Route::group([
    'prefix' => 'avi'
], function () {
    Route::get('showMenu/{id}', 'Restaurants\AvisController@showMenu');
    Route::get('showRestau/{id}', 'Restaurants\AvisController@showRestau');
});

/* ADMIN */

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'admin'
], function () {
    Route::get('show', 'Admin\AdminController@show');
    Route::put('update/{id}', 'Admin\AdminController@update');
});