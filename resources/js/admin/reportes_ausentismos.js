import Tablas from '../classes/Tablas.js';

$(() => {

	new Tablas({
		controller: '/admin/reportes',
		get_path: '/ausentismos_ajax',
		table: $('.tabla_reporte_ausentismos'),
		modulo_busqueda: $('[data-toggle="busqueda-fecha"]'),
		server_side: true,

		datatable_options: {
			order: [[5, 'desc']],
			columns: [
				{
					data: 'trabajador',
					name: 'clientes.nombre',
					render: v => {

						if (v == null) return '<span class="text-muted font-italic">[sin asociar]</span>'
						return v.cliente.nombre
					}
				},
				{
					data: 'trabajador',
					name: 'nominas.nombre',
					render: v => {
						if (v == null) return '<span class="text-muted font-italic">[sin asociar]</span>'
						return v.nombre
					}
				},
				{
					data: 'user',
					name: 'user'
				},
				{
					data: 'tipo',
					name: 'ausentismo_tipo.nombre',
					render: v => {
						return v.nombre
					}
				},
				{
					data: 'fecha_inicio',
					name: 'fecha_inicio'
				},
				{
					data: 'fecha_final',
					name: 'fecha_final',
					render: v => {
						return v == null ? '<i class="text-muted">[no cargada]</i>' : v
					}
				},
				{
					data: 'dias_ausente',
					name: 'dias_ausente'
				}
			]
		}
	})

})
