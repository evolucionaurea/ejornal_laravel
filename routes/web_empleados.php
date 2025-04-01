<?php

use App\Http\Controllers\EmpleadosAusentismoDocumentacionController;

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
	Route::get('empleados/nominas/exportar','EmpleadosNominasController@exportar');


	Route::get('empleados/nominas/historial', 'EmpleadosNominasController@historial')->name('/empleados/nominas/historial');
	Route::post('empleados/nominas/historial_listado', 'EmpleadosNominasController@historial_listado')->name('/empleados/nominas/historial_listado');

	Route::get('empleados/nominas/movimientos', 'EmpleadosNominasController@movimientos')->name('/empleados/nominas/movimientos');
	Route::post('empleados/nominas/movimientos_search', 'EmpleadosNominasController@movimientos_search');

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
	Route::get('empleados/ausentismo/{id}','EmpleadosAusentismosController@show')->name('ausentismo');
	Route::get('empleados/ausentismos/exportar','EmpleadosAusentismosController@exportar');
	Route::resource('empleados/ausentismos', 'EmpleadosAusentismosController', [
		'names' => [
			'index' => '/empleados/ausentismos'
		]
	]);
	Route::delete('empleados/ausentismos/destroy/{id}','EmpleadosAusentismosController@destroy');
	Route::post('empleados/ausentismos/busqueda','EmpleadosAusentismosController@busqueda');
	//Route::get('empleados/getAusentismos', 'EmpleadosAusentismosController@getAusentismos')->name('ausentismos.get_ausentismos');
	Route::post('empleados/ausentismos/editar_tipo/', 'EmpleadosAusentismosController@editarTipo')->name('/empleados/ausentismos/tipo/edit');
	Route::post('empleados/ausentismos/tipo', 'EmpleadosAusentismosController@tipo')->name('/empleados/ausentismos/tipo');
	Route::delete('empleados/ausentismos/tipo_delete/{id_tipo}', 'EmpleadosAusentismosController@tipo_destroy')->name('ausentismos.tipo_delete');
	Route::get('empleados/ausentismos/archivo/{id}', 'EmpleadosAusentismosController@descargar_archivo')->name('ausentismos.archivo');
	Route::post('empleados/ausentismos/extension_comunicacion','EmpleadosAusentismosController@extensionComunicacion');


	// TAREAS LIVIANAS
	Route::get('empleados/tareas_livianas/exportar','EmpleadoTareasLivianasController@exportar');
	Route::resource('empleados/tareas_livianas', 'EmpleadoTareasLivianasController', [
		'names' => [
			'index' => '/empleados/tareas_livianas'
		]
	]);
	Route::delete('empleados/tareas_livianas/destroy/{id}','EmpleadoTareasLivianasController@destroy');
	Route::post('empleados/tareas_livianas/busqueda','EmpleadoTareasLivianasController@busqueda');
	Route::post('empleados/tareas_livianas/tipo', 'EmpleadoTareasLivianasController@tipo')->name('/empleados/tareas_livianas/tipo');
	Route::delete('empleados/tareas_livianas/tipo_delete/{id_tipo}', 'EmpleadoTareasLivianasController@tipo_destroy')->name('tareas_livianas.tipo_delete');
	Route::get('empleados/tareas_livianas/archivo/{id}', 'EmpleadoTareasLivianasController@descargar_archivo')->name('tareas_livianas.archivo');
	Route::post('empleados/tareas_livianas/extension_comunicacion','EmpleadoTareasLivianasController@extensionComunicacion');

	// DOCUMENTACIONES LIVIANAS
	Route::resource('empleados/documentaciones_livianas', 'EmpleadosTareasLivianasDocumentacion', [
		'names' => [
			'index' => 'empleados.documentaciones_livianas'
		]
	]);
	Route::get('empleados/documentacion_liviana/archivo/{id}', 'EmpleadosTareasLivianasDocumentacion@descargar_archivo')->name('documentacion_liviana.archivo');
	Route::get('empleados/documentaciones_livianas/getDocumentacion/{id}', 'EmpleadosTareasLivianasDocumentacion@getDocumentacion')->name('documentaciones_livianas.getDocumentacion');
	Route::post('empleados/documentaciones_livianas/validarMatricula', 'EmpleadosTareasLivianasDocumentacion@validarMatricula');



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
	// Route::post('empleados/actualizar_cliente_actual', 'EmpleadosFichadasNuevasController@clienteActual')->name('empleados/actualizar_cliente_actual');


	// COMUNICACIONES
	Route::get('empleados/comunicaciones/exportar','EmpleadosComunicacionesController@exportar');
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

	Route::get('comunicaciones/archivo/{id}/{hash}', 'EmpleadosComunicacionesController@verArchivo')->name('comunicaciones.verArchivo');


	// COMUNICACIONES LIVIANAS
	Route::resource('empleados/comunicaciones_livianas', 'EmpleadosComunicacionesLivianas', [
		'names' => [
			'index' => '/empleados/comunicaciones_livianas'
		]
	]);
	Route::delete('empleados/comunicaciones_livianas/destroy/{id}','EmpleadosComunicacionesLivianas@destroy');
	Route::post('empleados/comunicaciones_livianas/busqueda','EmpleadosComunicacionesLivianas@busqueda');
	Route::post('empleados/comunicaciones_livianas/tipo', 'EmpleadosComunicacionesLivianas@tipo')->name('/empleados/comunicaciones_livianas/tipo');
	Route::delete('empleados/comunicaciones_livianas/tipo_delete/{id_tipo}', 'EmpleadosComunicacionesLivianas@tipo_destroy')->name('comunicaciones_livianas.tipo_delete');
	Route::get('empleados/comunicaciones_livianas/getComunicacionLiviana/{id}', 'EmpleadosComunicacionesLivianas@getComunicacionLiviana')->name('comunicaciones_livianas.getComunicacionLiviana');



	// CERTIFICADOS
	Route::get('empleados/certificados', 'EmpleadosCertificadosController@listado')->name('/empleados/certificados');
	Route::get('empleados/certificados/exportar', 'EmpleadosCertificadosController@exportar');
	Route::post('empleados/certificados/busqueda','EmpleadosCertificadosController@busqueda');


	// CERTIFICADOS LIVIANOS
	Route::get('empleados/certificados_livianos', 'EmpleadosCertificadosLivianosController@listado')->name('/empleados/certificados_livianos');
	Route::post('empleados/certificados_livianos/busqueda','EmpleadosCertificadosLivianosController@busqueda');

	// PREOCUPACIONALES
	Route::post('empleados/preocupacionales/completar','EmpleadosPreocupacionalesController@completar');
	Route::resource('empleados/preocupacionales', 'EmpleadosPreocupacionalesController', [
		'names' => [
			'index' => '/empleados/preocupacionales'
		]
	]);
	Route::delete('empleados/preocupacionales/destroy/{id}','EmpleadosPreocupacionalesController@destroy');
	Route::post('empleados/preocupacionales/busqueda','EmpleadosPreocupacionalesController@busqueda');
	Route::get('empleados/preocupacionales/archivo/{id}', 'EmpleadosPreocupacionalesController@descargar_archivo')->name('preocupacionales.archivo');
	Route::get('empleados/preocupacionales/find/{id}', 'EmpleadosPreocupacionalesController@find');


	//Tipo Preocupacionales
	Route::delete('empleados/preocupacionales_tipos/{id}','EmpleadosPreocupacionalesTipoController@destroy')->name('preocupacionales_tipos.delete');
	Route::post('empleados/preocupacionales_tipos','EmpleadosPreocupacionalesTipoController@store');


	// MEDICAMENTOS
	Route::get('empleados/medicamentos/exportar','EmpleadosStockMedicamentoController@exportar');
	Route::get('empleados/medicamentos_movimientos/exportar','EmpleadosStockMedicamentoController@exportarHistorial');
	Route::resource('empleados/medicamentos', 'EmpleadosStockMedicamentoController', [
		'names' => [
			'index' => '/empleados/medicamentos'
		]
	]);
	Route::post('empleados/medicamentos/busqueda','EmpleadosStockMedicamentoController@busqueda');
	Route::post('empleados/medicamentos_movimientos/busqueda','EmpleadosStockMedicamentoController@busquedaMovimientos');
	Route::get('empleados/medicamentos_movimientos', 'EmpleadosStockMedicamentoController@movimientos')->name('/empleados/medicamentos_movimientos');
	Route::get('empleados/medicamentos/stock_actual/{id}', 'EmpleadosStockMedicamentoController@stock_actual');


	// DOCUMENTACIONES
	Route::get('empleados/documentaciones/find_ajax/{id}', 'EmpleadosAusentismoDocumentacionController@find_ajax')->name('empleados/documentaciones/find_ajax');

	Route::post('empleados/documentaciones/store', 'EmpleadosAusentismoDocumentacionController@store')->name('empleados/documentaciones/store');

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


	// CONSULTAS TODAS
	Route::get('empleados/consultas/todas','EmpleadoConsultasTodasController@index')->name('empleados.consultas.todas');
	Route::post('empleados/consultas/todas/busqueda','EmpleadoConsultasTodasController@busqueda');
	Route::get('empleados.consultas.todas/exportar','EmpleadoConsultasTodasController@exportar');


	// CONSULTAS MEDICAS
	Route::get('empleados.consultas.medicas/exportar','EmpleadoConsultaMedicaController@exportar');
	Route::resource('empleados/consultas/medicas', 'EmpleadoConsultaMedicaController', [
		'names' => [
			'index' => 'empleados.consultas.medicas'
		]
	]);
	Route::post('empleados/consultas/medicas/busqueda','EmpleadoConsultaMedicaController@busqueda');
	Route::post('empleados/consultas/medicas/tipo', 'EmpleadoConsultaMedicaController@tipo')->name('/empleados/consultas/medicas/tipo');
	Route::delete('empleados/consultas/medicas/tipo_delete/{id_tipo}', 'EmpleadoConsultaMedicaController@tipo_destroy')->name('consultas.medicas.tipo_delete');



	// CONSULTAS ENFERMERIA
	Route::get('empleados.consultas.enfermeria/exportar','EmpleadoConsultaEnfermeriaController@exportar');
	Route::resource('empleados/consultas/enfermeria', 'EmpleadoConsultaEnfermeriaController', [
		'names' => [
			'index' => 'empleados.consultas.enfermeria'
		]
	]);
	Route::post('empleados/consultas/enfermeria/busqueda','EmpleadoConsultaEnfermeriaController@busqueda');
	Route::post('empleados/consultas/enfermeria/tipo', 'EmpleadoConsultaEnfermeriaController@tipo')->name('/empleados/consultas/enfermeria/tipo');
	Route::delete('empleados/consultas/enfermeria/tipo_delete/{id_tipo}', 'EmpleadoConsultaEnfermeriaController@tipo_destroy')->name('consultas.enfermeria.tipo_delete');


	// CONSULTAS Nutricionales
	Route::resource('empleados/consultas/nutricionales', 'EmpleadosConsultaNutricionalController', [
		'names' => [
			'index' => 'empleados.consultas.nutricionales',      // Listado de recursos
			'create' => 'empleados.consultas.nutricionales.create',    // Formulario de creación
			'store' => 'empleados.consultas.nutricionales.store',      // Guardar nuevo recurso
			'show' => 'empleados.consultas.nutricionales.show',        // Mostrar un recurso específico
			'edit' => 'empleados.consultas.nutricionales.edit',        // Formulario de edición
			'update' => 'empleados.consultas.nutricionales.update',    // Actualizar un recurso específico
			'destroy' => 'empleados.consultas.nutricionales.destroy',  // Eliminar un recurso específico
		],
	]);


	Route::resource('empleados/consultas/patologias', 'EmpleadosPatologiasController', [
		'names' => [
			'index' => 'empleados.consultas.patologias'
		]
	]);


	Route::get('empleados/caratulas', 'EmpleadosCaratulaController@index')->name('empleados.caratulas');
	Route::get('empleados/nominas/caratulas/create/{id_nomina}', 'EmpleadosCaratulaController@create')->name('empleados.nominas.caratulas.create');
	Route::post('empleados/nominas/caratulas', 'EmpleadosCaratulaController@store')->name('empleados.nominas.caratulas.store');
	Route::get('empleados/nominas/caratulas/{id}', 'EmpleadosCaratulaController@show')->name('empleados.nominas.caratulas.show');
	Route::get('empleados/nominas/caratulas/{id}/edit', 'EmpleadosCaratulaController@edit')->name('empleados.nominas.caratulas.edit');
	Route::put('empleados/nominas/caratulas/{id}', 'EmpleadosCaratulaController@update')->name('empleados.nominas.caratulas.update');
	Route::delete('empleados/nominas/caratulas/{id}', 'EmpleadosCaratulaController@destroy')->name('empleados.nominas.caratulas.destroy');



});