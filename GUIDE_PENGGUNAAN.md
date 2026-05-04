# 📖 Poliklinik Sejahtera — Guide Penggunaan

> **Versi Dokumen:** 1.2.0 | **Last Updated:** 12 April 2026

---

## 🚀 SETUP & INSTALASI

### Prasyarat

```bash
PHP >= 8.3
Composer >= 2.8
MySQL >= 5.7
Node.js >= 18
```

### Langkah Instalasi

```bash
# 1. Clone Repository
git clone <repository-url>
cd poliklinik-app

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Setup environment
cp .env.example .env
php artisan key:generate

# 5. Konfigurasi database di .env
#    DB_DATABASE=poliklinik_db
#    DB_USERNAME=root
#    DB_PASSWORD=your_password

# 6. Jalankan migrasi + seeder
php artisan migrate:fresh --seed

# 7. Jalankan server (buka 2 terminal)
php artisan serve    # Terminal 1 → http://localhost:8000
npm run dev          # Terminal 2 → Vite HMR
```

### Akun Login Default

```
👤 Admin   →  admin@gmail.com  / admin123
👨‍⚕️ Dokter  →  dokter@gmail.com / dokter123
🚶 Pasien  →  pasien@gmail.com / pasien123
```

---

## 🗄️ DATABASE SCHEMA

### Tabel `users`

```sql
id, name, email, alamat, no_ktp, no_hp, no_rm, role, id_poli, password, ...
- role: enum('admin', 'dokter', 'pasien')
- no_rm: auto-generated YYYYMM-XXX (hanya pasien)
- id_poli: nullable FK → poli.id (hanya dokter)
```

### Tabel `poli`

```sql
id, nama_poli, deskripsi, created_at, updated_at
```

### Tabel `jadwal_periksa`

```sql
id, id_dokter, hari, jam_mulai, jam_selesai, status, created_at, updated_at
- FK: id_dokter → users.id
- status: kolom tambahan (April 2026)
```

### Tabel `daftar_poli`

```sql
id, id_jadwal, id_pasien, keluhan, no_antrian, created_at, updated_at
- FK: id_jadwal → jadwal_periksa.id
- FK: id_pasien → users.id
```

### Tabel `periksa`

```sql
id, id_daftar_poli, tgl_periksa, catatan, biaya_periksa, ...
- FK: id_daftar_poli → daftar_poli.id
```

### Tabel `detail_periksa`

```sql
id, id_periksa, id_obat, ...
- FK: id_periksa → periksa.id
- FK: id_obat → obat.id
```

### Tabel `obat`

```sql
id, nama_obat, kemasan, harga, created_at, updated_at
```

---

## 🔗 RELASI MODEL

```
Poli         (1) ──→ (Many) User (Dokter)   [id_poli]
User/Dokter  (1) ──→ (Many) JadwalPeriksa   [id_dokter]
User/Pasien  (1) ──→ (Many) DaftarPoli      [id_pasien]
JadwalPeriksa(1) ──→ (Many) DaftarPoli      [id_jadwal]
DaftarPoli   (1) ──→ (One)  Periksa         [id_daftar_poli]
Periksa      (1) ──→ (Many) DetailPeriksa   [id_periksa]
DetailPeriksa(M) ──→ (One)  Obat            [id_obat]
```

---

## 🔐 AUTENTIKASI

### Mekanisme

- **Driver:** Eloquent (default Laravel)
- **Guard:** web
- **Session:** disimpan di tabel `sessions` (database driver)

### Middleware

| Middleware | Kegunaan |
|------------|----------|
| `auth` | Memastikan user sudah login |
| `guest` | Memastikan user belum login (halaman login/register) |
| `role:admin` | Hanya bisa diakses admin |
| `role:dokter` | Hanya bisa diakses dokter |
| `role:pasien` | Hanya bisa diakses pasien |

### Alur Login

```
User buka /login
→ Isi email + password
→ Sistem cek role
→ Redirect sesuai role:
   - admin  → /admin/dashboard
   - dokter → /dokter/dashboard
   - pasien → /pasien/dashboard
```

### Alur Register (Pasien saja)

