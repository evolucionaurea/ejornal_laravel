import Tablas from '../classes/Tablas.js';

$(() => {


	new Tablas({
		controller: '/admin/reportes',
		get_path: '/search_actividad_usuarios',
		table: $('[data-table="actividades"]'),
		modulo_busqueda: $('[data-toggle="busqueda-fecha"]'),
		server_side: true,
		responsive: true,

		datatable_options: {
			order: [[2, 'desc']],
			columns: [

				{
					data: null,
					name: 'user',
					render: v => {
						if (v.user == null) return `<span class="text-muted font-italic">[dato faltante]</span>`
						return `
							<div>${v.user}</div>
							<span class="badge badge-${v.estado == 1 ? 'success' : 'danger'}">${v.estado == 1 ? 'activo' : 'inactivo'}</span>
						`
					}
				},
				{
					data: 'cliente_nombre',
					name: 'cliente_nombre',
					render: v => {
						if (v == null) return ''
						return v
					}
				},
				{
					data: 'created_at_formatted',
					name: 'created_at'
				},
				{
					data: 'actividad',
					name: 'actividad'
				},
				{
					data: 'trabajador_nombre',
					name: 'trabajador_nombre',
					render: v => {
						if (v == null) return ''
						return v
					}
				}

			]
		}
	})


	$('[data-toggle="busqueda-fecha"]').find('[name="from_date"],[name="to_date"]').datepicker()
	$('[data-toggle="busqueda-fecha"]').find('[name="user"],[name="cliente"]').select2()



	console.log('actividades.usuarios')
})