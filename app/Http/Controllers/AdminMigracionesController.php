<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\User;
use App\ClienteUser;
use App\Nomina;
use App\Fichada;
use App\FichadaNueva;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use App\MigrarSitioPrevio;
use App\ProvinciaReceta;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\NamedRange;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Protection as CellProtection;

class AdminMigracionesController extends Controller
{

	public function migrar()
	{

		if (auth()->user()->email == 'sebas_admin@ejornal.com.ar') {

		// Migracion de clientes con datos actualizados (Momentáneo. Se deja comentado dps de la migracion)
		return view('admin.migrar');

			$migraciones = MigrarSitioPrevio::all()->first();
			$sitio_previo_migrado = false;

			if (isset($migraciones) && !empty($migraciones)) {

				if ($migraciones->clientes == 0) {
					$clientes = false;
				}else {
					$clientes = true;
				}

				if ($migraciones->user_empleados == 0) {
					$users_empleados = false;
				}else {
					$users_empleados = true;
				}

				if ($migraciones->nominas == 0) {
					$nominas = false;
				}else {
					$nominas = true;
				}

			}else {
				$sitio_previo_migrado = false;
				$users_empleados = false;
				$nominas = false;
				$clientes = false;
			}

			if ($clientes == true && $users_empleados == true && $nominas == true) {
				$sitio_previo_migrado = true;
			}

			// Se agreggó por una migracion el campo Fichada. Este campo es para migrar los datos de la tabla fichada
			// a la nueva tabla de FichadasNuevas. Esto no es del sitio viejo. Se pensaron de una forma las fichadas y
			// luego se decidió cambiarla, por eso la migracion.
			if ($migraciones->fichada == 0) {
				$fichadas = false;
			}else {
				$fichadas = true;
			}

			return view('admin.migrar', compact('sitio_previo_migrado', 'clientes', 'users_empleados', 'nominas', 'fichadas'));

		}else {
			return redirect('admin/resumen');
		}
	}


	// Clientes actualizados en su direccion (calle, nro, provincia) via CSV (Excel)  //

