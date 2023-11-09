import ResumenCliente from '../classes/ResumenCliente.js'
$(()=>{

	//ClientesResumenController > clientes/index_ajax
	new ResumenCliente({
		path:'index_ajax'
	})


	/// tablas ausentismos nómina
	$('[data-table="top_10_faltas"]').dataTable({
		order:[[2,'desc']],
		dom:'t'
	})
	$('[data-table="top_10_solicitudes_faltas"]').dataTable({
		order:[[1,'desc']],
		dom:'t'
	})





})