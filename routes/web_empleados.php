<?php

Route::group(['middleware' => 'autenticacion_empleados'], function () {

	// RESUMEN
	Route::get('empleados/resumen', 'EmpleadosResumenController@index')->name('/empleados/resumen');

	// CUENTA
	Route::get('empleados/cuenta', 'EmpleadosCuentaController@index')->name('/empleados/cuenta');
	Route::post('empleados/cuenta', 'EmpleadosCuentaController@store')->name('/empleados/cuenta');
	Route::post('empleados/cambiar_pass', 'EmpleadosCuentaController@cambiar_pass')->name('/empleados/cambiar_pass');
	Route::get('empleados/cuenta/download_titulo/{id}', 'EmpleadosCuentaController@downloadTitulo')->name('cuenta.download_titulo');
	Route::get('empleados/cuenta/download_dni/{id}', 'EmpleadosCuentaController@downloadDni')->name('cuenta.download_dni');
	Route::get('empleados/cuenta/download_matricula/{id}', 'EmpleadosCuentaController@downloadMatricula')->name('cuenta.download_matricula');
	Route::get('empleados/cuenta/download_titulo_detras/{id}', 'EmpleadosCuentaController@downloadTituloDetras')->name('cuenta.download_titulo_detras');
	Route::get('empleados/cuenta/download_dni_detras/{id}', 'EmpleadosCuentaController@downloadDniDetras')->name('cuenta.download_dni_detras');
	Route::get('empleados/cuenta/download_matricula_detras/{id}', 'EmpleadosCuentaController@downloadMatriculaDetras')->name('cuenta.download_matricula_detras');

	// LIQUIDACION
	Route::get('empleados/liquidacion', 'EmpleadosLiquidacionController@index')->name('/empleados/liquidacion');


	// NOMINAS (Trabajadores)
	Route::resource('empleados/nominas', 'EmpleadosNominasController', [
		'names' => [
			'index' => '/empleados/nominas'
		]
	]);
	Route::post('empleados/nominas/cargar_excel', 'EmpleadosNominasController@cargar_excel')->name('/empleados/nominas/cargar_excel');
	//Route::post('empleados/listado', 'EmpleadosNominasController@listado')->name('empleados.listado');
	//Route::get('empleados/buscar', 'EmpleadosNominasController@buscar')->name('empleados.buscar');
	Route::delete('empleados/nominas/destroy/{id}','EmpleadosNominasController@destroy');
	Route::post('empleados/nominas/busqueda','EmpleadosNominasController@busqueda');

	// AUSENTISMOS
	Route::resource('empleados/ausentismos', 'EmpleadosAusentismosController', [
		'names' => [
			'index' => '/empleados/ausentismos'
		]
	]);
	Route::delete('empleados/ausentismos/destroy/{id}','EmpleadosAusentismosController@destroy');
	Route::post('empleados/ausentismos/busqueda','EmpleadosAusentismosController@busqueda');
	//Route::get('empleados/getAusentismos', 'EmpleadosAusentismosController@getAusentismos')->name('ausentismos.get_ausentismos');
	Route::post('empleados/ausentismos/tipo', 'EmpleadosAusentismosController@tipo')->name('/empleados/ausentismos/tipo');
	Route::delete('empleados/ausentismos/tipo_delete/{id_tipo}', 'EmpleadosAusentismosController@tipo_destroy')->name('ausentismos.tipo_delete');
	Route::get('empleados/ausentismos/archivo/{id}', 'EmpleadosAusentismosController@descargar_archivo')->name('ausentismos.archivo');



	// FICHADAS
	Route::resource('empleados/fichadas', 'EmpleadosFichadasController', [
		'names' => [
			'index' => '/empleados/fichadas'
		]
	]);
	Route::resource('empleados/fichadas_nuevas', 'EmpleadosFichadasNuevasController', [
		'names' => [
			'index' => '/empleados/fichadas_nuevas'
		]
	]);


	// Ver el horario de la ultima fichada del usuario (Hay varios porque subsecciones no agarran en endpoint sino)
	// Route::get('empleados/horario_ultima_fichada', 'EmpleadosFichadasController@horarioUltimaFichada')->name('empleados/horario_ultima_fichada');
	// Route::get('empleados/covid/horario_ultima_fichada', 'EmpleadosFichadasController@horarioUltimaFichada')->name('empleados/covid/horario_ultima_fichada');
	// Route::get('empleados/covid/testeos/horario_ultima_fichada', 'EmpleadosFichadasController@horarioUltimaFichada')->name('empleados/covid/testeos/horario_ultima_fichada');
	// Route::get('empleados/covid/vacunas/horario_ultima_fichada', 'EmpleadosFichadasController@horarioUltimaFichada')->name('empleados/covid/vacunas/horario_ultima_fichada');

	Route::get('empleados/horario_ultima_fichada', 'EmpleadosFichadasNuevasController@horarioUltimaFichada')->name('empleados/horario_ultima_fichada');
	Route::get('empleados/covid/horario_ultima_fichada', 'EmpleadosFichadasNuevasController@horarioUltimaFichada')->name('empleados/covid/horario_ultima_fichada');
	Route::get('empleados/covid/testeos/horario_ultima_fichada', 'EmpleadosFichadasNuevasController@horarioUltimaFichada')->name('empleados/covid/testeos/horario_ultima_fichada');
	Route::get('empleados/covid/vacunas/horario_ultima_fichada', 'EmpleadosFichadasNuevasController@horarioUltimaFichada')->name('empleados/covid/vacunas/horario_ultima_fichada');

	// Actualizar el cliente actual del User Empleado
	// Route::post('empleados/actualizar_cliente_actual', 'EmpleadosFichadasController@clienteActual')->name('empleados/actualizar_cliente_actual');
	Route::post('empleados/actualizar_cliente_actual', 'EmpleadosFichadasNuevasController@clienteActual')->name('empleados/actualizar_cliente_actual');


	// COMUNICACIONES
	Route::resource('empleados/comunicaciones', 'EmpleadosComunicacionesController', [
		'names' => [
			'index' => '/empleados/comunicaciones'
		]
	]);
	Route::delete('empleados/comunicaciones/destroy/{id}','EmpleadosComunicacionesController@destroy');
	Route::post('empleados/comunicaciones/busqueda','EmpleadosComunicacionesController@busqueda');
	Route::post('empleados/comunicaciones/tipo', 'EmpleadosComunicacionesController@tipo')->name('/empleados/comunicaciones/tipo');
	Route::delete('empleados/comunicaciones/tipo_delete/{id_tipo}', 'EmpleadosComunicacionesController@tipo_destroy')->name('comunicaciones.tipo_delete');
	Route::get('empleados/comunicaciones/getComunicacion/{id}', 'EmpleadosComunicacionesController@getComunicacion')->name('comunicaciones.getComunicacion');

	// CERTIFICADOS
	Route::get('empleados/certificados', 'EmpleadosCertificadosController@listado')->name('/empleados/certificados');
	Route::post('empleados/certificados/busqueda','EmpleadosCertificadosController@busqueda');


	// PREOCUPACIONALES
	Route::resource('empleados/preocupacionales', 'EmpleadosPreocupacionalesController', [
		'names' => [
			'index' => '/empleados/preocupacionales'
		]
	]);
	Route::delete('empleados/preocupacionales/destroy/{id}','EmpleadosPreocupacionalesController@destroy');
	Route::post('empleados/preocupacionales/busqueda','EmpleadosPreocupacionalesController@busqueda');
	Route::get('empleados/preocupacionales/archivo/{id}', 'EmpleadosPreocupacionalesController@descargar_archivo')->name('preocupacionales.archivo');


	// MEDICAMENTOS
	Route::resource('empleados/medicamentos', 'EmpleadosStockMedicamentoController', [
		'names' => [
			'index' => '/empleados/medicamentos'
		]
	]);
	Route::post('empleados/medicamentos/busqueda','EmpleadosStockMedicamentoController@busqueda');
	Route::post('empleados/medicamentos_movimientos/busqueda','EmpleadosStockMedicamentoController@busquedaMovimientos');
	Route::get('empleados/medicamentos_movimientos', 'EmpleadosStockMedicamentoController@movimientos')->name('/empleados/medicamentos_movimientos');


	// DOCUMENTACIONES
	Route::resource('empleados/documentaciones', 'EmpleadosAusentismoDocumentacionController', [
		'names' => [
			'index' => 'empleados.documentaciones'
		]
	]);
	Route::get('empleados/documentacion_ausentismo/archivo/{id}', 'EmpleadosAusentismoDocumentacionController@descargar_archivo')->name('documentacion_ausentismo.archivo');
	Route::get('empleados/documentaciones/getDocumentacion/{id}', 'EmpleadosAusentismoDocumentacionController@getDocumentacion')->name('documentaciones.getDocumentacion');
	Route::post('empleados/documentaciones/validarMatricula', 'EmpleadosAusentismoDocumentacionController@validarMatricula');


	// COVID TESTEOS
	Route::resource('empleados/covid/testeos', 'EmpleadosCovidTesteoController', [
		'names' => [
			'index' => 'empleados.covid.testeos'
		]
	]);
	Route::delete('empleados/covid/testeos/destroy/{id}','EmpleadosCovidTesteoController@destroy');
	Route::post('empleados/covid/testeos/busqueda','EmpleadosCovidTesteoController@busqueda');
	Route::post('empleados/covid/testeos/tipo', 'EmpleadosCovidTesteoController@tipo')->name('/empleados/covid/testeos/tipo');
	Route::delete('empleados/covid/testeos/tipo_delete/{id_tipo}', 'EmpleadosCovidTesteoController@tipo_destroy')->name('covid.testeos.tipo_delete');


	// COVID VACUNAS
	Route::resource('empleados/covid/vacunas', 'EmpleadosCovidVacunasController', [
		'names' => [
			'index' => 'empleados.covid.vacunas'
		]
	]);
	Route::delete('empleados/covid/vacunas/destroy/{id}','EmpleadosCovidVacunasController@destroy');
	Route::post('empleados/covid/vacunas/busqueda','EmpleadosCovidVacunasController@busqueda');
	Route::post('empleados/covid/vacunas/tipo', 'EmpleadosCovidVacunasController@tipo')->name('/empleados/covid/vacunas/tipo');
	Route::delete('empleados/covid/vacunas/tipo_delete/{id_tipo}', 'EmpleadosCovidVacunasController@tipo_destroy')->name('covid.vacunas.tipo_delete');



	// CONSULTAS MEDICAS
	Route::resource('empleados/consultas/medicas', 'EmpleadoConsultaMedicaController', [
		'names' => [
			'index' => 'empleados.consultas.medicas'
		]
	]);
	Route::post('empleados/consultas/medicas/busqueda','EmpleadoConsultaMedicaController@busqueda');
	Route::post('empleados/consultas/medicas/tipo', 'EmpleadoConsultaMedicaController@tipo')->name('/empleados/consultas/medicas/tipo');
	Route::delete('empleados/consultas/medicas/tipo_delete/{id_tipo}', 'EmpleadoConsultaMedicaController@tipo_destroy')->name('consultas.medicas.tipo_delete');



	// CONSULTAS ENFERMERIA
	Route::resource('empleados/consultas/enfermeria', 'EmpleadoConsultaEnfermeriaController', [
		'names' => [
			'index' => 'empleados.consultas.enfermeria'
		]
	]);
	Route::post('empleados/consultas/enfermeria/busqueda','EmpleadoConsultaEnfermeriaController@busqueda');
	Route::post('empleados/consultas/enfermeria/tipo', 'EmpleadoConsultaEnfermeriaController@tipo')->name('/empleados/consultas/enfermeria/tipo');
	Route::delete('empleados/consultas/enfermeria/tipo_delete/{id_tipo}', 'EmpleadoConsultaEnfermeriaController@tipo_destroy')->name('consultas.enfermeria.tipo_delete');



});