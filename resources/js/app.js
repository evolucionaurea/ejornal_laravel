window.toastr = require('toastr');
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
if (route == '/admin/clientes') require('./admin/clientes')
if (route == 'admin.clientes.show') require('./admin/admin_tablas')
if (route == 'admin.clientes.show') require('./admin/clientes.agendas.js')
if (route == '/admin/medicamentos') require('./admin/medicamentos')

if (route == '/admin/movimiento_medicamentos') require('./admin/medicamentos_movimientos.js')

if (route == '/admin/resumen') require('./admin/resumen')
if (route == '/admin/reportes_fichadas_nuevas') require('./admin/reportes_fichadas_nuevas.js')

if (route == '/admin/users') require('./admin/users')
if (route == 'users.create') require('./admin/users.create.js')
if (route == 'users.edit') require('./admin/users.create.js')

if (route == '/admin/grupos') require('./admin/grupos')
if (route == 'grupos.create') require('./admin/grupos.create.js')
if (route == 'grupos.edit') require('./admin/grupos.create.js')
if (route == 'reportes_ausentismos') require('./admin/reportes_ausentismos.js')
if (route == 'reportes_certificaciones') require('./admin/reportes_certificaciones.js')
if (route == 'reportes_comunicaciones') require('./admin/reportes_comunicaciones.js')
if (route == 'reportes_consultas') require('./admin/reportes_consultas.js')
if (route == '/admin/reportes/actividad_usuarios') require('./admin/reportes_actividad_usuarios.js')
if (route == 'reportes_preocupacionales') require('./admin/reportes_preocupacionales.js')
if (route == 'reportes_tareas_adecuadas') require('./admin/reportes_tareas_adecuadas.js')

if (route == '/admin/agendas') require('./admin/agendas')

//EMPLEADOS
if (route == '/empleados/resumen') require('./empleados/resumen')

if (route == 'documentaciones.show') require('./empleados/documentaciones.show.js');
if (route == 'documentaciones_livianas.show') require('./documentacion_tarea_liviana.js');

if (route == '/empleados/nominas' || route == 'empleados.listado') require('./empleados/nominas')
if (route == '/empleados/nominas/historial') require('./empleados/nominas_historial')
if (route == '/empleados/nominas/movimientos') require('./empleados/nominas_movimientos')
if (route == 'nominas.edit') require('./empleados/nominas.edit.js')
if (route == 'nominas.create') require('./empleados/nominas_create')
if (route == 'nominas.show') require('./empleados/nominas_show')

if (route == '/empleados/ausentismos') require('./empleados/ausentismos')
///if(route=='/empleados/ausentismos') require('./empleados/ausentismos_extension_licencia')
if (route == 'ausentismos.create') require('./empleados/ausentismos_create')
if (route == 'ausentismos.edit') require('./empleados/ausentismos.edit')
if (route == 'ausentismos.show' || route == 'ausentismo') require('./empleados/ausentismos.show.js')

//if(route=='ausentismo') require('./empleados/ausentismo')

if (route == '/empleados/tareas_livianas') require('./empleados/tareas_livianas')
//if (route == '/empleados/tareas_livianas') require('./empleados/tareas_livianas_extension_licencia')
if (route == 'tareas_livianas.create') require('./empleados/tareas_livianas.create')
if (route == 'tareas_livianas.edit') require('./empleados/tareas_livianas.create')

if (route == '/empleados/comunicaciones') require('./empleados/comunicaciones')
if (route == '/empleados/certificados') require('./empleados/certificados')
if (route == '/empleados/comunicaciones_livianas') require('./empleados/comunicaciones_livianas')
if (route == '/empleados/certificados_livianos') require('./empleados/certificados_livianos')

if (route == 'empleados.consultas.todas') require('./empleados/consultas.todas')
if (route == 'empleados.consultas.medicas') require('./empleados/consultas.medicas')
if (route == 'empleados.consultas.enfermeria') require('./empleados/consultas.enfermeria')
if (route == 'empleados.consultas.nutricionales') require('./empleados/consultas.nutricionales')
if (route == 'empleados.consultas.nutricionales.create') {
	require('./empleados/consultas.nutricionales.create.js')
	//require('./empleados/caratula_trabajador.js')
}

if (route == 'empleados.caratulas.create') require('./empleados/caratulas.create')
if (route == 'empleados.caratulas.edit') require('./empleados/caratulas.edit')
if (route == 'empleados.caratulas.show') require('./empleados/caratula_trabajador.js')
if (route == 'empleados.caratulas') require('./empleados/caratulas')

if (route == 'empleados.covid.testeos') require('./empleados/covid.testeos')
if (route == 'empleados.covid.vacunas') require('./empleados/covid.vacunas')

if (route == '/empleados/medicamentos') require('./empleados/medicamentos')
if (route == 'medicamentos.create') require('./empleados/medicamentos.create')
if (route == '/empleados/medicamentos_movimientos') require('./empleados/medicamentos_movimientos')
if (route == '/empleados/preocupacionales') require('./empleados/preocupacionales')
if (route == 'preocupacionales.create') require('./empleados/preocupacionales.create')
if (route == 'preocupacionales.edit') require('./empleados/preocupacionales.create')

if (route == 'medicas.create') {
	require('./empleados/consultas.medicas.create')
	//require('./empleados/caratula_trabajador.js')
}
if (route == 'enfermeria.create') {
	require('./empleados/consultas.enfermeria.create')
	//require('./empleados/caratula_trabajador.js')
}

if (route == 'empleados/agenda') require('./empleados/agenda')

if (route == 'empleados.recetas.create') require('./empleados/recetas/create')


//CLIENTES
if (route == '/clientes/resumen') require('./clientes/resumen')
if (route == '/clientes/nominas') require('./clientes/nominas')
if (route == '/clientes/nominas/historial') require('./clientes/nominas_historial')
if (route == '/clientes/nominas/movimientos') require('./clientes/nominas_movimientos')
if (route == '/clientes/ausentismos') require('./clientes/ausentismos')
if (route == 'clientes.preocupacionales') require('./clientes/preocupacionales')


//GRUPOS
if (route == '/grupos/resumen') require('./grupos/resumen')
if (route == '/grupos/resumen_cliente') require('./grupos/resumen_cliente')
if (route == '/grupos/nominas') require('./grupos/nominas')
if (route == '/grupos/ausentismos') require('./grupos/ausentismos')
if (route == '/grupos/nominas_historial') require('./grupos/nominas_historial')
if (route == '/grupos/nominas_movimientos') require('./grupos/nominas_movimientos')


console.log(route)

/*fetch('/clientes/resumen')
	.then(response=>{
		if(response.status===503){
			setTimeout(()=>{
				window.location.reload()
			},10000)
		}

	})*/