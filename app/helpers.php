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