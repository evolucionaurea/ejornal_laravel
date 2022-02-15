$(document).ready(() => {

    $( ".editar_stock_medicamentos" ).click(function() {
      let id = $(this).attr('data-info');

      $('.form_editar_stock_medicamentos').attr('action', 'medicamentos/'+id);
      $('#editar_stock_medicamentos').modal('show');
    });


});
