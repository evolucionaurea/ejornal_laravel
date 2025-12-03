import Tablas from '../../classes/Tablas.js';

$(() => {

	new Tablas({
		controller: '/admin/reportes',
		get_path: '/comunicaciones',
		table: $('[data-table="comunicaciones"]'),
		modulo_busqueda: $('[data-toggle="busqueda-fecha"]'),
		server_side: true,
		responsive: true,

		datatable_options: {
			order: [[6, 'desc']],
			columns: [

				{
					data: 'ausentismo',
					name: 'nominas.nombre',
					render: v => {
						if (v.trabajador == null) return `<span class="small text-muted font-italic">[no encontrado]</span>`
						return v.trabajador.nombre
					}
				},
				{
					data: 'ausentismo',
					name: 'clientes.nombre',
					render: v => {
						if (v.trabajador == null) return `<span class="small text-muted font-italic">[no encontrado]</span>`
						return v.cliente.nombre
					}
				},
				{
					data: 'ausentismo',
					name: 'ausentismo_tipo.nombre',
					render: v => {
						return v.tipo.nombre
					}
				},
				{
					data: 'tipo',
					name: 'tipo_comunicacion.nombre',
					render: v => {
						return v.nombre
					}
				},
				{
					data: null,
					name: 'user',
					render: v => {
						const user = v.user == null ? v.user : v.ausentismo.user
						if (user == null) return `<span class="small text-muted font-italic">[no registrado]</span>`
						return user
					}
				},
				{
					data: 'descripcion',
					name: 'descripcion',
					width: 320,
					className: 'small'
				},
				{
					data: 'created_at',
					name: 'created_at'
				},

			]
		}
	})


	$('[data-toggle="busqueda-fecha"]').find('[name="from"],[name="to"]').datepicker()

})
