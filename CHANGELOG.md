# Changelog

All notable changes to the SIM UKM Jurnalistik project will be documented in this file.

## [1.0.0] - 2026-05-18

### 🔒 Security
- **CRITICAL**: Added `AuthorizesRequests` trait to base `Controller.php` — all authorization calls were previously non-functional
- Added `$this->authorize()` to **103 controller methods** across **16 controllers**
- Removed dead permissions (`notifikasi.*`, `backup.*`) from seeders
- Cleaned `notifikasi.*` from all 11 role assignments

### 🧪 Testing  
- Added `AuthorizationMatrixTest.php` with **46 feature tests**
- Tests verify correct 200/403 responses for all role-endpoint combinations
- All tests passing (46/46)

### 🎨 Dashboard Per Role (FR-11)
- Refactored `DashboardController` to route to 8 role-specific dashboards
- **Ketua/Wakil**: Full stats, charts, quick actions, activity log, surat pending
- **Sekretaris**: Notulensi-focused with rapat management
- **Bendahara**: Financial overview with anggaran vs realisasi chart
- **Kadiv**: Scoped to own division (anggota, anggaran, events)
- **Kanit**: Scoped to own unit with naskah redaksi for Kanit Redaksi
- **Staf**: Personal naskah and event list
- **Anggota Aktif**: Welcome card with membership info and events
- **Anggota Pasif**: Minimal view with keaktifan form and surat pernyataan

### 📸 Foto Profil (FR-03)
- Added `uploadFotoProfil()` and `deleteFotoProfil()` methods to `Anggota` model
- Added `foto_profil_url` accessor with initials fallback
- Added upload form with preview on profile page
- Photo displayed in navbar avatar
- Storage link created (`public/storage → storage/app/public`)
- ActivityLog integration for photo changes

### 🧹 Cleanup
- Removed non-functional notification bell icon from navbar
- Removed unused `dashboard.blade.php` (replaced by 8 role-specific views)

### Files Changed
- `app/Http/Controllers/Controller.php` — Added AuthorizesRequests trait
- `app/Http/Controllers/DashboardController.php` — Complete rewrite for per-role routing
- `app/Http/Controllers/ProfileController.php` — Added updateFoto, deleteFoto
- `app/Models/Anggota.php` — Added foto profil accessor and upload methods
- `routes/web.php` — Added profile/foto routes
- `resources/views/layouts/app.blade.php` — Removed bell, updated avatar
- `resources/views/profile/edit.blade.php` — Added photo upload section
- `resources/views/dashboard/*.blade.php` — 8 new dashboard views
- `database/seeders/PermissionSeeder.php` — Removed dead permissions
- `database/seeders/AssignPermissionToRoleSeeder.php` — Cleaned role assignments
- `tests/Feature/AuthorizationMatrixTest.php` — 46 new tests
- 16 controllers — authorize() calls added
