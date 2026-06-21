
<div class="sidebar-brand d-none d-lg-flex">
    <img src="<?php echo e(asset('assets/logo.png')); ?>" alt="Logo UKM Jurnalistik" class="brand-logo">
    <div class="brand-info">
        <h6>SIM UKM Jurnalistik</h6>
        <small>Politeknik Negeri Samarinda</small>
    </div>
</div>

<hr class="border-secondary mx-3 mt-0 mb-2 d-none d-lg-block sidebar-divider">

<div class="sidebar-nav-scroll">
<ul class="nav flex-column px-2 py-1">
    
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>" title="Dashboard">
            <i class="bi bi-speedometer2"></i> <span class="nav-link-text">Dashboard</span>
        </a>
    </li>

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['organisasi.view', 'organisasi.create'])): ?>
    <li class="sidebar-section"><span class="section-text">Organisasi</span></li>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('anggota.*') ? 'active' : ''); ?>" href="<?php echo e(route('anggota.index')); ?>" title="Anggota">
            <i class="bi bi-people"></i> <span class="nav-link-text">Anggota</span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('periode.view')): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('periode.*') ? 'active' : ''); ?>" href="<?php echo e(route('periode.index')); ?>" title="Periode">
            <i class="bi bi-calendar-range"></i> <span class="nav-link-text">Periode</span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('pergantian.view')): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('pergantian.*') ? 'active' : ''); ?>" href="<?php echo e(route('pergantian.index')); ?>" title="Pergantian">
            <i class="bi bi-arrow-repeat"></i> <span class="nav-link-text">Pergantian</span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('organisasi.view')): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('keaktifan.*') ? 'active' : ''); ?>" href="<?php echo e(route('keaktifan.index')); ?>" title="Keaktifan">
            <i class="bi bi-person-check"></i> <span class="nav-link-text">Keaktifan</span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rekrutmen.view')): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('rekrutmen.*') ? 'active' : ''); ?>" href="<?php echo e(route('rekrutmen.index')); ?>" title="Rekrutmen">
            <i class="bi bi-person-plus"></i> <span class="nav-link-text">Rekrutmen</span>
        </a>
    </li>
    <?php endif; ?>

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['event.view', 'kepanitiaan.view'])): ?>
    <li class="sidebar-section"><span class="section-text">Kegiatan</span></li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('event.view')): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('event.*') ? 'active' : ''); ?>" href="<?php echo e(route('event.index')); ?>" title="Event">
            <i class="bi bi-calendar-event"></i> <span class="nav-link-text">Event</span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('surat-pernyataan.view')): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('surat-pernyataan.*') ? 'active' : ''); ?>" href="<?php echo e(route('surat-pernyataan.index')); ?>" title="Surat Pernyataan">
            <i class="bi bi-file-earmark-check"></i> <span class="nav-link-text">Surat Pernyataan</span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('template-panitia.view')): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('template-kepanitiaan.*') ? 'active' : ''); ?>" href="<?php echo e(route('template-kepanitiaan.index')); ?>" title="Template Panitia">
            <i class="bi bi-clipboard-data"></i> <span class="nav-link-text">Template Panitia</span>
        </a>
    </li>
    <?php endif; ?>

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['notulensi.view', 'naskah-redaksi.view', 'jadwal-shift.view'])): ?>
    <li class="sidebar-section"><span class="section-text">Administrasi</span></li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('notulensi.view')): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('notulensi.*') ? 'active' : ''); ?>" href="<?php echo e(route('notulensi.index')); ?>" title="Notulensi">
            <i class="bi bi-journal-text"></i> <span class="nav-link-text">Notulensi</span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('naskah-redaksi.view')): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('naskah.*') ? 'active' : ''); ?>" href="<?php echo e(route('naskah.index')); ?>" title="Naskah Redaksi">
            <i class="bi bi-file-earmark-text"></i> <span class="nav-link-text">Naskah Redaksi</span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('jadwal-shift.view')): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('jadwal.*') ? 'active' : ''); ?>" href="<?php echo e(route('jadwal.index')); ?>" title="Jadwal Piket">
            <i class="bi bi-calendar3"></i> <span class="nav-link-text">Jadwal Piket</span>
        </a>
    </li>
    <?php endif; ?>

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['anggaran-divisi.view', 'anggaran-event.view'])): ?>
    <li class="sidebar-section"><span class="section-text">Keuangan</span></li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anggaran-divisi.view')): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('anggaran-divisi.*') ? 'active' : ''); ?>" href="<?php echo e(route('anggaran-divisi.index')); ?>" title="Anggaran Divisi">
            <i class="bi bi-wallet2"></i> <span class="nav-link-text">Anggaran Divisi</span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anggaran-event.view')): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('anggaran-event.*') ? 'active' : ''); ?>" href="<?php echo e(route('anggaran-event.index')); ?>" title="Anggaran Event">
            <i class="bi bi-cash-stack"></i> <span class="nav-link-text">Anggaran Event</span>
        </a>
    </li>
    <?php endif; ?>

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['laporan.view', 'activity-log.view', 'keamanan.view-log'])): ?>
    <li class="sidebar-section"><span class="section-text">Laporan & Log</span></li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('laporan.view')): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('laporan.*') ? 'active' : ''); ?>" href="<?php echo e(route('laporan.index')); ?>" title="Laporan">
            <i class="bi bi-file-bar-graph"></i> <span class="nav-link-text">Laporan</span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('activity-log.view')): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('keamanan.activity-log') ? 'active' : ''); ?>" href="<?php echo e(route('keamanan.activity-log')); ?>" title="Activity Log">
            <i class="bi bi-list-check"></i> <span class="nav-link-text">Activity Log</span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('keamanan.view-log')): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('keamanan.login-history') ? 'active' : ''); ?>" href="<?php echo e(route('keamanan.login-history')); ?>" title="Log Keamanan">
            <i class="bi bi-shield-lock"></i> <span class="nav-link-text">Log Keamanan</span>
        </a>
    </li>
    <?php endif; ?>
</ul>
</div>
<?php /**PATH C:\Users\Paulina\UKM Jurnalistik\resources\views/layouts/_sidebar-content.blade.php ENDPATH**/ ?>