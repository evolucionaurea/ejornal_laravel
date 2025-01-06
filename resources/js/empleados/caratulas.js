import Tablas from '../classes/Tablas.js';

$(()=>{


	new Tablas({
		controller:'/empleados/caratulas',
		// get_path:'/busqueda',
		delete_path:'/destroy',
		table:$('.tabla_caratulas'),
		// modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		//datatable_options:{order:false},
		// delete_message:'¿Seguro deseas borrar este ausentismo?',
		server_side:true,
		datatable_options:{
			order:[[5,'desc']],
			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',
			columns:[
				{
					data:row=>row,
					name:'caratulas.id',
					className:'align-middle',
					render:v=>{
                        console.log('ver', v);
                        
						return `
							<div><b>${v.id}</b></div>
						`
					}
				},
				{
					data:row=>row,
					name:'caratulas.trabajador',
					className:'align-middle',
					render:v=>{
						return v.id_trabajador==null ? '[no cargado]' : v.id_trabajador
					}
				},
				{
					data:row=>row,
					name:'caratulas.cliente',
					className:'align-middle',
					render:v=>{
						return v.id_cliente==null ? '[no cargado]' : v.id_cliente
					}
				},
				{
					data:row=>row,
					name:'caratulas.patologia',
					className:'align-middle',
					render:v=>{
						return v.id_patologia==null ? '[no cargado]' : v.id_patologia
					}
				},
				{
					data:row=>row,
					name:'actions',
					orderable:false,
					className:'align-middle',
					render:(v,type,row,meta)=>{

						return `
                        <div>Hola</div>
							
						`

					}
				}

			]
		}

	})


})
