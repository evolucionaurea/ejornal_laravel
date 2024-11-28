import Tablas from '../classes/Tablas.js';

$(()=>{
	new Tablas({
		controller:'/empleados/comunicaciones',
		get_path:'/busqueda',
		table:$('.tabla_comunicaciones_listado'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		server_side:true,
		datatable_options: {
			order: [[3, "desc"]],
			dom:'<"table-spacer-top"lf>t<"table-spacer-bottom"ip>',
			columns:[
				{
					data:'nombre',
					className:'align-middle',
					name:'nombre',
					width:180
				},
				{
					data:'tipo',
					className:'align-middle',
					name:'tipo',
					width:180
				},
				{
					data:'user',
					className:'align-middle',
					name:'user',
					orderable:false,
					width:180
				},
				{
					data:'created_at',
					className:'align-middle',
					name:'created_at'
				},
				{
					data: 'archivos',
					className: 'align-middle small',
					orderable: false,
					render: archivos => {
						const baseUrl = '/comunicaciones/archivo';
						if (archivos.length > 0) {

							const dropdownItems = archivos.map(archivo =>
								`<a class="dropdown-item"
								   href="${baseUrl}/${archivo.id_comunicacion}/${archivo.hash_archivo}"
								   target="_blank">
								   ${archivo.archivo}
								</a>`
							).join('');

							return `
								<div class="btn-group" role="group">
									<button type="button" class="btn btn-info dropdown-toggle btn-sm text-white" data-toggle="dropdown" aria-expanded="false">
										Archivos
									</button>
									<div class="dropdown-menu">
										${dropdownItems}
									</div>
								</div>
							`;
						}
						return 'No se subieron';
					}
				},
				{
					data:null,
					className:'align-middle',
					name:'estado',
					render:v=>{
						if(v.id_cliente != v.trabajador_cliente) return `<span class="badge badge-dark">transferido</span>`
						return `<span class="badge badge-${v.estado==1?'success':'danger'}">${v.estado==1?'Activo':'Inactivo'}</span>`
					}
				},
				{
					data:'descripcion',
					className:'align-middle small',
					name:'descripcion',
					orderable:false
				},

			]
		}

	})

})
