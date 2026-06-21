<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Anggota;
use App\Models\AnggotaPanitia;
use App\Models\Event;
use App\Models\PeriodeKepengurusan;
use App\Models\TemplateKepanitiaan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller Event (FR-09) + Kepanitiaan (FR-14)
 */
class EventController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('event.view');

        $query = Event::with(['pic', 'periode']);

        if ($search = $request->input('search')) {
            $query->where('nama_event', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $events = $query->latest()->paginate(15)->withQueryString();

        return view('event.index', compact('events'));
    }

    public function create(): View
    {
        $this->authorize('event.create');

        $periodes = PeriodeKepengurusan::orderByDesc('tahun_mulai')->get();
        $anggotaList = Anggota::nonAdmin()->aktif()->orderBy('nama_lengkap')->get();
        $templates = TemplateKepanitiaan::active()->orderBy('nama_template')->get();

        return view('event.create', compact('periodes', 'anggotaList', 'templates'));
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Remove template_id from data (it's not an Event column)
        $templateId = $data['template_id'] ?? null;
        unset($data['template_id']);

        $event = Event::create($data);

        // Auto-populate divisi dari template jika dipilih
        if ($templateId) {
            $template = TemplateKepanitiaan::find($templateId);
            if ($template && $template->struktur) {
                $struktur = is_array($template->struktur) ? $template->struktur : (json_decode($template->struktur, true) ?? []);
                foreach ($struktur as $divisi) {
                    $event->divisiPanitia()->create([
                        'nama_divisi' => $divisi['nama'] ?? 'Divisi',
                        'deskripsi'   => $divisi['deskripsi'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('event.show', $event)
            ->with('success', "Event \"{$event->nama_event}\" berhasil dibuat.");
    }

    public function show(Event $event): View
    {
        $this->authorize('event.view');

        $event->load(['pic', 'periode', 'divisiPanitia.anggotaPanitia.anggota', 'anggaranEvent', 'laporanPascaEvent']);

        return view('event.show', compact('event'));
    }

    public function edit(Event $event): View
    {
        $this->authorize('event.edit');

        $periodes = PeriodeKepengurusan::orderByDesc('tahun_mulai')->get();
        $anggotaList = Anggota::nonAdmin()->aktif()->orderBy('nama_lengkap')->get();
        $templates = TemplateKepanitiaan::active()->orderBy('nama_template')->get();

        return view('event.edit', compact('event', 'periodes', 'anggotaList', 'templates'));
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $event->update($request->validated());

        return redirect()->route('event.show', $event)
            ->with('success', "Event \"{$event->nama_event}\" berhasil diperbarui.");
    }

    public function destroy(Event $event): RedirectResponse
    {
        $this->authorize('event.delete');

        $nama = $event->nama_event;
        $event->delete();

        return redirect()->route('event.index')
            ->with('success', "Event \"{$nama}\" berhasil dihapus.");
    }

    // ── Kepanitiaan (FR-14) ──────────────────

    /**
     * Tambah divisi panitia ke event.
     */
    public function addDivisiPanitia(Request $request, Event $event): RedirectResponse
    {
        $this->authorize('kepanitiaan.create');

        $request->validate([
            'nama_divisi' => 'required|string|max:100',
            'deskripsi'   => 'nullable|string',
        ]);

        $event->divisiPanitia()->create($request->only('nama_divisi', 'deskripsi'));

        return redirect()->route('event.show', $event)
            ->with('success', 'Divisi panitia berhasil ditambahkan.');
    }

    /**
     * Assign anggota ke panitia event.
     */
    public function assignPanitia(Request $request, Event $event): RedirectResponse
    {
        $this->authorize('kepanitiaan.assign');

        $request->validate([
            'anggota_id'        => 'required|exists:anggota,id',
            'divisi_panitia_id' => 'required|exists:divisi_panitia,id',
            'jabatan_panitia'   => 'required|string|max:100',
        ]);

        // Cek unique constraint
        $exists = AnggotaPanitia::where('event_id', $event->id)
            ->where('anggota_id', $request->anggota_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Anggota sudah terdaftar di panitia event ini.');
        }

        AnggotaPanitia::create([
            'event_id'          => $event->id,
            'anggota_id'        => $request->anggota_id,
            'divisi_panitia_id' => $request->divisi_panitia_id,
            'jabatan_panitia'   => $request->jabatan_panitia,
        ]);

        return redirect()->route('event.show', $event)
            ->with('success', 'Anggota berhasil di-assign ke panitia.');
    }

    /**
     * Hapus anggota dari panitia.
     */
    public function removePanitia(Event $event, AnggotaPanitia $panitia): RedirectResponse
    {
        $this->authorize('kepanitiaan.delete');

        $panitia->delete();

        return redirect()->route('event.show', $event)
            ->with('success', 'Anggota berhasil dihapus dari panitia.');
    }
}
