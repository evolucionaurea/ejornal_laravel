$(()=>{

    setTimeout(() => {
        $( ".extension_de_licencia_adecuada" ).on( "click", function() {
            console.log( $( this ).attr("data-info") );
            let id_tarea_liviana = $( this ).attr("data-info");
            $('#form_crear_evento_tareas_livianas input[name="id_tarea_liviana"]').val(id_tarea_liviana);
        });
    }, 1000);

})