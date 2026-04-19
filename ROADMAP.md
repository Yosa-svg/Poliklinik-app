# 📋 Poliklinik Sejahtera - Roadmap & Struktur Aplikasi

## 🏥 Informasi Aplikasi

| Field            | Detail                                         |
|------------------|------------------------------------------------|
| **Nama**         | Poliklinik Sejahtera                           |
| **Deskripsi**    | Sistem Manajemen Poliklinik Terintegrasi       |
| **Status**       | 🟢 **AKTIF — Version 1.2**                     |
| **Versi**        | 1.2.0 *(Tailwind v4 + DaisyUI + Partials)*    |
| **Last Updated** | 12 April 2026                                  |

---

## 📊 Struktur Sistem

### 🏗️ Arsitektur MVC

```
Poliklinik-App/
├── app/
│   ├── Models/                         # 7 Model Eloquent
│   │   ├── User.php                   # Admin, Dokter, Pasien
│   │   ├── Poli.php                   # Jenis Poliklinik
│   │   ├── JadwalPeriksa.php          # Jadwal Dokter
│   │   ├── DaftarPoli.php             # Registrasi Pasien
│   │   ├── Periksa.php                # Data Pemeriksaan
│   │   ├── DetailPeriksa.php          # Detail Pemeriksaan + Obat
│   │   └── Obat.php                   # Daftar Obat
│   └── Http/
│       ├── Controllers/
│       │   ├── AuthController.php          # Login/Register/Logout
│       │   ├── DashboardController.php     # 3 Dashboard (Admin/Dokter/Pasien)
│       │   ├── PoliklinikController.php    # CRUD Poliklinik (Admin)
│       │   ├── DokterController.php        # CRUD Dokter (Admin)
│       │   ├── PasienController.php        # CRUD Pasien (Admin)
│       │   ├── ObatController.php          # CRUD Obat (Admin)
│       │   ├── Dokter/
│       │   │   ├── JadwalPeriksaController.php
│       │   │   ├── PeriksaController.php
│       │   │   └── DetailPeriksaController.php
│       │   └── Pasien/
│       │       ├── PoliController.php
│       │       ├── DaftarPoliController.php
│       │       ├── JadwalPeriksaController.php
│       │       └── PeriksaController.php
│       └── Middleware/
│           └── RoleMiddleware.php      # Kontrol Akses per Role
├── database/
│   ├── migrations/                     # 12 File Migrasi
│   └── seeders/                        # Test Data Seeding
├── resources/
│   ├── css/
│   │   └── app.css                     # Tailwind CSS v4 entry point
│   └── views/                          # 37+ Blade Templates
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── poli.blade.php
│       │   ├── dokter/              # index, create, edit
│       │   ├── obat/                # index, create, update
│       │   ├── pasien/              # index, create, edit
│       │   └── poliklinik/          # index, create, edit
│       ├── dokter/
│       │   ├── dashboard.blade.php
│       │   ├── jadwal-periksa/      # index, create, edit
│       │   └── periksa/             # index, edit, show
│       ├── pasien/
│       │   ├── dashboard.blade.php
│       │   ├── daftar-poli/         # index, create
│       │   ├── periksa/             # index, show
│       │   └── poli/                # index, show
│       ├── components/
│       │   ├── layouts/
│       │   │   ├── app.blade.php    # Layout utama (Tailwind)
│       │   │   └── guest.blade.php  # Layout auth (Tailwind)
│       │   ├── flash-alert.blade.php
│       │   └── nav-link.blade.php
│       └── containner/
│           └── partials/
│               ├── header.blade.php # Topbar + User Dropdown
│               ├── sidebar.blade.php# Sidebar role-based
│               └── footer.blade.php # Footer bar
├── routes/
│   └── web.php                     # Semua Route (auth, admin, dokter, pasien)
├── tailwind.config.js              # Tailwind v4 + DaisyUI plugin
├── vite.config.js                  # Vite + @tailwindcss/vite
└── public/
    └── images/
        ├── logo.png                # Logo Utama
        └── logo-icon.svg           # Icon Logo
```

