$(()=>{

    console.log('entre');
    setTimeout(() => {
        $( ".extension_de_licencia" ).on( "click", function() {
            console.log( $( this ).attr("data-info") );
            let id_ausentismo = $( this ).attr("data-info");
            $('#form_crear_evento_ausentismo input[name="id_ausentismo"]').val(id_ausentismo);
        });
    }, 1000);

})