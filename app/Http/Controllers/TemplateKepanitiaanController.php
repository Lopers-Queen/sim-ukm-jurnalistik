<?php

namespace App\Http\Controllers;

use App\Models\TemplateKepanitiaan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller Template Kepanitiaan (FR-19)
 * Buat & kelola template struktur kepanitiaan reusable.
 */
class TemplateKepanitiaanController extends Controller
{
    public function index(): View
    {
        $this->authorize('template-panitia.view');

        $templates = TemplateKepanitiaan::latest()->paginate(15);

        return view('template-kepanitiaan.index', compact('templates'));
    }

    public function create(): View
    {
        $this->authorize('template-panitia.create');

        return view('template-kepanitiaan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('template-panitia.create');

        $data = $request->validate([
            'nama_template' => 'required|string|max:100',
            'deskripsi'     => 'nullable|string',
            'divisi_panitia'       => 'required|array|min:1',
            'divisi_panitia.*.nama' => 'required|string|max:100',
            'divisi_panitia.*.deskripsi' => 'nullable|string',
            'divisi_panitia.*.estimasi_anggota' => 'nullable|integer|min:1',
        ]);

        $template = TemplateKepanitiaan::create([
            'nama_template' => $data['nama_template'],
            'deskripsi'     => $data['deskripsi'] ?? null,
            'struktur'      => $data['divisi_panitia'],
            'is_active'     => true,
        ]);

        return redirect()->route('template-kepanitiaan.show', $template)
            ->with('success', "Template \"{$template->nama_template}\" berhasil dibuat.");
    }

    public function show(TemplateKepanitiaan $templateKepanitiaan): View
    {
        $this->authorize('template-panitia.view');

        return view('template-kepanitiaan.show', ['template' => $templateKepanitiaan]);
    }

    public function edit(TemplateKepanitiaan $templateKepanitiaan): View
    {
        $this->authorize('template-panitia.edit');

        return view('template-kepanitiaan.create', ['template' => $templateKepanitiaan]);
    }

    public function update(Request $request, TemplateKepanitiaan $templateKepanitiaan): RedirectResponse
    {
        $this->authorize('template-panitia.edit');

        $data = $request->validate([
            'nama_template' => 'required|string|max:100',
            'deskripsi'     => 'nullable|string',
            'divisi_panitia'       => 'required|array|min:1',
            'divisi_panitia.*.nama' => 'required|string|max:100',
            'divisi_panitia.*.deskripsi' => 'nullable|string',
            'divisi_panitia.*.estimasi_anggota' => 'nullable|integer|min:1',
            'is_active'     => 'nullable|boolean',
        ]);

        $templateKepanitiaan->update([
            'nama_template' => $data['nama_template'],
            'deskripsi'     => $data['deskripsi'] ?? null,
            'struktur'      => $data['divisi_panitia'],
            'is_active'     => $data['is_active'] ?? $templateKepanitiaan->is_active,
        ]);

        return redirect()->route('template-kepanitiaan.show', $templateKepanitiaan)
            ->with('success', "Template berhasil diperbarui.");
    }

    public function destroy(TemplateKepanitiaan $templateKepanitiaan): RedirectResponse
    {
        $this->authorize('template-panitia.delete');

        $nama = $templateKepanitiaan->nama_template;
        $templateKepanitiaan->delete();

        return redirect()->route('template-kepanitiaan.index')
            ->with('success', "Template \"{$nama}\" berhasil dihapus.");
    }

    /**
     * Duplikasi template untuk modifikasi.
     */
    public function duplicate(TemplateKepanitiaan $templateKepanitiaan): RedirectResponse
    {
        $this->authorize('template-panitia.create');

        $newTemplate = $templateKepanitiaan->replicate();
        $newTemplate->nama_template = $templateKepanitiaan->nama_template . ' (Salinan)';
        $newTemplate->save();

        return redirect()->route('template-kepanitiaan.edit', $newTemplate)
            ->with('success', "Template berhasil diduplikasi. Silakan edit sesuai kebutuhan.");
    }
}
