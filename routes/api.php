<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth'], function(Router $api) {
        $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
        $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

        $api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');

        $api->post('logout', 'App\\Api\\V1\\Controllers\\LogoutController@logout');
        $api->post('refresh', 'App\\Api\\V1\\Controllers\\RefreshController@refresh');
        $api->get('me', 'App\\Api\\V1\\Controllers\\UserController@me');
    });

    $api->group(['middleware' => 'jwt.auth'], function(Router $api) {

        //----------------/disciplines/----------------//
        $api->get('disciplines', 'App\\Api\\V1\\Controllers\\DisciplineController@all');
        $api->get('disciplines/{id}', 'App\\Api\\V1\\Controllers\\DisciplineController@get');
        $api->post('discipline', 'App\\Api\\V1\\Controllers\\DisciplineController@create');
        $api->post('discipline/{id}', 'App\\Api\\V1\\Controllers\\DisciplineController@update');
        $api->delete('discipline/{id}', 'App\\Api\\V1\\Controllers\\DisciplineController@delete');

        //----------------/groups/----------------//
        $api->get('groups', 'App\\Api\\V1\\Controllers\\GroupController@all');
        $api->get('groups/{id}', 'App\\Api\\V1\\Controllers\\GroupController@get');

        //----------------/themes/----------------//
        $api->get('themes', 'App\\Api\\V1\\Controllers\\ThemeController@all');
        $api->get('themes/{id}', 'App\\Api\\V1\\Controllers\\ThemeController@get');
        $api->post('theme', 'App\\Api\\V1\\Controllers\\ThemeController@create');
        $api->put('theme', 'App\\Api\\V1\\Controllers\\ThemeController@update');
        $api->delete('theme', 'App\\Api\\V1\\Controllers\\ThemeController@delete');

        //----------------/tests/----------------//
        $api->get('tests', 'App\\Api\\V1\\Controllers\\TestController@all');
        $api->get('tests/{id}', 'App\\Api\\V1\\Controllers\\TestController@get');
        $api->post('test', 'App\\Api\\V1\\Controllers\\TestController@create');
        $api->put('test', 'App\\Api\\V1\\Controllers\\TestController@update');
        $api->delete('test', 'App\\Api\\V1\\Controllers\\TestController@delete');

        //----------------/tasks/----------------//
        $api->get('tasks', 'App\\Api\\V1\\Controllers\\TaskController@all');
        $api->get('tasks/{id}', 'App\\Api\\V1\\Controllers\\TaskController@get');
        $api->post('task', 'App\\Api\\V1\\Controllers\\TaskController@create');
        $api->put('task', 'App\\Api\\V1\\Controllers\\TaskController@update');
        $api->delete('task', 'App\\Api\\V1\\Controllers\\TaskController@delete');

    });

    $api->get('hello', function() {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    });
});