```
User buka /register
→ Isi form (nama, email, alamat, no_hp, no_ktp, password)
→ Sistem auto-generate no_rm (format: YYYYMM-XXX)
→ User login otomatis
→ Redirect ke pasien.dashboard
```

---

## 🎯 FITUR PER ROLE

### 👨‍💼 ADMIN — `/admin/dashboard`

#### 1. Poliklinik Management
- Lihat daftar poliklinik
- Tambah poliklinik (nama + deskripsi)
- Edit poliklinik
- Hapus poliklinik

#### 2. Dokter Management
- Lihat daftar dokter (beserta info poli)
- Tambah dokter (nama, email, KTP, HP, alamat, poli, password)
- Edit data dokter
- Hapus dokter

#### 3. Pasien Management
- Lihat daftar pasien (beserta No. RM)
- Tambah pasien (No. RM auto-generate)
- Edit data pasien
- Hapus pasien

#### 4. Obat Management
- Lihat daftar obat (nama, kemasan, harga)
- Tambah obat
- Edit obat
- Hapus obat

---

### 👨‍⚕️ DOKTER — `/dokter/dashboard`

#### 1. Jadwal Periksa Management
- Lihat jadwal milik sendiri
- Tambah jadwal (hari, jam mulai, jam selesai)
- Edit jadwal milik sendiri
- Hapus jadwal milik sendiri

> ⚠️ **Validasi:** Hanya bisa edit/delete jadwal milik sendiri. Format jam HH:MM (24-jam). Jam selesai harus lebih besar dari jam mulai.

#### 2. Pemeriksaan Pasien
- Lihat daftar pasien yang perlu diperiksa
- Buat catatan pemeriksaan (tanggal, catatan, biaya)
- Edit data pemeriksaan
- Lihat detail pemeriksaan
- Tambah resep obat (detail_periksa)
- Hapus item resep

---

### 🚶 PASIEN — `/pasien/dashboard`

#### 1. Lihat Poliklinik
- Lihat semua poliklinik yang tersedia
- Lihat deskripsi poliklinik
- Klik ke detail untuk melihat jadwal dokter

#### 2. Registrasi Pemeriksaan
- Pilih jadwal dokter pada halaman daftar
- Isi keluhan (textarea)
- Sistem auto-assign no. antrian per jadwal
- Validasi: tidak boleh daftar jadwal yang sama 2x

#### 3. Riwayat Pendaftaran
- Lihat semua pendaftaran yang pernah dibuat
- Lihat status: **Menunggu Periksa** / **Sudah Diperiksa**
- Lihat info: dokter, poli, hari, jam mulai-selesai

#### 4. Hasil Pemeriksaan
- Lihat daftar hasil periksa
- Lihat detail: tanggal, catatan dokter, biaya, daftar obat

---

## 🔄 ALUR PENGGUNAAN SISTEM

### 1. Setup Awal (Admin)

```
Admin login
→ Tambah Poliklinik (mis: Poli Umum, Poli Gigi)
→ Tambah Dokter (pilih poli yang sesuai)
→ Tambah Obat yang tersedia di klinik
```

### 2. Dokter Buat Jadwal

```
Dokter login
→ Buka menu "Jadwal Periksa"
→ Klik "Tambah Jadwal"
→ Isi: Hari, Jam Mulai, Jam Selesai
→ Simpan → jadwal muncul untuk pasien
```

### 3. Pasien Daftar Periksa

```
Pasien login
→ Buka menu "Poliklinik"
→ Pilih poliklinik yang diinginkan → klik "Lihat Jadwal"
→ Pilih jadwal dokter → klik "Daftar"
→ Isi keluhan → Submit
→ Nomor antrian otomatis ter-assign
→ Cek status di menu "Riwayat Pendaftaran"
```

### 4. Dokter Periksa Pasien

```
Dokter login
→ Buka menu "Pemeriksaan"
→ Pilih pasien yang akan diperiksa
→ Klik "Periksa" → isi: tanggal, catatan, biaya
→ Tambah resep obat (pilih dari daftar obat)
→ Simpan → status pasien berubah jadi "Sudah Diperiksa"
```

### 5. Pasien Lihat Hasil

```
Pasien login
→ Buka menu "Hasil Pemeriksaan"
→ Lihat daftar hasil → klik "Detail"
→ Lihat: catatan dokter, biaya, daftar obat yang diresepkan
```

