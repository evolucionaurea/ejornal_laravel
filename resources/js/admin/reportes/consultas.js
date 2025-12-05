import Tablas from '../../classes/Tablas.js'

$(() => {

	$('[name="fecha_inicio"],[name="fecha_final"]').datepicker()


	const tabla_medicas = new Tablas({
		controller: '/admin/reportes',
		get_path: '/consultas_medicas',
		table: $('[data-table="medicas"]'),
		modulo_busqueda: $('[data-form="form-medicas"]'),
		server_side: true,
		datatable_options: {
			order: [[4, 'desc']],
			dom: '<"table-spacer-top"l>t<"table-spacer-bottom"ip>',
			columns: [
				{
					data: null,
					name: 'nominas.nombre',
					render: v => {
						return `${v.trabajador.nombre}`
					}
				},
				{
					data: null,
					name: 'clientes.nombre',
					render: v => {
						if (!v.trabajador.cliente) return ''
						return v.trabajador.cliente.nombre
					}
				},
				/*{
					data:'temperatura_auxiliar',
					name:'temperatura_auxiliar'
				},
				{
					data:'peso',
					name:'peso'
				},
				{
					data:'altura',
					name:'altura'
				},*/
				{
					data: 'derivacion_consulta',
					name: 'derivacion_consulta'
				},
				{
					data: 'diagnostico.nombre',
					name: 'diagnostico_consulta.nombre'
				},
				{
					data: 'fecha',
					name: 'fecha'
				}

			]
		}
	})

	const tabla_enfermerias = new Tablas({
		controller: '/admin/reportes',
		get_path: '/consultas_enfermeria',
		//delete_path:'/destroy',
		table: $('[data-table="enfermeria"]'),
		modulo_busqueda: $('[data-form="form-enfermeria"]'),
		server_side: true,
		datatable_options: {
			order: [[4, 'desc']],
			dom: '<"table-spacer-top"l>t<"table-spacer-bottom"ip>',
			columns: [
				{
					data: null,
					name: 'nominas.nombre',
					render: v => {
						return `${v.trabajador.nombre}`
					}
				},
				{
					data: null,
					name: 'clientes.nombre',
					render: v => {
						if (!v.trabajador.cliente) return ''
						return v.trabajador.cliente.nombre
					}
				},
				/*{
					data:'temperatura_auxiliar',
					name:'temperatura_auxiliar'
				},
				{
					data:'peso',
					name:'peso'
				},
				{
					data:'altura',
					name:'altura'
				},*/
				{
					data: 'derivacion_consulta',
					name: 'derivacion_consulta'
				},
				{
					data: 'diagnostico.nombre',
					name: 'diagnostico_consulta.nombre'
				},
				{
					data: 'fecha',
					name: 'fecha'
				}

			]
		}

	})


	const tabla_nutricionales = new Tablas({
		controller: '/admin/reportes',
		get_path: '/consultas_nutricionales',
		//delete_path:'/destroy',
		table: $('[data-table="nutricionales"]'),
		modulo_busqueda: $('[data-form="form-nutricionales"]'),
		server_side: true,
		datatable_options: {
			order: [[4, 'desc']],
			dom: '<"table-spacer-top"l>t<"table-spacer-bottom"ip>',
			columns: [
				{
					data: null,
					name: 'nominas.nombre',
					render: v => {
						return `${v.trabajador.nombre}`
					}
				},
				{
					data: null,
					name: 'clientes.nombre',
					render: v => {
						if (!v.trabajador.cliente) return ''
						return v.trabajador.cliente.nombre
					}
				},
				/*{
					data:'temperatura_auxiliar',
					name:'temperatura_auxiliar'
				},
				{
					data:'peso',
					name:'peso'
				},
				{
					data:'altura',
					name:'altura'
				},*/
				{
					data: null,
					name: 'derivacion_consulta',
					render: v => {
						if (v.derivacion_consulta == null) return 'N/A'
						return v.derivacion_consulta
					}
				},
				{
					data: null,
					name: 'diagnostico_consulta.nombre',
					render: v => {
						if (v.diagnostico_consulta == null) return 'N/A'
						return v.diagnostico_consulta
					}
				},
				{
					data: 'fecha',
					name: 'fecha'
				}

			]
		}

	})


})