---

## 👥 User Roles & Permissions

### 👨‍💼 **ADMIN**

| Fitur | Status |
|-------|--------|
| Login / Logout | ✅ |
| Dashboard Analytics (KPI, Recent Activity) | ✅ |
| Kelola Poliklinik (CRUD) | ✅ |
| Kelola Dokter (CRUD) | ✅ |
| Kelola Pasien (CRUD) | ✅ |
| Kelola Obat (CRUD) | ✅ |

### 👨‍⚕️ **DOKTER**

| Fitur | Status |
|-------|--------|
| Login / Logout | ✅ |
| Dashboard (Stats, Keluhan Terbanyak) | ✅ |
| Kelola Jadwal Periksa (CRUD milik sendiri) | ✅ |
| Kelola Pemeriksaan Pasien (Create, Edit, Show) | ✅ |
| Tambah Detail Pemeriksaan & Resep Obat | ✅ |

### 🚶 **PASIEN**

| Fitur | Status |
|-------|--------|
| Register (auto-generate No. RM) | ✅ |
| Login / Logout | ✅ |
| Dashboard (Stats, Appointment) | ✅ |
| Lihat Daftar Poliklinik & Jadwal Dokter | ✅ |
| Daftar ke Poliklinik (auto no. antrian) | ✅ |
| Lihat Riwayat Pendaftaran | ✅ |
| Lihat Hasil Pemeriksaan | ✅ |

---

## 🗄️ Database Schema

### 📊 12 Migrations / 11 Tables

| # | Tabel | Keterangan |
|---|-------|------------|
| 1 | `users` | Admin, Dokter, Pasien |
| 2 | `poli` | Jenis Poliklinik |
| 3 | `jadwal_periksa` | Jadwal Dokter (+status col) |
| 4 | `daftar_poli` | Registrasi Pasien |
| 5 | `periksa` | Data Pemeriksaan |
| 6 | `detail_periksa` | Detail Periksa + Obat |
| 7 | `obat` | Daftar Obat |
| 8 | `cache` | Session Caching |
| 9 | `jobs` | Job Queue |
| 10 | `password_reset_tokens` | Password Reset |
| 11 | `sessions` | Database Sessions |
| +1 | *(migration)* | `add_status_to_jadwal_periksa_table` |

### 🔗 Relationships

```
User (1) ──→ (Many) JadwalPeriksa    [id_dokter]
User (1) ──→ (Many) DaftarPoli       [id_pasien]
Poli (1) ──→ (Many) User (Dokter)    [id_poli]
Poli (1) ──→ (Many) JadwalPeriksa

JadwalPeriksa (1) ──→ (Many) DaftarPoli   [id_jadwal]
DaftarPoli    (1) ──→ (One)  Periksa      [id_daftar_poli]
Periksa       (1) ──→ (Many) DetailPeriksa [id_periksa]
DetailPeriksa (M) ──→ (One)  Obat         [id_obat]
```

---

## 🎨 UI/UX Stack

### 🎯 **Layout Architecture**

| Komponen | File | Keterangan |
|----------|------|------------|
| Layout Utama | `components/layouts/app.blade.php` | Wrapper full app |
| Layout Auth | `components/layouts/guest.blade.php` | Login/Register wrapper |
| Topbar/Header | `containner/partials/header.blade.php` | User dropdown, sidebar toggle |
| Sidebar | `containner/partials/sidebar.blade.php` | Role-based nav, gradient |
| Footer | `containner/partials/footer.blade.php` | Copyright bar |
| Flash Alert | `components/flash-alert.blade.php` | Success/Error notif |
| Nav Link | `components/nav-link.blade.php` | Reusable sidebar link |

### 🎨 **Color & Design Tokens (`app.css`)**

