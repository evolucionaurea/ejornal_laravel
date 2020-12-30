<?php

// Rutas publicas

Route::get('/', 'webOficialController@index')->name('web_oficial');
Route::post('login', 'UserController@login');

// Rutas publicas





// Rutas protegidas por autenticacion
Route::group(['middleware' => ['autenticacion']], function () {

// Admin

Route::get('admin/resumen', 'AdminResumenController@index')->name('/admin/resumen')->middleware('autenticacion_admin');

// Admin



// Empleados

Route::resource('empleados/resumen', 'EmpleadosResumenController', [
    'names' => [
        'index' => '/empleados/resumen'
    ]
])->middleware('autenticacion_empleados');



Route::resource('empleados/usuarios', 'EmpleadosUsuariosController', [
    'names' => [
        'index' => '/empleados/usuarios'
    ]
])->middleware('autenticacion_empleados');



Route::resource('empleados/cuenta', 'EmpleadosCuentaController', [
    'names' => [
        'index' => '/empleados/cuenta'
    ]
])->middleware('autenticacion_empleados');



Route::resource('empleados/liquidacion', 'EmpleadosLiquidacionController', [
    'names' => [
        'index' => '/empleados/liquidacion'
    ]
])->middleware('autenticacion_empleados');


// Empleados

Route::resource('clientes/resumen', 'ClientesResumenController', [
    'names' => [
        'index' => '/clientes/resumen'
    ]
])->middleware('autenticacion_clientes');

// Clientes


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
