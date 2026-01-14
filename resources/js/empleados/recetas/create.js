(function ($, window, document) {
  'use strict';

  const AJAX_TIMEOUT_MS = 20000;

  let modalCss = false;
  function modalCSS() {
    if (modalCss) return; modalCss = true;
    const css = `
      .mm-back{position:fixed;inset:0;background:rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;z-index:9999}
      .mm{background:#fff;border-radius:12px;box-shadow:0 15px 50px rgba(0,0,0,.25);max-width:640px;width:94%;padding:18px 20px}
      .mm h3{margin:0 0 10px;font-size:18px;font-weight:800;color:#111}
      .mm p{margin:0 0 10px;font-size:14px;color:#333}
      .mm .row{display:flex;justify-content:flex-end;gap:8px;margin-top:14px}
      .mm .btn{border:0;border-radius:8px;padding:9px 14px;font-size:14px;cursor:pointer}
      .mm .b1{background:#2563eb;color:#fff}
      .mm ul.mm-list{list-style:none;margin:6px 0 0;padding:0}
      .mm ul.mm-list li{display:flex;align-items:flex-start;gap:10px; padding:8px 10px; border-radius:8px; background:#f8fafc; margin-bottom:8px; font-size:14px; color:#0f172a}
      .mm ul.mm-list li .dot{width:8px;height:8px;border-radius:999px;background:#2563eb; margin-top:6px; flex:0 0 8px}
      @media (prefers-color-scheme: dark){
        .mm{background:#0f172a;color:#e2e8f0}
        .mm h3{color:#e5e7eb}
        .mm p{color:#cbd5e1}
        .mm ul.mm-list li{background:#0b1221;color:#e2e8f0}
        .mm ul.mm-list li .dot{background:#60a5fa}
      }`;
    document.head.appendChild(Object.assign(document.createElement('style'), { textContent: css }));
  }
  function modal({ title='Atención', html='<p>Ocurrió un error.</p>' } = {}) {
    modalCSS();
    const $b = $('<div class="mm-back" role="dialog" aria-modal="true"></div>');
    const $m = $(`<div class="mm"><h3>${title}</h3>${html}<div class="row"><button class="btn b1">Aceptar</button></div></div>`);
    $b.append($m).appendTo(document.body);
    const close = () => $b.remove();
    $b.on('click', e => { if (e.target === $b[0]) close(); });
    $m.find('.b1').on('click', close);
  }
  const esc = s => String(s ?? '').replace(/[&<>"]/g, m => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;' }[m]));
  const errList = (items=[]) => `<ul class="mm-list">${items.map(it=>`<li><span class="dot"></span><div>${esc(it)}</div></li>`).join('')}</ul>`;

  // ===== Spinner pequeño en labels =====
  let spCSS=false;
  function ensureSpinner(){
    if (spCSS) return; spCSS=true;
    const css=`@keyframes miniSpin{to{transform:rotate(360deg)}}.msw{display:inline-block;margin-left:6px;vertical-align:middle}.ms{width:16px;height:16px;animation:miniSpin .9s linear infinite}.ms circle{stroke-linecap:round}`;
    document.head.appendChild(Object.assign(document.createElement('style'), { textContent: css }));
  }
  function spinnerSvg(){
    ensureSpinner();
    return '<span class="msw"><svg class="ms" viewBox="0 0 50 50"><defs><linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="#2196f3"/><stop offset="100%" stop-color="#81d4fa"/></linearGradient></defs><circle cx="25" cy="25" r="20" stroke="url(#g)" fill="none" stroke-width="5"/></svg></span>';
  }
  function labelLoader($sel, on){
    const $l=$sel.closest('.form-group').find('label').first();
    if(!$l.length) return;
    on ? (!$l.find('.msw').length && $l.append(spinnerSvg())) : $l.find('.msw').remove();
  }


// ===== Loader sobre el formulario (solo para cuando mandamos id_nomina en url) =====
let formLoaderReady = false;

function ensureFormLoaderCSS(){
  if (formLoaderReady) return;
  formLoaderReady = true;

  const css = `
    .fl-back{
      position:absolute; inset:0;
      background: rgba(255,255,255,.75);
      display:flex; align-items:center; justify-content:center;
      z-index: 9998;
      border-radius: 12px;
    }
    @media (prefers-color-scheme: dark){
      .fl-back{ background: rgba(15,23,42,.72); }
    }
    .fl-box{
      display:flex; align-items:center; gap:10px;
      padding:10px 14px;
      border-radius: 12px;
      background: rgba(255,255,255,.95);
      box-shadow: 0 10px 30px rgba(0,0,0,.18);
      font-size: 14px;
      color:#0f172a;
    }
    @media (prefers-color-scheme: dark){
      .fl-box{ background: rgba(2,6,23,.92); color:#e2e8f0; }
    }
    .fl-spin{
      width:18px; height:18px;
      border-radius:999px;
      border: 3px solid rgba(0,0,0,.12);
      border-top-color: rgba(0,0,0,.55);
      animation: flrot .9s linear infinite;
    }
    @media (prefers-color-scheme: dark){
      .fl-spin{
        border-color: rgba(255,255,255,.15);
        border-top-color: rgba(255,255,255,.65);
      }
    }
    @keyframes flrot{to{transform:rotate(360deg)}}
  `;
  document.head.appendChild(Object.assign(document.createElement('style'), { textContent: css }));
}

function showFormLoader(msg = 'Cargando datos del trabajador…'){
  ensureFormLoaderCSS();

  const $f = $('#recetaForm');
  if (!$f.length) return;

  // contenedor que cubrimos
  const $wrap = $f.closest('.tarjeta');
  const $host = $wrap.length ? $wrap : $f;

  // evitar duplicados
  if ($host.find('.fl-back').length) return;

  // asegurar posicionamiento
  if ($host.css('position') === 'static') $host.css('position', 'relative');

  const $ov = $(`
    <div class="fl-back" aria-live="polite" aria-busy="true">
      <div class="fl-box">
        <div class="fl-spin"></div>
        <div class="fl-msg">${msg}</div>
      </div>
    </div>
  `);

  $host.append($ov);
}

function hideFormLoader(){
  const $f = $('#recetaForm');
  if (!$f.length) return;

  const $wrap = $f.closest('.tarjeta');
  const $host = $wrap.length ? $wrap : $f;

  $host.find('.fl-back').remove();
}




  // ===== Helpers URL / fechas / select2 =====
  let pendingFechaISO = '';


  function rurl(u){ try{ if(!u) return '/'; if(/^https?:\/\//i.test(u)) return u; return new URL(u,location.origin).toString(); }catch{ return u; } }
  function urls(){
    const $f=$('#recetaForm');
    return {
      financiadores: rurl($f.data('url-get-financiadores')),
      diagnosticos : rurl($f.data('url-get-diagnosticos')),
      medicamentos : rurl($f.data('url-get-medicamentos')),
      practicas    : rurl($f.data('url-get-practicas')),
      provincias   : rurl($f.data('url-get-provincias'))
    };
  }
const isoAEs = iso => {
  if (!iso) return '';
  const s = String(iso).trim();
  const m = s.match(/^(\d{4})-(\d{2})-(\d{2})/); // acepta "YYYY-MM-DD ..." también
  return m ? `${m[3]}/${m[2]}/${m[1]}` : '';
};

  const esAISO = es => {
    if (!es) return '';
    const m = /^(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})$/.exec((es+'').trim());
    if (!m) return '';
    const dd=('0'+m[1]).slice(-2), mm=('0'+m[2]).slice(-2);
    return `${m[3]}-${mm}-${dd}`;
  };
  function normalizeISO(input){
  if (!input) return '';
  const s = String(input).trim();
  const m = s.match(/^(\d{4}-\d{2}-\d{2})/);
  return m ? m[1] : '';
}

  function s2Small($sel){
    const $w=$sel.next('.select2'), $s=$w.find('.select2-selection--single');
    $s.css({height:'31px','min-height':'31px','border-radius':'.2rem','border-color':'#ced4da',padding:'2px 8px'});
    $s.find('.select2-selection__rendered').css({'line-height':'26px','font-size':'.875rem'});
    $s.find('.select2-selection__arrow').css({height:'29px',right:'6px'});
  }
  function s2Ajax($sel, {url, minLen=2, dataFn, procFn, tpl, langNoResults}){
    $sel.select2({
      width:'100%',
      placeholder:'Buscar…',
      allowClear:false, // sin opción vacía
      minimumInputLength:minLen,
      ajax:{
        delay:300, url, dataType:'json', cache:true,
        data: p => (dataFn ? dataFn(p) : ({ q:p.term||'', page:p.page||1 })),
        processResults: res => (procFn ? procFn(res) : res),
        beforeSend: () => labelLoader($sel,true),
        complete:   () => labelLoader($sel,false),
        headers: { 'Accept':'application/json' },
        transport: function (params, success, failure) {
          const cfg = Object.assign({}, params, { timeout: AJAX_TIMEOUT_MS });
          return $.ajax(cfg).done(success).fail(failure);
        }
      },
      language:{ noResults: () => langNoResults || 'Sin resultados' },
      dropdownParent:$(document.body),
      templateResult: tpl
    });
    s2Small($sel);
  }

  // ===== Provincias por endpoint =====
  function fetchProvincias(){
    return $.ajax({
      url: urls().provincias, dataType:'json',
      headers:{'Accept':'application/json'}, timeout:AJAX_TIMEOUT_MS
    }).then(j => (Array.isArray(j?.results) ? j.results : [])).catch(()=>[]);
  }
  function toSelect($input, items, {name}={}){
    const $sel=$('<select class="form-control form-control-sm"></select>');
    if (name) $sel.attr('name', name);
    if ($input.attr('id')) $sel.attr('id', $input.attr('id'));
    items.forEach((p,i)=>{
      const v=p.id ? String(p.id) : (p.nombre||'');
      const t=p.nombre || v;
      const $o=$('<option>').val(v).text(t);
      if (i===0 && !$input.val()) $o.prop('selected', true);
      $sel.append($o);
    });
    $input.replaceWith($sel);
    return $sel;
  }

  // ===== Asteriscos dinámicos =====
  function setRequired($field, required){
    const $fg=$field.closest('.form-group');
    const $label=$fg.find('label').first();
    $field.prop('required', !!required);
    $label.find('.req').remove();
    if (required) $label.append(' <span class="req text-danger">*</span>');
  }

  // ===== Nómina → autocompletado de paciente =====
  function splitNombre(full){
    full=(full||'').trim().replace(/\s+/g,' ');
    if(!full) return {apellido:'',nombre:''};
    if (full.includes(',')){ const a=full.split(','); return {apellido:a[0].trim(), nombre:(a[1]||'').trim()}; }
    const i=full.indexOf(' '); return i===-1? {apellido:'',nombre:full}:{apellido:full.slice(0,i),nombre:full.slice(i+1)};
  }
  function setPac(d){
    const set=(n,v)=>$(`[name="${n}"]`).val(v??'');

    set('paciente[nombre]',d.nombre);
    set('paciente[apellido]',d.apellido);
    set('paciente[nroDoc]',d.dni && String(d.dni));
    set('paciente[email]',d.email);
    set('paciente[telefono]',d.telefono);

  // ===== Fecha de nacimiento (normalizada + fallback hoy si viene vacío) =====
  let iso = normalizeISO(d.fechaNacimiento) || esAISO(d.fechaNacimiento);

  if (!iso) {
    const t = new Date();
    const mm = ('0' + (t.getMonth() + 1)).slice(-2);
    const dd = ('0' + t.getDate()).slice(-2);
    iso = `${t.getFullYear()}-${mm}-${dd}`;
  }

  $('[name="paciente[fechaNacimiento]"]').val(iso);

  const $vis = $('#paciente_fecha_visual');
  if ($vis.length) {
    $vis.val(isoAEs(iso));
  } else {
    pendingFechaISO = iso;
  }



    // Domicilio
    set('domicilio[calle]',d.calle);
    set('domicilio[numero]',d.nro && String(d.nro));
    set('domicilio[localidad]',d.localidad);
    set('domicilio[codigoPostal]',d.cod_postal && String(d.cod_postal));

    // si está el loader, lo dejamos como ya lo tenías
    setTimeout(hideFormLoader, 50);
  }



function initNomina(){
  const $s = $('#id_nomina');
  if (!$s.length) return;

  const preset = String($s.data('preset') || '').trim();

  // 🔄 Si viene preset (redirect desde consulta médica), mostramos loader
  if (preset) {
    showFormLoader('Cargando datos del trabajador…');
  }

  // =========================
  // Ordenar opciones alfabéticamente (manteniendo la primera)
  // =========================
  const opts = $s.find('option').toArray();
  if (opts.length > 1) {
    const first = opts.shift();
    opts.sort((a, b) =>
      $(a).text().localeCompare($(b).text(), 'es', { sensitivity: 'base' })
    );
    $s.empty().append(first, opts);
  }

  // =========================
  // Inicializar Select2
  // =========================
  $s.select2({
    width: '100%',
    minimumInputLength: 0,
    dropdownParent: $(document.body)
  });
  s2Small($s);

  // =========================
  // Cambio de nómina → autocompletar paciente
  // =========================
const onCh = () => {
  const v = $s.val();
  if (!v) { setPac({}); hideFormLoader(); return; }

  const $o = $s.find('option:selected');

  let full = $o.attr('data-nombre') || $o.data('nombre') || ($o.text()||'')
    .replace(/\s+—\s+.*$/,'')
    .replace(/\(DNI:.*?\)/,'')
    .trim();

  const { apellido, nombre } = splitNombre(full);

  // ✅ leer del atributo, no confiar en .data()
  const fnac =
    ($o.attr('data-fecha-nacimiento') || '').trim() ||
    ($o.data('fechaNacimiento') || '').toString().trim() ||
    ($o.data('fecha-nacimiento') || '').toString().trim();

  setPac({
    nombre,
    apellido,
    dni: $o.attr('data-dni') || $o.data('dni'),
    email: $o.attr('data-email') || $o.data('email'),
    telefono: $o.attr('data-telefono') || $o.data('telefono'),
    fechaNacimiento: fnac, // <-- acá
    calle: $o.attr('data-calle') || $o.data('calle'),
    nro: $o.attr('data-nro') || $o.data('nro'),
    localidad: $o.attr('data-localidad') || $o.data('localidad'),
    cod_postal: $o.attr('data-cod-postal') || $o.data('cod-postal')
  });

  console.log(fnac);
  
};


  $s.on('change select2:select', onCh);

  // =========================
  // Caso 1: viene preset por querystring (?id_nomina=)
  // =========================
  if (preset && $s.find(`option[value="${preset}"]`).length) {
    // Forzamos valor, disparamos change y dejamos que onCh quite el loader
    $s.val(preset).trigger('change');
    $s.trigger('change.select2');
    return;
  }

  // =========================
  // Caso 2: NO viene preset → comportamiento actual
  // =========================
  if ($s.val()) {
    onCh();
  } else {
    // si no hay selección y había loader (caso raro), lo quitamos
    hideFormLoader();
  }
}



  // ===== Cobertura (Financiador + Plan) =====
function ensureCobInputs(){
  const $f = $('#recetaForm');
  ['cobertura[idFinanciador]','cobertura[planId]','cobertura[plan]'].forEach(n => {
    if (!$f.find(`input[name="${n}"]`).length) {
      $f.append(`<input type="hidden" name="${n}">`);
    }
  });
}


function initFinanciadores(){
  const $fin  = $('#financiador');
  const $plan = $('#plan');
  if (!$fin.length) return;

  ensureCobInputs();
  const u = urls();

  // Plan vacío al inicio
  $plan.select2({width:'100%',minimumInputLength:0,dropdownParent:$(document.body)});
  s2Small($plan);
  $plan.prop('disabled', true).trigger('change');

  // Financiadores (QBI2 GetFinanciadores)
  s2Ajax($fin, {
    url: u.financiadores,
    minLen: 3,
    dataFn: p => ({ q: p.term || '' }),
    procFn: res => ({ results: res.results || [] })
  });

  function rebuildPlans(list){
    try { $plan.select2('destroy'); } catch {}
    $plan.empty();

    const planes = (Array.isArray(list) ? list : []).reduce((acc,p) => {
      const id  = p.id ?? p.planId ?? p.planid;
      const nom = p.nombre ?? p.descripcion ?? p.name;
      if (id && nom) acc.push({ id, text: nom });
      return acc;
    }, []);

    planes.forEach(p => $plan.append(new Option(p.text, p.id)));

    $plan.prop('disabled', planes.length === 0);
    $plan.select2({width:'100%',minimumInputLength:0,dropdownParent:$(document.body)});
    s2Small($plan);

    if (planes.length){
      $plan.val(String(planes[0].id)).trigger('change');
    } else {
      $('[name="cobertura[planId]"]').val('');
      $('[name="cobertura[plan]"]').val('');
    }
  }

  function limpiarMedicamentos(){
    const $wrap = $('#medsWrapper');
    $wrap.find('.sel-medicamento').each(function(){ $(this).val(null).trigger('change'); });
    $wrap.find(
      '.regno,.presentacion,.nombre,.droga,' +
      'input[name*="[tratamiento]"],input[name*="[posologia]"],textarea[name*="[indicaciones]"]'
    ).val('');
    $wrap.find('.duplicado').prop('checked', false);
  }

  // Cuando el usuario elige financiador
  $fin.on('select2:select', function(){
    const sel = $fin.select2('data')[0];
    const raw = sel?.raw || {};

    // La doc usa idfinanciador como idFinanciador
    const finId = raw.idfinanciador ?? raw.nrofinanciador ?? raw.nroFinanciador ?? raw.id ?? '';

    $('[name="cobertura[idFinanciador]"]').val(String(finId).replace(/\D+/g,''));

    rebuildPlans(raw.planes || []);
    limpiarMedicamentos();
  });

  // Cuando el usuario elige plan
  $plan.on('select2:select', function(){
    const $o     = $plan.find('option:selected');
    const planId = String($o.val() || '').replace(/\D+/g,'');

    $('[name="cobertura[planId]"]').val(planId);
    $('[name="cobertura[plan]"]').val($o.text() || '');
    limpiarMedicamentos();
  });
}

  // ===== Diagnósticos =====
  function initDiagnosticos(){
    const $s=$('#diag_search'); if(!$s.length) return;
    s2Ajax($s,{ url:urls().diagnosticos, minLen:3, procFn:d=>d });
    $s.on('select2:select', ()=>{ const it=$s.select2('data')[0]; $('#diagnostico_codigo').val(it?.id||''); $('#diagnostico').val(it?.text||''); });
  }

  // ===== Medicamentos (ya filtrados por cobertura si existe) =====
  function coberturaParams(){
    const idFin=$('[name="cobertura[idFinanciador]"]').val() || $('#financiador').val();
    const planId=$('[name="cobertura[planId]"]').val() || $('#plan').val();
    const dni=$('[name="paciente[nroDoc]"]').val();
    const cred=$('[name="cobertura[credencial]"]').val();
    const q={}; if(idFin) q.idFinanciador=idFin; if(planId) q.planid=planId; if(dni) q.afiliadoDni=dni; if(cred) q.afiliadoCredencial=cred; return q;
  }
  function attachMed($row){
    const $sel=$row.find('.sel-medicamento'),
          $reg=$row.find('.regno'),
          $pre=$row.find('.presentacion'),
          $nom=$row.find('.nombre'),
          $dro=$row.find('.droga'),
          $dup=$row.find('.duplicado');

    // Reg. Nº solo lectura
    $reg.attr('readonly','readonly');
    if (!$row.find('.regno-help').length) $reg.after('<small class="text-muted regno-help">Se completa automáticamente al elegir un medicamento</small>');

    // Aviso visual sobre cobertura aplicada o no
    let $hint = $row.find('.med-hint');
    if (!$hint.length) {
      $hint = $('<div class="med-hint small mt-1 text-muted"></div>');
      $sel.closest('.form-group').append($hint);
    }
    function updateHint() {
      const p=coberturaParams();
      if (p.idFinanciador && p.planid) $hint.text('Resultados filtrados por tu cobertura/plan.');
      else $hint.text('Resultados generales (sin cobertura). Podés recetar con Reg. Nº + Cantidad.');
    }
    updateHint();

    // Select2 AJAX que SIEMPRE manda cobertura si existe (no hay validación posterior)
    s2Ajax($sel,{
      url: urls().medicamentos,
      minLen: 2,
      dataFn: p => {
        const cov = coberturaParams();
        const base = { q:p.term||'', page:p.page||1 };
        // mandamos SIEMPRE lo que haya de cobertura (si existe, la API ya filtra)
        if (cov.idFinanciador) base.idFinanciador = cov.idFinanciador;
        if (cov.planid)       base.planid       = cov.planid;
        if (cov.afiliadoDni)  base.afiliadoDni  = cov.afiliadoDni;
        if (cov.afiliadoCredencial) base.afiliadoCredencial = cov.afiliadoCredencial;
        return base;
      },
      procFn: d => ({ results: d.results||[], pagination:{ more: !!d?.pagination?.more } }),
      tpl: function(it){
        if(!it.id) return it.text;
        const r=it.raw||{}, flags=[];
        if (r.tieneCobertura) flags.push('Cobertura');
        if (r.psicofarmaco) flags.push('Psicofármaco');
        if (r.estupefaciente) flags.push('Estupefaciente');
        if (r.ventaControlada) flags.push('Venta controlada');
        if (r.hiv) flags.push('HIV');
        return $('<span>'+it.text+(flags.length? ' <small class="text-muted">('+flags.join(', ')+')</small>':'' )+'</span>');
      },
      langNoResults: 'No hay medicamentos disponibles con ese criterio.'
    });

    // Al elegir, completar campos (sin “validación extra”)
    $sel.on('select2:select', function(){
      const r=$sel.select2('data')[0]?.raw || {};
      $reg.val(r.regNo||'');
      $pre.val(r.presentacion||'');
      $nom.val(r.nombreProducto||'');
      $dro.val(r.nombreDroga||'');
      if (r.requiereDuplicado === true) $dup.prop('checked', true);
    });

    // Cuando la cobertura cambie, vaciamos selección de este row
    $(document).on('change', '#financiador, #plan', function(){
      $sel.val(null).trigger('change');
      $reg.val(''); $pre.val(''); $nom.val(''); $dro.val(''); $dup.prop('checked', false);
      updateHint();
    });
  }
  function initMeds(){
    let idx=1; const $wrap=$('#medsWrapper'), $add=$('#btnAddMed'); if(!$wrap.length) return;
    const tpl=i=>`
    <div class="med-row border rounded p-2 mb-3">
      <div class="form-row">
        <div class="form-group col-md-4 mb-2">
          <label class="mb-1 text-muted small">Buscar medicamento <span class="req text-danger">*</span></label>
          <select class="form-control form-control-sm sel-medicamento" style="width:100%"></select>
        </div>
        <div class="form-group col-md-2 mb-2">
          <label class="mb-1 text-muted small">Cantidad <span class="req text-danger">*</span></label>
          <input type="number" min="1" class="form-control form-control-sm" name="medicamentos[${i}][cantidad]" required>
        </div>
        <div class="form-group col-md-3 mb-2">
          <label class="mb-1 text-muted small">Registro Nº</label>
          <input type="text" class="form-control form-control-sm regno" name="medicamentos[${i}][regNo]" readonly>
        </div>
        <div class="form-group col-md-3 mb-2">
          <label class="mb-1 text-muted small">Presentación</label>
          <input type="text" class="form-control form-control-sm presentacion" name="medicamentos[${i}][presentacion]">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-4 mb-2">
          <label class="mb-1 text-muted small">Nombre</label>
          <input type="text" class="form-control form-control-sm nombre" name="medicamentos[${i}][nombre]">
        </div>
        <div class="form-group col-md-4 mb-2">
          <label class="mb-1 text-muted small">Droga</label>
          <input type="text" class="form-control form-control-sm droga" name="medicamentos[${i}][nombreDroga]">
        </div>
        <div class="form-group col-md-3 mb-2">
          <label class="mb-1 text-muted small">Tratamiento (días)</label>
          <input type="number" min="0" class="form-control form-control-sm" name="medicamentos[${i}][tratamiento]" placeholder="0">
        </div>
        <div class="form-group col-md-1 d-flex align-items-end mb-2">
          <button type="button" class="btn btn-sm btn-outline-danger btn-del-med" title="Eliminar">×</button>
        </div>
      </div>
      <div class="form-group mb-2">
        <label class="mb-1 text-muted small">Posología</label>
        <input type="text" class="form-control form-control-sm" name="medicamentos[${i}][posologia]">
      </div>
      <div class="form-group mb-2">
        <label class="mb-1 text-muted small">Indicaciones / Observaciones</label>
        <textarea class="form-control form-control-sm" name="medicamentos[${i}][indicaciones]" rows="2"></textarea>
      </div>
      <div class="custom-control custom-checkbox custom-control-inline">
        <input type="checkbox" class="custom-control-input duplicado" id="dup${i}" name="medicamentos[${i}][forzarDuplicado]" value="1">
        <label class="custom-control-label" for="dup${i}">Requiere duplicado</label>
      </div>
    </div>`;
    function toggleDel(){ const $rows=$wrap.children('.med-row'); $rows.find('.btn-del-med').prop('disabled',$rows.length<=1); }
    $add.on('click',()=>{ const $r=$(tpl(idx)); $wrap.append($r); attachMed($r); idx++; toggleDel(); });
    $wrap.on('click','.btn-del-med',function(){ if($wrap.children().length>1){ $(this).closest('.med-row').remove(); toggleDel(); } });
    attachMed($wrap.find('.med-row').first()); toggleDel();
  }

  // ===== Prácticas (si aplica) =====
  function initPracticas(){
    const $s=$('#practica_search'); if(!$s.length) return;
    s2Ajax($s,{
      url: urls().practicas, minLen:3,
      dataFn: p => {
        const term=p.term||''; const data={ page:p.page||1 };
        const mT=term.match(/tipo:([^ ]+)/i), mC=term.match(/cat:([^ ]+)/i);
        if(mT) data.tipo=mT[1]; if(mC) data.categoria=mC[1];
        const clean=term.replace(/tipo:[^ ]+/ig,'').replace(/cat:[^ ]+/ig,'').trim();
        if(clean) data.search=clean; return data;
      },
      procFn: d => ({ results: d.results||[], pagination:{ more: !!d?.pagination?.more } })
    });
    const $chips=$('#practicasList'), $h=$('#practicasHidden');
    function addChip(it){ const id=it.id; if($h.find(`input[value="${id}"]`).length) return; $h.append($('<input type="hidden" name="practicas[]">').val(id));
      $chips.append($(`<span class="badge badge-primary d-inline-flex align-items-center mr-2 mb-2" data-id="${id}" style="font-size:.8rem;"><span class="mr-2">${it.text}</span><button type="button" class="btn btn-sm btn-light py-0 px-1 quitar-chip" aria-label="Quitar" style="line-height:1;">×</button></span>`));
    }
    $s.on('select2:select',()=>{ const it=$s.select2('data')[0]; if(it){ addChip(it); $s.val(null).trigger('change'); } });
    $chips.on('click','.quitar-chip',function(){ const id=$(this).closest('[data-id]').data('id'); $h.find(`input[name="practicas[]"][value="${id}"]`).remove(); $(this).closest('[data-id]').remove(); });
  }

  // ===== Sexo sin opción vacía =====
  function initSexo(){
    const $s=$('[name="paciente[sexo]"]'); if(!$s.length) return;
    if (!$s.val()) $s.val('M');
    $s.find('option').each(function(){
      const v=$(this).val();
      if (v==='M') $(this).text('Hombre (M)');
      else if (v==='F') $(this).text('Mujer (F)');
      else if (v==='X') $(this).text('No binario (X)');
      else if (v==='O') $(this).text('Otro (O)');
    });
  }

  // ===== Matrícula MN/MP: provincia obligatoria sólo en MP =====
function initMatricula(){
  const $tipo   = $('[name="medico[matricula][tipo]"]');
  const $numero = $('[name="medico[matricula][numero]"]');
  let   $provMat = $('[name="medico[matricula][provincia]"]');
  const $provMatWrap = $provMat.closest('.form-group');

  fetchProvincias().then(items => {
    // === Select de provincias para MATRÍCULA ===
    if ($provMat.length) {
      $provMat = toSelect($provMat, items, { name: 'medico[matricula][provincia]' });
    }

    // === Select de provincias para DOMICILIO (opcional) ===
    const $provDom = $('[name="domicilio[provincia]"]');
    if ($provDom.length) {
      toSelect($provDom, items, { name: 'domicilio[provincia]' });
    }

    function applyRules(){
      const t = ($tipo.val() || 'MN').toUpperCase().trim();
      if (!$tipo.val()) $tipo.val('MN');

      $numero.attr('maxlength', '10');
      setRequired($numero, true);

      if (t === 'MP') {
        $provMatWrap.show();
        setRequired($provMat, true);
      } else {
        $provMatWrap.hide();
        setRequired($provMat, false);
      }
    }

    $tipo.on('change', applyRules);
    if (!$tipo.val()) $tipo.val('MN');
    applyRules();
  });
}



  // ===== Fecha: sólo datepicker (readonly) =====
  function initFecha(){
    const $iso=$('[name="paciente[fechaNacimiento]"]'); if(!$iso.length) return;
    const $vis=$('<input type="text" id="paciente_fecha_visual" class="form-control form-control-sm" placeholder="DD/MM/AAAA" readonly>');
    const curISO=$iso.val(); 
    $vis.val(isoAEs(curISO)).insertAfter($iso);

    // Si setPac corrió antes que initFecha, aplicamos la fecha pendiente
    if (pendingFechaISO) {
      $iso.val(pendingFechaISO);
      $vis.val(isoAEs(pendingFechaISO));
      pendingFechaISO = '';
    }

    $iso.attr('type','hidden');

    $vis.on('keydown paste', e=> e.preventDefault());

    if ($.datepicker && !$.datepicker.regional['es']) {
      $.datepicker.regional['es'] = {closeText:'Cerrar',prevText:'Anterior',nextText:'Siguiente',currentText:'Hoy',
        monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        monthNamesShort:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        dayNames:['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],dayNamesShort:['Dom','Lun','Mar','Mié','Jue','Sáb'],
        dayNamesMin:['Do','Lu','Ma','Mi','Ju','Vi','Sa'],weekHeader:'Sm',dateFormat:'dd/mm/yy',firstDay:1,isRTL:false,showMonthAfterYear:false,yearSuffix:''};
      $.datepicker.setDefaults($.datepicker.regional['es']);
    }
    if ($.fn.datepicker) {
      $vis.datepicker({
        changeMonth:true, changeYear:true, yearRange:'1900:+0', dateFormat:'dd/mm/yy',
        onClose:v=> $iso.val(esAISO(v))
      });
    }
    $vis.on('focus click', function(){ if ($.fn.datepicker) $(this).datepicker('show'); });
  }

  // ===== Submit (validaciones al usuario) =====
function initSubmit(){
  const $f = $('#recetaForm');
  if (!$f.length) return;

  $f.on('submit', function(e){
    e.preventDefault();

    const items = [];

    // Normalizar fecha visual → ISO (YYYY-MM-DD) antes de enviar
    const vis = $('#paciente_fecha_visual').val();
    const iso = esAISO(vis);
    if (vis && iso) {
      $('[name="paciente[fechaNacimiento]"]').val(iso);
    }

    // ===== DNI paciente =====
    const dni = ($('[name="paciente[nroDoc]"]').val() || '').replace(/\D+/g, '');
    if (!dni) {
      items.push('DNI del paciente es obligatorio.');
    } else if (dni.length < 7 || dni.length > 9) {
      items.push('DNI del paciente debe tener entre 7 y 9 dígitos.');
    }

    // ===== Sexo paciente =====
    const sexo = ($('[name="paciente[sexo]"]').val() || '').toUpperCase();
    if (!['M','F','X','O'].includes(sexo)) {
      items.push('Sexo seleccionado no es válido.');
    }

    // ===== Fecha de nacimiento: no futura =====
    const fiso = $('[name="paciente[fechaNacimiento]"]').val();
    if (fiso) {
      const d = new Date(fiso + 'T00:00:00');
      const today = new Date();
      today.setHours(0,0,0,0);
      if (isNaN(d.getTime())) {
        items.push('Fecha de nacimiento inválida.');
      } else if (d > today) {
        items.push('La fecha de nacimiento no puede ser futura.');
      }
    }

    // ===== Matrícula médico =====
    const tipoMat = ($('[name="medico[matricula][tipo]"]').val() || 'MN').toUpperCase();
    const nroMat  = ($('[name="medico[matricula][numero]"]').val() || '').replace(/\D+/g, '');
    const provMat = ($('[name="medico[matricula][provincia]"]').val() || '').trim();

    if (!nroMat) {
      items.push('Número de matrícula es obligatorio.');
    } else if (nroMat.length > 10) {
      items.push('Número de matrícula: máximo 10 dígitos.');
    }
    if (tipoMat === 'MP' && !provMat) {
      items.push('Para matrícula provincial (MP), la provincia es obligatoria.');
    }

    // ===== Domicilio OPCIONAL =====
    // Solo se considera que hay domicilio si completan algo DISTINTO de la provincia.
    // La provincia NO dispara validación por sí sola.
    const dCalle    = ($('[name="domicilio[calle]"]').val() || '').trim();
    const dNum      = ($('[name="domicilio[numero]"]').val() || '').trim();
    const domProvEl = $('[name="domicilio[provincia]"]');
    const dProv     = (domProvEl.val() || '').trim(); // no se usa para validar, solo lo dejamos por las dudas
    const dLoc      = ($('[name="domicilio[localidad]"]').val() || '').trim();
    const dCP       = ($('[name="domicilio[codigoPostal]"]').val() || '').trim();

    // NO incluimos dProv acá, así que seleccionar solo provincia NO obliga nada
    // Todo un tema lo de provincias
    const anyDom = dCalle || dNum || dLoc || dCP;

    if (anyDom) {
      if (!dCalle) {
        items.push('Domicilio: la calle es obligatoria si completás el domicilio.');
      }
      if (!dNum) {
        items.push('Domicilio: el número es obligatorio si completás el domicilio.');
      }
      // La provincia ES OPCIONAL para domicilio, no la validamos acá
    }


    // ===== Cobertura: si hay financiador, número de afiliado obligatorio y numérico =====
    const idFin = ($('[name="cobertura[idFinanciador]"]').val() || '').trim();
    const cred  = ($('[name="cobertura[credencial]"]').val() || '').trim();

    if (idFin) {
      if (!cred) {
        items.push('Cobertura: el número de afiliado es obligatorio si indicás un financiador.');
      } else if (!/^\d+$/.test(cred)) {
        items.push('Cobertura: el número de afiliado debe tener solo números (sin puntos ni guiones).');
      }
    }

    // ===== Medicamentos mínimos =====
    const $rows = $('#medsWrapper .med-row');
    if (!$rows.length) {
      items.push('Agregá al menos un medicamento.');
    }
    $rows.each(function(i){
      const $r   = $(this);
      const cant = Number($r.find('input[name^="medicamentos"][name$="[cantidad]"]').val() || 0);
      const reg  = ($r.find('.regno').val() || '').trim();

      if (!cant || cant < 1) {
        items.push(`Medicamento (fila ${i+1}): la cantidad debe ser mayor a 0.`);
      }
      if (!reg) {
        items.push(`Medicamento (fila ${i+1}): seleccioná un medicamento de la lista (Registro Nº).`);
      }
    });

    // ===== Si hay errores, mostramos modal y no enviamos =====
    if (items.length) {
      modal({ title: 'Revisá estos datos', html: errList(items) });
      return;
    }

    // ===== Envío AJAX =====
    const url  = $f.attr('action');
    const data = $f.serialize();
    const $btn = $f.find('button[type="submit"]');
    const old  = $btn.html();

    $btn.prop('disabled', true).html('Generando…');

    $.ajax({
      type: 'POST',
      url,
      data,
      headers: { 'Accept': 'application/json' },
      timeout: AJAX_TIMEOUT_MS
    })
    .done(r => {
      if (r && r.ok) {
        if (r.url) {
          location.assign(new URL(r.url, location.origin).toString());
          return;
        }
        if (r.show) {
          location.assign(new URL(r.show, location.origin).toString());
          return;
        }
        location.assign(location.origin + '/empleados/recetas');
        return;
      }
      modal({ title: 'Aviso', html: `<p>${esc(r?.message || 'Operación realizada.')}</p>` });
    })
    .fail(jq => {
      let items2 = [];

      if (jq.status === 422) {
        const e = (jq.responseJSON || {}).errors || {};
        items2 = Object.keys(e).map(k => `${k}: ${(e[k] || []).join(' ')}`);
      } else if (jq.status === 504) {
        items2 = ['El servicio tardó demasiado en responder. Probá nuevamente.'];
      } else {
        try {
          const j  = jq.responseJSON || JSON.parse(jq.responseText || '{}');
          const msg = j.message || j.mensaje || jq.statusText || 'Error al generar la receta.';
          if (msg) items2.push(msg);
          if (j.code) items2.push('Código técnico: ' + j.code);
        } catch {
          items2.push('Error desconocido al generar la receta.');
        }
      }

      modal({ title: 'No se pudo generar la receta', html: errList(items2) });
    })
    .always(() => {
      $btn.prop('disabled', false).html(old);
    });
  });
}


  // ===== Boot =====
  function boot(){
    $.ajaxSetup({ headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}, timeout: AJAX_TIMEOUT_MS });

    initSexo();
    initFecha();
    initSubmit();

    let tries=0; const iv=setInterval(function(){
      tries++;
      if (typeof $.fn.select2 === 'function'){
        clearInterval(iv);
        fetchProvincias().then(()=>{  // cargo provincias antes de reglas de matrícula
          initMatricula();
          initNomina();
          initFinanciadores();
          initDiagnosticos();
          initMeds();
          initPracticas();
        });
      } else if (tries>60){
        clearInterval(iv);
        fetchProvincias().then(initMatricula);
        initMeds();
      }
    },100);
  }
  $(boot);

})(jQuery, window, document);
