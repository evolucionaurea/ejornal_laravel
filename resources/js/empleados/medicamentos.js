import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/medicamentos',
		get_path:'/busqueda',
		table:$('[data-table="movimientos-medicamentos"]'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:[[ 5, "desc" ]]},
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
					<td>${fechaFormateada}</td>
					<td>
						<div style="max-width:180px">${medicamento.motivo}</div>
					</td>
					<td scope="row" class="acciones_tabla">
						<a class="editar_stock_medicamentos" title="Editar" data-toggle="modal" data-target="#editar_stock_medicamentos" href="#" data-info="${medicamento.id}">
							<i class="fas fa-minus"></i>
						</a>
					</td>
				</tr>`
			)
		}
	})


	$('[data-table="movimientos-medicamentos"]').on('click','.editar_stock_medicamentos',btn=>{
		let id = $(btn.currentTarget).attr('data-info')
		$('.form_editar_stock_medicamentos').attr('action', `medicamentos/${id}`)
	})


})
