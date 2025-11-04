(function ($, window, document) {
  'use strict';

  const AJAX_TIMEOUT_MS = 20000;
  const ALERT_COOLDOWN_MS = 4000;

  const inflight = {
    financiadores: null,
    diagnosticos: null,
    medicamentos: {},
    practicas: null
  };

  let ultimaAlerta = 0;
  let spinnerCssInyectado = false;

  function resolverUrl(u) {
    try {
      if (!u) return '/';
      if (/^https?:\/\//i.test(u)) return u;
      return new URL(u, window.location.origin).toString();
    } catch (e) {
      return u;
    }
  }

  function obtenerUrls() {
    const $f = $('#recetaForm');
    return {
      financiadores: resolverUrl($f.data('url-get-financiadores')),
      diagnosticos: resolverUrl($f.data('url-get-diagnosticos')),
      medicamentos: resolverUrl($f.data('url-get-medicamentos')),
      practicas: resolverUrl($f.data('url-get-practicas'))
    };
  }

  function mostrarErrorAjax(titulo, texto) {
    const ahora = Date.now();
    if (ahora - ultimaAlerta < ALERT_COOLDOWN_MS) return;
    ultimaAlerta = ahora;
    if (!window.Swal) {
      const s = document.createElement('script');
      s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
      s.onload = function () { Swal.fire({ icon: 'error', title: titulo || 'Error', text: texto || 'Ocurrió un error.' }); };
      document.head.appendChild(s);
      return;
    }
    Swal.fire({ icon: 'error', title: titulo || 'Error', text: texto || 'Ocurrió un error.' });
  }

  function isoAEs(iso) {
    if (!iso) return '';
    const m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(String(iso).trim());
    if (!m) return '';
    return m[3] + '/' + m[2] + '/' + m[1];
  }

  function esAISO(es) {
    if (!es) return '';
    const m = /^(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})$/.exec(String(es).trim());
    if (!m) return '';
    const dd = ('0' + m[1]).slice(-2), mm = ('0' + m[2]).slice(-2), yyyy = m[3];
    return `${yyyy}-${mm}-${dd}`;
  }

  function dividirNombreCompleto(full) {
    full = (full || '').trim().replace(/\s+/g, ' ');
    if (!full) return { apellido: '', nombre: '' };
    const idx = full.indexOf(' ');
    if (idx === -1) return { apellido: '', nombre: full };
    return { apellido: full.substring(0, idx), nombre: full.substring(idx + 1) };
  }

  function setearCamposPaciente(data) {
    const $nom = $('input[name="paciente[nombre]"]');
    const $ape = $('input[name="paciente[apellido]"]');
    const $dni = $('input[name="paciente[nroDoc]"]');
    const $eml = $('input[name="paciente[email]"]');
    const $tel = $('input[name="paciente[telefono]"]');
    const $fecVis = $('#paciente_fecha_visual');
    const $fecIso = $('input[name="paciente[fechaNacimiento]"]');

    if (data.nombre !== undefined) $nom.val(data.nombre || '');
    if (data.apellido !== undefined) $ape.val(data.apellido || '');
    if (data.dni !== undefined) $dni.val(String(data.dni || ''));
    if (data.email !== undefined) $eml.val(data.email || '');
    if (data.telefono !== undefined) $tel.val(data.telefono || '');

    if (data.fechaNacimiento !== undefined) {
      const iso = esAISO(data.fechaNacimiento) || data.fechaNacimiento;
      const es = isoAEs(iso);
      $fecIso.val(iso || '');
      $fecVis.val(es || '');
    }

    const $calle = $('input[name="domicilio[calle]"]');
    const $num   = $('input[name="domicilio[numero]"]');
    const $loc   = $('input[name="domicilio[localidad]"]');
    const $cp    = $('input[name="domicilio[cp]"]');
    if (data.calle !== undefined) $calle.val(data.calle || '');
    if (data.nro !== undefined)   $num.val(String(data.nro || ''));
    if (data.localidad !== undefined) $loc.val(data.localidad || '');
    if (data.cod_postal !== undefined) $cp.val(String(data.cod_postal || ''));
  }

  function hacerSelect2Chico($sel) {
    const $wrap = $sel.next('.select2');
    const $selection = $wrap.find('.select2-selection--single');
    $selection.css({
      height: '31px',
      'min-height': '31px',
      'border-radius': '.2rem',
      'border-color': '#ced4da',
      padding: '2px 8px'
    });
    $selection.find('.select2-selection__rendered').css({
      'line-height': '26px',
      'font-size': '.875rem'
    });
    $selection.find('.select2-selection__arrow').css({ height: '29px', right: '6px' });
  }

  function inyectarSpinnerCss() {
    if (spinnerCssInyectado) return;
    const css = `
      @keyframes miniSpin { 100% { transform: rotate(360deg);} }
      .mini-spinner-wrap{display:inline-block;margin-left:6px;vertical-align:middle;}
      .mini-spinner{display:inline-block; width:16px; height:16px; transform-origin:50% 50%; animation: miniSpin .9s linear infinite;}
      .mini-spinner circle{stroke-linecap:round;}
    `;
    const style = document.createElement('style');
    style.id = 'miniSpinnerCss';
    style.type = 'text/css';
    style.appendChild(document.createTextNode(css));
    document.head.appendChild(style);
    spinnerCssInyectado = true;
  }

  function svgSpinner() {
    inyectarSpinnerCss();
    return '' +
      '<span class="mini-spinner-wrap">' +
        '<svg class="mini-spinner" viewBox="0 0 50 50" aria-hidden="true" focusable="false">' +
          '<defs>' +
            '<linearGradient id="gradAzul" x1="0%" y1="0%" x2="100%" y2="0%">' +
              '<stop offset="0%" stop-color="#2196f3"/>' +
              '<stop offset="100%" stop-color="#81d4fa"/>' +
            '</linearGradient>' +
          '</defs>' +
          '<circle cx="25" cy="25" r="20" stroke="url(#gradAzul)" fill="none" stroke-width="5"/>' +
        '</svg>' +
      '</span>';
  }

  function cargarLoader($select) {
    const $lbl = $select.closest('.form-group').find('label').first();
    if ($lbl.length && !$lbl.find('.mini-spinner-wrap').length) $lbl.append(svgSpinner());
  }
  function quitarLoader($select) {
    $select.closest('.form-group').find('label .mini-spinner-wrap').remove();
  }

  function iniciarSelect2Nomina() {
    const $sel = $('#id_nomina');
    if (!$sel.length) return;

    const opts = $sel.find('option').toArray();
    if (opts.length > 1) {
      const first = opts.shift();
      opts.sort((a, b) => $(a).text().localeCompare($(b).text(), 'es', { sensitivity: 'base' }));
      $sel.empty().append(first).append(opts);
    }

    $sel.select2({
      width: '100%',
      placeholder: 'Seleccione…',
      allowClear: true,
      minimumInputLength: 0,
      dropdownParent: $(document.body)
    });
    hacerSelect2Chico($sel);

    function alCambiarNomina() {
      const val = $sel.val();
      if (!val) {
        setearCamposPaciente({
          nombre: '', apellido: '', dni: '',
          email: '', telefono: '', fechaNacimiento: '',
          calle: '', nro: '', localidad: '', cod_postal: ''
        });
        return;
      }
      const $opt = $sel.find('option:selected');
      if (!$opt.length) return;

      let full = $opt.data('nombre');
      if (!full) {
        full = ($opt.text() || '').trim()
          .replace(/\s+—\s+.*$/, '')
          .replace(/\(DNI:.*?\)/, '')
          .trim();
      }

      const partes = dividirNombreCompleto(full);
      setearCamposPaciente({
        nombre: partes.nombre,
        apellido: partes.apellido,
        dni: $opt.data('dni'),
        email: $opt.data('email'),
        telefono: $opt.data('telefono'),
        fechaNacimiento: $opt.data('fecha-nacimiento'),
        calle: $opt.data('calle'),
        nro: $opt.data('nro'),
        localidad: $opt.data('localidad'),
        cod_postal: $opt.data('cod-postal')
      });
    }

    $sel.on('select2:select', alCambiarNomina);
    $sel.on('change', alCambiarNomina);
    if ($sel.val()) alCambiarNomina();
  }


  function iniciarFinanciadores() {
  const $fin = $('#financiador');
  const $plan = $('#plan');
  if (!$fin.length) return;

  const urls = obtenerUrls();
  let cacheFin = [];

  const reinstanciarPlan = (disabled) => {
    const estabaAbierto = $plan.data('select2') && $plan.data('select2').isOpen && $plan.data('select2').isOpen();
    const valorActual = $plan.val();

    // Destruir instancia previa (si existía)
    if ($plan.data('select2')) {
      try { $plan.select2('destroy'); } catch (e) {}
    }

    // Asegurar estado habilitado/deshabilitado a nivel DOM (prop + atributo)
    if (disabled) {
      $plan.prop('disabled', true).attr('disabled', 'disabled');
    } else {
      $plan.prop('disabled', false).removeAttr('disabled');
    }

    // Re-instanciar Select2
    $plan.select2({
      width: '100%',
      placeholder: 'Plan…',
      allowClear: true,
      minimumInputLength: 0,
      dropdownParent: $(document.body)
    });
    hacerSelect2Chico($plan);

    // Restaurar valor si aplica
    if (!disabled && valorActual) {
      $plan.val(valorActual).trigger('change');
    }

    // Si queremos mostrarlo inmediatamente cuando queda habilitado:
    if (!disabled && estabaAbierto) {
      $plan.select2('open');
    }
  };

  // Estado inicial: vacío y deshabilitado
  $plan.empty().append('<option value=""></option>');
  reinstanciarPlan(true);

  $fin.select2({
    width: '100%',
    placeholder: 'Financiador…',
    allowClear: true,
    minimumInputLength: 1,
    ajax: {
      delay: 250,
      transport: (params, success, failure) => {
        if (inflight.financiadores && inflight.financiadores.readyState !== 4) inflight.financiadores.abort();
        const opts = $.extend(true, {}, params, { timeout: AJAX_TIMEOUT_MS, url: urls.financiadores });
        const $forLoader = $fin;
        cargarLoader($forLoader);
        inflight.financiadores = $.ajax(opts)
          .done(success)
          .fail(jq => {
            if (jq && (jq.statusText === 'abort' || jq.readyState === 0)) return;
            mostrarErrorAjax('Financiadores', (jq.responseJSON && jq.responseJSON.message) || 'No se pudo cargar el listado.');
            failure(jq);
          })
          .always(() => quitarLoader($forLoader));
        return inflight.financiadores;
      },
      processResults: (data, params) => {
        cacheFin = (data && data.results) ? data.results : [];
        const term = (params && params.term ? String(params.term).toLowerCase() : '').trim();
        let results = cacheFin;
        if (term) results = cacheFin.filter(x => String(x.text).toLowerCase().indexOf(term) !== -1);
        return { results };
      },
      cache: true
    },
    dropdownParent: $(document.body)
  });
  hacerSelect2Chico($fin);

  // Al seleccionar financiador: poblar planes y habilitar
  $fin.on('select2:select', function () {
    const sel = $fin.select2('data')[0];

    // Limpiar opciones anteriores
    $plan.empty().append('<option value=""></option>');

    if (sel && sel.raw && Array.isArray(sel.raw.planes) && sel.raw.planes.length) {
      sel.raw.planes.forEach(p => $plan.append('<option value="'+p.id+'">'+p.nombre+'</option>'));
      reinstanciarPlan(false);
      // Opcional: abrir automáticamente para que el usuario elija
      $plan.select2('open');
    } else {
      reinstanciarPlan(true);
    }
  });

  // Al limpiar financiador: dejar plan vacío y deshabilitado
  $fin.on('select2:clear', function () {
    $plan.empty().append('<option value=""></option>');
    reinstanciarPlan(true);
  });
}


  function iniciarDiagnosticos() {
    const $diag = $('#diag_search');
    const $txt  = $('#diagnostico');
    const $cod  = $('#diagnostico_codigo');
    if (!$diag.length) return;

    const urls = obtenerUrls();
    $diag.select2({
      width: '100%',
      placeholder: 'Buscar diagnóstico…',
      allowClear: true,
      minimumInputLength: 3,
      ajax: {
        delay: 300,
        transport: (params, success, failure) => {
          if (inflight.diagnosticos && inflight.diagnosticos.readyState !== 4) inflight.diagnosticos.abort();
          const opts = $.extend(true, {}, params, { timeout: AJAX_TIMEOUT_MS, url: urls.diagnosticos });
          const $sel = $diag;
          cargarLoader($sel);
          inflight.diagnosticos = $.ajax(opts)
            .done(success)
            .fail(jq => {
              if (jq && (jq.statusText === 'abort' || jq.readyState === 0)) return;
              mostrarErrorAjax('Diagnósticos', (jq.responseJSON && jq.responseJSON.message) || 'No se pudo buscar diagnósticos.');
              failure(jq);
            })
            .always(() => quitarLoader($sel));
          return inflight.diagnosticos;
        },
        processResults: data => data,
        cache: true
      },
      dropdownParent: $(document.body)
    });
    hacerSelect2Chico($diag);

    $diag.on('select2:select', function () {
      const item = $diag.select2('data')[0];
      if (!item) return;
      $cod.val(item.id || '');
      $txt.val(item.text || '');
    });
    $diag.on('select2:clear', function () { $cod.val(''); });
  }

  function coberturaParaMedicamentos() {
    const idFin  = $('#financiador').val();
    const planId = $('#plan').val();
    const dni    = $('input[name="paciente[nroDoc]"]').val();
    const cred   = $('input[name="cobertura[credencial]"]').val();
    const q = {};
    if (idFin) q.idFinanciador = idFin;
    if (planId) q.planid = planId;
    if (dni) q.afiliadoDni = dni;
    if (cred) q.afiliadoCredencial = cred;
    return q;
  }

  function adjuntarSelect2Medicamento($fila, index) {
    const $sel = $fila.find('.sel-medicamento');
    const $reg = $fila.find('.regno');
    const $pre = $fila.find('.presentacion');
    const $nom = $fila.find('.nombre');
    const $dro = $fila.find('.droga');
    const $dup = $fila.find('.duplicado');

    const urls = obtenerUrls();

    $sel.select2({
      width: '100%',
      placeholder: 'Buscar medicamento…',
      allowClear: true,
      minimumInputLength: 2,
      ajax: {
        delay: 250,
        transport: (params, success, failure) => {
          if (inflight.medicamentos[index] && inflight.medicamentos[index].readyState !== 4) {
            inflight.medicamentos[index].abort();
          }
          const extra = coberturaParaMedicamentos();
          const dataFn = params.data || function(){ return {}; };
          const data = typeof dataFn === 'function' ? dataFn(params) : {};
          const query = $.param(Object.assign({}, data, extra));
          const opts = $.extend(true, {}, params, {
            timeout: AJAX_TIMEOUT_MS,
            url: urls.medicamentos + (urls.medicamentos.indexOf('?') === -1 ? '?' : '&') + query
          });

          const $forLoader = $sel;
          cargarLoader($forLoader);

          inflight.medicamentos[index] = $.ajax(opts)
            .done(success)
            .fail(jq => {
              if (jq && (jq.statusText === 'abort' || jq.readyState === 0)) return;
              mostrarErrorAjax('Medicamentos', (jq.responseJSON && jq.responseJSON.message) || 'No se pudo buscar medicamentos.');
              failure(jq);
            })
            .always(() => quitarLoader($forLoader));

          return inflight.medicamentos[index];
        },
        data: params => ({ q: params.term || '', page: params.page || 1 }),
        processResults: data => ({
          results: data.results || [],
          pagination: { more: data.pagination && data.pagination.more }
        }),
        cache: true
      },
      dropdownParent: $(document.body),
      templateResult: function (item) {
        if (!item.id) return item.text;
        const r = item.raw || {};
        const extra = [];
        if (r.tieneCobertura)   extra.push('Cobertura');
        if (r.psicofarmaco)     extra.push('Psicofármaco');
        if (r.estupefaciente)   extra.push('Estupefaciente');
        if (r.ventaControlada)  extra.push('Venta controlada');
        if (r.hiv)              extra.push('HIV');
        return $('<span>' + item.text + (extra.length ? ' <small class="text-muted">(' + extra.join(', ') + ')</small>' : '') + '</span>');
      }
    });
    hacerSelect2Chico($sel);

    $sel.on('select2:select', function () {
      const item = $sel.select2('data')[0];
      const r = (item && item.raw) ? item.raw : {};
      $reg.val(r.regNo || '');
      $pre.val(r.presentacion || '');
      $nom.val(r.nombreProducto || '');
      $dro.val(r.nombreDroga || '');
      if (r.requiereDuplicado === true) $dup.prop('checked', true);
    });
  }

  function iniciarRepetidorMedicamentos() {
    let idx = 1;
    const $wrap = $('#medsWrapper');
    const $btnAdd = $('#btnAddMed');
    if (!$wrap.length || !$btnAdd.length) return;

    function plantillaFila(i) {
      return `
      <div class="med-row border rounded p-2 mb-3">
        <div class="form-row">
          <div class="form-group col-md-4 mb-2">
            <label class="mb-1 text-muted small">Buscar medicamento</label>
            <select class="form-control form-control-sm sel-medicamento" style="width:100%"></select>
          </div>
          <div class="form-group col-md-2 mb-2">
            <label class="mb-1 text-muted small">Cantidad</label>
            <input type="number" min="1" class="form-control form-control-sm" name="medicamentos[${i}][cantidad]" required>
          </div>
          <div class="form-group col-md-3 mb-2">
            <label class="mb-1 text-muted small">Reg. Nº</label>
            <input type="text" class="form-control form-control-sm regno" name="medicamentos[${i}][regNo]" placeholder="Ej: 20095">
          </div>
          <div class="form-group col-md-3 mb-2">
            <label class="mb-1 text-muted small">Presentación</label>
            <input type="text" class="form-control form-control-sm presentacion" name="medicamentos[${i}][presentacion]" placeholder="comp. blister x 10">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4 mb-2">
            <label class="mb-1 text-muted small">Nombre</label>
            <input type="text" class="form-control form-control-sm nombre" name="medicamentos[${i}][nombre]" placeholder="TAFIROL">
          </div>
          <div class="form-group col-md-4 mb-2">
            <label class="mb-1 text-muted small">Droga</label>
            <input type="text" class="form-control form-control-sm droga" name="medicamentos[${i}][nombreDroga]" placeholder="paracetamol">
          </div>
          <div class="form-group col-md-3 mb-2">
            <label class="mb-1 text-muted small">Tratamiento (días)</label>
            <input type="number" min="0" class="form-control form-control-sm" name="medicamentos[${i}][tratamiento]" placeholder="0">
          </div>
          <div class="form-group col-md-1 d-flex align-items-end mb-2">
            <button type="button" class="btn btn-sm btn-outline-danger btn-del-med">×</button>
          </div>
        </div>
        <div class="form-group mb-2">
          <label class="mb-1 text-muted small">Posología</label>
          <input type="text" class="form-control form-control-sm" name="medicamentos[${i}][posologia]" placeholder="1 comprimido cada 8 horas">
        </div>
        <div class="form-group mb-2">
          <label class="mb-1 text-muted small">Indicaciones / Observaciones</label>
          <textarea class="form-control form-control-sm" name="medicamentos[${i}][indicaciones]" rows="2" placeholder="No tomar con alcohol"></textarea>
        </div>
        <div class="custom-control custom-checkbox custom-control-inline">
          <input type="checkbox" class="custom-control-input duplicado" id="dup${i}" name="medicamentos[${i}][forzarDuplicado]" value="1">
          <label class="custom-control-label" for="dup${i}">Requiere duplicado</label>
        </div>
      </div>`;
    }

    function alternarBotonesBorrar() {
      const $rows = $wrap.children('.med-row');
      $rows.find('.btn-del-med').prop('disabled', $rows.length <= 1);
    }

    $btnAdd.on('click', function () {
      const $row = $(plantillaFila(idx));
      $wrap.append($row);
      adjuntarSelect2Medicamento($row, idx);
      idx++;
      alternarBotonesBorrar();
    });

    $wrap.on('click', '.btn-del-med', function () {
      const $row = $(this).closest('.med-row');
      if ($wrap.children().length > 1) {
        $row.remove();
        alternarBotonesBorrar();
      }
    });

    adjuntarSelect2Medicamento($wrap.find('.med-row').first(), 0);
    alternarBotonesBorrar();
  }

  function iniciarPracticas() {
    const $pr = $('#practica_search');
    const $chips = $('#practicasList');
    const $hidden = $('#practicasHidden');
    if (!$pr.length) return;

    const urls = obtenerUrls();
    $pr.select2({
      width: '100%',
      placeholder: 'Buscar práctica…',
      allowClear: true,
      minimumInputLength: 2,
      ajax: {
        delay: 300,
        transport: (params, success, failure) => {
          if (inflight.practicas && inflight.practicas.readyState !== 4) inflight.practicas.abort();
          const opts = $.extend(true, {}, params, { timeout: AJAX_TIMEOUT_MS, url: urls.practicas });
          const $sel = $pr;
          cargarLoader($sel);
          inflight.practicas = $.ajax(opts)
            .done(success)
            .fail(jq => {
              if (jq && (jq.statusText === 'abort' || jq.readyState === 0)) return;
              mostrarErrorAjax('Prácticas', (jq.responseJSON && jq.responseJSON.message) || 'No se pudo buscar prácticas.');
              failure(jq);
            })
            .always(() => quitarLoader($sel));
          return inflight.practicas;
        },
        data: params => {
          const term = params.term || '';
          const data = { page: params.page || 1 };
          const mTipo = term.match(/tipo:([^ ]+)/i);
          const mCat  = term.match(/cat:([^ ]+)/i);
          if (mTipo) data.tipo = mTipo[1];
          if (mCat)  data.categoria = mCat[1];
          const clean = term.replace(/tipo:[^ ]+/ig,'').replace(/cat:[^ ]+/ig,'').trim();
          if (clean) data.search = clean;
          return data;
        },
        processResults: data => ({
          results: data.results || [],
          pagination: { more: data.pagination && data.pagination.more }
        }),
        cache: true
      },
      dropdownParent: $(document.body)
    });
    hacerSelect2Chico($pr);

    function agregarChip(it) {
      const id = it.id;
      if ($hidden.find('input[value="'+id+'"]').length) return;

      const $in = $('<input type="hidden" name="practicas[]">').val(id);
      $hidden.append($in);

      const $chip = $(
        '<span class="badge badge-primary d-inline-flex align-items-center mr-2 mb-2" data-id="'+id+'" style="font-size:.8rem;">' +
          '<span class="mr-2">'+it.text+'</span>' +
          '<button type="button" class="btn btn-sm btn-light py-0 px-1 quitar-chip" aria-label="Quitar" style="line-height:1;">×</button>' +
        '</span>'
      );
      $chips.append($chip);
    }

    $pr.on('select2:select', function () {
      const it = $pr.select2('data')[0];
      if (!it) return;
      agregarChip(it);
      $pr.val(null).trigger('change');
    });

    $chips.on('click', '.quitar-chip', function () {
      const $chip = $(this).closest('[data-id]');
      const id = $chip.data('id');
      $hidden.find('input[name="practicas[]"][value="'+id+'"]').remove();
      $chip.remove();
    });
  }

  function iniciarFechaNacimientoES() {
    const $iso = $('input[name="paciente[fechaNacimiento]"]');
    if (!$iso.length) return;

    const es = isoAEs($iso.val());
    const $vis = $('<input type="text" id="paciente_fecha_visual" class="form-control form-control-sm" placeholder="DD/MM/AAAA" readonly>');
    $vis.val(es);
    $iso.after($vis).hide();

    if ($.datepicker && !$.datepicker.regional['es']) {
      $.datepicker.regional['es'] = {
        closeText: 'Cerrar', prevText: 'Anterior', nextText: 'Siguiente', currentText: 'Hoy',
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        weekHeader: 'Sm', dateFormat: 'dd/mm/yy', firstDay: 1, isRTL: false, showMonthAfterYear: false, yearSuffix: ''
      };
      $.datepicker.setDefaults($.datepicker.regional['es']);
    }

    if ($.fn.datepicker) {
      $vis.datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1900:+0',
        dateFormat: 'dd/mm/yy',
        onClose: function (valor) {
          const iso = esAISO(valor);
          $iso.val(iso);
        }
      });
    }

    $vis.on('focus click', function () {
      if ($.fn.datepicker) $(this).datepicker('show');
    });
  }

  function iniciarConversionFechaEnvio() {
    $('#recetaForm').on('submit', function () {
      const $vis = $('#paciente_fecha_visual');
      const $iso = $('input[name="paciente[fechaNacimiento]"]');
      if ($vis.length && $iso.length) $iso.val(esAISO($vis.val()));
    });
  }

  function boot() {
    $.ajaxSetup({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      timeout: AJAX_TIMEOUT_MS
    });

    iniciarFechaNacimientoES();
    iniciarConversionFechaEnvio();

    let intentos = 0;
    const iv = setInterval(function () {
      intentos++;
      if (typeof $.fn.select2 === 'function') {
        clearInterval(iv);
        iniciarSelect2Nomina();
        iniciarFinanciadores();
        iniciarDiagnosticos();
        iniciarRepetidorMedicamentos();
        iniciarPracticas();
      } else if (intentos > 60) {
        clearInterval(iv);
        iniciarRepetidorMedicamentos();
      }
    }, 100);
  }

  $(boot);

})(jQuery, window, document);
