$(() => {

	$('.select_2').select2();

	const tipoSelect = document.getElementById('tipo-consulta');
	const camposInicial = document.querySelectorAll('.campos-inicial');
	const camposSeguimiento = document.querySelectorAll('.campos-seguimiento');
	const decimalInputs = document.querySelectorAll('input[type="number"]');

	function toggleCampos() {
		const tipo = tipoSelect.value;
		if (tipo === 'inicial') {
			camposInicial.forEach(campo => campo.style.display = 'block');
			camposSeguimiento.forEach(campo => campo.style.display = 'none');
		} else if (tipo === 'seguimiento') {
			camposInicial.forEach(campo => campo.style.display = 'none');
			camposSeguimiento.forEach(campo => campo.style.display = 'block');
		}
	}

	// Validación para números decimales
	decimalInputs.forEach(input => {
		input.addEventListener('input', (e) => {
			const value = e.target.value;
			const regex = /^\d{0,3}(\.\d{0,2})?$/;

			if (!regex.test(value)) {
				alert('Por favor ingrese un valor válido (máximo 3 dígitos enteros y 2 decimales).');
				e.target.value = ''; // Limpiar el campo si el valor es inválido
			}
		});
	});

	tipoSelect.addEventListener('change', toggleCampos);
	toggleCampos(); // Ejecutar al cargar la página


	$('[data-toggle="has-datepicker"]').datepicker();


	$("input[name='peso'],input[name='altura']").on('keyup change',function() {
	
		const peso = $('input[name="peso"]').val();
		const altura = $('input[name="altura"]').val();
		$("input[name='imc']").val(window.calculate_imc(peso,altura));
		$("input[name='imc_disabled']").val(window.calculate_imc(peso,altura))

	})



});
