$(document).ready(() => {

  let fecha = new Date();
  $('.fichada_fecha_actual').text(fecha.getDate() + "/" + (fecha.getMonth() +1) + "/" + fecha.getFullYear());

  $( ".click_fichada_huella" ).click(function() {

    axios.get('horario_ultima_fichada')
    .then(response => {
      $('.estado_trabajando_desde').text(response.data[0].ingreso);
    });


    mostrarReloj();
  });

  let today = new Date();

  function mostrarReloj()
  {

  clock = new Date();
  hour =   clock.getHours();
  minutes = clock.getMinutes();
  seconds = clock.getSeconds();

  print_clock = hour + ":" + minutes + ":" + seconds;

  $('.reloj_hora_actual').text(print_clock);
  setTimeout(mostrarReloj, 1000);

  }
  setTimeout(mostrarReloj, 1000);


});
