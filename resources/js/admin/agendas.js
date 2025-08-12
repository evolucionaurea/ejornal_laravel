import { Calendar }      from '@fullcalendar/core';
import dayGridPlugin     from '@fullcalendar/daygrid';
import timeGridPlugin    from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale          from '@fullcalendar/core/locales/es';
import Swal              from 'sweetalert2';
import axios from 'axios';

$(function(){
  // CSRF para todas las peticiones AJAX
  const csrf = $('meta[name="csrf-token"]').attr('content');
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': csrf,
      'Accept':       'application/json'
    }
  });

  // Loader global con SweetAlert
  $(document)
    .ajaxStart(() => {
      Swal.fire({
        title: 'Trabajando...',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => Swal.showLoading()
      });
    })
    .ajaxStop(() => {
      Swal.close();
    });

  // Inicializar Select2
  $('#cal-user, #cal-cliente, #blk-user, #blk-cliente')
    .select2({ width: '100%' });

  // FullCalendar config
const calendar = new Calendar($('#calendar')[0], {
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
  initialView: 'dayGridWeek',
  locale: esLocale,
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,dayGridWeek'
  },
  loading: isLoading => {
    if (isLoading) {
      Swal.fire({
        title: 'Trabajando...',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => Swal.showLoading()
      });
    } else {
      Swal.close();
    }
  },
  events: (fetchInfo, successCallback, failureCallback) => {
    const u = $('#cal-user').val();
    const c = $('#cal-cliente').val();

    axios.get('/admin/agenda/events', {
      headers: {
        'X-CSRF-TOKEN': csfr,
        'Accept': 'application/json'
      },
      params: {
        start: fetchInfo.startStr,
        end: fetchInfo.endStr,
        cal_user: u || null,
        cal_cliente: c || null
      }
    })
    .then(response => {
      successCallback(response.data);
    })
    .catch(error => {
      console.error('Error cargando eventos:', error);
      failureCallback(error);
    });
  },
  eventClick: info => {
    const p = info.event.extendedProps;
    Swal.fire({
      title: `
      <div class="text-center p-0 m-0">
      ${info.event.title}
      </div>`,
      html: `
        <div class="text-left">
          <div class="mb-2"><strong>Usuario:</strong> ${p.usuario}</div>
          <div class="mb-2"><strong>Trabajador:</strong> ${p.trabajador}</div>
          <div class="mb-2"><strong>Comentarios:</strong><br>${p.comentarios}</div>
        </div>
      `,
      icon: 'info',
      customClass: {
        popup: 'text-left'
      }
    });
  },
  height: 'auto'
});

  calendar.render();

  // Refrescar calendario al cambiar filtros
  $('#cal-user, #cal-cliente').on('change', () => {
    calendar.refetchEvents();
  });

  // ===== BLOQUEOS =====

  function makeRow(start = '', end = '', id = null) {
    return `
      <div class="form-row bloque mb-2"${id ? ` data-id="${id}"` : ''}>
        <div class="col-5">
          <input type="time" class="form-control form-control-sm start" value="${start}">
        </div>
        <div class="col-5">
          <input type="time" class="form-control form-control-sm end" value="${end}">
        </div>
        <div class="col-2 text-center">
          <button class="btn-ejornal btn-danger btn-eliminar-bloque">&times;</button>
        </div>
      </div>
    `;
  }

  function loadBloqueos() {
    const user = $('#blk-user').val();
    const cli = $('#blk-cliente').val();

    if (!user || !cli) {
      $('#restricciones-container').hide();
      return;
    }

    $('#restricciones-container').show();

    // Limpiar UI
    $('#restricciones-container .card').each(function() {
      $(this).find('.bloques').empty();
    });

    // AJAX GET bloqueos existentes
    $.getJSON('/admin/agendas/bloqueos', { user_id: user, cliente_id: cli })
      .done(data => {
        for (const [dia, arr] of Object.entries(data)) {
          const body = $(`#restricciones-container .card[data-dia=${dia}] .bloques`);
          arr.forEach(b => body.append(makeRow(b.start, b.end, b.id)));
          body.append(makeRow());
        }
      })
      .fail(() => {
        Swal.fire('Error', 'No se pudieron cargar los bloqueos', 'error');
      });
  }

  // Delegación de eventos en el contenedor principal
  $('#restricciones-container')
    .on('click', '.add-bloque', function() {
      const body = $(this).closest('.card').find('.bloques');
      body.append(makeRow());
    })
    .on('click', '.btn-guardar', function() {
      const card = $(this).closest('.card');
      const dia = card.data('dia');
      const user = $('#blk-user').val();
      const cli = $('#blk-cliente').val();

      const bloqueos = [];
      card.find('.bloque').each(function() {
        const s = $(this).find('.start').val();
        const e = $(this).find('.end').val();
        if (s && e) bloqueos.push({ start: s, end: e });
      });

      if (bloqueos.length === 0) {
        Swal.fire('Advertencia', 'Debe agregar al menos un bloqueo', 'warning');
        return;
      }

      $.ajax({
        url: '/admin/agendas/bloqueos',
        method: 'POST',
        contentType: 'application/json; charset=utf-8',
        data: JSON.stringify({ user_id: user, cliente_id: cli, dia: dia, bloqueos: bloqueos })
      })
      .done(() => Swal.fire('Guardado', 'Bloqueos actualizados', 'success'))
      .fail(() => Swal.fire('Error', 'No se pudo guardar', 'error'));
    });

  // Delegación global para eliminación (independiente de cómo se genera el HTML)
  $(document).on('click', '.btn-eliminar-bloque', function() {
    const $fila = $(this).closest('.bloque');
    const bloqueoId = $fila.attr('data-id');
    console.log('🧹 click eliminar, id =', bloqueoId);

    if (!bloqueoId) {
      return $fila.remove();
    }

    Swal.fire({
      title: '¿Seguro?',
      text: 'Se eliminará este bloqueo permanentemente.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (!result.isConfirmed) return;

      $.ajax({
        url: `/admin/agendas/bloqueos/${bloqueoId}`,
        type: 'DELETE'
      })
      .done(() => {
        Swal.fire('Eliminado', 'El bloqueo ha sido eliminado', 'success');
        $fila.remove();
      })
      .fail(() => {
        Swal.fire('Error', 'No se pudo eliminar', 'error');
      });
    });
  });

  // Cargar bloqueos al cambiar selects
  $('#blk-user, #blk-cliente').on('change', loadBloqueos);
});
