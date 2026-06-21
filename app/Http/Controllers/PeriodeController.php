<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeriodeRequest;
use App\Http\Requests\UpdatePeriodeRequest;
use App\Models\PeriodeKepengurusan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Controller Periode Kepengurusan (FR-13)
 */
class PeriodeController extends Controller
{
    public function index(): View
    {
        $this->authorize('periode.view');

        $periodes = PeriodeKepengurusan::withCount(['riwayatKepengurusan', 'events'])
            ->orderByDesc('tahun_mulai')
            ->paginate(15);

        return view('periode.index', compact('periodes'));
    }

    public function create(): View
    {
        $this->authorize('periode.create');

        return view('periode.create');
    }

    public function store(StorePeriodeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Jika status aktif, nonaktifkan yang lain
        if ($data['status'] === 'aktif') {
            PeriodeKepengurusan::where('status', 'aktif')->update(['status' => 'selesai']);
        }

        PeriodeKepengurusan::create($data);

        return redirect()->route('periode.index')
            ->with('success', 'Periode kepengurusan berhasil ditambahkan.');
    }

    public function show(PeriodeKepengurusan $periode): View
    {
        $this->authorize('periode.view');

        $periode->load(['riwayatKepengurusan.anggota', 'events', 'rekrutmen', 'anggaranDivisi']);

        return view('periode.show', compact('periode'));
    }

    public function edit(PeriodeKepengurusan $periode): View
    {
        $this->authorize('periode.edit');

        return view('periode.edit', compact('periode'));
    }

    public function update(UpdatePeriodeRequest $request, PeriodeKepengurusan $periode): RedirectResponse
    {
        $data = $request->validated();

        if ($data['status'] === 'aktif') {
            PeriodeKepengurusan::where('status', 'aktif')
                ->where('id', '!=', $periode->id)
                ->update(['status' => 'selesai']);
        }

        $periode->update($data);

        return redirect()->route('periode.index')
            ->with('success', 'Periode kepengurusan berhasil diperbarui.');
    }

    public function destroy(PeriodeKepengurusan $periode): RedirectResponse
    {
        $this->authorize('periode.delete');

        $periode->delete();

        return redirect()->route('periode.index')
            ->with('success', 'Periode kepengurusan berhasil dihapus.');
    }
}
