/*
Cargar global por librería en <head> lo siguientes scripts: 
jquery
jquery-ui
bootstrap | popper
app.optimized
	|- functions
	|- swal_options (ver si combiene cargarlo global)
	|- sidebar
	|- nav_sup

-- reescribir y dejar de usar:
data_tables
data_picker
ajax
users

-- cargar solamente si se van a usar en la pág:
toastr
sweetalert2
select2
datatable
chart
axios

-- chequear su uso y necesidad: 
slick
lodash
*/


require('./functions');
////require('./swal_options.js');
console.log('app.optimized')