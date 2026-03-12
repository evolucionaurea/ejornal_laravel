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
/******/ 	return __webpack_require__(__webpack_require__.s = 4);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/users/create.js":
/*!********************************************!*\
  !*** ./resources/js/admin/users/create.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  var fields = [{
    roles: [1],
    "class": '.select_permiso_edicion_fichadas'
  }, {
    roles: [2],
    "class": '.mostrar_personal_interno'
  }, {
    roles: [2],
    "class": '.mostrar_clientes'
  }, {
    roles: [2],
    "class": '.mostrar_permiso_desplegables'
  }, {
    roles: [2],
    "class": '.mostrar_especialidades'
  }, {
    roles: [2],
    "class": '.mostrar_cuil'
  }, {
    roles: [2],
    "class": '.mostrar_calle'
  }, {
    roles: [2],
    "class": '.mostrar_nro'
  }, {
    roles: [2],
    "class": '.mostrar_entre_calles'
  }, {
    roles: [2],
    "class": '.mostrar_localidad'
  }, {
    roles: [2],
    "class": '.mostrar_partido'
  }, {
    roles: [2],
    "class": '.mostrar_cod_postal'
  }, {
    roles: [2],
    "class": '.mostrar_permitir_fichada'
  }, {
    roles: [2],
    "class": '.mostrar_observaciones'
  }, {
    roles: [2],
    "class": '.select_contratacion_users'
  }, {
    roles: [2],
    "class": '.liquidacion_onedrive_creacion_users'
  }, {
    roles: [3],
    "class": '.cliente_original'
  }, {
    roles: [4],
    "class": '.grupos'
  }];
  function roleId() {
    return parseInt($('[name="rol"]').val() || '0', 10);
  }
  function especialidadId() {
    return parseInt($('[name="especialidad"]').val() || '0', 10);
  }
  function isEmpleadoMedico() {
    return roleId() === 2 && especialidadId() === 1;
  }
  function mostrar_ocultar_campos(rid) {
    rid = parseInt(rid, 10);
    fields.forEach(function (field) {
      if (!field.roles.includes(rid)) $(field["class"]).addClass('d-none');else $(field["class"]).removeClass('d-none');
    });
    if (rid === 2) $('.mostrar_clientes label').text('¿Para quien trabajará?');
    if (rid === 3) $('.mostrar_clientes label').text('¿Este usuario a que Cliente pertenece?');

    // SOLO empleado médico: mostrar sección docs
    $('.mostrar_docs_medico').toggleClass('d-none', !isEmpleadoMedico());
  }

  // Inicial
  mostrar_ocultar_campos($('[name="rol"]').val());

  // Cambios
  $('[name="rol"]').on('change', function (e) {
    mostrar_ocultar_campos($(e.currentTarget).val());
  });
  $(document).on('change', '[name="especialidad"]', function () {
    mostrar_ocultar_campos($('[name="rol"]').val());
  });

  // select2 clientes
  if ($('#cliente_select_multiple').length) {
    $('#cliente_select_multiple').select2({
      placeholder: 'Buscar...'
    }).trigger('change');
  }

  // custom-file label
  $(document).on('change', '.custom-file-input', function () {
    var fileName = this.files && this.files.length ? this.files[0].name : 'Seleccionar archivo';
    $(this).siblings('.custom-file-label').text(fileName);
  });

  // ===== límite uploads (10MB por archivo, 10MB total) =====
  var MAX_FILE_BYTES = 10 * 1024 * 1024;
  var MAX_TOTAL_BYTES = 10 * 1024 * 1024;
  function mb(bytes) {
    return (bytes / 1024 / 1024).toFixed(2);
  }
  function showWarn(msg) {
    if (window.Swal && Swal.fire) {
      Swal.fire({
        icon: 'warning',
        title: 'Carga inválida',
        text: msg
      });
    } else {
      alert(msg);
    }
  }
  function validateFiles($form) {
    var total = 0;
    var tooBigFile = null;
    $form.find('input[type="file"]').each(function () {
      var files = this.files;
      if (!files || !files.length) return;
      for (var i = 0; i < files.length; i++) {
        var f = files[i];
        total += f.size;
        if (f.size > MAX_FILE_BYTES) {
          tooBigFile = f;
          return false;
        }
      }
      if (tooBigFile) return false;
    });
    if (tooBigFile) {
      showWarn("\"".concat(tooBigFile.name, "\" supera ").concat(mb(MAX_FILE_BYTES), "MB. Eleg\xED un archivo m\xE1s liviano."));
      return false;
    }
    if (total > MAX_TOTAL_BYTES) {
      showWarn("La carga total es ".concat(mb(total), "MB y supera el m\xE1ximo permitido (").concat(mb(MAX_TOTAL_BYTES), "MB). Sub\xED menos archivos por vez."));
      return false;
    }
    return true;
  }

  // Intercept submit en create y edit
  $('#form_create_user_por_admin, #form_edit_user_por_admin').on('submit', function (e) {
    if (!$('.mostrar_docs_medico').length) return;
    if ($('.mostrar_docs_medico').hasClass('d-none')) return;
    var ok = validateFiles($(this));
    if (!ok) {
      e.preventDefault();
      e.stopImmediatePropagation();
      return false;
    }
  });

  // confirmación existente (solo edit)
  $("#admin_edit_user").click(function (e) {
    e.preventDefault();
    var rol = $('[name="rol"]').val();
    if (rol == 2) {
      var fichada = $('#validacion_submit').data('fichada');
      var usuario_debe_fichar = $('[name="fichar"]').val();
      if (fichada == 1 && usuario_debe_fichar == 0) {
        Swal.fire({
          icon: 'warning',
          title: 'El usuario tiene la fichada activa. Si continúa, ficharemos la salida.',
          showCancelButton: true,
          reverseButtons: true,
          cancelButtonText: '<i class="fa fa-times fa-fw"></i> Cancelar',
          confirmButtonText: '<i class="fa fa-check fa-fw"></i> Aceptar'
        }).then(function (result) {
          if (result.isConfirmed) {
            $('#form_edit_user_por_admin').submit();
          }
        });
      } else {
        $('#form_edit_user_por_admin').submit();
      }
    } else {
      $('#form_edit_user_por_admin').submit();
    }
  });
});

/***/ }),

/***/ "./resources/js/admin/users/edit.js":
/*!******************************************!*\
  !*** ./resources/js/admin/users/edit.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! ./create.js */ "./resources/js/admin/users/create.js");

/***/ }),

/***/ 4:
/*!************************************************!*\
  !*** multi ./resources/js/admin/users/edit.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\ela_g\Herd\ejornal_laravel\resources\js\admin\users\edit.js */"./resources/js/admin/users/edit.js");


/***/ })

/******/ });