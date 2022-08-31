import Tablas from '../Tablas.js';

$(()=>{

	new Tablas({
		controller:'/clientes/ausentismos',
		table:$('.tabla_ausentismos'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:false},
		render_row:ausentismo=>{
			///console.log(ausentismo)
			return $(`
				<tr>
					<td>${ausentismo.nombre}</td>
					<td>${ausentismo.nombre_ausentismo}</td>
					<td>${ausentismo.fecha_inicio}</td>
					<td>${ausentismo.fecha_final}</td>
					<td>${ausentismo.fecha_regreso_trabajar==null ? '[no cargada]' : ausentismo.fecha_regreso_trabajar}</td>
				</tr>`
			)
		}
	})

})
