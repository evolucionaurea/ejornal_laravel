$(() => {
  // límites
  const MAX_FILE_BYTES = 2 * 1024 * 1024;   // 2MB por archivo
  const MAX_TOTAL_BYTES = 7 * 1024 * 1024;  // 7MB total (seguro < 8MB con multipart)

  const $form = $('#form-documentacion');
  const $alert = $('#upload-alert');
  const $alertText = $('#upload-alert-text');

  if (!$form.length) return;

  function mb(bytes) {
    return (bytes / 1024 / 1024).toFixed(2);
  }

  function showUploadError(msg) {
    if ($alert.length) {
      $alertText.text(msg);
      $alert.removeClass('d-none');
    } else {
      alert(msg);
    }
  }

  function hideUploadError() {
    if ($alert.length) $alert.addClass('d-none');
  }

  function calcFiles() {
    let total = 0;
    let tooBig = null;

    $form.find('input[type="file"]').each(function () {
      const files = this.files;
      if (!files || !files.length) return;

      for (let i = 0; i < files.length; i++) {
        const f = files[i];
        total += f.size;

        if (f.size > MAX_FILE_BYTES) {
          tooBig = { input: this, file: f };
          return false; 
        }
      }

      if (tooBig) return false; 
    });

    return { total, tooBig };
  }

  function validateAllFiles() {
    const { total, tooBig } = calcFiles();

    if (tooBig) {
      const name = tooBig.file ? tooBig.file.name : 'archivo';
      // limpiar ese input para que no intente enviar
      tooBig.input.value = '';
      // reset label visual
      $(tooBig.input).siblings('.custom-file-label').text('Seleccionar archivo');

      showUploadError(`"${name}" supera 2MB. Elegí un archivo más liviano.`);
      return false;
    }

    if (total > MAX_TOTAL_BYTES) {
      showUploadError(
        `La carga total seleccionada es ${mb(total)}MB y supera el máximo permitido (${mb(MAX_TOTAL_BYTES)}MB). ` +
        `Guardá en más de una vez (subí menos archivos por vez).`
      );
      return false;
    }

    hideUploadError();
    return true;
  }

  // Label + validación al cambiar archivos
  $(document).on('change', '.custom-file-input', function () {
    const fileName = (this.files && this.files.length)
      ? this.files[0].name
      : 'Seleccionar archivo';

    $(this).siblings('.custom-file-label').text(fileName);

    validateAllFiles();
  });

  // Submit: si pasa validación, mostramos feedback visual
  $form.on('submit', function (e) {
    if (!validateAllFiles()) {
      e.preventDefault();
      e.stopImmediatePropagation();
      return false;
    }

    // feedback visible: “Guardando...”
    const $btn = $form.find('button[type="submit"]');
    $btn.prop('disabled', true);
    $btn.data('old-text', $btn.html());
    $btn.html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando…');
  });
});