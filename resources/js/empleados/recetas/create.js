(function ($, window, document) {
  'use strict';

  const AJAX_TIMEOUT_MS = 20000;

  // =========================================================
  // Modal simple
  // =========================================================
  let modalCss = false;
  function modalCSS() {
    if (modalCss) return;
    modalCss = true;
    const css = `
      .mm-back{position:fixed;inset:0;background:rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;z-index:9999}
      .mm{background:#fff;border-radius:12px;box-shadow:0 15px 50px rgba(0,0,0,.25);max-width:640px;width:94%;padding:18px 20px}
      .mm h3{margin:0 0 10px;font-size:18px;font-weight:800;color:#111}
      .mm p{margin:0 0 10px;font-size:14px;color:#333}
      .mm .row{display:flex;justify-content:flex-end;gap:8px;margin-top:14px}
      .mm .btn{border:0;border-radius:8px;padding:9px 14px;font-size:14px;cursor:pointer}
      .mm .b1{background:#2563eb;color:#fff}
      .mm ul.mm-list{list-style:none;margin:6px 0 0;padding:0}
      .mm ul.mm-list li{display:flex;align-items:flex-start;gap:10px; padding:8px 10px; border-radius:8px; background:#f8fafc; margin-bottom:8px; font-size:14px; color:#0f172a}
      .mm ul.mm-list li .dot{width:8px;height:8px;border-radius:999px;background:#2563eb; margin-top:6px; flex:0 0 8px}
      @media (prefers-color-scheme: dark){
        .mm{background:#0f172a;color:#e2e8f0}
        .mm h3{color:#e5e7eb}
        .mm p{color:#cbd5e1}
        .mm ul.mm-list li{background:#0b1221;color:#e2e8f0}
        .mm ul.mm-list li .dot{background:#60a5fa}
      }`;
    document.head.appendChild(Object.assign(document.createElement('style'), { textContent: css }));
  }

  function modal({ title = 'Atención', html = '<p>Ocurrió un error.</p>' } = {}) {
    modalCSS();
    const $b = $('<div class="mm-back" role="dialog" aria-modal="true"></div>');
    const $m = $(`<div class="mm"><h3>${title}</h3>${html}<div class="row"><button class="btn b1">Aceptar</button></div></div>`);
    $b.append($m).appendTo(document.body);
    const close = () => $b.remove();
    $b.on('click', e => { if (e.target === $b[0]) close(); });
    $m.find('.b1').on('click', close);
  }

  const esc = s => String(s ?? '').replace(/[&<>"]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[m]));
  const errList = (items = []) => `<ul class="mm-list">${items.map(it => `<li><span class="dot"></span><div>${esc(it)}</div></li>`).join('')}</ul>`;

  // =========================================================
  // XOR UI (Medicamentos vs Prácticas)
  // =========================================================
  let RX_MODE = 'none';        // none | meds | practicas
  let RX_FORCE = null;         // null | 'practicas'
  let RX_LAST = 'none';        // none | meds | practicas

  let rxCssReady = false;
  let rxLayoutBound = false;

  let $MED_CARD = $(), $PRA_CARD = $();

  function ensureRxCSS() {
    if (rxCssReady) return;
    rxCssReady = true;

    // Grisado SOLO visual. No se usa pointer-events:none porque rompe select2:opening.
    const css = `
      .rx-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
      .rx-head-off{opacity:.65;filter:grayscale(.18)}
      .rx-off{position:relative;opacity:.60;filter:grayscale(.25)}
      .rx-off::after{
        content:"";
        position:absolute; inset:0;
        background:rgba(255,255,255,.35);
        border-radius:10px;
        pointer-events:none;
      }
      @media (prefers-color-scheme: dark){
        .rx-off::after{ background:rgba(15,23,42,.35); }
      }

      .rx-off .select2-selection{
        background:#e9ecef !important;
        cursor:not-allowed !important;
      }
      @media (prefers-color-scheme: dark){
        .rx-off .select2-selection{
          background: rgba(148,163,184,.18) !important;
        }
      }
    `;
    document.head.appendChild(Object.assign(document.createElement('style'), { textContent: css }));
  }

  function ensurePracticasHidden() {
    let $h = $('#practicasHidden');
    if (!$h.length) {
      const $f = $('#recetaForm');
      $h = $('<div/>', { id: 'practicasHidden', class: 'd-none' });
      ($f.length ? $f : $(document.body)).append($h);
    }
    return $h;
  }

  function ensurePracticasList() {
    let $l = $('#practicasList');
    if (!$l.length) {
      const $s = $('#practica_search');
      $l = $('<div/>', { id: 'practicasList', class: 'mt-2' });
      if ($s.length) $s.closest('.form-group').append($l);
      else $(document.body).append($l);
    }
    return $l;
  }

  function practicasCount() {
    const nHidden = ensurePracticasHidden().find('input[name="practicas[]"]').length;
    const nChips = $('#practicasList [data-id]').length;
    return Math.max(nHidden, nChips);
  }

  function hasMedsSelected() {
    let any = false;
    $('#medsWrapper .med-row').each(function () {
      const reg = String($(this).find('.regno').val() || '').trim();
      const sel = $(this).find('.sel-medicamento').val();
      if (reg || (sel && String(sel).trim() !== '')) { any = true; return false; }
    });
    return any;
  }

  function clearPracticasUI() {
    ensurePracticasHidden().find('input[name="practicas[]"]').remove();
    ensurePracticasList().empty();
    const $s = $('#practica_search');
    if ($s.length) $s.val(null).trigger('change');
  }

  function clearMedsUI() {
    const $wrap = $('#medsWrapper');
    if (!$wrap.length) return;

    $wrap.children('.med-row').slice(1).remove();

    const $r = $wrap.children('.med-row').first();
    if (!$r.length) return;

    $r.find('.sel-medicamento').val(null).trigger('change');
    $r.find('input[type="number"], input[type="text"], textarea').val('');
    $r.find('.duplicado').prop('checked', false);
  }

  function ensureRxLayout() {
    ensureRxCSS();
    ensurePracticasHidden();
    ensurePracticasList();

    const $medWrap = $('#medsWrapper');
    $MED_CARD = $medWrap.closest('.receta-card');

    const $praSel = $('#practica_search');
    $PRA_CARD = $praSel.closest('.receta-card');

    // --- Botón quitar meds (siempre existe y queda cerca del botón agregar)
    let $btnClearMeds = $('#btnClearMeds');
    if (!$btnClearMeds.length) {
      $btnClearMeds = $('<button/>', {
        id: 'btnClearMeds',
        type: 'button',
        class: 'btn-ejornal btn-ejornal-gris-claro',
        text: 'Quitar medicamentos'
      }).css({ 'white-space': 'nowrap' });
    } else {
      $btnClearMeds.attr('type', 'button');
    }

    const $btnAddMed = $('#btnAddMed');
    if ($btnAddMed.length) {
      // lo pego al lado de agregar (sin depender de headers)
      if (!$btnClearMeds.parent().is($btnAddMed.parent())) $btnClearMeds.insertAfter($btnAddMed);
    } else if ($MED_CARD.length && !$btnClearMeds.closest('.receta-card').is($MED_CARD)) {
      $MED_CARD.prepend($('<div class="rx-actions mb-2"></div>').append($btnClearMeds));
    }

    // --- Botón quitar prácticas
    let $btnClearPract = $('#btnClearPracticas');
    if (!$btnClearPract.length) {
      $btnClearPract = $('<button/>', {
        id: 'btnClearPracticas',
        type: 'button',
        class: 'btn-ejornal btn-ejornal-gris-claro',
        text: 'Quitar práctica'
      }).css({ 'white-space': 'nowrap' });
    } else {
      $btnClearPract.attr('type', 'button');
    }

    if ($praSel.length) {
     const $s2 = $praSel.next('.select2');
      if ($s2.length) {

        // 👇 la fila que agregamos está DESPUÉS del .select2, no es un padre
        let $row = $s2.next('.rx-actions');

        if (!$row.length) {
          $row = $('<div class="rx-actions mt-2"></div>');
          $s2.after($row);
        }

        // Si quedaron filas duplicadas por el bug anterior, las borramos
        $s2.parent().children('.rx-actions').not($row).remove();

        if (!$btnClearPract.parent().is($row)) $row.append($btnClearPract);

      } else {
        // fallback si select2 aún no armó su DOM
        const $fg = $praSel.closest('.form-group');
        let $row = $fg.find('> .rx-actions').first();
        if (!$row.length) {
          $row = $('<div class="rx-actions mt-2"></div>');
          $fg.append($row);
        }
        if (!$btnClearPract.parent().is($row)) $row.append($btnClearPract);
      }
    } else if ($PRA_CARD.length && !$btnClearPract.closest('.receta-card').is($PRA_CARD)) {
      $PRA_CARD.prepend($('<div class="rx-actions mb-2"></div>').append($btnClearPract));
    }

    if (!rxLayoutBound) {
      rxLayoutBound = true;

      $(document).on('click.rxClearMeds', '#btnClearMeds', function (e) {
        e.preventDefault();
        RX_LAST = 'none';
        clearMedsUI();
        RX_FORCE = null;
        RX_MODE = 'none';
        applyRxXorUI();
        setTimeout(applyRxXorUI, 0);
      });

      $(document).on('click.rxClearPract', '#btnClearPracticas', function (e) {
        e.preventDefault();
        RX_LAST = 'none';
        clearPracticasUI();
        RX_FORCE = null;
        RX_MODE = 'none';
        applyRxXorUI();
        setTimeout(applyRxXorUI, 0);
      });
    }
  }

  function updateRxButtons() {
    $('#btnClearMeds').prop('disabled', !hasMedsSelected());
    $('#btnClearPracticas').prop('disabled', practicasCount() === 0);
  }

  function setMedsEnabled(enabled) {
    ensureRxLayout();

    const $wrap = $('#medsWrapper');
    if ($MED_CARD.length) $MED_CARD.toggleClass('rx-off', !enabled);
    if ($wrap.length) $wrap.toggleClass('rx-off', !enabled);

    $('#btnAddMed').prop('disabled', !enabled);

    // Deshabilito edición real, pero NO deshabilito el select (para que select2:opening muestre el modal)
    if ($wrap.length) {
      $wrap.find('input, textarea').prop('disabled', !enabled);
      $wrap.find('.btn-del-med').prop('disabled', !enabled);

      // refrescar select2 look
      $wrap.find('select').trigger('change.select2');
    }

    $('#medsWrapper').trigger('meds:toggleDel');
  }

  function setPracticasEnabled(enabled) {
    ensureRxLayout();

    if ($PRA_CARD.length) $PRA_CARD.toggleClass('rx-off', !enabled);

    // NO deshabilito el select (para que select2:opening muestre el modal)
    const $s = $('#practica_search');
    if ($s.length) $s.trigger('change.select2');

    // Sí deshabilito botones de quitar chip para evitar edición en modo bloqueado
    $('#practicasList .quitar-chip').prop('disabled', !enabled);
  }

  function applyRxXorUI() {
    ensureRxLayout();

    let hasPract = practicasCount() > 0;
    let hasMeds = hasMedsSelected();

    // Si por timing queda mezcla, resuelvo sin romper la UI
    if (hasPract && hasMeds) {
      if (RX_FORCE === 'practicas' || RX_LAST === 'practicas') {
        clearMedsUI();
      } else {
        clearPracticasUI();
      }
      hasPract = practicasCount() > 0;
      hasMeds = hasMedsSelected();
    }

    if (hasPract) {
      RX_MODE = 'practicas';
      RX_FORCE = null;
      setMedsEnabled(false);
      setPracticasEnabled(true);
      updateRxButtons();
      return;
    }

    if (hasMeds) {
      RX_MODE = 'meds';
      RX_FORCE = null;
      setPracticasEnabled(false);
      setMedsEnabled(true);
      updateRxButtons();
      return;
    }

    if (RX_FORCE === 'practicas') {
      RX_MODE = 'practicas';
      setMedsEnabled(false);
      setPracticasEnabled(true);
      updateRxButtons();
      return;
    }

    RX_MODE = 'none';
    RX_FORCE = null;
    setMedsEnabled(true);
    setPracticasEnabled(true);
    updateRxButtons();
  }

  // =========================================================
  // Spinner pequeño en labels
  // =========================================================
  let spCSS = false;
  function ensureSpinner() {
    if (spCSS) return; spCSS = true;
    const css = `@keyframes miniSpin{to{transform:rotate(360deg)}}.msw{display:inline-block;margin-left:6px;vertical-align:middle}.ms{width:16px;height:16px;animation:miniSpin .9s linear infinite}.ms circle{stroke-linecap:round}`;
    document.head.appendChild(Object.assign(document.createElement('style'), { textContent: css }));
  }
  function spinnerSvg() {
    ensureSpinner();
    return '<span class="msw"><svg class="ms" viewBox="0 0 50 50"><defs><linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="#2196f3"/><stop offset="100%" stop-color="#81d4fa"/></linearGradient></defs><circle cx="25" cy="25" r="20" stroke="url(#g)" fill="none" stroke-width="5"/></svg></span>';
  }
  function labelLoader($sel, on) {
    const $l = $sel.closest('.form-group').find('label').first();
    if (!$l.length) return;
    on ? (!$l.find('.msw').length && $l.append(spinnerSvg())) : $l.find('.msw').remove();
  }

  // =========================================================
  // Loader sobre el formulario
  // =========================================================
  let formLoaderReady = false;
  function ensureFormLoaderCSS() {
    if (formLoaderReady) return;
    formLoaderReady = true;
    const css = `
      .fl-back{position:absolute; inset:0; background: rgba(255,255,255,.75); display:flex; align-items:center; justify-content:center; z-index: 9998; border-radius: 12px;}
      @media (prefers-color-scheme: dark){ .fl-back{ background: rgba(15,23,42,.72); } }
      .fl-box{display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius: 12px; background: rgba(255,255,255,.95); box-shadow: 0 10px 30px rgba(0,0,0,.18); font-size: 14px; color:#0f172a;}
      @media (prefers-color-scheme: dark){ .fl-box{ background: rgba(2,6,23,.92); color:#e2e8f0; } }
      .fl-spin{width:18px; height:18px; border-radius:999px; border: 3px solid rgba(0,0,0,.12); border-top-color: rgba(0,0,0,.55); animation: flrot .9s linear infinite;}
      @media (prefers-color-scheme: dark){ .fl-spin{ border-color: rgba(255,255,255,.15); border-top-color: rgba(255,255,255,.65); } }
      @keyframes flrot{to{transform:rotate(360deg)}}
    `;
    document.head.appendChild(Object.assign(document.createElement('style'), { textContent: css }));
  }
  function showFormLoader(msg = 'Cargando datos del trabajador…') {
    ensureFormLoaderCSS();
    const $f = $('#recetaForm');
    if (!$f.length) return;

    const $wrap = $f.closest('.tarjeta');
    const $host = $wrap.length ? $wrap : $f;

    if ($host.find('.fl-back').length) return;
    if ($host.css('position') === 'static') $host.css('position', 'relative');

    const $ov = $(`
      <div class="fl-back" aria-live="polite" aria-busy="true">
        <div class="fl-box">
          <div class="fl-spin"></div>
          <div class="fl-msg">${msg}</div>
        </div>
      </div>
    `);
    $host.append($ov);
  }
  function hideFormLoader() {
    const $f = $('#recetaForm');
    if (!$f.length) return;
    const $wrap = $f.closest('.tarjeta');
    const $host = $wrap.length ? $wrap : $f;
    $host.find('.fl-back').remove();
  }

  // =========================================================
  // Helpers URL / fechas / select2
  // =========================================================
  let pendingFechaISO = '';
  let PROV_BY_ID = {};
  let PROV_ID_BY_NAME = {};
  let provinciasCache = null;
  let pendingDomProvId = '';
  let pendingDomProvName = '';

  function rurl(u) {
    try {
      if (!u) return '/';
      if (/^https?:\/\//i.test(u)) return u;
      return new URL(u, location.origin).toString();
    } catch {
      return u;
    }
  }
  function urls() {
    const $f = $('#recetaForm');
    return {
      financiadores: rurl($f.data('url-get-financiadores')),
      diagnosticos: rurl($f.data('url-get-diagnosticos')),
      medicamentos: rurl($f.data('url-get-medicamentos')),
      practicas: rurl($f.data('url-get-practicas')),
      provincias: rurl($f.data('url-get-provincias'))
    };
  }

  const isoAEs = iso => {
    if (!iso) return '';
    const s = String(iso).trim();
    const m = s.match(/^(\d{4})-(\d{2})-(\d{2})/);
    return m ? `${m[3]}/${m[2]}/${m[1]}` : '';
  };
  const esAISO = es => {
    if (!es) return '';
    const m = /^(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})$/.exec((es + '').trim());
    if (!m) return '';
    const dd = ('0' + m[1]).slice(-2), mm = ('0' + m[2]).slice(-2);
    return `${m[3]}-${mm}-${dd}`;
  };
  function normalizeISO(input) {
    if (!input) return '';
    const s = String(input).trim();
    const m = s.match(/^(\d{4}-\d{2}-\d{2})/);
    return m ? m[1] : '';
  }

  function s2Small($sel) {
    const $w = $sel.next('.select2'), $s = $w.find('.select2-selection--single');
    $s.css({ height: '31px', 'min-height': '31px', 'border-radius': '.2rem', 'border-color': '#ced4da', padding: '2px 8px' });
    $s.find('.select2-selection__rendered').css({ 'line-height': '26px', 'font-size': '.875rem' });
    $s.find('.select2-selection__arrow').css({ height: '29px', right: '6px' });
  }

  function s2Ajax($sel, { url, minLen = 2, dataFn, procFn, tpl, langNoResults }) {
    $sel.select2({
      width: '100%',
      placeholder: 'Buscar…',
      allowClear: false,
      minimumInputLength: minLen,
      ajax: {
        delay: 300,
        url,
        dataType: 'json',
        cache: true,
        data: p => (dataFn ? dataFn(p) : ({ q: p.term || '', page: p.page || 1 })),
        processResults: res => (procFn ? procFn(res) : res),
        beforeSend: () => labelLoader($sel, true),
        complete: () => labelLoader($sel, false),
        headers: { 'Accept': 'application/json' },
        transport: function (params, success, failure) {
          const cfg = Object.assign({}, params, { timeout: AJAX_TIMEOUT_MS });
          return $.ajax(cfg).done(success).fail(failure);
        }
      },
      language: { noResults: () => langNoResults || 'Sin resultados' },
      dropdownParent: $(document.body),
      templateResult: tpl
    });
    s2Small($sel);
  }

  function normSexo(v) {
    v = String(v || '').trim().toUpperCase();
    if (!v) return '';
    if (['M', 'F', 'X', 'O'].includes(v)) return v;
    if (v.startsWith('M') || v.startsWith('H')) return 'M';
    if (v.startsWith('F')) return 'F';
    if (v.startsWith('X')) return 'X';
    if (v.startsWith('O')) return 'O';
    return '';
  }

  // =========================================================
  // Provincias
  // =========================================================
  function fetchProvincias() {
    if (provinciasCache) return $.Deferred().resolve(provinciasCache).promise();

    return $.ajax({
      url: urls().provincias,
      dataType: 'json',
      headers: { 'Accept': 'application/json' },
      timeout: AJAX_TIMEOUT_MS
    })
      .then(j => {
        const arr = Array.isArray(j?.results) ? j.results : [];
        provinciasCache = arr;

        PROV_BY_ID = {};
        PROV_ID_BY_NAME = {};
        arr.forEach(p => {
          const id = p?.id;
          const nom = String(p?.nombre ?? '').trim();
          if (id != null && nom) {
            PROV_BY_ID[String(id)] = nom;
            PROV_ID_BY_NAME[nom.toLowerCase()] = String(id);
          }
        });

        return arr;
      })
      .catch(() => {
        provinciasCache = [];
        PROV_BY_ID = {};
        PROV_ID_BY_NAME = {};
        return [];
      });
  }

  function toSelect($input, items, { name } = {}) {
    const current = String($input.val() || '').trim();
    const $sel = $('<select class="form-control form-control-sm"></select>');

    if (name) $sel.attr('name', name);
    if ($input.attr('id')) $sel.attr('id', $input.attr('id'));

    $sel.append($('<option>').val('').text('Seleccione…'));

    items.forEach(p => {
      const id = (p?.id != null) ? String(p.id) : '';
      const nom = String(p?.nombre ?? '').trim();
      $sel.append($('<option>').val(id).text(nom || id));
    });

    $input.replaceWith($sel);

    if (current) {
      $sel.val(current);
      if (!$sel.val()) {
        const c = current.toLowerCase();
        const $opt = $sel.find('option').filter(function () {
          return String($(this).text() || '').trim().toLowerCase() === c;
        }).first();
        if ($opt.length) $sel.val($opt.val());
      }
    }
    return $sel;
  }

  function ensureProvinciaPair(items, { visibleName, idName }) {
    const $f = $('#recetaForm');
    if (!$f.length) return null;

    let $hiddenName = $f.find(`[name="${visibleName}"]`);
    let $idField = $f.find(`[name="${idName}"]`);
    let $host = $hiddenName;

    if ($hiddenName.length > 1) {
      $hiddenName.slice(1).remove();
      $hiddenName = $f.find(`[name="${visibleName}"]`).first();
    }
    if ($idField.length > 1) {
      $idField.slice(1).remove();
      $idField = $f.find(`[name="${idName}"]`).first();
    }

    if (!$hiddenName.length) {
      $hiddenName = $(`<input type="hidden" name="${visibleName}">`);
      $f.append($hiddenName);
    }

    if ($hiddenName.is('input') && ($hiddenName.attr('type') || 'text').toLowerCase() !== 'hidden') {
      const txt = String($hiddenName.val() || '').trim();
      $hiddenName.attr('type', 'hidden');
      $hiddenName.val(txt);
      $host = $('<input type="text" class="form-control form-control-sm" value="">');
      $hiddenName.after($host);
    }

    if ($host && $host.length && $host.is('select')) {
      $host.attr('name', idName);
      return $host;
    }

    if ($idField.length && !$host.length) $host = $idField;
    if (!$host || !$host.length) {
      $host = $('<input type="text" class="form-control form-control-sm" value="">');
      $f.append($host);
    }
    if ($idField.length && $idField[0] !== $host[0]) $idField.remove();

    const curId = String($f.find(`[name="${idName}"]`).val() || '').trim();
    const curText = String($hiddenName.val() || '').trim();

    const $sel = toSelect($host, items, { name: idName });

    if (curId) $sel.val(curId);
    if (!$sel.val() && curText) {
      const k = curText.toLowerCase();
      if (PROV_ID_BY_NAME[k]) $sel.val(PROV_ID_BY_NAME[k]);
    }

    $sel.on('change', function () {
      const txt = String($(this).find('option:selected').text() || '').trim();
      $hiddenName.val(txt);
    });

    $sel.trigger('change');
    return $sel;
  }

  function syncProvinciaTextFromSelects() {
    const $f = $('#recetaForm');
    if (!$f.length) return;

    const $domSel = $f.find('[name="domicilio[id_provincia]"]');
    let $domTxt = $f.find('[name="domicilio[provincia]"]');
    if (!$domTxt.length) {
      $domTxt = $('<input type="hidden" name="domicilio[provincia]">');
      $f.append($domTxt);
    }
    if ($domSel.length) {
      const txt = String($domSel.find('option:selected').text() || '').trim();
      if (txt && txt !== 'Seleccione…') $domTxt.val(txt);
    }

    const $matSel = $f.find('[name="medico[matricula][id_provincia]"]');
    let $matTxt = $f.find('[name="medico[matricula][provincia]"]');
    if (!$matTxt.length) {
      $matTxt = $('<input type="hidden" name="medico[matricula][provincia]">');
      $f.append($matTxt);
    }
    if ($matSel.length) {
      const txt = String($matSel.find('option:selected').text() || '').trim();
      if (txt && txt !== 'Seleccione…') $matTxt.val(txt);
    }
  }

  function setRequired($field, required) {
    if (!$field || !$field.length) return;
    const $fg = $field.closest('.form-group');
    const $label = $fg.find('label').first();
    $field.prop('required', !!required);
    $label.find('.req').remove();
    if (required) $label.append(' <span class="req text-danger">*</span>');
  }

  // =========================================================
  // Nómina → autocompletado
  // =========================================================
  function splitNombre(full) {
    full = (full || '').trim().replace(/\s+/g, ' ');
    if (!full) return { apellido: '', nombre: '' };
    if (full.includes(',')) {
      const a = full.split(',');
      return { apellido: a[0].trim(), nombre: (a[1] || '').trim() };
    }
    const i = full.indexOf(' ');
    return i === -1 ? { apellido: '', nombre: full } : { apellido: full.slice(0, i), nombre: full.slice(i + 1) };
  }

  function clearPaciente() {
    const $f = $('#recetaForm');
    const set = (n, v) => {
      const $el = $f.find(`[name="${n}"]`);
      if ($el.length) $el.val(v ?? '').trigger('input').trigger('change');
    };
    set('paciente[nombre]', '');
    set('paciente[apellido]', '');
    set('paciente[nroDoc]', '');
    set('paciente[email]', '');
    set('paciente[telefono]', '');
    set('paciente[fechaNacimiento]', '');
    set('paciente[sexo]', 'M');
    const $vis = $('#paciente_fecha_visual');
    if ($vis.length) $vis.val('');
  }

  function setPaciente(d) {
    const empty = !d || (
      !String(d.nombre || '').trim() &&
      !String(d.apellido || '').trim() &&
      !String(d.dni || '').trim() &&
      !String(d.email || '').trim() &&
      !String(d.telefono || '').trim() &&
      !String(d.fechaNacimiento || '').trim()
    );
    if (empty) { clearPaciente(); return; }

    const $f = $('#recetaForm');
    const set = (n, v) => {
      const $el = $f.find(`[name="${n}"]`);
      if (!$el.length) return;
      $el.val(v ?? '').trigger('input').trigger('change');
    };

    set('paciente[nombre]', d.nombre);
    set('paciente[apellido]', d.apellido);
    set('paciente[nroDoc]', d.dni && String(d.dni));
    set('paciente[email]', d.email);
    set('paciente[telefono]', d.telefono);

    const sx = normSexo(d.sexo);
    if (sx) set('paciente[sexo]', sx);

    const iso = normalizeISO(d.fechaNacimiento) || esAISO(d.fechaNacimiento);
    set('paciente[fechaNacimiento]', iso);

    const $vis = $('#paciente_fecha_visual');
    if ($vis.length) $vis.val(isoAEs(iso));
    else pendingFechaISO = iso;
  }

  function clearDomicilio() {
    const $f = $('#recetaForm');
    const set = (n, v) => {
      const $el = $f.find(`[name="${n}"]`);
      if ($el.length) $el.val(v ?? '').trigger('input').trigger('change');
    };
    set('domicilio[calle]', '');
    set('domicilio[numero]', '');
    set('domicilio[provincia]', '');

    const $sel = $f.find('[name="domicilio[id_provincia]"]');
    if ($sel.length) $sel.val('').trigger('change');

    pendingDomProvId = '';
    pendingDomProvName = '';
  }

  function setDomicilioFromCliente(c) {
    const $f = $('#recetaForm');
    const set = (n, v) => {
      const $el = $f.find(`[name="${n}"]`);
      if (!$el.length) return;
      $el.val(v ?? '').trigger('input').trigger('change');
    };

    const empty = !c || (
      !String(c.calle || '').trim() &&
      !String(c.nro || '').trim() &&
      !String(c.id_provincia || '').trim() &&
      !String(c.provincia || '').trim()
    );
    if (empty) { clearDomicilio(); return; }

    set('domicilio[calle]', c.calle);
    set('domicilio[numero]', c.nro != null ? String(c.nro).trim() : '');

    const provId = String(c.id_provincia || '').trim();
    const provName = String(c.provincia || '').trim() || (provId && PROV_BY_ID[provId] ? PROV_BY_ID[provId] : '');

    let $provHidden = $f.find('[name="domicilio[provincia]"]');
    if (!$provHidden.length) {
      $provHidden = $('<input type="hidden" name="domicilio[provincia]">');
      $f.append($provHidden);
    }
    $provHidden.val(provName);

    const $provSel = $f.find('[name="domicilio[id_provincia]"]');
    if ($provSel.length && $provSel.is('select')) {
      if (provId) {
        $provSel.val(provId);
        if (!$provSel.val()) $provSel.append(new Option(provName || provId, provId, true, true));
      } else if (provName) {
        const k = provName.toLowerCase();
        if (PROV_ID_BY_NAME[k]) $provSel.val(PROV_ID_BY_NAME[k]);
      }
      $provSel.trigger('change');
      return;
    }

    pendingDomProvId = provId;
    pendingDomProvName = provName;

    const $provInput = $f.find('[name="domicilio[provincia]"]');
    if ($provInput.length && !$provInput.is('select')) $provInput.val(provName).trigger('input').trigger('change');
  }

  function initNomina() {
    const $s = $('#id_nomina');
    if (!$s.length) return;

    const preset = String($s.data('preset') || '').trim();
    if (preset) showFormLoader('Cargando datos del trabajador…');

    const opts = $s.find('option').toArray();
    if (opts.length > 1) {
      const first = opts.shift();
      opts.sort((a, b) => $(a).text().localeCompare($(b).text(), 'es', { sensitivity: 'base' }));
      $s.empty().append(first, opts);
    }

    $s.select2({ width: '100%', minimumInputLength: 0, dropdownParent: $(document.body) });
    s2Small($s);

    if (preset) {
      $s.on('select2:opening select2:unselecting', e => e.preventDefault());
      const $sel2 = $s.next('.select2').find('.select2-selection--single');
      $sel2.css({ background: '#e9ecef', cursor: 'not-allowed' });
    }

    const onCh = () => {
      const v = $s.val();
      if (!v) {
        clearPaciente();
        clearDomicilio();
        hideFormLoader();
        return;
      }

      const $o = $s.find('option:selected');

      let full = ($o.attr('data-nombre') || ($o.data('nombre') ?? '') || ($o.text() || ''))
        .replace(/\s+—\s+.*$/, '')
        .replace(/\(DNI:.*?\)/, '')
        .trim();

      const { apellido, nombre } = splitNombre(full);

      const fnac =
        ($o.attr('data-fecha-nacimiento') || '').trim() ||
        (String($o.data('fechaNacimiento') ?? '').trim()) ||
        (String($o.data('fecha-nacimiento') ?? '').trim());

      const sexoRaw = String($o.attr('data-sexo') || $o.data('sexo') || '').trim();

      setPaciente({
        nombre,
        apellido,
        dni: $o.attr('data-dni') || $o.data('dni'),
        email: $o.attr('data-email') || $o.data('email'),
        telefono: $o.attr('data-telefono') || $o.data('telefono'),
        fechaNacimiento: fnac,
        sexo: sexoRaw
      });

      const cCalle = String($o.attr('data-cliente-calle') || '').trim();
      const cNro = String(($o.attr('data-cliente-nro') || $o.attr('data-cliente-numero') || '')).trim();
      const cProvId = String($o.attr('data-cliente-id-provincia') || '').trim();
      const cProvNom = String($o.attr('data-cliente-provincia') || '').trim();

      setDomicilioFromCliente({
        calle: cCalle,
        nro: cNro,
        id_provincia: cProvId,
        provincia: cProvNom
      });

      setTimeout(hideFormLoader, 50);
    };

    $s.on('change select2:select', onCh);

    if (preset && $s.find(`option[value="${preset}"]`).length) {
      $s.val(preset).trigger('change');
      $s.trigger('change.select2');
      return;
    }

    if ($s.val()) onCh();
    else hideFormLoader();
  }

  // =========================================================
  // Cobertura
  // =========================================================
  function ensureCobInputs() {
    const $f = $('#recetaForm');
    ['cobertura[idFinanciador]', 'cobertura[planId]', 'cobertura[plan]'].forEach(n => {
      if (!$f.find(`input[name="${n}"]`).length) $f.append(`<input type="hidden" name="${n}">`);
    });
  }

  function initFinanciadores() {
    const $fin = $('#financiador');
    const $plan = $('#plan');
    if (!$fin.length) return;

    ensureCobInputs();
    const u = urls();

    $plan.select2({ width: '100%', minimumInputLength: 0, dropdownParent: $(document.body) });
    s2Small($plan);
    $plan.prop('disabled', true).trigger('change.select2');

    s2Ajax($fin, {
      url: u.financiadores,
      minLen: 3,
      dataFn: p => ({ q: p.term || '' }),
      procFn: res => ({ results: res.results || [] })
    });

    function rebuildPlans(list) {
      try { $plan.select2('destroy'); } catch { }
      $plan.empty();

      const planes = (Array.isArray(list) ? list : []).reduce((acc, p) => {
        const id = p.id ?? p.planId ?? p.planid;
        const nom = p.nombre ?? p.descripcion ?? p.name;
        if (id && nom) acc.push({ id, text: nom });
        return acc;
      }, []);

      planes.forEach(p => $plan.append(new Option(p.text, p.id)));

      $plan.prop('disabled', planes.length === 0);
      $plan.select2({ width: '100%', minimumInputLength: 0, dropdownParent: $(document.body) });
      s2Small($plan);

      if (planes.length) {
        $plan.val(String(planes[0].id)).trigger('change');
      } else {
        $('[name="cobertura[planId]"]').val('');
        $('[name="cobertura[plan]"]').val('');
      }
      $plan.trigger('change.select2');
    }

    function limpiarMedicamentos() {
      const $wrap = $('#medsWrapper');
      $wrap.find('.sel-medicamento').each(function () { $(this).val(null).trigger('change'); });
      $wrap.find('.regno,.presentacion,.nombre,.droga,' +
        'input[name*="[tratamiento]"],input[name*="[posologia]"],textarea[name*="[indicaciones]"]').val('');
      $wrap.find('.duplicado').prop('checked', false);
      applyRxXorUI();
    }

    $fin.on('select2:select', function () {
      const sel = $fin.select2('data')[0];
      const raw = sel?.raw || {};
      const finId = raw.idfinanciador ?? raw.nrofinanciador ?? raw.nroFinanciador ?? raw.id ?? '';
      $('[name="cobertura[idFinanciador]"]').val(String(finId).replace(/\D+/g, ''));

      rebuildPlans(raw.planes || []);
      limpiarMedicamentos();
    });

    $plan.on('select2:select', function () {
      const $o = $plan.find('option:selected');
      const planId = String($o.val() || '').replace(/\D+/g, '');
      $('[name="cobertura[planId]"]').val(planId);
      $('[name="cobertura[plan]"]').val($o.text() || '');
      limpiarMedicamentos();
    });
  }

  // =========================================================
  // Diagnósticos
  // =========================================================
  function initDiagnosticos() {
    const $s = $('#diag_search');
    if (!$s.length) return;

    s2Ajax($s, { url: urls().diagnosticos, minLen: 3, procFn: d => d });

    $s.on('select2:select', () => {
      const it = $s.select2('data')[0];
      $('#diagnostico_codigo').val(it?.id || '');
      $('#diagnostico').val(it?.text || '');
    });
  }

  // =========================================================
  // Medicamentos
  // =========================================================
  function coberturaParams() {
    const idFin = $('[name="cobertura[idFinanciador]"]').val() || $('#financiador').val();
    const planId = $('[name="cobertura[planId]"]').val() || $('#plan').val();
    const dni = $('[name="paciente[nroDoc]"]').val();
    const cred = $('[name="cobertura[credencial]"]').val();
    const q = {};
    if (idFin) q.idFinanciador = idFin;
    if (planId) q.planid = planId;
    if (dni) q.afiliadoDni = dni;
    if (cred) q.afiliadoCredencial = cred;
    return q;
  }

  function attachMed($row) {
    const $sel = $row.find('.sel-medicamento'),
      $reg = $row.find('.regno'),
      $pre = $row.find('.presentacion'),
      $nom = $row.find('.nombre'),
      $dro = $row.find('.droga'),
      $dup = $row.find('.duplicado');

    $reg.attr('readonly', 'readonly');
    if (!$row.find('.regno-help').length) {
      $reg.after('<small class="text-muted regno-help">Se completa automáticamente al elegir un medicamento</small>');
    }

    let $hint = $row.find('.med-hint');
    if (!$hint.length) {
      $hint = $('<div class="med-hint small mt-1 text-muted"></div>');
      $sel.closest('.form-group').append($hint);
    }

    function updateHint() {
      const p = coberturaParams();
      if (p.idFinanciador && p.planid) $hint.text('Resultados filtrados por tu cobertura/plan.');
      else $hint.text('Resultados generales (sin cobertura). Podés recetar con Reg. Nº + Cantidad.');
    }
    $row.data('updateHint', updateHint);
    updateHint();

    s2Ajax($sel, {
      url: urls().medicamentos,
      minLen: 2,
      dataFn: p => {
        const cov = coberturaParams();
        const base = { q: p.term || '', page: p.page || 1 };
        if (cov.idFinanciador) base.idFinanciador = cov.idFinanciador;
        if (cov.planid) base.planid = cov.planid;
        if (cov.afiliadoDni) base.afiliadoDni = cov.afiliadoDni;
        if (cov.afiliadoCredencial) base.afiliadoCredencial = cov.afiliadoCredencial;
        return base;
      },
      procFn: d => ({ results: d.results || [], pagination: { more: !!d?.pagination?.more } }),
      tpl: function (it) {
        if (!it.id) return it.text;
        const r = it.raw || {}, flags = [];
        if (r.tieneCobertura) flags.push('Cobertura');
        if (r.psicofarmaco) flags.push('Psicofármaco');
        if (r.estupefaciente) flags.push('Estupefaciente');
        if (r.ventaControlada) flags.push('Venta controlada');
        if (r.hiv) flags.push('HIV');
        return $('<span>' + it.text + (flags.length ? ' <small class="text-muted">(' + flags.join(', ') + ')</small>' : '') + '</span>');
      },
      langNoResults: 'No hay medicamentos disponibles con ese criterio.'
    });

    $sel.on('select2:select', function () {
      if (practicasCount() > 0 || RX_FORCE === 'practicas' || RX_MODE === 'practicas') {
        modal({
          title: 'No se puede mezclar',
          html: '<p>Esta receta es <b>solo de prácticas</b>. Quitá las prácticas para poder cargar medicamentos.</p>'
        });
        $sel.val(null).trigger('change');
        return;
      }

      RX_LAST = 'meds';
      RX_FORCE = null;

      const r = $sel.select2('data')[0]?.raw || {};
      $reg.val(r.regNo || '');
      $pre.val(r.presentacion || '');
      $nom.val(r.nombreProducto || '');
      $dro.val(r.nombreDroga || '');
      if (r.requiereDuplicado === true) $dup.prop('checked', true);

      applyRxXorUI();
    });

    $sel.on('change', function () {
      applyRxXorUI();
    });
  }

  function bindMedFilterChangeOnce() {
    $(document).off('change.medFilter');
    $(document).on('change.medFilter', '#financiador, #plan', function () {
      $('#medsWrapper .med-row').each(function () {
        const $r = $(this);
        const $sel = $r.find('.sel-medicamento');
        const $reg = $r.find('.regno');
        const $pre = $r.find('.presentacion');
        const $nom = $r.find('.nombre');
        const $dro = $r.find('.droga');
        const $dup = $r.find('.duplicado');

        if ($sel.length) $sel.val(null).trigger('change');
        $reg.val(''); $pre.val(''); $nom.val(''); $dro.val(''); $dup.prop('checked', false);

        const fn = $r.data('updateHint');
        if (typeof fn === 'function') fn();
      });

      RX_LAST = 'none';
      applyRxXorUI();
    });
  }

  function initMeds() {
    let idx = 1;
    const $wrap = $('#medsWrapper'), $add = $('#btnAddMed');
    if (!$wrap.length) return;

    bindMedFilterChangeOnce();

    const tpl = i => `
      <div class="med-row border rounded p-2 mb-3">
        <div class="form-row">
          <div class="form-group col-md-4 mb-2">
            <label class="mb-1 text-muted small">Buscar medicamento <span class="req text-danger">*</span></label>
            <select class="form-control form-control-sm sel-medicamento" style="width:100%"></select>
          </div>
          <div class="form-group col-md-2 mb-2">
            <label class="mb-1 text-muted small">Cantidad <span class="req text-danger">*</span></label>
            <input type="number" min="1" class="form-control form-control-sm" name="medicamentos[${i}][cantidad]" required>
          </div>
          <div class="form-group col-md-3 mb-2">
            <label class="mb-1 text-muted small">Registro Nº</label>
            <input type="text" class="form-control form-control-sm regno" name="medicamentos[${i}][regNo]" readonly>
          </div>
          <div class="form-group col-md-3 mb-2">
            <label class="mb-1 text-muted small">Presentación</label>
            <input type="text" class="form-control form-control-sm presentacion" name="medicamentos[${i}][presentacion]">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4 mb-2">
            <label class="mb-1 text-muted small">Nombre</label>
            <input type="text" class="form-control form-control-sm nombre" name="medicamentos[${i}][nombre]">
          </div>
          <div class="form-group col-md-4 mb-2">
            <label class="mb-1 text-muted small">Droga</label>
            <input type="text" class="form-control form-control-sm droga" name="medicamentos[${i}][nombreDroga]">
          </div>
          <div class="form-group col-md-3 mb-2">
            <label class="mb-1 text-muted small">Tratamiento (días)</label>
            <input type="number" min="0" class="form-control form-control-sm" name="medicamentos[${i}][tratamiento]" placeholder="0">
          </div>
          <div class="form-group col-md-1 d-flex align-items-end mb-2">
            <button type="button" class="btn btn-sm btn-outline-danger btn-del-med" title="Eliminar">×</button>
          </div>
        </div>
        <div class="form-group mb-2">
          <label class="mb-1 text-muted small">Posología</label>
          <input type="text" class="form-control form-control-sm" name="medicamentos[${i}][posologia]">
        </div>
        <div class="form-group mb-2">
          <label class="mb-1 text-muted small">Indicaciones / Observaciones</label>
          <textarea class="form-control form-control-sm" name="medicamentos[${i}][indicaciones]" rows="2"></textarea>
        </div>
        <div class="custom-control custom-checkbox custom-control-inline">
          <input type="checkbox" class="custom-control-input duplicado" id="dup${i}" name="medicamentos[${i}][forzarDuplicado]" value="1">
          <label class="custom-control-label" for="dup${i}">Requiere duplicado</label>
        </div>
      </div>`;

    function toggleDel() {
      const $rows = $wrap.children('.med-row');
      $rows.find('.btn-del-med').prop('disabled', $rows.length <= 1);
    }

    $wrap.off('meds:toggleDel').on('meds:toggleDel', toggleDel);

    $add.off('click.rxAddMed').on('click.rxAddMed', () => {
      if (practicasCount() > 0 || RX_FORCE === 'practicas' || RX_MODE === 'practicas') {
        modal({
          title: 'No se puede mezclar',
          html: '<p>Esta receta es <b>solo de prácticas</b>. Quitá las prácticas para poder agregar medicamentos.</p>'
        });
        return;
      }

      RX_LAST = 'meds';

      const $r = $(tpl(idx));
      $wrap.append($r);
      attachMed($r);
      idx++;
      toggleDel();
      applyRxXorUI();
    });

    $wrap.off('click.rxDelMed').on('click.rxDelMed', '.btn-del-med', function () {
      if ($wrap.children().length > 1) {
        $(this).closest('.med-row').remove();
        toggleDel();
        RX_LAST = 'meds';
        applyRxXorUI();
      }
    });

    attachMed($wrap.find('.med-row').first());
    toggleDel();

    ensureRxLayout();
    applyRxXorUI();
  }

  // =========================================================
  // Prácticas
  // =========================================================
  function initPracticas() {
    const $s = $('#practica_search');
    if (!$s.length) return;

    ensurePracticasHidden();
    ensurePracticasList();

    s2Ajax($s, {
      url: urls().practicas,
      minLen: 3,
      dataFn: p => {
        const term = p.term || '';
        const data = { page: p.page || 1 };
        const mT = term.match(/tipo:([^ ]+)/i), mC = term.match(/cat:([^ ]+)/i);
        if (mT) data.tipo = mT[1];
        if (mC) data.categoria = mC[1];
        const clean = term.replace(/tipo:[^ ]+/ig, '').replace(/cat:[^ ]+/ig, '').trim();
        if (clean) data.search = clean;
        return data;
      },
      procFn: d => ({ results: d.results || [], pagination: { more: !!d?.pagination?.more } })
    });

    const $chips = ensurePracticasList();
    const $h = ensurePracticasHidden();

    function addChip(it) {
      if (hasMedsSelected() || RX_MODE === 'meds') {
        modal({
          title: 'No se puede mezclar',
          html: '<p>Esta receta es <b>solo de medicamentos</b>. Quitá los medicamentos para poder cargar prácticas.</p>'
        });
        return false;
      }

      const id = String(it?.id ?? '').trim();
      if (!id) return false;

      if ($h.find(`input[name="practicas[]"][value="${id}"]`).length) return false;

      RX_LAST = 'practicas';

      $h.append($('<input type="hidden" name="practicas[]">').val(id));
      $chips.append($(
        `<span class="badge badge-primary d-inline-flex align-items-center mr-2 mb-2" data-id="${id}" style="font-size:.8rem;">
          <span class="mr-2">${esc(it.text)}</span>
          <button type="button" class="btn btn-sm btn-light py-0 px-1 quitar-chip" aria-label="Quitar" style="line-height:1;">×</button>
        </span>`
      ));

      applyRxXorUI();
      setTimeout(applyRxXorUI, 0);

      return true;
    }

    $(document)
      .off('select2:select.rxPract')
      .on('select2:select.rxPract', '#practica_search', function () {
        const it = $(this).select2('data')[0];
        if (!it) return;

        const ok = addChip(it);

        // limpiar búsqueda SIEMPRE
        $(this).val(null).trigger('change');

        if (!ok) return;
      });

    $chips
      .off('click.rxChip')
      .on('click.rxChip', '.quitar-chip', function () {
        const id = String($(this).closest('[data-id]').data('id') || '').trim();
        if (id) $h.find(`input[name="practicas[]"][value="${id}"]`).remove();
        $(this).closest('[data-id]').remove();

        if (practicasCount() === 0) {
          RX_FORCE = null;
          RX_MODE = 'none';
          RX_LAST = 'none';
        } else {
          RX_LAST = 'practicas';
        }

        applyRxXorUI();
        setTimeout(applyRxXorUI, 0);
      });

    ensureRxLayout();
    applyRxXorUI();
  }

  // =========================================================
  // Sexo paciente
  // =========================================================
  function initSexo() {
    const $s = $('[name="paciente[sexo]"]');
    if (!$s.length) return;
    if (!$s.val()) $s.val('M');
    $s.find('option').each(function () {
      const v = $(this).val();
      if (v === 'M') $(this).text('Hombre (M)');
      else if (v === 'F') $(this).text('Mujer (F)');
      else if (v === 'X') $(this).text('No binario (X)');
      else if (v === 'O') $(this).text('Otro (O)');
    });
  }

  // =========================================================
  // Matrícula MN/MP + Provincias
  // =========================================================
  function initMatricula() {
    const $tipo = $('[name="medico[matricula][tipo]"]');
    const $numero = $('[name="medico[matricula][numero]"]');

    fetchProvincias().then(items => {
      ensureProvinciaPair(items, { visibleName: 'domicilio[provincia]', idName: 'domicilio[id_provincia]' });

      const $domSel = $('[name="domicilio[id_provincia]"]');
      if ($domSel.length && pendingDomProvId) {
        $domSel.val(pendingDomProvId);
        if (!$domSel.val()) $domSel.append(new Option(pendingDomProvName || pendingDomProvId, pendingDomProvId, true, true));
        $domSel.trigger('change');
        pendingDomProvId = '';
        pendingDomProvName = '';
      }

      const $selMat = ensureProvinciaPair(items, { visibleName: 'medico[matricula][provincia]', idName: 'medico[matricula][id_provincia]' });
      const $provMatWrap = $selMat ? $selMat.closest('.form-group') : null;

      function applyRules() {
        const t = String($tipo.val() || 'MN').toUpperCase().trim();
        if (!$tipo.val()) $tipo.val('MN');

        $numero.attr('maxlength', '10');
        setRequired($numero, true);

        if (t === 'MP') {
          if ($provMatWrap && $provMatWrap.length) $provMatWrap.show();
          if ($selMat && $selMat.length) setRequired($selMat, true);
        } else {
          if ($provMatWrap && $provMatWrap.length) $provMatWrap.hide();
          if ($selMat && $selMat.length) setRequired($selMat, false);
        }
      }

      $tipo.off('change.rxMat').on('change.rxMat', applyRules);
      if (!$tipo.val()) $tipo.val('MN');
      applyRules();

      lockMedicoFields();
    });
  }

  // =========================================================
  // Fecha nacimiento (datepicker)
  // =========================================================
  function initFecha() {
    const $iso = $('[name="paciente[fechaNacimiento]"]');
    if (!$iso.length) return;

    const $vis = $('<input type="text" id="paciente_fecha_visual" class="form-control form-control-sm" placeholder="DD/MM/AAAA" readonly>');
    const curISO = $iso.val();
    $vis.val(isoAEs(curISO)).insertAfter($iso);

    if (pendingFechaISO) {
      $iso.val(pendingFechaISO);
      $vis.val(isoAEs(pendingFechaISO));
      pendingFechaISO = '';
    }

    $iso.attr('type', 'hidden');
    $vis.on('keydown paste', e => e.preventDefault());

    if ($.datepicker && !$.datepicker.regional['es']) {
      $.datepicker.regional['es'] = {
        closeText: 'Cerrar', prevText: 'Anterior', nextText: 'Siguiente', currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1
      };
      $.datepicker.setDefaults($.datepicker.regional['es']);
    }

    if ($.fn.datepicker) {
      $vis.datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1900:+0',
        dateFormat: 'dd/mm/yy',
        onClose: v => $iso.val(esAISO(v))
      });
    }
    $vis.on('focus click', function () { if ($.fn.datepicker) $(this).datepicker('show'); });
  }

  // =========================================================
  // Firma + Sello UI
  // =========================================================
  function initFirmaSelloUI() {
    const $chk = $('#incluir_firma_medico');
    if (!$chk.length) return;

    const hasFirma = String($chk.data('hasFirma') ?? $chk.data('has-firma') ?? '') === '1';
    const $box = $('#box_sello_medico');
    const $fields = $box.find('.sello-field');

    function apply() {
      const on = hasFirma && $chk.is(':checked');
      $box.toggleClass('d-none', !on);
      $fields.prop('disabled', !on);
    }

    if (!hasFirma) $chk.prop('checked', false);

    $chk.off('change.firmaSello').on('change.firmaSello', apply);
    apply();

    // Truncar al pegar texto que exceda maxlength
    $fields.on('input.selloMax', function () {
      const ml = parseInt(this.getAttribute('maxlength'), 10);
      if (ml && this.value.length > ml) this.value = this.value.substring(0, ml);
    });
  }




    // =========================================================
  // Loader bloqueante al generar receta
  // =========================================================
  let submitFxReady = false;

  function ensureSubmitFxCSS() {
    if (submitFxReady) return;
    submitFxReady = true;

    const css = `
      .rx-submit-back{
        position:fixed;
        inset:0;
        z-index:10050;
        background:rgba(15,23,42,.38);
        backdrop-filter:blur(3px);
        display:flex;
        align-items:center;
        justify-content:center;
      }

      .rx-submit-box{
        width:min(92vw, 430px);
        background:#fff;
        border-radius:16px;
        box-shadow:0 20px 60px rgba(0,0,0,.25);
        padding:20px 18px;
      }

      .rx-submit-title{
        margin:0 0 8px;
        font-size:18px;
        font-weight:800;
        color:#111827;
      }

      .rx-submit-text{
        margin:0 0 14px;
        font-size:14px;
        color:#4b5563;
      }

      .rx-submit-row{
        display:flex;
        align-items:center;
        gap:10px;
        margin-bottom:12px;
      }

      .rx-submit-spin{
        width:18px;
        height:18px;
        border-radius:999px;
        border:3px solid rgba(0,0,0,.12);
        border-top-color:#2563eb;
        animation:rxSubmitSpin .9s linear infinite;
      }

      .rx-submit-barp{
        height:12px;
        border-radius:999px;
        background:#e5e7eb;
        overflow:hidden;
        position:relative;
      }

      .rx-submit-bar{
        height:100%;
        width:100%;
        border-radius:999px;
        background:linear-gradient(90deg,#60a5fa 0%, #2563eb 45%, #60a5fa 100%);
        background-size:200% 100%;
        animation:rxSubmitBar 1.2s linear infinite;
      }

      .rx-submit-meta{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-top:8px;
        font-size:12px;
        color:#6b7280;
      }

      .rx-submit-btn-loading{
        position:relative;
        overflow:hidden;
        filter:saturate(1.08);
      }

      .rx-submit-btn-loading::after{
        content:"";
        position:absolute;
        top:0;
        bottom:0;
        left:-35%;
        width:35%;
        transform:skewX(-20deg);
        background:linear-gradient(90deg,transparent,rgba(255,255,255,.38),transparent);
        animation:rxBtnSweep 1.1s linear infinite;
      }

      @keyframes rxSubmitSpin{
        to{ transform:rotate(360deg); }
      }

      @keyframes rxSubmitBar{
        0%{ background-position:200% 0; }
        100%{ background-position:-200% 0; }
      }

      @keyframes rxBtnSweep{
        100%{ left:135%; }
      }

      @media (prefers-color-scheme: dark){
        .rx-submit-box{ background:#0f172a; }
        .rx-submit-title{ color:#e5e7eb; }
        .rx-submit-text,
        .rx-submit-meta{ color:#cbd5e1; }
        .rx-submit-barp{ background:#1e293b; }
        .rx-submit-spin{
          border-color:rgba(255,255,255,.15);
          border-top-color:#60a5fa;
        }
      }
    `;

    document.head.appendChild(
      Object.assign(document.createElement('style'), { textContent: css })
    );
  }

  function showSubmitLoader(message = 'Estamos generando la receta. Esto puede tardar unos segundos.') {
    ensureSubmitFxCSS();

    const $btn = $('#recetaForm').find('button[type="submit"]').last();
    $btn.addClass('rx-submit-btn-loading').prop('disabled', true);

    let $ov = $('#rxSubmitLoader');

    if (!$ov.length) {
      $ov = $(`
        <div id="rxSubmitLoader" class="rx-submit-back" aria-live="polite" aria-busy="true">
          <div class="rx-submit-box">
            <div class="rx-submit-title">Generando receta</div>
            <p class="rx-submit-text"></p>

            <div class="rx-submit-row">
              <div class="rx-submit-spin"></div>
              <strong>Procesando…</strong>
            </div>

            <div class="rx-submit-barp">
              <div class="rx-submit-bar"></div>
            </div>

            <div class="rx-submit-meta">
              <span>Preparando documento y validando datos</span>
              <span>100%</span>
            </div>
          </div>
        </div>
      `);

      $(document.body).append($ov);
    }

    $ov.find('.rx-submit-text').text(message);
  }

  function hideSubmitLoader() {
    $('#rxSubmitLoader').remove();

    const $btn = $('#recetaForm').find('button[type="submit"]').last();
    $btn.removeClass('rx-submit-btn-loading');
  }



  // =========================================================
  // Submit (validaciones + AJAX)
  // =========================================================

    function initSubmit() {
    const $f = $('#recetaForm');
    if (!$f.length) return;

    $f.off('submit.rxSubmit').on('submit.rxSubmit', function (e) {
      e.preventDefault();

      const items = [];

      const idNom = String($('#id_nomina').val() || '').trim();
      if (!idNom) items.push('Nómina es obligatoria.');

      syncProvinciaTextFromSelects();

      const vis = $('#paciente_fecha_visual').val();
      const iso = esAISO(vis);
      if (vis && iso) $('[name="paciente[fechaNacimiento]"]').val(iso);

      const dni = ($('[name="paciente[nroDoc]"]').val() || '').replace(/\D+/g, '');
      if (!dni) items.push('DNI del paciente es obligatorio.');
      else if (dni.length < 7 || dni.length > 9) items.push('DNI del paciente debe tener entre 7 y 9 dígitos.');

      const sexo = String($('[name="paciente[sexo]"]').val() || '').toUpperCase();
      if (!['M', 'F', 'X', 'O'].includes(sexo)) items.push('Sexo seleccionado no es válido.');

      const fiso = $('[name="paciente[fechaNacimiento]"]').val();
      if (!fiso) items.push('Fecha de nacimiento es obligatoria.');
      else {
        const d = new Date(fiso + 'T00:00:00');
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (isNaN(d.getTime())) items.push('Fecha de nacimiento inválida.');
        else if (d > today) items.push('La fecha de nacimiento no puede ser futura.');
      }

      const tipoMat = String($('[name="medico[matricula][tipo]"]').val() || 'MN').toUpperCase();
      const nroMat = String($('[name="medico[matricula][numero]"]').val() || '').replace(/\D+/g, '');
      const provMatTxt = String($('[name="medico[matricula][provincia]"]').val() || '').trim();
      const provMatId = String($('[name="medico[matricula][id_provincia]"]').val() || '').trim();

      if (!nroMat) items.push('Número de matrícula es obligatorio.');
      else if (nroMat.length > 10) items.push('Número de matrícula: máximo 10 dígitos.');

      if (tipoMat === 'MP' && !provMatTxt && !provMatId) {
        items.push('Para matrícula provincial (MP), la provincia es obligatoria.');
      }

      const dCalle = String($('[name="domicilio[calle]"]').val() || '').trim();
      const dNum = String($('[name="domicilio[numero]"]').val() || '').trim();
      const dProvTxt = String($('[name="domicilio[provincia]"]').val() || '').trim();
      const dProvId = String($('[name="domicilio[id_provincia]"]').val() || '').trim();

      if (!dCalle) items.push('Domicilio: la calle es obligatoria.');
      if (!dNum) items.push('Domicilio: el número es obligatorio.');
      if (!dProvTxt && !dProvId) items.push('Domicilio: la provincia es obligatoria.');

      const idFin = String($('[name="cobertura[idFinanciador]"]').val() || '').trim();
      const cred = String($('[name="cobertura[credencial]"]').val() || '').trim();

      if (idFin) {
        if (!cred) items.push('Cobertura: el número de afiliado es obligatorio si indicás un financiador.');
        else if (!/^\d+$/.test(cred)) {
          items.push('Cobertura: el número de afiliado debe tener solo números (sin puntos ni guiones).');
        }
      }

      const hasPract = practicasCount() > 0;
      const $rows = $('#medsWrapper .med-row');
      let hasMeds = false;

      $rows.each(function () {
        const reg = String($(this).find('.regno').val() || '').trim();
        const sel = $(this).find('.sel-medicamento').val();

        if (reg || (sel && String(sel).trim() !== '')) {
          hasMeds = true;
          return false;
        }
      });

      if (hasMeds && hasPract) {
        items.push('No se pueden cargar medicamentos y prácticas en la misma receta. Elegí solo una opción.');
      }

      if (!hasMeds && !hasPract) {
        items.push('Tenés que cargar al menos un medicamento o una práctica.');
      }

      if (hasPract && !hasMeds) {
        if (practicasCount() > 10) items.push('Prácticas: máximo 10 prácticas por receta.');
      }

      if (hasMeds && !hasPract) {
        $rows.each(function (i) {
          const $r = $(this);
          const cant = Number($r.find('input[name^="medicamentos"][name$="[cantidad]"]').val() || 0);
          const reg = String($r.find('.regno').val() || '').trim();
          const sel = $r.find('.sel-medicamento').val();

          if (!reg && (!sel || String(sel).trim() === '')) {
            items.push(`Medicamento (fila ${i + 1}): seleccioná un medicamento o eliminá la fila.`);
            return;
          }

          const pre = String($r.find('.presentacion').val() || '').trim();
          const nom = String($r.find('.nombre').val() || '').trim();
          const dro = String($r.find('.droga').val() || '').trim();
          const trat = String($r.find('input[name^="medicamentos"][name$="[tratamiento]"]').val() || '').trim();

          if (!cant || cant < 1) items.push(`Medicamento (fila ${i + 1}): la cantidad debe ser mayor a 0.`);
          if (!pre) items.push(`Medicamento (fila ${i + 1}): la presentación es obligatoria.`);
          if (!nom) items.push(`Medicamento (fila ${i + 1}): el nombre es obligatorio.`);
          if (!dro) items.push(`Medicamento (fila ${i + 1}): la droga es obligatoria.`);
          if (trat !== '' && isNaN(Number(trat))) items.push(`Medicamento (fila ${i + 1}): tratamiento inválido.`);
        });
      }

      const $chkFirma = $('#incluir_firma_medico');
      if ($chkFirma.length) {
        const incluirFirma = $chkFirma.is(':checked');
        const hasFirma = String($chkFirma.data('hasFirma') || $chkFirma.data('has-firma') || '') === '1';

        if (incluirFirma && !hasFirma) {
          items.push('Marcaste incluir firma, pero este médico no tiene firma cargada en su cuenta.');
        }

        if (incluirFirma && hasFirma) {
          $('#box_sello_medico .sello-field').each(function () {
            const ml = parseInt(this.getAttribute('maxlength'), 10);
            if (ml && this.value.length > ml) {
              items.push($(this).closest('.form-group').find('label').text().trim() + ': máximo ' + ml + ' caracteres.');
            }
          });
        }
      }

      if (items.length) {
        modal({ title: 'Revisá estos datos', html: errList(items) });
        return;
      }

      const url = $f.attr('action');
      const data = $f.serialize();
      const $btn = $f.find('button[type="submit"]').last();
      const old = $btn.html();

      let redirecting = false;
      let deferredModal = null;

      $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Generando…');
      showSubmitLoader('Estamos generando la receta. Esto puede tardar unos segundos.');

      $.ajax({
        type: 'POST',
        url,
        data,
        headers: { 'Accept': 'application/json' },
        timeout: AJAX_TIMEOUT_MS
      })
        .done(r => {
          if (r && r.ok) {
            redirecting = true;

            if (r.url) {
              location.assign(new URL(r.url, location.origin).toString());
              return;
            }

            if (r.show) {
              location.assign(new URL(r.show, location.origin).toString());
              return;
            }

            location.assign(location.origin + '/empleados/recetas');
            return;
          }

          deferredModal = {
            title: 'Aviso',
            html: `<p>${esc(r?.message || 'Operación realizada.')}</p>`
          };
        })
        .fail(jq => {
          let items2 = [];

          if (jq.status === 422) {
            const e = (jq.responseJSON || {}).errors || {};
            items2 = Object.keys(e).map(k => `${k}: ${(e[k] || []).join(' ')}`);
          } else if (jq.status === 504) {
            items2 = ['El servicio tardó demasiado en responder. Probá nuevamente.'];
          } else {
            try {
              const j = jq.responseJSON || JSON.parse(jq.responseText || '{}');
              const msg = j.message || j.mensaje || jq.statusText || 'Error al generar la receta.';
              if (msg) items2.push(msg);
              if (j.code) items2.push('Código técnico: ' + j.code);
            } catch {
              items2.push('Error desconocido al generar la receta.');
            }
          }

          deferredModal = {
            title: 'No se pudo generar la receta',
            html: errList(items2)
          };
        })
        .always(() => {
          if (redirecting) return;

          hideSubmitLoader();
          $btn.prop('disabled', false).html(old);

          if (deferredModal) {
            modal(deferredModal);
          }
        });
    });
  }

  // =========================================================
  // Lock médico (no editable, pero se envía en POST)
  // =========================================================
  let medLockCssReady = false;
  function ensureMedLockCSS() {
    if (medLockCssReady) return;
    medLockCssReady = true;

    const css = `
      .med-lock-input{ background:#e9ecef !important; }
      .med-lock-select{ background:#e9ecef !important; cursor:not-allowed !important; }
      @media (prefers-color-scheme: dark){
        .med-lock-input, .med-lock-select{ background: rgba(148,163,184,.18) !important; }
      }
    `;
    document.head.appendChild(Object.assign(document.createElement('style'), { textContent: css }));
  }

  function hardLockSelect($el) {
    const lockedVal = $el.val();
    $el.data('lockedVal', lockedVal);

    $el
      .addClass('med-lock-select')
      .attr('tabindex', '-1')
      .on('change.medLock', function () {
        $(this).val($(this).data('lockedVal'));
      })
      .on('mousedown.medLock click.medLock keydown.medLock', function (e) {
        e.preventDefault();
        e.stopPropagation();
        this.blur();
        $(this).val($(this).data('lockedVal'));
        return false;
      })
      .on('select2:opening.medLock select2:unselecting.medLock', e => e.preventDefault());
  }

  function lockMedicoFields() {
    ensureMedLockCSS();

    const $f = $('#recetaForm');
    if (!$f.length) return;

    $f.find(
      '[name="medico[apellido]"],' +
      '[name="medico[nombre]"],' +
      '[name="medico[nroDoc]"],' +
      '[name="medico[matricula][numero]"]'
    ).each(function () {
      const $el = $(this);
      if ($el.data('medLocked')) return;
      $el.data('medLocked', 1);
      $el.prop('readonly', true).addClass('med-lock-input');
    });

    $f.find(
      '[name="medico[tipoDoc]"],' +
      '[name="medico[sexo]"],' +
      '[name="medico[matricula][tipo]"],' +
      '[name="medico[matricula][id_provincia]"]'
    ).each(function () {
      const $el = $(this);
      if ($el.data('medLocked')) return;
      $el.data('medLocked', 1);
      hardLockSelect($el);
    });
  }

  // =========================================================
  // Boot
  // =========================================================
  function boot() {
    $.ajaxSetup({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      timeout: AJAX_TIMEOUT_MS
    });

    $(document).off('change.firmaMedico').on('change.firmaMedico', '#incluir_firma_medico', function () {
      const hasFirma = String($(this).data('hasFirma') || $(this).data('has-firma') || '') === '1';
      if ($(this).is(':checked') && !hasFirma) {
        modal({
          title: 'Sin firma cargada',
          html: '<p>Este médico no tiene firma cargada en su cuenta. Cargala desde tu perfil y volvé a intentar.</p>'
        });
        $(this).prop('checked', false);
      }
    });

    initFirmaSelloUI();

    ensureRxLayout();
    applyRxXorUI();

    // HARD BLOCK: si hay prácticas → no abrir select2 de medicamentos
    $(document)
      .off('select2:opening.rxHardBlock')
      .on('select2:opening.rxHardBlock', '.sel-medicamento', function (e) {
        if (practicasCount() > 0 || RX_MODE === 'practicas' || RX_FORCE === 'practicas') {
          e.preventDefault();
          modal({
            title: 'No se puede mezclar',
            html: '<p>Esta receta es <b>solo de prácticas</b>. Quitá las prácticas para poder cargar medicamentos.</p>'
          });
        }
      });

    // HARD BLOCK: si hay medicamentos → no abrir select2 de prácticas
    $(document)
      .off('select2:opening.rxHardBlockPract')
      .on('select2:opening.rxHardBlockPract', '#practica_search', function (e) {
        if (hasMedsSelected() || RX_MODE === 'meds') {
          e.preventDefault();
          modal({
            title: 'No se puede mezclar',
            html: '<p>Esta receta es <b>solo de medicamentos</b>. Quitá los medicamentos para poder cargar prácticas.</p>'
          });
        }
      });

    initSexo();
    initFecha();
    initSubmit();

    let tries = 0;
    const iv = setInterval(function () {
      tries++;

      if (typeof $.fn.select2 === 'function') {
        clearInterval(iv);

        fetchProvincias().then(() => {
          initMatricula();
          initNomina();
          initFinanciadores();
          initDiagnosticos();
          initMeds();
          initPracticas();

          ensureRxLayout();
          applyRxXorUI();
        });

      } else if (tries > 60) {
        clearInterval(iv);
        fetchProvincias().then(() => { initMatricula(); });

        initMeds();
        ensureRxLayout();
        applyRxXorUI();
      }
    }, 100);
  }

  $(boot);

})(jQuery, window, document);