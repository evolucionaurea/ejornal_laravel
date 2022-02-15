$(document).ready(() => {


    $(".medicamentos_cant_pedida").keyup(function(event){
      console.log('valor de cantidad' + this.value);
  		$(".medicamentos_stock").val(this.value);
      console.log('valor de stock' + $(".medicamentos_stock").val());
  	});


});
