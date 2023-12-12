import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/medicamentos_movimientos',
		get_path:'/busqueda',
		table:$('.tabla_medicamentos_movimientos'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:[[ 6, "desc" ]]},
		render_row: medicamento => {
			const formatDate = (date) => {
				if (!date) return '';
				const d = new Date(date);
				const formattedDate = `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear()}`;
				const isoFormattedDate = `${d.getFullYear()}-${(d.getMonth() + 1).toString().padStart(2, '0')}-${d.getDate().toString().padStart(2, '0')}`;
				return { formattedDate, isoFormattedDate };
			};
		
			const { formattedDate: formattedFechaIngreso, isoFormattedDate: isoFechaIngreso } = formatDate(medicamento.fecha_ingreso);
			const { formattedDate: formattedCreatedAt, isoFormattedDate: isoCreatedAt } = formatDate(medicamento.created_at);
		
			return $(`
				<tr>
					<td>${medicamento.nombre}</td>
					<td>${medicamento.ingreso == null ? '' : medicamento.ingreso}</td>
					<td>${medicamento.suministrados != null && medicamento.suministrados != 0 ? medicamento.suministrados : ''}</td>
					<td>${medicamento.egreso != null && medicamento.egreso != 0 ? medicamento.egreso : ''}</td>
					<td>${medicamento.motivo != null && medicamento.motivo != 0 ? medicamento.motivo : ''}</td>
					<td data-order="${isoFechaIngreso}">${formattedFechaIngreso}</td>
					<td data-order="${isoCreatedAt}">${formattedCreatedAt}</td>
				</tr>`
			);
		}
		
		
	})

})
