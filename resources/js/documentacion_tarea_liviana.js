$(document).ready(() => {

    $('.matricula_tilde_liviana').css('display', 'none');
    $('.matricula_cruz_liviana').css('display', 'none');
    $('.matricula_validada_liviana_hidden').val(0);
  
    $( "#submit_crear_documentacion_tarea_liviana" ).click(function() {
  
      if ($('.matricula_validada_liviana_hidden').val() == 1) {
        $('#form_crear_evento_tareas_livianas').submit();
      }else {
        alert('La matricula no fue validada');
      }
    });
  
    $( "#validar_matricula_liviana" ).click(function(e) {
      validarMatriculaLiviana();
    });
  
    function validarMatriculaLiviana(){
        $('.matricula_tilde_liviana').css('display', 'block');
        $('.matricula_validada_liviana_hidden').val(1);
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
        //     $('._liviana').css('display', 'none');
        //   })
        //   .catch(function (error) {
        //     $('.matricula_cruz').css('display', 'block');
        //     $('.matricula_tilde').css('display', 'none');
        //     $('.matricula_validada_hidden').val(0);
        //     console.log(error);
        //   });
  
        };
  
  
  
  });
  