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
		table: $('.tabla_reporte_fichadas'),
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
					data: 'ingreso',
					name: 'ingreso'
				},
				{
					data: 'egreso',
					name: 'egreso',
					render: v => v ?? '<i class="text-muted">[aún trabajando]</i>'
				},
				{
					data: 'tiempo_dedicado',
					name: 'tiempo_dedicado',
					orderable: false,
					render: v => v == null ? '<i class="text-muted">[aún trabajando]</i>' : v
				},
				{
					data: 'ip',
					name: 'ip'
				}
			]
		}
	});

});
