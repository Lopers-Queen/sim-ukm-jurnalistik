# 📘 BUKU PANDUAN SISTEM
# Sistem Informasi Manajemen UKM Jurnalistik
## Politeknik Negeri Samarinda

**Versi:** 1.0.0 | **Tahun:** 2026

---

## DAFTAR ISI

1. [Tentang Sistem](#1-tentang-sistem)
2. [Cara Mengakses Sistem](#2-cara-mengakses-sistem)
3. [Panduan Login & Keamanan Akun](#3-panduan-login--keamanan-akun)
4. [Dashboard](#4-dashboard)
5. [Manajemen Anggota](#5-manajemen-anggota)
6. [Manajemen Kepengurusan](#6-manajemen-kepengurusan)
7. [Notulensi Rapat](#7-notulensi-rapat)
8. [Rekrutmen Anggota Baru](#8-rekrutmen-anggota-baru)
9. [Jadwal Piket](#9-jadwal-piket)
10. [Alur Redaksi Naskah](#10-alur-redaksi-naskah)
11. [Manajemen Anggaran](#11-manajemen-anggaran)
12. [Manajemen Event & Kepanitiaan](#12-manajemen-event--kepanitiaan)
13. [Surat Pernyataan Anggota Pasif](#13-surat-pernyataan-anggota-pasif)
14. [Laporan & Ekspor Data](#14-laporan--ekspor-data)
15. [Pengaturan Profil & Keamanan](#15-pengaturan-profil--keamanan)
16. [FAQ (Pertanyaan Umum)](#16-faq-pertanyaan-umum)

---

## 1. TENTANG SISTEM

### 1.1 Apa itu SIM UKM Jurnalistik?

SIM UKM Jurnalistik adalah **sistem informasi berbasis web** yang dirancang khusus untuk mengelola seluruh kegiatan organisasi UKM Jurnalistik Politeknik Negeri Samarinda secara digital dan terpusat.

Bayangkan sistem ini seperti **"kantor virtual"** UKM — semua data anggota, notulensi rapat, keuangan, kepanitiaan event, hingga jadwal piket tersimpan rapi di satu tempat yang bisa diakses kapan saja dan dari mana saja.

**Tujuan Sistem:**
- Mendigitalisasi seluruh administrasi UKM yang sebelumnya dilakukan manual
- Menyediakan satu sumber data terpercaya untuk seluruh pengurus dan anggota
- Memastikan transisi kepengurusan berjalan lancar dengan validasi otomatis
- Mendokumentasikan setiap kegiatan UKM secara terstruktur

**Masalah yang Diselesaikan:**

| Sebelum (Manual) | Sesudah (Dengan SIM) |
|---|---|
| Data anggota tersebar di Google Spreadsheet | Data terpusat, bisa dicari & difilter |
| Notulensi rapat hilang di WhatsApp Group | Tersimpan permanen, bisa diakses kapan saja |
| Pergantian kepengurusan tanpa validasi | Syarat jabatan dicek otomatis oleh sistem |
| Keuangan dicatat di buku tulis | Tercatat digital dengan bukti transaksi |
| Kepanitiaan event dibentuk lewat chat | Terstruktur dengan aturan & template |
| Tidak ada riwayat kepengurusan | Setiap jabatan tercatat per periode |

**Siapa yang Menggunakan:**
- Seluruh pengurus BPI (Badan Pengurus Inti)
- Kepala Divisi & Kepala Unit
- Staf unit
- Anggota aktif & pasif UKM Jurnalistik

**Keunggulan:**
- ✅ 100% gratis (teknologi open source)
- ✅ Bisa diakses dari HP, tablet, dan laptop
- ✅ Data aman dengan enkripsi password
- ✅ Setiap perubahan tercatat (audit trail)

### 1.2 Bagaimana Sistem Ini Bekerja? (Arsitektur)

Untuk memahami cara kerja sistem, bayangkan sebuah **restoran**:

```
┌─────────────────────────────────────────────────────────────────┐
│                    CARA KERJA SISTEM                            │
│                                                                 │
│   👤 Pengguna                                                   │
│   (HP/Laptop/Tablet)                                            │
│        │                                                        │
│        ▼                                                        │
│   ┌─────────────────────┐                                       │
│   │    🎨 FRONTEND      │  ← Tampilan yang Anda lihat & klik   │
│   │   "Ruang Makan"     │     (Tombol, tabel, form, grafik)    │
│   │                     │                                       │
│   │   Bootstrap 5       │  → Mengatur tampilan agar rapi       │
│   │   Alpine.js         │  → Membuat tampilan interaktif       │
│   │   Chart.js          │  → Menampilkan grafik & diagram      │
│   └────────┬────────────┘                                       │
│            │ mengirim permintaan                                 │
│            ▼                                                     │
│   ┌─────────────────────┐                                       │
│   │    ⚙️ BACKEND       │  ← Otak di balik layar               │
│   │    "Dapur"          │     (Memproses semua logika)          │
│   │                     │                                       │
│   │   Laravel 11 (PHP)  │  → Memproses data & aturan bisnis   │
│   │   Spatie Permission │  → Mengatur hak akses per jabatan   │
│   │   Spatie Activitylog│  → Mencatat semua aktivitas          │
│   │   DomPDF            │  → Membuat file PDF                  │
│   │   Maatwebsite Excel │  → Membuat file Excel                │
│   └────────┬────────────┘                                       │
│            │ menyimpan/mengambil data                            │
│            ▼                                                     │
│   ┌─────────────────────┐                                       │
│   │    🗄️ DATABASE      │  ← Tempat semua data disimpan       │
│   │    "Gudang"         │     (Aman & terstruktur)             │
│   │                     │                                       │
│   │   MySQL             │  → 31 tabel data                     │
│   │                     │  → 148 data anggota                  │
│   │                     │  → 17 jenis role/jabatan             │
│   │                     │  → 80 jenis hak akses                │
│   └─────────────────────┘                                       │
└─────────────────────────────────────────────────────────────────┘
```

#### 🎨 Frontend — "Ruang Makan Restoran"

Frontend adalah **tampilan yang Anda lihat dan sentuh** saat membuka sistem di browser. Seperti ruang makan restoran — Anda melihat menu, memilih makanan, dan menerima pesanan di sini.

Apa saja yang termasuk frontend:
- Halaman login dengan form NIM & password
- Dashboard dengan grafik dan statistik
- Tabel daftar anggota yang bisa dicari & difilter
- Form untuk mengisi data (tambah anggota, buat event, dll)
- Tombol-tombol aksi (Simpan, Hapus, Edit, Export PDF)
- Sidebar menu navigasi yang berubah sesuai jabatan

**Teknologi yang digunakan:**

| Teknologi | Fungsi | Analogi |
|---|---|---|
| **Bootstrap 5** | Membuat tampilan rapi, responsif, dan modern di semua ukuran layar | Dekorasi & tata letak ruang makan |
| **Alpine.js** | Membuat tampilan interaktif (dropdown, modal, toggle) | Pelayan yang sigap merespons |
| **Chart.js** | Menampilkan grafik batang, lingkaran, dan statistik visual | Papan informasi bergambar |
| **Blade Template** | Menyusun halaman HTML secara terstruktur | Cetakan/template menu restoran |

#### ⚙️ Backend — "Dapur Restoran"

Backend adalah **otak di balik layar** yang memproses semua permintaan. Seperti dapur restoran — tidak terlihat oleh pengunjung, tapi di sinilah semua "masakan" (data) diolah.

Apa yang dilakukan backend:
- Memverifikasi login (cek NIM & password benar atau tidak)
- Memvalidasi syarat jabatan saat pergantian kepengurusan
- Menghitung pembagian jadwal piket secara acak
- Memproses form yang Anda isi dan menyimpan datanya
- Mengecek hak akses (apakah Anda boleh mengakses halaman tertentu)
- Menghasilkan file PDF dan Excel saat Anda export laporan
- Mencatat setiap aktivitas untuk keperluan audit

**Teknologi yang digunakan:**

| Teknologi | Fungsi | Analogi |
|---|---|---|
| **Laravel 11** | Framework PHP utama — mengelola seluruh logika aplikasi | Kepala koki & resep masakan |
| **PHP 8.x** | Bahasa pemrograman yang menjalankan Laravel | Bahasa yang digunakan di dapur |
| **Spatie Permission** | Mengatur 17 role dan 80 hak akses per jabatan | Kartu identitas karyawan |
| **Spatie Activitylog** | Mencatat semua perubahan data (siapa, kapan, apa) | CCTV dapur |
| **DomPDF** | Mengkonversi data menjadi file PDF yang bisa di-download | Mesin cetak bon/laporan |
| **Maatwebsite Excel** | Mengkonversi data menjadi file Excel (.xlsx) | Mesin cetak spreadsheet |
| **Laravel Breeze** | Menangani autentikasi (login, logout, reset password) | Satpam pintu masuk |

#### 🗄️ Database — "Gudang Penyimpanan Restoran"

Database adalah **tempat semua data disimpan** secara aman dan terstruktur. Seperti gudang restoran — menyimpan semua bahan baku yang terorganisir rapi di rak-rak berbeda.

Data apa saja yang disimpan:

| Tabel (Rak Gudang) | Isi | Jumlah Tabel |
|---|---|---|
| **Anggota** | Data pribadi, NIM, password, jabatan, divisi | 1 tabel utama |
| **Periode & Riwayat** | Kepengurusan per periode, riwayat jabatan | 2 tabel |
| **Event & Kepanitiaan** | Data event, divisi panitia, anggota panitia | 3 tabel |
| **Notulensi** | Catatan rapat (agenda, hasil, daftar hadir) | 1 tabel |
| **Naskah Redaksi** | Naskah, status review, catatan editor | 1 tabel |
| **Anggaran** | Keuangan divisi & event (pemasukan/pengeluaran) | 2 tabel |
| **Rekrutmen** | Data calon anggota, status seleksi | 1 tabel |
| **Jadwal Piket** | Jadwal piket per hari per anggota | 1 tabel |
| **Surat Pernyataan** | Surat untuk anggota pasif, status approval | 1 tabel |
| **Keamanan** | Riwayat login, log aktivitas, log override | 3 tabel |
| **Template** | Template kepanitiaan yang bisa digunakan ulang | 1 tabel |
| **Sistem** | Session, cache, permission, role | 10 tabel |
| | **TOTAL** | **31 tabel** |

**Teknologi yang digunakan:**

| Teknologi | Fungsi | Analogi |
|---|---|---|
| **MySQL** | Sistem database relasional — menyimpan & mengelola data | Gudang dengan rak berlabel |

#### 🔄 Bagaimana Ketiganya Bekerja Bersama?

Contoh: **Ketika Anda mengklik "Tambah Anggota"**

```
1. 🎨 FRONTEND: Anda mengklik tombol "Tambah Anggota" dan mengisi form
        ↓
2. ⚙️ BACKEND:  Laravel menerima data, memvalidasi input,
                mengenkripsi password dari tanggal lahir,
                dan meng-assign role sesuai jabatan
        ↓
3. 🗄️ DATABASE: Data anggota baru disimpan ke tabel 'anggota',
                role disimpan ke tabel 'model_has_roles'
        ↓
4. ⚙️ BACKEND:  Laravel menyiapkan pesan sukses
        ↓
5. 🎨 FRONTEND: Halaman menampilkan "Anggota berhasil ditambahkan!"
                dan tabel ter-update dengan data baru
```

#### 💰 Semua Teknologi 100% GRATIS

| Komponen | Teknologi | Lisensi | Biaya |
|---|---|---|---|
| Backend | Laravel 11 + PHP | MIT (Open Source) | **Rp 0** |
| Frontend | Bootstrap 5 + Alpine.js | MIT (Open Source) | **Rp 0** |
| Database | MySQL | GPL (Open Source) | **Rp 0** |
| PDF Export | DomPDF | LGPL (Open Source) | **Rp 0** |
| Excel Export | Maatwebsite Excel | MIT (Open Source) | **Rp 0** |
| Hak Akses | Spatie Permission | MIT (Open Source) | **Rp 0** |
| Audit Log | Spatie Activitylog | MIT (Open Source) | **Rp 0** |
| | | **Total Biaya** | **Rp 0** |

### 1.3 Struktur Organisasi UKM yang Tercermin di Sistem

```
┌──────────────────────────────────────┐
│         BPI (Pengurus Inti)           │
│  Ketum, Waketum, Sekum 1&2, Bendum 1&2  │
└──────────────────┬───────────────────┘
                   │
      ┌────────────┼────────────┐
      │            │            │
   DIVISI        DIVISI       DIVISI
  Fotografi   Pers & Penyiaran  Videografi
  (Kadiv)       (Kadiv)        (Kadiv)
      │            │            │
   Anggota      Anggota      Anggota
      
      ┌────────────┼────────────┐
      │            │            │
    UNIT         UNIT         UNIT
   Kominfo      Redaksi     Inventory
   (Kanit)      (Kanit)      (Kanit)
      │            │            │
    Staf         Staf         Staf
```

### 1.4 Hak Akses per Jabatan

Setiap jabatan memiliki tampilan dan kemampuan yang **berbeda** di sistem. Menu yang muncul di sidebar akan menyesuaikan dengan jabatan Anda.

| Jabatan | Apa yang Bisa Dilakukan |
|---|---|
| **Super Admin** | Semua fitur + Reset password anggota (individual & bulk) + Override validasi + Approve surat pernyataan |
| **Ketua Umum** | Semua fitur + Override validasi + Approve surat pernyataan |
| **Wakil Ketum** | Semua fitur + Override validasi + Approve surat pernyataan |
| **Sekretaris Umum 1 & 2** | Kelola notulensi, anggota, event, rekrutmen, laporan |
| **Bendahara Umum 1 & 2** | Kelola anggaran divisi & event, anggota, laporan keuangan |
| **Kepala Divisi (Kadiv)** | Kelola anggota divisi sendiri, event, anggaran divisi |
| **Kepala Unit (Kanit)** | Kelola staf unit sendiri, jadwal piket, naskah redaksi |
| **Staf Unit** | Akses jadwal piket, naskah, event yang diikuti |
| **Anggota Aktif** | Lihat info divisi, event, profil sendiri |
| **Anggota Pasif** | Akses terbatas: profil & form perpanjangan keaktifan |

---

## 2. CARA MENGAKSES SISTEM

### 2.1 Perangkat yang Didukung

Sistem bisa diakses dari **semua perangkat** yang memiliki browser:

| Perangkat | Didukung | Catatan |
|---|---|---|
| 💻 Laptop/Komputer | ✅ | Tampilan paling optimal |
| 📱 Smartphone | ✅ | Layout otomatis menyesuaikan layar HP |
| 📱 Tablet | ✅ | Layout responsif |

**Browser yang didukung:**
- Google Chrome (direkomendasikan)
- Mozilla Firefox
- Microsoft Edge
- Safari (iOS/Mac)

### 2.2 Alamat Website

Akses sistem melalui browser di alamat:

```
http://[alamat-server]:8000
```

> **Catatan:** Alamat lengkap akan diberikan oleh Ketua Umum atau Tim Pengembang.

---

## 3. PANDUAN LOGIN & KEAMANAN AKUN

### 3.1 Login Pertama Kali

Setiap anggota baru yang didaftarkan oleh admin akan mendapat akun otomatis:
- **Username:** NIM mahasiswa
- **Password awal:** Tanggal lahir (format DDMMYYYY)

📌 **Langkah 1:** Buka website SIM UKM Jurnalistik di browser
📌 **Langkah 2:** Masukkan **NIM** sebagai username
📌 **Langkah 3:** Masukkan **tanggal lahir** sebagai password awal
- Format: DDMMYYYY (tanggal-bulan-tahun, tanpa spasi/strip)
- Contoh: Lahir 7 Agustus 2005 → password: `07082005`
📌 **Langkah 4:** Klik tombol **"Masuk"**
📌 **Langkah 5:** Sistem akan **MEMINTA** Anda mengganti password
- Ini adalah langkah keamanan yang disarankan
- Password baru minimal 8 karakter
- Harus mengandung huruf besar, huruf kecil, dan angka
- Anda juga bisa memilih **“Lewati”** untuk menggunakan password default dan mengganti nanti melalui menu Profil
📌 **Langkah 6:** Setelah ganti password (atau melewati), Anda masuk ke **Dashboard**

📸 *Screenshot: Halaman Login*
📸 *Screenshot: Halaman Ganti Password Pertama Kali*

### 3.2 Login Selanjutnya

📌 Masukkan **NIM** dan **password yang sudah diganti**
📌 Klik **"Masuk"**
📌 Anda langsung masuk ke Dashboard sesuai jabatan

### 3.3 Lupa Password

Ada 2 cara untuk mengatasi lupa password:

**Cara 1: Reset via Email**
📌 Klik **"Lupa Password?"** di halaman login
📌 Masukkan email yang terdaftar di sistem
📌 Cek email, klik link reset password
📌 Masukkan password baru
📌 Login kembali dengan password baru

**Cara 2: Hubungi Super Admin**
📌 Hubungi Super Admin atau Ketua Umum
📌 Admin akan **reset password** ke `12345678` (atau password custom)
📌 Anda akan diminta mengganti password saat login berikutnya (bisa dilewati)

### 3.4 Keamanan Akun

Sistem dilengkapi fitur keamanan berlapis:

- 🔒 **Account Lockout:** Akun terkunci otomatis jika **5 kali salah password**
- ⏱️ **Session Timeout:** Otomatis logout jika tidak digunakan selama beberapa waktu
- 📋 **Riwayat Login:** Setiap login tercatat (waktu, IP, status berhasil/gagal)
- 🔐 **Password Terenkripsi:** Password disimpan dalam bentuk terenkripsi (bcrypt)

---

## 4. DASHBOARD

### 4.1 Apa itu Dashboard?

Dashboard adalah **halaman utama** yang pertama kali muncul setelah login. Halaman ini menampilkan **ringkasan informasi penting** sesuai jabatan Anda — seperti "papan informasi digital" yang isinya berbeda untuk setiap orang.

### 4.2 Dashboard Ketua Umum & Wakil Ketua Umum

Menampilkan gambaran lengkap organisasi:
- 📊 **Statistik anggota** — total, aktif, pasif, alumni
- 📈 **Grafik distribusi** — anggota per divisi (diagram batang)
- 📈 **Grafik status** — aktif vs pasif vs alumni (diagram lingkaran)
- 📅 **Event mendatang** — daftar event yang akan berlangsung
- 📝 **Surat pernyataan pending** — menunggu approval
- 🕐 **Aktivitas terbaru** — log perubahan di sistem
- ⚡ **Tombol aksi cepat** — kelola anggota, buat event, lihat laporan

📸 *Screenshot: Dashboard Ketua Umum*

### 4.3 Dashboard Sekretaris Umum

Fokus pada administrasi dan dokumentasi rapat:
- 📝 Total notulensi yang tercatat
- 📋 Notulensi terbaru (5 terakhir)
- 📅 Event mendatang
- 👥 Statistik anggota

📸 *Screenshot: Dashboard Sekretaris*

### 4.4 Dashboard Bendahara Umum

Fokus pada keuangan organisasi:
- 💰 **Total anggaran** — gabungan divisi + event
- 💸 **Total terpakai** — realisasi pengeluaran
- 💵 **Sisa anggaran** — selisih anggaran - terpakai
- 📊 **Grafik anggaran per divisi** — perbandingan anggaran vs realisasi
- 📅 Event aktif yang memiliki anggaran

📸 *Screenshot: Dashboard Bendahara*

### 4.5 Dashboard Kepala Divisi (Kadiv)

Fokus pada divisi yang dipimpin:
- 👥 Jumlah anggota divisi aktif
- 💰 Anggaran divisi (total & terpakai)
- 📅 Event mendatang

📸 *Screenshot: Dashboard Kadiv*

### 4.6 Dashboard Kepala Unit (Kanit)

Fokus pada unit yang dipimpin:
- 👥 Jumlah staf unit aktif
- 📅 Event mendatang
- 📰 Status naskah redaksi (khusus Kanit Redaksi: jumlah draft, review, total)
- ⚡ Tombol cepat: Kelola Jadwal Piket, Review Naskah

📸 *Screenshot: Dashboard Kanit*

### 4.7 Dashboard Staf

- 📅 Event mendatang
- 📰 Naskah yang ditulis sendiri (5 terbaru)

### 4.8 Dashboard Anggota Aktif

- 📅 Event mendatang
- 📋 Informasi divisi

### 4.9 Dashboard Anggota Pasif

Tampilan paling minimal:
- 📋 Surat pernyataan milik sendiri (jika ada)
- 🔄 Link perpanjangan keaktifan

📸 *Screenshot: Dashboard Anggota Pasif*

---

## 5. MANAJEMEN ANGGOTA

### 5.1 Melihat Daftar Anggota

📌 Klik menu **"Anggota"** di sidebar (bagian Organisasi)
📌 Halaman menampilkan seluruh anggota UKM dalam bentuk tabel
📌 **Fitur pencarian:** Cari berdasarkan NIM, nama, atau email
📌 **Filter:** Berdasarkan divisi, status keanggotaan, atau jabatan
📌 **Urutan:** Otomatis diurutkan berdasarkan hierarki jabatan (Ketum di atas)

📸 *Screenshot: Halaman Daftar Anggota*

### 5.2 Menambah Anggota Baru (Manual)

📌 Klik tombol **"+ Tambah Anggota"**
📌 Isi formulir lengkap:
- **NIM** (wajib, unik)
- **Nama Lengkap** (wajib)
- **Email** (wajib, format email valid)
- **Tanggal Lahir** (wajib — digunakan sebagai password awal)
- **Tempat Lahir, Jenis Kelamin, No. HP, Alamat** (opsional)
- **Program Studi & Jurusan** (opsional)
- **Divisi** (wajib — pilih salah satu dari 6 divisi/unit)
- **Jabatan Struktural** (wajib)
- **Status Keanggotaan** (aktif/pasif/alumni)
- **Foto Profil** (opsional, maks 2MB)
📌 Klik **"Simpan"**
📌 Sistem otomatis:
- Membuat akun login (username = NIM, password = tanggal lahir DDMMYYYY)
- Meng-assign hak akses sesuai jabatan

📸 *Screenshot: Form Tambah Anggota*

### 5.3 Import Anggota dari Excel

Untuk menambahkan banyak anggota sekaligus:
📌 Klik **"Import Excel"** di halaman anggota
📌 Download template Excel terlebih dahulu (klik "Download Template")
📌 Isi template sesuai format yang ditentukan
📌 Upload file Excel (.xlsx/.xls/.csv, maks 5MB)
📌 Sistem memproses dan menampilkan hasil: berapa berhasil, berapa dilewati

### 5.4 Mengedit Data Anggota

📌 Klik tombol ✏️ (Edit) di baris anggota
📌 Ubah data yang diperlukan
📌 Klik **"Simpan Perubahan"**
📌 Jika jabatan berubah, hak akses otomatis di-update

### 5.5 Menghapus Anggota

📌 Klik tombol 🗑️ (Hapus) di baris anggota
📌 Konfirmasi penghapusan
📌 Data anggota dihapus (soft delete — bisa dipulihkan jika diperlukan)

### 5.6 Reset Password Anggota

> ⚠️ **Hanya Super Admin** yang bisa melakukan ini.

**Reset Password Individual:**
📌 Klik tombol 🔑 (Reset Password) di baris anggota
📌 Masukkan password baru (kosongkan untuk default: `12345678`)
📌 Klik **“Reset”**
📌 Password direset ke password yang dimasukkan (atau `12345678` jika dikosongkan)
📌 Status akun di-unlock jika sebelumnya terkunci
📌 Anggota akan diminta mengganti password saat login berikutnya (bisa dilewati)

**Reset Semua Password Sekaligus (Bulk):**
📌 Klik tombol **“Reset Semua Password”** di atas tabel anggota
📌 Masukkan password baru (kosongkan untuk default: `12345678`)
📌 Centang konfirmasi
📌 Klik **“Reset Semua Password”**
📌 Semua password anggota (kecuali admin) akan direset sekaligus

> 💡 **Catatan:** Setelah password direset, anggota dapat memilih untuk mengganti password atau melewatkannya. Password tetap bisa diganti kapan saja melalui menu **Profil**.

### 5.7 Melihat Detail Anggota

📌 Klik tombol 👁️ (Lihat Detail) di baris anggota
📌 Halaman menampilkan:
- Data pribadi lengkap
- Riwayat kepengurusan (jabatan apa saja di setiap periode)
- Riwayat kepanitiaan event
- Riwayat login terakhir

📸 *Screenshot: Detail Anggota*

---

## 6. MANAJEMEN KEPENGURUSAN

### 6.1 Periode Kepengurusan

Setiap kepengurusan memiliki **periode** (contoh: 2025/2026, 2026/2027). Sistem mencatat:
- Nama periode
- Tahun mulai & selesai
- Status: Aktif / Selesai
- Susunan pengurus periode tersebut

📌 Klik menu **"Periode"** untuk melihat daftar periode
📌 Bisa menambah, mengedit, atau melihat detail setiap periode

### 6.2 Syarat Naik Jabatan

Sistem secara otomatis **memvalidasi** apakah seorang anggota memenuhi syarat untuk jabatan tertentu:

| Jabatan Target | Syarat Minimal |
|---|---|
| **Staf Unit** | Sudah 1 tahun menjadi anggota di UKM |
| **Kepala Divisi (Kadiv)** | Sudah 1 tahun di UKM |
| **Kepala Unit (Kanit)** | Sudah 2 tahun di UKM DAN pernah menjadi staf |
| **Wakil Ketum / Sekum / Bendum** | Sudah 1 tahun di UKM |
| **Ketua Umum** | Sudah 2 tahun di UKM |

### 6.3 Pergantian Kepengurusan

Fitur ini digunakan setelah **MUBES** (Musyawarah Besar) untuk mentransisikan kepengurusan:

📌 **Langkah 1:** Buka menu **"Pergantian"** di sidebar
📌 **Langkah 2:** Isi data periode baru (nama, tanggal mulai & selesai)
📌 **Langkah 3:** Pilih susunan pengurus baru untuk **12 jabatan** inti:
- Ketua Umum & Wakil Ketua Umum
- Sekretaris Umum 1 & 2
- Bendahara Umum 1 & 2
- 3 Kepala Divisi (Fotografi, Pers & Penyiaran, Videografi)
- 3 Kepala Unit (Kominfo, Redaksi, Inventory)
📌 **Langkah 4:** Sistem otomatis **memvalidasi** syarat jabatan untuk setiap calon
📌 **Langkah 5:** Jika ada yang **tidak memenuhi syarat**:
- Sistem menampilkan peringatan
- Ketum/Waketum bisa melakukan **Override** dengan alasan tertulis (min. 50 karakter)
📌 **Langkah 6:** Klik **"Konfirmasi Pergantian"**
📌 **Langkah 7:** Sistem otomatis:
- Mengarsipkan periode lama (status → Selesai)
- Membuat periode baru (status → Aktif)
- Mengubah jabatan setiap anggota yang ditunjuk
- Memperbarui hak akses sesuai jabatan baru
- Mencatat riwayat kepengurusan
- Me-reset jabatan pengurus lama yang tidak terpilih ke "Anggota"

> ⚠️ **Aturan:** Satu anggota tidak boleh menempati 2 jabatan sekaligus.

📸 *Screenshot: Halaman Pergantian Kepengurusan*
📸 *Screenshot: Validasi Eligibility*
📸 *Screenshot: Override Dialog*

### 6.4 Override Validasi

Ketika seorang calon **belum memenuhi syarat** tetapi tetap dipilih hasil MUBES:

📌 Hanya **Ketua Umum / Wakil Ketua Umum** yang bisa override
📌 **Wajib** menulis alasan minimal 50 karakter
📌 Override tercatat di **Log Override** untuk keperluan audit
📌 Catatan override tersimpan: siapa yang override, untuk siapa, jabatan apa, dan alasannya

### 6.5 Riwayat Kepengurusan

📌 Setiap anggota bisa melihat **riwayat jabatan** mereka di halaman Detail Anggota
📌 Riwayat mencakup: periode, jabatan, divisi, tanggal mulai & selesai

---

## 7. NOTULENSI RAPAT

### 7.1 Kategori Rapat

Sistem mendukung pencatatan untuk berbagai jenis rapat:

| Kategori | Keterangan |
|---|---|
| MUBES | Musyawarah Besar (tertinggi) |
| Rapat BPI | Rapat Badan Pengurus Inti |
| Rapat Divisi | Rapat internal divisi |
| Rapat Unit | Rapat internal unit |
| Rapat Umum | Rapat seluruh anggota |
| Rapat Kepanitiaan | Rapat pembentukan/koordinasi panitia event |

### 7.2 Membuat Notulensi Baru

📌 Klik menu **"Notulensi"** di sidebar
📌 Klik **"+ Buat Notulensi"**
📌 Isi formulir:
- **Kategori rapat** (pilih dari daftar)
- **Tanggal & waktu rapat**
- **Agenda/Pembahasan** (isi pokok pembahasan)
- **Hasil Rapat** (kesimpulan & keputusan)
- **Daftar Hadir** (centang anggota yang hadir)
- **Lampiran** (upload file pendukung, opsional)
📌 Klik **"Simpan"**
📌 Notulis tercatat otomatis dari user yang login

📸 *Screenshot: Form Notulensi*

### 7.3 Mencari & Membaca Notulensi

📌 Gunakan **search bar** untuk mencari berdasarkan judul/agenda
📌 Filter berdasarkan kategori rapat
📌 Klik notulensi untuk membaca detail lengkap
📌 Bisa mengedit atau menghapus notulensi yang sudah dibuat

---

## 8. REKRUTMEN ANGGOTA BARU

### 8.1 Alur Rekrutmen

```
Buka Periode → Calon Mendaftar → Seleksi Administrasi → Wawancara → Pengumuman
```

### 8.2 Mengelola Rekrutmen

📌 Klik menu **"Rekrutmen"** di sidebar
📌 Klik **"+ Buka Rekrutmen Baru"** untuk membuat periode rekrutmen
📌 Isi: Nama calon, NIM, divisi yang dipilih, dan data pendaftaran
📌 Update status seleksi per tahap:
- **Menunggu** → baru mendaftar
- **Seleksi Administrasi** → berkas sedang diperiksa
- **Wawancara** → lolos administrasi, menunggu wawancara
- **Lolos** → diterima sebagai anggota
- **Tidak Lolos** → tidak memenuhi kriteria
📌 Input catatan pewawancara dan skor seleksi
📌 Setelah diterima, admin bisa langsung mendaftarkan sebagai anggota baru di menu Anggota

📸 *Screenshot: Halaman Rekrutmen*
