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
/******/ 	return __webpack_require__(__webpack_require__.s = 24);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/empleados/cuenta.js":
/*!******************************************!*\
  !*** ./resources/js/empleados/cuenta.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  // límites
  var MAX_FILE_BYTES = 2 * 1024 * 1024; // 2MB por archivo
  var MAX_TOTAL_BYTES = 7 * 1024 * 1024; // 7MB total (seguro < 8MB con multipart)

  var $form = $('#form-documentacion');
  var $alert = $('#upload-alert');
  var $alertText = $('#upload-alert-text');
  if (!$form.length) return;
  function mb(bytes) {
    return (bytes / 1024 / 1024).toFixed(2);
  }
  function showUploadError(msg) {
    if ($alert.length) {
      $alertText.text(msg);
      $alert.removeClass('d-none');
    } else {
      alert(msg);
    }
  }
  function hideUploadError() {
    if ($alert.length) $alert.addClass('d-none');
  }
  function calcFiles() {
    var total = 0;
    var tooBig = null;
    $form.find('input[type="file"]').each(function () {
      var files = this.files;
      if (!files || !files.length) return;
      for (var i = 0; i < files.length; i++) {
        var f = files[i];
        total += f.size;
        if (f.size > MAX_FILE_BYTES) {
          tooBig = {
            input: this,
            file: f
          };
          return false;
        }
      }
      if (tooBig) return false;
    });
    return {
      total: total,
      tooBig: tooBig
    };
  }
  function validateAllFiles() {
    var _calcFiles = calcFiles(),
      total = _calcFiles.total,
      tooBig = _calcFiles.tooBig;
    if (tooBig) {
      var name = tooBig.file ? tooBig.file.name : 'archivo';
      // limpiar ese input para que no intente enviar
      tooBig.input.value = '';
      // reset label visual
      $(tooBig.input).siblings('.custom-file-label').text('Seleccionar archivo');
      showUploadError("\"".concat(name, "\" supera 2MB. Eleg\xED un archivo m\xE1s liviano."));
      return false;
    }
    if (total > MAX_TOTAL_BYTES) {
      showUploadError("La carga total seleccionada es ".concat(mb(total), "MB y supera el m\xE1ximo permitido (").concat(mb(MAX_TOTAL_BYTES), "MB). ") + "Guard\xE1 en m\xE1s de una vez (sub\xED menos archivos por vez).");
      return false;
    }
    hideUploadError();
    return true;
  }

  // Label + validación al cambiar archivos
  $(document).on('change', '.custom-file-input', function () {
    var fileName = this.files && this.files.length ? this.files[0].name : 'Seleccionar archivo';
    $(this).siblings('.custom-file-label').text(fileName);
    validateAllFiles();
  });

  // Submit: si pasa validación, mostramos feedback visual
  $form.on('submit', function (e) {
    if (!validateAllFiles()) {
      e.preventDefault();
      e.stopImmediatePropagation();
      return false;
    }

    // feedback visible: “Guardando...”
    var $btn = $form.find('button[type="submit"]');
    $btn.prop('disabled', true);
    $btn.data('old-text', $btn.html());
    $btn.html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando…');
  });
});

/***/ }),

/***/ 24:
/*!************************************************!*\
  !*** multi ./resources/js/empleados/cuenta.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\ela_g\Herd\ejornal_laravel\resources\js\empleados\cuenta.js */"./resources/js/empleados/cuenta.js");


/***/ })

/******/ });