---

## ✅ VALIDASI FORM

### Register Pasien

| Field | Aturan |
|-------|--------|
| `name` | required, string, max:255 |
| `email` | required, email, unique:users |
| `password` | required, min:8, confirmed |
| `alamat` | required, string |
| `no_hp` | required, max:20, unique:users |
| `no_ktp` | required, max:20, unique:users |

### Tambah Dokter (Admin)

| Field | Aturan |
|-------|--------|
| `name` | required, string, max:255 |
| `email` | required, email, unique:users |
| `password` | required, min:6 |
| `no_ktp` | required, max:16, unique:users |
| `no_hp` | required, max:15 |
| `alamat` | required, string |
| `id_poli` | required, exists:poli,id |

### Jadwal Periksa

| Field | Aturan |
|-------|--------|
| `hari` | required, in:Senin,Selasa,...,Minggu |
| `jam_mulai` | required, date_format:H:i |
| `jam_selesai` | required, date_format:H:i, after:jam_mulai |

### Daftar Poli (Pasien)

| Field | Aturan |
|-------|--------|
| `id_jadwal` | required, exists:jadwal_periksa,id |
| `keluhan` | required, string |
| *(logic)* | Pasien tidak boleh daftar jadwal yang sama 2x |

---

## ⚙️ AUTO-GENERATE FIELDS

### No. Rekam Medis (`no_rm`) — Pasien

```
Format : YYYYMM-XXX
Contoh : 202604-001, 202604-002
Logika : Ambil format bulan-tahun sekarang, cari no urut terakhir, +1
Reset  : Tidak reset (urut terus, tidak per bulan ulang)
```

### No. Antrian (`no_antrian`) — Daftar Poli

```
Format : 1, 2, 3, 4, ...
Logika : Hitung jumlah pendaftaran pada id_jadwal yang sama, +1
Reset  : Per jadwal unik (tiap jadwal punya antrian sendiri)
```

---

## 🏗️ ARSITEKTUR BLADE / LAYOUT

### Layout Utama (`x-layouts.app`)

```
┌─────────────────────────────────────────┐
│  SIDEBAR (containner/partials/sidebar)  │
│  - Brand (Logo + Nama Klinik)           │
│  - User Panel (nama + role)             │
│  - Navigation (role-based menu)         │
│  - Logout Button                        │
├─────────────────────────────────────────┤
│  HEADER (containner/partials/header)    │
│  - Sidebar Toggle Button                │
│  - User Dropdown (Menu + Logout)        │
├─────────────────────────────────────────┤
│  CONTENT AREA ({{ $slot }})             │
│  - Flash Alert (x-flash-alert)          │
│  - Page content                         │
├─────────────────────────────────────────┤
│  FOOTER (containner/partials/footer)    │
└─────────────────────────────────────────┘
```

### Layout Auth (`x-layouts.guest`)

```
Centered card di layar penuh
→ Login / Register form
```

### Cara Penggunaan Layout di View

```php
{{-- Halaman yang butuh layout utama --}}
<x-layouts.app title="Judul Halaman">
    {{-- Konten halaman di sini --}}
</x-layouts.app>

{{-- Halaman auth --}}
<x-layouts.guest title="Login">
    {{-- Form login/register di sini --}}
</x-layouts.guest>
```

---

## 🎨 FRONTEND STACK

| Teknologi | Versi | Cara Pakai |
|-----------|-------|------------|
| Tailwind CSS | v4.2.2 | `@import "tailwindcss"` di `app.css` |
| DaisyUI | v5.5.19 | Plugin di `tailwind.config.js` |
| Font Awesome | 6.x | CDN di layout head |
| Plus Jakarta Sans | — | Google Fonts CDN |
| Vite | v7.x | `npm run dev` |

### Custom CSS Classes (`app.css`)

| Class | Kegunaan |
|-------|----------|
| `.app-wrapper` | Wrapper flex seluruh halaman |
| `.sidebar-fixed` | Sidebar sticky, gradient biru gelap |
| `.main-content` | Area konten utama (flex + scroll) |
| `.main-scroll` | Padding area konten |
| `.sidebar-overlay` | Overlay saat sidebar mobile terbuka |
| `.btn-primary-gradient` | Tombol gradient biru utama |

