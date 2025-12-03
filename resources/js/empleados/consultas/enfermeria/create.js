require('../../caratulas/caratula_trabajador')

//const { isNumber } = require("lodash");

$(() => {

	let imc;
	let medicamentos = '';
	let medicamentos_suministrados = [];

	$("input[name='peso'],input[name='altura']").on('keyup change', function () {

		const peso = $('input[name="peso"]').val();
		const altura = $('input[name="altura"]').val();
		$("input[name='imc']").val(window.calculate_imc(peso, altura));
		$("input[name='imc_disabled']").val(window.calculate_imc(peso, altura))

	})


	$('#cargar_medicacion').click(function () {
		$('#cargar_medicacion_abrir').modal('show');
		medicamentos_suministrados = [];
	});



	$("#aceptar_suministrar_medicamentos").click(function () {

		medicamentos_suministrados = [];
		let sin_stock = []

		$.each($(".modal_medicacion_a_suministrar .btn-toolbar"), (k, v) => {

			if ($(v).find('input').val() == '' || $(v).find('input').val() == '0') return true

			const stock = parseInt($(v).find('[data-content="stock"]').text())
			const suministrados = parseInt($(v).find('input').val())
			const medicamento = $(v).find('[data-content="medicamento"]').text()

			if (suministrados > stock) sin_stock.push(medicamento)

			medicamentos_suministrados.push({
				nombre: medicamento,
				id_medicamento: $(v).find('input').attr('data-medicamentoid'),
				suministrados: suministrados
			})
		})

		if (sin_stock.length > 0) {
			Swal.fire({
				icon: 'error',
				title: `${sin_stock.join(', ')}`,
				html: `no dispone${sin_stock.length > 1 ? 'n' : ''} de suficiente stock para la cantidad a suministrar.`
			})
			return false
		}

		$('#cargar_medicacion_abrir').modal('hide');

		// Remover si el elemento existe
		if ($(".ul_lista_medicamentos").length > 0) {
			$('.ul_lista_medicamentos').remove();
		}
		if ($(".listado_medicaciones_inputs_ocultos input").length > 0) {
			$('.listado_medicaciones_inputs_ocultos input').remove();
		}

		for (i = 0; i < medicamentos_suministrados.length; i++) {
			if (medicamentos_suministrados[i].suministrados.length !== 0 || medicamentos_suministrados[i].suministrados !== '0') {

				$(".listado_medicaciones").append(
					$('<ul>', {
						'class': 'list-group ul_lista_medicamentos small'
					}).append(
						$('<li>', {
							'class': 'list-group-item p-1'
						}).append(
							$('<div>', {
								'text': `${medicamentos_suministrados[i].nombre}:  ${medicamentos_suministrados[i].suministrados}`
							})
						)
					)
				);

				$(".listado_medicaciones_inputs_ocultos").append(
					$('<input>', {
						'type': 'hidden',
						'name': 'medicaciones[]',
						'value': `${medicamentos_suministrados[i].id_medicamento},${medicamentos_suministrados[i].suministrados}`
					})
				);
			}
		}

		$('.listado_medicaciones ul li').css('color', 'grey');

	})


	// Evento de búsqueda en tiempo real
	$('#medicamentoSearch').on('input', function () {
		var searchText = $(this).val().toLowerCase(); // Texto de búsqueda en minúsculas

		// Filtra los medicamentos que coinciden con el texto de búsqueda
		$('.btn-toolbar').each(function () {
			var medicamentoNombre = $(this).find('[data-content="medicamento"]').text().toLowerCase(); // Nombre del medicamento

			if (medicamentoNombre.includes(searchText)) {
				$(this).show(); // Muestra el medicamento si coincide
			} else {
				$(this).hide(); // Oculta el medicamento si no coincide
			}
		});
	});

	$("#guarda_consulta").click(function (e) {
		e.preventDefault();
		$('#consulta_confirmacion_final').modal('show');

		$('#consulta_crear_ok').click(function (e) {
			$('#form_guardar_consulta_enfermeria').submit();
			$('#consulta_confirmacion_final').modal('hide');
		});

	});


	$('.select_2').select2();

	$('#data_picker_gral').datepicker();


})