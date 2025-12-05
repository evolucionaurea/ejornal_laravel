import Tablas from '../../classes/Tablas.js';
//import Swal from 'sweetalert2';


$(() => {


	const table = new Tablas({

		controller: '/admin/reportes',
		get_path: '/fichadas_ajax',
		delete_path: '/fichadas/destroy',
		table: $('[data-table="fichadas"]'),
		modulo_busqueda: $('[data-toggle="busqueda-fecha"]'),
		server_side: true,
		datatable_options: {
			order: [[4, 'desc']],
			columns: [
				{
					className: 'align-middle',
					data: 'user',
					name: 'users.nombre',
					render: v => {
						return v.nombre
					}
				},
				{
					className: 'align-middle',
					data: 'user',
					name: 'users.estado',
					render: v => v.estado == 1 ? 'Activo' : 'Inactivo'
				},
				{
					className: 'align-middle',
					data: 'user',
					name: 'especialidades.nombre',
					render: v => {
						if (v.especialidad == null) return '<i class="text-muted">[no aplica]</i>'
						return v.especialidad.nombre
					}
				},
				{
					className: 'align-middle',
					data: 'cliente',
					name: 'clientes.nombre',
					render: v => {
						if (v == null) return ''
						return v.nombre
					}
				},

				{
					className: 'align-middle has-floating-btn',
					data: null,
					name: 'ingreso',
					orderable: false,
					render: (v, type, row, meta) => {

						//console.log(meta.settings.json.user.permiso_edicion_fichada);

						if (meta.settings.json.user.permiso_edicion_fichada == 1) {
							return `
								<div>${v.ingreso_formatted}</div>
								<a href="#" class="floating-btn" title="Editar Fecha/Hora" data-toggle="editar-fichada" data-action="ingreso" data-fecha="${v.ingreso_carbon}">
									<i class="fa fa-pencil"></i>
								</a>`
						}
						return v.ingreso_formatted
					}
				},

				{
					className: 'align-middle has-floating-btn',
					data: null,
					name: 'egreso',
					render: (v, type, row, meta) => {
						if (v.egreso == null) return '<i class="text-muted">[aún trabajando]</i>'
						if (meta.settings.json.user.permiso_edicion_fichada == 1) {
							return `
								<div>${v.egreso_formatted}</div>
								<a href="#" class="floating-btn" title="Editar Fecha/Hora" data-toggle="editar-fichada" data-action="egreso" data-fecha="${v.egreso_carbon}">
									<i class="fa fa-pencil fa-fw"></i>
								</a>`;
						}
						return v.egreso_formatted
					}
				},

				{
					className: 'align-middle',
					data: null,
					name: 'tiempo_dedicado',
					orderable: false,
					render: v => v.egreso == null ? '<i class="text-muted">[aún trabajando]</i>' : `${v.horas_minutos_trabajado}`
				},

				{
					className: 'align-middle',
					data: null,
					name: 'dispositivo',
					render: v => {
						let output = `<div>${v.sistema_operativo}</div>`
						if (v.browser) output += `<div class="small">Nav: ${v.browser}</div>`
						if (v.dispositivo) output += `<div class="text-muted small">Disp: ${v.dispositivo}</div>`

						return output
					}
				},
				{
					className: 'align-middle',
					data: 'ip',
					name: 'ip'
				},
				{
					data: null,
					className: 'align-middle',
					name: 'actions',
					render: v => {
						return `
						<div class="acciones_tabla">
							<button data-toggle="delete" title="Eliminar" class="">
								<i class="fa fa-trash"></i>
							</button>
						</div>`
					}
				}
			]
		}
	})

	// Evento para capturar clic en ingreso y egreso
	$('[data-table="fichadas"]').on('click', '[data-toggle="editar-fichada"]', async btn => {
		btn.preventDefault();

		const td = $(btn.currentTarget).closest('td')
		const action = $(btn.currentTarget).attr('data-action')

		const tr = $(btn.currentTarget).closest('tr')
		const id = tr.attr('data-id')

		const fecha = td.find('span').text()
		const empleado = tr.find('td:first-of-type').text()

		//return console.log( oldDate, action, id )

		//const id = $(this).data('id');
		///const isEditIngreso = $(this).attr('id') === 'edit_ingreso';

		// Obtener el valor directamente del data-ingreso o data-egreso
		///const valor = isEditIngreso ? $(this).data('ingreso') : $(this).data('egreso');

		const promise = await Promise.all([
			get_template('/templates/form-cambiar-fichada'),
			axios.get(`/admin/reportes/fichada_nueva/${id}`)
		])

		const response = promise[0]
		const fichada = promise[1].data


		const form = $(response)
		form.find('[data-content="current_date"]').text(`${action == 'ingreso' ? fichada.ingreso : fichada.egreso} hs.`)
		form.find('[data-content="empleado"]').text(fichada.user.nombre)

		const old_date_obj = new Date(action == 'ingreso' ? fichada.ingreso_carbon : fichada.egreso_carbon)

		const old_month = old_date_obj.getMonth() + 1
		const old_day = old_date_obj.getDate()
		const old_date = `${old_day < 10 ? '0' + old_day : old_day}/${old_month < 10 ? '0' + old_month : old_month}/${old_date_obj.getFullYear()}`
		//console.log(old_date_obj.getHours())

		form.find('[name="new_date"]').val(old_date)
		form.find('[name="id"]').val(id)
		form.find('[name="action"]').val(action)

		for (var i = 0; i <= 23; i++) {
			const option = dom('option')
			const hour = i < 10 ? `0${i}` : i
			option.val(hour).text(hour)
			if (i == old_date_obj.getHours()) option.attr({ selected: true })
			form.find('[name="new_hour"]').append(option)
		}
		for (var m = 0; m <= 59; m++) {
			const option = dom('option')
			const minutes = m < 10 ? `0${m}` : m
			option.val(minutes).text(minutes)
			if (m == old_date_obj.getMinutes()) option.attr({ selected: true })
			form.find('[name="new_minutes"]').append(option)
		}

		const swal = await Swal.fire({
			title: 'Editar Fecha',
			html: form,
			showCancelButton: true,
			showConfirmButton: false,
			width: 720,
			cancelButtonText: 'Cancelar',
			didOpen: () => {
				// Inicializa el Datepicker en el input dentro de SweetAlert
				$('[name="new_date"]').datepicker({
					dateFormat: 'dd/mm/yy'
				});
				$('[name="new_date"]').blur()

				// Aplicar estilos a los botones
				const confirmButton = Swal.getConfirmButton();
				const cancelButton = Swal.getCancelButton();

				// Estilos para el botón de guardar
				confirmButton.style.backgroundColor = '#007bff'; // Color de fondo
				confirmButton.style.color = 'white'; // Texto en blanco
				confirmButton.style.fontSize = '12px'; // Tamaño de fuente más pequeño
				confirmButton.style.padding = '5px 10px'; // Espaciado interno
				confirmButton.style.borderRadius = '4px'; // Bordes redondeados
				confirmButton.style.border = 'none'; // Sin borde

				// Estilos para el botón de cancelar
				cancelButton.style.backgroundColor = '#dc3545'; // Color de fondo
				cancelButton.style.color = 'white'; // Texto en blanco
				cancelButton.style.fontSize = '12px'; // Tamaño de fuente más pequeño
				cancelButton.style.padding = '5px 10px'; // Espaciado interno
				cancelButton.style.borderRadius = '4px'; // Bordes redondeados
				cancelButton.style.border = 'none'; // Sin borde
			},
			/*preConfirm: () => {
				const newDate = $('#newDate').val();
				const oldDate = $('#oldDate').val();
				if (!newDate) {
					Swal.showValidationMessage('Por favor selecciona una fecha');
					return false;
				}
				const post = window.get_form($('[data-form="cambiar-fichada"]'))
				return console.log(post)
				return { id, newDate, fecha, action, oldDate };
			}*/
		})

		if (!swal.isConfirmed) return false

	})

	$('body').on('submit', '[data-form="cambiar-fichada"]', async form => {
		form.preventDefault()
		const post = get_form(form.currentTarget)

		try {
			const response = await axios.post('/admin/reportes/cambiar_fichada', post)
			///console.log(response)
			toastr.success(response.data.message)

			///$('[data-table="fichadas"]').find(`[data-id="${response.data.last_record.id}"]`)
			Swal.close()

			//$('.dataTables_filter input').val(response.data.last_record.user.nombre).trigger('keyup').effect('highlight').effect('pulsate')
			table.datatable_instance.ajax.reload()

		} catch (error) {
			toastr.error(error.response.data.message)
		}

	})



});
