@extends('layouts.app')
@section('title', 'Activity Log — SIM UKM Jurnalistik')
@section('page-title', 'Activity Log')
@section('breadcrumb')
<li class="breadcrumb-item active">Log Keamanan</li>
@endsection

@section('content')
<h4 class="fw-bold mb-4">Log Aktivitas Sistem</h4>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>Waktu</th><th>Pelaku</th><th>Model</th><th>Deskripsi</th></tr></thead>
        <tbody>
        @forelse($activities as $act)
        <tr>
            <td class="small">{{ $act->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $act->causer?->nama_lengkap ?? 'Sistem' }}</td>
            <td><code class="small">{{ class_basename($act->subject_type ?? '-') }}</code></td>
            <td>{{ $act->description }}</td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada aktivitas</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
@if($activities->hasPages())<div class="card-footer">{{ $activities->links() }}</div>@endif
</div>
@endsection
