import Tablas from '../Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/medicamentos_movimientos',
		table:$('.tabla_medicamentos_movimientos'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:[[ 6, "desc" ]]},
		render_row:medicamento=>{
			return $(`
				<tr>
					<td>${medicamento.nombre}</td>
					<td>${medicamento.ingreso==null ? '' : medicamento.ingreso}</td>
					<td>${medicamento.suministrados!=null && medicamento.suministrados!=0 ? medicamento.suministrados : ''}</td>
					<td>${medicamento.egreso!=null && medicamento.egreso!=0 ? medicamento.egreso : ''}</td>

					<td>${medicamento.motivo!=null && medicamento.motivo!=0 ? medicamento.motivo : ''}</td>
					<td>${medicamento.fecha_ingreso}</td>
					<td>${medicamento.created_at}</td>
				</tr>`
			)
		}
	})

})
