$(document).ready(() => {

  $('.matricula_tilde').css('display', 'none');
  $('.matricula_cruz').css('display', 'none');
  $('.matricula_validada_hidden').val(0);

  $( "#submit_crear_documentacion_ausentismo" ).click(function() {

    if ($('.matricula_validada_hidden').val() == 1) {
      $('#form_crear_evento_ausentismo').submit();
    }else {
      alert('La matricula no fue validada');
    }
  });

  $( "#validar_matricula" ).click(function(e) {
    validarMatricula();
  });

  function validarMatricula(){
      $('.matricula_tilde').css('display', 'block');
      $('.matricula_validada_hidden').val(1);
      // let matricula_nacional = $('.nro_matricula_nacional').val();
      // let url = '/empleados/documentaciones/validarMatricula';
      // let usuario = 'jrpichot';
      // let clave = 'JavierPichot00';
      // let nombre = 'Juan';
      // let apellido = 'Perez';
      // let codigo = '025158';
      // let nrodoc = '32105897';

      // axios.post(url, {
      //   usuario: usuario,
      //   clave: clave,
      //   nombre: nombre,
      //   apellido: apellido,
      //   codigo: codigo,
      //   nrodoc: nrodoc,
      //   matricula: matricula_nacional
      //   })
      //   .then(function (response) {
      //     console.log(response);
      //     $('.matricula_validada_hidden').val(1);
      //     $('.matricula_tilde').css('display', 'block');
      //     $('.matricula_cruz').css('display', 'none');
      //   })
      //   .catch(function (error) {
      //     $('.matricula_cruz').css('display', 'block');
      //     $('.matricula_tilde').css('display', 'none');
      //     $('.matricula_validada_hidden').val(0);
      //     console.log(error);
      //   });

      };



});
