function calcularImc(peso, altura) {
	let imc = peso / (Math.pow((altura/100), 2));
	return imc.toFixed(2);
}

$(()=>{

	$(".form-row .form-group input[name='peso']").keyup(function() {
		let peso = parseFloat($(this).val());
		let altura = parseFloat($(".form-row .form-group input[name='altura']").val());
		if(!peso || !altura) return;

		$(".form-row .form-group input[name='imc']").val(calcularImc(peso,altura));
		$(".form-row .form-group input[name='imc_disabled']").val(calcularImc(peso,altura));

	});


	$(".form-row .form-group input[name='altura']").keyup(function() {
		let altura = parseFloat($(this).val());
		let peso = parseFloat($(".form-row .form-group input[name='peso']").val());
		if(!peso || !altura) return;
		$(".form-row .form-group input[name='imc']").val(calcularImc(peso,altura));
		$(".form-row .form-group input[name='imc_disabled']").val(calcularImc(peso,altura));

	});

	$(document).on('click', '.btn-editar-patologia', function () {
		let id = $(this).data('id');
		let nombre = $(this).data('nombre');

		// Setea el nombre
		$('#editarPatologiaNombre').val(nombre);

		// Cambia la acción del form
		let ruta = "{{ url('empleados/consultas/patologias') }}/" + id;
		$('#editarPatologiaForm').attr('action', ruta);
	});

	if( $('[name="id_nomina"]').attr('type')!='hidden' ){
		$('[name="id_nomina"]').select2();
	}

	$('[name="id_patologia[]"]').select2({
		placeholder: "Seleccione una o más patologías",
		allowClear: true
	});
	
	

})