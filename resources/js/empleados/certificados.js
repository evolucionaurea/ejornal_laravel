import Tablas from '../Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/certificados',
		table:$('.tabla_certificados_ausentismo_listado'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:[[ 4, "desc" ]]},
		render_row:certificado=>{
			return $(`
				<tr>
					<td>${certificado.nombre}</td>
					<td>${certificado.medico}</td>
					<td>${certificado.institucion}</td>
					<td>${certificado.fecha_inicio}</td>
					<td>${certificado.fecha_final}</td>
					<td>${certificado.fecha_regreso_trabajar}</td>
					<td>${certificado.matricula_nacional==null ? 'no cargada' : certificado.matricula_nacional}</td>
				</tr>`
			)
		}
	})

})
