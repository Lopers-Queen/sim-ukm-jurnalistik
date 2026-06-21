<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNaskahRequest;
use App\Http\Requests\UpdateNaskahRequest;
use App\Models\NaskahRedaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controller Naskah Redaksi (FR-08)
 * Workflow: Draft -> Review -> Revisi -> Disetujui -> Published / Ditolak
 */
class NaskahRedaksiController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('naskah-redaksi.view');

        $query = NaskahRedaksi::with(['penulis', 'editor']);

        if ($search = $request->input('search')) {
            $query->where('judul', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($kategori = $request->input('kategori')) {
            $query->where('kategori', $kategori);
        }

        $naskah = $query->latest()->paginate(15)->withQueryString();

        return view('naskah.index', compact('naskah'));
    }

    public function create(): View
    {
        $this->authorize('naskah-redaksi.create');

        return view('naskah.create');
    }

    public function store(StoreNaskahRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['penulis_id'] = Auth::id();
        $data['status'] = 'draft';

        NaskahRedaksi::create($data);

        return redirect()->route('naskah.index')
            ->with('success', 'Naskah berhasil disimpan sebagai draft.');
    }

    public function show(NaskahRedaksi $naskah): View
    {
        $this->authorize('naskah-redaksi.view');

        $naskah->load(['penulis', 'editor']);

        return view('naskah.show', compact('naskah'));
    }

    public function edit(NaskahRedaksi $naskah): View
    {
        $this->authorize('naskah-redaksi.edit');

        return view('naskah.edit', compact('naskah'));
    }

    public function update(UpdateNaskahRequest $request, NaskahRedaksi $naskah): RedirectResponse
    {
        $naskah->update($request->validated());

        return redirect()->route('naskah.show', $naskah)
            ->with('success', 'Naskah berhasil diperbarui.');
    }

    /**
     * Submit naskah untuk review.
     */
    public function submitReview(NaskahRedaksi $naskah): RedirectResponse
    {
        $naskah->update(['status' => 'review']);

        return redirect()->route('naskah.show', $naskah)
            ->with('success', 'Naskah telah disubmit untuk review.');
    }

    /**
     * Approve naskah (oleh Kanit Redaksi / Ketum).
     */
    public function approve(Request $request, NaskahRedaksi $naskah): RedirectResponse
    {
        $this->authorize('naskah-redaksi.approve');

        $naskah->update([
            'status'         => 'disetujui',
            'editor_id'      => Auth::id(),
            'catatan_editor' => $request->input('catatan_editor'),
        ]);

        return redirect()->route('naskah.show', $naskah)
            ->with('success', 'Naskah disetujui.');
    }

    /**
     * Revisi naskah (oleh editor).
     */
    public function revisi(Request $request, NaskahRedaksi $naskah): RedirectResponse
    {
        $request->validate(['catatan_editor' => 'required|string|min:10']);

        $naskah->update([
            'status'         => 'revisi',
            'editor_id'      => Auth::id(),
            'catatan_editor' => $request->catatan_editor,
        ]);

        return redirect()->route('naskah.show', $naskah)
            ->with('warning', 'Naskah dikembalikan untuk revisi.');
    }

    /**
     * Publish naskah (setelah disetujui).
     */
    public function publish(NaskahRedaksi $naskah): RedirectResponse
    {
        $this->authorize('naskah-redaksi.publish');

        $naskah->update([
            'status'          => 'published',
            'tanggal_publish' => now(),
        ]);

        return redirect()->route('naskah.show', $naskah)
            ->with('success', 'Naskah berhasil dipublikasikan.');
    }

    public function destroy(NaskahRedaksi $naskah): RedirectResponse
    {
        $this->authorize('naskah-redaksi.delete');

        $naskah->delete();

        return redirect()->route('naskah.index')
            ->with('success', 'Naskah berhasil dihapus.');
    }
}