---

## 🔒 KEAMANAN

| Fitur | Status |
|-------|--------|
| CSRF Protection (`@csrf`) | ✅ Aktif di semua form |
| SQL Injection Prevention (Eloquent ORM) | ✅ Aktif |
| Password Hashing (bcrypt) | ✅ Aktif |
| Role-based Access Control (Middleware) | ✅ Aktif |
| Session stored in database | ✅ Aktif |
| XSS Prevention (Blade escaping `{{ }}`) | ✅ Aktif |
| Two-Factor Authentication | ❌ Belum diimplementasi |
| Audit Logs | ❌ Belum diimplementasi |

---

## 📂 STRUKTUR ROUTE

```php
// Auth (guest only)
GET  /login                  → AuthController@showLoginForm
POST /login                  → AuthController@login
GET  /register               → AuthController@showRegisterForm
POST /register               → AuthController@register
POST /logout                 → AuthController@logout [auth]

// Admin [auth, role:admin]
GET  /admin/dashboard        → DashboardController@adminDashboard
resource /poliklinik         → PoliklinikController (CRUD)
resource /dokter             → DokterController (CRUD)
resource /pasien             → PasienController (CRUD)
resource /obat               → ObatController (CRUD)

// Dokter [auth, role:dokter]
GET  /dokter/dashboard       → DashboardController@dokterDashboard
resource /jadwal-periksa     → JadwalPeriksaController (no show)
resource /dokter/{dokter}/periksa         → Dokter\PeriksaController
resource /dokter/{dokter}/periksa/{periksa}/detail → Dokter\DetailPeriksaController

// Pasien [auth, role:pasien] prefix: /pasien
GET  /pasien/dashboard       → DashboardController@pasienDashboard
resource /pasien/poli        → Pasien\PoliController (index, show)
resource /pasien/daftar-poli → Pasien\DaftarPoliController (index, create, store)
resource /pasien/periksa     → Pasien\PeriksaController (index, show)
```

---

## 🛠️ PERINTAH ARTISAN BERGUNA

```bash
# Jalankan migrasi ulang + seeder
php artisan migrate:fresh --seed

# Buat controller baru
php artisan make:controller NamaController --resource

# Buat model + migrasi sekaligus
php artisan make:model NamaModel -m

# Lihat semua route
php artisan route:list

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Storage link (untuk file upload)
php artisan storage:link
```

---

## 📋 PRODUCTION CHECKLIST

Sebelum deploy ke server production:

- [ ] Set `APP_DEBUG=false` di `.env`
- [ ] Set `APP_ENV=production` di `.env`
- [ ] Konfigurasi database production (`DB_*`)
- [ ] Jalankan `php artisan migrate` di server
- [ ] Jalankan `npm run build` (bukan `dev`)
- [ ] Pastikan `storage/` & `bootstrap/cache/` writable
- [ ] Jalankan `php artisan storage:link`
- [ ] Konfigurasi `MAIL_*` jika pakai email
- [ ] Setup backup database otomatis
- [ ] Setup error logging (Sentry / Laravel Telescope)
- [ ] Konfigurasi rate limiting
- [ ] Pastikan `.env` tidak ikut di-commit ke Git

---

## 🧩 FIELD NAMES REFERENCE

> Penting untuk konsistensi saat membuat form atau query

| Model | Field Penting |
|-------|---------------|
| User | `name`, `email`, `alamat`, `no_ktp`, `no_hp`, `no_rm`, `role`, `id_poli` |
| Poli | `nama_poli`, `deskripsi` |
| JadwalPeriksa | `id_dokter`, `hari`, `jam_mulai`, `jam_selesai`, `status` |
| DaftarPoli | `id_jadwal`, `id_pasien`, `keluhan`, `no_antrian` |
| Periksa | `id_daftar_poli`, `tgl_periksa`, `catatan`, `biaya_periksa` |
| DetailPeriksa | `id_periksa`, `id_obat` |
| Obat | `nama_obat`, `kemasan`, `harga` |

---

**📞 Untuk bug atau pertanyaan, hubungi tim development.**

*Poliklinik Sejahtera — Sistem Manajemen Terintegrasi | v1.2.0*
