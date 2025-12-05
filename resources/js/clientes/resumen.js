import DataTable from 'datatables.net-dt';
import ResumenCliente from '../classes/ResumenCliente.js'
$(() => {

	//ClientesResumenController > clientes/index_ajax
	new ResumenCliente({
		path: 'index_ajax'
	})


	/// tablas ausentismos nómina
	new DataTable('[data-table="top_10_faltas"]', {
		order: [[2, 'desc']],
		dom: 't'
	})
	new DataTable('[data-table="top_10_solicitudes_faltas"]', {
		order: [[1, 'desc']],
		dom: 't'
	})





})