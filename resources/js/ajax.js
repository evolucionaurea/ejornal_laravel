$(document).ready(() => {


//// Documentacion ///
    $( ".editar_documentos_ausentismo" ).click(function(e) {
      e.preventDefault();
      let id_doc = $(this).data("id");

      axios.get('getDocumentacion/'+id_doc)
      .then(res => {
        $("#form_editar_documentacion_ausencia input[name='id_doc']").val(res.data.id);
        $("#form_editar_documentacion_ausencia input[name='institucion']").val(res.data.institucion);
        $("#form_editar_documentacion_ausencia input[name='medico']").val(res.data.medico);
        $("#form_editar_documentacion_ausencia input[name='matricula_provincial']").val(res.data.matricula_provincial);
        $("#form_editar_documentacion_ausencia input[name='matricula_nacional']").val(res.data.matricula_nacional);
        $("#form_editar_documentacion_ausencia input[name='fecha_documento']").val(res.data.fecha_documento);
        $("#form_editar_documentacion_ausencia textarea[name='diagnostico']").text(res.data.diagnostico);
        $("#form_editar_documentacion_ausencia textarea[name='observaciones']").text(res.data.observaciones);
        $("#form_editar_documentacion_ausencia archivo_edit_doc_ausencia[attr='href']").text('empleados/documentacion_ausentismo/archivo/'+res.data.id);
      })
      .catch((err) => {
        // console.log(err);
      })


      $('#form_editar_documentacion_ausencia').attr('action', "{{action('EmpleadosAusentismoDocumentacionController@update', "+id_doc);
      $('#modal_edit_docu_ausentismo').modal('show');
    });
//// Documentacion ///





//// Comunicaciones ///
    $( ".editar_comunicacion_ausentismo" ).click(function(e) {
      e.preventDefault();
      let id_comunicacion = $(this).data("id");

      axios.get('getComunicacion/'+id_comunicacion)
      .then(res => {
        $("#form_editar_comunicacion_ausencia input[name='id_comunicacion']").val(res.data.id);
        $("#form_editar_comunicacion_ausencia input[name='id_tipo']").val(res.data.id_tipo);
        $("#form_editar_comunicacion_ausencia input[name='descripcion']").val(res.data.descripcion);
      })
      .catch((err) => {
        // console.log(err);
      })


      $('#form_editar_comunicacion_ausencia').attr('action', "{{action('EmpleadosComunicacionesController@update', "+id_comunicacion);
      $('#modal_edit_comunicacion_ausentismo').modal('show');
    });
//// Comunicaciones ///


});
