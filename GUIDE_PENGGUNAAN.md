# 📋 POLIKLINIK APP - GUIDE PENGGUNAAN

## 🎯 OVERVIEW APLIKASI

Aplikasi Poliklinik adalah sistem manajemen klinik berbasis web yang dibuat dengan **Laravel 11**. Aplikasi ini mengelola:

- **Poliklinik** (departemen/layanan kesehatan)
- **Dokter** (tenaga medis di poliklinik)
- **Pasien** (pendaftar layanan kesehatan)
- **Jadwal Periksa** (jadwal ketersediaan dokter)
- **Pendaftaran** (registrasi pasien untuk pemeriksaan)
- **Obat** (data obat yang tersedia)

---

## 🏗️ STRUKTUR MVC

### MODELS

```
app/Models/
├── User.php              (Dokter & Pasien)
├── Poli.php              (Poliklinik)
├── JadwalPeriksa.php     (Jadwal Dokter)
├── DaftarPoli.php        (Registrasi Pasien)
├── Periksa.php           (Pemeriksaan)
├── DetailPeriksa.php     (Resep Obat)
└── Obat.php              (Data Obat)
```

### CONTROLLERS

```
app/Http/Controllers/
├── AuthController.php             (Login/Register)
├── PoliklinikController.php        (Kelola Poliklinik)
├── DokterController.php            (Kelola Dokter)
├── PasienController.php            (Kelola Pasien)
├── ObatController.php              (Kelola Obat)
├── Dokter/
│   └── JadwalPeriksaController.php (Jadwal Dokter)
└── Pasien/
    ├── PoliController.php           (Lihat Poliklinik)
    └── DaftarPoliController.php     (Daftar Poliklinik)
```

### VIEWS

```
resources/views/
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── admin/
│   ├── dashboard.blade.php
│   ├── poliklinik/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── dokter/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── pasien/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   └── obat/
│       ├── index.blade.php
│       ├── create.blade.php
│       └── edit.blade.php
├── dokter/
│   ├── dashboard.blade.php
│   └── jadwal-periksa/
│       ├── index.blade.php
│       ├── create.blade.php
│       └── edit.blade.php
└── pasien/
    ├── dashboard.blade.php
    ├── poli/
    │   ├── index.blade.php
    │   └── show.blade.php
    └── daftar-poli/
        ├── index.blade.php
        └── create.blade.php
```

---

## 🔐 RELASI MODEL

### User → Poli (Dokter)

```
Dokter memiliki satu Poli
User: role='dokter' + id_poli → Poli.id
```

### Poli → Dokter

```
Satu Poli memiliki banyak Dokter
Poli.id ← User.id_poli (role='dokter')
```

### Dokter → JadwalPeriksa

```
Satu Dokter memiliki banyak Jadwal Periksa
User.id ← JadwalPeriksa.id_dokter
```

### JadwalPeriksa → DaftarPoli

```
Satu Jadwal memiliki banyak Daftar/Registrasi
JadwalPeriksa.id ← DaftarPoli.id_jadwal
```

### DaftarPoli → Pasien (User)

```
Daftar/Registrasi milik satu Pasien
DaftarPoli.id_pasien → User.id (role='pasien')
```

### DaftarPoli → Periksa

```
Satu Daftar memiliki satu Pemeriksaan
DaftarPoli.id ← Periksa.id_daftar_poli
```

### Periksa → DetailPeriksa

```
Satu Pemeriksaan memiliki banyak Detail Resep
Periksa.id ← DetailPeriksa.id_periksa
```

### DetailPeriksa → Obat

```
Detail Resep merujuk ke satu Obat
DetailPeriksa.id_obat → Obat.id
```

---

## 🔑 FITUR SESUAI ROLE

### 👥 ADMIN

**Akses ke:** `/admin/dashboard`

**Fitur:**

