{{-- Breadcrumb — SIM UKM Jurnalistik --}}
@hasSection('breadcrumb')
<nav class="app-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
        @yield('breadcrumb')
    </ol>
</nav>
@endif
