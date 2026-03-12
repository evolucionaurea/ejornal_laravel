<?php

namespace App\Http\Controllers;

use App\ClienteUser;
use App\Rol;
use App\User;
use App\UserMatricula;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class EmpleadosCuentaController extends Controller
{
    public function index()
    {
        $roles = Rol::all();

        $clientes = ClienteUser::join('clientes', 'cliente_user.id_cliente', '=', 'clientes.id')
            ->where('cliente_user.id_user', auth()->id())
            ->select('clientes.nombre', 'clientes.id')
            ->get();

        $user = auth()->user();

        $tipos = $this->tiposMatriculaDisponibles();
        $labels = $this->labelsMatricula();

        $matriculasPorTipo = UserMatricula::where('id_user', $user->id)
            ->get()
            ->keyBy('tipo');

        $matriculas_form = [];

        foreach ($tipos as $tipo) {
            $m = $matriculasPorTipo->get($tipo);

            $matriculas_form[] = [
                'id' => $m ? $m->id : null,
                'tipo' => $tipo,
                'label' => $labels[$tipo] ?? $tipo,
                'nro' => (string) old("matricula_nro.$tipo", $m ? (string) $m->nro : ''),
                'fecha_vencimiento' => (string) old(
                    "matricula_vencimiento.$tipo",
                    ($m && $m->fecha_vencimiento) ? $m->fecha_vencimiento->format('Y-m-d') : ''
                ),
                'archivo_frente' => $m ? $m->archivo_frente : null,
                'archivo_dorso' => $m ? $m->archivo_dorso : null,
            ];
        }

        return view('empleados.cuenta', compact('roles', 'clientes', 'matriculas_form'));
    }

    public function store(Request $request)
    {
        if (!$request->filled('id_user')) {
            return back()->with(
                'error',
                'La carga supera el límite permitido. Máximo 2MB por archivo. Subí menos archivos por vez.'
            );
        }

        $user = User::findOrFail($request->id_user);

        if ((int) $user->id !== (int) auth()->id()) {
            abort(403);
        }

        $request->validate($this->documentacionRules(), $this->documentacionMessages());

        $user->dni = $request->dni;
        $user->sello_linea_1 = $request->filled('sello_linea_1') ? $request->sello_linea_1 : null;
        $user->sello_linea_2 = $request->filled('sello_linea_2') ? $request->sello_linea_2 : null;
        $user->sello_linea_3 = $request->filled('sello_linea_3') ? $request->sello_linea_3 : null;

        $this->upsertMatriculas($user, $request);
        $this->syncLegacyMatriculaFields($user);

        $this->syncUserDocument($user, $request, 'archivo_dni', 'archivo_dni', 'hash_dni', 'dni');
        $this->syncUserDocument($user, $request, 'archivo_dni_detras', 'archivo_dni_detras', 'hash_dni_detras', 'dni');
        $this->syncUserDocument($user, $request, 'archivo_titulo', 'titulo', 'hash_titulo', 'titulos');
        $this->syncUserDocument($user, $request, 'archivo_titulo_detras', 'archivo_titulo_detras', 'hash_titulo_detras', 'titulos');
        $this->syncUserDocument($user, $request, 'firma_medico', 'firma_medico', 'hash_firma_medico', 'firmas_medico');

        $user->save();

        return back()->with('success', 'Datos cambiados correctamente');
    }

    public function cambiar_pass(Request $request)
    {
        $caracteres = strlen((string) $request->password);

        if ($request->password == '' || $request->password == null || $caracteres < 6) {
            return back()->with('error', 'La contraseña no puede estar vacía ni tener menos de 6 caracteres');
        }

        if ($request->password != $request->cpassword) {
            return back()->with('error', 'No conciden la contraseñas');
        }

        $user = User::findOrFail($request->id_user);

        if ((int) $user->id !== (int) auth()->id()) {
            abort(403);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return back()->with('success', 'Contraseña cambiada correctamente');
    }

    public function downloadTitulo($id)
    {
        return $this->downloadUserDocument(
            $id,
            auth()->user()->hash_titulo,
            'titulos',
            auth()->user()->titulo,
            'No hay archivo de título adjunto.',
            'El archivo de título no existe en el servidor.'
        );
    }

    public function downloadDni($id)
    {
        return $this->downloadUserDocument(
            $id,
            auth()->user()->hash_dni,
            'dni',
            auth()->user()->archivo_dni,
            'No hay archivo de DNI adjunto.',
            'El archivo de DNI no existe en el servidor.'
        );
    }

    public function downloadTituloDetras($id)
    {
        return $this->downloadUserDocument(
            $id,
            auth()->user()->hash_titulo_detras,
            'titulos',
            auth()->user()->archivo_titulo_detras,
            'No hay archivo de dorso de título adjunto.',
            'El archivo de dorso de título no existe en el servidor.'
        );
    }

    public function downloadDniDetras($id)
    {
        return $this->downloadUserDocument(
            $id,
            auth()->user()->hash_dni_detras,
            'dni',
            auth()->user()->archivo_dni_detras,
            'No hay archivo de dorso de DNI adjunto.',
            'El archivo de dorso de DNI no existe en el servidor.'
        );
    }

    public function downloadMatricula($id)
    {
        $matricula = $this->findUserMatriculaForDownload($id);

        if (!$matricula) {
            abort(404);
        }

        return $this->downloadMatriculaDocument(
            $matricula,
            'frente',
            'No hay archivo de matrícula (frente) adjunto.',
            'El archivo de matrícula (frente) no existe en el servidor.'
        );
    }

    public function downloadMatriculaDetras($id)
    {
        $matricula = $this->findUserMatriculaForDownload($id);

        if (!$matricula) {
            abort(404);
        }

        return $this->downloadMatriculaDocument(
            $matricula,
            'dorso',
            'No hay archivo de matrícula (dorso) adjunto.',
            'El archivo de matrícula (dorso) no existe en el servidor.'
        );
    }

    private function tiposMatriculaDisponibles(): array
    {
        return ['MN', 'MP'];
    }

    private function labelsMatricula(): array
    {
        return [
            'MN' => 'nacional',
            'MP' => 'provincial',
        ];
    }

    private function documentacionRules(): array
    {
        return [
            'dni' => 'nullable|string|max:50',
            'sello_linea_1' => 'nullable|string|max:255',
            'sello_linea_2' => 'nullable|string|max:255',
            'sello_linea_3' => 'nullable|string|max:255',

            'archivo_dni' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'archivo_dni_detras' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'archivo_titulo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'archivo_titulo_detras' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'firma_medico' => 'nullable|file|mimes:png|max:2048',

            'matricula_nro' => 'nullable|array',
            'matricula_nro.*' => 'nullable|string|max:50',

            'matricula_vencimiento' => 'nullable|array',
            'matricula_vencimiento.*' => 'nullable|date_format:Y-m-d',

            'archivo_matricula_frente' => 'nullable|array',
            'archivo_matricula_frente.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

            'archivo_matricula_dorso' => 'nullable|array',
            'archivo_matricula_dorso.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }

    private function documentacionMessages(): array
    {
        return [
            'archivo_dni.max' => 'Máximo 2MB por archivo.',
            'archivo_dni_detras.max' => 'Máximo 2MB por archivo.',
            'archivo_titulo.max' => 'Máximo 2MB por archivo.',
            'archivo_titulo_detras.max' => 'Máximo 2MB por archivo.',
            'firma_medico.max' => 'Máximo 2MB por archivo.',
            'archivo_matricula_frente.*.max' => 'Máximo 2MB por archivo.',
            'archivo_matricula_dorso.*.max' => 'Máximo 2MB por archivo.',
        ];
    }

    private function upsertMatriculas(User $user, Request $request): void
    {
        $tipos = $this->tiposMatriculaDisponibles();

        $nros = $request->input('matricula_nro', []);
        $vencs = $request->input('matricula_vencimiento', []);

        if (!is_array($nros)) {
            $nros = [];
        }

        if (!is_array($vencs)) {
            $vencs = [];
        }

        foreach ($tipos as $tipo) {
            $tipo = strtoupper(trim((string) $tipo));

            $nro = isset($nros[$tipo]) ? trim((string) $nros[$tipo]) : '';
            $fechaVenc = isset($vencs[$tipo]) ? trim((string) $vencs[$tipo]) : '';

            if ($fechaVenc === '') {
                $fechaVenc = null;
            }

            $fileFrente = $request->file("archivo_matricula_frente.$tipo");
            $fileDorso = $request->file("archivo_matricula_dorso.$tipo");

            $hayArchivos = ($fileFrente && $fileFrente->isValid()) || ($fileDorso && $fileDorso->isValid());
            $hayDatos = ($nro !== '' || $fechaVenc !== null);

            $matricula = UserMatricula::firstOrNew([
                'id_user' => $user->id,
                'tipo' => $tipo,
            ]);

            if (!$hayDatos && !$hayArchivos && !$matricula->exists) {
                continue;
            }

            $matricula->nro = $nro !== '' ? $nro : null;
            $matricula->fecha_vencimiento = $fechaVenc;

            $this->syncMatriculaFile($matricula, $fileFrente, 'frente');
            $this->syncMatriculaFile($matricula, $fileDorso, 'dorso');

            $matricula->save();
        }
    }

    private function syncMatriculaFile(UserMatricula $matricula, ?UploadedFile $file, string $lado): void
    {
        if (!$file || !$file->isValid()) {
            return;
        }

        $lado = strtolower($lado);

        $hashField = $lado === 'dorso' ? 'hash_dorso' : 'hash_frente';
        $nameField = $lado === 'dorso' ? 'archivo_dorso' : 'archivo_frente';

        $dir = "users/{$matricula->id_user}/matriculas/{$matricula->tipo}";

        $matricula->{$nameField} = $file->getClientOriginalName();
        $matricula->{$hashField} = $this->replaceFileOnDisk(
            'public',
            $dir,
            $matricula->{$hashField},
            $file
        );
    }

    private function syncLegacyMatriculaFields(User $user): void
    {
        $matricula = UserMatricula::where('id_user', $user->id)
            ->where('tipo', 'MN')
            ->first();

        if (!$matricula) {
            $matricula = UserMatricula::where('id_user', $user->id)
                ->orderBy('id', 'asc')
                ->first();
        }

        if (!$matricula) {
            return;
        }

        $user->matricula = $matricula->nro;
        $user->fecha_vencimiento = $matricula->fecha_vencimiento;
        $user->archivo_matricula = $matricula->archivo_frente;
        $user->hash_matricula = $matricula->hash_frente;
        $user->archivo_matricula_detras = $matricula->archivo_dorso;
        $user->hash_matricula_detras = $matricula->hash_dorso;
    }

    private function syncUserDocument(
        User $user,
        Request $request,
        string $inputName,
        string $originalNameField,
        string $hashField,
        string $subDir
    ): void {
        if (!$request->hasFile($inputName)) {
            return;
        }

        $file = $request->file($inputName);

        if (!$file || !$file->isValid()) {
            return;
        }

        $dir = "users/{$user->id}/{$subDir}";

        $user->{$originalNameField} = $file->getClientOriginalName();
        $user->{$hashField} = $this->replaceFileOnDisk(
            'public',
            $dir,
            $user->{$hashField},
            $file
        );
    }

    private function replaceFileOnDisk(string $disk, string $dir, ?string $oldHash, UploadedFile $uploadedFile): string
    {
        if ($oldHash && Storage::disk($disk)->exists($dir . '/' . $oldHash)) {
            Storage::disk($disk)->delete($dir . '/' . $oldHash);
        }

        $newHash = $uploadedFile->hashName();

        Storage::disk($disk)->putFileAs($dir, $uploadedFile, $newHash);

        return $newHash;
    }

    private function findUserMatriculaForDownload($id): ?UserMatricula
    {
        $userId = auth()->id();

        $matricula = UserMatricula::where('id', $id)
            ->where('id_user', $userId)
            ->first();

        if ($matricula) {
            return $matricula;
        }

        return UserMatricula::where('id_user', $id)
            ->where('id_user', $userId)
            ->first();
    }

    private function downloadUserDocument(
        $requestedUserId,
        ?string $hash,
        string $grupo,
        ?string $downloadName,
        string $emptyMessage,
        string $notFoundMessage
    ) {
        if ((int) $requestedUserId !== (int) auth()->id()) {
            abort(403);
        }

        if (empty($hash)) {
            return back()->with('error', $emptyMessage);
        }

        $ruta = $this->resolveUserDocumentPath((int) $requestedUserId, $grupo, $hash);

        if (!$ruta) {
            return back()->with('error', $notFoundMessage);
        }

        return response()->download($ruta, $downloadName ?: basename($ruta));
    }

    private function downloadMatriculaDocument(
        UserMatricula $matricula,
        string $lado,
        string $emptyMessage,
        string $notFoundMessage
    ) {
        $lado = strtolower($lado);

        $hash = $lado === 'dorso' ? $matricula->hash_dorso : $matricula->hash_frente;
        $nombre = $lado === 'dorso' ? $matricula->archivo_dorso : $matricula->archivo_frente;

        if (empty($hash)) {
            return back()->with('error', $emptyMessage);
        }

        $ruta = $this->resolveUserDocumentPath(
            (int) $matricula->id_user,
            'matriculas',
            $hash,
            $matricula->tipo
        );

        if (!$ruta) {
            return back()->with('error', $notFoundMessage);
        }

        return response()->download($ruta, $nombre ?: basename($ruta));
    }

    private function resolveUserDocumentPath(int $userId, string $grupo, string $hash, ?string $tipo = null): ?string
    {
        if (!$hash) {
            return null;
        }

        $legacyRelative = null;
        $publicRelative = null;

        switch ($grupo) {
            case 'dni':
                $legacyRelative = "dni/user/{$userId}/{$hash}";
                $publicRelative = "users/{$userId}/dni/{$hash}";
                break;

            case 'titulos':
                $legacyRelative = "titulos/user/{$userId}/{$hash}";
                $publicRelative = "users/{$userId}/titulos/{$hash}";
                break;

            case 'matriculas':
                $legacyRelative = "matriculas/user/{$userId}/{$tipo}/{$hash}";
                $publicRelative = "users/{$userId}/matriculas/{$tipo}/{$hash}";
                break;

            case 'firmas_medico':
                $publicRelative = "users/{$userId}/firmas_medico/{$hash}";
                break;
        }

        return $this->firstExistingFile(array_filter([
            $legacyRelative ? storage_path('app/' . $legacyRelative) : null,
            $publicRelative ? $this->diskAbsolutePath('public', $publicRelative) : null,
            $publicRelative ? public_path('storage/' . $publicRelative) : null,
        ]));
    }

    private function diskAbsolutePath(string $disk, string $relativePath): string
    {
        $root = rtrim(config('filesystems.disks.' . $disk . '.root'), DIRECTORY_SEPARATOR);

        return $root . DIRECTORY_SEPARATOR . ltrim($relativePath, DIRECTORY_SEPARATOR);
    }

    private function firstExistingFile(array $paths): ?string
    {
        foreach ($paths as $path) {
            if ($path && is_file($path)) {
                return $path;
            }
        }

        return null;
    }
}