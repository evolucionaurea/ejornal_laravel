$(()=>{

	$('[name="fecha_nacimiento"]').datepicker({
		changeYear:true,
		changeMonth:true,
		yearRange:'-100:+0'
	})

	console.log('edit nomina')
})