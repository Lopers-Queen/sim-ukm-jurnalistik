<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description" content="Sistem Informasi Manajemen UKM Jurnalistik Politeknik Negeri Samarinda">
    <title><?php echo $__env->yieldContent('title', config('app.name', 'SIM UKM Jurnalistik')); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('vendor-styles'); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
    
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>
<body>
    <div class="d-flex">
        
        <?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <div class="main-content-wrapper flex-grow-1 d-flex flex-column">
            
            <nav class="top-navbar d-flex align-items-center justify-content-between">
                
                <button class="btn btn-link d-lg-none me-2 p-0" type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas"
                        style="color: var(--heading);">
                    <i class="bi bi-list fs-4"></i>
                </button>

                
                <h5 class="navbar-page-title d-none d-md-block"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h5>
                <div class="d-md-none"></div>

                
                <div class="ms-auto d-flex align-items-center gap-3">
                    
                    <button class="theme-toggle" id="themeToggle" title="Toggle dark/light mode">
                        <i class="bi bi-sun-fill icon-sun"></i>
                        <i class="bi bi-moon-fill icon-moon"></i>
                    </button>

                    <div class="dropdown">
                        <button class="user-dropdown-btn dropdown-toggle" data-bs-toggle="dropdown">
                            <?php if(Auth::user()->foto_profil_url): ?>
                                <img src="<?php echo e(Auth::user()->foto_profil_url); ?>" alt="Avatar"
                                     class="rounded-circle" width="32" height="32" style="object-fit: cover;">
                            <?php else: ?>
                                <div class="avatar avatar-sm">
                                    <?php echo e(strtoupper(substr(Auth::user()->nama_lengkap, 0, 2))); ?>

                                </div>
                            <?php endif; ?>
                            <div class="d-none d-md-block text-start">
                                <div class="user-name"><?php echo e(Auth::user()->nama_lengkap); ?></div>
                                <div class="user-role"><?php echo e(Auth::user()->jabatan_lengkap); ?></div>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            
            <?php echo $__env->make('layouts._breadcrumb', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <div class="content-wrapper">
                
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show flash-alert" role="alert">
                        <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if(session('warning')): ?>
                    <div class="alert alert-warning alert-dismissible fade show flash-alert" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i><?php echo e(session('warning')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show flash-alert" role="alert">
                        <i class="bi bi-x-circle me-2"></i><?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </div>

            
            <?php echo $__env->make('layouts._footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Track last visit timestamp for "new since last visit" indicators
            const now = new Date().toISOString();
            localStorage.setItem('dashboard_last_visit', localStorage.getItem('dashboard_current_visit') || now);
            localStorage.setItem('dashboard_current_visit', now);
        });
    </script>

    
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

    <?php echo $__env->yieldPushContent('vendor-scripts'); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/layouts/app.blade.php ENDPATH**/ ?>