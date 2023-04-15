import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/certificados',
		get_path:'/busqueda',
		table:$('.tabla_certificados_ausentismo_listado'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:[[ 4, "desc" ]]},
		render_row:certificado=>{

			// Creamos las variables con las fechas en inglés (pueden ser null)
			let fechaInicioEnIngles = certificado.fecha_inicio;
			let fechaFinalEnIngles = certificado.fecha_final;
			let fechaRegresoTrabajarEnIngles = certificado.fecha_regreso_trabajar;

			// Función para formatear la fecha
			function formatearFechaEnEspanol(fechaEnIngles) {
			let fecha = fechaEnIngles ? new Date(fechaEnIngles) : null;
			return fecha ? `${fecha.getDate().toString().padStart(2, '0')}/${(fecha.getMonth() + 1).toString().padStart(2, '0')}/${fecha.getFullYear()}` : '[no cargada]';
			}

			// Formateamos las fechas en español
			let fecha_inicio = formatearFechaEnEspanol(fechaInicioEnIngles);
			let fecha_final = formatearFechaEnEspanol(fechaFinalEnIngles);
			let fecha_regreso_trabajar = formatearFechaEnEspanol(fechaRegresoTrabajarEnIngles);

			return $(`
				<tr>
					<td>${certificado.nombre}</td>
					<td>${certificado.medico}</td>
					<td>${certificado.institucion}</td>
					<td>${fecha_inicio}</td>
					<td>${fecha_final}</td>
					<td>${fecha_regreso_trabajar}</td>
					<td>${certificado.matricula_nacional==null ? 'no cargada' : certificado.matricula_nacional}</td>
				</tr>`
			)
		}
	})

})
