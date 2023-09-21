import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/medicamentos_movimientos',
		get_path:'/busqueda',
		table:$('.tabla_medicamentos_movimientos'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:[[ 6, "desc" ]]},
		render_row:medicamento=>{

			const formatDate = (date, includeTime) => {
                if (!date) return '';
                const d = new Date(date);
                const formattedDate = `${d.getDate()}/${d.getMonth() + 1}/${d.getFullYear()}`;
                if (includeTime) {
                    const formattedTime = `${d.getHours()}:${d.getMinutes()}:${d.getSeconds()}`;
                    return `${formattedDate} ${formattedTime}`;
                }
                return formattedDate;
            };

            const formattedFechaIngreso = formatDate(medicamento.fecha_ingreso, false);
            const formattedCreatedAt = formatDate(medicamento.created_at, true);
			
			return $(`
				<tr>
					<td>${medicamento.nombre}</td>
					<td>${medicamento.ingreso==null ? '' : medicamento.ingreso}</td>
					<td>${medicamento.suministrados!=null && medicamento.suministrados!=0 ? medicamento.suministrados : ''}</td>
					<td>${medicamento.egreso!=null && medicamento.egreso!=0 ? medicamento.egreso : ''}</td>

					<td>${medicamento.motivo!=null && medicamento.motivo!=0 ? medicamento.motivo : ''}</td>
					<td>${formattedFechaIngreso}</td>
					<td>${formattedCreatedAt}</td>
				</tr>`
			)
		}
	})

})
