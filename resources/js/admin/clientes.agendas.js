import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';
import Swal from 'sweetalert2';
import axios from 'axios';

$(function () {
  // Si la vista no tiene el div, salimos
  if (!$('#cliente-agenda').length) return;

  // CSRF global para axios
  const csrf = $('meta[name="csrf-token"]').attr('content');
  axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;
  axios.defaults.headers.common['Accept'] = 'application/json';
  // Obtener ID desde la URL actual
  const pathParts = window.location.pathname.split('/');
  const idCliente = pathParts[3]; // /admin/clientes/{id} → índice 3 es "{id}"

  if (!idCliente) {
        console.error('No se pudo obtener el ID del cliente de la URL.');
        return;
    }

  // Inicializamos FullCalendar
  const calendar = new Calendar($('#cliente-agenda')[0], {
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    locale: esLocale,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    height: 'auto',
    loading: isLoading => {
      if (isLoading) {
        Swal.fire({
          title: 'Cargando...',
          allowOutsideClick: false,
          showConfirmButton: false,
          willOpen: () => Swal.showLoading()
        });
      } else {
        Swal.close();
      }
    },
    events: (fetchInfo, successCallback, failureCallback) => {
      axios.get(`/admin/clientes/${idCliente}/get_agendas`, {
        params: {
          start: fetchInfo.startStr,
          end: fetchInfo.endStr
        }
      })
        .then(res => successCallback(res.data))
        .catch(err => {
          console.error('Error cargando agenda:', err);
          failureCallback(err);
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
    }
  });

  calendar.render();
});