1. **Poliklinik Management**
    - Lihat daftar poliklinik
    - Tambah poliklinik (nama + deskripsi)
    - Edit poliklinik
    - Hapus poliklinik

2. **Dokter Management**
    - Lihat daftar dokter (+ info poli)
    - Tambah dokter (nama, email, KTP, HP, poli, password)
    - Edit dokter
    - Hapus dokter

3. **Pasien Management**
    - Lihat daftar pasien (+ no. RM)
    - Tambah pasien (auto-generate no. RM)
    - Edit pasien
    - Hapus pasien

4. **Obat Management**
    - Lihat daftar obat
    - Tambah obat
    - Edit obat
    - Hapus obat

---

### 👨‍⚕️ DOKTER

**Akses ke:** `/dokter/dashboard`

**Fitur:**

1. **Jadwal Periksa Management**
    - Lihat jadwal sendiri
    - Tambah jadwal (hari, jam_mulai, jam_selesai)
    - Edit jadwal sendiri
    - Hapus jadwal sendiri

**Validasi:**

- Hanya bisa edit/delete jadwal milik sendiri
- Format jam: HH:MM (24-jam)
- Jam selesai harus lebih besar dari jam mulai

---

### 👤 PASIEN

**Akses ke:** `/pasien/dashboard`

**Fitur:**

1. **Lihat Poliklinik**
    - Lihat semua poliklinik yang tersedia
    - Lihat deskripsi poliklinik
    - Lihat jadwal dokter per poliklinik

2. **Registrasi Poliklinik**
    - Pilih jadwal dokter
    - Isi keluhan (teks)
    - Auto-assign nomor antrian
    - Validasi: tidak boleh daftar jadwal yang sama 2x

3. **Riwayat Pendaftaran**
    - Lihat semua pendaftaran yang pernah dibuat
    - Lihat status (Menunggu Periksa / Sudah Diperiksa)
    - Lihat info dokter, poli, hari, jam

---

## 🚀 ALUR PENGGUNAAN

### 1. REGISTRASI PASIEN

```
User buka /register
→ Isi form (nama, email, alamat, no_hp, no_ktp, password)
→ Sistem auto-generate no_rm (YYYYMM-XXX)
→ User login
→ Redirect ke pasien.dashboard
```

### 2. LOGIN

```
User buka /login
→ Isi email + password
→ Sistem cek role
→ Redirect sesuai role:
   - Admin → admin.dashboard
   - Dokter → dokter.dashboard
   - Pasien → pasien.dashboard
```

### 3. ADMIN SETUP AWAL

```
Admin login
→ Tambah Poliklinik (misal: Poli Umum, Poli Gigi, dll)
→ Tambah Dokter (sesuai poli)
→ Tambah Obat
→ Dokter bisa mulai membuat jadwal
```

### 4. DOKTER BUAT JADWAL

```
Dokter login
→ Buka menu "Jadwal Periksa"
→ Klik "Tambah Jadwal"
→ Isi: Hari (Senin-Minggu), Jam Mulai, Jam Selesai
→ Simpan
→ Jadwal muncul di aplikasi pasien
```

### 5. PASIEN DAFTAR PEMERIKSAAN

```
Pasien login
→ Buka menu "Poliklinik"
→ Lihat daftar poliklinik
→ Klik "Lihat Jadwal Dokter"
→ Klik "Daftar" pada jadwal yang diinginkan
→ Isi keluhan
→ Sistem auto-assign no_antrian
→ Pasien bisa lihat di "Riwayat Pendaftaran"
```

---

## 📱 DATABASE SCHEMA HIGHLIGHTS

### Users Table

```sql
id, name, email, alamat, no_ktp, no_hp, no_rm, role, id_poli, password, ...
- role: enum('admin', 'dokter', 'pasien')
- no_rm: auto-generated YYYYMM-XXX (hanya pasien)
- id_poli: nullable (hanya dokter yang punya)
```

### Poli Table

