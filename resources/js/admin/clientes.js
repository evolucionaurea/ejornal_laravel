$(()=>{

	$(".restaurar_cliente").on( "click", btn=>{
		$("input[name='id_cliente']").val($(btn.currentTarget).attr('data-info'))
	})

	$('[data-table="clientes"]').dataTable(window.datatable_options)

})