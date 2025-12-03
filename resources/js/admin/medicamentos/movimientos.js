import Tablas from '../../classes/Tablas.js';

$(() => {

	new Tablas({
		controller: '/admin/movimiento',
		get_path: '/medicamentos',
		table: $('[data-table="movimientos"]'),
		modulo_busqueda: $('[data-toggle="busqueda-fecha"]'),
		server_side: true,
		responsive: true,

		datatable_options: {
			order: [[9, 'desc']],
			columns: [

				{
					data: 'stock_medicamento',
					name: 'medicamentos.nombre',
					render: v => {
						if (v.medicamento == null) return ''
						return v.medicamento.nombre
					}
				},
				{
					data: 'stock_medicamento',
					name: 'clientes.nombre',
					render: v => {
						if (v.cliente == null) return ''
						return v.cliente.nombre
					}
				},
				{
					data: 'stock_medicamento',
					name: 'users.nombre',
					render: v => {
						if (v.user == null) return ''
						return v.user.nombre
					}
				},
				{
					data: 'ingreso',
					name: 'ingreso'
				},
				{
					data: 'suministrados',
					name: 'suministrados'
				},
				{
					data: 'egreso',
					name: 'egreso'
				},
				{
					data: 'stock',
					name: 'stock'
				},
				{
					data: 'fecha_ingreso',
					name: 'fecha_ingreso'
				},
				{
					data: 'motivo',
					name: 'motivo',
					className: 'small'
				},
				{
					data: 'created_at',
					name: 'created_at'
				}

			]
		}
	})


	$('[data-toggle="busqueda-fecha"]').find('[name="from"],[name="to"]').datepicker()

})
