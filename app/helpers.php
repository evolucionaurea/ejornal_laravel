<?php

function setActive($route){
  return request()->routeIs($route) ? 'activo' : '';
}

function setActiveSub($route){
  return request()->routeIs($route) ? 'activo_sub' : '';
}

 ?>
