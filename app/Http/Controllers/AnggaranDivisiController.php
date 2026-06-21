<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnggaranDivisiRequest;
use App\Http\Requests\UpdateAnggaranDivisiRequest;
use App\Models\AnggaranUkmDivisi;
use App\Models\PeriodeKepengurusan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnggaranDivisiController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('anggaran-divisi.view');

        $query = AnggaranUkmDivisi::with('periode');
        if ($divisi = $request->input('divisi')) {
            $query->where('divisi', $divisi);
        }
        if ($periodeId = $request->input('periode_id')) {
            $query->where('periode_id', $periodeId);
        }
        $anggaran = $query->orderByDesc('tahun')->orderByDesc('bulan')->paginate(15)->withQueryString();
        $periodes = PeriodeKepengurusan::orderByDesc('tahun_mulai')->get();

        return view('anggaran-divisi.index', compact('anggaran', 'periodes'));
    }

    public function create(): View
    {
        $this->authorize('anggaran-divisi.create');

        $periodes = PeriodeKepengurusan::orderByDesc('tahun_mulai')->get();

        return view('anggaran-divisi.create', compact('periodes'));
    }

    public function store(StoreAnggaranDivisiRequest $request): RedirectResponse
    {
        AnggaranUkmDivisi::create($request->validated());

        return redirect()->route('anggaran-divisi.index')->with('success', 'Anggaran divisi berhasil ditambahkan.');
    }

    public function edit(AnggaranUkmDivisi $anggaranDivisi): View
    {
        $this->authorize('anggaran-divisi.edit');

        $periodes = PeriodeKepengurusan::orderByDesc('tahun_mulai')->get();

        return view('anggaran-divisi.edit', compact('anggaranDivisi', 'periodes'));
    }

    public function update(UpdateAnggaranDivisiRequest $request, AnggaranUkmDivisi $anggaranDivisi): RedirectResponse
    {
        $anggaranDivisi->update($request->validated());

        return redirect()->route('anggaran-divisi.index')->with('success', 'Anggaran divisi berhasil diperbarui.');
    }

    public function destroy(AnggaranUkmDivisi $anggaranDivisi): RedirectResponse
    {
        $this->authorize('anggaran-divisi.delete');

        $anggaranDivisi->delete();

        return redirect()->route('anggaran-divisi.index')->with('success', 'Anggaran divisi berhasil dihapus.');
    }
}
