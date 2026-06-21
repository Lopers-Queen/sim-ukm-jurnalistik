@extends('layouts.app')
@section('title', 'Log Keamanan — SIM UKM Jurnalistik')
@section('page-title', 'Log Login')
@section('breadcrumb')
<li class="breadcrumb-item active">Log Keamanan</li>
@endsection

@section('content')
<h4 class="fw-bold mb-4">Riwayat Login</h4>
<div class="card mb-4"><div class="card-body">
    <form method="GET" class="row g-3 align-items-center">
        <div class="col-md-5"><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari NIM/nama..."></div></div>
        <div class="col-md-3"><select class="form-select" name="status" onchange="this.form.submit()"><option value="">Semua Status</option>
            <option value="success" {{ request('status')=='success'?'selected':'' }}>Berhasil</option>
            <option value="failed" {{ request('status')=='failed'?'selected':'' }}>Gagal</option></select></div>
        @if(request('search') || request('status'))
        <div class="col-md-4"><a href="{{ route('keamanan.login-history') }}" class="btn btn-outline-secondary w-100 btn-sm"><i class="bi bi-x-lg me-1"></i> Reset</a></div>
        @endif
    </form>
</div></div>
@push('scripts')
<script>
let t;document.getElementById('searchInput').addEventListener('input',function(){clearTimeout(t);t=setTimeout(()=>this.form.submit(),500)});
</script>
@endpush
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>Waktu</th><th>Anggota</th><th>IP Address</th><th>User Agent</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse($histories as $h)
        <tr>
            <td class="small">{{ $h->attempted_at->format('d/m/Y H:i:s') }}</td>
            <td>{{ $h->anggota?->nama_lengkap ?? $h->anggota_id }}</td>
            <td><code>{{ $h->ip_address }}</code></td>
            <td class="small text-muted" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $h->user_agent }}</td>
            <td><span class="badge bg-{{ $h->status === 'success' ? 'success' : 'danger' }}">{{ $h->status === 'success' ? 'Berhasil' : 'Gagal' }}</span></td>
            <td>
                @if($h->status === 'failed' && $h->anggota && ($h->anggota->is_locked || $h->anggota->failed_login_attempts >= 3))
                @can('keamanan.manage-lockout')
                <form method="POST" action="{{ route('keamanan.unlock-account') }}" class="d-inline" onsubmit="return confirm('Unlock akun {{ $h->anggota->nama_lengkap }}?')">
                    @csrf
                    <input type="hidden" name="anggota_id" value="{{ $h->anggota->id }}">
                    <button class="btn btn-outline-warning btn-sm"><i class="bi bi-unlock me-1"></i>Unlock</button>
                </form>
                @endcan
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat login</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
@if($histories->hasPages())<div class="card-footer">{{ $histories->links() }}</div>@endif
</div>
@endsection
