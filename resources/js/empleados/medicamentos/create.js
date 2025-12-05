$(() => {

	$('[name="medicamento"]').change(async select => {
		const medicamentoid = $(select.currentTarget).val()
		const response = await axios.get(`/empleados/medicamentos/stock_actual/${medicamentoid}`)
		$('[data-content="stock-actual"]').text(`Stock actual: ${response.data ? response.data.stock : 'sin stock'}`)
	})

	$('[name="fecha_ingreso"]').datepicker()
	$('.select_2').select2()


});
