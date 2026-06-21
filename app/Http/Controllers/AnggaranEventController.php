<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnggaranEventRequest;
use App\Http\Requests\UpdateAnggaranEventRequest;
use App\Models\AnggaranEvent;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnggaranEventController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('anggaran-event.view');

        $query = AnggaranEvent::with('event');
        if ($eventId = $request->input('event_id')) {
            $query->where('event_id', $eventId);
        }
        $anggaran = $query->latest()->paginate(15)->withQueryString();
        $events = Event::orderByDesc('tanggal_mulai')->get();

        return view('anggaran-event.index', compact('anggaran', 'events'));
    }

    public function create(): View
    {
        $this->authorize('anggaran-event.create');

        $events = Event::orderByDesc('tanggal_mulai')->get();

        return view('anggaran-event.create', compact('events'));
    }

    public function store(StoreAnggaranEventRequest $request): RedirectResponse
    {
        AnggaranEvent::create($request->validated());

        return redirect()->route('anggaran-event.index')->with('success', 'Item anggaran event berhasil ditambahkan.');
    }

    public function edit(AnggaranEvent $anggaranEvent): View
    {
        $this->authorize('anggaran-event.edit');

        $events = Event::orderByDesc('tanggal_mulai')->get();

        return view('anggaran-event.edit', compact('anggaranEvent', 'events'));
    }

    public function update(UpdateAnggaranEventRequest $request, AnggaranEvent $anggaranEvent): RedirectResponse
    {
        $anggaranEvent->update($request->validated());

        return redirect()->route('anggaran-event.index')->with('success', 'Item anggaran event berhasil diperbarui.');
    }

    public function destroy(AnggaranEvent $anggaranEvent): RedirectResponse
    {
        $this->authorize('anggaran-event.delete');

        $anggaranEvent->delete();

        return redirect()->route('anggaran-event.index')->with('success', 'Item anggaran event berhasil dihapus.');
    }
}
