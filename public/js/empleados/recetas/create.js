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

  // ===== Spinner pequeño en labels =====
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

  // ===== Loader sobre el formulario (solo para cuando mandamos id_nomina en url) =====
  var formLoaderReady = false;
  function ensureFormLoaderCSS() {
    if (formLoaderReady) return;
    formLoaderReady = true;
    var css = "\n    .fl-back{\n      position:absolute; inset:0;\n      background: rgba(255,255,255,.75);\n      display:flex; align-items:center; justify-content:center;\n      z-index: 9998;\n      border-radius: 12px;\n    }\n    @media (prefers-color-scheme: dark){\n      .fl-back{ background: rgba(15,23,42,.72); }\n    }\n    .fl-box{\n      display:flex; align-items:center; gap:10px;\n      padding:10px 14px;\n      border-radius: 12px;\n      background: rgba(255,255,255,.95);\n      box-shadow: 0 10px 30px rgba(0,0,0,.18);\n      font-size: 14px;\n      color:#0f172a;\n    }\n    @media (prefers-color-scheme: dark){\n      .fl-box{ background: rgba(2,6,23,.92); color:#e2e8f0; }\n    }\n    .fl-spin{\n      width:18px; height:18px;\n      border-radius:999px;\n      border: 3px solid rgba(0,0,0,.12);\n      border-top-color: rgba(0,0,0,.55);\n      animation: flrot .9s linear infinite;\n    }\n    @media (prefers-color-scheme: dark){\n      .fl-spin{\n        border-color: rgba(255,255,255,.15);\n        border-top-color: rgba(255,255,255,.65);\n      }\n    }\n    @keyframes flrot{to{transform:rotate(360deg)}}\n  ";
    document.head.appendChild(Object.assign(document.createElement('style'), {
      textContent: css
    }));
  }
  function showFormLoader() {
    var msg = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'Cargando datos del trabajador…';
    ensureFormLoaderCSS();
    var $f = $('#recetaForm');
    if (!$f.length) return;

    // contenedor que cubrimos
    var $wrap = $f.closest('.tarjeta');
    var $host = $wrap.length ? $wrap : $f;

    // evitar duplicados
    if ($host.find('.fl-back').length) return;

    // asegurar posicionamiento
    if ($host.css('position') === 'static') $host.css('position', 'relative');
    var $ov = $("\n    <div class=\"fl-back\" aria-live=\"polite\" aria-busy=\"true\">\n      <div class=\"fl-box\">\n        <div class=\"fl-spin\"></div>\n        <div class=\"fl-msg\">".concat(msg, "</div>\n      </div>\n    </div>\n  "));
    $host.append($ov);
  }
  function hideFormLoader() {
    var $f = $('#recetaForm');
    if (!$f.length) return;
    var $wrap = $f.closest('.tarjeta');
    var $host = $wrap.length ? $wrap : $f;
    $host.find('.fl-back').remove();
  }

  // ===== Helpers URL / fechas / select2 =====
  var pendingFechaISO = '';
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
    var m = s.match(/^(\d{4})-(\d{2})-(\d{2})/); // acepta "YYYY-MM-DD ..." también
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
      // sin opción vacía
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

  // ===== Provincias por endpoint =====
  function fetchProvincias() {
    return $.ajax({
      url: urls().provincias,
      dataType: 'json',
      headers: {
        'Accept': 'application/json'
      },
      timeout: AJAX_TIMEOUT_MS
    }).then(function (j) {
      return Array.isArray(j === null || j === void 0 ? void 0 : j.results) ? j.results : [];
    })["catch"](function () {
      return [];
    });
  }
  function toSelect($input, items) {
    var _ref3 = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {},
      name = _ref3.name;
    var $sel = $('<select class="form-control form-control-sm"></select>');
    if (name) $sel.attr('name', name);
    if ($input.attr('id')) $sel.attr('id', $input.attr('id'));
    items.forEach(function (p, i) {
      var v = p.id ? String(p.id) : p.nombre || '';
      var t = p.nombre || v;
      var $o = $('<option>').val(v).text(t);
      if (i === 0 && !$input.val()) $o.prop('selected', true);
      $sel.append($o);
    });
    $input.replaceWith($sel);
    return $sel;
  }

  // ===== Asteriscos dinámicos =====
  function setRequired($field, required) {
    var $fg = $field.closest('.form-group');
    var $label = $fg.find('label').first();
    $field.prop('required', !!required);
    $label.find('.req').remove();
    if (required) $label.append(' <span class="req text-danger">*</span>');
  }

  // ===== Nómina → autocompletado de paciente =====
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
  function setPac(d) {
    var set = function set(n, v) {
      return $("[name=\"".concat(n, "\"]")).val(v !== null && v !== void 0 ? v : '');
    };
    set('paciente[nombre]', d.nombre);
    set('paciente[apellido]', d.apellido);
    set('paciente[nroDoc]', d.dni && String(d.dni));
    set('paciente[email]', d.email);
    set('paciente[telefono]', d.telefono);

    // ===== Fecha de nacimiento (normalizada + fallback hoy si viene vacío) =====
    var iso = normalizeISO(d.fechaNacimiento) || esAISO(d.fechaNacimiento);
    if (!iso) {
      var t = new Date();
      var mm = ('0' + (t.getMonth() + 1)).slice(-2);
      var dd = ('0' + t.getDate()).slice(-2);
      iso = "".concat(t.getFullYear(), "-").concat(mm, "-").concat(dd);
    }
    $('[name="paciente[fechaNacimiento]"]').val(iso);
    var $vis = $('#paciente_fecha_visual');
    if ($vis.length) {
      $vis.val(isoAEs(iso));
    } else {
      pendingFechaISO = iso;
    }

    // Domicilio
    set('domicilio[calle]', d.calle);
    set('domicilio[numero]', d.nro && String(d.nro));
    set('domicilio[localidad]', d.localidad);
    set('domicilio[codigoPostal]', d.cod_postal && String(d.cod_postal));

    // si está el loader, lo dejamos como ya lo tenías
    setTimeout(hideFormLoader, 50);
  }
  function initNomina() {
    var $s = $('#id_nomina');
    if (!$s.length) return;
    var preset = String($s.data('preset') || '').trim();

    // 🔄 Si viene preset (redirect desde consulta médica), mostramos loader
    if (preset) {
      showFormLoader('Cargando datos del trabajador…');
    }

    // =========================
    // Ordenar opciones alfabéticamente (manteniendo la primera)
    // =========================
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

    // =========================
    // Inicializar Select2
    // =========================
    $s.select2({
      width: '100%',
      minimumInputLength: 0,
      dropdownParent: $(document.body)
    });
    s2Small($s);

    // =========================
    // Cambio de nómina → autocompletar paciente
    // =========================
    var onCh = function onCh() {
      var v = $s.val();
      if (!v) {
        setPac({});
        hideFormLoader();
        return;
      }
      var $o = $s.find('option:selected');
      var full = $o.attr('data-nombre') || $o.data('nombre') || ($o.text() || '').replace(/\s+—\s+.*$/, '').replace(/\(DNI:.*?\)/, '').trim();
      var _splitNombre = splitNombre(full),
        apellido = _splitNombre.apellido,
        nombre = _splitNombre.nombre;

      // ✅ leer del atributo, no confiar en .data()
      var fnac = ($o.attr('data-fecha-nacimiento') || '').trim() || ($o.data('fechaNacimiento') || '').toString().trim() || ($o.data('fecha-nacimiento') || '').toString().trim();
      setPac({
        nombre: nombre,
        apellido: apellido,
        dni: $o.attr('data-dni') || $o.data('dni'),
        email: $o.attr('data-email') || $o.data('email'),
        telefono: $o.attr('data-telefono') || $o.data('telefono'),
        fechaNacimiento: fnac,
        // <-- acá
        calle: $o.attr('data-calle') || $o.data('calle'),
        nro: $o.attr('data-nro') || $o.data('nro'),
        localidad: $o.attr('data-localidad') || $o.data('localidad'),
        cod_postal: $o.attr('data-cod-postal') || $o.data('cod-postal')
      });
      console.log(fnac);
    };
    $s.on('change select2:select', onCh);

    // =========================
    // Caso 1: viene preset por querystring (?id_nomina=)
    // =========================
    if (preset && $s.find("option[value=\"".concat(preset, "\"]")).length) {
      // Forzamos valor, disparamos change y dejamos que onCh quite el loader
      $s.val(preset).trigger('change');
      $s.trigger('change.select2');
      return;
    }

    // =========================
    // Caso 2: NO viene preset → comportamiento actual
    // =========================
    if ($s.val()) {
      onCh();
    } else {
      // si no hay selección y había loader (caso raro), lo quitamos
      hideFormLoader();
    }
  }

  // ===== Cobertura (Financiador + Plan) =====
  function ensureCobInputs() {
    var $f = $('#recetaForm');
    ['cobertura[idFinanciador]', 'cobertura[planId]', 'cobertura[plan]'].forEach(function (n) {
      if (!$f.find("input[name=\"".concat(n, "\"]")).length) {
        $f.append("<input type=\"hidden\" name=\"".concat(n, "\">"));
      }
    });
  }
  function initFinanciadores() {
    var $fin = $('#financiador');
    var $plan = $('#plan');
    if (!$fin.length) return;
    ensureCobInputs();
    var u = urls();

    // Plan vacío al inicio
    $plan.select2({
      width: '100%',
      minimumInputLength: 0,
      dropdownParent: $(document.body)
    });
    s2Small($plan);
    $plan.prop('disabled', true).trigger('change');

    // Financiadores (QBI2 GetFinanciadores)
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
        var _ref4, _p$id, _ref5, _p$nombre;
        var id = (_ref4 = (_p$id = p.id) !== null && _p$id !== void 0 ? _p$id : p.planId) !== null && _ref4 !== void 0 ? _ref4 : p.planid;
        var nom = (_ref5 = (_p$nombre = p.nombre) !== null && _p$nombre !== void 0 ? _p$nombre : p.descripcion) !== null && _ref5 !== void 0 ? _ref5 : p.name;
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
    }
    function limpiarMedicamentos() {
      var $wrap = $('#medsWrapper');
      $wrap.find('.sel-medicamento').each(function () {
        $(this).val(null).trigger('change');
      });
      $wrap.find('.regno,.presentacion,.nombre,.droga,' + 'input[name*="[tratamiento]"],input[name*="[posologia]"],textarea[name*="[indicaciones]"]').val('');
      $wrap.find('.duplicado').prop('checked', false);
    }

    // Cuando el usuario elige financiador
    $fin.on('select2:select', function () {
      var _ref6, _ref7, _ref8, _raw$idfinanciador;
      var sel = $fin.select2('data')[0];
      var raw = (sel === null || sel === void 0 ? void 0 : sel.raw) || {};

      // La doc usa idfinanciador como idFinanciador
      var finId = (_ref6 = (_ref7 = (_ref8 = (_raw$idfinanciador = raw.idfinanciador) !== null && _raw$idfinanciador !== void 0 ? _raw$idfinanciador : raw.nrofinanciador) !== null && _ref8 !== void 0 ? _ref8 : raw.nroFinanciador) !== null && _ref7 !== void 0 ? _ref7 : raw.id) !== null && _ref6 !== void 0 ? _ref6 : '';
      $('[name="cobertura[idFinanciador]"]').val(String(finId).replace(/\D+/g, ''));
      rebuildPlans(raw.planes || []);
      limpiarMedicamentos();
    });

    // Cuando el usuario elige plan
    $plan.on('select2:select', function () {
      var $o = $plan.find('option:selected');
      var planId = String($o.val() || '').replace(/\D+/g, '');
      $('[name="cobertura[planId]"]').val(planId);
      $('[name="cobertura[plan]"]').val($o.text() || '');
      limpiarMedicamentos();
    });
  }

  // ===== Diagnósticos =====
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

  // ===== Medicamentos (ya filtrados por cobertura si existe) =====
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

    // Reg. Nº solo lectura
    $reg.attr('readonly', 'readonly');
    if (!$row.find('.regno-help').length) $reg.after('<small class="text-muted regno-help">Se completa automáticamente al elegir un medicamento</small>');

    // Aviso visual sobre cobertura aplicada o no
    var $hint = $row.find('.med-hint');
    if (!$hint.length) {
      $hint = $('<div class="med-hint small mt-1 text-muted"></div>');
      $sel.closest('.form-group').append($hint);
    }
    function updateHint() {
      var p = coberturaParams();
      if (p.idFinanciador && p.planid) $hint.text('Resultados filtrados por tu cobertura/plan.');else $hint.text('Resultados generales (sin cobertura). Podés recetar con Reg. Nº + Cantidad.');
    }
    updateHint();

    // Select2 AJAX que SIEMPRE manda cobertura si existe (no hay validación posterior)
    s2Ajax($sel, {
      url: urls().medicamentos,
      minLen: 2,
      dataFn: function dataFn(p) {
        var cov = coberturaParams();
        var base = {
          q: p.term || '',
          page: p.page || 1
        };
        // mandamos SIEMPRE lo que haya de cobertura (si existe, la API ya filtra)
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

    // Al elegir, completar campos (sin “validación extra”)
    $sel.on('select2:select', function () {
      var _$sel$select2$;
      var r = ((_$sel$select2$ = $sel.select2('data')[0]) === null || _$sel$select2$ === void 0 ? void 0 : _$sel$select2$.raw) || {};
      $reg.val(r.regNo || '');
      $pre.val(r.presentacion || '');
      $nom.val(r.nombreProducto || '');
      $dro.val(r.nombreDroga || '');
      if (r.requiereDuplicado === true) $dup.prop('checked', true);
    });

    // Cuando la cobertura cambie, vaciamos selección de este row
    $(document).on('change', '#financiador, #plan', function () {
      $sel.val(null).trigger('change');
      $reg.val('');
      $pre.val('');
      $nom.val('');
      $dro.val('');
      $dup.prop('checked', false);
      updateHint();
    });
  }
  function initMeds() {
    var idx = 1;
    var $wrap = $('#medsWrapper'),
      $add = $('#btnAddMed');
    if (!$wrap.length) return;
    var tpl = function tpl(i) {
      return "\n    <div class=\"med-row border rounded p-2 mb-3\">\n      <div class=\"form-row\">\n        <div class=\"form-group col-md-4 mb-2\">\n          <label class=\"mb-1 text-muted small\">Buscar medicamento <span class=\"req text-danger\">*</span></label>\n          <select class=\"form-control form-control-sm sel-medicamento\" style=\"width:100%\"></select>\n        </div>\n        <div class=\"form-group col-md-2 mb-2\">\n          <label class=\"mb-1 text-muted small\">Cantidad <span class=\"req text-danger\">*</span></label>\n          <input type=\"number\" min=\"1\" class=\"form-control form-control-sm\" name=\"medicamentos[".concat(i, "][cantidad]\" required>\n        </div>\n        <div class=\"form-group col-md-3 mb-2\">\n          <label class=\"mb-1 text-muted small\">Registro N\xBA</label>\n          <input type=\"text\" class=\"form-control form-control-sm regno\" name=\"medicamentos[").concat(i, "][regNo]\" readonly>\n        </div>\n        <div class=\"form-group col-md-3 mb-2\">\n          <label class=\"mb-1 text-muted small\">Presentaci\xF3n</label>\n          <input type=\"text\" class=\"form-control form-control-sm presentacion\" name=\"medicamentos[").concat(i, "][presentacion]\">\n        </div>\n      </div>\n      <div class=\"form-row\">\n        <div class=\"form-group col-md-4 mb-2\">\n          <label class=\"mb-1 text-muted small\">Nombre</label>\n          <input type=\"text\" class=\"form-control form-control-sm nombre\" name=\"medicamentos[").concat(i, "][nombre]\">\n        </div>\n        <div class=\"form-group col-md-4 mb-2\">\n          <label class=\"mb-1 text-muted small\">Droga</label>\n          <input type=\"text\" class=\"form-control form-control-sm droga\" name=\"medicamentos[").concat(i, "][nombreDroga]\">\n        </div>\n        <div class=\"form-group col-md-3 mb-2\">\n          <label class=\"mb-1 text-muted small\">Tratamiento (d\xEDas)</label>\n          <input type=\"number\" min=\"0\" class=\"form-control form-control-sm\" name=\"medicamentos[").concat(i, "][tratamiento]\" placeholder=\"0\">\n        </div>\n        <div class=\"form-group col-md-1 d-flex align-items-end mb-2\">\n          <button type=\"button\" class=\"btn btn-sm btn-outline-danger btn-del-med\" title=\"Eliminar\">\xD7</button>\n        </div>\n      </div>\n      <div class=\"form-group mb-2\">\n        <label class=\"mb-1 text-muted small\">Posolog\xEDa</label>\n        <input type=\"text\" class=\"form-control form-control-sm\" name=\"medicamentos[").concat(i, "][posologia]\">\n      </div>\n      <div class=\"form-group mb-2\">\n        <label class=\"mb-1 text-muted small\">Indicaciones / Observaciones</label>\n        <textarea class=\"form-control form-control-sm\" name=\"medicamentos[").concat(i, "][indicaciones]\" rows=\"2\"></textarea>\n      </div>\n      <div class=\"custom-control custom-checkbox custom-control-inline\">\n        <input type=\"checkbox\" class=\"custom-control-input duplicado\" id=\"dup").concat(i, "\" name=\"medicamentos[").concat(i, "][forzarDuplicado]\" value=\"1\">\n        <label class=\"custom-control-label\" for=\"dup").concat(i, "\">Requiere duplicado</label>\n      </div>\n    </div>");
    };
    function toggleDel() {
      var $rows = $wrap.children('.med-row');
      $rows.find('.btn-del-med').prop('disabled', $rows.length <= 1);
    }
    $add.on('click', function () {
      var $r = $(tpl(idx));
      $wrap.append($r);
      attachMed($r);
      idx++;
      toggleDel();
    });
    $wrap.on('click', '.btn-del-med', function () {
      if ($wrap.children().length > 1) {
        $(this).closest('.med-row').remove();
        toggleDel();
      }
    });
    attachMed($wrap.find('.med-row').first());
    toggleDel();
  }

  // ===== Prácticas (si aplica) =====
  function initPracticas() {
    var $s = $('#practica_search');
    if (!$s.length) return;
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
    var $chips = $('#practicasList'),
      $h = $('#practicasHidden');
    function addChip(it) {
      var id = it.id;
      if ($h.find("input[value=\"".concat(id, "\"]")).length) return;
      $h.append($('<input type="hidden" name="practicas[]">').val(id));
      $chips.append($("<span class=\"badge badge-primary d-inline-flex align-items-center mr-2 mb-2\" data-id=\"".concat(id, "\" style=\"font-size:.8rem;\"><span class=\"mr-2\">").concat(it.text, "</span><button type=\"button\" class=\"btn btn-sm btn-light py-0 px-1 quitar-chip\" aria-label=\"Quitar\" style=\"line-height:1;\">\xD7</button></span>")));
    }
    $s.on('select2:select', function () {
      var it = $s.select2('data')[0];
      if (it) {
        addChip(it);
        $s.val(null).trigger('change');
      }
    });
    $chips.on('click', '.quitar-chip', function () {
      var id = $(this).closest('[data-id]').data('id');
      $h.find("input[name=\"practicas[]\"][value=\"".concat(id, "\"]")).remove();
      $(this).closest('[data-id]').remove();
    });
  }

  // ===== Sexo sin opción vacía =====
  function initSexo() {
    var $s = $('[name="paciente[sexo]"]');
    if (!$s.length) return;
    if (!$s.val()) $s.val('M');
    $s.find('option').each(function () {
      var v = $(this).val();
      if (v === 'M') $(this).text('Hombre (M)');else if (v === 'F') $(this).text('Mujer (F)');else if (v === 'X') $(this).text('No binario (X)');else if (v === 'O') $(this).text('Otro (O)');
    });
  }

  // ===== Matrícula MN/MP: provincia obligatoria sólo en MP =====
  function initMatricula() {
    var $tipo = $('[name="medico[matricula][tipo]"]');
    var $numero = $('[name="medico[matricula][numero]"]');
    var $provMat = $('[name="medico[matricula][provincia]"]');
    var $provMatWrap = $provMat.closest('.form-group');
    fetchProvincias().then(function (items) {
      // === Select de provincias para MATRÍCULA ===
      if ($provMat.length) {
        $provMat = toSelect($provMat, items, {
          name: 'medico[matricula][provincia]'
        });
      }

      // === Select de provincias para DOMICILIO (opcional) ===
      var $provDom = $('[name="domicilio[provincia]"]');
      if ($provDom.length) {
        toSelect($provDom, items, {
          name: 'domicilio[provincia]'
        });
      }
      function applyRules() {
        var t = ($tipo.val() || 'MN').toUpperCase().trim();
        if (!$tipo.val()) $tipo.val('MN');
        $numero.attr('maxlength', '10');
        setRequired($numero, true);
        if (t === 'MP') {
          $provMatWrap.show();
          setRequired($provMat, true);
        } else {
          $provMatWrap.hide();
          setRequired($provMat, false);
        }
      }
      $tipo.on('change', applyRules);
      if (!$tipo.val()) $tipo.val('MN');
      applyRules();
    });
  }

  // ===== Fecha: sólo datepicker (readonly) =====
  function initFecha() {
    var $iso = $('[name="paciente[fechaNacimiento]"]');
    if (!$iso.length) return;
    var $vis = $('<input type="text" id="paciente_fecha_visual" class="form-control form-control-sm" placeholder="DD/MM/AAAA" readonly>');
    var curISO = $iso.val();
    $vis.val(isoAEs(curISO)).insertAfter($iso);

    // Si setPac corrió antes que initFecha, aplicamos la fecha pendiente
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
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
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

  // ===== Submit (validaciones al usuario) =====
  function initSubmit() {
    var $f = $('#recetaForm');
    if (!$f.length) return;
    $f.on('submit', function (e) {
      e.preventDefault();
      var items = [];

      // Normalizar fecha visual → ISO (YYYY-MM-DD) antes de enviar
      var vis = $('#paciente_fecha_visual').val();
      var iso = esAISO(vis);
      if (vis && iso) {
        $('[name="paciente[fechaNacimiento]"]').val(iso);
      }

      // ===== DNI paciente =====
      var dni = ($('[name="paciente[nroDoc]"]').val() || '').replace(/\D+/g, '');
      if (!dni) {
        items.push('DNI del paciente es obligatorio.');
      } else if (dni.length < 7 || dni.length > 9) {
        items.push('DNI del paciente debe tener entre 7 y 9 dígitos.');
      }

      // ===== Sexo paciente =====
      var sexo = ($('[name="paciente[sexo]"]').val() || '').toUpperCase();
      if (!['M', 'F', 'X', 'O'].includes(sexo)) {
        items.push('Sexo seleccionado no es válido.');
      }

      // ===== Fecha de nacimiento: no futura =====
      var fiso = $('[name="paciente[fechaNacimiento]"]').val();
      if (fiso) {
        var d = new Date(fiso + 'T00:00:00');
        var today = new Date();
        today.setHours(0, 0, 0, 0);
        if (isNaN(d.getTime())) {
          items.push('Fecha de nacimiento inválida.');
        } else if (d > today) {
          items.push('La fecha de nacimiento no puede ser futura.');
        }
      }

      // ===== Matrícula médico =====
      var tipoMat = ($('[name="medico[matricula][tipo]"]').val() || 'MN').toUpperCase();
      var nroMat = ($('[name="medico[matricula][numero]"]').val() || '').replace(/\D+/g, '');
      var provMat = ($('[name="medico[matricula][provincia]"]').val() || '').trim();
      if (!nroMat) {
        items.push('Número de matrícula es obligatorio.');
      } else if (nroMat.length > 10) {
        items.push('Número de matrícula: máximo 10 dígitos.');
      }
      if (tipoMat === 'MP' && !provMat) {
        items.push('Para matrícula provincial (MP), la provincia es obligatoria.');
      }

      // ===== Domicilio OPCIONAL =====
      // Solo se considera que hay domicilio si completan algo DISTINTO de la provincia.
      // La provincia NO dispara validación por sí sola.
      var dCalle = ($('[name="domicilio[calle]"]').val() || '').trim();
      var dNum = ($('[name="domicilio[numero]"]').val() || '').trim();
      var domProvEl = $('[name="domicilio[provincia]"]');
      var dProv = (domProvEl.val() || '').trim(); // no se usa para validar, solo lo dejamos por las dudas
      var dLoc = ($('[name="domicilio[localidad]"]').val() || '').trim();
      var dCP = ($('[name="domicilio[codigoPostal]"]').val() || '').trim();

      // NO incluimos dProv acá, así que seleccionar solo provincia NO obliga nada
      // Todo un tema lo de provincias
      var anyDom = dCalle || dNum || dLoc || dCP;
      if (anyDom) {
        if (!dCalle) {
          items.push('Domicilio: la calle es obligatoria si completás el domicilio.');
        }
        if (!dNum) {
          items.push('Domicilio: el número es obligatorio si completás el domicilio.');
        }
        // La provincia ES OPCIONAL para domicilio, no la validamos acá
      }

      // ===== Cobertura: si hay financiador, número de afiliado obligatorio y numérico =====
      var idFin = ($('[name="cobertura[idFinanciador]"]').val() || '').trim();
      var cred = ($('[name="cobertura[credencial]"]').val() || '').trim();
      if (idFin) {
        if (!cred) {
          items.push('Cobertura: el número de afiliado es obligatorio si indicás un financiador.');
        } else if (!/^\d+$/.test(cred)) {
          items.push('Cobertura: el número de afiliado debe tener solo números (sin puntos ni guiones).');
        }
      }

      // ===== Medicamentos mínimos =====
      var $rows = $('#medsWrapper .med-row');
      if (!$rows.length) {
        items.push('Agregá al menos un medicamento.');
      }
      $rows.each(function (i) {
        var $r = $(this);
        var cant = Number($r.find('input[name^="medicamentos"][name$="[cantidad]"]').val() || 0);
        var reg = ($r.find('.regno').val() || '').trim();
        if (!cant || cant < 1) {
          items.push("Medicamento (fila ".concat(i + 1, "): la cantidad debe ser mayor a 0."));
        }
        if (!reg) {
          items.push("Medicamento (fila ".concat(i + 1, "): seleccion\xE1 un medicamento de la lista (Registro N\xBA)."));
        }
      });

      // ===== Si hay errores, mostramos modal y no enviamos =====
      if (items.length) {
        modal({
          title: 'Revisá estos datos',
          html: errList(items)
        });
        return;
      }

      // ===== Envío AJAX =====
      var url = $f.attr('action');
      var data = $f.serialize();
      var $btn = $f.find('button[type="submit"]');
      var old = $btn.html();
      $btn.prop('disabled', true).html('Generando…');
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
        modal({
          title: 'Aviso',
          html: "<p>".concat(esc((r === null || r === void 0 ? void 0 : r.message) || 'Operación realizada.'), "</p>")
        });
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
        modal({
          title: 'No se pudo generar la receta',
          html: errList(items2)
        });
      }).always(function () {
        $btn.prop('disabled', false).html(old);
      });
    });
  }

  // ===== Boot =====
  function boot() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      timeout: AJAX_TIMEOUT_MS
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
          // cargo provincias antes de reglas de matrícula
          initMatricula();
          initNomina();
          initFinanciadores();
          initDiagnosticos();
          initMeds();
          initPracticas();
        });
      } else if (tries > 60) {
        clearInterval(iv);
        fetchProvincias().then(initMatricula);
        initMeds();
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

module.exports = __webpack_require__(/*! C:\laragon\www\ejornal_laravel\resources\js\empleados\recetas\create.js */"./resources/js/empleados/recetas/create.js");


/***/ })

/******/ });