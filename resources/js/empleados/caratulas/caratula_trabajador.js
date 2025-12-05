$(() => {

	console.log('caratula.trabajador');

	var tokenEl = document.querySelector('meta[name="csrf-token"]');
	var CSRF = tokenEl ? tokenEl.getAttribute('content') : '';

	$('#id_nomina').on('change', async select => {
		let idNomina = $(select.currentTarget).val();
		if (!idNomina) {
			$('#caratula').html('<p class="alert alert-info">Seleccione un trabajador de la nomina</p>');
			return;
		}
		const template = await window.get_template(`/api/get_caratula_nomina/${idNomina}`);
		$('#caratula').html(template);
	});
	if ($('#id_nomina').val() != '') {
		$('#id_nomina').trigger('change')
	}

	$('body').on('click', '[data-toggle="editar-caratula"]', async btn => {
		btn.preventDefault();
		let idNomina = $('#id_nomina').val();
		let dominio = window.location.origin;
		const template = await window.get_template(`${dominio}/api/get_caratula_modal/${idNomina}`);
		const $template = $(template);
		$template.find('[name="patologia_edit_caratula[]"]').select2();
		$('body').append($template);
		$('#editarCaratulaModal').modal('show');
	});

	// Eliminar el modal al cerrarlo
	$('body').on('hidden.bs.modal', '#editarCaratulaModal', () => {
		$('#editarCaratulaModal').remove();
	});

	// SUBMIT: actualizar carátula por PUT
	$('body').on('submit', '[data-form="editar-caratula"]', async (form) => {
		form.preventDefault();
		const post = window.get_form(form.currentTarget);

		// Obtenemos el ID de carátula para la URL PUT
		const caratulaId =
			$('input[name="caratula_id"]').val() ||
			$('#editarCaratulaModal').data('caratula-id') ||
			$('#editarCaratulaModal').attr('data-caratula-id') ||
			null;

		if (!caratulaId) {
			toastr.error('No se encontró el ID de la carátula para actualizar.');
			return;
		}

		// Construimos el payload que espera el controller
		const payload = {
			id_nomina: post.id_nomina || post.trabajador_id_edit_caratula || $('#id_nomina').val() || null,
			medicacion_habitual: post.medicacion_habitual || post.medicacion_habitual_edit_caratula || '',
			antecedentes: post.antecedentes || post.antecedentes_edit_caratula || '',
			alergias: post.alergias || post.alergias_edit_caratula || '',
			peso: post.peso || post.peso_edit_caratula || '',
			altura: post.altura || post.altura_edit_caratula || '',
			imc: post.imc || post.imc_edit_caratula || '',
			id_patologia: post['id_patologia[]'] || post.id_patologia || post['patologia_edit_caratula[]'] || []
		};

		if (!Array.isArray(payload.id_patologia)) {
			payload.id_patologia = payload.id_patologia ? [payload.id_patologia] : [];
		}

		// ⇩⇩ Si imc viene vacío, lo calculamos en el cliente para pasar validación
		if ((!payload.imc || payload.imc === '') && payload.peso && payload.altura) {
			payload.imc = window.calculate_imc(payload.peso, payload.altura);
		}

		try {
			const response = await axios.put(
				`/empleados/caratulas/${encodeURIComponent(caratulaId)}`,
				payload,
				{
					headers: {
						'X-CSRF-TOKEN': CSRF,
						'X-Requested-With': 'XMLHttpRequest',
						'Accept': 'application/json'
					},
					withCredentials: true
				}
			);

			if (response?.data?.estado === false) {
				toastr.error(response.data.message || 'No se pudo actualizar la carátula.');
				return;
			}

			toastr.success((response?.data?.message) || 'Carátula actualizada con éxito');
			$('#editarCaratulaModal').modal('hide');

			const idNomina = payload.id_nomina;
			if (idNomina) {
				const template = await window.get_template(`/api/get_caratula_nomina/${idNomina}`);
				$('#caratula').html(template);
			}

		} catch (err) {
			const status = err?.response?.status;
			if (status === 422) {
				// Mostramos detalles de validación si vienen
				const errs = err?.response?.data?.errors;
				if (errs) {
					const first = Object.values(errs)[0]?.[0];
					toastr.error(first || 'Datos inválidos (422).');
				} else {
					toastr.error('Datos inválidos (422).');
				}
			} else if (status === 419) {
				toastr.error('CSRF inválido o sesión expirada (419). Recargá la página.');
			} else if (status === 403) {
				toastr.error('Sin permiso (403).');
			} else {
				toastr.error('Error al actualizar la carátula.');
			}
			console.error('PUT carátula error:', status, err?.response?.data || err);
		}
	});


	$('body').on('change keyup', 'input[name="peso_edit_caratula"], input[name="altura_edit_caratula"]', () => {
		const peso = $("input[name='peso_edit_caratula']").val();
		const altura = $("input[name='altura_edit_caratula']").val();
		$("input[name='imc_edit_caratula']").val(window.calculate_imc(peso, altura));
	});

	$('body').on('click', '[data-toggle="usar-datos-caratula"]', () => {
		const peso = $('[data-content="peso"]').text();
		const altura = $('[data-content="altura"]').text();

		$('[name="peso"]').val(peso);
		$('[name="altura"]').val(altura);
		$("input[name='imc']").val(window.calculate_imc(peso, altura));
		$("input[name='imc_disabled']").val(window.calculate_imc(peso, altura));
	});

});