```css
/* Sidebar Gradient */
background: linear-gradient(160deg, #1e2d6b 0%, #2d4499 60%, #1a2d7a 100%);

/* Body Background */
bg-[#f1f5f9]

/* Custom CSS Variables */
--sidebar-w: 260px;
```

### 📦 **Frontend Dependencies**

| Package | Versi | Status |
|---------|-------|--------|
| Tailwind CSS | v4.2.2 | ✅ Aktif |
| @tailwindcss/vite | v4.2.2 | ✅ Aktif |
| DaisyUI | v5.5.19 | ✅ Aktif |
| Font Awesome | 6.x (CDN) | ✅ Aktif |
| Plus Jakarta Sans | (Google Fonts CDN) | ✅ Aktif |
| Vite | v7.x | ✅ Aktif |
| Bootstrap / AdminLTE | — | ❌ Dihapus |

---

## ✅ Changelog

### v1.0 — Core System *(Maret 2026)*
- ✅ Semua model & migrasi
- ✅ Auth (login, register, logout, role redirect)
- ✅ CRUD Admin (Poliklinik, Dokter, Pasien, Obat)
- ✅ Fitur Dokter (Jadwal, Periksa, Detail/Resep)
- ✅ Fitur Pasien (Daftar Poli, Lihat Periksa)
- ✅ Role middleware
- ✅ Auto-generate No. RM & No. Antrian

### v1.1 — UI Migration *(5 April 2026)*
- ✅ Migrasi dari AdminLTE → Tailwind CSS v4
- ✅ Rewrite semua Blade view (auth, admin, dokter, pasien)
- ✅ Layout baru: `app.blade.php` & `guest.blade.php`
- ✅ Komponen: `flash-alert`, `nav-link`
- ✅ Sidebar gradient premium, header topbar
- ✅ Codebase bebas Bootstrap dependency

### v1.2 — Polish & Partials *(12 April 2026)*
- ✅ Ekstrak `header.blade.php` sebagai partial terpisah
- ✅ Ekstrak `sidebar.blade.php` sebagai partial terpisah
- ✅ Ekstrak `footer.blade.php` sebagai partial terpisah
- ✅ Integrasi **DaisyUI v5** untuk komponen UI tambahan
- ✅ Font: **Plus Jakarta Sans** (Google Fonts)
- ✅ Custom CSS class: `sidebar-fixed`, `main-content`, `main-scroll`, `btn-primary-gradient`
- ✅ Mobile responsive sidebar (toggle + overlay)
- ✅ Tambah kolom `status` pada tabel `jadwal_periksa`

---

## 🚀 Roadmap Fase Selanjutnya

### 🎯 **Phase 2: UX Enhancement (Target: Mei 2026)**

- [ ] Pagination pada semua tabel index
- [ ] Search & filter pada tabel manajemen
- [ ] Modal konfirmasi delete (ganti `confirm()` native)
- [ ] Dark mode toggle
- [ ] Loading state / spinner saat form submit
- [ ] Animasi page transition

### 📊 **Phase 3: Analytics & Reporting (Target: Juni 2026)**

- [ ] Chart.js / ApexCharts pada dashboard (grafik kunjungan)
- [ ] Export PDF hasil pemeriksaan (DomPDF)
- [ ] Export CSV/Excel untuk laporan admin
- [ ] Laporan pendapatan dokter per periode
- [ ] Print kartu resep

### 🔔 **Phase 4: Notifikasi (Target: Q3 2026)**

- [ ] In-app notification bell
- [ ] Email reminder jadwal (Laravel Mail + Queue)
- [ ] SMS notification (Twilio / Fonnte)
- [ ] Status update otomatis

### 🔒 **Phase 5: Security (Target: Q4 2026)**

- [ ] Two-Factor Authentication (2FA)
- [ ] Audit Logs (siapa edit/hapus apa & kapan)
- [ ] Spatie Laravel Permission (ACL granular)
- [ ] API Rate Limiting
- [ ] Data encryption at rest

### 🌐 **Phase 6: API & Mobile (Target: Q1 2027)**

