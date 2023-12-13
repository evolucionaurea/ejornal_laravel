$(()=>{

    setTimeout(() => {
        $( ".extension_de_licencia_adecuada" ).on( "click", function() {
            let id_tarea_liviana = $( this ).attr("data-info");
            $('#form_crear_evento_tarea_liviana input[name="id_tarea_liviana"]').val(id_tarea_liviana);
        });
    }, 500);

})