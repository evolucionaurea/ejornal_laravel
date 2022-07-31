import Tablas from '../Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/ausentismos',
		table:$('.tabla_ausentismos'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:false},
		delete_message:'¿Seguro deseas borrar este ausentismo?',
		render_row:ausentismo=>{
			return $(`
				<tr>
					<td>${ausentismo.nombre}</td>
					<td>${ausentismo.dni==null ? 'no cargado' : ausentismo.dni}</td>
					<td>${ausentismo.sector==null ? 'no cargado' : ausentismo.sector}</td>
					<td>${ausentismo.nombre_ausentismo}</td>
					<td>${ausentismo.fecha_inicio}</td>
					<td>${ausentismo.fecha_final}</td>
					<td>${ausentismo.fecha_regreso_trabajar==null ? 'no cargada' : ausentismo.fecha_regreso_trabajar}</td>

					<td class="acciones_tabla" scope="row">

						<a title="Comunicaciones" href="comunicaciones/${ausentismo.id}">
							<i title="Comunicaciones" class="fas fa-bullhorn"></i>
						</a>

						<a title="Documentacion" href="documentaciones/${ausentismo.id}">
							<i title="Documentacion" class="fas fa-files-medical"></i>
						</a>

						<a title="Historial" href="ausentismos/${ausentismo.id_trabajador}">
							<i title="Historial" class="fas fa-book"></i>
						</a>

						<a title="Editar" href="ausentismos/${ausentismo.id}/edit">
							<i class="fas fa-pencil"></i>
						</a>
						<button data-toggle="delete" data-id="${ausentismo.id}" title="Eliminar" type="submit">
							<i class="fas fa-trash"></i>
						</button>

					</td>
				</tr>`
			)
		}
	})

})
