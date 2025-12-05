$(document).ready(() => {

	let fecha = new Date();
	$('.fichada_fecha_actual').text(`${fecha.getDate()} / ${fecha.getMonth() + 1} / ${fecha.getFullYear()}`);

	$(".click_fichada_huella").on('click', async btn => {
		const response = await axios.get('horario_ultima_fichada')
		$('.estado_trabajando_desde').text(response.data[0].ingreso)
		mostrarReloj()
	})

	function mostrarReloj() {

		const clock = new Date();
		const hour = clock.getHours();
		const minutes = clock.getMinutes();
		const seconds = clock.getSeconds();

		const print_clock = `${hour}:${minutes}:${seconds}`;

		$('.reloj_hora_actual').text(print_clock);
		setTimeout(mostrarReloj, 1000);

	}
	setTimeout(mostrarReloj, 1000);


});
