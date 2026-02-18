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
/******/ 	return __webpack_require__(__webpack_require__.s = 61);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/empleados/preocupacionales/create.js":
/*!***********************************************************!*\
  !*** ./resources/js/empleados/preocupacionales/create.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
var Preocupacional = /*#__PURE__*/function () {
  function Preocupacional() {
    var _this = this;
    _classCallCheck(this, Preocupacional);
    get_template('/templates/tr-certificado-ausentismo').then(function (template) {
      _this.table_archivos = $('[data-table="archivos"]');
      _this.archivo_row = template;
      _this.init();
    });
  }
  return _createClass(Preocupacional, [{
    key: "init",
    value: function init() {
      var _this2 = this;
      $('[data-toggle="select2"]').select2();
      $('[name="fecha"],[name="fecha_vencimiento"]').datepicker();
      $('[name="tiene_vencimiento"]').change(function (select) {
        var value = $(select.currentTarget).val();
        if (value == '1') {
          $('[data-toggle="vencimiento"]').removeClass('d-none');
          $('[name="fecha_vencimiento"]').attr({
            required: true
          });
        } else {
          $('[data-toggle="vencimiento"]').addClass('d-none');
          $('[name="fecha_vencimiento"]').attr({
            required: false
          });
        }
      });
      $('[name="completado"]').change(function (select) {
        var value = $(select.currentTarget).val();
        if (value == '1') {
          $('[name="completado_comentarios"]').attr({
            required: true,
            disabled: false
          });
        } else {
          $('[name="completado_comentarios"]').attr({
            required: false,
            disabled: true
          });
        }
      });

      /// ARCHIVOS
      $('[data-toggle="agregar-archivo"]').click(function (btn) {
        var tr = $(_this2.archivo_row);
        _this2.table_archivos.find('tbody').append(tr);
      });
      this.table_archivos.on('click', 'tbody tr button[data-toggle="quitar-archivo"]', function (btn) {
        var tbody = $(btn.currentTarget).closest('tbody');
        var tr = $(btn.currentTarget).closest('tr');
        // Se quita la validacion de que minimo debe subirse un archivo
        // const indx = tr.index()
        // if(indx == 0){
        // 	Swal.fire({
        // 		icon:'warning',
        // 		title:'Debes subir al menos 1 archivo'
        // 	})
        // 	return false
        // }

        tr.remove();
      });
      this.table_archivos.on('change', 'input[type="file"]', function (event) {
        event.preventDefault();
        var wrapper = $(event.currentTarget).closest('.custom-file');
        wrapper.find('label').text(event.target.files[0].name);
      });
    }
  }]);
}();
new Preocupacional();

/***/ }),

/***/ 61:
/*!*****************************************************************!*\
  !*** multi ./resources/js/empleados/preocupacionales/create.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\laragon\www\ejornal_laravel\resources\js\empleados\preocupacionales\create.js */"./resources/js/empleados/preocupacionales/create.js");


/***/ })

/******/ });