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

Route::get('reset_password/{token}', ['as' => 'password.reset', function ($token) {
    // implement your reset password route here!
}]);

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('login', function () {
    return view('login');
});

Route::get('register', 'AuthWebController@getRegister');

Route::post('login', 'AuthWebController@login');
Route::post('register', 'AuthWebController@register');
Route::get('logout', 'AuthWebController@logout');

Route::get('import', 'ImportWebController@getImport');
Route::post('import', 'ImportWebController@import');
Route::get('importGroups', 'ImportWebController@getImportGroups');
Route::post('importGroups', 'ImportWebController@importGroups');

Route::get('themesByName', 'CreateEntityWebController@themesByName');

Route::get('createDiscipline', 'CreateEntityWebController@getCreateDiscipline');
Route::post('createDiscipline', 'CreateEntityWebController@createDiscipline');

Route::get('createTheme', 'CreateEntityWebController@getCreateTheme');
Route::post('createTheme', 'CreateEntityWebController@createTheme');

Route::get('analytics', 'AnalyticsWebController@getAnalytics');
Route::get('profile', 'AuthWebController@getProfile');

Route::get('analytics/tests', 'AnalyticsWebController@getTests');
Route::get('analytics/time', 'AnalyticsWebController@getTime');
Route::get('analytics/answers', 'AnalyticsWebController@getAnswers');

