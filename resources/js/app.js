require('toastr')
require('./bootstrap');
require('./functions');
require('../slick/slick.min.js');
require('./slick.js');
require('./data_picker_code.js');
require('./data_tables.js');
require('./data_picker.js');
require('./swal_options.js');

require('./sidebar');
//require('./footer.js');
require('./ajax.js');
require('./users.js');
require('./nav_sup.js');
//require('./medicamentos.js');
///require('./stock_medicamentos.js');



//ADMIN
if(route=='/admin/clientes') require('./admin/clientes')
if(route=='admin.clientes.show') require('./admin/admin_tablas')
if(route=='/admin/medicamentos') require('./admin/medicamentos')

if(route=='/admin/movimiento_medicamentos') require('./admin/medicamentos/movimientos')

if(route=='/admin/resumen') require('./admin/resumen')
if(route=='/admin/reportes_fichadas_nuevas') require('./admin/reportes/fichadas')

if(route=='/admin/users') require('./admin/users')
if(route=='users.create') require('./admin/users/create.edit')
if(route=='users.edit') require('./admin/users/create.edit')

if(route=='/admin/grupos') require('./admin/grupos')
if(route=='grupos.create') require('./admin/grupos/create.edit')
if(route=='grupos.edit') require('./admin/grupos/create.edit')
if(route=='reportes_ausentismos') require('./admin/reportes/ausentismos')
if(route=='reportes_certificaciones') require('./admin/reportes/certificaciones')
if(route=='reportes_comunicaciones') require('./admin/reportes/comunicaciones')
if(route=='reportes_consultas') require('./admin/reportes/consultas')
if(route=='/admin/reportes/actividad_usuarios') require('./admin/reportes/actividad_usuarios')
if(route=='reportes_preocupacionales') require('./admin/reportes/preocupacionales')


//EMPLEADOS
if(route=='/empleados/resumen') require('./empleados/resumen')

if(route=='documentaciones.show') require('./empleados/documentacion_ausentismo.js');
if(route=='documentaciones_livianas.show') require('./documentacion_tarea_liviana.js');

if(route=='/empleados/nominas' || route=='empleados.listado') require('./empleados/nominas')
if(route=='/empleados/nominas/historial') require('./empleados/nominas_historial')
if(route=='/empleados/nominas/movimientos') require('./empleados/nominas_movimientos')
if(route=='nominas.edit') require('./empleados/nominas_edit')
if(route=='nominas.create') require('./empleados/nominas_create')
if(route=='nominas.show') require('./empleados/nominas_show')

if(route=='/empleados/ausentismos') require('./empleados/ausentismos')
///if(route=='/empleados/ausentismos') require('./empleados/ausentismos_extension_licencia')
if(route=='ausentismos.create') require('./empleados/ausentismos_create')
if(route=='ausentismos.edit') require('./empleados/ausentismos_create')
if(route=='ausentismos.show' || route=='ausentismo') require('./empleados/ausentismo')

//if(route=='ausentismo') require('./empleados/ausentismo')

if(route=='/empleados/tareas_livianas') require('./empleados/tareas_livianas')
if(route=='/empleados/tareas_livianas') require('./empleados/tareas_livianas_extension_licencia')
if(route=='tareas_livianas.create') require('./empleados/tareas_livianas.create')
if(route=='tareas_livianas.edit') require('./empleados/tareas_livianas.create')

if(route=='/empleados/comunicaciones') require('./empleados/comunicaciones')
if(route=='/empleados/certificados') require('./empleados/certificados')
if(route=='/empleados/comunicaciones_livianas') require('./empleados/comunicaciones_livianas')
if(route=='/empleados/certificados_livianos') require('./empleados/certificados_livianos')

if(route=='empleados.consultas.todas') require('./empleados/consultas.todas')
if(route=='empleados.consultas.medicas') require('./empleados/consultas.medicas')
if(route=='empleados.consultas.enfermeria') require('./empleados/consultas.enfermeria')
if(route=='empleados.consultas.nutricionales') require('./empleados/nutricional')
if(route=='empleados.consultas.nutricionales.create') require('./empleados/nutricional_create')

if(route=='empleados.nominas.caratulas.create') require('./empleados/nominas_caratulas')
if(route=='empleados.caratulas') require('./empleados/caratulas')

if(route=='empleados.covid.testeos') require('./empleados/covid.testeos')
if(route=='empleados.covid.vacunas') require('./empleados/covid.vacunas')

if(route=='/empleados/medicamentos') require('./empleados/medicamentos')
if(route=='medicamentos.create') require('./empleados/medicamentos.create')
if(route=='/empleados/medicamentos_movimientos') require('./empleados/medicamentos_movimientos')
if(route=='/empleados/preocupacionales') require('./empleados/preocupacionales')
if(route=='preocupacionales.create') require('./empleados/preocupacionales.create')
if(route=='preocupacionales.edit') require('./empleados/preocupacionales.create')

if(route=='medicas.create') require('./empleados/consultas.medicas.create')
if(route=='enfermeria.create') require('./empleados/consultas.enfermeria.create')



//CLIENTES
if(route=='/clientes/resumen') require('./clientes/resumen')
if(route=='/clientes/nominas') require('./clientes/nominas')
if(route=='/clientes/nominas/historial') require('./clientes/nominas_historial')
if(route=='/clientes/nominas/movimientos') require('./clientes/nominas_movimientos')
if(route=='/clientes/ausentismos') require('./clientes/ausentismos')


//GRUPOS
if(route=='/grupos/resumen') require('./grupos/resumen')
if(route=='/grupos/resumen_cliente') require('./grupos/resumen_cliente')
if(route=='/grupos/nominas') require('./grupos/nominas')
if(route=='/grupos/ausentismos') require('./grupos/ausentismos')
if(route=='/grupos/nominas_historial') require('./grupos/nominas_historial')
if(route=='/grupos/nominas_movimientos') require('./grupos/nominas_movimientos')


console.log(route)

/*fetch('/clientes/resumen')
	.then(response=>{
		if(response.status===503){
			setTimeout(()=>{
				window.location.reload()
			},10000)
		}

	})*/