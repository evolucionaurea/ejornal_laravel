window.Swal = require('sweetalert2');
window.SwalWarning = window.Swal.mixin({
	icon:'warning',
	showCancelButton:true,
	reverseButtons:true,
	cancelButtonText:'<i class="fa fa-times fa-fw"></i> Cancelar',
	confirmButtonText:'<i class="fa fa-check fa-fw"></i> Aceptar'
});
$('body').on('click','[data-swal]',btn=>{
	let text = $(btn.currentTarget).attr('data-swal');
	Swal.fire({
		icon:'info',
		title:text
	});
});