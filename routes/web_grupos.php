<?php


Route::group(['middleware'=>App\Http\Middleware\Autenticacion_grupos::class],function(){

	Route::resource('grupos/resumen', 'GruposResumenController', [
		'names' => [
			'index' => '/grupos/resumen'
		]
	]);

	Route::resource('grupos/cuenta', 'GruposCuentaController', [
		'names' => [
			'index' => '/grupos/cuenta'
		]
	]);
	Route::post('grupos/cambiar_pass', 'GruposCuentaController@cambiar_pass')->name('/grupos/cambiar_pass');



	Route::get('grupos/nominas', 'GruposNominasController@index')->name('/clientes/nominas');
	Route::post('grupos/nominas/busqueda','GruposNominasController@busqueda');

	Route::get('grupos/ausentismos', 'GruposAusentismosController@index')->name('/clientes/ausentismos');
	Route::post('grupos/ausentismos/busqueda','GruposAusentismosController@busqueda');

});