```sql
id, nama_poli, deskripsi, created_at, updated_at
```

### JadwalPeriksa Table

```sql
id, id_dokter, hari, jam_mulai, jam_selesai, created_at, updated_at
- Foreign key: id_dokter → users.id
```

### DaftarPoli Table

```sql
id, id_jadwal, id_pasien, keluhan, no_antrian, created_at, updated_at
- Foreign key: id_jadwal → jadwal_periksa.id
- Foreign key: id_pasien → users.id
```

### Periksa Table

```sql
id, id_daftar_poli, tgl_periksa, catatan, biaya_periksa, ...
- Foreign key: id_daftar_poli → daftar_poli.id
```

### DetailPeriksa Table

```sql
id, id_periksa, id_obat, ...
- Foreign key: id_periksa → periksa.id
- Foreign key: id_obat → obat.id
```

### Obat Table

```sql
id, nama_obat, kemasan, harga, ...
```

---

## 🔧 TEKNIS

### Authentication

- **Driver:** Eloquent (default Laravel)
- **Guard:** web
- **Middleware:**
    - `auth` - memastikan user login
    - `guest` - memastikan user belum login (auth/register)
    - `role:admin|dokter|pasien` - validasi role

### Field Names

- User: `name` (bukan `nama`)
- Poli: `nama_poli`, `deskripsi`
- Obat: `nama_obat`, `kemasan`, `harga`

### Auto-Generate

- **No. RM (no_rm):** Format `YYYYMM-XXX`
    - Prefix: tahun-bulan (misal: 202603)
    - Urut: 001, 002, 003, ... per bulan
    - Contoh: 202603-001, 202603-002
- **No. Antrian (no_antrian):** Urut 1, 2, 3, ... per jadwal
    - Reset per jadwal berbeda

---

## ⚠️ VALIDASI

### User Registration (Pasien)

- `name`: required, string, max 255
- `email`: required, email, unique
- `password`: required, min 8, confirmed
- `alamat`: required, string
- `no_hp`: required, max 20, unique
- `no_ktp`: required, max 20, unique

### Dokter Management

- `name`: required, string, max 255
- `email`: required, email, unique
- `password`: required, min 6
- `no_ktp`: required, max 16, unique
- `no_hp`: required, max 15
- `alamat`: required, string
- `id_poli`: required, exists:poli

### Jadwal Periksa

- `hari`: required, in (Senin...Minggu)
- `jam_mulai`: required, format HH:MM
- `jam_selesai`: required, format HH:MM, after:jam_mulai

### Daftar Poli

- `id_jadwal`: required, exists
- `keluhan`: required, string
- Validasi: pasien tidak boleh daftar jadwal sama 2x

---

## 🎨 UI FRAMEWORK

- **CSS Framework:** AdminLTE 3.2
- **Icons:** Font Awesome 6
- **Bootstrap:** v5 (included with AdminLTE)
- **Layout Component:** `<x-layouts.app>` (Blade component)

---

## 📝 CATATAN PENTING

1. **Sebelum deploy**, pastikan:
    - `.env` configured (DB_HOST, DB_USER, DB_PASSWORD, dll)
    - `php artisan migrate` sudah dijalankan
    - `php artisan db:seed` (optional) untuk data awal

2. **File storage** untuk profile picture, dokumen, dll:
    - Gunakan `Storage::disk('public')`
    - Link: `php artisan storage:link`

3. **Email** (untuk notifikasi, belum diimplementasi):
    - Configure MAIL\_\* di .env
    - Extend AuthController untuk kirim email verifikasi

4. **Production checklist:**
    - Set `APP_DEBUG=false` di production
    - Configure backup database
    - Setup error logging & monitoring
    - Implement rate limiting
    - Add CSRF protection (sudah ada @csrf)

---

## 📞 CONTACT / SUPPORT

Untuk pertanyaan atau bug, hubungi tim development.
