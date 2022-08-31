<?php


Route::group(['middleware'=>App\Http\Middleware\Autenticacion_grupos::class],function(){

	Route::get('grupos/resumen', 'GruposResumenController@index')->name('/grupos/resumen');

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

});