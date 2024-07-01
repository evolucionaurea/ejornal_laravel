import Tablas from '../classes/Tablas.js';


$(()=>{

	const table = new Tablas({
		controller:'/admin',
		get_path:'/get_medicamentos',
		delete_path:'/destroy',
		table:$('[data-table="medicamentos"]'),

		modulo_busqueda:$('[data-toggle="search"]'),

		server_side:true,

		datatable_options:{
			ordering:false,
			columns:[
				{
					data:'medicamento.nombre',
					className:'align-middle',
					name:'medicamentos.nombre'
				},
				{
					data:'stock',
					className:'align-middle',
					name:'stock'
				},
				{
					data:'cliente',
					className:'align-middle',
					name:'clientes.nombre',
					render:v=>{
						if(v==null) return '<span class="text-muted font-italic">[no encontrado]</span>'
						return v.nombre
					}
				}
			]
		}
	})

	$('[name="medicamento"],[name="cliente"]').on('change',select=>{
		table.datatable_instance.ajax.reload()
	})


});
