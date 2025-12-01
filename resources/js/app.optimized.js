/*
Cargar global por librería en <head> lo siguientes scripts: 
jquery
jquery-ui
bootstrap | popper
select2
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
datatable
chart
axios

-- chequear su uso y necesidad: 
slick
lodash
*/


// window.Popper = require('popper.js').default
// require('bootstrap')

import toastr from 'toastr'
window.toastr = toastr

import axios from 'axios'
window.axios = axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

import Swal from 'sweetalert2'
window.Swal = Swal


$.datepicker.regional['es'] = { closeText: 'Cerrar', prevText: '<Ant', nextText: 'Sig>', currentText: 'Hoy', monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'], monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'], dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'], dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'], dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'], weekHeader: 'Sm', dateFormat: 'dd/mm/yy', firstDay: 1, isRTL: false, showMonthAfterYear: false, yearSuffix: '' };
$.datepicker.setDefaults($.datepicker.regional['es']);


import './functions'

require('./nav_sup')
require('./sidebar')
require('./datatables_options')
require('./swal_options')

//console.log('app.optimized')
console.log(route)