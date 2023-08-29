import ResumenCliente from '../classes/ResumenCliente.js'
$(()=>{

	//GruposResumenController > index_cliente_ajax
	new ResumenCliente({
		path:'index_cliente_ajax'
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