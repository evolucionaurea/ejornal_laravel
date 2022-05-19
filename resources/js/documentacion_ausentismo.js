$(document).ready(() => {

  $('.matricula_tilde').css('display', 'none');
  $('.matricula_cruz').css('display', 'block');

  $( "#submit_crear_documentacion_ausentismo" ).click(function(e) {
    e.preventDefault();

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
      let matricula_nacional = $('.nro_matricula_nacional').val();
      let url = 'https://sisa.msal.gov.ar/sisa/services/rest/profesional/obtener';
      let nombre = '';
      let apellido = '';
      let codigo = '';
      let nrodoc = '';


      return console.log(matricula_nacional)


      axios.post(url, {
        usuario: 'jrpichot',
        clave: 'JavierPichot00',
        apellido: 'Perez',
        nombre: 'Juan',
        codigo: '025158',
        nrodoc: '32105897'
        })
        .then(function (response) {
          console.log(response);
        })
        .catch(function (error) {
          console.log(error);
        });

      // $('.matricula_tilde').css('display', 'block');
      // $('.matricula_cruz').css('display', 'none');
      // $('.matricula_validada_hidden').val(1);

  }




});
