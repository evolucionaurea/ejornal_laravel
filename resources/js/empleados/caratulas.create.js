
$(() => {

	$("input[name='peso']").on('change', function () {
		let peso = parseFloat($(this).val());
		let altura = parseFloat($("input[name='altura']").val());
		if (!peso || !altura) return;

		$("input[name='imc']").val(calculate_imc(peso, altura));
		$("input[name='imc_disabled']").val(calculate_imc(peso, altura));

	});


	$("input[name='altura']").on('change', function () {
		let altura = parseFloat($(this).val());
		let peso = parseFloat($("input[name='peso']").val());
		if (!peso || !altura) return;
		$("input[name='imc']").val(calculate_imc(peso, altura));
		$("input[name='imc_disabled']").val(calculate_imc(peso, altura));

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

	if ($('[name="id_nomina"]').attr('type') != 'hidden') {
		$('[name="id_nomina"]').select2();
	}

	$('[name="id_patologia[]"]').select2({
		placeholder: "Seleccione una o más patologías",
		allowClear: true
	});



})