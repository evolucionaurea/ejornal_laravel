import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/grupos/nominas',
		get_path:'/busqueda'
		table:$('.tabla_nominas'),
		modulo_busqueda:$('[data-toggle="busqueda"]'),
		datatable_options:{
			order:[[0,'asc']],
			columns:[
				{data:'nombre'},
				{data:'email'},
				{data:'telefono'},
				{data:'dni'},
				{
					data:'estado',
					render:v=>{
						return `<span class="tag_ejornal tag_ejornal_${v==1?'success':'danger'}">${v==1?'Activo':'Inactivo'}</span>`
					}
				},
				{data:'sector'}
			]
		},
		server_side:true

	})

})
