<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;


class AdminMonitoreoController extends Controller
{


	public function index()
	{
		// Conteo de usuarios
		$usuariosTotal = User::count();
		$usuariosHoy   = User::whereDate('created_at', now()->toDateString())->count();

		// CPU y memoria
		$cpuPercent            = $this->getCpuUsagePercent();
		list($memTotalMB, $memUsedMB) = $this->getMemoryInfo();

		// Leer logs de últimos 2 meses
		$logDir = storage_path('logs');
		$lines  = [];
		$errorsFatal = [];
		$allErrorsFatal = [];
		$errorsWarn  = [];
		$allErrorsWarn  = [];

		$trafficLabels    = [];
		$trafficDataError = [];
		$trafficDataWarn  = [];

		$cutoff = now()->subDays(14);


		//dd($trafficLabels);


		$files = collect(File::files($logDir))
			->filter(function ($file) {
				return preg_match('/laravel-(\d{4}-\d{2}-\d{2})\.log/', $file->getFilename());
			})
			->map(function ($file) {
					preg_match('/laravel-(\d{4}-\d{2}-\d{2})\.log/', $file->getFilename(), $matches);
					return (object) [
						'file' => $file,
						'date' => Carbon::createFromFormat('Y-m-d', $matches[1])
					];
			})
			->sortByDesc('date');

		foreach($files as $file){

			///dump([$file->date->format('Ymd'),$cutoff->format('Ymd')]);
			if($file->date < $cutoff) continue;

			$fileLines = file($file->file->getPathname(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			//dd($fileLines);
			//$lines = array_merge($lines,$fileLines);
			$fileLines = array_reverse($fileLines);


			foreach($fileLines as $line){

				$error_values = (object) [
					'date'=>$file->date,
					'line'=>$line
				];

				if (preg_match('/\]\s.*\.ERROR:\s(.*)$/', $line, $m) ) {
					if(!isset($trafficDataError[$file->date->format('Ymd')])){
						$trafficDataError[$file->date->format('Ymd')] = 1;
					}else{
						$trafficDataError[$file->date->format('Ymd')]++;
					}
					if(count($errorsFatal)>10) continue;
					$errorsFatal[] = $error_values;
				}
				if (preg_match('/\]\s.*\.WARNING:\s(.*)$/', $line, $m)) {

					if(!isset($trafficDataWarn[$file->date->format('Ymd')])){
						$trafficDataWarn[$file->date->format('Ymd')] = 1;
					}else{
						$trafficDataWarn[$file->date->format('Ymd')]++;
					}

					if(count($errorsWarn)>10) continue;
					$errorsWarn[] = $error_values;
				}

			}

		}
		//dd($trafficDataError);

		$intervals = new \DatePeriod($cutoff, \DateInterval::createFromDateString('1 day'), now()->addDays(1));
		foreach($intervals as $interval){
			$trafficLabels[] = $interval->format('d/m/Y');
			if( !isset($trafficDataError[$interval->format('Ymd')]) ) $trafficDataError[$interval->format('Ymd')] = 0;
			if( !isset($trafficDataWarn[$interval->format('Ymd')]) ) $trafficDataWarn[$interval->format('Ymd')] = 0;
		}
		ksort($trafficDataError);
		ksort($trafficDataWarn);
		//dd($trafficDataError);
		$trafficDataError = array_values($trafficDataError);
		$trafficDataWarn = array_values($trafficDataWarn);
		//dd($trafficDataError);

		//dd($trafficDataError);


		/*foreach (File::files($logDir) as $file) {
			if (preg_match('/laravel-(\d{4}-\d{2}-\d{2})\.log$/', $file->getFilename(), $m)) {

				$date = Carbon::createFromFormat('Y-m-d', $m[1]);
				dd($m[1]);
				if($date->greaterThanOrEqualTo($cutoff)) continue;

				$fileLines = file($file->getPathname(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
				$lines[$m[1]] = $fileLines;
			}
		}
		$lines = array_reverse($lines);*/

		// Últimos errores
		/*$errorsFatal = [];
		$errorsWarn  = [];
		foreach ($lines as $line) {
			if (preg_match('/\]\s.*\.ERROR:\s(.*)$/', $line, $m)) {
				if (count($errorsFatal) < 10) $errorsFatal[] = $m[1];
			} elseif (preg_match('/\]\s.*\.WARNING:\s(.*)$/', $line, $m)) {
				if (count($errorsWarn) < 10) $errorsWarn[] = $m[1];
			}
			if (count($errorsFatal) >= 10 && count($errorsWarn) >= 10) break;
		}*/

		// Métricas de errores últimos 2 meses
		/*$stats = [];
		for ($i = 1; $i >= 0; $i--) {
			$m = now()->subMonths($i)->format('Y-m');
			$stats[$m] = ['error' => 0, 'warning' => 0];
		}
		foreach ($lines as $line) {
			if (preg_match('/\[(\d{4}-\d{2})-/',$line,$m)) {
				$month = $m[1];
				if (isset($stats[$month])) {
					if (strpos($line, '.ERROR:')   !== false) $stats[$month]['error']++;
					if (strpos($line, '.WARNING:') !== false) $stats[$month]['warning']++;
				}
			}
		}
		$trafficLabels    = array_keys($stats);
		$trafficDataError = array_column($stats, 'error');
		$trafficDataWarn  = array_column($stats, 'warning');*/



		// Uso de servicios: top 5 rutas
		$routes = Cache::get('routes_list', []);
		$counts = [];
		foreach ($routes as $uri) {
			$counts[$uri] = Cache::get("route_hits:{$uri}", 0);
		}
		arsort($counts);
		$svc            = array_slice($counts, 0, 5, true);
		$serviceLabels  = array_keys($svc);
		$serviceValues  = array_values($svc);

		// Uso de disco
		list($diskTotalMB, $diskUsedMB, $diskFreeMB, $diskUsedPct) = $this->getDiskInfo();

		// Cantidad de conexiones activas a bases de datos
		$dbConnections = $this->getActiveDbConnections();

		// Consultas por segundo (QPS)
		$qpsMetrics = $this->getQps();

		// Tarjetas
		$cards = [
			['label'=>'Usuarios totales','value'=>$usuariosTotal,'icon'=>'users','unit'=>''],
			['label'=>'Users nuevos hoy','value'=>$usuariosHoy,'icon'=>'user-plus','unit'=>''],
			['label'=>'CPU %','value'=>$cpuPercent,'icon'=>'microchip','unit'=>' %'],
			['label'=>'Memoria total','value'=>number_format($memTotalMB,0,',','.'),'icon'=>'hdd','unit'=>' MB'],
			['label'=>'Memoria usada','value'=>number_format($memUsedMB,0,',','.'),'icon'=>'hdd','unit'=>' MB'],
			['label'=>'Disco total','value'=>number_format($diskTotalMB,0,',','.'),'icon'=>'database','unit'=>' MB'],
			['label'=>'Disco usado','value'=>number_format($diskUsedMB,0,',','.'),'icon'=>'chart-pie','unit'=>' MB'],
			['label'=>'Disco libre','value'=>number_format($diskFreeMB,0,',','.'),'icon'=>'chart-bar','unit'=>' MB'],
			['label'=>'Uso de disco','value'=>$diskUsedPct,'icon'=>'percent','unit'=>' %'],
		];

		return view('admin.monitoreo', compact(
			'cards',
			'errorsFatal',
			'errorsWarn',
			'trafficLabels','trafficDataError','trafficDataWarn',
			'serviceLabels','serviceValues', 'dbConnections', 'qpsMetrics'
		));
	}




	/**
	 * Calcula QPS (queries per second) para cada conexión MySQL/PGSQL.
	 *
	 * @return array [ 'mysql_conn_name' => float|'N/A', 'pgsql_conn_name' => float|'N/A', ... ]
	 */
	private function getQps()
	{
		$results   = [];
		$conns     = config('database.connections', []);

		foreach ($conns as $name => $cfg) {
			if (! in_array($cfg['driver'], ['mysql', 'pgsql'])) {
				continue;
			}

			try {
				$conn = DB::connection($name);

				if ($cfg['driver'] === 'mysql') {
					// Obtenemos total de consultas y uptime
					$status    = $conn->selectOne("SHOW GLOBAL STATUS WHERE Variable_name IN ('Questions','Uptime')");
					// Como SHOW GLOBAL STATUS devuelve filas separadas, consultamos por variable
					$questions = $conn->selectOne("SHOW GLOBAL STATUS LIKE 'Questions'")->Value ?? 0;
					$uptime    = $conn->selectOne("SHOW GLOBAL STATUS LIKE 'Uptime'")->Value    ?? 1;
					$qps       = $uptime > 0 ? round($questions / $uptime, 2) : 0;

				} else {
					// PostgreSQL: sum commits+rollbacks / uptime desde pg_postmaster_start_time()
					$row = $conn->selectOne("
						SELECT
							SUM(xact_commit + xact_rollback) AS total_q,
							EXTRACT(EPOCH FROM (NOW() - pg_postmaster_start_time())) AS up
						FROM pg_stat_database
					");
					$totalQ = $row->total_q ?? 0;
					$upSecs = $row->up     ?? 1;
					$qps    = $upSecs > 0 ? round($totalQ / $upSecs, 2) : 0;
				}

				$results[$name] = $qps;
			} catch (\Exception $e) {
				$results[$name] = 'N/A';
			}
		}

		return $results;
	}




	/**
	 * Devuelve el número de conexiones activas para cada conexión MySQL/PGSQL configurada.
	 *
	 * @return array [ 'mysql_conn_name' => int|'N/A', 'pgsql_conn_name' => int|'N/A', ... ]
	 */
	private function getActiveDbConnections()
	{
		$counts      = [];
		$allConns    = config('database.connections', []);
		foreach ($allConns as $name => $cfg) {
			if (! in_array($cfg['driver'], ['mysql', 'pgsql'])) {
				continue;
			}
			try {
				$conn = DB::connection($name);
				if ($cfg['driver'] === 'mysql') {
					// Para MySQL usamos Threads_connected
					$res   = $conn->select("SHOW STATUS WHERE variable_name = 'Threads_connected'");
					$count = isset($res[0]->Value) ? (int) $res[0]->Value : 0;
				} else {
					// Para PostgreSQL contamos pg_stat_activity
					$res   = $conn->select("SELECT COUNT(*) AS count FROM pg_stat_activity");
					$count = isset($res[0]->count) ? (int) $res[0]->count : 0;
				}
				$counts[$name] = $count;
			} catch (\Exception $e) {
				$counts[$name] = 'N/A';
			}
		}
		return $counts;
	}




	/**
	 * Obtiene info de disco en MB y porcentaje usado.
	 *
	 * @return array [totalMB, usedMB, freeMB, usedPercent]
	*/
	private function getDiskInfo()
	{
		// ruta al disco principal, ajustar si es necesario
		$path = '/';
		$total = disk_total_space($path);
		$free  = disk_free_space($path);
		if ($total === false || $free === false) {
			return ['N/A','N/A','N/A','N/A'];
		}
		$used = $total - $free;
		// convertir a MB y redondear
		$totalMB = round($total / 1024 / 1024, 2);
		$usedMB  = round($used  / 1024 / 1024, 2);
		$freeMB  = round($free  / 1024 / 1024, 2);
		$usedPct = $total > 0 ? round($used / $total * 100, 2) : 'N/A';

		return [$totalMB, $usedMB, $freeMB, $usedPct];
	}

	public function metric($metric)
	{
		switch ($metric) {
			case 'usuarios_total': return User::count();
			case 'usuarios_hoy':   return User::whereDate('created_at', now()->toDateString())->count();
			case 'carga_cpu':      return $this->getCpuUsagePercent();
			default: return null;
		}
	}

	private function getCpuUsagePercent()
	{
		if (PHP_OS_FAMILY === 'Linux') {
			if (!file_exists('/proc/loadavg')) return 'N/A';
			$load = file_get_contents('/proc/loadavg');
			$cores = (int)shell_exec('nproc');
			$one = (float)explode(' ', $load)[0];
			return $cores>0? round($one/$cores*100, 2) : 'N/A';
		}
		// Windows y otros
		$output = @shell_exec('wmic cpu get LoadPercentage /value');
		if (preg_match('/LoadPercentage=(\d+)/', $output, $m)) return (float)$m[1];
		return 'N/A';
	}

	private function getMemoryInfo()
	{
		if (PHP_OS_FAMILY === 'Linux') {
			$info = file_get_contents('/proc/meminfo');
			preg_match('/MemTotal:\s+(\d+)/', $info, $t);
			preg_match('/MemAvailable:\s+(\d+)/', $info, $a);
			if (isset($t[1])&&isset($a[1])){
				$total = round($t[1]/1024);
				$used = round(($t[1]-$a[1])/1024);
				return [$total, $used];
			}
		}
		// Windows
		$out = @shell_exec('wmic OS get TotalVisibleMemorySize,FreePhysicalMemory /value');
		preg_match('/TotalVisibleMemorySize=(\d+)/',$out,$t);
		preg_match('/FreePhysicalMemory=(\d+)/',$out,$f);
		if(isset($t[1])&&isset($f[1])){
			$total=round($t[1]/1024);
			$used=round(($t[1]-$f[1])/1024);
			return [$total,$used];
		}
		return ['N/A','N/A'];
	}
}
