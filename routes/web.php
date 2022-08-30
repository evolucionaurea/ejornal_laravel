<?php

// Rutas publicas
Route::get('/', 'webOficialController@index')->name('web_oficial');
Route::post('login', 'UserController@login');



// Rutas protegidas por autenticacion
Route::group(['middleware' => ['autenticacion']], function () {

	// Admin
	require 'web_admin.php';

	// Empleados
	require 'web_empleados.php';


	// Clientes
	require 'web_clientes.php';


	// Grupos
	require 'web_grupos.php';


	//Logout
	Route::get('logout', [
		'as' => 'logout', 'uses' => 'UserController@logout'
	]);



});




// Ruta Error 404. Si no machea con ninguna ruta creada va a ésta
Route::fallback(function(){
	return view('error404');
});
