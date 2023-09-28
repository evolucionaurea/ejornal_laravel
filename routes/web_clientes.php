<?php

Route::group(['middleware' => 'autenticacion_clientes'], function () {


	Route::resource('clientes/resumen', 'ClientesResumenController', [
		'names' => [
			'index' => '/clientes/resumen'
		]
		]);

	Route::resource('clientes/cuenta', 'ClientesCuentaController', [
		'names' => [
			'index' => '/clientes/cuenta'
		]
	]);
	Route::post('clientes/cambiar_pass', 'ClientesCuentaController@cambiar_pass')->name('/clientes/cambiar_pass');


	Route::get('clientes/nominas', 'ClientesNominasController@index')->name('/clientes/nominas');
	Route::post('clientes/nominas/busqueda','ClientesNominasController@busqueda');
	Route::get('clientes/nominas/exportar', 'ClientesNominasController@exportar');
	Route::get('clientes/nominas/historial', 'ClientesNominasController@historial');

	Route::get('clientes/ausentismos', 'ClientesAusentismosController@index')->name('/clientes/ausentismos');
	Route::post('clientes/ausentismos/busqueda','ClientesAusentismosController@busqueda');
	Route::get('clientes/ausentismos/exportar', 'ClientesAusentismosController@exportar');

	//Route::get('clientes/getAccidentesAnual', 'ClientesResumenController@getAccidentesAnual')->name('/clientes/get_accidentes_anual');
	//Route::get('clientes/getAccidentesMesActual', 'ClientesResumenController@getAccidentesMesActual')->name('/clientes/get_accidentes_mes_actual');

	Route::get('clientes/index_ajax', 'ClientesResumenController@index_ajax');

	Route::get('clientes/api', 'ClientesApiController@index')->name('/clientes/api');

});