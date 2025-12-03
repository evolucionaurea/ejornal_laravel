import DataTable from 'datatables.net-dt';

$(() => {

	$(".restaurar_cliente").on("click", btn => {
		$("input[name='id_cliente']").val($(btn.currentTarget).attr('data-info'))
	})


	new DataTable('[data-table="clientes"]', window.datatable_options)

})