$(document).ready(function(){

  $(window).scroll(function(){
  	let scroll = $(window).scrollTop();
	  if (scroll > 100) {
	    $(".barra_sup_home").css("background-color", "#295d75");
	  }else{
		  $(".barra_sup_home").css("background-color", "transparent");
	  }

  });

});
