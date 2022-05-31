require('./bootstrap');

require('./functions');
require('../slick/slick.min.js');
require('./slick.js');
require('./data_picker_code.js');
require('./data_tables.js');
require('./data_picker.js');
require('./swal_options.js');

require('./sidebar');
require('./footer.js');
require('./ajax.js');
require('./users.js');
require('./nav_sup.js');
require('./medicamentos.js');
require('./stock_medicamentos.js');
require('./documentacion_ausentismo.js');


//EMPLEADOS
if(route=='/empleados/nominas' || route=='empleados.listado') require('./empleados/nominas')
if(route=='/empleados/ausentismos') require('./empleados/ausentismos')
if(route=='/empleados/comunicaciones') require('./empleados/comunicaciones')
if(route=='/empleados/certificados') require('./empleados/certificados')

if(route=='empleados.consultas.medicas') require('./empleados/consultas.medicas')
if(route=='empleados.consultas.enfermeria') require('./empleados/consultas.enfermeria')

if(route=='empleados.covid.testeos') require('./empleados/covid.testeos')
if(route=='empleados.covid.vacunas') require('./empleados/covid.vacunas')

if(route=='/empleados/medicamentos') require('./empleados/medicamentos')
if(route=='/empleados/medicamentos_movimientos') require('./empleados/medicamentos_movimientos')
if(route=='/empleados/preocupacionales') require('./empleados/preocupacionales')

//CLIENTES
if(route=='/clientes/resumen') require('./clientes/resumen')
if(route=='/clientes/nominas') require('./clientes/nominas')
if(route=='/clientes/ausentismos') require('./clientes/ausentismos')

console.log(route)