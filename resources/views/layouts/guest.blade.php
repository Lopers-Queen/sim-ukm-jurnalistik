<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Login — SIM UKM Jurnalistik Politeknik Negeri Samarinda">
    <title>{{ config('app.name', 'SIM UKM Jurnalistik') }} — Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>
<body class="login-page">
    {{-- Theme toggle on login page --}}
    <button class="theme-toggle" id="themeToggleLogin"
            style="position: fixed; top: 1rem; right: 1rem; z-index: 100;"
            title="Toggle dark/light mode">
        <i class="bi bi-sun-fill icon-sun"></i>
        <i class="bi bi-moon-fill icon-moon"></i>
    </button>

    <div class="login-wrapper">
        {{ $slot }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('themeToggleLogin');
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
</body>
</html>
