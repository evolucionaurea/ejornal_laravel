<script type="text/javascript">
    window.onload = function() {

        /////////////////////// LOGICA CERTIFICACIONES /////////////////////////////
        let certificaciones = [];
        var route = '';
        let user_ausen_docu = '';

        function poblarTablasCertificaciones() {
            for (let i = 0; i < certificaciones.length; i++) {
                $(".resultados_reporte_certificaciones").append(
                    $('<tr>', {
                        'class': ''
                    }).append(
                        $('<td>', {
                            'text': certificaciones[i].cliente
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': certificaciones[i].trabajador
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': certificaciones[i].user
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': certificaciones[i].tipo
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': certificaciones[i].fecha_inicio
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': certificaciones[i].fecha_final
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': certificaciones[i].dias_ausente
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': ''
                        })
                        .append(
                            (certificaciones[i].documentaciones.length > 0) ?

                            certificaciones[i].documentaciones.map(function(obj){
                              route = '{{ route("documentacion_ausentismo.descargar", ":id") }}';
                              route = route.replace(':id', certificaciones[i].documentaciones[0].id);
                              user_ausen_docu = (obj.user) ? obj.user : 'No registrado';
                              return(
                                $('<div>', {
                                  'class': 'list-group'
                                })
                                .append(
                                  $('<p>', {
                                    'text': 'Institución: ' + obj.institucion + ' - ' +  'Matrícula nacional: ' + obj.matricula_nacional +
                                     ' - ' + 'Matrícula Provincial: ' +  obj.matricula_provincial + ' - ' + 'Fecha documento: ' +
                                     obj.fecha_documento + ' - ' + 'Medico: ' + obj.medico + ' - ' + 'User que registra: ' + user_ausen_docu
                                  })
                                )
                                .append(
                                  $('<a>', {
                                    'text': obj.archivo,
                                    'class': 'btn-ejornal btn-ejornal-gris-claro btn_sm',
                                    'target': '_blank',
                                    'href': route
                                  })
                                )
                              )
                            })
                            :
                            'No tiene'
                        )
                    )
                )
            }
        }

        async function getCertificaciones() {
            await fetch('/admin/reportes/certificaciones')
                .then(respuesta => respuesta.json())
                .then(
                    (data) => {
                        certificaciones = data
                        poblarTablasCertificaciones();
                        $('.tabla_reporte_certificaciones').DataTable().destroy();

                        $('.tabla_reporte_certificaciones').DataTable({
                            scrollY: 400,
                            dom: 'Bfrtip',
                            pageLength:10,
                        		lengthMenu:[10,25,50,100,250,500],
                            order: [[ 5, "desc" ]],
                            buttons: [{
                                    extend: 'copy',
                                    text: 'Copiar',
                                },
                                {
                                    extend: 'excel',
                                    title: 'eJornal',
                                },
                                {
                                    extend: 'pdf',
                                    text: 'PDF',
                                    title: 'eJornal',
                                },
                                {
                                    extend: 'print',
                                    text: 'Imprimir',
                                    title: 'eJornal',
                                }
                            ],
                            responsive: true,
                            "language": {
                                "sProcessing": "Procesando...",
                                "sLengthMenu": "Mostrar _MENU_ registros",
                                "sZeroRecords": "No se encontraron resultados",
                                "sEmptyTable": "Ningún dato disponible en esta tabla",
                                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                "sInfoPostFix": "",
                                "sSearch": "Buscar:",
                                "sUrl": "",
                                "sInfoThousands": ",",
                                "sLoadingRecords": "Cargando...",
                                "oPaginate": {
                                    "sFirst": "Primero",
                                    "sLast": "Último",
                                    "sNext": "Siguiente",
                                    "sPrevious": "Anterior"
                                },
                                "oAria": {
                                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                                }
                            }

                        });

                    });
        }

        // Al cargar la pagina llamamos a todas las certificaciones
        getCertificaciones();

        $("#reporte_certificacion_todo").click(function() {

            fetch('/admin/reportes/certificaciones')
                .then(response => response.json())
                .then(
                    (resultado) => {
                        certificaciones = resultado

                        $('.tabla_reporte_certificaciones').DataTable().destroy();
                        $('.resultados_reporte_certificaciones tr').remove();
                        poblarTablasCertificaciones();

                        $('.tabla_reporte_certificaciones').DataTable({
                            scrollY: 400,
                            dom: 'Bfrtip',
                            pageLength:10,
                        		lengthMenu:[10,25,50,100,250,500],
                            order: [[ 5, "desc" ]],
                            buttons: [{
                                    extend: 'copy',
                                    text: 'Copiar',
                                },
                                {
                                    extend: 'excel',
                                    title: 'eJornal',
                                },
                                {
                                    extend: 'pdf',
                                    text: 'PDF',
                                    title: 'eJornal',
                                },
                                {
                                    extend: 'print',
                                    text: 'Imprimir',
                                    title: 'eJornal',
                                }
                            ],
                            responsive: true,
                            "language": {
                                "sProcessing": "Procesando...",
                                "sLengthMenu": "Mostrar _MENU_ registros",
                                "sZeroRecords": "No se encontraron resultados",
                                "sEmptyTable": "Ningún dato disponible en esta tabla",
                                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                "sInfoPostFix": "",
                                "sSearch": "Buscar:",
                                "sUrl": "",
                                "sInfoThousands": ",",
                                "sLoadingRecords": "Cargando...",
                                "oPaginate": {
                                    "sFirst": "Primero",
                                    "sLast": "Último",
                                    "sNext": "Siguiente",
                                    "sPrevious": "Anterior"
                                },
                                "oAria": {
                                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                                }
                            }

                        });

                    });

        });

        $("#reporte_certificacion_filtro").click(function() {
            let registro_desde_certi = $('#reporte_certificaciones_desde').val();
            let certifi_desde_partes = registro_desde_certi.split("/");
            let certificaciones_desde = new Date(+certifi_desde_partes[2], certifi_desde_partes[1] - 1, +certifi_desde_partes[0]);

            let registro_hasta_ausen = $('#reporte_certificaciones_hasta').val();
            let certifi_hasta_partes = registro_hasta_ausen.split("/");
            let certificaciones_hasta = new Date(+certifi_hasta_partes[2], certifi_hasta_partes[1] - 1, +certifi_hasta_partes[0]);

            $('#modalReportes').modal('show');

            axios.post('/admin/reportes/filtrar_certificaciones', {
                certificaciones_desde: certificaciones_desde,
                certificaciones_hasta: certificaciones_hasta
            }).then(resp => {

                setTimeout(function() {
                    $('#modalReportes').modal('hide');
                }, 1000);

                certificaciones = resp.data;

                $('.tabla_reporte_certificaciones').DataTable().destroy();
                $('.resultados_reporte_certificaciones tr').remove();

                poblarTablasCertificaciones();

                $('.tabla_reporte_certificaciones').DataTable({
                    scrollY: 400,
                    dom: 'Bfrtip',
                    pageLength:10,
                    lengthMenu:[10,25,50,100,250,500],
                    order: [[ 5, "desc" ]],
                    buttons: [{
                            extend: 'copy',
                            text: 'Copiar',
                        },
                        {
                            extend: 'excel',
                            title: 'eJornal',
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF',
                            title: 'eJornal',
                        },
                        {
                            extend: 'print',
                            text: 'Imprimir',
                            title: 'eJornal',
                        }
                    ],
                    responsive: true,
                    "language": {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }

                });


            }).catch(e => {
                console.log(e);
            });

        });


        /////////////////////// LOGICA CERTIFICACIONES /////////////////////////////




    };
</script>
