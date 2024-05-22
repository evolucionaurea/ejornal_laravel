$(()=>{

	$('[data-toggle="select2"]').select2()

	$('[name="fecha"],[name="fecha_vencimiento"]').datepicker()


	$('[name="tiene_vencimiento"]').change(select=>{
		const value = $(select.currentTarget).val()

		if(value=='1'){
			$('[data-toggle="vencimiento"]').removeClass('d-none')
			$('[name="fecha_vencimiento"]').attr({required:true})
		}else{
			$('[data-toggle="vencimiento"]').addClass('d-none')
			$('[name="fecha_vencimiento"]').attr({required:false})
		}
	})

})