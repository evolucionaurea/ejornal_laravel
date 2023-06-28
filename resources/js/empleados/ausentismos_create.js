$(()=>{

	$('[name="fecha_inicio"]').datepicker({
		onSelect:(date,obj)=>{
			const minDate = new Date(obj.selectedYear,obj.selectedMonth,obj.selectedDay)
			$('[name="fecha_final"],[name="fecha_regreso_trabajar"]').datepicker({
				minDate:minDate
			})

		}
	})
	$('[name="fecha_final"]').on('change',event=>{
		const value = $(event.currentTarget).val()
		$('[name="fecha_regreso_trabajar"]').val(value)
	})


	$('.select_2').select2()

	$('.select_2').trigger('change')

})