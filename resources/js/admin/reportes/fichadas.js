import Tablas from '../../classes/Tablas.js';

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
					data:'ingreso_formatted',
					name:'ingreso',
					orderable:false,
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
						return v.egreso_formatted
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
