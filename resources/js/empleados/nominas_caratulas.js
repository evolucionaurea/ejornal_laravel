$(()=>{

	let dominio = window.location.host;
	let imc;

	$(".form-row .form-group input[name='peso']").keyup(function() {
		let peso = $(this).val();
		let altura = $(".form-row .form-group input[name='altura']").val();
		if (peso != '' && peso != null && peso != undefined && altura != '' && altura != null && altura != undefined && altura !== 0 || peso !== 0) {
		  imc = parseFloat(peso / (Math.pow((altura/100), 2))).toFixed(2);
			$(".form-row .form-group input[name='imc']").val(imc);
			$(".form-row .form-group input[name='imc_disabled']").val(imc);
		} else {
			$(".form-row .form-group input[name='imc']").val("");
		}
		if ($(".form-row .form-group input[name='imc']").val() == NaN) {
			$(".form-row .form-group input[name='imc']").val("");
		}
		if ($(".form-row .form-group input[name='imc']").val() == Infinity) {
			$(".form-row .form-group input[name='imc']").val("");
		}
	});


	$(".form-row .form-group input[name='altura']").keyup(function() {
		let altura = $(this).val();
		let peso = $(".form-row .form-group input[name='peso']").val();
		if (altura != '' && altura != null && altura != undefined && peso != '' && peso != null && peso != undefined && altura !== 0 || peso !== 0) {
		  imc = parseFloat(peso / (Math.pow((altura/100), 2))).toFixed(2);
			$(".form-row .form-group input[name='imc']").val(imc);
			$(".form-row .form-group input[name='imc_disabled']").val(imc);
		} else {
			$(".form-row .form-group input[name='imc']").val("");
		}
		if ($(".form-row .form-group input[name='imc']").val() == NaN) {
			$(".form-row .form-group input[name='imc']").val("");
		}
		if ($(".form-row .form-group input[name='imc']").val() == Infinity) {
			$(".form-row .form-group input[name='imc']").val("");
		}
	});


	$('.select_2').select2({
		placeholder: "Seleccione una o más patologías",
		allowClear: true
	});
	

})