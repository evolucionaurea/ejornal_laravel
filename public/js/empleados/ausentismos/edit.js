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
/******/ 	return __webpack_require__(__webpack_require__.s = 38);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/empleados/ausentismos/create.js":
/*!******************************************************!*\
  !*** ./resources/js/empleados/ausentismos/create.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
var Ausentismo = /*#__PURE__*/function () {
  function Ausentismo() {
    var _this = this;
    _classCallCheck(this, Ausentismo);
    get_template('/templates/tr-certificado-ausentismo').then(function (template) {
      _this.table_archivos_cert = $('[data-table="certificado_archivos"]');
      _this.table_archivos_comunicacion = $('[data-table="comunicacion_archivos"]');
      _this.tr_archivo = template;
      _this.init();
    });
  }
  return _createClass(Ausentismo, [{
    key: "init",
    value: function init() {
      var _this2 = this;
      $('[name="fecha_inicio"]').datepicker({
        onSelect: function onSelect(date, obj) {
          var minDate = new Date(obj.selectedYear, obj.selectedMonth, obj.selectedDay);
          $('[name="fecha_final"],[name="fecha_regreso_trabajar"]').datepicker('destroy');
          $('[name="fecha_final"],[name="fecha_regreso_trabajar"]').datepicker({
            minDate: minDate
          });
        }
      });
      $('[name="fecha_final"]').on('change', function (event) {
        var value = $(event.currentTarget).val();
        $('[name="fecha_regreso_trabajar"]').val(value);
      });
      console.log('class: Ausentismo');
      $('.select_2').select2();
      $('.select_2').trigger('change');
      var inst = $.datepicker._getInst($('[name="fecha_inicio"]')[0]);
      $.datepicker._get(inst, 'onSelect').apply(inst.input[0], [$('[name="fecha_inicio"]').datepicker('getDate'), inst]);
      $('.btn_editar_tipo_ausentismo').on('click', function (event) {
        var id_tipo = $(this).data("id");
        var tipo_actual = $(this).data("text");
        var color = $(this).data("color");
        var incluir_indice = $(this).data("indice");
        $('#editar_tipo_ausentismo [name="tipo_editado"]').val(tipo_actual);
        $('#editar_tipo_ausentismo [name="id_tipo"]').val(id_tipo);
        $('#editar_tipo_ausentismo [name="color"]').val(color);
        $('#editar_tipo_ausentismo [name="editar_incluir_indice"]').val(incluir_indice || 0);
      });

      //// Certificado
      $('[name="incluir_certificado"]').on('change', function (input) {
        var checked = $(input.currentTarget).is(':checked');
        var required_fields = ['cert_institucion', 'cert_medico', 'cert_fecha_documento', 'cert_diagnostico'];
        var required;
        if (checked) {
          required = true;
          $('#certificado_content').slideDown();
        } else {
          required = false;
          $('#certificado_content').slideUp();
        }
        required_fields.map(function (field) {
          $("[name=\"".concat(field, "\"]")).prop({
            required: required
          });
        });
      });
      $('[data-toggle="incluir-certificado"]').click(function (btn) {
        var wrapper = $(btn.currentTarget).closest('.input-group');
        var checkbox = wrapper.find('input');
        checkbox.prop({
          checked: !checkbox.prop('checked')
        }).trigger('change');
      });
      $('[data-toggle="validar-matricula"]').click(function (btn) {
        if ($('[name="cert_matricula_nacional"]').val() == '') {
          Swal.fire({
            icon: 'warning',
            title: 'Debes agregar algún número de matrícula'
          });
          return false;
        }
        $('[data-toggle="certificado-validar-icon"][data-value="ok"]').removeClass('d-none');
        $('[name="matricula_validada"]').val(1);
      });
      $('[data-toggle="agregar-archivo-cert"]').click(function (btn) {
        var tr = $(_this2.tr_archivo);
        tr.find('input').attr({
          name: 'archivos_certificado[]'
        });
        tr.find('button').attr({
          'data-toggle': 'quitar-archivo-certificado'
        });
        _this2.table_archivos_cert.find('tbody').append(tr);
      });
      $('[data-toggle="agregar-archivo-comunicacion"]').click(function (btn) {
        var tr = $(_this2.tr_archivo);
        tr.find('input').attr({
          name: 'archivos_comunicacion[]'
        });
        tr.find('button').attr({
          'data-toggle': 'quitar-archivo-comunicacion'
        });
        _this2.table_archivos_comunicacion.find('tbody').append(tr);
      });
      this.table_archivos_cert.on('click', 'tbody tr button[data-toggle="quitar-archivo-certificado"]', function (btn) {
        var tbody = $(btn.currentTarget).closest('tbody');
        var tr = $(btn.currentTarget).closest('tr');
        var indx = tr.index();
        if (indx == 0) {
          Swal.fire({
            icon: 'warning',
            title: 'Debes subir al menos 1 archivo'
          });
          return false;
        }
        tr.remove();
      });
      this.table_archivos_cert.on('change', 'input[type="file"]', function (event) {
        event.preventDefault();
        var wrapper = $(event.currentTarget).closest('.custom-file');
        wrapper.find('label').text(event.target.files[0].name);
      });
      this.table_archivos_comunicacion.on('click', 'tbody tr button[data-toggle="quitar-archivo-comunicacion"]', function (btn) {
        var tbody = $(btn.currentTarget).closest('tbody');
        var tr = $(btn.currentTarget).closest('tr');
        tr.remove();
      });
      this.table_archivos_comunicacion.on('change', 'input[type="file"]', function (event) {
        event.preventDefault();
        var wrapper = $(event.currentTarget).closest('.custom-file');
        wrapper.find('label').text(event.target.files[0].name);
      });
      $('[name="cert_fecha_documento"]').datepicker();
    }
  }]);
}();
new Ausentismo();

/***/ }),

/***/ "./resources/js/empleados/ausentismos/edit.js":
/*!****************************************************!*\
  !*** ./resources/js/empleados/ausentismos/edit.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! ./create */ "./resources/js/empleados/ausentismos/create.js");

/***/ }),

/***/ 38:
/*!**********************************************************!*\
  !*** multi ./resources/js/empleados/ausentismos/edit.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\laragon\www\ejornal_laravel\resources\js\empleados\ausentismos\edit.js */"./resources/js/empleados/ausentismos/edit.js");


/***/ })

/******/ });