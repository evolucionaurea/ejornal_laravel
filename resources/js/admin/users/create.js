$(() => {
  const fields = [
    { roles: [1], class: '.select_permiso_edicion_fichadas' },
    { roles: [2], class: '.mostrar_personal_interno' },
    { roles: [2], class: '.mostrar_clientes' },
    { roles: [2], class: '.mostrar_permiso_desplegables' },
    { roles: [2], class: '.mostrar_especialidades' },
    { roles: [2], class: '.mostrar_cuil' },
    { roles: [2], class: '.mostrar_calle' },
    { roles: [2], class: '.mostrar_nro' },
    { roles: [2], class: '.mostrar_entre_calles' },
    { roles: [2], class: '.mostrar_localidad' },
    { roles: [2], class: '.mostrar_partido' },
    { roles: [2], class: '.mostrar_cod_postal' },
    { roles: [2], class: '.mostrar_permitir_fichada' },
    { roles: [2], class: '.mostrar_observaciones' },
    { roles: [2], class: '.select_contratacion_users' },
    { roles: [2], class: '.liquidacion_onedrive_creacion_users' },
    { roles: [3], class: '.cliente_original' },
    { roles: [4], class: '.grupos' }
  ];

  function roleId() {
    return parseInt(($('[name="rol"]').val() || '0'), 10);
  }

  function especialidadId() {
    return parseInt(($('[name="especialidad"]').val() || '0'), 10);
  }

  function isEmpleadoMedico() {
    return roleId() === 2 && especialidadId() === 1;
  }

  function mostrar_ocultar_campos(rid) {
    rid = parseInt(rid, 10);

    fields.forEach(field => {
      if (!field.roles.includes(rid)) $(field.class).addClass('d-none');
      else $(field.class).removeClass('d-none');
    });

    if (rid === 2) $('.mostrar_clientes label').text('¿Para quien trabajará?');
    if (rid === 3) $('.mostrar_clientes label').text('¿Este usuario a que Cliente pertenece?');

    // SOLO empleado médico: mostrar sección docs
    $('.mostrar_docs_medico').toggleClass('d-none', !isEmpleadoMedico());
  }

  // Inicial
  mostrar_ocultar_campos($('[name="rol"]').val());

  // Cambios
  $('[name="rol"]').on('change', e => {
    mostrar_ocultar_campos($(e.currentTarget).val());
  });

  $(document).on('change', '[name="especialidad"]', () => {
    mostrar_ocultar_campos($('[name="rol"]').val());
  });

  // select2 clientes
  if ($('#cliente_select_multiple').length) {
    $('#cliente_select_multiple').select2({ placeholder: 'Buscar...' }).trigger('change');
  }

  // custom-file label
  $(document).on('change', '.custom-file-input', function () {
    const fileName = (this.files && this.files.length) ? this.files[0].name : 'Seleccionar archivo';
    $(this).siblings('.custom-file-label').text(fileName);
  });

  // ===== límite uploads (10MB por archivo, 10MB total) =====
  const MAX_FILE_BYTES  = 10 * 1024 * 1024;
  const MAX_TOTAL_BYTES = 10 * 1024 * 1024;

  function mb(bytes) { return (bytes / 1024 / 1024).toFixed(2); }

  function showWarn(msg) {
    if (window.Swal && Swal.fire) {
      Swal.fire({ icon: 'warning', title: 'Carga inválida', text: msg });
    } else {
      alert(msg);
    }
  }

  function validateFiles($form) {
    let total = 0;
    let tooBigFile = null;

    $form.find('input[type="file"]').each(function () {
      const files = this.files;
      if (!files || !files.length) return;

      for (let i = 0; i < files.length; i++) {
        const f = files[i];
        total += f.size;
        if (f.size > MAX_FILE_BYTES) { tooBigFile = f; return false; }
      }
      if (tooBigFile) return false;
    });

    if (tooBigFile) {
      showWarn(`"${tooBigFile.name}" supera ${mb(MAX_FILE_BYTES)}MB. Elegí un archivo más liviano.`);
      return false;
    }

    if (total > MAX_TOTAL_BYTES) {
      showWarn(`La carga total es ${mb(total)}MB y supera el máximo permitido (${mb(MAX_TOTAL_BYTES)}MB). Subí menos archivos por vez.`);
      return false;
    }

    return true;
  }

  // Intercept submit en create y edit
  $('#form_create_user_por_admin, #form_edit_user_por_admin').on('submit', function (e) {
    if (!$('.mostrar_docs_medico').length) return;
    if ($('.mostrar_docs_medico').hasClass('d-none')) return;

    const ok = validateFiles($(this));
    if (!ok) {
      e.preventDefault();
      e.stopImmediatePropagation();
      return false;
    }
  });

  // confirmación existente (solo edit)
  $("#admin_edit_user").click(function (e) {
    e.preventDefault();

    let rol = $('[name="rol"]').val();
    if (rol == 2) {
      let fichada = $('#validacion_submit').data('fichada');
      let usuario_debe_fichar = $('[name="fichar"]').val();

      if (fichada == 1 && usuario_debe_fichar == 0) {
        Swal.fire({
          icon: 'warning',
          title: 'El usuario tiene la fichada activa. Si continúa, ficharemos la salida.',
          showCancelButton: true,
          reverseButtons: true,
          cancelButtonText: '<i class="fa fa-times fa-fw"></i> Cancelar',
          confirmButtonText: '<i class="fa fa-check fa-fw"></i> Aceptar'
        }).then((result) => {
          if (result.isConfirmed) {
            $('#form_edit_user_por_admin').submit();
          }
        });
      } else {
        $('#form_edit_user_por_admin').submit();
      }
    } else {
      $('#form_edit_user_por_admin').submit();
    }
  });
});