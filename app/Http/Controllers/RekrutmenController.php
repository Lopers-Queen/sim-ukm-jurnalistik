<?php

namespace App\Http\Controllers;

use App\Models\Rekrutmen;
use App\Models\PeriodeKepengurusan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RekrutmenController extends Controller
{
    public function index(): View
    {
        $this->authorize('rekrutmen.view');

        $rekrutmen = Rekrutmen::with('periode')->latest('tanggal_buka')->paginate(15);
        return view('rekrutmen.index', compact('rekrutmen'));
    }

    public function create(): View
    {
        $this->authorize('rekrutmen.create');

        $periodes = PeriodeKepengurusan::orderByDesc('tahun_mulai')->get();
        return view('rekrutmen.create', compact('periodes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('rekrutmen.create');

        $data = $request->validate([
            'nama_rekrutmen' => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'periode_id'     => 'required|exists:periode_kepengurusan,id',
            'tanggal_buka'   => 'required|date',
            'tanggal_tutup'  => 'required|date|after:tanggal_buka',
            'status'         => 'required|in:draft,dibuka,ditutup,selesai',
            'kuota'          => 'nullable|integer|min:1',
            'persyaratan'    => 'nullable|string',
        ]);
        Rekrutmen::create($data);
        return redirect()->route('rekrutmen.index')->with('success', 'Rekrutmen berhasil dibuat.');
    }

    public function show(Rekrutmen $rekrutmen): View
    {
        $this->authorize('rekrutmen.view');

        $rekrutmen->load('periode');
        return view('rekrutmen.show', compact('rekrutmen'));
    }

    public function edit(Rekrutmen $rekrutmen): View
    {
        $this->authorize('rekrutmen.edit');

        $periodes = PeriodeKepengurusan::orderByDesc('tahun_mulai')->get();
        return view('rekrutmen.edit', compact('rekrutmen', 'periodes'));
    }

    public function update(Request $request, Rekrutmen $rekrutmen): RedirectResponse
    {
        $this->authorize('rekrutmen.edit');

        $data = $request->validate([
            'nama_rekrutmen' => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'periode_id'     => 'required|exists:periode_kepengurusan,id',
            'tanggal_buka'   => 'required|date',
            'tanggal_tutup'  => 'required|date|after:tanggal_buka',
            'status'         => 'required|in:draft,dibuka,ditutup,selesai',
            'kuota'          => 'nullable|integer|min:1',
            'persyaratan'    => 'nullable|string',
        ]);
        $rekrutmen->update($data);
        return redirect()->route('rekrutmen.index')->with('success', 'Rekrutmen berhasil diperbarui.');
    }

    public function destroy(Rekrutmen $rekrutmen): RedirectResponse
    {
        $this->authorize('rekrutmen.delete');

        $rekrutmen->delete();
        return redirect()->route('rekrutmen.index')->with('success', 'Rekrutmen berhasil dihapus.');
    }
}
