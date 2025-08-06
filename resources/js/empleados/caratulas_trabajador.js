import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/caratulas',
		get_path:'/busqueda_trabajador',
		delete_path:'/destroy',
		table:$('[data-table="caratulas"]'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		//datatable_options:{order:false},
	  //delete_message:'¿Seguro deseas borrar esta carátula?',
		server_side:true,
		datatable_options:{
			order:[[9,'desc']],
			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',
			columns:[
				{
					data:'id',
					name:'id',
					className:'align-middle'					
				},
				{
					data:'patologias',
					name:'patologias',
					className:'align-middle',
          render:patologias=>{
            if(patologias.length==0) return '<span class="text-muted font-italic">[Sin patologías]</span>'
						let output = []
						patologias.map(p=>{
							output.push(`<span class="badge badge-dark p-2 mr-1 mb-1">${p.nombre}</span>`)
						})
						return output.join('')
          }
				},
        {
          data:'user',
          name:'user',
          className:'align-middle'					
        },
        {
					data:'medicacion_habitual',
					name:'medicacion_habitual',
					className:'align-middle'					
				},
        {
					data:'antecedentes',
					name:'antecedentes',
					className:'align-middle'					
				},
        {
					data:'alergias',
					name:'alergias',
					className:'align-middle'					
				},
        {
					data:'peso',
					name:'peso',
					className:'align-middle'					
				},
        {
					data:'altura',
					name:'altura',
					className:'align-middle'					
				},
        {
					data:'imc',
					name:'imc',
					className:'align-middle'					
				},
        {
					data:'created_at_formatted',
					name:'created_at',
					className:'align-middle'					
				},
        {
					data:null,
					name:'id',
					className:'align-middle text-right',
          render:(v,type,row,meta)=>{
            if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user) return ''
						return `
						<div class="acciones_tabla justify-content-end">
							<button data-toggle="delete" data-id="${v.id}" title="Eliminar" >
                <i class="fas fa-trash"></i>
              </button>
						</div>`
          }
				},

			]
		}

	})


})
