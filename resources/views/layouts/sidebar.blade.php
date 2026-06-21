{{-- Sidebar Navigation — SIM UKM Jurnalistik --}}
{{-- Desktop: fixed sidebar (collapsible) | Mobile: offcanvas --}}

{{-- Desktop Sidebar --}}
<div class="sidebar-desktop d-none d-lg-flex flex-column" id="sidebarDesktop">
    <div class="sidebar d-flex flex-column p-0">
        @include('layouts._sidebar-content')
    </div>
</div>

{{-- Mobile Offcanvas Sidebar --}}
<div class="offcanvas offcanvas-start sidebar-offcanvas d-lg-none" tabindex="-1" id="sidebarOffcanvas">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-bold">SIM UKM Jurnalistik</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="sidebar p-0">
            @include('layouts._sidebar-content')
        </div>
    </div>
</div>

{{-- Sidebar Toggle Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebarDesktop');
    const storageKey = 'sidebar-collapsed';

    if (!sidebar) return;

    // Restore state
    if (localStorage.getItem(storageKey) === 'true') {
        sidebar.classList.add('collapsed');
    }

    // Click logo to toggle
    const logo = sidebar.querySelector('.brand-logo');
    if (logo) {
        logo.style.cursor = 'pointer';
        logo.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            sidebar.classList.toggle('collapsed');
            localStorage.setItem(storageKey, sidebar.classList.contains('collapsed'));
        });
    }
});
</script>
