<?php

// Rutas publicas

Route::get('/', 'webOficialController@index')->name('web_oficial');
Route::post('login', 'UserController@login');

// Rutas publicas





// Rutas protegidas por autenticacion
Route::group(['middleware' => ['autenticacion']], function () {

// Admin

Route::group(['middleware' => 'autenticacion_admin'], function () {

  Route::get('admin/resumen', 'AdminResumenController@index')->name('/admin/resumen');

  Route::resource('admin/users', 'AdminUserController', [
      'names' => [
          'index' => '/admin/users'
      ]
  ]);
  Route::get('admin/users/download_titulo/{id}', 'AdminUserController@downloadTitulo')->name('users.download_titulo');
  Route::get('admin/users/download_dni/{id}', 'AdminUserController@downloadDni')->name('users.download_dni');
  Route::get('admin/users/download_matricula/{id}', 'AdminUserController@downloadMatricula')->name('users.download_matricula');

  Route::get('admin/users/download_titulo_detras/{id}', 'AdminUserController@downloadTituloDetras')->name('users.download_titulo_detras');
  Route::get('admin/users/download_dni_detras/{id}', 'AdminUserController@downloadDniDetras')->name('users.download_dni_detras');
  Route::get('admin/users/download_matricula_detras/{id}', 'AdminUserController@downloadMatriculaDetras')->name('users.download_matricula_detras');

  // Route::get('admin/users_fichadas', 'AdminFichadasController@fichadas')->name('/admin/users_fichadas');
  Route::get('admin/users_fichadas_nuevas', 'AdminFichadasNuevasController@fichadas')->name('/admin/users_fichadas_nuevas');

  Route::post('admin/users/reset_password', 'AdminUserController@reset_password')->name('/admin/users/reset_password');

  Route::resource('admin/clientes', 'AdminClientesController', [
      'names' => [
          'index' => '/admin/clientes'
      ]
  ]);

  Route::post('admin/clientes/cargar_excel', 'AdminClientesController@cargar_excel')->name('/admin/clientes/cargar_excel');
  Route::post('admin/generar_token', 'AdminClientesController@generarToken')->name('/admin/generar_token');
  Route::delete('admin/delete_token', 'AdminClientesController@deleteToken')->name('delete_token');

  Route::resource('admin/medicamentos', 'AdminMedicamentosController', [
      'names' => [
          'index' => '/admin/medicamentos'
      ]
  ]);

  Route::get('admin/movimiento_medicamentos', 'AdminMovimientoMedicamentosController@index')->name('/admin/movimiento_medicamentos');


  Route::get('admin/cuenta', 'AdminCuentaController@index')->name('/admin/cuenta');
  Route::post('admin/cuenta', 'AdminCuentaController@store')->name('/admin/cuenta');
  Route::post('admin/cambiar_pass', 'AdminCuentaController@cambiar_pass')->name('/admin/cambiar_pass');

  Route::get('admin/migrar', 'AdminMigracionesController@migrar')->name('/admin/migrar');
  Route::get('admin/migrar_clientes', 'AdminMigracionesController@migrarClientes')->name('migrar_clientes');
  Route::get('admin/migrar_users_empleados', 'AdminMigracionesController@migrarUsersEmpleados')->name('migrar_users_empleados');
  Route::get('admin/migrar_nominas', 'AdminMigracionesController@migrarNominas')->name('migrar_nominas');
  Route::get('admin/migrar_fichadas', 'AdminMigracionesController@migrarFichadas')->name('migrar_fichadas');

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

// Admin





// Empleados


Route::group(['middleware' => 'autenticacion_empleados'], function () {

  // Resumen
  Route::get('empleados/resumen', 'EmpleadosResumenController@index')->name('/empleados/resumen');

  // Cuenta
  Route::get('empleados/cuenta', 'EmpleadosCuentaController@index')->name('/empleados/cuenta');
  Route::post('empleados/cuenta', 'EmpleadosCuentaController@store')->name('/empleados/cuenta');
  Route::post('empleados/cambiar_pass', 'EmpleadosCuentaController@cambiar_pass')->name('/empleados/cambiar_pass');
  Route::get('empleados/cuenta/download_titulo/{id}', 'EmpleadosCuentaController@downloadTitulo')->name('cuenta.download_titulo');
  Route::get('empleados/cuenta/download_dni/{id}', 'EmpleadosCuentaController@downloadDni')->name('cuenta.download_dni');
  Route::get('empleados/cuenta/download_matricula/{id}', 'EmpleadosCuentaController@downloadMatricula')->name('cuenta.download_matricula');
  Route::get('empleados/cuenta/download_titulo_detras/{id}', 'EmpleadosCuentaController@downloadTituloDetras')->name('cuenta.download_titulo_detras');
  Route::get('empleados/cuenta/download_dni_detras/{id}', 'EmpleadosCuentaController@downloadDniDetras')->name('cuenta.download_dni_detras');
  Route::get('empleados/cuenta/download_matricula_detras/{id}', 'EmpleadosCuentaController@downloadMatriculaDetras')->name('cuenta.download_matricula_detras');

  // Liquidacion
  Route::get('empleados/liquidacion', 'EmpleadosLiquidacionController@index')->name('/empleados/liquidacion');


  // Nominas (Trabajadores)
  Route::resource('empleados/nominas', 'EmpleadosNominasController', [
    'names' => [
      'index' => '/empleados/nominas'
    ]
  ]);
  Route::post('admin/nominas/cargar_excel', 'EmpleadosNominasController@cargar_excel')->name('/admin/nominas/cargar_excel');


  // Ausentismos
  Route::resource('empleados/ausentismos', 'EmpleadosAusentismosController', [
    'names' => [
      'index' => '/empleados/ausentismos'
    ]
  ]);
  Route::post('empleados/ausentismos/tipo', 'EmpleadosAusentismosController@tipo')->name('/empleados/ausentismos/tipo');
  Route::delete('empleados/ausentismos/tipo_delete/{id_tipo}', 'EmpleadosAusentismosController@tipo_destroy')->name('ausentismos.tipo_delete');
  Route::get('empleados/ausentismos/archivo/{id}', 'EmpleadosAusentismosController@descargar_archivo')->name('ausentismos.archivo');
  Route::get('empleados/getAusentismos', 'EmpleadosAusentismosController@getAusentismos')->name('ausentismos.get_ausentismos');

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



  Route::resource('empleados/comunicaciones', 'EmpleadosComunicacionesController', [
    'names' => [
      'index' => '/empleados/comunicaciones'
    ]
    ]);
    Route::post('empleados/comunicaciones/tipo', 'EmpleadosComunicacionesController@tipo')->name('/empleados/comunicaciones/tipo');
    Route::delete('empleados/comunicaciones/tipo_delete/{id_tipo}', 'EmpleadosComunicacionesController@tipo_destroy')->name('comunicaciones.tipo_delete');
    Route::get('empleados/comunicaciones/getComunicacion/{id}', 'EmpleadosComunicacionesController@getComunicacion')->name('comunicaciones.getComunicacion');
    Route::get('empleados/certificados', 'EmpleadosCertificadosController@listado')->name('/empleados/certificados');

    Route::resource('empleados/preocupacionales', 'EmpleadosPreocupacionalesController', [
      'names' => [
        'index' => '/empleados/preocupacionales'
      ]
      ]);

    Route::get('empleados/preocupacionales/archivo/{id}', 'EmpleadosPreocupacionalesController@descargar_archivo')->name('preocupacionales.archivo');


    Route::resource('empleados/medicamentos', 'EmpleadosStockMedicamentoController', [
      'names' => [
        'index' => '/empleados/medicamentos'
      ]
      ]);
      Route::get('empleados/medicamentos_movimientos', 'EmpleadosStockMedicamentoController@movimientos')->name('/empleados/medicamentos_movimientos');


    Route::resource('empleados/documentaciones', 'EmpleadosAusentismoDocumentacionController', [
      'names' => [
        'index' => 'empleados.documentaciones'
      ]
      ]);
      Route::get('empleados/documentacion_ausentismo/archivo/{id}', 'EmpleadosAusentismoDocumentacionController@descargar_archivo')->name('documentacion_ausentismo.archivo');
      Route::get('empleados/documentaciones/getDocumentacion/{id}', 'EmpleadosAusentismoDocumentacionController@getDocumentacion')->name('documentaciones.getDocumentacion');
      Route::get('empleados/documentaciones/validarMatricula/{matricula}', 'EmpleadosAusentismoDocumentacionController@validarMatricula')->name('documentaciones.validarMatricula');

    Route::resource('empleados/covid/testeos', 'EmpleadosCovidTesteoController', [
      'names' => [
        'index' => 'empleados.covid.testeos'
      ]
      ]);
      Route::post('empleados/covid/testeos/tipo', 'EmpleadosCovidTesteoController@tipo')->name('/empleados/covid/testeos/tipo');
      Route::delete('empleados/covid/testeos/tipo_delete/{id_tipo}', 'EmpleadosCovidTesteoController@tipo_destroy')->name('covid.testeos.tipo_delete');


    Route::resource('empleados/covid/vacunas', 'EmpleadosCovidVacunasController', [
      'names' => [
        'index' => 'empleados.covid.vacunas'
      ]
      ]);
      Route::post('empleados/covid/vacunas/tipo', 'EmpleadosCovidVacunasController@tipo')->name('/empleados/covid/vacunas/tipo');
      Route::delete('empleados/covid/vacunas/tipo_delete/{id_tipo}', 'EmpleadosCovidVacunasController@tipo_destroy')->name('covid.vacunas.tipo_delete');



    Route::resource('empleados/consultas/medicas', 'EmpleadoConsultaMedicaController', [
      'names' => [
        'index' => 'empleados.consultas.medicas'
      ]
      ]);
      Route::post('empleados/consultas/medicas/tipo', 'EmpleadoConsultaMedicaController@tipo')->name('/empleados/consultas/medicas/tipo');
      Route::delete('empleados/consultas/medicas/tipo_delete/{id_tipo}', 'EmpleadoConsultaMedicaController@tipo_destroy')->name('consultas.medicas.tipo_delete');


    Route::resource('empleados/consultas/enfermeria', 'EmpleadoConsultaEnfermeriaController', [
      'names' => [
        'index' => 'empleados.consultas.enfermeria'
      ]
      ]);
      Route::post('empleados/consultas/enfermeria/tipo', 'EmpleadoConsultaEnfermeriaController@tipo')->name('/empleados/consultas/enfermeria/tipo');
      Route::delete('empleados/consultas/enfermeria/tipo_delete/{id_tipo}', 'EmpleadoConsultaEnfermeriaController@tipo_destroy')->name('consultas.enfermeria.tipo_delete');



});

// Empleados






// Clientes

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
Route::get('clientes/ausentismos', 'ClientesAusentismosController@index')->name('/clientes/ausentismos');
Route::get('clientes/getAccidentesAnual', 'ClientesResumenController@getAccidentesAnual')->name('/clientes/get_accidentes_anual');
Route::get('clientes/getAccidentesMesActual', 'ClientesResumenController@getAccidentesMesActual')->name('/clientes/get_accidentes_mes_actual');

Route::get('clientes/api', 'ClientesApiController@index')->name('/clientes/api');

  });

// Clientes





//Logout
Route::get('logout', [
  'as' => 'logout', 'uses' => 'UserController@logout'
]);
//Logout



});
// Rutas protegidas por autenticacion





// Ruta Error 404. Si no machea con ninguna ruta creada va a ésta
Route::fallback(function(){
  return view('error404');
});
