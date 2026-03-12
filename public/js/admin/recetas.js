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
/******/ 	return __webpack_require__(__webpack_require__.s = 21);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/recetas.js":
/*!***************************************!*\
  !*** ./resources/js/admin/recetas.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function (window, document) {
  'use strict';

  var $ = window.jQuery || null;
  function q(sel, ctx) {
    return (ctx || document).querySelector(sel);
  }
  function qa(sel, ctx) {
    return Array.from((ctx || document).querySelectorAll(sel));
  }

  // ================== SweetAlert helper ==================
  function ensureSwal(cb) {
    if (window.Swal) return cb();
    var s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    s.onload = cb;
    document.head.appendChild(s);
  }
  function showLoadingSwal(text) {
    if (!window.Swal) return;
    window.Swal.fire({
      title: text || 'Cargando...',
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: function didOpen() {
        window.Swal.showLoading();
      },
      showConfirmButton: false
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
    }).then(function (res) {
      return res.text();
    }).then(function (html) {
      container.innerHTML = html;

      // Actualizar URL del navegador sin recargar
      try {
        window.history.replaceState({}, '', url);
      } catch (e) {}

      // Re-bind sobre el nuevo HTML
      bindAjaxPagination();
      if (window.Swal && window.Swal.isLoading()) window.Swal.close();
    })["catch"](function () {
      if (window.Swal && window.Swal.isLoading()) window.Swal.close();
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
      var fd = new FormData(form);
      var params = new URLSearchParams(fd).toString();
      var url = action + (action.indexOf('?') === -1 ? '?' : '&') + params;
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
    if (!$ || !$.fn || !$.fn.datepicker) return;
    if ($.datepicker && $.datepicker.regional && $.datepicker.regional['es']) {
      $.datepicker.setDefaults($.datepicker.regional['es']);
    }
    $('.js-datepicker').each(function () {
      var $input = $(this);
      if ($input.data('jsDatepickerInit')) return;
      $input.data('jsDatepickerInit', true);
      var hiddenId = $input.data('hidden-target');
      var hasAlt = hiddenId && $('#' + hiddenId).length;
      var opts = {
        dateFormat: 'dd-mm-yy',
        // lo que ve el usuario
        changeMonth: true,
        changeYear: true
      };
      if (hasAlt) {
        opts.altField = '#' + hiddenId; // hidden con formato ISO
        opts.altFormat = 'yy-mm-dd';
      }
      $input.datepicker(opts);

      // Inicializar valor visible a partir del hidden
      if (hasAlt) {
        var isoVal = $('#' + hiddenId).val(); // ej: 2025-12-04
        if (isoVal) {
          try {
            var dateObj = $.datepicker.parseDate('yy-mm-dd', isoVal);
            $input.datepicker('setDate', dateObj); // muestra 04-12-2025
          } catch (e) {}
        }
      }
    });
  }

  // ================== Select2 (Trabajador / Cliente) ==================
  function initSelect2Filters() {
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

/***/ }),

/***/ 21:
/*!*********************************************!*\
  !*** multi ./resources/js/admin/recetas.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\ela_g\Herd\ejornal_laravel\resources\js\admin\recetas.js */"./resources/js/admin/recetas.js");


/***/ })

/******/ });