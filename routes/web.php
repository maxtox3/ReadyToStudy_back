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

Route::get('reset_password/{token}', ['as' => 'password.reset', function($token)
{
    // implement your reset password route here!
}]);

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('login', function () {
    return view('login');
});

Route::get('register', 'WebController@getRegister');

Route::post('login', 'WebController@login');
Route::post('register', 'WebController@register');
Route::get('logout', 'WebController@logout');

Route::get('import', 'WebController@getImport');
Route::post('import', 'WebController@import');
Route::get('themesByName', 'WebController@themesByName');

Route::get('createDiscipline', 'WebController@getCreateDiscipline');
Route::post('createDiscipline', 'WebController@createDiscipline');
Route::get('createTheme', 'WebController@getCreateTheme');
Route::post('createTheme', 'WebController@createTheme');

Route::get('importGroups', 'WebController@getImportGroups');
Route::post('importGroups', 'WebController@importGroups');

