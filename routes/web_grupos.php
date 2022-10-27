<?php


Route::group(['middleware'=>App\Http\Middleware\Autenticacion_grupos::class],function(){

	Route::get('grupos/resumen', 'GruposResumenController@index')->name('/grupos/resumen');
	Route::get('grupos/resumen_cliente', 'GruposResumenController@index_cliente')->name('/grupos/resumen_cliente');

	Route::get('grupos/index_cliente_ajax', 'GruposResumenController@index_cliente_ajax');

	Route::resource('grupos/cuenta', 'GruposCuentaController',[
		'names'=>[
			'index'=>'/grupos/cuenta'
		]
	]);
	Route::post('grupos/cambiar_pass', 'GruposCuentaController@cambiar_pass')->name('/grupos/cambiar_pass');



	Route::get('grupos/nominas', 'GruposNominasController@index')->name('/grupos/nominas');
	Route::post('grupos/nominas/busqueda','GruposNominasController@busqueda');

	Route::get('grupos/ausentismos', 'GruposAusentismosController@index')->name('/grupos/ausentismos');
	Route::post('grupos/ausentismos/busqueda','GruposAusentismosController@busqueda');


	Route::post('grupos/actualizar_cliente_actual', 'GruposResumenController@clienteActual')->name('/grupos/actualizar_cliente_actual');

	Route::get('grupos/api', 'GruposApiController@index')->name('/grupos/api');

});
