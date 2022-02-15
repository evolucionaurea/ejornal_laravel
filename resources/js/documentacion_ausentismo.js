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

      $('.matricula_tilde').css('display', 'block');
      $('.matricula_cruz').css('display', 'none');
      $('.matricula_validada_hidden').val(1);

  }




});
