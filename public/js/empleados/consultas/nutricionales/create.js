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
/******/ 	return __webpack_require__(__webpack_require__.s = 52);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/empleados/caratulas/caratula_trabajador.js":
/*!*****************************************************************!*\
  !*** ./resources/js/empleados/caratulas/caratula_trabajador.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); } r ? i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2)); }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
$(function () {
  console.log('caratula.trabajador');
  var tokenEl = document.querySelector('meta[name="csrf-token"]');
  var CSRF = tokenEl ? tokenEl.getAttribute('content') : '';
  $('#id_nomina').on('change', /*#__PURE__*/function () {
    var _ref = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee(select) {
      var idNomina, template;
      return _regenerator().w(function (_context) {
        while (1) switch (_context.n) {
          case 0:
            idNomina = $(select.currentTarget).val();
            if (idNomina) {
              _context.n = 1;
              break;
            }
            $('#caratula').html('<p class="alert alert-info">Seleccione un trabajador de la nomina</p>');
            return _context.a(2);
          case 1:
            _context.n = 2;
            return window.get_template("/api/get_caratula_nomina/".concat(idNomina));
          case 2:
            template = _context.v;
            $('#caratula').html(template);
          case 3:
            return _context.a(2);
        }
      }, _callee);
    }));
    return function (_x) {
      return _ref.apply(this, arguments);
    };
  }());
  if ($('#id_nomina').val() != '') {
    $('#id_nomina').trigger('change');
  }
  $('body').on('click', '[data-toggle="editar-caratula"]', /*#__PURE__*/function () {
    var _ref2 = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee2(btn) {
      var idNomina, dominio, template, $template;
      return _regenerator().w(function (_context2) {
        while (1) switch (_context2.n) {
          case 0:
            btn.preventDefault();
            idNomina = $('#id_nomina').val();
            dominio = window.location.origin;
            _context2.n = 1;
            return window.get_template("".concat(dominio, "/api/get_caratula_modal/").concat(idNomina));
          case 1:
            template = _context2.v;
            $template = $(template);
            $template.find('[name="patologia_edit_caratula[]"]').select2();
            $('body').append($template);
            $('#editarCaratulaModal').modal('show');
          case 2:
            return _context2.a(2);
        }
      }, _callee2);
    }));
    return function (_x2) {
      return _ref2.apply(this, arguments);
    };
  }());

  // Eliminar el modal al cerrarlo
  $('body').on('hidden.bs.modal', '#editarCaratulaModal', function () {
    $('#editarCaratulaModal').remove();
  });

  // SUBMIT: actualizar carátula por PUT
  $('body').on('submit', '[data-form="editar-caratula"]', /*#__PURE__*/function () {
    var _ref3 = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee3(form) {
      var post, caratulaId, payload, _response$data, _response$data2, response, idNomina, template, _err$response, _err$response3, status, _err$response2, errs, _Object$values$, first, _t;
      return _regenerator().w(function (_context3) {
        while (1) switch (_context3.p = _context3.n) {
          case 0:
            form.preventDefault();
            post = window.get_form(form.currentTarget); // Obtenemos el ID de carátula para la URL PUT
            caratulaId = $('input[name="caratula_id"]').val() || $('#editarCaratulaModal').data('caratula-id') || $('#editarCaratulaModal').attr('data-caratula-id') || null;
            if (caratulaId) {
              _context3.n = 1;
              break;
            }
            toastr.error('No se encontró el ID de la carátula para actualizar.');
            return _context3.a(2);
          case 1:
            // Construimos el payload que espera el controller
            payload = {
              id_nomina: post.id_nomina || post.trabajador_id_edit_caratula || $('#id_nomina').val() || null,
              medicacion_habitual: post.medicacion_habitual || post.medicacion_habitual_edit_caratula || '',
              antecedentes: post.antecedentes || post.antecedentes_edit_caratula || '',
              alergias: post.alergias || post.alergias_edit_caratula || '',
              peso: post.peso || post.peso_edit_caratula || '',
              altura: post.altura || post.altura_edit_caratula || '',
              imc: post.imc || post.imc_edit_caratula || '',
              id_patologia: post['id_patologia[]'] || post.id_patologia || post['patologia_edit_caratula[]'] || []
            };
            if (!Array.isArray(payload.id_patologia)) {
              payload.id_patologia = payload.id_patologia ? [payload.id_patologia] : [];
            }

            // ⇩⇩ Si imc viene vacío, lo calculamos en el cliente para pasar validación
            if ((!payload.imc || payload.imc === '') && payload.peso && payload.altura) {
              payload.imc = window.calculate_imc(payload.peso, payload.altura);
            }
            _context3.p = 2;
            _context3.n = 3;
            return axios.put("/empleados/caratulas/".concat(encodeURIComponent(caratulaId)), payload, {
              headers: {
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
              },
              withCredentials: true
            });
          case 3:
            response = _context3.v;
            if (!((response === null || response === void 0 || (_response$data = response.data) === null || _response$data === void 0 ? void 0 : _response$data.estado) === false)) {
              _context3.n = 4;
              break;
            }
            toastr.error(response.data.message || 'No se pudo actualizar la carátula.');
            return _context3.a(2);
          case 4:
            toastr.success((response === null || response === void 0 || (_response$data2 = response.data) === null || _response$data2 === void 0 ? void 0 : _response$data2.message) || 'Carátula actualizada con éxito');
            $('#editarCaratulaModal').modal('hide');
            idNomina = payload.id_nomina;
            if (!idNomina) {
              _context3.n = 6;
              break;
            }
            _context3.n = 5;
            return window.get_template("/api/get_caratula_nomina/".concat(idNomina));
          case 5:
            template = _context3.v;
            $('#caratula').html(template);
          case 6:
            _context3.n = 8;
            break;
          case 7:
            _context3.p = 7;
            _t = _context3.v;
            status = _t === null || _t === void 0 || (_err$response = _t.response) === null || _err$response === void 0 ? void 0 : _err$response.status;
            if (status === 422) {
              // Mostramos detalles de validación si vienen
              errs = _t === null || _t === void 0 || (_err$response2 = _t.response) === null || _err$response2 === void 0 || (_err$response2 = _err$response2.data) === null || _err$response2 === void 0 ? void 0 : _err$response2.errors;
              if (errs) {
                first = (_Object$values$ = Object.values(errs)[0]) === null || _Object$values$ === void 0 ? void 0 : _Object$values$[0];
                toastr.error(first || 'Datos inválidos (422).');
              } else {
                toastr.error('Datos inválidos (422).');
              }
            } else if (status === 419) {
              toastr.error('CSRF inválido o sesión expirada (419). Recargá la página.');
            } else if (status === 403) {
              toastr.error('Sin permiso (403).');
            } else {
              toastr.error('Error al actualizar la carátula.');
            }
            console.error('PUT carátula error:', status, (_t === null || _t === void 0 || (_err$response3 = _t.response) === null || _err$response3 === void 0 ? void 0 : _err$response3.data) || _t);
          case 8:
            return _context3.a(2);
        }
      }, _callee3, null, [[2, 7]]);
    }));
    return function (_x3) {
      return _ref3.apply(this, arguments);
    };
  }());
  $('body').on('change keyup', 'input[name="peso_edit_caratula"], input[name="altura_edit_caratula"]', function () {
    var peso = $("input[name='peso_edit_caratula']").val();
    var altura = $("input[name='altura_edit_caratula']").val();
    $("input[name='imc_edit_caratula']").val(window.calculate_imc(peso, altura));
  });
  $('body').on('click', '[data-toggle="usar-datos-caratula"]', function () {
    var peso = $('[data-content="peso"]').text();
    var altura = $('[data-content="altura"]').text();
    $('[name="peso"]').val(peso);
    $('[name="altura"]').val(altura);
    $("input[name='imc']").val(window.calculate_imc(peso, altura));
    $("input[name='imc_disabled']").val(window.calculate_imc(peso, altura));
  });
});

