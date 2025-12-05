$(()=>{

	$('[name="fecha_inicio"]').datepicker({
		onSelect:(date,obj)=>{
			const minDate = new Date(obj.selectedYear,obj.selectedMonth,obj.selectedDay)
			$('[name="fecha_final"],[name="fecha_regreso_trabajar"]').datepicker('destroy')
			$('[name="fecha_final"],[name="fecha_regreso_trabajar"]').datepicker({
				minDate:minDate
			})

		}
	})
	$('[name="fecha_final"]').on('change',event=>{
		const value = $(event.currentTarget).val()
		$('[name="fecha_regreso_trabajar"]').val(value)
	})


	const inst = $.datepicker._getInst($('[name="fecha_inicio"]')[0]);
	$.datepicker._get(inst, 'onSelect').apply(inst.input[0], [$('[name="fecha_inicio"]').datepicker('getDate'), inst]);



	$('.select_2').select2()
	$('.select_2').trigger('change')



})