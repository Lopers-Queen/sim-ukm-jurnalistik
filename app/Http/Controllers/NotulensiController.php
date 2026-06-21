<?php

namespace App\Http\Controllers;

use App\Models\Notulensi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controller Notulensi (FR-04)
 */
class NotulensiController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('notulensi.view');

        $query = Notulensi::with('pencatat');
        if ($search = $request->input('search')) {
            $query->where('judul', 'like', "%{$search}%");
        }
        if ($jenis = $request->input('jenis')) {
            $query->where('jenis_rapat', $jenis);
        }
        $notulensi = $query->latest('tanggal_rapat')->paginate(15)->withQueryString();
        return view('notulensi.index', compact('notulensi'));
    }

    public function create(): View { $this->authorize('notulensi.create'); return view('notulensi.create'); }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('notulensi.create');

        $data = $request->validate([
            'judul'         => 'required|string|max:255',
            'tanggal_rapat' => 'required|date',
            'lokasi'        => 'nullable|string|max:150',
            'jenis_rapat'   => 'required|in:rapat_rutin,rapat_khusus,rapat_evaluasi,rapat_kerja,rapat_pleno',
            'isi_notulensi' => 'required|string',
            'daftar_hadir'  => 'nullable|array',
        ]);
        $data['pencatat_id'] = Auth::id();
        Notulensi::create($data);
        return redirect()->route('notulensi.index')->with('success', 'Notulensi berhasil disimpan.');
    }

    public function show(Notulensi $notulensi): View
    {
        $this->authorize('notulensi.view');

        $notulensi->load('pencatat');
        return view('notulensi.show', compact('notulensi'));
    }

    public function edit(Notulensi $notulensi): View { $this->authorize('notulensi.edit'); return view('notulensi.edit', compact('notulensi')); }

    public function update(Request $request, Notulensi $notulensi): RedirectResponse
    {
        $this->authorize('notulensi.edit');

        $data = $request->validate([
            'judul'         => 'required|string|max:255',
            'tanggal_rapat' => 'required|date',
            'lokasi'        => 'nullable|string|max:150',
            'jenis_rapat'   => 'required|in:rapat_rutin,rapat_khusus,rapat_evaluasi,rapat_kerja,rapat_pleno',
            'isi_notulensi' => 'required|string',
            'daftar_hadir'  => 'nullable|array',
        ]);
        $notulensi->update($data);
        return redirect()->route('notulensi.index')->with('success', 'Notulensi berhasil diperbarui.');
    }

    public function destroy(Notulensi $notulensi): RedirectResponse
    {
        $this->authorize('notulensi.delete');

        $notulensi->delete();
        return redirect()->route('notulensi.index')->with('success', 'Notulensi berhasil dihapus.');
    }
}
