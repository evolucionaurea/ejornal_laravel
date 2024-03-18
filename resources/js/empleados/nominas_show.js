$(()=>{

	datatable_options.ordering = false
	$('[data-table="resumen_historial"]').DataTable(datatable_options)
	$('[data-table="ausentismos"]').DataTable(datatable_options)
	$('[data-table="preocupacionales"]').DataTable(datatable_options)
	$('[data-table="enfermeria"]').DataTable(datatable_options)
	$('[data-table="medicas"]').DataTable(datatable_options)

})