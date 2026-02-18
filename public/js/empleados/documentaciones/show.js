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
/******/ 	return __webpack_require__(__webpack_require__.s = 42);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/classes/Certificado.js":
/*!*********************************************!*\
  !*** ./resources/js/classes/Certificado.js ***!
  \*********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); } r ? i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2)); }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
var Certificado = /*#__PURE__*/function () {
  function Certificado() {
    var _this = this;
    _classCallCheck(this, Certificado);
    Promise.all([get_template('/templates/tr-certificado-ausentismo'), get_template('/templates/tr-certificado-ausentismo-readonly'), get_template('/templates/form-certificado')]).then(function (promise) {
      _this.popup = $('#popups');
      _this.table = $('[data-table="certificados"]');
      _this.table_archivos_cert = '[data-table="certificaciones_archivos"]';
      _this.tr_certificado_ausentismo = promise[0];
      _this.tr_certificado_ausentismo_readonly = promise[1];
      _this.form_certificado = promise[2];
      _this.init();
    });
  }
  return _createClass(Certificado, [{
    key: "pop_certificado",
    value: function pop_certificado() {
      var _this2 = this;
      var data = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
      var $form = $(this.form_certificado);
      $form.find(this.table_archivos_cert).find('tbody').html('');
      if (data) {
        $.each(data, function (k, v) {
          $form.find("[name=\"".concat(k, "\"]")).val(v);
        });
        if (data.matricula_nacional != null) $form.find('[data-toggle="validar-matricula"]').trigger('click');
        if (data.archivos.length > 0) {
          data.archivos.map(function (archivo, k) {
            var tr = $(_this2.tr_certificado_ausentismo_readonly);
            tr.find('a').text(archivo.archivo).attr({
              href: "../documentacion_ausentismo/archivo/".concat(archivo.ausentismo_documentacion_id)
            });
            $form.find(_this2.table_archivos_cert).find('tbody').append(tr);
          });
        }
      } else {
        $form.find('[name="id_ausentismo"]').val($('[data-toggle="crear-certificado"]').attr('data-ausenciaid'));
      }
      $form.find('[name="fecha_documento"]').datepicker();
      this.popup.find('.modal-body').html($form);
      this.popup.find('.modal-dialog').addClass('modal-lg');
      this.popup.modal('show');
      if (!data) this.popup.find('[data-toggle="agregar-archivo-cert"]').trigger('click');
    }
  }, {
    key: "init",
    value: function init() {
      var _this3 = this;
      console.log('certificados');
      this.popup.on('click', '[data-toggle="validar-matricula"]', function (btn) {
        if (_this3.popup.find('[name="matricula_nacional"]').val() == '') {
          Swal.fire({
            icon: 'warning',
            title: 'Debes agregar algún número de matrícula'
          });
          return false;
        }
        _this3.popup.find('[data-toggle="certificado-validar-icon"][data-value="ok"]').removeClass('d-none');
        _this3.popup.find('[name="matricula_validada"]').val(1);
      });
      this.popup.on('click', '[data-toggle="agregar-archivo-cert"]', function (btn) {
        var tr = $(_this3.tr_certificado_ausentismo);
        console.log(tr);
        _this3.popup.find(_this3.table_archivos_cert).find('tbody').append(tr);
      });
      this.popup.on('click', 'button[data-toggle="quitar-archivo"]', function (btn) {
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
      this.popup.on('change', "".concat(this.table_archivos_cert, " input[type=\"file\"]"), function (event) {
        event.preventDefault();
        var wrapper = $(event.currentTarget).closest('.custom-file');
        wrapper.find('label').text(event.target.files[0].name);
      });

      ///editar
      this.table.on('click', '[data-toggle="editar-certificado"]', /*#__PURE__*/function () {
        var _ref = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee(btn) {
          var tr, id, response;
          return _regenerator().w(function (_context) {
            while (1) switch (_context.n) {
              case 0:
                tr = $(btn.currentTarget).closest('tr');
                id = tr.attr('data-id');
                _context.n = 1;
                return axios.get("/empleados/documentaciones/find_ajax/".concat(id));
              case 1:
                response = _context.v;
                if (!(response.status != 200)) {
                  _context.n = 2;
                  break;
                }
                Swal.fire({
                  icon: 'error',
                  title: 'No se pudo encontrar el certificado'
                });
                return _context.a(2, false);
              case 2:
                _this3.pop_certificado(response.data);
              case 3:
                return _context.a(2);
            }
          }, _callee);
        }));
        return function (_x) {
          return _ref.apply(this, arguments);
        };
      }());
      ///new
      $('[data-toggle="crear-certificado"]').click(function (btn) {
        var id_ausentismo = $(btn.currentTarget).attr('data-ausenciaid');
        _this3.pop_certificado();
      });
    }
  }]);
}();
/* harmony default export */ __webpack_exports__["default"] = (Certificado);

/***/ }),

/***/ "./resources/js/empleados/documentaciones/show.js":
/*!********************************************************!*\
  !*** ./resources/js/empleados/documentaciones/show.js ***!
  \********************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _classes_Certificado_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../classes/Certificado.js */ "./resources/js/classes/Certificado.js");

$(function () {
  console.log('cert');
  new _classes_Certificado_js__WEBPACK_IMPORTED_MODULE_0__["default"]();
});

/***/ }),

/***/ 42:
/*!**************************************************************!*\
  !*** multi ./resources/js/empleados/documentaciones/show.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\laragon\www\ejornal_laravel\resources\js\empleados\documentaciones\show.js */"./resources/js/empleados/documentaciones/show.js");


/***/ })

/******/ });