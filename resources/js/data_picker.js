$.datepicker.regional['es'] = {closeText:'Cerrar',prevText:'<Ant',nextText:'Sig>',currentText:'Hoy',monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],monthNamesShort:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],dayNames:['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],dayNamesShort:['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],dayNamesMin:['Do','Lu','Ma','Mi','Ju','Vi','Sá'],weekHeader: 'Sm',dateFormat:'dd/mm/yy',firstDay:1,isRTL:false,showMonthAfterYear:false,yearSuffix:''};
$.datepicker.setDefaults($.datepicker.regional['es']);

$(document).ready(function($){
	$(`
		#data_picker_gral,
		#data_picker_edit_doc_ausentismo,
		#reporte_fichadas_desde,
		#reporte_fichadas_hasta,
		#reporte_ausentismos_desde,
		#reporte_ausentismos_hasta,
		#reporte_certificaciones_desde,
		#reporte_certificaciones_hasta,
		#reporte_consultas_medicas_desde,
		#reporte_consultas_medicas_hasta,
		#reporte_consultas_enfermerias_desde,
		#reporte_consultas_enfermerias_hasta,
		#reporte_comunicaciones_desde,
		#reporte_comunicaciones_hasta,
		#fecha_vencimiento_matricula
	`).datepicker()

	/*#ausentismo_fecha_inicio,
		#ausentismo_fecha_final,
		#ausentismo_fecha_regreso,*/

	const today = new Date()
	let mes = today.getMonth()+1
	mes = mes<10 ? `0${mes}` : mes
	$('#data_picker_edit_doc_ausentismo_ult_modif').val(`${today.getDate()}/${mes}/${today.getFullYear()}`)

})
