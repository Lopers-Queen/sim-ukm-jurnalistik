<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistem Informasi Manajemen UKM Jurnalistik Politeknik Negeri Samarinda">
    <title>@yield('title', config('app.name', 'SIM UKM Jurnalistik'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('vendor-styles')
    @stack('styles')
    {{-- Prevent FOUC: apply theme before render --}}
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>
<body>
    <div class="d-flex">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main Content --}}
        <div class="main-content-wrapper flex-grow-1 d-flex flex-column">
            {{-- Top Navbar --}}
            <nav class="top-navbar d-flex align-items-center justify-content-between">
                {{-- Mobile toggle --}}
                <button class="btn btn-link d-lg-none me-2 p-0" type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas"
                        style="color: var(--heading);">
                    <i class="bi bi-list fs-4"></i>
                </button>

                {{-- Page title --}}
                <h5 class="navbar-page-title d-none d-md-block">@yield('page-title', 'Dashboard')</h5>
                <div class="d-md-none"></div>

                {{-- Right side --}}
                <div class="ms-auto d-flex align-items-center gap-3">
                    {{-- Super Admin Indicator --}}
                    @if(Auth::user()->hasRole('super_admin'))
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger" style="font-size: 0.7rem;">
                            <i class="bi bi-shield-lock-fill me-1"></i>Super Admin
                        </span>
                    @endif

                    {{-- Impersonating Banner --}}
                    @if(session('impersonating_role'))
                        <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">
                            <i class="bi bi-eye me-1"></i>Impersonating: {{ session('impersonating_role') }}
                            <a href="{{ route('admin.stop-impersonate') }}" class="ms-1 text-decoration-none"><i class="bi bi-x-circle-fill"></i></a>
                        </span>
                    @endif
                    {{-- Theme Toggle --}}
                    <button class="theme-toggle" id="themeToggle" title="Toggle dark/light mode">
                        <i class="bi bi-sun-fill icon-sun"></i>
                        <i class="bi bi-moon-fill icon-moon"></i>
                    </button>

                    <div class="dropdown">
                        <button class="user-dropdown-btn dropdown-toggle" data-bs-toggle="dropdown">
                            @if(Auth::user()->foto_profil_url)
                                <img src="{{ Auth::user()->foto_profil_url }}" alt="Avatar"
                                     class="rounded-circle" width="32" height="32" style="object-fit: cover;">
                            @else
                                <div class="avatar avatar-sm">
                                    {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 2)) }}
                                </div>
                            @endif
                            <div class="d-none d-md-block text-start">
                                <div class="user-name">{{ Auth::user()->nama_lengkap }}</div>
                                <div class="user-role">{{ Auth::user()->jabatan_lengkap }}</div>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
                            @if(Auth::user()->hasRole('super_admin') && config('app.debug'))
                                <li><hr class="dropdown-divider"></li>
                                <li class="dropdown-header small text-muted">
                                    <i class="bi bi-shield-lock me-1"></i>Admin Tools
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.impersonate-roles') }}">
                                        <i class="bi bi-eye me-2"></i>Impersonate Role
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            {{-- Breadcrumb --}}
            @include('layouts._breadcrumb')

            {{-- Content --}}
            <div class="content-wrapper">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show flash-alert" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show flash-alert" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show flash-alert" role="alert">
                        <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>

            {{-- Footer --}}
            @include('layouts._footer')
        </div>
    </div>

    {{-- Global Dashboard Cookies --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Track last visit timestamp for "new since last visit" indicators
            const now = new Date().toISOString();
            localStorage.setItem('dashboard_last_visit', localStorage.getItem('dashboard_current_visit') || now);
            localStorage.setItem('dashboard_current_visit', now);
        });
    </script>

    {{-- Auto-dismiss flash alerts --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.flash-alert').forEach(alert => {
                setTimeout(() => {
                    alert.classList.add('fade-out');
                    setTimeout(() => alert.remove(), 600);
                }, 5000);
            });
        });
    </script>

    {{-- Theme Toggle Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('themeToggle');
            if (!toggle) return;

            toggle.addEventListener('click', function() {
                const html = document.documentElement;
                const current = html.getAttribute('data-theme') || 'light';
                const next = current === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-theme', next);
                localStorage.setItem('theme', next);
            });
        });
    </script>

    {{-- Tom-Select (searchable dropdowns) --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.querySelectorAll('.select-search').forEach(el => {
            new TomSelect(el, {
                create: false,
                sortField: { field: 'text', direction: 'asc' },
                placeholder: el.getAttribute('placeholder') || 'Ketik untuk mencari...',
            });
        });
    </script>

    @stack('vendor-scripts')
    @stack('scripts')
</body>
</html>