/***/ }),

/***/ "./resources/js/empleados/consultas/nutricionales/create.js":
/*!******************************************************************!*\
  !*** ./resources/js/empleados/consultas/nutricionales/create.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! ../../caratulas/caratula_trabajador */ "./resources/js/empleados/caratulas/caratula_trabajador.js");
$(function () {
  $('.select_2').select2();
  var tipoSelect = document.getElementById('tipo-consulta');
  var camposInicial = document.querySelectorAll('.campos-inicial');
  var camposSeguimiento = document.querySelectorAll('.campos-seguimiento');
  var decimalInputs = document.querySelectorAll('input[type="number"]');
  function toggleCampos() {
    var tipo = tipoSelect.value;
    if (tipo === 'inicial') {
      camposInicial.forEach(function (campo) {
        return campo.style.display = 'block';
      });
      camposSeguimiento.forEach(function (campo) {
        return campo.style.display = 'none';
      });
    } else if (tipo === 'seguimiento') {
      camposInicial.forEach(function (campo) {
        return campo.style.display = 'none';
      });
      camposSeguimiento.forEach(function (campo) {
        return campo.style.display = 'block';
      });
    }
  }

  // Validación para números decimales
  decimalInputs.forEach(function (input) {
    input.addEventListener('input', function (e) {
      var value = e.target.value;
      var regex = /^\d{0,3}(\.\d{0,2})?$/;
      if (!regex.test(value)) {
        alert('Por favor ingrese un valor válido (máximo 3 dígitos enteros y 2 decimales).');
        e.target.value = ''; // Limpiar el campo si el valor es inválido
      }
    });
  });
  tipoSelect.addEventListener('change', toggleCampos);
  toggleCampos(); // Ejecutar al cargar la página

  $('[name="fecha_atencion"]').datepicker();
  $("input[name='peso'],input[name='altura']").on('keyup change', function () {
    var peso = $('input[name="peso"]').val();
    var altura = $('input[name="altura"]').val();
    $("input[name='imc']").val(window.calculate_imc(peso, altura));
    $("input[name='imc_disabled']").val(window.calculate_imc(peso, altura));
  });
});

/***/ }),

/***/ 52:
/*!************************************************************************!*\
  !*** multi ./resources/js/empleados/consultas/nutricionales/create.js ***!
  \************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\laragon\www\ejornal_laravel\resources\js\empleados\consultas\nutricionales\create.js */"./resources/js/empleados/consultas/nutricionales/create.js");


/***/ })

/******/ });