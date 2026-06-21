<?php

namespace App\Http\Controllers;

use App\Enums\Jabatan;
use App\Exports\AnggotaTemplateExport;
use App\Helpers\JabatanOrder;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\StoreAnggotaRequest;
use App\Http\Requests\UpdateAnggotaRequest;
use App\Imports\AnggotaImport;
use App\Models\Anggota;
use App\Services\AnggotaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Controller Manajemen Anggota (FR-02)
 * CRUD lengkap untuk data anggota UKM.
 */
class AnggotaController extends Controller
{
    protected AnggotaService $anggotaService;

    public function __construct(AnggotaService $anggotaService)
    {
        $this->anggotaService = $anggotaService;
    }

    /**
     * Daftar semua anggota dengan filter & search.
     */
    public function index(Request $request): View
    {
        $this->authorize('organisasi.view');

        $query = Anggota::nonAdmin()
            ->search($request->input('search'))
            ->when($request->input('divisi'), fn ($q, $divisi) => $q->divisi($divisi))
            ->when($request->input('status'), fn ($q, $status) => $q->where('status_keanggotaan', $status))
            ->when($request->input('jabatan'), fn ($q, $jabatan) => $q->where('jabatan_struktural', $jabatan));

        // Urut berdasarkan hierarki jabatan sesuai Struktur Organisasi
        JabatanOrder::apply($query, 'jabatan_struktural', Jabatan::HIERARCHY_ORDER);

        $anggota = $query->orderBy('nama_lengkap')
            ->paginate(15)
            ->withQueryString();

        return view('anggota.index', compact('anggota'));
    }

    /**
     * Form tambah anggota baru.
     */
    public function create(): View
    {
        $this->authorize('organisasi.create');

        return view('anggota.create');
    }

    /**
     * Simpan anggota baru.
     * Auto-generate password dari tanggal lahir (DDMMYYYY).
     * Auto-set is_first_login = TRUE.
     */
    public function store(StoreAnggotaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Auto-generate password default (DDMMYYYY)
        $data = $this->anggotaService->prepareForCreation($data);

        // Upload foto profil
        if ($request->hasFile('foto_profil')) {
            $data['foto_profil'] = $request->file('foto_profil')->store('foto-profil', 'public');
        }

        $anggota = Anggota::create($data);

        // Auto-assign role berdasarkan jabatan
        $this->anggotaService->assignRoleByJabatan($anggota);

        return redirect()->route('anggota.index')
            ->with('success', "Anggota {$anggota->nama_lengkap} berhasil ditambahkan.");
    }

    /**
     * Detail anggota.
     */
    public function show(Anggota $anggotum): View
    {
        $this->authorize('organisasi.view');

        $anggotum->load(['riwayatKepengurusan.periode', 'kepanitiaan.event', 'loginHistories']);

        return view('anggota.show', ['anggota' => $anggotum]);
    }

    /**
     * Form edit anggota.
     */
    public function edit(Anggota $anggotum): View
    {
        $this->authorize('organisasi.edit');

        return view('anggota.edit', ['anggota' => $anggotum]);
    }

    /**
     * Update anggota.
     */
    public function update(UpdateAnggotaRequest $request, Anggota $anggotum): RedirectResponse
    {
        $data = $request->validated();

        // Upload foto profil baru
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama
            if ($anggotum->foto_profil) {
                Storage::disk('public')->delete($anggotum->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('foto-profil', 'public');
        }

        $anggotum->update($data);

        // Re-assign role jika jabatan berubah
        if (isset($data['jabatan_struktural'])) {
            $this->anggotaService->assignRoleByJabatan($anggotum);
        }

        return redirect()->route('anggota.index')
            ->with('success', "Data {$anggotum->nama_lengkap} berhasil diperbarui.");
    }

    /**
     * Soft delete anggota.
     */
    public function destroy(Anggota $anggotum): RedirectResponse
    {
        $this->authorize('organisasi.delete');

        $nama = $anggotum->nama_lengkap;
        $anggotum->delete();

        return redirect()->route('anggota.index')
            ->with('success', "Anggota {$nama} berhasil dihapus.");
    }

    /**
     * Reset password anggota ke password custom atau default (12345678).
     * Hanya bisa dilakukan oleh Super Admin.
     */
    public function resetPassword(ResetPasswordRequest $request, Anggota $anggota): RedirectResponse
    {
        $password = $request->getPasswordValue();

        $this->anggotaService->resetPassword($anggota, $password);

        $displayPw = $password === '12345678' ? '12345678 (default)' : 'custom';

        return redirect()->back()
            ->with('success', "Password {$anggota->nama_lengkap} berhasil direset ke: {$displayPw}");
    }

    /**
     * Bulk reset semua password anggota ke password custom atau default (12345678).
     * Hanya bisa dilakukan oleh Super Admin.
     */
    public function resetAllPasswords(ResetPasswordRequest $request): RedirectResponse
    {
        $password = $request->getPasswordValue();

        // Exclude admin account
        $anggotas = Anggota::nonAdmin()->get();

        $count = $this->anggotaService->resetAllPasswords($anggotas, $password);

        $displayPw = $password === '12345678' ? '12345678 (default)' : 'custom';

        return redirect()->back()
            ->with('success', "Password {$count} anggota berhasil direset ke: {$displayPw}");
    }

    /**
     * Import anggota dari file Excel/CSV.
     */
    public function importExcel(Request $request): RedirectResponse
    {
        $this->authorize('organisasi.create');

        $request->validate([
            'file_import' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file_import.required' => 'File import wajib diunggah.',
            'file_import.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file_import.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $import = new AnggotaImport();

        try {
            Excel::import($import, $request->file('file_import'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membaca file: ' . $e->getMessage())
                ->withInput();
        }

        // Build result message
        $messages = [];
        if ($import->successCount > 0) {
            $messages[] = "{$import->successCount} anggota berhasil diimport.";
        }
        if ($import->skipCount > 0) {
            $messages[] = "{$import->skipCount} baris dilewati.";
        }

        $flashKey = $import->successCount > 0 ? 'success' : 'warning';
        $flashMessage = implode(' ', $messages);

        return redirect()->route('anggota.index')
            ->with($flashKey, $flashMessage)
            ->with('import_errors', $import->errors);
    }

    /**
     * Download template Excel untuk import anggota.
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        $this->authorize('organisasi.create');

        return Excel::download(new AnggotaTemplateExport(), 'template_import_anggota.xlsx');
    }
}
