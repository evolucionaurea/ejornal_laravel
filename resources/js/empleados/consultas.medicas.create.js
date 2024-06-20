$(()=>{

	let dominio = window.location.host;
	let imc;
	let medicamentos = '';
	let medicamentos_suministrados = [];

	$(".form-row .form-group input[name='peso']").keyup(function() {
		let peso = $(this).val();
		let altura = $(".form-row .form-group input[name='altura']").val();
		if (peso != '' && peso != null && peso != undefined && altura != '' && altura != null && altura != undefined && altura !== 0 || peso !== 0) {
		  imc = parseFloat(peso / (Math.pow((altura/100), 2))).toFixed(2);
			$(".form-row .form-group input[name='imc']").val(imc);
			$(".form-row .form-group input[name='imc_disabled']").val(imc);
		} else {
			$(".form-row .form-group input[name='imc']").val("");
		}
		if ($(".form-row .form-group input[name='imc']").val() == NaN) {
			$(".form-row .form-group input[name='imc']").val("");
		}
		if ($(".form-row .form-group input[name='imc']").val() == Infinity) {
			$(".form-row .form-group input[name='imc']").val("");
		}
	});


	$(".form-row .form-group input[name='altura']").keyup(function() {
		let altura = $(this).val();
		let peso = $(".form-row .form-group input[name='peso']").val();
		if (altura != '' && altura != null && altura != undefined && peso != '' && peso != null && peso != undefined && altura !== 0 || peso !== 0) {
		  imc = parseFloat(peso / (Math.pow((altura/100), 2))).toFixed(2);
			$(".form-row .form-group input[name='imc']").val(imc);
			$(".form-row .form-group input[name='imc_disabled']").val(imc);
		} else {
			$(".form-row .form-group input[name='imc']").val("");
		}
		if ($(".form-row .form-group input[name='imc']").val() == NaN) {
			$(".form-row .form-group input[name='imc']").val("");
		}
		if ($(".form-row .form-group input[name='imc']").val() == Infinity) {
			$(".form-row .form-group input[name='imc']").val("");
		}
	});


	$('#cargar_medicacion').click(function() {
		$('#cargar_medicacion_abrir').modal('show');
		medicamentos = '';
		medicamentos_suministrados = [];
	});



	$("#aceptar_suministrar_medicamentos").click(function() {

		// VALIDAR QUE NO PONGA MAS DEL STOCK QUE HAY EN CADA MEDICAMENTO
		medicamentos = '';
		medicamentos_suministrados = [];

		$(".modal_medicacion_a_suministrar .btn-toolbar").each(function(index) {
			if ($(this).find('input').val() != '') {
				medicamentos_suministrados.push({
					'nombre': $(this).find('h6').text(),
					'id_medicamento': $(this).find('input').attr('name'),
					'suministrados': $(this).find('input').val()
				})
			}
		});

		console.log(medicamentos_suministrados);

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
					'class': 'ul_lista_medicamentos'
				}).append(
					$('<li>', {
						'class': ''
					}).append(
						$('<p>', {
							'text': medicamentos_suministrados[i].nombre + ':  ' + medicamentos_suministrados[i].suministrados
						})
					)
				)
			);

			$(".listado_medicaciones_inputs_ocultos").append(
				$('<input>', {
					'type': 'hidden',
					'name': 'medicaciones[]',
					'value': medicamentos_suministrados[i].id_medicamento+','+medicamentos_suministrados[i].suministrados
				})
			);
		  }
		}

		$('.listado_medicaciones ul li p').css('color', 'grey');

	});


	// Evento de búsqueda en tiempo real
	$('#medicamentoSearch').on('input', function () {
		var searchText = $(this).val().toLowerCase(); // Texto de búsqueda en minúsculas

		// Filtra los medicamentos que coinciden con el texto de búsqueda
		$('.btn-toolbar').each(function () {
			var medicamentoNombre = $(this).find('h6').text().toLowerCase(); // Nombre del medicamento

			if (medicamentoNombre.includes(searchText)) {
				$(this).show(); // Muestra el medicamento si coincide
			} else {
				$(this).hide(); // Oculta el medicamento si no coincide
			}
		});
	});

	
	$("#guarda_consulta").click(function(e) {
		e.preventDefault();
		$('#consulta_confirmacion_final').modal('show');

		$('#consulta_medica_crear_ok').click(function(e) {
			$('#form_guardar_consulta_medica').submit();
			$('#consulta_confirmacion_final').modal('hide');
		});

	});

	$('.select_2').select2();
	let today = new Date();
    let formattedDate = ('0' + today.getDate()).slice(-2) + '/' + ('0' + (today.getMonth() + 1)).slice(-2) + '/' + today.getFullYear();

    $('#data_picker_gral').val(formattedDate).prop('readonly', true);
	$('#fecha_actual_oculta').val(formattedDate).prop('readonly', true);
	$('#data_picker_gral').click(false);

})