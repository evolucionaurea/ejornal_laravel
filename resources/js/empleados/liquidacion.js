import Swal from 'sweetalert2'

$(()=>{
	console.log('liquidacion...')

	Swal.fire({
		icon:'success',
		title:'Mensaje Sweet Alert 2',
		html:'Este es un mensaje de prueba'
	})
})