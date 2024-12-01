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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/ausentismos_mes_actual/{token}', 'EndpointsController@ausentismosMesActual');
Route::get('/ausentismos_hoy/{token}', 'EndpointsController@ausentismosHoy');
Route::get('/get_nominas/{token}', 'EndpointsController@getNominas');
Route::post('/set_nominas', 'EndpointsController@setNominas');
Route::delete('/delete_nominas', 'EndpointsController@deleteNominas');

Route::post('/actualizar_cliente_actual', 'EmpleadosFichadasNuevasController@clienteActual')->name('actualizar_cliente_actual');