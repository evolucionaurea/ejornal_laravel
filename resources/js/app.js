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


//ADMIN
if(route=='/admin/clientes') require('./admin/admin_tablas')
if(route=='/admin/medicamentos') require('./admin/medicamentos')
if(route=='/admin/movimiento_medicamentos') require('./admin/admin_tablas')
if(route=='/admin/resumen') require('./admin/resumen')

if(route=='/admin/users') require('./admin/users')
if(route=='users.create') require('./admin/users/create.edit')
if(route=='users.edit') require('./admin/users/create.edit')

if(route=='grupos.create') require('./admin/grupos/create.edit')
if(route=='grupos.edit') require('./admin/grupos/create.edit')

/*const url = location.href;
const admin = 'admin/users';
const cliente = 'admin/clientes';
if (url.includes(admin) || url.includes(cliente)) {
  require('./admin/admin_tablas');
}*/

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

//GRUPOS
if(route=='/grupos/nominas') require('./grupos/nominas')
if(route=='/grupos/ausentismos') require('./grupos/ausentismos')


console.log(route)
