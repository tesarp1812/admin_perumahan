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

$router->get('warga', 'PenghuniController@getWarga');
$router->get('warga/{id}', 'PenghuniController@getWargaById');
$router->post('warga', 'PenghuniController@storeWarga');
$router->get('rumah', 'PenghuniController@getRumah');
$router->get('rumah/{id}', 'PenghuniController@getRumahById');
$router->get('penghuni', 'PenghuniController@getPenghuni');
$router->get('penghuni/{id}', 'PenghuniController@getPenghuniById');

$router->get('iuran', 'AdministrasiController@getIuran');
$router->post('pembayaran', 'AdministrasiController@storePembayaran');

