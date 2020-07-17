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

Route::get('/', function () {
    return view('teamList');
})->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// TeamController routes
Route::resource('teams','TeamController');
Route::post('teams/update', 'TeamController@update')->name('teams.update');
Route::get('teams/destroy/{id}', 'TeamController@destroy');
Route::get('teams/edit/{id}', 'TeamController@edit');
// PlayerController routes
Route::resource('players','PlayerController');
Route::get('players/details/{id}','PlayerController@details');
Route::post('players/update', 'PlayerController@update')->name('players.update');
Route::get('players/destroy/{id}', 'PlayerController@destroy');
Route::get('players/edit/{id}', 'PlayerController@edit');
Route::get('players/listByTeamId', 'PlayerController@listByTeamId');
// PointsController routes
Route::resource('matches','MatchController');
