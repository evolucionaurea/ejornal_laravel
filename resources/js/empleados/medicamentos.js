import Tablas from '../classes/Tablas.js';

$(()=>{


	new Tablas({

		controller:'/empleados/medicamentos',
		get_path:'/busqueda',
		table:$('[data-table="movimientos-medicamentos"]'),
		modulo_busqueda:$('[data-toggle="busqueda-filtros"]'),
		datatable_options:{order:[[ 0, "desc" ]]},
		server_side:true,
		datatable_options:{
			order:[[0,'asc']],

			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',

			columns:[

				{
					data:'nombre',
					name:'nombre',
					className:'align-middle border-left'
				},
				{
					data:'ingreso',
					name:'ingreso',
					className:'align-middle border-left'
				},
				{
					data:'suministrados',
					name:'suministrados',
					className:'align-middle border-left'
				},
				{
					data:'egreso',
					name:'egreso',
					className:'align-middle border-left'
				},
				{
					data:'stock',
					name:'stock',
					className:'align-middle border-left'
				},
				{
					data:null,
					name:'actions',
					sortable:false,
					className:'align-middle text-right border-left',
					render:(v,type,row,meta)=>{

						if(meta.settings.json.fichada_user!=1 && meta.settings.json.fichar_user) return ''

						return `
							<div class="acciones_tabla justify-content-end">
								<button class="editar_stock_medicamentos" title="Egreso del medicamento" data-toggle="modal" data-target="#editar_stock_medicamentos" href="#" data-info="${v.id}">
									<i class="fas fa-minus"></i>
								</button>
							</div>
						`
					}
				}

			]
		}


	})

	/*new Tablas({
		controller:'/empleados/medicamentos',
		get_path:'/busqueda',
		table:$('[data-table="movimientos-medicamentos"]'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:[[ 0, "desc" ]]},
		render_row:medicamento=>{

			// Obtener la fecha en formato ISO (yyyy-mm-dd)
			const fechaISO = medicamento.fecha_ingreso.split(' ')[0];

			// Dividir la fecha en año, mes y día
			const [año, mes, dia] = fechaISO.split('-');

			// Formatear la fecha en el formato "dd/mm/yyyy"
			const fechaFormateada = `${dia}/${mes}/${año}`;

			return $(`
				<tr>
					<td>${medicamento.nombre}</td>
					<td>${medicamento.ingreso}</td>
					<td>${medicamento.suministrados}</td>
					<td>${medicamento.egreso}</td>
					<td>${medicamento.stock}</td>
					<td scope="row" class="acciones_tabla">
						<a class="editar_stock_medicamentos" title="Editar" data-toggle="modal" data-target="#editar_stock_medicamentos" href="#" data-info="${medicamento.id}">
							<i class="fas fa-minus"></i>
						</a>
					</td>
				</tr>`
			)
		}
	})*/


	$('[data-table="movimientos-medicamentos"]').on('click','.editar_stock_medicamentos',btn=>{
		let id = $(btn.currentTarget).attr('data-info')
		$('.form_editar_stock_medicamentos').attr('action', `medicamentos/${id}`)
	})


})
