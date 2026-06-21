<?php

namespace App\Http\Controllers;

use App\Helpers\JabatanOrder;
use App\Http\Requests\StoreJadwalShiftRequest;
use App\Http\Requests\UpdateJadwalShiftRequest;
use App\Models\Anggota;
use App\Models\JadwalShift;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller Jadwal Piket (FR-06)
 * Jadwal piket diacak dari seluruh anggota aktif UKM Jurnalistik.
 * Lokasi tetap: Sekretariat UKM Jurnalistik.
 */
class JadwalShiftController extends Controller
{
    private const HARI_ORDER = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

    public function index(Request $request): View
    {
        $this->authorize('jadwal-shift.view');

        $query = JadwalShift::with('anggota');

        if ($hari = $request->input('hari')) {
            $query->where('hari', $hari);
        }
        if ($search = $request->input('search')) {
            $query->whereHas('anggota', fn ($q) => $q->where('nama_lengkap', 'like', "%{$search}%"));
        }

        JabatanOrder::apply($query, 'hari', self::HARI_ORDER);

        $jadwal = $query->paginate(20)->withQueryString();

        return view('jadwal.index', compact('jadwal'));
    }

    public function create(): View
    {
        $this->authorize('jadwal-shift.create');

        return view('jadwal.create');
    }

    public function store(StoreJadwalShiftRequest $request): RedirectResponse
    {
        JadwalShift::create($request->validated());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal piket berhasil ditambahkan.');
    }

    public function edit(JadwalShift $jadwal): View
    {
        $this->authorize('jadwal-shift.edit');

        return view('jadwal.edit', compact('jadwal'));
    }

    public function update(UpdateJadwalShiftRequest $request, JadwalShift $jadwal): RedirectResponse
    {
        $jadwal->update($request->validated());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal piket berhasil diperbarui.');
    }

    public function destroy(JadwalShift $jadwal): RedirectResponse
    {
        $this->authorize('jadwal-shift.delete');

        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal piket berhasil dihapus.');
    }

    /**
     * Generate jadwal piket acak dari seluruh anggota aktif.
     */
    public function generateAcak(Request $request): RedirectResponse
    {
        $this->authorize('jadwal-shift.create');

        $request->validate([
            'hari'   => 'required|array|min:1',
            'hari.*' => 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
        ]);

        // Ambil semua anggota aktif (bukan admin)
        $anggotaAktif = Anggota::nonAdmin()->aktif()->get();

        if ($anggotaAktif->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada anggota aktif untuk dijadwalkan.');
        }

        // Hapus jadwal lama (soft delete)
        JadwalShift::query()->delete();

        // Hitung otomatis: total anggota dibagi jumlah hari
        $shuffled = $anggotaAktif->shuffle();
        $total = $shuffled->count();
        $jumlahHari = count($request->hari);
        $perHari = (int) ceil($total / $jumlahHari);

        $index = 0;
        foreach ($request->hari as $hari) {
            for ($i = 0; $i < $perHari && $index < $total; $i++) {
                JadwalShift::create([
                    'anggota_id' => $shuffled[$index]->id,
                    'hari'       => $hari,
                ]);
                $index++;
            }
        }

        return redirect()->route('jadwal.index')
            ->with('success', "Jadwal piket berhasil di-generate! {$total} anggota dibagi ke {$jumlahHari} hari ({$perHari} orang/hari).");
    }
}