	public function exportarModeloClientesActualizados()
	{
		if (auth()->user()->email !== 'sebas_admin@ejornal.com.ar') {
			return redirect('admin/resumen');
		}

		$password = 'ejornal_migraciones'; // cambiá esto si querés

		$provincias = ProvinciaReceta::select('id','nombre')->orderBy('nombre')->get();
		$provNombreById = $provincias->pluck('nombre', 'id')->all();

		$clientes = Cliente::select('id','nombre','direccion','calle','nro','id_provincia')
			->orderBy('id','asc')->get();

		$spreadsheet = new Spreadsheet();

		// ===== Hoja 1: Clientes =====
		$sh = $spreadsheet->getActiveSheet();
		$sh->setTitle('Clientes');

		$sh->fromArray(['id_cliente','nombre','direccion_actual','calle','nro','provincia'], null, 'A1');
		$sh->getStyle('A1:F1')->getFont()->setBold(true);
		$sh->freezePane('A2');

		$row = 2;
		foreach ($clientes as $c) {
			$prov = $c->id_provincia ? ($provNombreById[$c->id_provincia] ?? '') : '';

			$sh->fromArray([
				$c->id,
				$c->nombre,
				$c->direccion ?? '',
				$c->calle ?? '',
				$c->nro ?? '',
				$prov
			], null, "A{$row}");

			$row++;
		}
		$lastRow = max(2, $row - 1);

		// Anchos
		$sh->getColumnDimension('A')->setWidth(12);
		$sh->getColumnDimension('B')->setWidth(28);
		$sh->getColumnDimension('C')->setWidth(40);
		$sh->getColumnDimension('D')->setWidth(25);
		$sh->getColumnDimension('E')->setWidth(10);
		$sh->getColumnDimension('F')->setWidth(22);

		// Bloqueo por celdas: A:C locked, D:F editable
		$sh->getStyle("A2:C{$lastRow}")->getProtection()
			->setLocked(CellProtection::PROTECTION_PROTECTED);

		$sh->getStyle("D2:F{$lastRow}")->getProtection()
			->setLocked(CellProtection::PROTECTION_UNPROTECTED);

		// ===== Hoja 2: Provincias =====
		$shProv = $spreadsheet->createSheet();
		$shProv->setTitle('Provincias');

		$shProv->setCellValue('A1', 'provincia');
		$shProv->getStyle('A1')->getFont()->setBold(true);

		$r = 2;
		foreach ($provincias as $p) {
			$shProv->setCellValue("A{$r}", $p->nombre);
			$r++;
		}
		$provLastRow = max(2, $r - 1);

		$shProv->getColumnDimension('A')->setWidth(25);

		// NamedRange para el dropdown (más robusto)
		$spreadsheet->addNamedRange(new NamedRange('ListaProvincias', $shProv, "A2:A{$provLastRow}"));

		// Dropdown en F2:F{lastRow}
		for ($i = 2; $i <= $lastRow; $i++) {
			$dv = $sh->getCell("F{$i}")->getDataValidation();
			$dv->setType(DataValidation::TYPE_LIST);
			$dv->setErrorStyle(DataValidation::STYLE_STOP);
			$dv->setAllowBlank(false);
			$dv->setShowDropDown(true);
			$dv->setShowInputMessage(true);
			$dv->setPromptTitle('Provincia');
			$dv->setPrompt('Elegí una provincia del listado.');
			$dv->setShowErrorMessage(true);
			$dv->setErrorTitle('Valor inválido');
			$dv->setError('Debés elegir una provincia del dropdown.');
			$dv->setFormula1('=ListaProvincias');
		}

		// ===== Protecciones =====

		// Bloquear estructura del libro: no borrar/renombrar hojas
		$spreadsheet->getSecurity()->setLockStructure(true);
		$spreadsheet->getSecurity()->setWorkbookPassword($password);

		// Proteger hoja Provincias (no editable)
		$shProv->getProtection()->setSheet(true);
		$shProv->getProtection()->setPassword($password);

		// Proteger hoja Clientes (no pueden tocar validaciones ni celdas bloqueadas)
		$sh->getProtection()->setSheet(true);
		$sh->getProtection()->setPassword($password);

		// Export a output
		$filename = 'modelo_clientes_actualizados_' . date('Ymd_His') . '.xlsx';
		$writer = new Xlsx($spreadsheet);

		return response()->streamDownload(function () use ($writer) {
			$writer->save('php://output');
		}, $filename, [
			'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		]);
	}



public function migrarClientesActualizados(Request $request)
{
    if (auth()->user()->email !== 'sebas_admin@ejornal.com.ar') {
        return redirect('admin/resumen');
    }

    $request->validate([
        'archivo_excel' => 'required|file|max:10240|mimes:xlsx',
    ], [
        'archivo_excel.required' => 'Tenés que subir un archivo.',
        'archivo_excel.mimes'    => 'Formato inválido. Subí el Excel .xlsx descargado desde el botón de modelo.',
        'archivo_excel.max'      => 'El archivo supera el máximo permitido (10MB).',
    ]);

    // Provincias DB: nombre_normalizado -> id
    $provMap = [];
    foreach (ProvinciaReceta::select('id','nombre')->get() as $p) {
        $provMap[$this->normText($p->nombre)] = $p->id;
    }

    try {
        $spreadsheet = IOFactory::load($request->file('archivo_excel')->getRealPath());
    } catch (\Throwable $e) {
        return back()->withErrors(['No se pudo leer el Excel: ' . $e->getMessage()]);
    }

    $sh = $spreadsheet->getSheetByName('Clientes');
    if (!$sh) {
        return back()->withErrors(['El Excel no contiene la hoja "Clientes". Descargá el modelo nuevamente.']);
    }

    // Validar cabeceras (fila 1)
    $expected = ['id_cliente','nombre','direccion_actual','calle','nro','provincia'];
    $found = [];
    foreach (['A','B','C','D','E','F'] as $idx => $col) {
        $found[] = $this->normHeader((string)$sh->getCell($col.'1')->getValue());
    }
    if ($found !== $expected) {
        return back()->withErrors([
            'Cabeceras inválidas en hoja "Clientes". Deben ser: ' . implode(', ', $expected)
        ]);
    }

    $highestRow = (int)$sh->getHighestRow();
    if ($highestRow < 2) {
        return back()->withErrors(['El Excel no tiene filas para procesar.']);
    }

    $errores = [];
    $updates = [];

    for ($r = 2; $r <= $highestRow; $r++) {
        $id = trim((string)$sh->getCell("A{$r}")->getValue());
        $calle = trim((string)$sh->getCell("D{$r}")->getValue());
        $nroVal = $sh->getCell("E{$r}")->getValue();
        $prov = trim((string)$sh->getCell("F{$r}")->getValue());

        // Saltar fila totalmente vacía
        if ($id === '' && $calle === '' && (string)$nroVal === '' && $prov === '') {
            continue;
        }

        if ($id === '' || !ctype_digit($id)) {
            $errores[] = "Fila {$r}: id_cliente inválido.";
            continue;
        }

        // calle/nro/provincia: si están vacíos, los ignoramos (para “completar faltantes”)
        $nro = $this->parseDigits($nroVal);
        if ($nroVal !== null && $nroVal !== '' && $nro === null) {
            $errores[] = "Fila {$r}: nro inválido. Debe ser numérico (solo dígitos).";
            continue;
        }

        $idProvincia = null;
        if ($prov !== '') {
            $idProvincia = $provMap[$this->normText($prov)] ?? null;
            if (!$idProvincia) {
                $errores[] = "Fila {$r}: provincia '{$prov}' no existe en ProvinciaReceta.";
                continue;
            }
        }

        $cliente = Cliente::find((int)$id);
        if (!$cliente) {
            $errores[] = "Fila {$r}: no existe Cliente con id {$id}.";
            continue;
        }

        $updates[] = [
            'cliente' => $cliente,
            'calle' => $calle,
            'nro' => $nro,
            'id_provincia' => $idProvincia,
        ];
    }

    if (!empty($errores)) {
        return back()->withErrors(array_slice($errores, 0, 60));
    }

    DB::beginTransaction();
    try {
        $actualizados = 0;

        foreach ($updates as $u) {
            /** @var Cliente $c */
            $c = $u['cliente'];

            // Completar faltantes (no pisa si ya existe)
            if ($u['calle'] !== '' && empty($c->calle)) {
                $c->calle = $u['calle'];
            }
            if ($u['nro'] !== null && ($c->nro === null || $c->nro === '')) {
                $c->nro = $u['nro'];
            }
            if ($u['id_provincia'] && empty($c->id_provincia)) {
                $c->id_provincia = $u['id_provincia'];
            }

            if ($c->isDirty(['calle','nro','id_provincia'])) {
                $c->save();
                $actualizados++;
            }
        }

        DB::commit();
    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->withErrors(['Error al actualizar: ' . $e->getMessage()]);
    }

    return back()->with('ok', "Migración OK. Clientes actualizados: {$actualizados}.");
}

    private function normHeader($s)
{
    $s = trim((string)$s);
    $s = mb_strtolower($s, 'UTF-8');
    $s = $this->stripAccents($s);
    $s = preg_replace('/[^a-z0-9_ ]/i', '', $s);
    $s = preg_replace('/\s+/', '_', $s);
    return trim($s, '_');
}

private function normText($s)
{
    $s = trim((string)$s);
    $s = mb_strtolower($s, 'UTF-8');
    $s = $this->stripAccents($s);
    $s = preg_replace('/[^a-z0-9 ]/i', ' ', $s);
    $s = preg_replace('/\s+/', ' ', $s);
    return trim($s);
}

private function stripAccents($str)
{
    $out = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
    return $out !== false ? $out : $str;
}

// Acepta nro como número de Excel o string; devuelve string de dígitos o null
private function parseDigits($v)
{
    if ($v === null) return null;

    // si viene como float/int
    if (is_int($v)) return (string)$v;
    if (is_float($v)) {
        if (floor($v) != $v) return null;
        return (string)(int)$v;
    }

    $s = trim((string)$v);
    if ($s === '') return null;

    // si es "123" ok
    if (ctype_digit($s)) return $s;

    // si viene "123.0"
    if (is_numeric($s)) {
        $f = (float)$s;
        if (floor($f) != $f) return null;
        return (string)(int)$f;
    }

    return null;
}


	// Clientes actualizados en su direccion (calle, nro, provincia) via CSV (Excel)  //


	public function migrarClientes()
	{

		$archivo = file_get_contents(__DIR__.'/migrar_viejo_sitio/clientes.json');
		$array = json_decode($archivo);

		if (empty($array) || count($array) < 1) {
			return back()->with('error', 'El archivo no tiene información o está vacío');
		}else {
			foreach ($array as $value) {
				$cliente = new Cliente();
				$cliente->id = $value->id;
				$cliente->nombre = $value->nombre;
				$cliente->direccion = $value->direccion;
				$cliente->save();
			}

			$clientes_migrados = MigrarSitioPrevio::findOrFail(1);
			$clientes_migrados->clientes = 1;
			$clientes_migrados->save();

			return redirect()->action([AdminMigracionesController::class, 'migrar']);
		}



	}


	public function migrarUsersEmpleados()
	{
		$archivo = file_get_contents(__DIR__.'/migrar_viejo_sitio/users_empleados.json');
		$array = json_decode($archivo);


		if (empty($array) || count($array) < 1) {
			return back()->with('error', 'El archivo no tiene información o está vacío');
		}else {
			foreach ($array as $value) {
				$user = new User();
				$user->id = $value->id;
				$user->id_rol = 2;
				$user->nombre = $value->nombre . ' ' . $value->apellido;
				$user->estado = 1;
				if (isset($value->email) && !empty($value->email)) {
					$user->email = $value->email;
				}
				$user->password = bcrypt('123456');
				$user->save();
			}

			$clientes_migrados = MigrarSitioPrevio::findOrFail(1);
			$clientes_migrados->user_empleados = 1;
			$clientes_migrados->save();

			return redirect()->action([AdminMigracionesController::class, 'migrar']);
		}

	}


	public function migrarNominas()
	{
		$archivo = file_get_contents(__DIR__.'/migrar_viejo_sitio/nominas.json');
		$array = json_decode($archivo);

		if (empty($array) || count($array) < 1) {
			return back()->with('error', 'El archivo no tiene información o está vacío');
		}else {
			foreach ($array as $value) {
				$nomina = new Nomina();
				$nomina->id = $value->id;
				$nomina->id_cliente = $value->empresa_id;
				$nomina->nombre = $value->nombre . ' ' . $value->apellido;
				$nomina->email = $value->email;
				$nomina->telefono = $value->telefono;
				$nomina->dni = $value->documento;
				$nomina->estado = 1;
				$nomina->sector = $value->sector;
				$nomina->save();
			}

			$clientes_migrados = MigrarSitioPrevio::findOrFail(1);
			$clientes_migrados->nominas = 1;
			$clientes_migrados->save();

			return redirect()->action([AdminMigracionesController::class, 'migrar']);
		}


	}



	public function migrarFichadas()
	{
			$results =  Fichada::join('users', 'fichadas.id_user', 'users.id')
			->join('clientes', 'fichadas.id_cliente', 'clientes.id')
			->select('fichadas.*', DB::raw('clientes.nombre cliente'), DB::raw('users.nombre user'))
			->orderBy('fichadas.id_user', 'desc')
			->orderBy('fichadas.created_at', 'desc')
			->get();

			$fichadas = [];

			$modelo = 'App\Fichada';
			foreach ($results as $resultado) {
				$audits_fichadas = DB::table('audits')->where('auditable_type', $modelo)->get();
					if (!empty($audits_fichadas) && count($audits_fichadas) > 0) {
					foreach ($audits_fichadas as $audit) {
						if ($resultado->id == json_decode($audit->new_values)->id) {
							$resultado['ip'] = $audit->ip_address;
						}
					}
				}
			}

			foreach ($results as $key => $result) {

					$egreso_hallado = null;
					$ingreso_hallago = null;

					if ($result->horario_ingreso != null) {
						$ingreso_hallago = $result->created_at;
						if (isset($results[$key-1]->id_user) && $results[$key-1]->id_user == $result->id_user) {
							// Cargar el egreso
							$egreso_hallado = $results[$key-1]->created_at;
						}else {
							$egreso_hallado = null;
						}

						$fecha_ingreso = Carbon::createFromFormat('Y-m-d H:i:s', $ingreso_hallago)->format('d-m-Y H:i:s');

						if ($egreso_hallado != null) {
							$fecha_egreso = Carbon::createFromFormat('Y-m-d H:i:s', $egreso_hallado)->format('d-m-Y H:i:s');
							$f_ingreso = new DateTime($result->created_at);
							$f_egreso = new DateTime($egreso_hallado);
							$time = $f_ingreso->diff($f_egreso);
							$tiempo_dedicado = $time->days . ' dias ' . $time->format('%H horas %i minutos %s segundos');
						}

						$fichadas[] = [
							'id' => $result->id,
							'fecha_actual' => $result->fecha_actual,
							'created_at' => $result->created_at,
							'updated_at' => $result->updated_at,
							'cliente' => $result->cliente,
							'id_cliente' => $result->id_cliente,
							'id_user' => $result->id_user,
							'user' => $result->user,
							'tiempo_dedicado' => (isset($tiempo_dedicado) && !empty($tiempo_dedicado)) ? $tiempo_dedicado : 'Aún trabajando',
							'fecha_ingreso' => $fecha_ingreso,
							'fecha_egreso' => ($egreso_hallado != null) ? $fecha_egreso : null,
							'ip' => $result->ip
						];
					}
				}
				foreach ($fichadas as $fichada) {
					$fichadas_nuevas = new FichadaNueva;
					$fichadas_nuevas->ingreso = new DateTime($fichada['fecha_ingreso']);
					$fichadas_nuevas->egreso = ($fichada['fecha_egreso'] != null) ? new DateTime($fichada['fecha_egreso']) : null;
					$fichadas_nuevas->tiempo_dedicado = $fichada['tiempo_dedicado'];
					$fichadas_nuevas->id_user = $fichada['id_user'];
					$fichadas_nuevas->id_cliente = $fichada['id_cliente'];
					$fichadas_nuevas->ip = $fichada['ip'];
					$fichadas_nuevas->created_at = $fichada['created_at'];
					$fichadas_nuevas->updated_at = $fichada['updated_at'];
					$fichadas_nuevas->save();
				}

				$fichadas_migradas = MigrarSitioPrevio::findOrFail(1);
				$fichadas_migradas->fichada = 1;
				$fichadas_migradas->save();

				return redirect()->action([AdminMigracionesController::class, 'migrar']);

	}


	public function migrarUsersClientes()
	{
		$users = User::where('id_rol',3)->whereNotNull('id_cliente_relacionar')->get();

		$clientes_users = ClienteUser::all();

		foreach($users as $user)
		{
			$exists = false;
			foreach($clientes_users as $cliente_user)
			{
				if($cliente_user->id_user==$user->id && $cliente_user->id_cliente==$user->id_cliente_relacionar) $exists=true;
			}

			$cl_us = new ClienteUser;
			$cl_us->id_user = $user->id;
			$cl_us->id_cliente = $user->id_cliente_relacionar;
			$cl_us->save();

			$user->id_cliente_relacionar = null;
			$user->save();

			///var_dump($exists);
		}

		return redirect()->action([AdminMigracionesController::class, 'migrar']);


	}


}
