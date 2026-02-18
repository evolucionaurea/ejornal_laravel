import Tablas from '../classes/Tablas.js';

$(() => {

  new Tablas({
    controller: '/admin/medicamentos',
    get_path: '/busqueda',     // axios.post => /admin/medicamentos/busqueda
    delete_path: '/destroy',   // importante el "/"

    table: $('.tabla'),
    modulo_busqueda: $('[data-toggle="busqueda-filtros"]'),

    datatable_options: {
      order: [[0, 'asc']]
    },

    delete_message: '¿Seguro deseas borrar este medicamento?',

    render_row: medicamento => {
      const eliminado = !!medicamento.cliente_eliminado;
      const trClass = eliminado ? 'fila-cliente-eliminado' : '';
      const badge = eliminado ? ' <span class="badge-eliminado">Cliente Eliminado</span>' : '';

      const stock = Number(medicamento.stock_total || 0);

      return $(`
        <tr class="${trClass}" data-id="${medicamento.id}">
          <td>${medicamento.nombre}${badge}</td>
          <td class="text-center">${stock <= 0 ? '[sin stock]' : stock}</td>
          <td class="text-center">${medicamento.suministrados_total ?? 0}</td>
          <td class="acciones_tabla" scope="row">
            <a title="Ver" href="/admin/medicamentos/${medicamento.id}/edit">
              <i class="fas fa-pencil"></i>
            </a>

            <button data-toggle="delete" data-id="${medicamento.id}" title="Eliminar" type="button">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>
      `);
    }
  });

});
