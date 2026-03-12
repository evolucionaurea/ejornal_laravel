/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 64);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/empleados/recetas/create.js":
/*!**************************************************!*\
  !*** ./resources/js/empleados/recetas/create.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function ($, window, document) {
  'use strict';

  var AJAX_TIMEOUT_MS = 20000;

  // =========================================================
  // Modal simple
  // =========================================================
  var modalCss = false;
  function modalCSS() {
    if (modalCss) return;
    modalCss = true;
    var css = "\n      .mm-back{position:fixed;inset:0;background:rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;z-index:9999}\n      .mm{background:#fff;border-radius:12px;box-shadow:0 15px 50px rgba(0,0,0,.25);max-width:640px;width:94%;padding:18px 20px}\n      .mm h3{margin:0 0 10px;font-size:18px;font-weight:800;color:#111}\n      .mm p{margin:0 0 10px;font-size:14px;color:#333}\n      .mm .row{display:flex;justify-content:flex-end;gap:8px;margin-top:14px}\n      .mm .btn{border:0;border-radius:8px;padding:9px 14px;font-size:14px;cursor:pointer}\n      .mm .b1{background:#2563eb;color:#fff}\n      .mm ul.mm-list{list-style:none;margin:6px 0 0;padding:0}\n      .mm ul.mm-list li{display:flex;align-items:flex-start;gap:10px; padding:8px 10px; border-radius:8px; background:#f8fafc; margin-bottom:8px; font-size:14px; color:#0f172a}\n      .mm ul.mm-list li .dot{width:8px;height:8px;border-radius:999px;background:#2563eb; margin-top:6px; flex:0 0 8px}\n      @media (prefers-color-scheme: dark){\n        .mm{background:#0f172a;color:#e2e8f0}\n        .mm h3{color:#e5e7eb}\n        .mm p{color:#cbd5e1}\n        .mm ul.mm-list li{background:#0b1221;color:#e2e8f0}\n        .mm ul.mm-list li .dot{background:#60a5fa}\n      }";
    document.head.appendChild(Object.assign(document.createElement('style'), {
      textContent: css
    }));
  }
  function modal() {
    var _ref = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {},
      _ref$title = _ref.title,
      title = _ref$title === void 0 ? 'Atención' : _ref$title,
      _ref$html = _ref.html,
      html = _ref$html === void 0 ? '<p>Ocurrió un error.</p>' : _ref$html;
    modalCSS();
    var $b = $('<div class="mm-back" role="dialog" aria-modal="true"></div>');
    var $m = $("<div class=\"mm\"><h3>".concat(title, "</h3>").concat(html, "<div class=\"row\"><button class=\"btn b1\">Aceptar</button></div></div>"));
    $b.append($m).appendTo(document.body);
    var close = function close() {
      return $b.remove();
    };
    $b.on('click', function (e) {
      if (e.target === $b[0]) close();
    });
    $m.find('.b1').on('click', close);
  }
  var esc = function esc(s) {
    return String(s !== null && s !== void 0 ? s : '').replace(/[&<>"]/g, function (m) {
      return {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;'
      }[m];
    });
  };
  var errList = function errList() {
    var items = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
    return "<ul class=\"mm-list\">".concat(items.map(function (it) {
      return "<li><span class=\"dot\"></span><div>".concat(esc(it), "</div></li>");
    }).join(''), "</ul>");
  };

  // =========================================================
  // XOR UI (Medicamentos vs Prácticas)
  // =========================================================
  var RX_MODE = 'none'; // none | meds | practicas
  var RX_FORCE = null; // null | 'practicas'
  var RX_LAST = 'none'; // none | meds | practicas

  var rxCssReady = false;
  var rxLayoutBound = false;
  var $MED_CARD = $(),
    $PRA_CARD = $();
  function ensureRxCSS() {
    if (rxCssReady) return;
    rxCssReady = true;

    // Grisado SOLO visual. No se usa pointer-events:none porque rompe select2:opening.
    var css = "\n      .rx-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap}\n      .rx-head-off{opacity:.65;filter:grayscale(.18)}\n      .rx-off{position:relative;opacity:.60;filter:grayscale(.25)}\n      .rx-off::after{\n        content:\"\";\n        position:absolute; inset:0;\n        background:rgba(255,255,255,.35);\n        border-radius:10px;\n        pointer-events:none;\n      }\n      @media (prefers-color-scheme: dark){\n        .rx-off::after{ background:rgba(15,23,42,.35); }\n      }\n\n      .rx-off .select2-selection{\n        background:#e9ecef !important;\n        cursor:not-allowed !important;\n      }\n      @media (prefers-color-scheme: dark){\n        .rx-off .select2-selection{\n          background: rgba(148,163,184,.18) !important;\n        }\n      }\n    ";
    document.head.appendChild(Object.assign(document.createElement('style'), {
      textContent: css
    }));
  }
  function ensurePracticasHidden() {
    var $h = $('#practicasHidden');
    if (!$h.length) {
      var $f = $('#recetaForm');
      $h = $('<div/>', {
        id: 'practicasHidden',
        "class": 'd-none'
      });
      ($f.length ? $f : $(document.body)).append($h);
    }
    return $h;
  }
  function ensurePracticasList() {
    var $l = $('#practicasList');
    if (!$l.length) {
      var $s = $('#practica_search');
      $l = $('<div/>', {
        id: 'practicasList',
        "class": 'mt-2'
      });
      if ($s.length) $s.closest('.form-group').append($l);else $(document.body).append($l);
    }
    return $l;
  }
  function practicasCount() {
    var nHidden = ensurePracticasHidden().find('input[name="practicas[]"]').length;
    var nChips = $('#practicasList [data-id]').length;
    return Math.max(nHidden, nChips);
  }
  function hasMedsSelected() {
    var any = false;
    $('#medsWrapper .med-row').each(function () {
      var reg = String($(this).find('.regno').val() || '').trim();
      var sel = $(this).find('.sel-medicamento').val();
      if (reg || sel && String(sel).trim() !== '') {
        any = true;
        return false;
      }
    });
    return any;
  }
  function clearPracticasUI() {
    ensurePracticasHidden().find('input[name="practicas[]"]').remove();
    ensurePracticasList().empty();
    var $s = $('#practica_search');
    if ($s.length) $s.val(null).trigger('change');
  }
  function clearMedsUI() {
    var $wrap = $('#medsWrapper');
    if (!$wrap.length) return;
    $wrap.children('.med-row').slice(1).remove();
    var $r = $wrap.children('.med-row').first();
    if (!$r.length) return;
    $r.find('.sel-medicamento').val(null).trigger('change');
    $r.find('input[type="number"], input[type="text"], textarea').val('');
    $r.find('.duplicado').prop('checked', false);
  }
  function ensureRxLayout() {
    ensureRxCSS();
    ensurePracticasHidden();
    ensurePracticasList();
    var $medWrap = $('#medsWrapper');
    $MED_CARD = $medWrap.closest('.receta-card');
    var $praSel = $('#practica_search');
    $PRA_CARD = $praSel.closest('.receta-card');

    // --- Botón quitar meds (siempre existe y queda cerca del botón agregar)
    var $btnClearMeds = $('#btnClearMeds');
    if (!$btnClearMeds.length) {
      $btnClearMeds = $('<button/>', {
        id: 'btnClearMeds',
        type: 'button',
        "class": 'btn-ejornal btn-ejornal-gris-claro',
        text: 'Quitar medicamentos'
      }).css({
        'white-space': 'nowrap'
      });
    } else {
      $btnClearMeds.attr('type', 'button');
    }
    var $btnAddMed = $('#btnAddMed');
    if ($btnAddMed.length) {
      // lo pego al lado de agregar (sin depender de headers)
      if (!$btnClearMeds.parent().is($btnAddMed.parent())) $btnClearMeds.insertAfter($btnAddMed);
    } else if ($MED_CARD.length && !$btnClearMeds.closest('.receta-card').is($MED_CARD)) {
      $MED_CARD.prepend($('<div class="rx-actions mb-2"></div>').append($btnClearMeds));
    }

    // --- Botón quitar prácticas
    var $btnClearPract = $('#btnClearPracticas');
    if (!$btnClearPract.length) {
      $btnClearPract = $('<button/>', {
        id: 'btnClearPracticas',
        type: 'button',
        "class": 'btn-ejornal btn-ejornal-gris-claro',
        text: 'Quitar práctica'
      }).css({
        'white-space': 'nowrap'
      });
    } else {
      $btnClearPract.attr('type', 'button');
    }
    if ($praSel.length) {
      var $s2 = $praSel.next('.select2');
      if ($s2.length) {
        // 👇 la fila que agregamos está DESPUÉS del .select2, no es un padre
        var $row = $s2.next('.rx-actions');
        if (!$row.length) {
          $row = $('<div class="rx-actions mt-2"></div>');
          $s2.after($row);
        }

        // Si quedaron filas duplicadas por el bug anterior, las borramos
        $s2.parent().children('.rx-actions').not($row).remove();
        if (!$btnClearPract.parent().is($row)) $row.append($btnClearPract);
      } else {
        // fallback si select2 aún no armó su DOM
        var $fg = $praSel.closest('.form-group');
        var _$row = $fg.find('> .rx-actions').first();
        if (!_$row.length) {
          _$row = $('<div class="rx-actions mt-2"></div>');
          $fg.append(_$row);
        }
        if (!$btnClearPract.parent().is(_$row)) _$row.append($btnClearPract);
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
    var $wrap = $('#medsWrapper');
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
    var $s = $('#practica_search');
    if ($s.length) $s.trigger('change.select2');

    // Sí deshabilito botones de quitar chip para evitar edición en modo bloqueado
    $('#practicasList .quitar-chip').prop('disabled', !enabled);
  }
  function applyRxXorUI() {
    ensureRxLayout();
    var hasPract = practicasCount() > 0;
    var hasMeds = hasMedsSelected();

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
  var spCSS = false;
  function ensureSpinner() {
    if (spCSS) return;
    spCSS = true;
    var css = "@keyframes miniSpin{to{transform:rotate(360deg)}}.msw{display:inline-block;margin-left:6px;vertical-align:middle}.ms{width:16px;height:16px;animation:miniSpin .9s linear infinite}.ms circle{stroke-linecap:round}";
    document.head.appendChild(Object.assign(document.createElement('style'), {
      textContent: css
    }));
  }
  function spinnerSvg() {
    ensureSpinner();
    return '<span class="msw"><svg class="ms" viewBox="0 0 50 50"><defs><linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="#2196f3"/><stop offset="100%" stop-color="#81d4fa"/></linearGradient></defs><circle cx="25" cy="25" r="20" stroke="url(#g)" fill="none" stroke-width="5"/></svg></span>';
  }
  function labelLoader($sel, on) {
    var $l = $sel.closest('.form-group').find('label').first();
    if (!$l.length) return;
    on ? !$l.find('.msw').length && $l.append(spinnerSvg()) : $l.find('.msw').remove();
  }

  // =========================================================
  // Loader sobre el formulario
  // =========================================================
  var formLoaderReady = false;
  function ensureFormLoaderCSS() {
    if (formLoaderReady) return;
    formLoaderReady = true;
    var css = "\n      .fl-back{position:absolute; inset:0; background: rgba(255,255,255,.75); display:flex; align-items:center; justify-content:center; z-index: 9998; border-radius: 12px;}\n      @media (prefers-color-scheme: dark){ .fl-back{ background: rgba(15,23,42,.72); } }\n      .fl-box{display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius: 12px; background: rgba(255,255,255,.95); box-shadow: 0 10px 30px rgba(0,0,0,.18); font-size: 14px; color:#0f172a;}\n      @media (prefers-color-scheme: dark){ .fl-box{ background: rgba(2,6,23,.92); color:#e2e8f0; } }\n      .fl-spin{width:18px; height:18px; border-radius:999px; border: 3px solid rgba(0,0,0,.12); border-top-color: rgba(0,0,0,.55); animation: flrot .9s linear infinite;}\n      @media (prefers-color-scheme: dark){ .fl-spin{ border-color: rgba(255,255,255,.15); border-top-color: rgba(255,255,255,.65); } }\n      @keyframes flrot{to{transform:rotate(360deg)}}\n    ";
    document.head.appendChild(Object.assign(document.createElement('style'), {
      textContent: css
    }));
  }
  function showFormLoader() {
    var msg = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'Cargando datos del trabajador…';
    ensureFormLoaderCSS();
    var $f = $('#recetaForm');
    if (!$f.length) return;
    var $wrap = $f.closest('.tarjeta');
    var $host = $wrap.length ? $wrap : $f;
    if ($host.find('.fl-back').length) return;
    if ($host.css('position') === 'static') $host.css('position', 'relative');
    var $ov = $("\n      <div class=\"fl-back\" aria-live=\"polite\" aria-busy=\"true\">\n        <div class=\"fl-box\">\n          <div class=\"fl-spin\"></div>\n          <div class=\"fl-msg\">".concat(msg, "</div>\n        </div>\n      </div>\n    "));
    $host.append($ov);
  }
  function hideFormLoader() {
    var $f = $('#recetaForm');
    if (!$f.length) return;
    var $wrap = $f.closest('.tarjeta');
    var $host = $wrap.length ? $wrap : $f;
    $host.find('.fl-back').remove();
  }

  // =========================================================
  // Helpers URL / fechas / select2
  // =========================================================
  var pendingFechaISO = '';
  var PROV_BY_ID = {};
  var PROV_ID_BY_NAME = {};
  var provinciasCache = null;
  var pendingDomProvId = '';
  var pendingDomProvName = '';
  function rurl(u) {
    try {
      if (!u) return '/';
      if (/^https?:\/\//i.test(u)) return u;
      return new URL(u, location.origin).toString();
    } catch (_unused) {
      return u;
    }
  }
  function urls() {
    var $f = $('#recetaForm');
    return {
      financiadores: rurl($f.data('url-get-financiadores')),
      diagnosticos: rurl($f.data('url-get-diagnosticos')),
      medicamentos: rurl($f.data('url-get-medicamentos')),
      practicas: rurl($f.data('url-get-practicas')),
      provincias: rurl($f.data('url-get-provincias'))
    };
  }
  var isoAEs = function isoAEs(iso) {
    if (!iso) return '';
    var s = String(iso).trim();
    var m = s.match(/^(\d{4})-(\d{2})-(\d{2})/);
    return m ? "".concat(m[3], "/").concat(m[2], "/").concat(m[1]) : '';
  };
  var esAISO = function esAISO(es) {
    if (!es) return '';
    var m = /^(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})$/.exec((es + '').trim());
    if (!m) return '';
    var dd = ('0' + m[1]).slice(-2),
      mm = ('0' + m[2]).slice(-2);
    return "".concat(m[3], "-").concat(mm, "-").concat(dd);
  };
  function normalizeISO(input) {
    if (!input) return '';
    var s = String(input).trim();
    var m = s.match(/^(\d{4}-\d{2}-\d{2})/);
    return m ? m[1] : '';
  }
  function s2Small($sel) {
    var $w = $sel.next('.select2'),
      $s = $w.find('.select2-selection--single');
    $s.css({
      height: '31px',
      'min-height': '31px',
      'border-radius': '.2rem',
      'border-color': '#ced4da',
      padding: '2px 8px'
    });
    $s.find('.select2-selection__rendered').css({
      'line-height': '26px',
      'font-size': '.875rem'
    });
    $s.find('.select2-selection__arrow').css({
      height: '29px',
      right: '6px'
    });
  }
  function s2Ajax($sel, _ref2) {
    var url = _ref2.url,
      _ref2$minLen = _ref2.minLen,
      minLen = _ref2$minLen === void 0 ? 2 : _ref2$minLen,
      dataFn = _ref2.dataFn,
      procFn = _ref2.procFn,
      tpl = _ref2.tpl,
      langNoResults = _ref2.langNoResults;
    $sel.select2({
      width: '100%',
      placeholder: 'Buscar…',
      allowClear: false,
      minimumInputLength: minLen,
      ajax: {
        delay: 300,
        url: url,
        dataType: 'json',
        cache: true,
        data: function data(p) {
          return dataFn ? dataFn(p) : {
            q: p.term || '',
            page: p.page || 1
          };
        },
        processResults: function processResults(res) {
          return procFn ? procFn(res) : res;
        },
        beforeSend: function beforeSend() {
          return labelLoader($sel, true);
        },
        complete: function complete() {
          return labelLoader($sel, false);
        },
        headers: {
          'Accept': 'application/json'
        },
        transport: function transport(params, success, failure) {
          var cfg = Object.assign({}, params, {
            timeout: AJAX_TIMEOUT_MS
          });
          return $.ajax(cfg).done(success).fail(failure);
        }
      },
      language: {
        noResults: function noResults() {
          return langNoResults || 'Sin resultados';
        }
      },
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
      headers: {
        'Accept': 'application/json'
      },
      timeout: AJAX_TIMEOUT_MS
    }).then(function (j) {
      var arr = Array.isArray(j === null || j === void 0 ? void 0 : j.results) ? j.results : [];
      provinciasCache = arr;
      PROV_BY_ID = {};
      PROV_ID_BY_NAME = {};
      arr.forEach(function (p) {
        var _p$nombre;
        var id = p === null || p === void 0 ? void 0 : p.id;
        var nom = String((_p$nombre = p === null || p === void 0 ? void 0 : p.nombre) !== null && _p$nombre !== void 0 ? _p$nombre : '').trim();
        if (id != null && nom) {
          PROV_BY_ID[String(id)] = nom;
          PROV_ID_BY_NAME[nom.toLowerCase()] = String(id);
        }
      });
      return arr;
    })["catch"](function () {
      provinciasCache = [];
      PROV_BY_ID = {};
      PROV_ID_BY_NAME = {};
      return [];
    });
  }
  function toSelect($input, items) {
    var _ref3 = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {},
      name = _ref3.name;
    var current = String($input.val() || '').trim();
    var $sel = $('<select class="form-control form-control-sm"></select>');
    if (name) $sel.attr('name', name);
    if ($input.attr('id')) $sel.attr('id', $input.attr('id'));
    $sel.append($('<option>').val('').text('Seleccione…'));
    items.forEach(function (p) {
      var _p$nombre2;
      var id = (p === null || p === void 0 ? void 0 : p.id) != null ? String(p.id) : '';
      var nom = String((_p$nombre2 = p === null || p === void 0 ? void 0 : p.nombre) !== null && _p$nombre2 !== void 0 ? _p$nombre2 : '').trim();
      $sel.append($('<option>').val(id).text(nom || id));
    });
    $input.replaceWith($sel);
    if (current) {
      $sel.val(current);
      if (!$sel.val()) {
        var c = current.toLowerCase();
        var $opt = $sel.find('option').filter(function () {
          return String($(this).text() || '').trim().toLowerCase() === c;
        }).first();
        if ($opt.length) $sel.val($opt.val());
      }
    }
    return $sel;
  }
  function ensureProvinciaPair(items, _ref4) {
    var visibleName = _ref4.visibleName,
      idName = _ref4.idName;
    var $f = $('#recetaForm');
    if (!$f.length) return null;
    var $hiddenName = $f.find("[name=\"".concat(visibleName, "\"]"));
    var $idField = $f.find("[name=\"".concat(idName, "\"]"));
    var $host = $hiddenName;
    if ($hiddenName.length > 1) {
      $hiddenName.slice(1).remove();
      $hiddenName = $f.find("[name=\"".concat(visibleName, "\"]")).first();
    }
    if ($idField.length > 1) {
      $idField.slice(1).remove();
      $idField = $f.find("[name=\"".concat(idName, "\"]")).first();
    }
    if (!$hiddenName.length) {
      $hiddenName = $("<input type=\"hidden\" name=\"".concat(visibleName, "\">"));
      $f.append($hiddenName);
    }
    if ($hiddenName.is('input') && ($hiddenName.attr('type') || 'text').toLowerCase() !== 'hidden') {
      var txt = String($hiddenName.val() || '').trim();
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
    var curId = String($f.find("[name=\"".concat(idName, "\"]")).val() || '').trim();
    var curText = String($hiddenName.val() || '').trim();
    var $sel = toSelect($host, items, {
      name: idName
    });
    if (curId) $sel.val(curId);
    if (!$sel.val() && curText) {
      var k = curText.toLowerCase();
      if (PROV_ID_BY_NAME[k]) $sel.val(PROV_ID_BY_NAME[k]);
    }
    $sel.on('change', function () {
      var txt = String($(this).find('option:selected').text() || '').trim();
      $hiddenName.val(txt);
    });
    $sel.trigger('change');
    return $sel;
  }
  function syncProvinciaTextFromSelects() {
    var $f = $('#recetaForm');
    if (!$f.length) return;
    var $domSel = $f.find('[name="domicilio[id_provincia]"]');
    var $domTxt = $f.find('[name="domicilio[provincia]"]');
    if (!$domTxt.length) {
      $domTxt = $('<input type="hidden" name="domicilio[provincia]">');
      $f.append($domTxt);
    }
    if ($domSel.length) {
      var txt = String($domSel.find('option:selected').text() || '').trim();
      if (txt && txt !== 'Seleccione…') $domTxt.val(txt);
    }
    var $matSel = $f.find('[name="medico[matricula][id_provincia]"]');
    var $matTxt = $f.find('[name="medico[matricula][provincia]"]');
    if (!$matTxt.length) {
      $matTxt = $('<input type="hidden" name="medico[matricula][provincia]">');
      $f.append($matTxt);
    }
    if ($matSel.length) {
      var _txt = String($matSel.find('option:selected').text() || '').trim();
      if (_txt && _txt !== 'Seleccione…') $matTxt.val(_txt);
    }
  }
  function setRequired($field, required) {
    if (!$field || !$field.length) return;
    var $fg = $field.closest('.form-group');
    var $label = $fg.find('label').first();
    $field.prop('required', !!required);
    $label.find('.req').remove();
    if (required) $label.append(' <span class="req text-danger">*</span>');
  }

  // =========================================================
  // Nómina → autocompletado
  // =========================================================
  function splitNombre(full) {
    full = (full || '').trim().replace(/\s+/g, ' ');
    if (!full) return {
      apellido: '',
      nombre: ''
    };
    if (full.includes(',')) {
      var a = full.split(',');
      return {
        apellido: a[0].trim(),
        nombre: (a[1] || '').trim()
      };
    }
    var i = full.indexOf(' ');
    return i === -1 ? {
      apellido: '',
      nombre: full
    } : {
      apellido: full.slice(0, i),
      nombre: full.slice(i + 1)
    };
  }
  function clearPaciente() {
    var $f = $('#recetaForm');
    var set = function set(n, v) {
      var $el = $f.find("[name=\"".concat(n, "\"]"));
      if ($el.length) $el.val(v !== null && v !== void 0 ? v : '').trigger('input').trigger('change');
    };
    set('paciente[nombre]', '');
    set('paciente[apellido]', '');
    set('paciente[nroDoc]', '');
    set('paciente[email]', '');
    set('paciente[telefono]', '');
    set('paciente[fechaNacimiento]', '');
    set('paciente[sexo]', 'M');
    var $vis = $('#paciente_fecha_visual');
    if ($vis.length) $vis.val('');
  }
  function setPaciente(d) {
    var empty = !d || !String(d.nombre || '').trim() && !String(d.apellido || '').trim() && !String(d.dni || '').trim() && !String(d.email || '').trim() && !String(d.telefono || '').trim() && !String(d.fechaNacimiento || '').trim();
    if (empty) {
      clearPaciente();
      return;
    }
    var $f = $('#recetaForm');
    var set = function set(n, v) {
      var $el = $f.find("[name=\"".concat(n, "\"]"));
      if (!$el.length) return;
      $el.val(v !== null && v !== void 0 ? v : '').trigger('input').trigger('change');
    };
    set('paciente[nombre]', d.nombre);
    set('paciente[apellido]', d.apellido);
    set('paciente[nroDoc]', d.dni && String(d.dni));
    set('paciente[email]', d.email);
    set('paciente[telefono]', d.telefono);
    var sx = normSexo(d.sexo);
    if (sx) set('paciente[sexo]', sx);
    var iso = normalizeISO(d.fechaNacimiento) || esAISO(d.fechaNacimiento);
    set('paciente[fechaNacimiento]', iso);
    var $vis = $('#paciente_fecha_visual');
    if ($vis.length) $vis.val(isoAEs(iso));else pendingFechaISO = iso;
  }
  function clearDomicilio() {
    var $f = $('#recetaForm');
    var set = function set(n, v) {
      var $el = $f.find("[name=\"".concat(n, "\"]"));
      if ($el.length) $el.val(v !== null && v !== void 0 ? v : '').trigger('input').trigger('change');
    };
    set('domicilio[calle]', '');
    set('domicilio[numero]', '');
    set('domicilio[provincia]', '');
    var $sel = $f.find('[name="domicilio[id_provincia]"]');
    if ($sel.length) $sel.val('').trigger('change');
    pendingDomProvId = '';
    pendingDomProvName = '';
  }
  function setDomicilioFromCliente(c) {
    var $f = $('#recetaForm');
    var set = function set(n, v) {
      var $el = $f.find("[name=\"".concat(n, "\"]"));
      if (!$el.length) return;
      $el.val(v !== null && v !== void 0 ? v : '').trigger('input').trigger('change');
    };
    var empty = !c || !String(c.calle || '').trim() && !String(c.nro || '').trim() && !String(c.id_provincia || '').trim() && !String(c.provincia || '').trim();
    if (empty) {
      clearDomicilio();
      return;
    }
    set('domicilio[calle]', c.calle);
    set('domicilio[numero]', c.nro != null ? String(c.nro).trim() : '');
    var provId = String(c.id_provincia || '').trim();
    var provName = String(c.provincia || '').trim() || (provId && PROV_BY_ID[provId] ? PROV_BY_ID[provId] : '');
    var $provHidden = $f.find('[name="domicilio[provincia]"]');
    if (!$provHidden.length) {
      $provHidden = $('<input type="hidden" name="domicilio[provincia]">');
      $f.append($provHidden);
    }
    $provHidden.val(provName);
    var $provSel = $f.find('[name="domicilio[id_provincia]"]');
    if ($provSel.length && $provSel.is('select')) {
      if (provId) {
        $provSel.val(provId);
        if (!$provSel.val()) $provSel.append(new Option(provName || provId, provId, true, true));
      } else if (provName) {
        var k = provName.toLowerCase();
        if (PROV_ID_BY_NAME[k]) $provSel.val(PROV_ID_BY_NAME[k]);
      }
      $provSel.trigger('change');
      return;
    }
    pendingDomProvId = provId;
    pendingDomProvName = provName;
    var $provInput = $f.find('[name="domicilio[provincia]"]');
    if ($provInput.length && !$provInput.is('select')) $provInput.val(provName).trigger('input').trigger('change');
  }
  function initNomina() {
    var $s = $('#id_nomina');
    if (!$s.length) return;
    var preset = String($s.data('preset') || '').trim();
    if (preset) showFormLoader('Cargando datos del trabajador…');
    var opts = $s.find('option').toArray();
    if (opts.length > 1) {
      var first = opts.shift();
      opts.sort(function (a, b) {
        return $(a).text().localeCompare($(b).text(), 'es', {
          sensitivity: 'base'
        });
      });
      $s.empty().append(first, opts);
    }
    $s.select2({
      width: '100%',
      minimumInputLength: 0,
      dropdownParent: $(document.body)
    });
    s2Small($s);
    if (preset) {
      $s.on('select2:opening select2:unselecting', function (e) {
        return e.preventDefault();
      });
      var $sel2 = $s.next('.select2').find('.select2-selection--single');
      $sel2.css({
        background: '#e9ecef',
        cursor: 'not-allowed'
      });
    }
    var onCh = function onCh() {
      var _$o$data, _$o$data2, _$o$data3;
      var v = $s.val();
      if (!v) {
        clearPaciente();
        clearDomicilio();
        hideFormLoader();
        return;
      }
      var $o = $s.find('option:selected');
      var full = ($o.attr('data-nombre') || ((_$o$data = $o.data('nombre')) !== null && _$o$data !== void 0 ? _$o$data : '') || $o.text() || '').replace(/\s+—\s+.*$/, '').replace(/\(DNI:.*?\)/, '').trim();
      var _splitNombre = splitNombre(full),
        apellido = _splitNombre.apellido,
        nombre = _splitNombre.nombre;
      var fnac = ($o.attr('data-fecha-nacimiento') || '').trim() || String((_$o$data2 = $o.data('fechaNacimiento')) !== null && _$o$data2 !== void 0 ? _$o$data2 : '').trim() || String((_$o$data3 = $o.data('fecha-nacimiento')) !== null && _$o$data3 !== void 0 ? _$o$data3 : '').trim();
      var sexoRaw = String($o.attr('data-sexo') || $o.data('sexo') || '').trim();
      setPaciente({
        nombre: nombre,
        apellido: apellido,
        dni: $o.attr('data-dni') || $o.data('dni'),
        email: $o.attr('data-email') || $o.data('email'),
        telefono: $o.attr('data-telefono') || $o.data('telefono'),
        fechaNacimiento: fnac,
        sexo: sexoRaw
      });
      var cCalle = String($o.attr('data-cliente-calle') || '').trim();
      var cNro = String($o.attr('data-cliente-nro') || $o.attr('data-cliente-numero') || '').trim();
      var cProvId = String($o.attr('data-cliente-id-provincia') || '').trim();
      var cProvNom = String($o.attr('data-cliente-provincia') || '').trim();
      setDomicilioFromCliente({
        calle: cCalle,
        nro: cNro,
        id_provincia: cProvId,
        provincia: cProvNom
      });
      setTimeout(hideFormLoader, 50);
    };
    $s.on('change select2:select', onCh);
    if (preset && $s.find("option[value=\"".concat(preset, "\"]")).length) {
      $s.val(preset).trigger('change');
      $s.trigger('change.select2');
      return;
    }
    if ($s.val()) onCh();else hideFormLoader();
  }

  // =========================================================
  // Cobertura
  // =========================================================
  function ensureCobInputs() {
    var $f = $('#recetaForm');
    ['cobertura[idFinanciador]', 'cobertura[planId]', 'cobertura[plan]'].forEach(function (n) {
      if (!$f.find("input[name=\"".concat(n, "\"]")).length) $f.append("<input type=\"hidden\" name=\"".concat(n, "\">"));
    });
  }
  function initFinanciadores() {
    var $fin = $('#financiador');
    var $plan = $('#plan');
    if (!$fin.length) return;
    ensureCobInputs();
    var u = urls();
    $plan.select2({
      width: '100%',
      minimumInputLength: 0,
      dropdownParent: $(document.body)
    });
    s2Small($plan);
    $plan.prop('disabled', true).trigger('change.select2');
    s2Ajax($fin, {
      url: u.financiadores,
      minLen: 3,
      dataFn: function dataFn(p) {
        return {
          q: p.term || ''
        };
      },
      procFn: function procFn(res) {
        return {
          results: res.results || []
        };
      }
    });
    function rebuildPlans(list) {
      try {
        $plan.select2('destroy');
      } catch (_unused2) {}
      $plan.empty();
      var planes = (Array.isArray(list) ? list : []).reduce(function (acc, p) {
        var _ref5, _p$id, _ref6, _p$nombre3;
        var id = (_ref5 = (_p$id = p.id) !== null && _p$id !== void 0 ? _p$id : p.planId) !== null && _ref5 !== void 0 ? _ref5 : p.planid;
        var nom = (_ref6 = (_p$nombre3 = p.nombre) !== null && _p$nombre3 !== void 0 ? _p$nombre3 : p.descripcion) !== null && _ref6 !== void 0 ? _ref6 : p.name;
        if (id && nom) acc.push({
          id: id,
          text: nom
        });
        return acc;
      }, []);
      planes.forEach(function (p) {
        return $plan.append(new Option(p.text, p.id));
      });
      $plan.prop('disabled', planes.length === 0);
      $plan.select2({
        width: '100%',
        minimumInputLength: 0,
        dropdownParent: $(document.body)
      });
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
      var $wrap = $('#medsWrapper');
      $wrap.find('.sel-medicamento').each(function () {
        $(this).val(null).trigger('change');
      });
      $wrap.find('.regno,.presentacion,.nombre,.droga,' + 'input[name*="[tratamiento]"],input[name*="[posologia]"],textarea[name*="[indicaciones]"]').val('');
      $wrap.find('.duplicado').prop('checked', false);
      applyRxXorUI();
    }
    $fin.on('select2:select', function () {
      var _ref7, _ref8, _ref9, _raw$idfinanciador;
      var sel = $fin.select2('data')[0];
      var raw = (sel === null || sel === void 0 ? void 0 : sel.raw) || {};
      var finId = (_ref7 = (_ref8 = (_ref9 = (_raw$idfinanciador = raw.idfinanciador) !== null && _raw$idfinanciador !== void 0 ? _raw$idfinanciador : raw.nrofinanciador) !== null && _ref9 !== void 0 ? _ref9 : raw.nroFinanciador) !== null && _ref8 !== void 0 ? _ref8 : raw.id) !== null && _ref7 !== void 0 ? _ref7 : '';
      $('[name="cobertura[idFinanciador]"]').val(String(finId).replace(/\D+/g, ''));
      rebuildPlans(raw.planes || []);
      limpiarMedicamentos();
    });
    $plan.on('select2:select', function () {
      var $o = $plan.find('option:selected');
      var planId = String($o.val() || '').replace(/\D+/g, '');
      $('[name="cobertura[planId]"]').val(planId);
      $('[name="cobertura[plan]"]').val($o.text() || '');
      limpiarMedicamentos();
    });
  }

  // =========================================================
  // Diagnósticos
  // =========================================================
  function initDiagnosticos() {
    var $s = $('#diag_search');
    if (!$s.length) return;
    s2Ajax($s, {
      url: urls().diagnosticos,
      minLen: 3,
      procFn: function procFn(d) {
        return d;
      }
    });
    $s.on('select2:select', function () {
      var it = $s.select2('data')[0];
      $('#diagnostico_codigo').val((it === null || it === void 0 ? void 0 : it.id) || '');
      $('#diagnostico').val((it === null || it === void 0 ? void 0 : it.text) || '');
    });
  }

  // =========================================================
  // Medicamentos
  // =========================================================
  function coberturaParams() {
    var idFin = $('[name="cobertura[idFinanciador]"]').val() || $('#financiador').val();
    var planId = $('[name="cobertura[planId]"]').val() || $('#plan').val();
    var dni = $('[name="paciente[nroDoc]"]').val();
    var cred = $('[name="cobertura[credencial]"]').val();
    var q = {};
    if (idFin) q.idFinanciador = idFin;
    if (planId) q.planid = planId;
    if (dni) q.afiliadoDni = dni;
    if (cred) q.afiliadoCredencial = cred;
    return q;
  }
  function attachMed($row) {
    var $sel = $row.find('.sel-medicamento'),
      $reg = $row.find('.regno'),
      $pre = $row.find('.presentacion'),
      $nom = $row.find('.nombre'),
      $dro = $row.find('.droga'),
      $dup = $row.find('.duplicado');
    $reg.attr('readonly', 'readonly');
    if (!$row.find('.regno-help').length) {
      $reg.after('<small class="text-muted regno-help">Se completa automáticamente al elegir un medicamento</small>');
    }
    var $hint = $row.find('.med-hint');
    if (!$hint.length) {
      $hint = $('<div class="med-hint small mt-1 text-muted"></div>');
      $sel.closest('.form-group').append($hint);
    }
    function updateHint() {
      var p = coberturaParams();
      if (p.idFinanciador && p.planid) $hint.text('Resultados filtrados por tu cobertura/plan.');else $hint.text('Resultados generales (sin cobertura). Podés recetar con Reg. Nº + Cantidad.');
    }
    $row.data('updateHint', updateHint);
    updateHint();
    s2Ajax($sel, {
      url: urls().medicamentos,
      minLen: 2,
      dataFn: function dataFn(p) {
        var cov = coberturaParams();
        var base = {
          q: p.term || '',
          page: p.page || 1
        };
        if (cov.idFinanciador) base.idFinanciador = cov.idFinanciador;
        if (cov.planid) base.planid = cov.planid;
        if (cov.afiliadoDni) base.afiliadoDni = cov.afiliadoDni;
        if (cov.afiliadoCredencial) base.afiliadoCredencial = cov.afiliadoCredencial;
        return base;
      },
      procFn: function procFn(d) {
        var _d$pagination;
        return {
          results: d.results || [],
          pagination: {
            more: !!(d !== null && d !== void 0 && (_d$pagination = d.pagination) !== null && _d$pagination !== void 0 && _d$pagination.more)
          }
        };
      },
      tpl: function tpl(it) {
        if (!it.id) return it.text;
        var r = it.raw || {},
          flags = [];
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
      var _$sel$select2$;
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
      var r = ((_$sel$select2$ = $sel.select2('data')[0]) === null || _$sel$select2$ === void 0 ? void 0 : _$sel$select2$.raw) || {};
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
        var $r = $(this);
        var $sel = $r.find('.sel-medicamento');
        var $reg = $r.find('.regno');
        var $pre = $r.find('.presentacion');
        var $nom = $r.find('.nombre');
        var $dro = $r.find('.droga');
        var $dup = $r.find('.duplicado');
        if ($sel.length) $sel.val(null).trigger('change');
        $reg.val('');
        $pre.val('');
        $nom.val('');
        $dro.val('');
        $dup.prop('checked', false);
        var fn = $r.data('updateHint');
        if (typeof fn === 'function') fn();
      });
      RX_LAST = 'none';
      applyRxXorUI();
    });
  }
  function initMeds() {
    var idx = 1;
    var $wrap = $('#medsWrapper'),
      $add = $('#btnAddMed');
    if (!$wrap.length) return;
    bindMedFilterChangeOnce();
    var tpl = function tpl(i) {
      return "\n      <div class=\"med-row border rounded p-2 mb-3\">\n        <div class=\"form-row\">\n          <div class=\"form-group col-md-4 mb-2\">\n            <label class=\"mb-1 text-muted small\">Buscar medicamento <span class=\"req text-danger\">*</span></label>\n            <select class=\"form-control form-control-sm sel-medicamento\" style=\"width:100%\"></select>\n          </div>\n          <div class=\"form-group col-md-2 mb-2\">\n            <label class=\"mb-1 text-muted small\">Cantidad <span class=\"req text-danger\">*</span></label>\n            <input type=\"number\" min=\"1\" class=\"form-control form-control-sm\" name=\"medicamentos[".concat(i, "][cantidad]\" required>\n          </div>\n          <div class=\"form-group col-md-3 mb-2\">\n            <label class=\"mb-1 text-muted small\">Registro N\xBA</label>\n            <input type=\"text\" class=\"form-control form-control-sm regno\" name=\"medicamentos[").concat(i, "][regNo]\" readonly>\n          </div>\n          <div class=\"form-group col-md-3 mb-2\">\n            <label class=\"mb-1 text-muted small\">Presentaci\xF3n</label>\n            <input type=\"text\" class=\"form-control form-control-sm presentacion\" name=\"medicamentos[").concat(i, "][presentacion]\">\n          </div>\n        </div>\n        <div class=\"form-row\">\n          <div class=\"form-group col-md-4 mb-2\">\n            <label class=\"mb-1 text-muted small\">Nombre</label>\n            <input type=\"text\" class=\"form-control form-control-sm nombre\" name=\"medicamentos[").concat(i, "][nombre]\">\n          </div>\n          <div class=\"form-group col-md-4 mb-2\">\n            <label class=\"mb-1 text-muted small\">Droga</label>\n            <input type=\"text\" class=\"form-control form-control-sm droga\" name=\"medicamentos[").concat(i, "][nombreDroga]\">\n          </div>\n          <div class=\"form-group col-md-3 mb-2\">\n            <label class=\"mb-1 text-muted small\">Tratamiento (d\xEDas)</label>\n            <input type=\"number\" min=\"0\" class=\"form-control form-control-sm\" name=\"medicamentos[").concat(i, "][tratamiento]\" placeholder=\"0\">\n          </div>\n          <div class=\"form-group col-md-1 d-flex align-items-end mb-2\">\n            <button type=\"button\" class=\"btn btn-sm btn-outline-danger btn-del-med\" title=\"Eliminar\">\xD7</button>\n          </div>\n        </div>\n        <div class=\"form-group mb-2\">\n          <label class=\"mb-1 text-muted small\">Posolog\xEDa</label>\n          <input type=\"text\" class=\"form-control form-control-sm\" name=\"medicamentos[").concat(i, "][posologia]\">\n        </div>\n        <div class=\"form-group mb-2\">\n          <label class=\"mb-1 text-muted small\">Indicaciones / Observaciones</label>\n          <textarea class=\"form-control form-control-sm\" name=\"medicamentos[").concat(i, "][indicaciones]\" rows=\"2\"></textarea>\n        </div>\n        <div class=\"custom-control custom-checkbox custom-control-inline\">\n          <input type=\"checkbox\" class=\"custom-control-input duplicado\" id=\"dup").concat(i, "\" name=\"medicamentos[").concat(i, "][forzarDuplicado]\" value=\"1\">\n          <label class=\"custom-control-label\" for=\"dup").concat(i, "\">Requiere duplicado</label>\n        </div>\n      </div>");
    };
    function toggleDel() {
      var $rows = $wrap.children('.med-row');
      $rows.find('.btn-del-med').prop('disabled', $rows.length <= 1);
    }
    $wrap.off('meds:toggleDel').on('meds:toggleDel', toggleDel);
    $add.off('click.rxAddMed').on('click.rxAddMed', function () {
      if (practicasCount() > 0 || RX_FORCE === 'practicas' || RX_MODE === 'practicas') {
        modal({
          title: 'No se puede mezclar',
          html: '<p>Esta receta es <b>solo de prácticas</b>. Quitá las prácticas para poder agregar medicamentos.</p>'
        });
        return;
      }
      RX_LAST = 'meds';
      var $r = $(tpl(idx));
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
    var $s = $('#practica_search');
    if (!$s.length) return;
    ensurePracticasHidden();
    ensurePracticasList();
    s2Ajax($s, {
      url: urls().practicas,
      minLen: 3,
      dataFn: function dataFn(p) {
        var term = p.term || '';
        var data = {
          page: p.page || 1
        };
        var mT = term.match(/tipo:([^ ]+)/i),
          mC = term.match(/cat:([^ ]+)/i);
        if (mT) data.tipo = mT[1];
        if (mC) data.categoria = mC[1];
        var clean = term.replace(/tipo:[^ ]+/ig, '').replace(/cat:[^ ]+/ig, '').trim();
        if (clean) data.search = clean;
        return data;
      },
      procFn: function procFn(d) {
        var _d$pagination2;
        return {
          results: d.results || [],
          pagination: {
            more: !!(d !== null && d !== void 0 && (_d$pagination2 = d.pagination) !== null && _d$pagination2 !== void 0 && _d$pagination2.more)
          }
        };
      }
    });
    var $chips = ensurePracticasList();
    var $h = ensurePracticasHidden();
    function addChip(it) {
      var _it$id;
      if (hasMedsSelected() || RX_MODE === 'meds') {
        modal({
          title: 'No se puede mezclar',
          html: '<p>Esta receta es <b>solo de medicamentos</b>. Quitá los medicamentos para poder cargar prácticas.</p>'
        });
        return false;
      }
      var id = String((_it$id = it === null || it === void 0 ? void 0 : it.id) !== null && _it$id !== void 0 ? _it$id : '').trim();
      if (!id) return false;
      if ($h.find("input[name=\"practicas[]\"][value=\"".concat(id, "\"]")).length) return false;
      RX_LAST = 'practicas';
      $h.append($('<input type="hidden" name="practicas[]">').val(id));
      $chips.append($("<span class=\"badge badge-primary d-inline-flex align-items-center mr-2 mb-2\" data-id=\"".concat(id, "\" style=\"font-size:.8rem;\">\n          <span class=\"mr-2\">").concat(esc(it.text), "</span>\n          <button type=\"button\" class=\"btn btn-sm btn-light py-0 px-1 quitar-chip\" aria-label=\"Quitar\" style=\"line-height:1;\">\xD7</button>\n        </span>")));
      applyRxXorUI();
      setTimeout(applyRxXorUI, 0);
      return true;
    }
    $(document).off('select2:select.rxPract').on('select2:select.rxPract', '#practica_search', function () {
      var it = $(this).select2('data')[0];
      if (!it) return;
      var ok = addChip(it);

      // limpiar búsqueda SIEMPRE
      $(this).val(null).trigger('change');
      if (!ok) return;
    });
    $chips.off('click.rxChip').on('click.rxChip', '.quitar-chip', function () {
      var id = String($(this).closest('[data-id]').data('id') || '').trim();
      if (id) $h.find("input[name=\"practicas[]\"][value=\"".concat(id, "\"]")).remove();
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
    var $s = $('[name="paciente[sexo]"]');
    if (!$s.length) return;
    if (!$s.val()) $s.val('M');
    $s.find('option').each(function () {
      var v = $(this).val();
      if (v === 'M') $(this).text('Hombre (M)');else if (v === 'F') $(this).text('Mujer (F)');else if (v === 'X') $(this).text('No binario (X)');else if (v === 'O') $(this).text('Otro (O)');
    });
  }

  // =========================================================
  // Matrícula MN/MP + Provincias
  // =========================================================
  function initMatricula() {
    var $tipo = $('[name="medico[matricula][tipo]"]');
    var $numero = $('[name="medico[matricula][numero]"]');
    fetchProvincias().then(function (items) {
      ensureProvinciaPair(items, {
        visibleName: 'domicilio[provincia]',
        idName: 'domicilio[id_provincia]'
      });
      var $domSel = $('[name="domicilio[id_provincia]"]');
      if ($domSel.length && pendingDomProvId) {
        $domSel.val(pendingDomProvId);
        if (!$domSel.val()) $domSel.append(new Option(pendingDomProvName || pendingDomProvId, pendingDomProvId, true, true));
        $domSel.trigger('change');
        pendingDomProvId = '';
        pendingDomProvName = '';
      }
      var $selMat = ensureProvinciaPair(items, {
        visibleName: 'medico[matricula][provincia]',
        idName: 'medico[matricula][id_provincia]'
      });
      var $provMatWrap = $selMat ? $selMat.closest('.form-group') : null;
      function applyRules() {
        var t = String($tipo.val() || 'MN').toUpperCase().trim();
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
    var $iso = $('[name="paciente[fechaNacimiento]"]');
    if (!$iso.length) return;
    var $vis = $('<input type="text" id="paciente_fecha_visual" class="form-control form-control-sm" placeholder="DD/MM/AAAA" readonly>');
    var curISO = $iso.val();
    $vis.val(isoAEs(curISO)).insertAfter($iso);
    if (pendingFechaISO) {
      $iso.val(pendingFechaISO);
      $vis.val(isoAEs(pendingFechaISO));
      pendingFechaISO = '';
    }
    $iso.attr('type', 'hidden');
    $vis.on('keydown paste', function (e) {
      return e.preventDefault();
    });
    if ($.datepicker && !$.datepicker.regional['es']) {
      $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: 'Anterior',
        nextText: 'Siguiente',
        currentText: 'Hoy',
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
        onClose: function onClose(v) {
          return $iso.val(esAISO(v));
        }
      });
    }
    $vis.on('focus click', function () {
      if ($.fn.datepicker) $(this).datepicker('show');
    });
  }

  // =========================================================
  // Firma + Sello UI
  // =========================================================
  function initFirmaSelloUI() {
    var _ref0, _$chk$data;
    var $chk = $('#incluir_firma_medico');
    if (!$chk.length) return;
    var hasFirma = String((_ref0 = (_$chk$data = $chk.data('hasFirma')) !== null && _$chk$data !== void 0 ? _$chk$data : $chk.data('has-firma')) !== null && _ref0 !== void 0 ? _ref0 : '') === '1';
    var $box = $('#box_sello_medico');
    var $fields = $box.find('.sello-field');
    function apply() {
      var on = hasFirma && $chk.is(':checked');
      $box.toggleClass('d-none', !on);
      $fields.prop('disabled', !on);
    }
    if (!hasFirma) $chk.prop('checked', false);
    $chk.off('change.firmaSello').on('change.firmaSello', apply);
    apply();
  }

  // =========================================================
  // Loader bloqueante al generar receta
  // =========================================================
  var submitFxReady = false;
  function ensureSubmitFxCSS() {
    if (submitFxReady) return;
    submitFxReady = true;
    var css = "\n      .rx-submit-back{\n        position:fixed;\n        inset:0;\n        z-index:10050;\n        background:rgba(15,23,42,.38);\n        backdrop-filter:blur(3px);\n        display:flex;\n        align-items:center;\n        justify-content:center;\n      }\n\n      .rx-submit-box{\n        width:min(92vw, 430px);\n        background:#fff;\n        border-radius:16px;\n        box-shadow:0 20px 60px rgba(0,0,0,.25);\n        padding:20px 18px;\n      }\n\n      .rx-submit-title{\n        margin:0 0 8px;\n        font-size:18px;\n        font-weight:800;\n        color:#111827;\n      }\n\n      .rx-submit-text{\n        margin:0 0 14px;\n        font-size:14px;\n        color:#4b5563;\n      }\n\n      .rx-submit-row{\n        display:flex;\n        align-items:center;\n        gap:10px;\n        margin-bottom:12px;\n      }\n\n      .rx-submit-spin{\n        width:18px;\n        height:18px;\n        border-radius:999px;\n        border:3px solid rgba(0,0,0,.12);\n        border-top-color:#2563eb;\n        animation:rxSubmitSpin .9s linear infinite;\n      }\n\n      .rx-submit-barp{\n        height:12px;\n        border-radius:999px;\n        background:#e5e7eb;\n        overflow:hidden;\n        position:relative;\n      }\n\n      .rx-submit-bar{\n        height:100%;\n        width:100%;\n        border-radius:999px;\n        background:linear-gradient(90deg,#60a5fa 0%, #2563eb 45%, #60a5fa 100%);\n        background-size:200% 100%;\n        animation:rxSubmitBar 1.2s linear infinite;\n      }\n\n      .rx-submit-meta{\n        display:flex;\n        justify-content:space-between;\n        align-items:center;\n        margin-top:8px;\n        font-size:12px;\n        color:#6b7280;\n      }\n\n      .rx-submit-btn-loading{\n        position:relative;\n        overflow:hidden;\n        filter:saturate(1.08);\n      }\n\n      .rx-submit-btn-loading::after{\n        content:\"\";\n        position:absolute;\n        top:0;\n        bottom:0;\n        left:-35%;\n        width:35%;\n        transform:skewX(-20deg);\n        background:linear-gradient(90deg,transparent,rgba(255,255,255,.38),transparent);\n        animation:rxBtnSweep 1.1s linear infinite;\n      }\n\n      @keyframes rxSubmitSpin{\n        to{ transform:rotate(360deg); }\n      }\n\n      @keyframes rxSubmitBar{\n        0%{ background-position:200% 0; }\n        100%{ background-position:-200% 0; }\n      }\n\n      @keyframes rxBtnSweep{\n        100%{ left:135%; }\n      }\n\n      @media (prefers-color-scheme: dark){\n        .rx-submit-box{ background:#0f172a; }\n        .rx-submit-title{ color:#e5e7eb; }\n        .rx-submit-text,\n        .rx-submit-meta{ color:#cbd5e1; }\n        .rx-submit-barp{ background:#1e293b; }\n        .rx-submit-spin{\n          border-color:rgba(255,255,255,.15);\n          border-top-color:#60a5fa;\n        }\n      }\n    ";
    document.head.appendChild(Object.assign(document.createElement('style'), {
      textContent: css
    }));
  }
  function showSubmitLoader() {
    var message = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'Estamos generando la receta. Esto puede tardar unos segundos.';
    ensureSubmitFxCSS();
    var $btn = $('#recetaForm').find('button[type="submit"]').last();
    $btn.addClass('rx-submit-btn-loading').prop('disabled', true);
    var $ov = $('#rxSubmitLoader');
    if (!$ov.length) {
      $ov = $("\n        <div id=\"rxSubmitLoader\" class=\"rx-submit-back\" aria-live=\"polite\" aria-busy=\"true\">\n          <div class=\"rx-submit-box\">\n            <div class=\"rx-submit-title\">Generando receta</div>\n            <p class=\"rx-submit-text\"></p>\n\n            <div class=\"rx-submit-row\">\n              <div class=\"rx-submit-spin\"></div>\n              <strong>Procesando\u2026</strong>\n            </div>\n\n            <div class=\"rx-submit-barp\">\n              <div class=\"rx-submit-bar\"></div>\n            </div>\n\n            <div class=\"rx-submit-meta\">\n              <span>Preparando documento y validando datos</span>\n              <span>100%</span>\n            </div>\n          </div>\n        </div>\n      ");
      $(document.body).append($ov);
    }
    $ov.find('.rx-submit-text').text(message);
  }
  function hideSubmitLoader() {
    $('#rxSubmitLoader').remove();
    var $btn = $('#recetaForm').find('button[type="submit"]').last();
    $btn.removeClass('rx-submit-btn-loading');
  }

  // =========================================================
  // Submit (validaciones + AJAX)
  // =========================================================

  function initSubmit() {
    var $f = $('#recetaForm');
    if (!$f.length) return;
    $f.off('submit.rxSubmit').on('submit.rxSubmit', function (e) {
      e.preventDefault();
      var items = [];
      var idNom = String($('#id_nomina').val() || '').trim();
      if (!idNom) items.push('Nómina es obligatoria.');
      syncProvinciaTextFromSelects();
      var vis = $('#paciente_fecha_visual').val();
      var iso = esAISO(vis);
      if (vis && iso) $('[name="paciente[fechaNacimiento]"]').val(iso);
      var dni = ($('[name="paciente[nroDoc]"]').val() || '').replace(/\D+/g, '');
      if (!dni) items.push('DNI del paciente es obligatorio.');else if (dni.length < 7 || dni.length > 9) items.push('DNI del paciente debe tener entre 7 y 9 dígitos.');
      var sexo = String($('[name="paciente[sexo]"]').val() || '').toUpperCase();
      if (!['M', 'F', 'X', 'O'].includes(sexo)) items.push('Sexo seleccionado no es válido.');
      var fiso = $('[name="paciente[fechaNacimiento]"]').val();
      if (!fiso) items.push('Fecha de nacimiento es obligatoria.');else {
        var d = new Date(fiso + 'T00:00:00');
        var today = new Date();
        today.setHours(0, 0, 0, 0);
        if (isNaN(d.getTime())) items.push('Fecha de nacimiento inválida.');else if (d > today) items.push('La fecha de nacimiento no puede ser futura.');
      }
      var tipoMat = String($('[name="medico[matricula][tipo]"]').val() || 'MN').toUpperCase();
      var nroMat = String($('[name="medico[matricula][numero]"]').val() || '').replace(/\D+/g, '');
      var provMatTxt = String($('[name="medico[matricula][provincia]"]').val() || '').trim();
      var provMatId = String($('[name="medico[matricula][id_provincia]"]').val() || '').trim();
      if (!nroMat) items.push('Número de matrícula es obligatorio.');else if (nroMat.length > 10) items.push('Número de matrícula: máximo 10 dígitos.');
      if (tipoMat === 'MP' && !provMatTxt && !provMatId) {
        items.push('Para matrícula provincial (MP), la provincia es obligatoria.');
      }
      var dCalle = String($('[name="domicilio[calle]"]').val() || '').trim();
      var dNum = String($('[name="domicilio[numero]"]').val() || '').trim();
      var dProvTxt = String($('[name="domicilio[provincia]"]').val() || '').trim();
      var dProvId = String($('[name="domicilio[id_provincia]"]').val() || '').trim();
      if (!dCalle) items.push('Domicilio: la calle es obligatoria.');
      if (!dNum) items.push('Domicilio: el número es obligatorio.');
      if (!dProvTxt && !dProvId) items.push('Domicilio: la provincia es obligatoria.');
      var idFin = String($('[name="cobertura[idFinanciador]"]').val() || '').trim();
      var cred = String($('[name="cobertura[credencial]"]').val() || '').trim();
      if (idFin) {
        if (!cred) items.push('Cobertura: el número de afiliado es obligatorio si indicás un financiador.');else if (!/^\d+$/.test(cred)) {
          items.push('Cobertura: el número de afiliado debe tener solo números (sin puntos ni guiones).');
        }
      }
      var hasPract = practicasCount() > 0;
      var $rows = $('#medsWrapper .med-row');
      var hasMeds = false;
      $rows.each(function () {
        var reg = String($(this).find('.regno').val() || '').trim();
        var sel = $(this).find('.sel-medicamento').val();
        if (reg || sel && String(sel).trim() !== '') {
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
          var $r = $(this);
          var cant = Number($r.find('input[name^="medicamentos"][name$="[cantidad]"]').val() || 0);
          var reg = String($r.find('.regno').val() || '').trim();
          var sel = $r.find('.sel-medicamento').val();
          if (!reg && (!sel || String(sel).trim() === '')) {
            items.push("Medicamento (fila ".concat(i + 1, "): seleccion\xE1 un medicamento o elimin\xE1 la fila."));
            return;
          }
          var pre = String($r.find('.presentacion').val() || '').trim();
          var nom = String($r.find('.nombre').val() || '').trim();
          var dro = String($r.find('.droga').val() || '').trim();
          var trat = String($r.find('input[name^="medicamentos"][name$="[tratamiento]"]').val() || '').trim();
          if (!cant || cant < 1) items.push("Medicamento (fila ".concat(i + 1, "): la cantidad debe ser mayor a 0."));
          if (!pre) items.push("Medicamento (fila ".concat(i + 1, "): la presentaci\xF3n es obligatoria."));
          if (!nom) items.push("Medicamento (fila ".concat(i + 1, "): el nombre es obligatorio."));
          if (!dro) items.push("Medicamento (fila ".concat(i + 1, "): la droga es obligatoria."));
          if (trat !== '' && isNaN(Number(trat))) items.push("Medicamento (fila ".concat(i + 1, "): tratamiento inv\xE1lido."));
        });
      }
      var $chkFirma = $('#incluir_firma_medico');
      if ($chkFirma.length) {
        var incluirFirma = $chkFirma.is(':checked');
        var hasFirma = String($chkFirma.data('hasFirma') || $chkFirma.data('has-firma') || '') === '1';
        if (incluirFirma && !hasFirma) {
          items.push('Marcaste incluir firma, pero este médico no tiene firma cargada en su cuenta.');
        }
      }
      if (items.length) {
        modal({
          title: 'Revisá estos datos',
          html: errList(items)
        });
        return;
      }
      var url = $f.attr('action');
      var data = $f.serialize();
      var $btn = $f.find('button[type="submit"]').last();
      var old = $btn.html();
      var redirecting = false;
      var deferredModal = null;
      $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Generando…');
      showSubmitLoader('Estamos generando la receta. Esto puede tardar unos segundos.');
      $.ajax({
        type: 'POST',
        url: url,
        data: data,
        headers: {
          'Accept': 'application/json'
        },
        timeout: AJAX_TIMEOUT_MS
      }).done(function (r) {
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
          html: "<p>".concat(esc((r === null || r === void 0 ? void 0 : r.message) || 'Operación realizada.'), "</p>")
        };
      }).fail(function (jq) {
        var items2 = [];
        if (jq.status === 422) {
          var _e = (jq.responseJSON || {}).errors || {};
          items2 = Object.keys(_e).map(function (k) {
            return "".concat(k, ": ").concat((_e[k] || []).join(' '));
          });
        } else if (jq.status === 504) {
          items2 = ['El servicio tardó demasiado en responder. Probá nuevamente.'];
        } else {
          try {
            var j = jq.responseJSON || JSON.parse(jq.responseText || '{}');
            var msg = j.message || j.mensaje || jq.statusText || 'Error al generar la receta.';
            if (msg) items2.push(msg);
            if (j.code) items2.push('Código técnico: ' + j.code);
          } catch (_unused3) {
            items2.push('Error desconocido al generar la receta.');
          }
        }
        deferredModal = {
          title: 'No se pudo generar la receta',
          html: errList(items2)
        };
      }).always(function () {
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
  var medLockCssReady = false;
  function ensureMedLockCSS() {
    if (medLockCssReady) return;
    medLockCssReady = true;
    var css = "\n      .med-lock-input{ background:#e9ecef !important; }\n      .med-lock-select{ background:#e9ecef !important; cursor:not-allowed !important; }\n      @media (prefers-color-scheme: dark){\n        .med-lock-input, .med-lock-select{ background: rgba(148,163,184,.18) !important; }\n      }\n    ";
    document.head.appendChild(Object.assign(document.createElement('style'), {
      textContent: css
    }));
  }
  function hardLockSelect($el) {
    var lockedVal = $el.val();
    $el.data('lockedVal', lockedVal);
    $el.addClass('med-lock-select').attr('tabindex', '-1').on('change.medLock', function () {
      $(this).val($(this).data('lockedVal'));
    }).on('mousedown.medLock click.medLock keydown.medLock', function (e) {
      e.preventDefault();
      e.stopPropagation();
      this.blur();
      $(this).val($(this).data('lockedVal'));
      return false;
    }).on('select2:opening.medLock select2:unselecting.medLock', function (e) {
      return e.preventDefault();
    });
  }
  function lockMedicoFields() {
    ensureMedLockCSS();
    var $f = $('#recetaForm');
    if (!$f.length) return;
    $f.find('[name="medico[apellido]"],' + '[name="medico[nombre]"],' + '[name="medico[nroDoc]"],' + '[name="medico[matricula][numero]"]').each(function () {
      var $el = $(this);
      if ($el.data('medLocked')) return;
      $el.data('medLocked', 1);
      $el.prop('readonly', true).addClass('med-lock-input');
    });
    $f.find('[name="medico[tipoDoc]"],' + '[name="medico[sexo]"],' + '[name="medico[matricula][tipo]"],' + '[name="medico[matricula][id_provincia]"]').each(function () {
      var $el = $(this);
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
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      timeout: AJAX_TIMEOUT_MS
    });
    $(document).off('change.firmaMedico').on('change.firmaMedico', '#incluir_firma_medico', function () {
      var hasFirma = String($(this).data('hasFirma') || $(this).data('has-firma') || '') === '1';
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
    $(document).off('select2:opening.rxHardBlock').on('select2:opening.rxHardBlock', '.sel-medicamento', function (e) {
      if (practicasCount() > 0 || RX_MODE === 'practicas' || RX_FORCE === 'practicas') {
        e.preventDefault();
        modal({
          title: 'No se puede mezclar',
          html: '<p>Esta receta es <b>solo de prácticas</b>. Quitá las prácticas para poder cargar medicamentos.</p>'
        });
      }
    });

    // HARD BLOCK: si hay medicamentos → no abrir select2 de prácticas
    $(document).off('select2:opening.rxHardBlockPract').on('select2:opening.rxHardBlockPract', '#practica_search', function (e) {
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
    var tries = 0;
    var iv = setInterval(function () {
      tries++;
      if (typeof $.fn.select2 === 'function') {
        clearInterval(iv);
        fetchProvincias().then(function () {
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
        fetchProvincias().then(function () {
          initMatricula();
        });
        initMeds();
        ensureRxLayout();
        applyRxXorUI();
      }
    }, 100);
  }
  $(boot);
})(jQuery, window, document);

/***/ }),

/***/ 64:
/*!********************************************************!*\
  !*** multi ./resources/js/empleados/recetas/create.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\ela_g\Herd\ejornal_laravel\resources\js\empleados\recetas\create.js */"./resources/js/empleados/recetas/create.js");


/***/ })

/******/ });