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
//PUT PARA ACTUALIZAR DATOS O RECURSOS
//POST GUARDAR DATOS O RECURSOS
Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-orm','PruebaController@testOrm');

Route::post('/api/register','UserController@register');
Route::post('/api/login','UserController@login');
Route::put('/api/update','UserController@update');
//Route::post('/api/update','UserController@update');
//Route::post('/api/login','UserController@login');
//Route::post('/api/prueba','UserController@pruebaPost');















