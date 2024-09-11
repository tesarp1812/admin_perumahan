<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('penghuni', 'PenghuniController@getPenghuni');
$router->post('penghuni', 'PenghuniController@store');
$router->put('penghuni/{id}', 'PenghuniController@update');


$router->get('rumah', 'RumahController@index');
$router->post('rumah', 'RumahController@store');
$router->put('rumah/{id}', 'RumahController@update');
$router->delete('rumah/{id}', 'RumahController@destroy');
