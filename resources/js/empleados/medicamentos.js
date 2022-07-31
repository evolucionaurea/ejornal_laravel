import Tablas from '../Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/medicamentos',
		table:$('.tabla_movimientos_empleado_listado'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:[[ 5, "desc" ]]},
		render_row:medicamento=>{
			return $(`
				<tr>
					<td>${medicamento.nombre}</td>
					<td>${medicamento.ingreso}</td>
					<td>${medicamento.suministrados}</td>
					<td>${medicamento.egreso}</td>
					<td>${medicamento.stock}</td>
					<td>${medicamento.fecha_ingreso}</td>
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

})
