<?php


Route::group(['middleware' => 'autenticacion_admin'], function () {

	Route::get('admin/resumen', 'AdminResumenController@index')->name('/admin/resumen');
	Route::get('admin/get_medicamentos', 'AdminResumenController@getMedicamentos');


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
					'index' => '/admin/clientes'
			]
	]);
	Route::post('admin/clientes/cargar_excel', 'AdminClientesController@cargar_excel')->name('/admin/clientes/cargar_excel');
	Route::post('admin/generar_token', 'AdminClientesController@generarToken')->name('/admin/generar_token');

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

	Route::get('admin/reportes_fichadas_nuevas', 'AdminReporteController@reportes_fichadas_nuevas')->name('reportes_fichadas_nuevas');
	Route::get('admin/reportes/fichadas_nuevas', 'AdminReporteController@fichadas_nuevas')->name('reportes.fichadas_nuevas');
	Route::post('admin/reportes/filtrar_fichadas_nuevas', 'AdminReporteController@filtrarFichadasNuevas')->name('reportes.filtrar_fichadas_nuevas');
	Route::get('admin/reportes_ausentismos', 'AdminReporteController@reportes_ausentismos')->name('reportes_ausentismos');
	Route::get('admin/reportes/ausentismos', 'AdminReporteController@ausentismos')->name('reportes.ausentismos');
	Route::post('admin/reportes/filtrar_ausentismos', 'AdminReporteController@filtrarAusentismos')->name('reportes.filtrar_ausentismos');
	Route::get('admin/reportes_certificaciones', 'AdminReporteController@reportes_certificaciones')->name('reportes_certificaciones');
	Route::get('admin/reportes/certificaciones', 'AdminReporteController@certificaciones')->name('reportes.certificaciones');
	Route::post('admin/reportes/filtrar_certificaciones', 'AdminReporteController@filtrarCertificaciones')->name('reportes.filtrar_certificaciones');
	Route::get('admin/reportes/descargar_documentacion/{id}', 'AdminReporteController@descargar_documentacion')->name('reportes.descargar_documentacion');
	Route::get('admin/documentacion_ausentismo/descargar/{id}', 'AdminReporteController@descargar_archivo')->name('documentacion_ausentismo.descargar');
	Route::get('admin/reportes_consultas', 'AdminReporteController@reportes_consultas')->name('reportes_consultas');
	Route::get('admin/reportes/consultas_medicas', 'AdminReporteController@consultas_medicas')->name('reportes.consultas_medicas');
	Route::post('admin/reportes/filtrar_consultas_medicas', 'AdminReporteController@filtrarConsultasMedicas')->name('reportes.filtrar_consultas_medicas');
	Route::get('admin/reportes/consultas_enfermeria', 'AdminReporteController@consultas_enfermeria')->name('reportes.consultas_enfermeria');
	Route::post('admin/reportes/filtrar_consultas_enfermeria', 'AdminReporteController@filtrarConsultasEnfermeria')->name('reportes.filtrar_consultas_enfermeria');
	Route::get('admin/reportes_comunicaciones', 'AdminReporteController@reportes_comunicaciones')->name('reportes_comunicaciones');
	Route::get('admin/reportes/comunicaciones', 'AdminReporteController@comunicaciones')->name('reportes.comunicaciones');
	Route::post('admin/reportes/filtrar_comunicaciones', 'AdminReporteController@filtrarComunicaciones')->name('reportes.filtrar_comunicaciones');

});