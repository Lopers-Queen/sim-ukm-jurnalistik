# DOKUMEN PRESENTASI PROYEK PERANGKAT LUNAK
# Sistem Informasi Manajemen UKM Jurnalistik
## Politeknik Negeri Samarinda

**Versi:** 1.0.0 | **Tahun:** 2026

---

## DAFTAR ISI

1. [Pendahuluan](#1-pendahuluan)
2. [Analisis Kebutuhan](#2-analisis-kebutuhan)
3. [Desain Sistem](#3-desain-sistem)
4. [Implementasi](#4-implementasi)
5. [Demonstrasi Aplikasi](#5-demonstrasi-aplikasi)
6. [Pengujian Sistem](#6-pengujian-sistem)
7. [Hasil dan Evaluasi](#7-hasil-dan-evaluasi)
8. [Kesimpulan](#8-kesimpulan)

---

## 1. PENDAHULUAN (1-2 Menit)

### 1.1 Perkenalan Tim

| No | Nama | Jabatan dalam Tim | Peran |
|----|------|-------------------|-------|
| 1 | [Nama Anggota 1] | Project Manager / Ketua Tim | Koordinasi tim, perencanaan proyek |
| 2 | [Nama Anggota 2] | System Analyst | Analisis kebutuhan, desain sistem |
| 3 | [Nama Anggota 3] | Backend Developer | Pengembangan logika server, database |
| 4 | [Nama Anggota 4] | Frontend Developer | Pengembangan antarmuka pengguna |
| 5 | [Nama Anggota 5] | QA / Tester | Pengujian dan quality assurance |

> **Catatan:** Sesuaikan nama dan jumlah anggota tim sesuai kebutuhan.

### 1.2 Judul Proyek

**"Sistem Informasi Manajemen UKM Jurnalistik (SIM UKM Jurnalistik)"**

*Sistem informasi berbasis web untuk mengelola seluruh kegiatan organisasi UKM Jurnalistik Politeknik Negeri Samarinda secara digital dan terpusat.*

### 1.3 Latar Belakang Masalah

UKM Jurnalistik Politeknik Negeri Samarinda sebagai organisasi kemahasiswaan yang bergerak di bidang jurnalistik memiliki berbagai kegiatan administratif yang kompleks. Sebelum adanya sistem ini, pengelolaan organisasi masih dilakukan secara manual dan terfragmentasi, yang menyebabkan berbagai permasalahan berikut:

| No | Masalah yang Dihadapi | Dampak |
|----|----------------------|--------|
| 1 | Data anggota tersebar di Google Spreadsheet dan berbagai platform | Sulit mencari data, duplikasi data, inkonsistensi |
| 2 | Notulensi rapat hilang di grup WhatsApp | Dokumentasi tidak terstruktur, informasi hilang |
| 3 | Pergantian kepengurusan tanpa validasi syarat | Calon pengurus tidak memenuhi kualifikasi |
| 4 | Keuangan dicatat di buku tulis | Rawan kehilangan, sulit diaudit, tidak transparan |
| 5 | Kepanitiaan event dibentuk lewat chat | Tidak terstruktur, sulit tracking |
| 6 | Tidak ada riwayat kepengurusan | Sejarah organisasi tidak tercatat |
| 7 | Jadwal piket diatur manual | Pembagian tidak adil, sering bentrok |
| 8 | Naskah redaksi tidak terkelola | Alur review tidak jelas, naskah hilang |

### 1.4 Tujuan Pengembangan Aplikasi

**Tujuan Umum:**
Membangun sistem informasi manajemen berbasis web yang mengintegrasikan seluruh proses administrasi dan manajemen UKM Jurnalistik dalam satu platform digital.

**Tujuan Khusus:**
1. **Mendigitalisasi administrasi** — Mengubah seluruh pencatatan manual menjadi digital yang terpusat
2. **Menyediakan sumber data terpercaya** — Satu database tunggal untuk seluruh data organisasi
3. **Memastikan transisi kepengurusan berjalan lancar** — Validasi otomatis syarat jabatan
4. **Mendokumentasikan kegiatan secara terstruktur** — Notulensi, event, dan laporan tersimpan permanen
5. **Meningkatkan transparansi keuangan** — Pencatatan anggaran dan realisasi yang dapat diaudit
6. **Mengelola alur redaksi** — Workflow naskah dari draft hingga publikasi
7. **Menyediakan akses berbasis peran** — Setiap jabatan memiliki hak akses yang sesuai

---

## 2. ANALISIS KEBUTUHAN

### 2.1 Kebutuhan Fungsional (Functional Requirements)

Kebutuhan fungsional menggambarkan apa yang harus dapat dilakukan oleh sistem:

#### A. Manajemen Anggota & Organisasi

| Kode | Kebutuhan Fungsional | Deskripsi | Aktor |
|------|---------------------|-----------|-------|
| FR-01 | Autentikasi & Login | Login menggunakan NIM dan password (DDMMYYYY), reset password via email, account lockout setelah 5x gagal | Semua User |
| FR-02 | Manajemen Anggota | CRUD data anggota, import Excel, foto profil, pencarian & filter | Admin, Ketum, Sekum, Kadiv |
| FR-03 | Manajemen Profil | Edit profil, ganti password, upload/hapus foto profil | Semua User |
| FR-04 | Notulensi Rapat | CRUD notulensi, kategori rapat (MUBES, BPI, Divisi, Unit, dll), daftar hadir | Sekum, Admin |
| FR-05 | Rekrutmen | Buka periode rekrutmen, tracking status seleksi (Administrasi → Wawancara → Lolos) | Sekum, Admin |
| FR-06 | Jadwal Piket | Generate jadwal piket acak, kelola jadwal per hari | Kanit, Admin |

#### B. Manajemen Kepengurusan

| Kode | Kebutuhan Fungsional | Deskripsi | Aktor |
|------|---------------------|-----------|-------|
| FR-16 | Periode Kepengurusan | CRUD periode kepengurusan (tahun mulai/selesai, status aktif/selesai) | Admin, Ketum |
| FR-17 | Pergantian Kepengurusan | Validasi syarat jabatan, override eligibility, transisi otomatis | Ketum, Waketum |
| FR-13 | Keaktifan Anggota | Toggle status aktif/pasif, perpanjangan keaktifan, batch update | Admin, Ketum |

#### C. Manajemen Kegiatan & Event

| Kode | Kebutuhan Fungsional | Deskripsi | Aktor |
|------|---------------------|-----------|-------|
| FR-09 | Manajemen Event | CRUD event, status (Draft → Direncanakan → Aktif → Selesai), PIC assignment | Sekum, Kadiv, Admin |
| FR-14 | Kepanitiaan | Pembentukan divisi panitia, assign anggota ke panitia | Kadiv, Admin |
| FR-19 | Template Kepanitiaan | Template kepanitiaan reusable, duplikasi template | Admin, Ketum |
| FR-23 | Laporan Pasca Event | Laporan evaluasi, export PDF, finalisasi laporan | PIC, Sekum |
| FR-21 | Surat Pernyataan | Generate PDF, upload TTD, workflow approval (Ketum/Admin) | Admin, Ketum |

#### D. Administrasi & Keuangan

| Kode | Kebutuhan Fungsional | Deskripsi | Aktor |
|------|---------------------|-----------|-------|
| FR-07 | Anggaran Divisi | CRUD anggaran per divisi per bulan, tracking realisasi | Bendahara |
| FR-18 | Anggaran Event | CRUD item anggaran event, bukti transaksi, realisasi | Bendahara |
| FR-08 | Naskah Redaksi | Workflow naskah (Draft → Review → Revisi → Disetujui → Published) | Staf, Kanit Redaksi |
| FR-12 | Laporan & Ekspor | Export PDF/Excel: laporan anggota, event, keuangan | Sekum, Bendahara, Admin |

#### E. Keamanan & Audit

| Kode | Kebutuhan Fungsional | Deskripsi | Aktor |
|------|---------------------|-----------|-------|
| FR-10 | Log Keamanan | Riwayat login (waktu, IP, status), unlock account | Admin |
| FR-22 | Activity Log | Pencatatan seluruh perubahan data (siapa, kapan, apa) | Admin |
| FR-11 | Dashboard Role-Based | 8 jenis dashboard berbeda sesuai jabatan | Semua User |

### 2.2 Kebutuhan Non-Fungsional (Non-Functional Requirements)

| Kode | Kategori | Kebutuhan | Deskripsi |
|------|----------|-----------|-----------|
| NFR-01 | Performance | Response Time | Halaman dimuat dalam < 3 detik pada koneksi normal |
| NFR-02 | Performance | Concurrent Users | Mendukung minimal 50 user bersamaan |
| NFR-03 | Security | Password Encryption | Password dienkripsi menggunakan bcrypt |
| NFR-04 | Security | Account Lockout | Akun terkunci setelah 5x gagal login (15 menit) |
| NFR-05 | Security | Session Timeout | Auto-logout saat session habis |
| NFR-06 | Security | Authorization | Setiap endpoint dilindungi permission check |
| NFR-07 | Reliability | Data Integrity | Soft delete untuk data penting |
| NFR-08 | Reliability | Audit Trail | Seluruh perubahan tercatat di activity log |
| NFR-09 | Usability | Responsive Design | Tampilan optimal di desktop, tablet, dan mobile |
| NFR-10 | Usability | Intuitive UI | Antarmuka mudah dipelajari tanpa pelatihan khusus |
| NFR-11 | Portability | Cross-Browser | Kompatibel dengan Chrome, Firefox, Edge, Safari |
| NFR-12 | Maintainability | MVC Architecture | Arsitektur Model-View-Controller yang terstruktur |
| NFR-13 | Maintainability | Code Documentation | Kode terdokumentasi dengan PHPDoc |
| NFR-14 | Scalability | Modular Design | Mudah menambahkan modul/fitur baru |
| NFR-15 | Cost | Open Source | 100% menggunakan teknologi open source (Rp 0) |

---

## 3. DESAIN SISTEM

### 3.1 Use Case Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        USE CASE DIAGRAM                                      │
│              Sistem Informasi Manajemen UKM Jurnalistik                      │
│                                                                              │
│  ┌──────────┐                                                                │
│  │ Super    │──┬──[Login/Logout]                                             │
│  │ Admin    │  ├──[Kelola Anggota (CRUD + Import + Reset Password)]          │
│  └──────────┘  ├──[Kelola Periode Kepengurusan]                             │
│                ├──[Pergantian Kepengurusan + Override]                       │
│                ├──[Kelola Notulensi]                                         │
│                ├──[Kelola Rekrutmen]                                         │
│                ├──[Kelola Event & Kepanitiaan]                               │
│                ├──[Kelola Jadwal Piket]                                      │
│                ├──[Kelola Naskah Redaksi]                                    │
│                ├──[Kelola Anggaran Divisi & Event]                           │
│                ├──[Generate & Approve Surat Pernyataan]                      │
│                ├──[Kelola Laporan & Export PDF/Excel]                        │
│                ├──[Lihat Log Keamanan & Activity Log]                        │
│                └──[Kelola Template Kepanitiaan]                              │
│                                                                              │
│  ┌──────────┐                                                                │
│  │ Ketua    │──┬──[Login/Logout]                                             │
│  │ Umum     │  ├──[Lihat Dashboard (Statistik Lengkap)]                      │
│  └──────────┘  ├──[Pergantian Kepengurusan + Override]                       │
│                ├──[Approve/Reject Surat Pernyataan]                          │
│                ├──[Lihat Laporan]                                            │
│                └──[Lihat Activity Log]                                       │
│                                                                              │
│  ┌──────────┐                                                                │
│  │ Wakil    │──┬──[Login/Logout]                                             │
│  │ Ketum    │  ├──[Sama seperti Ketua Umum]                                  │
│  └──────────┘  └──[Override Validasi]                                        │
│                                                                              │
│  ┌──────────┐                                                                │
│  │Sekretaris│──┬──[Login/Logout]                                             │
│  │ Umum     │  ├──[CRUD Notulensi Rapat]                                     │
│  └──────────┘  ├──[Kelola Rekrutmen]                                         │
│                ├──[Kelola Event]                                             │
│                ├──[Lihat Laporan]                                            │
│                └──[Manage Anggota (terbatas)]                                │
│                                                                              │
│  ┌──────────┐                                                                │
│  │Bendahara │──┬──[Login/Logout]                                             │
│  │ Umum     │  ├──[Kelola Anggaran Divisi]                                   │
│  └──────────┘  ├──[Kelola Anggaran Event]                                    │
│                ├──[Lihat Laporan Keuangan]                                   │
│                └──[Export Laporan Keuangan PDF]                              │
│                                                                              │
│  ┌──────────┐                                                                │
│  │ Kepala   │──┬──[Login/Logout]                                             │
│  │ Divisi   │  ├──[Lihat Dashboard Divisi]                                   │
│  └──────────┘  ├──[Kelola Anggota Divisi Sendiri]                            │
│                ├──[Kelola Anggaran Divisi]                                   │
│                └──[Kelola Event & Kepanitiaan]                               │
│                                                                              │
│  ┌──────────┐                                                                │
│  │ Kepala   │──┬──[Login/Logout]                                             │
│  │ Unit     │  ├──[Kelola Jadwal Piket]                                      │
│  └──────────┘  ├──[Review & Approve Naskah Redaksi]                          │
│                └──[Kelola Staf Unit]                                         │
│                                                                              │
│  ┌──────────┐                                                                │
│  │ Staf     │──┬──[Login/Logout]                                             │
│  │ Unit     │  ├──[Lihat Jadwal Piket]                                       │
│  └──────────┘  ├──[Buat & Edit Naskah Redaksi]                               │
│                └──[Lihat Event yang Diikuti]                                 │
│                                                                              │
│  ┌──────────┐                                                                │
│  │ Anggota  │──┬──[Login/Logout]                                             │
│  │ (Aktif/  │  ├──[Lihat Dashboard]                                          │
│  │  Pasif)  │  ├──[Edit Profil & Ganti Password]                             │
│  └──────────┘  ├──[Upload Foto Profil]                                       │
│                └──[Perpanjangan Keaktifan (Pasif)]                           │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

> **📌 PLACEHOLDER: Ganti diagram teks di atas dengan gambar Use Case Diagram dari tool UML (draw.io, StarUML, atau Visual Paradigm)**
>
> **Instruksi:** Buat diagram Use Case menggunakan tool UML yang menampilkan:
> - 9 Actor: Super Admin, Ketua Umum, Wakil Ketum, Sekretaris, Bendahara, Kadiv, Kanit, Staf, Anggota
> - Use case utama sesuai tabel kebutuhan fungsional
> - Relasi <<include>> dan <<extend>> jika diperlukan

---

### 3.2 Activity Diagram

#### Activity Diagram: Proses Login

```
┌──────────────────────────────────────────────────────────────────┐
│                    ACTIVITY DIAGRAM: LOGIN                         │
│                                                                    │
│                    ● (Start)                                       │
│                    │                                               │
│                    ▼                                               │
│            ┌───────────────┐                                       │
│            │ Buka Halaman  │                                       │
│            │   Login       │                                       │
│            └───────┬───────┘                                       │
│                    │                                               │
│                    ▼                                               │
│            ┌───────────────┐                                       │
│            │ Input NIM &   │                                       │
│            │   Password    │                                       │
│            └───────┬───────┘                                       │
│                    │                                               │
│                    ▼                                               │
│            ◇ Apakah akun  ─── Ya ──→ [Tampilkan pesan             │
│              terkunci?              "Akun terkunci"]  ──→ ●       │
│            │ (Tidak)                                              │
│            ▼                                                      │
│            ◇ NIM & Password ─ Tidak → [Increment                 │
│              valid?               gagal login]                    │
│            │ (Ya)                    │                            │
│            ▼                         ◇ >= 5x gagal? ─ Ya →       │
│    ┌───────────────┐                 │ (Tidak)      [Lock akun   │
│    │ Reset failed  │                 │               15 menit]   │
│    │ login counter │                 ▼               │           │
│    └───────┬───────┘          [Tampilkan error]      │           │
│            │                       │                 │           │
│            ▼                       └─────────────────┘           │
│    ◇ First login?  ─ Ya → [Halaman ganti password]              │
│            │ (Tidak)         │                                    │
│            ▼                 │                                    │
│    ┌───────────────┐         │                                    │
│    │ Masuk ke      │◄────────┘                                    │
│    │  Dashboard    │                                              │
│    └───────┬───────┘                                              │
│            │                                                      │
│            ▼                                                      │
│           ◉ (End)                                                 │
└──────────────────────────────────────────────────────────────────┘
```

#### Activity Diagram: Pergantian Kepengurusan

```
┌──────────────────────────────────────────────────────────────────┐
│          ACTIVITY DIAGRAM: PERGANTIAN KEPENGURUSAN               │
│                                                                    │
│                    ● (Start)                                       │
│                    │                                               │
│                    ▼                                               │
│          ┌─────────────────┐                                      │
│          │ Ketum Buka Menu │                                      │
│          │  Pergantian     │                                      │
│          └────────┬────────┘                                      │
│                   │                                                │
│                   ▼                                                │
│          ┌─────────────────┐                                      │
│          │ Input Data      │                                      │
│          │ Periode Baru    │                                      │
│          └────────┬────────┘                                      │
│                   │                                                │
│                   ▼                                                │
│          ┌─────────────────┐                                      │
│          │ Pilih Susunan   │                                      │
│          │ Pengurus (12    │                                      │
│          │ jabatan)        │                                      │
│          └────────┬────────┘                                      │
│                   │                                                │
│                   ▼                                                │
│          ◇ Semua calon   ─ Tidak → ◇ Override oleh              │
│            memenuhi syarat?          Ketum/Waketum?               │
│                   │ (Ya)              │(Ya)    │(Tidak)           │
│                   │                   │        [Tampilkan error]  │
│                   │                   ▼                           │
│                   │          ┌─────────────────┐                  │
│                   │          │ Input alasan    │                  │
│                   │          │ override        │                  │
│                   │          │ (min 50 char)   │                  │
│                   │          └────────┬────────┘                  │
│                   │                   │                           │
│                   ▼                   ▼                           │
│          ┌─────────────────────────────┐                          │
│          │ Konfirmasi Pergantian       │                          │
│          └────────┬────────────────────┘                          │
│                   │                                                │
│                   ▼                                                │
│          ┌─────────────────┐                                      │
│          │ Sistem Proses:  │                                      │
│          │ 1. Arsip periode│                                      │
│          │    lama         │                                      │
│          │ 2. Buat periode │                                      │
│          │    baru         │                                      │
│          │ 3. Update jabatan│                                     │
│          │ 4. Update hak   │                                      │
│          │    akses        │                                      │
│          │ 5. Catat riwayat│                                      │
│          └────────┬────────┘                                      │
│                   │                                                │
│                   ▼                                                │
│                  ◉ (End)                                           │
└──────────────────────────────────────────────────────────────────┘
```

#### Activity Diagram: Alur Redaksi Naskah

```
┌──────────────────────────────────────────────────────────────────┐
│           ACTIVITY DIAGRAM: ALUR REDAKSI NASKAH                  │
│                                                                    │
│  ● (Start)                                                        │
│  │                                                                │
│  ▼                                                                │
│  [Staf: Buat Naskah Draft]                                        │
│  │                                                                │
│  ▼                                                                │
│  [Submit untuk Review] ──→ ◇ Kanit Redaksi Review                │
│  │                              │                                │
│  │                         ┌────┴────┐                           │
│  │                         ▼         ▼                           │
│  │                    [Disetujui] [Revisi]                       │
│  │                         │         │                           │
│  │                         │    [Staf revisi naskah]             │
│  │                         │         │                           │
│  │                         │    [Submit ulang]──→ (kembali       │
│  │                         │                      ke Review)     │
│  │                         ▼                                     │
│  │                   [Dipublikasikan]                             │
│  │                         │                                     │
│  │                         ▼                                     │
│  │                        ◉ (End)                                │
│  │                                                                │
│  └── [Ditolak] ──→ ◉ (End)                                      │
└──────────────────────────────────────────────────────────────────┘
```

> **📌 PLACEHOLDER: Ganti diagram teks di atas dengan gambar Activity Diagram dari tool UML**
>
> **Instruksi:** Buat Activity Diagram untuk proses-proses utama:
> 1. Login & Autentikasi
> 2. Pergantian Kepengurusan
> 3. Alur Redaksi Naskah
> 4. Pembentukan Kepanitiaan Event
> 5. Pengajuan Surat Pernyataan

---

### 3.3 ERD (Entity Relationship Diagram)

Berikut adalah Entity Relationship Diagram (ERD) dari sistem SIM UKM Jurnalistik:

#### Tabel-tabel Utama dan Relasinya:

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                           ERD - SIM UKM JURNALISTIK                             │
│                                                                                 │
│  ┌──────────────────────┐         ┌──────────────────────┐                      │
│  │  periode_kepengurusan │         │      anggota          │                     │
│  │──────────────────────│         │──────────────────────│                      │
│  │ PK id                │◄──┐    │ PK id                │                      │
│  │    nama_periode      │   │    │    nim               │                      │
│  │    tahun_mulai       │   │    │    nama_lengkap       │                      │
│  │    tahun_selesai     │   │    │    email             │                      │
│  │    tanggal_mulai     │   │    │    password          │                      │
│  │    tanggal_selesai   │   │    │    tanggal_lahir     │                      │
│  │    is_active         │   │    │    tempat_lahir      │                      │
│  │    status            │   │    │    jenis_kelamin     │                      │
│  │    deskripsi         │   │    │    no_hp             │                      │
│  └──────────┬───────────┘   │    │    alamat            │                      │
│             │               │    │    program_studi     │                      │
│             │               │    │    jurusan           │                      │
│    ┌────────┼────────┐      │    │    foto_profil       │                      │
│    │        │        │      │    │    divisi            │                      │
│    ▼        ▼        ▼      │    │    jabatan_struktural│                      │
│ ┌──────┐┌──────┐┌──────┐   │    │    status_keanggotaan│                      │
│ │riwayat││rekru-││anggaran│  │    │    tanggal_bergabung│                      │
│ │kepeng-││tmen  ││ukm_   │  │    │    is_first_login   │                      │
│ │urusan ││      ││divisi │  │    │    is_locked        │                      │
│ └───┬───┘└──┬───┘└──────┘  │    │    locked_until     │                      │
│     │       │              │    │    failed_login_att │                      │
│     │       │              │    └──────────┬───────────┘                      │
│     ▼       ▼              │               │                                   │
│  [anggota_id]  [periode_id] │    ┌──────────┼──────────────────────────┐       │
│                             │    │          │          │               │       │
│                             │    ▼          ▼          ▼               ▼       │
│                             │ ┌──────┐ ┌──────┐ ┌──────────┐ ┌────────────┐   │
│                             │ │login_│ │notu- │ │jadwal_   │ │naskah_     │   │
│                             │ │histo-│ │lensi │ │shift     │ │redaksi     │   │
│                             │ │ry    │ │      │ │          │ │            │   │
│                             │ │anggo-│ │pence-│ │anggota_id│ │penulis_id  │   │
│                             │ │ta_id │ │tat_id│ │          │ │editor_id   │   │
│                             │ └──────┘ └──────┘ └──────────┘ └────────────┘   │
│                             │                                                   │
│                             │    ┌──────────────────────────────┐              │
│                             │    │          event                │              │
│                             │    │──────────────────────────────│              │
│                             │    │ PK id                        │              │
│                             │    │    nama_event                │              │
│                             │    │    deskripsi                 │              │
│                             │    │    tanggal_mulai             │              │
│                             │    │    tanggal_selesai           │              │
│                             │    │    lokasi                    │              │
│                             │    │    status                    │              │
│                             │    │ FK pic_id ──→ anggota        │              │
│                             │    │ FK periode_id ──→ periode    │              │
│                             │    │    anggaran_total            │              │
│                             │    └──────────┬───────────────────┘              │
│                             │               │                                   │
│                             │    ┌──────────┼──────────────────┐               │
│                             │    │          │                  │               │
│                             │    ▼          ▼                  ▼               │
│                             │ ┌──────────┐ ┌──────────────┐ ┌──────────────┐  │
│                             │ │divisi_   │ │anggaran_event │ │laporan_pasca_│  │
│                             │ │panitia   │ │              │ │event         │  │
│                             │ │──────────│ │event_id      │ │──────────────│  │
│                             │ │event_id  │ │item          │ │event_id      │  │
│                             │ │nama_divisi│ │kategori     │ │pelapor_id    │  │
│                             │ └─────┬────┘ │qty          │ │ringkasan     │  │
│                             │       │      │harga_satuan  │ │evaluasi      │  │
│                             │       ▼      │jml_dianggarkan│ │saran        │  │
│                             │ ┌──────────┐ │jml_realisasi │ └──────────────┘  │
│                             │ │anggota_  │ │bukti_transaksi│                   │
│                             │ │panitia   │ └──────────────┘                    │
│                             │ │──────────│                                     │
│                             │ │event_id  │    ┌──────────────────────┐         │
│                             │ │anggota_id│    │  surat_pernyataan     │         │
│                             │ │divisi_   │    │──────────────────────│         │
│                             │ │ panitia_id│    │ FK anggota_id        │         │
│                             │ │jabatan_  │    │ FK event_id          │         │
│                             │ │ panitia  │    │ FK anggota_panitia_id│         │
│                             │ └──────────┘    │ nomor_surat          │         │
│                             │                 │ status               │         │
│                             │                 │ file_pdf             │         │
│                             │                 │ file_ttd             │         │
│                             │                 │ approver_id          │         │
│                             │                 └──────────────────────┘         │
│                             │                                                   │
│  ┌──────────────────┐      │    ┌──────────────────────┐                      │
│  │ log_override     │      │    │ template_kepanitiaan  │                      │
│  │──────────────────│      │    │──────────────────────│                      │
│  │ anggota_id       │◄─────┘    │    nama_template     │                      │
│  │ pelaku_id        │           │    struktur (JSON)   │                      │
│  │ jabatan_target   │           │    deskripsi         │                      │
│  │ alasan           │           │    is_active         │                      │
│  └──────────────────┘           └──────────────────────┘                      │
│                                                                                 │
│  ┌──────────────────────┐                                                      │
│  │ activity_log          │  (Spatie Activity Log - semua perubahan tercatat)   │
│  │──────────────────────│                                                      │
│  │ log_name, description │                                                     │
│  │ subject_type, subject_id                                                   │
│  │ causer_type, causer_id                                                     │
│  │ properties (JSON)     │                                                     │
│  └──────────────────────┘                                                      │
└─────────────────────────────────────────────────────────────────────────────────┘
```

#### Detail Relasi Antar Tabel:

| Tabel Asal | Relasi | Tabel Tujuan | Kardinalitas | Foreign Key |
|-----------|--------|-------------|--------------|-------------|
| Anggota | hasMany | LoginHistory | 1:N | anggota_id |
| Anggota | hasMany | RiwayatKepengurusan | 1:N | anggota_id |
| Anggota | hasMany | Notulensi (pencatat) | 1:N | pencatat_id |
| Anggota | hasMany | JadwalShift | 1:N | anggota_id |
| Anggota | hasMany | NaskahRedaksi (penulis) | 1:N | penulis_id |
| Anggota | hasMany | NaskahRedaksi (editor) | 1:N | editor_id |
| Anggota | hasMany | Event (PIC) | 1:N | pic_id |
| Anggota | hasMany | AnggotaPanitia | 1:N | anggota_id |
| Anggota | hasMany | SuratPernyataan | 1:N | anggota_id |
| Anggota | hasMany | LaporanPascaEvent | 1:N | pelapor_id |
| Anggota | hasMany | LogOverride | 1:N | anggota_id |
| Anggota | hasMany | LogOverride (pelaku) | 1:N | pelaku_id |
| PeriodeKepengurusan | hasMany | RiwayatKepengurusan | 1:N | periode_id |
| PeriodeKepengurusan | hasMany | Rekrutmen | 1:N | periode_id |
| PeriodeKepengurusan | hasMany | Event | 1:N | periode_id |
| PeriodeKepengurusan | hasMany | AnggaranUkmDivisi | 1:N | periode_id |
| Event | hasMany | DivisiPanitia | 1:N | event_id |
| Event | hasMany | AnggotaPanitia | 1:N | event_id |
| Event | hasMany | AnggaranEvent | 1:N | event_id |
| Event | hasMany | SuratPernyataan | 1:N | event_id |
| Event | hasOne | LaporanPascaEvent | 1:1 | event_id |
| DivisiPanitia | hasMany | AnggotaPanitia | 1:N | divisi_panitia_id |
| AnggotaPanitia | hasOne | SuratPernyataan | 1:1 | anggota_panitia_id |

> **📌 PLACEHOLDER: Ganti diagram teks ERD di atas dengan gambar ERD dari tool database design**
>
> **Instruksi:** Buat ERD menggunakan tool seperti:
> - **draw.io** (gratis, online) — https://app.diagrams.net
> - **MySQL Workbench** — Reverse engineer dari database
> - **dbdiagram.io** — https://dbdiagram.io
>
> Export gambar ERD dan sisipkan di sini.
>
> **Gambar ERD:**
>
> ![ERD Diagram](./placeholder-erd.png)

---

### 3.4 Mockup / Wireframe Antarmuka

Berikut adalah desain antarmuka (mockup/wireframe) dari halaman-halaman utama sistem:

#### 3.4.1 Halaman Login

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                   │
│              ┌─────────────────────────────────┐                  │
│              │     🏛️ UKM JURNALISTIK          │                  │
│              │   Politeknik Negeri Samarinda    │                  │
│              │                                  │                  │
│              │   ┌──────────────────────────┐   │                  │
│              │   │ NIM                      │   │                  │
│              │   └──────────────────────────┘   │                  │
│              │   ┌──────────────────────────┐   │                  │
│              │   │ Password                 │   │                  │
│              │   └──────────────────────────┘   │                  │
│              │                                  │                  │
│              │   [  MASUK  ]                    │                  │
│              │                                  │                  │
│              │   Lupa Password?                 │                  │
│              └─────────────────────────────────┘                  │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

#### 3.4.2 Dashboard Ketua Umum

```
┌─────────────────────────────────────────────────────────────────┐
│ 🏛️ SIM UKM Jurnalistik          🔍 Cari...        👤 Ketum ▼   │
├──────────┬──────────────────────────────────────────────────────┤
│          │                                                      │
│ 📊 Dashboard│   Selamat Datang, Ketua Umum!                     │
│ 👥 Anggota │                                                    │
│ 📝 Notulensi│  ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐   │
│ 📅 Rekrutmen│  │Total   │ │Aktif   │ │Pasif   │ │Alumni  │   │
│ 🎯 Event   │  │  148   │ │  120   │ │   20   │ │    8   │   │
│ 📆 Jadwal  │  └────────┘ └────────┘ └────────┘ └────────┘   │
│ 📰 Naskah  │                                                    │
│ 💰 Anggaran│  ┌──────────────────────┐ ┌──────────────────┐   │
│ 📋 Laporan │  │ Grafik Distribusi    │ │ Status Anggota   │   │
│ 🔐 Keamanan│  │ Anggota per Divisi   │ │ (Pie Chart)      │   │
│ 🔄 Pergantian│ │  (Bar Chart)        │ │                  │   │
│          │  └──────────────────────┘ └──────────────────┘   │
│          │                                                    │
│          │  ┌──────────────────────┐ ┌──────────────────┐   │
│          │  │ Event Mendatang      │ │ Aktivitas Terbaru│   │
│          │  │ • Seminar Jurnalistik│ │ • Anggota baru   │   │
│          │  │ • Workshop Foto      │ │ • Event dibuat   │   │
│          │  └──────────────────────┘ └──────────────────┘   │
└──────────┴──────────────────────────────────────────────────────┘
```

#### 3.4.3 Halaman Daftar Anggota

```
┌─────────────────────────────────────────────────────────────────┐
│ 📊 Dashboard > Anggota                                           │
├──────────┬──────────────────────────────────────────────────────┤
│          │                                                      │
│ 🏛️ SIM   │  Manajemen Anggota                                   │
│          │                                                      │
│ 📊 Dash  │  [+ Tambah Anggota]  [📥 Import Excel]  [📄 Export] │
│ 👥 Anggota│                                                     │
│ 📝 Notulen│  🔍 [Cari NIM/Nama...]  Filter: [Divisi ▼] [Status▼]│
│ 📅 Rekrut │                                                     │
│ 🎯 Event │  ┌────┬──────────┬──────────┬──────┬────────┬────┐ │
│ 📆 Jadwal│  │ No │ NIM      │ Nama     │Divisi│ Jabatan│Aksi│ │
│ 📰 Naskah│  ├────┼──────────┼──────────┼──────┼────────┼────┤ │
│ 💰 Angg  │  │ 1  │ 2301001  │ Ahmad S. │Foto  │ Ketum  │👁✏🗑🔑│
│ 📋 Lap   │  │ 2  │ 2301002  │ Budi P.  │Pers  │ Kadiv  │👁✏🗑🔑│
│ 🔐 Keam  │  │ 3  │ 2301003  │ Citra D. │Video │ Staf   │👁✏🗑🔑│
│ 🔄 Perg  │  └────┴──────────┴──────────┴──────┴────────┴────┘ │
│          │                                                      │
│          │  Menampilkan 1-10 dari 148 data        [1][2][3]... │
└──────────┴──────────────────────────────────────────────────────┘
```

#### 3.4.4 Halaman Manajemen Event

```
┌─────────────────────────────────────────────────────────────────┐
│ 🎯 Dashboard > Event                                             │
├──────────┬──────────────────────────────────────────────────────┤
│          │                                                      │
│ 🏛️ SIM   │  Manajemen Event                                     │
│          │                                                      │
│ 📊 Dash  │  [+ Buat Event Baru]                                 │
│ 👥 Angg  │                                                      │
│ 📝 Notul │  ┌──────────────────────────────────────────────┐   │
│ 📅 Event │  │ 🎯 Seminar Jurnalistik 2026                  │   │
│ 📆 Jadwal│  │ Status: [Direncanakan]  Tanggal: 15 Jul 2026 │   │
│ 📰 Naskah│  │ PIC: Ahmad S.   Anggaran: Rp 5.000.000      │   │
│ 💰 Angg  │  │ Panitia: 25 orang                             │   │
│ 📋 Lap   │  │ [Detail] [Edit] [Kepanitiaan] [Laporan]      │   │
│          │  └──────────────────────────────────────────────┘   │
│          │                                                      │
│          │  ┌──────────────────────────────────────────────┐   │
│          │  │ 📸 Workshop Fotografi                        │   │
│          │  │ Status: [Aktif]  Tanggal: 20 Jun 2026        │   │
│          │  │ PIC: Citra D.   Anggaran: Rp 2.000.000      │   │
│          │  └──────────────────────────────────────────────┘   │
└──────────┴──────────────────────────────────────────────────────┘
```

> **📌 PLACEHOLDER: Ganti wireframe teks di atas dengan gambar mockup dari tool desain**
>
> **Instruksi:** Buat mockup/wireframe menggunakan tool berikut:
> - **Figma** — https://figma.com (gratis)
> - **Balsamiq** — https://balsamiq.com
> - **draw.io** — https://app.diagrams.net
>
> Halaman yang perlu di-mockup:
> 1. Halaman Login
> 2. Dashboard (Ketua Umum, Sekretaris, Bendahara, Kadiv, Kanit, Staf, Anggota)
> 3. Halaman Daftar Anggota (dengan tabel, search, filter)
> 4. Form Tambah/Edit Anggota
> 5. Halaman Manajemen Event
> 6. Halaman Notulensi Rapat
> 7. Halaman Jadwal Piket
> 8. Halaman Naskah Redaksi
> 9. Halaman Anggaran
> 10. Halaman Pergantian Kepengurusan
> 11. Halaman Laporan
>
> **Gambar Mockup:**
>
> ![Mockup Login](./placeholder-mockup-login.png)
> ![Mockup Dashboard](./placeholder-mockup-dashboard.png)
> ![Mockup Anggota](./placeholder-mockup-anggota.png)
> ![Mockup Event](./placeholder-mockup-event.png)

---

## 4. IMPLEMENTASI

### 4.1 Teknologi yang Digunakan

| Komponen | Teknologi | Versi | Fungsi |
|----------|-----------|-------|--------|
| **Backend Framework** | Laravel | 11.x | Framework PHP utama |
| **Bahasa Pemrograman** | PHP | 8.x | Server-side logic |
| **Build Tool** | Vite | 6.0.11 | Asset bundling & hot reload |
| **CSS Framework** | Bootstrap | 5.3.8 | UI components & responsive layout |
| **JavaScript Library** | Alpine.js | 3.15.12 | Interactive UI components |
| **Charting** | Chart.js | 4.5.1 | Data visualization (grafik & diagram) |
| **CSS Preprocessor** | Sass | 1.99.0 | Custom styling |
| **HTTP Client** | Axios | 1.7.4 | API calls |
| **Database** | MySQL / SQLite | - | Relational database |
| **Permission Management** | Spatie Permission | - | Role-based access control (17 role, 80+ permission) |
| **Activity Log** | Spatie Activitylog | - | Audit trail seluruh perubahan |
| **PDF Generation** | DomPDF (Barryvdh) | - | Generate laporan PDF |
| **Excel Export** | Maatwebsite Excel | - | Export/Import data Excel |
| **Authentication** | Laravel Breeze | - | Login, register, password reset |

### 4.2 Arsitektur Sistem

Sistem menggunakan arsitektur **MVC (Model-View-Controller)**:

```
┌─────────────────────────────────────────────────────────────────┐
│                    ARSITEKTUR SISTEM                              │
│                                                                   │
│   👤 User (Browser)                                              │
│        │                                                          │
│        ▼                                                          │
│   ┌─────────────────────────┐                                     │
│   │   VIEW (Blade Template) │  ← Tampilan HTML                  │
│   │   • Bootstrap 5         │    (Bootstrap + Alpine.js)         │
│   │   • Alpine.js           │                                     │
│   │   • Chart.js            │                                     │
│   └──────────┬──────────────┘                                     │
│              │ HTTP Request                                       │
│              ▼                                                    │
│   ┌─────────────────────────┐                                     │
│   │   CONTROLLER            │  ← Logika Aplikasi                │
│   │   • Validasi Input      │    (FormRequest + authorize)       │
│   │   • Authorization       │                                     │
│   │   • Business Logic      │                                     │
│   └──────────┬──────────────┘                                     │
│              │ Eloquent ORM                                       │
│              ▼                                                    │
│   ┌─────────────────────────┐                                     │
│   │   MODEL                 │  ← Data Access                    │
│   │   • Eloquent ORM        │    (Relationships + Scopes)        │
│   │   • Spatie Permission   │                                     │
│   │   • Activity Log        │                                     │
│   └──────────┬──────────────┘                                     │
│              │ SQL Query                                          │
│              ▼                                                    │
│   ┌─────────────────────────┐                                     │
│   │   DATABASE              │  ← Penyimpanan Data               │
│   │   • 31 Tabel            │    (MySQL / SQLite)                │
│   │   • 17 Role             │                                     │
│   │   • 80+ Permission      │                                     │
│   └─────────────────────────┘                                     │
└─────────────────────────────────────────────────────────────────┘
```

### 4.3 Struktur Modul Aplikasi

| Modul | Deskripsi | Controller | Model |
|-------|-----------|-----------|-------|
| **Autentikasi** | Login, logout, reset password, first-login force change | AuthController | Anggota |
| **Dashboard** | 8 jenis dashboard berbasis peran | DashboardController | - |
| **Anggota** | CRUD anggota, import/export, reset password, foto profil | AnggotaController | Anggota |
| **Profil** | Edit profil, ganti password, upload/hapus foto | ProfileController | Anggota |
| **Notulensi** | CRUD notulensi rapat dengan kategori | NotulensiController | Notulensi |
| **Rekrutmen** | Periode rekrutmen, tracking seleksi | RekrutmenController | Rekrutmen |
| **Jadwal Piket** | Generate jadwal acak, kelola shift | JadwalShiftController | JadwalShift |
| **Event** | CRUD event, kepanitiaan, divisi panitia | EventController | Event |
| **Naskah Redaksi** | Workflow naskah (draft → review → publish) | NaskahRedaksiController | NaskahRedaksi |
| **Anggaran Divisi** | CRUD anggaran per divisi per bulan | AnggaranDivisiController | AnggaranUkmDivisi |
| **Anggaran Event** | CRUD item anggaran event + realisasi | AnggaranEventController | AnggaranEvent |
| **Surat Pernyataan** | Generate PDF, upload TTD, approval workflow | SuratPernyataanController | SuratPernyataan |
| **Laporan** | Export PDF/Excel (anggota, event, keuangan) | LaporanController | - |
| **Keamanan** | Login history, activity log, unlock account | LogKeamananController | LoginHistory |
| **Pergantian** | Validasi eligibility, override, transisi | PergantianKepengurusanController | - |
| **Periode** | CRUD periode kepengurusan | PeriodeController | PeriodeKepengurusan |
| **Keaktifan** | Toggle status, perpanjangan, batch update | KeaktifanController | Anggota |
| **Template Kepanitiaan** | CRUD template, duplikasi | TemplateKepanitiaanController | TemplateKepanitiaan |

### 4.4 Fitur Keamanan Implementasi

| Fitur | Implementasi |
|-------|-------------|
| Password Encryption | bcrypt hashing (Laravel built-in) |
| Account Lockout | 5x gagal login → terkunci 15 menit |
| Session Management | Laravel session + middleware auth |
| CSRF Protection | Laravel CSRF token pada setiap form |
| Authorization | 103+ `authorize()` calls di controller |
| Role-Based Access | 17 role, 80+ permission (Spatie) |
| Audit Trail | Spatie Activitylog pada 14 model |
| Input Validation | 17 FormRequest classes |
| SQL Injection Prevention | Eloquent ORM parameterized queries |
| XSS Prevention | Blade auto-escaping |

---

## 5. DEMONSTRASI APLIKASI (SYSTEM DEMO)

> **📌 Bagian ini dilakukan secara live di depan penguji/audiens**

### 5.1 Skenario Demonstrasi

Demonstrasi aplikasi dilakukan dengan skenario berikut:

#### Skenario 1: Autentikasi & Dashboard

| Langkah | Aksi | Yang Ditampilkan |
|---------|------|-----------------|
| 1 | Buka halaman login | Tampilan halaman login yang responsif |
| 2 | Login sebagai Ketua Umum | Dashboard Ketum dengan statistik lengkap |
| 3 | Login sebagai Sekretaris | Dashboard Sekretaris (berbeda) |
| 4 | Login sebagai Bendahara | Dashboard Bendahara dengan data keuangan |
| 5 | Gagal login 5x | Account lockout otomatis |

#### Skenario 2: Manajemen Anggota

| Langkah | Aksi | Yang Ditampilkan |
|---------|------|-----------------|
| 1 | Lihat daftar anggota | Tabel anggota dengan search & filter |
| 2 | Tambah anggota baru | Form input, auto-create account |
| 3 | Import anggota dari Excel | Bulk import dengan template |
| 4 | Edit data anggota | Update data & role otomatis |
| 5 | Reset password | Reset individual & bulk |

#### Skenario 3: Manajemen Event & Kepanitiaan

| Langkah | Aksi | Yang Ditampilkan |
|---------|------|-----------------|
| 1 | Buat event baru | Form event dengan status & PIC |
| 2 | Bentuk kepanitiaan | Assign divisi & anggota panitia |
| 3 | Gunakan template kepanitiaan | Apply template untuk kepanitiaan cepat |
| 4 | Lihat anggaran event | Item anggaran & realisasi |

#### Skenario 4: Pergantian Kepengurusan

| Langkah | Aksi | Yang Ditampilkan |
|---------|------|-----------------|
| 1 | Buka menu pergantian | Form periode baru |
| 2 | Pilih susunan pengurus | Dropdown calon per jabatan |
| 3 | Sistem validasi eligibility | Warning jika tidak memenuhi syarat |
| 4 | Override validasi | Input alasan override |
| 5 | Konfirmasi pergantian | Transisi otomatis |

#### Skenario 5: Fitur Utama Lainnya

| Langkah | Aksi | Yang Ditampilkan |
|---------|------|-----------------|
| 1 | Buat notulensi rapat | Form notulensi dengan daftar hadir |
| 2 | Buat naskah redaksi | Workflow draft → review → publish |
| 3 | Generate jadwal piket | Acak otomatis |
| 4 | Export laporan PDF/Excel | Download file laporan |
| 5 | Surat pernyataan | Generate → TTD → Approve |

> **📌 PLACEHOLDER: Screenshot setiap langkah demonstrasi**
>
> **Instruksi:** Ambil screenshot setiap langkah demonstrasi dan sisipkan di sini sebagai dokumentasi.
>
> ![Demo Screenshot 1](./placeholder-demo-1.png)
> ![Demo Screenshot 2](./placeholder-demo-2.png)

---

## 6. PENGUJIAN SISTEM

### 6.1 Metode Pengujian

Pengujian sistem menggunakan **Black Box Testing** dan **Automated Testing**:

| Metode | Deskripsi | Tool |
|--------|-----------|------|
| **Unit Testing** | Pengujian individual method/fungsi | PHPUnit |
| **Feature Testing** | Pengujian alur fitur secara end-to-end | PHPUnit + Laravel Testing |
| **Authorization Testing** | Pengujian hak akses per role | PHPUnit (46 test permission) |
| **Input Validation Testing** | Pengujian validasi input form | PHPUnit |
| **Black Box Testing** | Pengujian fungsional tanpa melihat kode | Manual testing |

### 6.2 Test Suite

Sistem dilengkapi dengan **test suite komprehensif** yang mencakup:

| File Test | Kategori | Jumlah Test | Deskripsi |
|-----------|----------|-------------|-----------|
| `AnggotaCrudTest.php` | Feature | 8+ | CRUD anggota, import, foto profil |
| `AuthorizationMatrixTest.php` | Feature | 46+ | Validasi permission per role |
| `EventCrudTest.php` | Feature | 6+ | CRUD event & kepanitiaan |
| `InputValidationTest.php` | Feature | 10+ | Validasi input form |
| `JadwalPiketTest.php` | Feature | 5+ | Generate jadwal & CRUD |
| `KeaktifanTest.php` | Feature | 4+ | Toggle status & perpanjangan |
| `LaporanTest.php` | Feature | 3+ | Export laporan PDF/Excel |
| `NaskahRedaksiTest.php` | Feature | 5+ | Workflow naskah redaksi |
| `NotulensiCrudTest.php` | Feature | 5+ | CRUD notulensi |
| `PeriodePergantianTest.php` | Feature | 6+ | Validasi eligibility & override |
| `PasswordResetTest.php` | Feature | 4+ | Reset password individual & bulk |
| `Auth/LoginTest.php` | Feature | 3+ | Login, lockout, session |

### 6.3 Contoh Kasus Pengujian

#### Test Case 1: Login

| ID | Skenario | Input | Hasil yang Diharapkan | Status |
|----|----------|-------|----------------------|--------|
| TC-001 | Login berhasil | NIM valid, password benar | Masuk ke dashboard | ✅ Pass |
| TC-002 | Login gagal (password salah) | NIM valid, password salah | Pesan error, counter increment | ✅ Pass |
| TC-003 | Account lockout | 5x gagal login | Akun terkunci 15 menit | ✅ Pass |
| TC-004 | Login akun terkunci | NIM terkunci | Pesan akun terkunci | ✅ Pass |
| TC-005 | First login redirect | Login pertama kali | Redirect ke halaman ganti password | ✅ Pass |

#### Test Case 2: Manajemen Anggota

| ID | Skenario | Input | Hasil yang Diharapkan | Status |
|----|----------|-------|----------------------|--------|
| TC-006 | Tambah anggota valid | Data lengkap | Anggota berhasil ditambah | ✅ Pass |
| TC-007 | NIM duplikat | NIM sudah ada | Error validasi | ✅ Pass |
| TC-008 | Import Excel valid | File .xlsx | Data terimport | ✅ Pass |
| TC-009 | Import file invalid | File .txt | Error format | ✅ Pass |
| TC-010 | Hapus anggota | Anggota existing | Soft delete berhasil | ✅ Pass |

#### Test Case 3: Authorization

| ID | Skenario | Role | Akses | Hasil yang Diharapkan | Status |
|----|----------|------|-------|----------------------|--------|
| TC-011 | Admin akses semua | Super Admin | Semua halaman | 200 OK | ✅ Pass |
| TC-012 | Staf akses anggota | Staf | /anggota | 403 Forbidden | ✅ Pass |
| TC-013 | Bendahara akses anggaran | Bendahara | /anggaran-divisi | 200 OK | ✅ Pass |
| TC-014 | Anggota akses laporan | Anggota | /laporan | 403 Forbidden | ✅ Pass |

#### Test Case 4: Pergantian Kepengurusan

| ID | Skenario | Input | Hasil yang Diharapkan | Status |
|----|----------|-------|----------------------|--------|
| TC-015 | Calon memenuhi syarat | Anggota >= 2 tahun | Validasi pass | ✅ Pass |
| TC-016 | Calon tidak memenuhi syarat | Anggota < 1 tahun | Warning eligibility | ✅ Pass |
| TC-017 | Override dengan alasan | Alasan 50+ char | Override berhasil | ✅ Pass |
| TC-018 | Override tanpa alasan | Alasan < 50 char | Error validasi | ✅ Pass |
| TC-019 | Satu anggota 2 jabatan | Duplikasi | Error | ✅ Pass |

### 6.4 Menjalankan Test Suite

```bash
# Jalankan seluruh test
php artisan test

# Jalankan test tertentu
php artisan test --filter=AuthorizationMatrixTest

# Jalankan dengan verbose
php artisan test -v
```

> **📌 PLACEHOLDER: Screenshot hasil pengujian / terminal output**
>
> ![Test Results](./placeholder-test-results.png)

---

## 7. HASIL DAN EVALUASI

### 7.1 Hasil yang Dicapai

Sistem SIM UKM Jurnalistik telah berhasil dikembangkan dengan fitur-fitur berikut:

| No | Fitur | Status | Keterangan |
|----|-------|--------|-----------|
| 1 | Autentikasi & Keamanan | ✅ Selesai | Login NIM, lockout, reset password |
| 2 | Dashboard Role-Based (8 jenis) | ✅ Selesai | Dashboard berbeda per jabatan |
| 3 | Manajemen Anggota (CRUD + Import) | ✅ Selesai | Termasuk foto profil, search, filter |
| 4 | Manajemen Kepengurusan | ✅ Selesai | Periode, riwayat, validasi eligibility |
| 5 | Pergantian Kepengurusan + Override | ✅ Selesai | Validasi otomatis + override log |
| 6 | Notulensi Rapat | ✅ Selesai | 6 kategori rapat, daftar hadir |
| 7 | Rekrutmen Anggota Baru | ✅ Selesai | Tracking multi-tahap |
| 8 | Jadwal Piket Acak | ✅ Selesai | Generate otomatis per hari |
| 9 | Manajemen Event & Kepanitiaan | ✅ Selesai | Divisi panitia, template reusable |
| 10 | Alur Redaksi Naskah | ✅ Selesai | Draft → Review → Revisi → Publish |
| 11 | Anggaran Divisi & Event | ✅ Selesai | Tracking anggaran + realisasi |
| 12 | Surat Pernyataan + Approval | ✅ Selesai | Generate PDF, TTD, workflow |
| 13 | Laporan & Export (PDF/Excel) | ✅ Selesai | 3 jenis laporan |
| 14 | Audit Trail (Activity Log) | ✅ Selesai | Seluruh perubahan tercatat |
| 15 | Authorization Matrix (17 role, 80+ perm) | ✅ Selesai | 103+ authorization check |
| 16 | Responsive Design | ✅ Selesai | Desktop, tablet, mobile |

### 7.2 Kelebihan Sistem

| No | Kelebihan | Penjelasan |
|----|-----------|-----------|
| 1 | **Role-Based Access Control yang Komprehensif** | 17 role dan 80+ permission memastikan setiap jabatan hanya mengakses fitur yang sesuai |
| 2 | **Audit Trail Lengkap** | Setiap perubahan data tercatat (siapa, kapan, apa) menggunakan Spatie Activitylog |
| 3 | **Validasi Eligibility Otomatis** | Sistem mengecek syarat jabatan secara otomatis saat pergantian kepengurusan |
| 4 | **Dashboard yang Personalisasi** | 8 jenis dashboard berbeda menampilkan informasi yang relevan per jabatan |
| 5 | **100% Open Source (Zero Cost)** | Seluruh teknologi yang digunakan gratis tanpa biaya lisensi |
| 6 | **Responsive Design** | Bisa diakses dari perangkat apapun (desktop, tablet, mobile) |
| 7 | **Automated Testing** | Test suite komprehensif dengan 100+ test case |
| 8 | **Export Multi-Format** | Mendukung export PDF dan Excel untuk berbagai laporan |
| 9 | **Account Security** | Lockout otomatis, password encryption, session management |
| 10 | **Template Reusable** | Template kepanitiaan yang bisa digunakan berulang |

### 7.3 Kekurangan Sistem

| No | Kekurangan | Rencana Perbaikan |
|----|-----------|-----------------|
| 1 | **Belum ada notifikasi real-time** | Implementasi WebSocket/Laravel Echo |
| 2 | **Belum ada mobile app** | Pengembangan Progressive Web App (PWA) |
| 3 | **Belum ada backup otomatis** | Implementasi scheduled backup dengan Laravel Task Scheduling |
| 4 | **Belum ada multi-bahasa** | Implementasi Laravel Localization |
| 5 | **Hosting masih on-premise** | Migrasi ke cloud (VPS/shared hosting) |
| 6 | **Belum ada API untuk pihak ketiga** | Implementasi REST API jika diperlukan |
| 7 | **Fitur chat internal belum ada** | Bisa ditambahkan menggunakan Pusher/Laravel Echo |
| 8 | **Belum ada integrasi media sosial** | Auto-publish naskah ke website/sosmed |

---

## 8. KESIMPULAN

### 8.1 Pencapaian Tujuan Proyek

Berdasarkan hasil pengembangan dan pengujian sistem, dapat disimpulkan bahwa:

1. ✅ **Tujuan mendigitalisasi administrasi** — Tercapai. Seluruh data anggota, notulensi, keuangan, dan kegiatan telah terdigitalisasi dalam satu platform terpusat.

2. ✅ **Tujuan menyediakan sumber data terpercaya** — Tercapai. Database tunggal dengan 31 tabel menyimpan seluruh data organisasi secara terstruktur dan konsisten.

3. ✅ **Tujuan memastikan transisi kepengurusan** — Tercapai. Sistem secara otomatis memvalidasi syarat jabatan dan mencatat riwayat kepengurusan.

4. ✅ **Tujuan mendokumentasikan kegiatan** — Tercapai. Notulensi, event, laporan pasca event, dan activity log tersimpan secara permanen.

5. ✅ **Tujuan meningkatkan transparansi keuangan** — Tercapai. Anggaran divisi dan event tercatat digital dengan bukti transaksi.

6. ✅ **Tujuan mengelola alur redaksi** — Tercapai. Workflow naskah dari draft hingga publikasi terkelola dengan jelas.

7. ✅ **Tujuan menyediakan akses berbasis peran** — Tercapai. 17 role dengan 80+ permission dan 103+ authorization check.

### 8.2 Manfaat Sistem yang Dibangun

| Aspek | Manfaat |
|-------|--------|
| **Administrasi** | Penghematan waktu pengelolaan data hingga 70% dibandingkan manual |
| **Transparansi** | Seluruh pengurus dapat melihat data sesuai hak aksesnya |
| **Akuntabilitas** | Audit trail memastikan setiap perubahan dapat dipertanggungjawabkan |
| **Keberlanjutan** | Riwayat kepengurusan dan data organisasi tersimpan permanen |
| **Efisiensi** | Automasi validasi, jadwal piket, dan laporan mengurangi beban kerja |
| **Keamanan** | Password encryption, account lockout, dan authorization berlapis |

### 8.3 Hasil Pengujian Sistem

| Kategori | Jumlah Test | Pass | Fail | Persentase |
|----------|-------------|------|------|-----------|
| Authorization Matrix | 46 | 46 | 0 | 100% |
| CRUD Operations | 30+ | 30+ | 0 | 100% |
| Input Validation | 10+ | 10+ | 0 | 100% |
| Authentication & Security | 8+ | 8+ | 0 | 100% |
| **TOTAL** | **100+** | **100+** | **0** | **100%** |

> **Catatan:** Sesuaikan angka test dengan hasil actual saat menjalankan `php artisan test`.

### 8.4 Penutup

Sistem Informasi Manajemen UKM Jurnalistik telah berhasil dikembangkan sebagai solusi digital untuk mengatasi permasalahan administrasi organisasi yang selama ini dilakukan secara manual. Dengan arsitektur MVC yang terstruktur, role-based access control yang komprehensif, dan audit trail yang lengkap, sistem ini siap digunakan untuk mendukung kelancaran kegiatan organisasi UKM Jurnalistik Politeknik Negeri Samarinda.

Sistem ini bersifat **open source** dan **zero cost**, sehingga dapat terus dikembangkan dan disesuaikan dengan kebutuhan organisasi di masa mendatang.

---

## LAMPIRAN

### A. Daftar Tabel Database (31 Tabel)

| No | Nama Tabel | Deskripsi |
|----|-----------|-----------|
| 1 | `anggota` | Data anggota UKM |
| 2 | `periode_kepengurusan` | Periode kepengurusan per tahun |
| 3 | `riwayat_kepengurusan` | Riwayat jabatan per periode |
| 4 | `login_history` | Catatan percobaan login |
| 5 | `log_override` | Log override eligibility |
| 6 | `notulensi` | Catatan rapat |
| 7 | `rekrutmen` | Data rekrutmen anggota baru |
| 8 | `jadwal_shift` | Jadwal piket anggota |
| 9 | `anggaran_ukm_divisi` | Anggaran per divisi per bulan |
| 10 | `naskah_redaksi` | Naskah dan status redaksi |
| 11 | `event` | Data event/kegiatan |
| 12 | `divisi_panitia` | Divisi kepanitiaan per event |
| 13 | `anggota_panitia` | Penugasan anggota ke panitia |
| 14 | `template_kepanitiaan` | Template kepanitiaan reusable |
| 15 | `surat_pernyataan` | Surat pernyataan anggota pasif |
| 16 | `anggaran_event` | Item anggaran per event |
| 17 | `laporan_pasca_event` | Laporan evaluasi pasca event |
| 18 | `activity_log` | Log aktivitas (Spatie) |
| 19 | `roles` | Tabel role (Spatie) |
| 20 | `permissions` | Tabel permission (Spatie) |
| 21 | `model_has_roles` | Mapping role ke user |
| 22 | `model_has_permissions` | Mapping permission ke user |
| 23 | `role_has_permissions` | Mapping permission ke role |
| 24-31 | System tables | Session, cache, jobs, migrations, dll |

### B. Daftar Role (17 Role)

| No | Role | Jabatan |
|----|------|--------|
| 1 | `admin` | Super Administrator |
| 2 | `ketua_umum` | Ketua Umum |
| 3 | `wakil_ketua_umum` | Wakil Ketua Umum |
| 4 | `sekretaris_umum_1` | Sekretaris Umum 1 |
| 5 | `sekretaris_umum_2` | Sekretaris Umum 2 |
| 6 | `bendahara_umum_1` | Bendahara Umum 1 |
| 7 | `bendahara_umum_2` | Bendahara Umum 2 |
| 8 | `kadiv_fotografi` | Kepala Divisi Fotografi |
| 9 | `kadiv_pers_penyiaran` | Kepala Divisi Pers & Penyiaran |
| 10 | `kadiv_videografi` | Kepala Divisi Videografi |
| 11 | `kanit_kominfo` | Kepala Unit Kominfo |
| 12 | `kanit_redaksi` | Kepala Unit Redaksi |
| 13 | `kanit_inventory` | Kepala Unit Inventory |
| 14 | `staf` | Staf Unit |
| 15 | `anggota_aktif` | Anggota Aktif |
| 16 | `anggota_pasif` | Anggota Pasif |
| 17 | `alumni` | Alumni |

---

**Dokumen ini disiapkan untuk presentasi proyek perangkat lunak SIM UKM Jurnalistik.**

*Politeknik Negeri Samarinda — 2026*