- [ ] RESTful API (Laravel Sanctum)
- [ ] Mobile App (React Native / Flutter)
- [ ] Telemedicine integration
- [ ] EHR Export (format FHIR)

---

## 🛠️ Technology Stack

### **Backend**

| Teknologi | Versi |
|-----------|-------|
| Laravel | 11 |
| PHP | 8.3.16 |
| MySQL | ≥ 5.7 |
| Eloquent ORM | — |
| Composer | 2.8.8 |

### **Frontend**

| Teknologi | Versi | Keterangan |
|-----------|-------|------------|
| Tailwind CSS | v4.2.2 | Utility-first CSS |
| @tailwindcss/vite | v4.2.2 | Vite plugin |
| DaisyUI | v5.5.19 | UI component library |
| Font Awesome | 6.x | Icon library (CDN) |
| Plus Jakarta Sans | — | Font utama (CDN) |
| Blade Templating | — | Laravel templating |
| Vite | v7.x | Asset bundler |

---

## 🚀 Getting Started

### Prerequisites

```bash
PHP >= 8.3
Composer >= 2.8
MySQL >= 5.7
Node.js >= 18
```

### Installation

```bash
# Clone Repository
git clone <repository-url>
cd poliklinik-app

# Install PHP Dependencies
composer install

# Install Node Dependencies
npm install

# Environment Setup
cp .env.example .env
php artisan key:generate

# Setup Database
php artisan migrate:fresh --seed

# Start Development
php artisan serve    # terminal 1 → http://localhost:8000
npm run dev          # terminal 2 → Vite HMR
```

### Test Credentials

```
👤 Admin:   admin@gmail.com  / admin123
👨‍⚕️ Dokter:  dokter@gmail.com / dokter123
🚶 Pasien:  pasien@gmail.com / pasien123
```

---

## 📁 Project Statistics

| Metric | v1.0 | v1.1 | v1.2 (Now) |
|--------|------|------|-----------|
| Models | 7 | 7 | 7 |
| Controllers | 11 | 11 | 12 |
| Views (Blade) | 25+ | 35+ | 37+ |
| Partials | 0 | 0 | 3 |
| Migrations | 11 | 11 | 12 |
| Routes | 20+ | 25+ | 28+ |
| Database Tables | 11 | 11 | 11 |
| Lines of Code | 3000+ | 5000+ | 6000+ |
| Test Users | 3 | 3 | 3 |
| Features | 30+ | 40+ | 45+ |

---

## 🎯 Next Steps

### Segera (Minggu Ini)
- [ ] Tambah pagination pada tabel index
- [ ] Modal konfirmasi delete yang elegan
- [ ] Search / filter kolom pada tabel manajemen

### Jangka Pendek (Minggu 2-3)
- [ ] Integrasi chart pada dashboard admin
- [ ] Export PDF untuk hasil periksa
- [ ] Dark mode toggle

### Jangka Menengah (Bulan 2)
- [ ] Email notification sistem
- [ ] Laporan keuangan dokter
- [ ] Unit test dasar (PHPUnit)

---

## 📝 Technical Notes

- Code mengikuti standar **PSR-12**
- Semua aset gambar format **PNG/SVG**
- Database menggunakan timestamps (`created_at`, `updated_at`)
- Sessions disimpan di database (`sessions` table)
- Password di-hash dengan **bcrypt**
- **CSRF protection** aktif di semua form
- SQL injection dicegah via **Eloquent ORM**
- Tailwind CSS v4 digunakan via **`@tailwindcss/vite`** plugin (tanpa config PostCSS terpisah)
- DaisyUI v5 diintegrasikan sebagai Tailwind plugin di `tailwind.config.js`
- Sidebar menggunakan arsitektur **partials** (`containner/partials/`)
- Layout utama menggunakan **Blade component** (`x-layouts.app`, `x-layouts.guest`)

---

**🎉 Poliklinik Sejahtera — Sistem Manajemen Terintegrasi | v1.2.0**
