$(document).ready(() => {

  $('.dropdownContent').css('display', 'block');

    //Botón de acción del acordeón
  	$('.dropdownButton').click(function() {
  		//Elimina la clase on de todos los botones
  		$('.dropdownButton').removeClass('on');
  		//Plegamos todo el contenido que esta abierto
  	 	$('.dropdownContent').slideUp('slow');
  		//Si el siguiente slide no esta abierto lo abrimos
  		if($(this).next().is(':hidden') == true) {
  			//Añade la clase on en el botón
  			$(this).addClass('on');
        $(this).next().find('ul .activo_sub a').css('color', '#00a7a4');
  			//Abre el slide
  			$(this).next().slideDown('slow');
  		 }
  	 });
  	// Cerramos todo el contenido al cargar la página
  	$('.dropdownContent').hide();

});
