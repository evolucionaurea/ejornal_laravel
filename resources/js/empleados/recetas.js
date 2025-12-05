(function (window, document) {
  'use strict';

  var $ = window.jQuery || null;

  function q(sel, ctx) { return (ctx || document).querySelector(sel); }
  function qa(sel, ctx) { return Array.from((ctx || document).querySelectorAll(sel)); }

  // ================== SweetAlert helper ==================
  function ensureSwal(cb) {
    if (window.Swal) return cb();
    var s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    s.onload = cb;
    document.head.appendChild(s);
  }

  function showToast(title, icon) {
    if (!window.Swal) {
      window.alert(title || 'Operación realizada.');
      return;
    }
    window.Swal.fire({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2500,
      icon: icon || 'success',
      title: title
    });
  }

  function showLoadingSwal(text) {
    if (!window.Swal) return;
    window.Swal.fire({
      title: text || 'Cargando...',
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: function () {
        window.Swal.showLoading();
      },
      showConfirmButton: false
    });
  }

  // ================== ANULAR RECETA ==================
  function bindAnularButtons() {
    qa('.js-anular').forEach(function (btn) {
      if (btn.__bound) return;
      btn.__bound = true;

      btn.addEventListener('click', function () {
        var row = btn.closest('[data-id]');
        if (!row) row = document.getElementById('receta-actions');

        var id   = row ? row.getAttribute('data-id') : (btn.getAttribute('data-id') || '');
        var url  = row ? row.getAttribute('data-url-anular') : btn.getAttribute('data-url-anular');
        var csrf = row ? row.getAttribute('data-csrf') : btn.getAttribute('data-csrf');

        if (!url || !csrf || !id) return;

        window.Swal.fire({
          title: 'Anular receta',
          text: '¿Confirmás la anulación de la receta #' + id + '?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, anular',
          cancelButtonText: 'Cancelar'
        }).then(function (res) {
          if (!res.isConfirmed) return;

          btn.disabled = true;
          showLoadingSwal('Anulando receta...');

          fetch(url, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': csrf,
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
            .then(function (r) {
              return r.json()
                .catch(function () { return {}; })
                .then(function (j) { return { ok: r.ok, status: r.status, body: j }; });
            })
            .then(function (res) {
              if (window.Swal.isLoading()) window.Swal.close();

              if (res.ok && res.body && res.body.ok) {
                // === LISTADO ===
                var tr = q('#receta-' + id);
                if (tr) {
                  var badge = tr.querySelector('td:nth-child(6) .badge');
                  if (badge) {
                    badge.className = 'badge p-2 badge-danger';
                    badge.textContent = 'Anulada';
                  }
                  var b = tr.querySelector('.js-anular');
                  if (b) {
                    b.disabled = true;
                    b.classList.remove('btn-danger');
                    b.classList.add('btn-secondary');
                    b.textContent = 'Anulada';
                  }
                }

                // === SHOW ===
                var actions = document.getElementById('receta-actions');
                if (actions) {
                  var tarjeta = actions.closest('.tarjeta');
                  if (tarjeta) {
                    var headerBadge = tarjeta.querySelector('.badge');
                    if (headerBadge) {
                      headerBadge.className = 'badge badge-danger';
                      headerBadge.textContent = 'Anulada';
                    }
                  }

                  btn.disabled = true;
                  btn.classList.remove('btn-danger');
                  btn.classList.add('btn-secondary');
                  btn.textContent = 'Anulada';
                }

                showToast(res.body.message || 'Receta anulada correctamente', 'success');
              } else {
                var msg = (res.body && (res.body.message || res.body.mensaje)) || 'No se pudo anular la receta.';
                window.Swal.fire({ icon: 'error', title: 'Error', text: msg });
                btn.disabled = false;
              }
            })
            .catch(function () {
              if (window.Swal.isLoading()) window.Swal.close();
              window.Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo anular la receta.' });
              btn.disabled = false;
            });
        });
      });
    });
  }

  // ================== CARGA AJAX (filtros + paginación) ==================
  function loadRecetasHtml(url) {
    var container = q('#recetas-container');
    if (!container || !url) return;

    showLoadingSwal('Cargando recetas...');

    fetch(url, {
      method: 'GET',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'text/html'
      }
    })
      .then(function (res) { return res.text(); })
      .then(function (html) {
        container.innerHTML = html;

        // Actualizar URL del navegador sin recargar
        try {
          window.history.replaceState({}, '', url);
        } catch (e) {}

        // Re-bind sobre el nuevo HTML
        bindAnularButtons();
        bindAjaxPagination();

        if (window.Swal.isLoading()) window.Swal.close();
      })
      .catch(function () {
        if (window.Swal.isLoading()) window.Swal.close();
        if (window.Swal) {
          window.Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar las recetas. Intentá nuevamente.'
          });
        } else {
          window.alert('Error al cargar recetas.');
        }
      });
  }

  function bindFiltersAjax() {
    var form = q('#form-filtros-recetas');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var action = form.getAttribute('action') || window.location.href;
      var fd     = new FormData(form);
      var params = new URLSearchParams(fd).toString();
      var url    = action + (action.indexOf('?') === -1 ? '?' : '&') + params;

      loadRecetasHtml(url);
    });
  }

  function bindAjaxPagination() {
    var container = q('#recetas-container');
    if (!container) return;

    qa('.pagination a', container).forEach(function (link) {
      if (link.__bound) return;
      link.__bound = true;

      link.addEventListener('click', function (e) {
        e.preventDefault();
        var url = this.getAttribute('href');
        if (!url) return;
        loadRecetasHtml(url);
      });
    });
  }

  // ================== Datepicker (jQuery UI) ==================
  function initDatePickers() {
    // Usamos jQuery UI ya cargado por el layout (jquery-ui.js)
    if (!$ || !$.fn || !$.fn.datepicker) return;

    if ($.datepicker && $.datepicker.regional && $.datepicker.regional['es']) {
      $.datepicker.setDefaults($.datepicker.regional['es']);
    }

    $('.js-datepicker').each(function () {
      var $input = $(this);
      if ($input.data('jsDatepickerInit')) return;

      $input.data('jsDatepickerInit', true);

      // El id del campo hidden donde se guarda el valor en formato Y-m-d
      var hiddenId = $input.data('hidden-target');
      var hasAlt   = hiddenId && $('#' + hiddenId).length;

      var opts = {
        // Formato que ve el usuario: dd-mm-yy => 04-12-2025
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true
      };

      // Si hay hidden asociado, usamos altField/altFormat
      if (hasAlt) {
        opts.altField  = '#' + hiddenId;      // este es el <input hidden>
        opts.altFormat = 'yy-mm-dd';          // formato para el backend (Y-m-d)
      }

      $input.datepicker(opts);

      // Inicializo valor visible a partir del hidden (si viene en la URL)
      if (hasAlt) {
        var isoVal = $('#' + hiddenId).val(); // ej: 2025-12-04
        if (isoVal) {
          try {
            var dateObj = $.datepicker.parseDate('yy-mm-dd', isoVal);
            $input.datepicker('setDate', dateObj); // muestra 04-12-2025
          } catch (e) {
            // si no se puede parsear, lo ignoramos
          }
        }
      }
    });
  }


  // ================== Select2 (Trabajador / Cliente) ==================
  function initSelect2Filters() {
    // Usamos select2.min.js cargado por el layout
    if (!$ || !$.fn || !$.fn.select2) return;

    $('.js-select2-nomina, .js-select2-cliente').each(function () {
      var $sel = $(this);
      if ($sel.data('jsSelect2Init')) return;

      $sel.data('jsSelect2Init', true);

      $sel.select2({
        width: '100%',
        placeholder: 'Todos',
        allowClear: true
      });
    });
  }

  // ================== BOOT ==================
  function boot() {
    ensureSwal(function () {
      bindAnularButtons();
      bindFiltersAjax();
      bindAjaxPagination();
      initDatePickers();
      initSelect2Filters();
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }

})(window, document);
