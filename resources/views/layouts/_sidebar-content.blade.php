{{-- Sidebar Content (shared between desktop & mobile) --}}
<div class="sidebar-brand d-none d-lg-flex">
    <img src="{{ asset('assets/logo.png') }}" alt="Logo UKM Jurnalistik" class="brand-logo">
    <div class="brand-info">
        <h6>SIM UKM Jurnalistik</h6>
        <small>Politeknik Negeri Samarinda</small>
    </div>
</div>

<hr class="border-secondary mx-3 mt-0 mb-2 d-none d-lg-block sidebar-divider">

<div class="sidebar-nav-scroll">
<ul class="nav flex-column px-2 py-1">
    {{-- Dashboard --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" title="Dashboard">
            <i class="bi bi-speedometer2"></i> <span class="nav-link-text">Dashboard</span>
        </a>
    </li>

    {{-- Manajemen Organisasi --}}
    @canany(['organisasi.view', 'organisasi.create'])
    <li class="sidebar-section"><span class="section-text">Organisasi</span></li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('anggota.*') ? 'active' : '' }}" href="{{ route('anggota.index') }}" title="Anggota">
            <i class="bi bi-people"></i> <span class="nav-link-text">Anggota</span>
        </a>
    </li>
    @endcanany

    @can('periode.view')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('periode.*') ? 'active' : '' }}" href="{{ route('periode.index') }}" title="Periode">
            <i class="bi bi-calendar-range"></i> <span class="nav-link-text">Periode</span>
        </a>
    </li>
    @endcan

    @can('pergantian.view')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('pergantian.*') ? 'active' : '' }}" href="{{ route('pergantian.index') }}" title="Pergantian">
            <i class="bi bi-arrow-repeat"></i> <span class="nav-link-text">Pergantian</span>
        </a>
    </li>
    @endcan

    @can('organisasi.view')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('keaktifan.*') ? 'active' : '' }}" href="{{ route('keaktifan.index') }}" title="Keaktifan">
            <i class="bi bi-person-check"></i> <span class="nav-link-text">Keaktifan</span>
        </a>
    </li>
    @endcan

    @can('rekrutmen.view')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('rekrutmen.*') ? 'active' : '' }}" href="{{ route('rekrutmen.index') }}" title="Rekrutmen">
            <i class="bi bi-person-plus"></i> <span class="nav-link-text">Rekrutmen</span>
        </a>
    </li>
    @endcan

    {{-- Kegiatan --}}
    @canany(['event.view', 'kepanitiaan.view'])
    <li class="sidebar-section"><span class="section-text">Kegiatan</span></li>
    @endcanany

    @can('event.view')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('event.*') ? 'active' : '' }}" href="{{ route('event.index') }}" title="Event">
            <i class="bi bi-calendar-event"></i> <span class="nav-link-text">Event</span>
        </a>
    </li>
    @endcan

    @can('surat-pernyataan.view')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('surat-pernyataan.*') ? 'active' : '' }}" href="{{ route('surat-pernyataan.index') }}" title="Surat Pernyataan">
            <i class="bi bi-file-earmark-check"></i> <span class="nav-link-text">Surat Pernyataan</span>
        </a>
    </li>
    @endcan

    @can('template-panitia.view')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('template-kepanitiaan.*') ? 'active' : '' }}" href="{{ route('template-kepanitiaan.index') }}" title="Template Panitia">
            <i class="bi bi-clipboard-data"></i> <span class="nav-link-text">Template Panitia</span>
        </a>
    </li>
    @endcan

    {{-- Administrasi --}}
    @canany(['notulensi.view', 'naskah-redaksi.view', 'jadwal-shift.view'])
    <li class="sidebar-section"><span class="section-text">Administrasi</span></li>
    @endcanany

    @can('notulensi.view')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('notulensi.*') ? 'active' : '' }}" href="{{ route('notulensi.index') }}" title="Notulensi">
            <i class="bi bi-journal-text"></i> <span class="nav-link-text">Notulensi</span>
        </a>
    </li>
    @endcan

    @can('naskah-redaksi.view')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('naskah.*') ? 'active' : '' }}" href="{{ route('naskah.index') }}" title="Naskah Redaksi">
            <i class="bi bi-file-earmark-text"></i> <span class="nav-link-text">Naskah Redaksi</span>
        </a>
    </li>
    @endcan

    @can('jadwal-shift.view')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('jadwal.*') ? 'active' : '' }}" href="{{ route('jadwal.index') }}" title="Jadwal Piket">
            <i class="bi bi-calendar3"></i> <span class="nav-link-text">Jadwal Piket</span>
        </a>
    </li>
    @endcan

    {{-- Keuangan --}}
    @canany(['anggaran-divisi.view', 'anggaran-event.view'])
    <li class="sidebar-section"><span class="section-text">Keuangan</span></li>
    @endcanany

    @can('anggaran-divisi.view')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('anggaran-divisi.*') ? 'active' : '' }}" href="{{ route('anggaran-divisi.index') }}" title="Anggaran Divisi">
            <i class="bi bi-wallet2"></i> <span class="nav-link-text">Anggaran Divisi</span>
        </a>
    </li>
    @endcan

    @can('anggaran-event.view')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('anggaran-event.*') ? 'active' : '' }}" href="{{ route('anggaran-event.index') }}" title="Anggaran Event">
            <i class="bi bi-cash-stack"></i> <span class="nav-link-text">Anggaran Event</span>
        </a>
    </li>
    @endcan

    {{-- Laporan & Log --}}
    @canany(['laporan.view', 'activity-log.view', 'keamanan.view-log'])
    <li class="sidebar-section"><span class="section-text">Laporan & Log</span></li>
    @endcanany

    @can('laporan.view')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}" title="Laporan">
            <i class="bi bi-file-bar-graph"></i> <span class="nav-link-text">Laporan</span>
        </a>
    </li>
    @endcan

    @can('activity-log.view')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('keamanan.activity-log') ? 'active' : '' }}" href="{{ route('keamanan.activity-log') }}" title="Activity Log">
            <i class="bi bi-list-check"></i> <span class="nav-link-text">Activity Log</span>
        </a>
    </li>
    @endcan

    @can('keamanan.view-log')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('keamanan.login-history') ? 'active' : '' }}" href="{{ route('keamanan.login-history') }}" title="Log Keamanan">
            <i class="bi bi-shield-lock"></i> <span class="nav-link-text">Log Keamanan</span>
        </a>
    </li>
    @endcan
</ul>
</div>
