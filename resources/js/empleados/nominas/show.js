import DataTable from 'datatables.net-dt';

$(() => {

	datatable_options.ordering = false
	new DataTable('[data-table="resumen_historial"]', datatable_options)
	new DataTable('[data-table="ausentismos"]', datatable_options)
	new DataTable('[data-table="preocupacionales"]', datatable_options)
	new DataTable('[data-table="enfermeria"]', datatable_options)
	new DataTable('[data-table="medicas"]', datatable_options)

})