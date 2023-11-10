import Tablas from '../classes/Tablas.js';

$(()=>{
    new Tablas({
        controller:'/empleados/comunicaciones_livianas',
		get_path:'/busqueda',
		table:$('.tabla_comunicaciones_livianas_listado'),
		modulo_busqueda:$('[data-toggle="busqueda-fecha"]'),
		datatable_options:{order:[[ 3, "desc" ]]},
		render_row:comunicacion=>{

            // Formatear la fecha
			 const fecha = new Date(comunicacion.created_at);
			 const dia = fecha.getDate().toString().padStart(2, '0');
			 const mes = (fecha.getMonth() + 1).toString().padStart(2, '0'); // Los meses van de 0 a 11
			 const anio = fecha.getFullYear();

            return $(`
            <tr>
            <td>${comunicacion.nombre}</td>
            <td>${(comunicacion.email == null) ? 'No fue cargado' : comunicacion.email}</td>
            <td>${comunicacion.tipo}</td>
            <td>
            ${dia}/${mes}/${anio}
            </td>
            <td>
            <span class="tag_ejornal tag_ejornal_${comunicacion.estado==1?'success':'danger'}">${comunicacion.estado==1?'Activo':'Inactivo'}</span>
            </td>
            </tr>`
			)
		}
	})

})
