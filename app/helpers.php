<?php
function setActive($route){
	return request()->routeIs($route) ? 'activo' : '';
}

function setActiveSub($route){
	return request()->routeIs($route) ? 'activo_sub' : '';
}

function echo_json($obj,$exit=true){
	header("Content-Type: application/json; charset=utf-8", true);
	echo json_encode($obj);
	if($exit) exit;
}

function download_file($ruta){
	$mime = mime_content_type($ruta);
	$open_in_browser = false;

	if(!file_exists($ruta)) return redirect()->back()->withErrors(['El archivo no existe.']);

	if(
		preg_match('/image/',$mime) ||
		preg_match('/pdf/', $mime)
	) $open_in_browser=true;
	if(!$open_in_browser) return response()->download($ruta);

	$file = file_get_contents($ruta);
	return response($file, 200)->header('Content-Type', $mime);
}