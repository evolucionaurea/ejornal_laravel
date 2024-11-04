import Tablas from '../../classes/Tablas.js';
import Swal from 'sweetalert2';


$(() => {
	/*const updateSelectState = () => {
		const estado = $('[name="estado"]').val();
		if (estado) {
			$('[name="estado"]').val(estado);
		} else {
			$('[name="estado"]').val('todos');
		}
	};

	updateSelectState();

	$('[data-toggle="search"]').click(() => {
		updateSelectState();
	});

	$('[data-toggle="clear"]').click(() => {
		$('[name="estado"]').val('todos');
		updateSelectState();
	});*/

	let permiso_edicion_fichada = $('#permiso_edicion_fichada').val();

    // Evento para capturar clic en ingreso y egreso
	$(document).on('click', '#edit_ingreso, #edit_egreso', function (event) {
		event.preventDefault();  // Prevenir el refresco de pantalla
	
		const id = $(this).data('id');
		const isEditIngreso = $(this).attr('id') === 'edit_ingreso';

		// Obtener el valor directamente del data-ingreso o data-egreso
		const valor = isEditIngreso ? $(this).data('ingreso') : $(this).data('egreso');
	
		Swal.fire({
			title: 'Editar Fecha',
			html: `
				<p>Fecha actual: <strong>${valor}</strong></p>
				<div>
					<input type="text" id="newDate" class="border border-secondary">
				</div>
			`,
			showCancelButton: true,
			confirmButtonText: 'Guardar',
			cancelButtonText: 'Cancelar',
			didOpen: () => {
				// Inicializa el Datepicker en el input dentro de SweetAlert
				$('#newDate').datepicker({
					dateFormat: 'dd/mm/yy'
				});
	
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
			preConfirm: () => {
				const newDate = $('#newDate').val();
				if (!newDate) {
					Swal.showValidationMessage('Por favor selecciona una fecha');
					return false;
				}
				return { id, newDate, valor, isEditIngreso };
			}
		}).then((result) => {
			if (result.isConfirmed) {
				const { id, newDate, valor, isEditIngreso } = result.value;
				const id_loggeado = $('#id_loggeado').val();				
	
				// Hacer el fetch a la ruta de la API
				fetch('/api/cambiar_fichada', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Asegúrate de tener el token CSRF disponible
					},
					body: JSON.stringify({ id, newDate, valor, isEditIngreso, id_loggeado })
				})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						Swal.fire('Éxito', 'La fichada se actualizó correctamente', 'success');
					} else {
						Swal.fire('Error', data.message, 'error');
					}
				})
				.catch(error => {
					console.error('Error:', error);
					// Muestra un mensaje más descriptivo
					Swal.fire('Error', 'Hubo un problema al conectar con el servidor: ' + error.message, 'error');
				});				
			}
		});
	});
	
	
	
	
	new Tablas({

		controller: '/admin/reportes',
		get_path: '/fichadas_ajax',
		table: $('[data-table="fichadas"]'),
		modulo_busqueda: $('[data-toggle="busqueda-fecha"]'),
		server_side: true,

		datatable_options: {
			order: [[4, 'desc']],
			columns: [
				{
					data:'user',
					name:'users.nombre',
					render:v=>{
						return v.nombre
					}
				},
				{
					data: 'user',
					name: 'users.estado',
					render: v => v.estado == 1 ? 'Activo' : 'Inactivo'
				},
				{
					data: 'user',
					name: 'especialidades.nombre',
					render:v=>{
						if(v.especialidad==null) return '<i class="text-muted">[no aplica]</i>'
						return v.especialidad.nombre
					}
				},
				{
					data: 'cliente',
					name: 'clientes.nombre',
					render:v=>{
						if(v==null) return ''
						return v.nombre
					}
				},

				{
					data:null,
					name:'ingreso',
					orderable:false,
					render:v=>{
						console.log(v);
						if (permiso_edicion_fichada != null && permiso_edicion_fichada == 1 && v.ultimo_registro_user) {
							
							return `
							<div>
							${v.ingreso_formatted}
							<a data-ingreso="${v.ingreso_carbon}" href="#" data-id="${v.id}" id="edit_ingreso"><i class="fas fa-edit"></i></a>
							</div>
						`;
						}else{
							return v.ingreso_formatted
						}
						/*const date = new Date(v.egreso_carbon)
						return window.get_formatted_date(date)*/
					}
					/*render:v=>{
						const date = new Date(v.ingreso_carbon)

						return `${window.get_week_day(date.getDay())}, ${v.ingreso} hs.`
					}*/
					/*render:v=>{
						const date = new Date(v)
						return window.get_week_day(date.getDay())
					}*/
				},
				/*{
					data: 'ingreso_carbon',
					name: 'ingreso',
					render:v=>{
						const date = new Date(v)
						return window.get_formatted_date(date)
					}
				},
				{
					data: 'ingreso_carbon',
					name: 'ingreso',
					orderable:false,
					render:v=>{
						const date = new Date(v)
						return window.get_hours_minutes(date)
					}
				},*/

				{
					data:null,
					name:'egreso',
					render:v=>{
						if(v.egreso==null) return '<i class="text-muted">[aún trabajando]</i>'
						if (permiso_edicion_fichada != null && permiso_edicion_fichada == 1 && v.ultimo_registro_user) {
							return `
							<div>
							${v.egreso_formatted}
							<a data-ingreso="${v.egreso_carbon}"  href="#" data-id="${v.id}" id="edit_egreso"><i class="fas fa-edit"></i></a>
							</div>
						`;
						}else{
							return v.egreso_formatted
						}
						/*const date = new Date(v.egreso_carbon)
						return window.get_formatted_date(date)*/
					}
				},
				/*{
					data: null,
					name: 'egreso',
					orderable:false,
					render:v=>{
						if(v.egreso==null) return '<i class="text-muted">[aún trabajando]</i>'
						const date = new Date(v.egreso_carbon)
						return window.get_hours_minutes(date)
					}
				},*/


				{
					data: null,
					name: 'tiempo_dedicado',
					orderable: false,
					render: v => v.egreso == null ? '<i class="text-muted">[aún trabajando]</i>' : `${v.horas_minutos_trabajado} hs.`
				},

				{
					data:null,
					name:'dispositivo',
					render:v=>{
						let output = `<div>${v.sistema_operativo}</div>`
						if(v.browser) output += `<div class="small">${v.browser}</div>`
						if(v.dispositivo) output += `<div class="text-muted small">${v.dispositivo}</div>`

						return output
					}
				},
				{
					data: 'ip',
					name: 'ip'
				}
			]
		}
	});

});
