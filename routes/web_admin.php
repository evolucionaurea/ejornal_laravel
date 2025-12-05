<?php


Route::group(['middleware' => 'autenticacion_admin'], function () {

	Route::get('admin/monitoreo', 'AdminMonitoreoController@index')->name('/admin/monitoreo');
	Route::get('admin/monitoreo/metrics/{metric}', 'AdminMonitoreoController@metric')->name('admin.monitoreo.metric');

	Route::get('admin/resumen', 'AdminResumenController@index')->name('/admin/resumen');
	Route::post('admin/get_medicamentos', 'AdminResumenController@getMedicamentos');


	// USERS
	Route::resource('admin/users', 'AdminUserController', [
		'names' => [
			'index' => '/admin/users'
		]
	]);
	Route::delete('admin/users/destroy/{id}','AdminUserController@destroy');
	Route::post('admin/users/busqueda','AdminUserController@busqueda');

	Route::get('admin/users/download_titulo/{id}', 'AdminUserController@downloadTitulo')->name('users.download_titulo');
	Route::get('admin/users/download_dni/{id}', 'AdminUserController@downloadDni')->name('users.download_dni');
	Route::get('admin/users/download_matricula/{id}', 'AdminUserController@downloadMatricula')->name('users.download_matricula');
	Route::get('admin/users/download_titulo_detras/{id}', 'AdminUserController@downloadTituloDetras')->name('users.download_titulo_detras');
	Route::get('admin/users/download_dni_detras/{id}', 'AdminUserController@downloadDniDetras')->name('users.download_dni_detras');
	Route::get('admin/users/download_matricula_detras/{id}', 'AdminUserController@downloadMatriculaDetras')->name('users.download_matricula_detras');
	// Route::get('admin/users_fichadas', 'AdminFichadasController@fichadas')->name('/admin/users_fichadas');
	Route::get('admin/users_fichadas_nuevas', 'AdminFichadasNuevasController@fichadas')->name('/admin/users_fichadas_nuevas');
	Route::post('admin/users/reset_password', 'AdminUserController@reset_password')->name('/admin/users/reset_password');


	// CLIENTES
	Route::resource('admin/clientes', 'AdminClientesController', [
		'names' => [
			'index' => '/admin/clientes',
			'create' => 'admin.clientes.create',
			'store' => 'admin.clientes.store',
			'show' => 'admin.clientes.show',
			'edit' => 'admin.clientes.edit',
			'update' => 'admin.clientes.update',
			'destroy' => 'admin.clientes.destroy'
		]
	]);

	Route::post('admin/clientes/cargar_excel', 'AdminClientesController@cargar_excel')->name('/admin/clientes/cargar_excel');
	Route::post('admin/generar_token', 'AdminClientesController@generarToken')->name('/admin/generar_token');
	Route::post('admin/clientes/restaurar', 'AdminClientesController@restaurarCliente')->name('admin/clientes/restaurar');
	Route::get('admin/clientes/{id}/get_agendas', 'AdminClientesController@getAgendas')->name('/admin/get/agendas');

	// GRUPOS
	Route::resource('admin/grupos', 'AdminGruposController', [
			'names' => [
					'index' => '/admin/grupos'
			]
	]);

	Route::delete('admin/delete_token', 'AdminClientesController@deleteToken')->name('delete_token');

	Route::resource('admin/medicamentos', 'AdminMedicamentosController', [
			'names' => [
					'index' => '/admin/medicamentos'
			]
	]);
	Route::get('admin/movimiento_medicamentos', 'AdminMovimientoMedicamentosController@index')->name('/admin/movimiento_medicamentos');
	Route::post('admin/movimiento/medicamentos', 'AdminMovimientoMedicamentosController@busqueda');

	Route::delete('admin/medicamentos/destroy/{id}','AdminMedicamentosController@destroy');
	Route::post('admin/medicamentos/busqueda','AdminMedicamentosController@busqueda');


	Route::get('admin/cuenta', 'AdminCuentaController@index')->name('/admin/cuenta');
	Route::post('admin/cuenta', 'AdminCuentaController@store')->name('/admin/cuenta');
	Route::post('admin/cambiar_pass', 'AdminCuentaController@cambiar_pass')->name('/admin/cambiar_pass');

	Route::get('admin/migrar', 'AdminMigracionesController@migrar')->name('/admin/migrar');
	Route::get('admin/migrar_clientes', 'AdminMigracionesController@migrarClientes')->name('migrar_clientes');
	Route::get('admin/migrar_users_empleados', 'AdminMigracionesController@migrarUsersEmpleados')->name('migrar_users_empleados');
	Route::get('admin/migrar_nominas', 'AdminMigracionesController@migrarNominas')->name('migrar_nominas');
	Route::get('admin/migrar_fichadas', 'AdminMigracionesController@migrarFichadas')->name('migrar_fichadas');
	Route::get('admin/migrar_users_clientes', 'AdminMigracionesController@migrarUsersClientes')->name('migrar_users_clientes');

	// Route::get('admin/reportes_fichadas', 'AdminReporteController@reportes_fichadas')->name('reportes_fichadas');
	// Route::get('admin/reportes/fichadas', 'AdminReporteController@fichadas')->name('reportes.fichadas');
	// Route::post('admin/reportes/filtrar_fichadas', 'AdminReporteController@filtrarFichadas')->name('reportes.filtrar_fichadas');


	/// REPORTES

	// Fichadas
	Route::delete('admin/reportes/fichadas/destroy/{id}','AdminFichadasNuevasController@destroy');
	Route::get('admin/reportes/fichada_nueva/{id}','AdminReporteController@find_fichada');
	Route::get('admin/reportes_fichadas_nuevas/exportar','AdminReporteController@exportar_fichadas');
	Route::get('admin/reportes_fichadas_nuevas', 'AdminReporteController@reportes_fichadas_nuevas')->name('/admin/reportes_fichadas_nuevas');

	Route::get('admin/reportes/fichadas_nuevas', 'AdminReporteController@fichadas_nuevas')->name('reportes.fichadas_nuevas');
	Route::post('admin/reportes/filtrar_fichadas_nuevas', 'AdminReporteController@filtrarFichadasNuevas')->name('reportes.filtrar_fichadas_nuevas');

	// Ausentismos
	Route::get('admin/reportes_ausentismos', 'AdminReporteController@reportes_ausentismos')->name('reportes_ausentismos');
	Route::get('admin/reportes/ausentismos', 'AdminReporteController@ausentismos')->name('reportes.ausentismos');
	Route::post('admin/reportes/filtrar_ausentismos', 'AdminReporteController@filtrarAusentismos')->name('reportes.filtrar_ausentismos');
	Route::post('admin/reportes/fichadas_ajax', 'AdminReporteController@fichadas_ajax');
	Route::post('admin/reportes/ausentismos_ajax', 'AdminReporteController@ausentismos_ajax');
	Route::post('admin/reportes/cambiar_fichada', 'AdminReporteController@cambiar_fichada');


	// Certificaciones
	Route::get('admin/reportes_certificaciones', 'AdminReporteController@reportes_certificaciones')->name('reportes_certificaciones');
	Route::post('admin/reportes/certificaciones', 'AdminReporteController@certificaciones')->name('reportes.certificaciones');
	Route::post('admin/reportes/filtrar_certificaciones', 'AdminReporteController@filtrarCertificaciones')->name('reportes.filtrar_certificaciones');

	// Documentacion
	Route::get('admin/reportes/descargar_documentacion/{id}', 'AdminReporteController@descargar_documentacion')->name('reportes.descargar_documentacion');
	Route::get('admin/documentacion_ausentismo/descargar/{id}', 'AdminReporteController@descargar_archivo')->name('documentacion_ausentismo.descargar');

	// Consultas
	Route::get('admin/reportes_consultas', 'AdminReporteController@reportes_consultas')->name('reportes_consultas');
	Route::post('admin/reportes/consultas_medicas', 'AdminReporteController@consultas_medicas');
	Route::post('admin/reportes/consultas_enfermeria', 'AdminReporteController@consultas_enfermeria');
	Route::post('admin/reportes/consultas_nutricionales', 'AdminReporteController@consultas_nutricionales');

	//Route::post('admin/reportes/filtrar_consultas_medicas', 'AdminReporteController@filtrarConsultasMedicas')->name('reportes.filtrar_consultas_medicas');
	//Route::post('admin/reportes/filtrar_consultas_enfermeria', 'AdminReporteController@filtrarConsultasEnfermeria')->name('reportes.filtrar_consultas_enfermeria');

	// Comunicaciones
	Route::get('admin/reportes_comunicaciones', 'AdminReporteController@reportes_comunicaciones')->name('reportes_comunicaciones');
	Route::post('admin/reportes/comunicaciones', 'AdminReporteController@comunicaciones')->name('reportes.comunicaciones');

	// Preocupacionales
	Route::get('admin/reportes_preocupacionales', 'AdminReporteController@reportes_preocupacionales')->name('reportes_preocupacionales');
	Route::post('admin/reportes/preocupacionales/busqueda','AdminReporteController@preocupacionales');
	Route::get('admin/preocupacionales/archivo/{id}','AdminReporteController@descargar_archivo_preocupacionales');

	// Tareas Adecuadas
	Route::get('admin/reportes_tareas_adecuadas', 'AdminReporteController@reportes_tareas_adecuadas')->name('reportes_tareas_adecuadas');
	Route::post('admin/reportes/tareas-adecuadas/busqueda','AdminReporteController@tareas_adecuadas');
	Route::get('admin/tareas_livianas/archivo/{id}','AdminReporteController@descargar_archivo_tarea_liviana');

	// Actividad Usuarios
	Route::get('admin/reportes/actividad_usuarios', 'AdminReporteController@actividad_usuarios')->name('/admin/reportes/actividad_usuarios');
	Route::get('admin/reportes/actividad_usuarios/exportar', 'AdminReporteController@exportar_actividad_usuarios')->name('/admin/reportes/actividad_usuarios/exportar');
	Route::post('admin/reportes/search_actividad_usuarios', 'AdminReporteController@search_actividad_usuarios')->name('/admin/reportes/search_actividad_usuarios');



	Route::post('admin/reportes/filtrar_comunicaciones', 'AdminReporteController@filtrarComunicaciones')->name('reportes.filtrar_comunicaciones');


	Route::get('admin/reportes/ediciones_fichadas', 'AdminEdicionFichadaController@index')->name('/admin/reportes/ediciones_fichadas');


	Route::get('admin/errores', 'ErrorController@index')->name('/admin/errores');
	Route::post('admin/errores', 'ErrorController@limpiar')->name('admin.limpiar_errores');


	Route::resource('admin/configuraciones', 'AdminConfiguracionController', [
		'names' => [
				'index' => '/admin/configuraciones'
		]
	]);


	Route::get('admin/agendas', 'AdminAgendaController@index')->name('/admin/agendas');
	Route::get('admin/agendas/bloqueos', 'AdminAgendaController@getBloqueos')->name('/admin/agendas/bloqueos');
	Route::post('admin/agendas/bloqueos', 'AdminAgendaController@storeBloqueos')->name('admin.agendas.bloqueos.store');
	Route::delete('admin/agendas/bloqueos/{id}', 'AdminAgendaController@destroyBloqueos')->name('/admin/agendas/bloqueos.destroy');

	Route::resource('admin/agenda_motivos', 'AdminAgendaMotivoController', [
		'names' => [
			'index' => '/admin/agenda_motivos',
			'create' => 'admin.agenda_motivos.create',
			'store' => 'admin.agenda_motivos.store',
			'show' => 'admin.agenda_motivos.show',
			'edit' => 'admin.agenda_motivos.edit',
			'update' => 'admin.agenda_motivos.update',
			'destroy' => 'admin.agenda_motivos.destroy'
		]
	]);

	Route::get('admin/agenda/events', 'AdminAgendaController@getAgendaEvents');
	// Route::get('admin/agenda_motivos', 'EndpointsController@getMotivosAgenda');
	
	// Recetas
Route::get('admin/recetas', 'AdminRecetasController@index')
    ->name('admin.recetas');

Route::get('admin/recetas/{id}', 'AdminRecetasController@show')
    ->where('id', '\d+')
    ->name('admin.recetas.show');


});