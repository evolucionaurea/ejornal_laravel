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
			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',
			columns:[
				{
					data:null,
					className:'align-middle',
					name:'nombre',
					width:180,
					render:row=>{
						return `
							<div>${row.ausentismo.trabajador.nombre}</div>
							<div class="small text-muted">DNI: ${row.ausentismo.trabajador.dni}</div>
						`
					}
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
					name:'comunicaciones.created_at'
				},
				{
					data: 'archivos',
					className: 'align-middle small',
					orderable: false,
					render: archivos => {
						const baseUrl = '/comunicaciones/archivo';
						if (archivos.length > 0) {

							const dropdownItems = archivos.map(archivo =>
								`<li class="dropdown-item" style="width:auto">
									<a class="small" href="${baseUrl}/${archivo.id_comunicacion}/${archivo.hash_archivo}" target="_blank">
									   ${archivo.archivo}
									</a>
								</li>`
							).join('');

							return `
								<div class="btn-group" role="group">
									<button type="button" class="btn btn-info dropdown-toggle btn-sm py-1 px-3 text-white" data-toggle="dropdown" aria-expanded="false">
										Archivos
									</button>
									<ul class="dropdown-menu p-0">
										${dropdownItems}
									</ul>
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
				{
					data:null,
					className:'align-middle',
					name:'actions',
					orderable:false,
					render:(v,type,row,meta)=>{

						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user) return ''

						return ''
						return `
						<a href="empleados/ausentismo/${v.id_ausentismo}" class="btn btn-tiny btn-info">
							<i class="fal fa-angle-double-right fa-fw"></i> Ver Ausentismo
						</a>`
					}
				}

			]
		}

	})

})
