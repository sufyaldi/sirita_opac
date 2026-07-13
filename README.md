# SIRITA SLiMS — Laravel Edition

> **SIRITA** (**Si**stem Informasi **Ri**set & **T**ata **A**rsip) adalah implementasi ulang sistem manajemen perpustakaan [SLiMS 9 Bulian](https://slims.web.id) berbasis **Laravel 11**, dibangun dengan arsitektur modern, antarmuka yang premium, dan infrastruktur berbasis Docker.

---

## 📚 Tentang Proyek

Proyek ini adalah hasil migrasi penuh dari SLiMS 9 (PHP prosedural + framework simbio) ke **Laravel 11** menggunakan stack modern yang siap produksi. Semua modul utama perpustakaan telah diimplementasikan ulang dengan pola desain yang bersih, meliputi MVC, Eloquent ORM, dan RESTful routing.

---

## ✨ Fitur Utama

| Modul | Fitur |
|---|---|
| 🔐 **Autentikasi** | Login dengan hash bcrypt kompatibel SLiMS |
| 📖 **Bibliografi** | CRUD koleksi, cover buku, lampiran berkas |
| 👥 **Keanggotaan** | CRUD anggota, masa berlaku keanggotaan |
| 🔄 **Sirkulasi** | Peminjaman, pengembalian, quick return, cetak struk |
| 💰 **Manajemen Denda** | Denda otomatis, pembayaran lunas, denda manual |
| 📋 **Copy Cataloging** | Import via Z39.50 / SRU (Library of Congress & Perpusnas RI) |
| 🏷️ **Barcode & Label** | Cetak barcode eksemplar & label punggung buku |
| 📥 **MARC Import/Export** | Impor & ekspor data bibliografi format MARCXML |
| 📰 **Serial Control** | Manajemen langganan majalah/jurnal & kartu kendali Kardex |
| 📊 **Pelaporan** | Dashboard analitik interaktif dengan Chart.js |
| 🗃️ **Stok Opname** | Inventarisasi koleksi fisik berbasis scan barcode *(dalam pengembangan)* |
| 🌐 **OPAC** | Halaman publik pencarian & detail koleksi |
| ⚙️ **Master Data** | Manajemen penulis, penerbit, GMD, lokasi, bahasa, dll |

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|---|---|
| Backend | **Laravel 11** (PHP 8.3) |
| Database | **MariaDB LTS** |
| Web Server | **Nginx** (via Docker) |
| Kontainer | **Docker** + Docker Compose |
| Frontend | **Blade** + Vanilla CSS (Dark mode / Glassmorphism) |
| Visualisasi | **Chart.js** (CDN) |
| Barcode | **JsBarcode** (CDN) |

---

## 🚀 Memulai (Quick Start)

### Prasyarat
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) terinstal dan berjalan
- Git

### Langkah Instalasi

**1. Clone repositori:**
```bash
git clone https://github.com/<username>/sirita_slims.git
cd sirita_slims
```

**2. Salin file konfigurasi environment:**
```bash
cp .env.example .env
```
> Sesuaikan nilai `DB_*` jika diperlukan. Nilai default sudah cocok untuk Docker Compose.

**3. Jalankan kontainer Docker:**
```bash
docker compose up -d --build
```

**4. Jalankan migrasi database & seeder:**
```bash
# Jalankan semua migrasi tabel
docker compose exec app php artisan migrate

# Seed data default (admin user, GMD, dll)
docker compose exec app php artisan db:seed

# Seed data dummy untuk simulasi visualisasi (opsional)
docker compose exec app php artisan db:seed --class=DummyDataSeeder
```

**5. Generate application key:**
```bash
docker compose exec app php artisan key:generate
```

**6. Buat symlink storage:**
```bash
docker compose exec app php artisan storage:link
```

**7. Akses aplikasi:**

| URL | Keterangan |
|---|---|
| `http://localhost:8000` | OPAC (Halaman Publik) |
| `http://localhost:8000/admin` | Panel Admin |

---

## 🔑 Kredensial Default

| Field | Nilai |
|---|---|
| Username | `admin` |
| Password | `admin123` |

---

## 🗂️ Struktur Direktori Penting

```
sirita_slims/
├── app/
│   ├── Http/Controllers/Admin/   # Controller admin per modul
│   ├── Models/                   # Eloquent Models
│   └── Services/                 # Service classes (SRU, MARC)
├── database/
│   ├── migrations/               # 25 file migrasi tabel
│   └── seeders/                  # DatabaseSeeder & DummyDataSeeder
├── resources/views/
│   ├── admin/                    # Tampilan panel admin
│   ├── opac/                     # Tampilan OPAC publik
│   └── layouts/                  # Master layout admin & opac
├── routes/
│   └── web.php                   # Semua route aplikasi
├── tests/Feature/
│   └── CirculationTest.php       # Feature tests sirkulasi
├── Dockerfile                    # Konfigurasi image PHP + Nginx
└── docker-compose.yml            # Orkestrasi layanan Docker
```

---

## 🧪 Menjalankan Pengujian Otomatis

```bash
docker compose exec app php artisan test
```

**Hasil yang diharapkan:**
```
PASS  Tests\Feature\CirculationTest
  ✓ item can be checked out to member
  ✓ item return calculates overdue fines

Tests: 2 passed (5 assertions)
```

---

## 🐳 Layanan Docker

| Service | Container | Port Host | Keterangan |
|---|---|---|---|
| PHP App | `sirita_slims` | `8000` | Laravel + Nginx |
| Database | `sirita_db` | `3307` | MariaDB LTS |

---

## 🗺️ Roadmap

- [x] Modul Bibliografi (CRUD + Lampiran + Cover)
- [x] Modul Sirkulasi (Peminjaman, Pengembalian, Denda)
- [x] Copy Cataloging via SRU/Z39.50
- [x] Cetak Barcode & Label Punggung
- [x] Impor/Ekspor MARC
- [x] Serial Control & Kardex
- [x] Pelaporan & Analytics (Chart.js)
- [x] OPAC Publik
- [ ] Stock Take / Stok Opname *(in progress)*
- [ ] Multi-bahasa (i18n)
- [ ] API REST untuk integrasi pihak ketiga
- [ ] Role-based Access Control (RBAC) granular

---

## 🤝 Kontribusi

Kontribusi sangat disambut! Silakan buka _issue_ terlebih dahulu untuk mendiskusikan perubahan besar yang ingin Anda usulkan, lalu kirimkan _pull request_.

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

## 🙏 Kredit

- Proyek ini terinspirasi dan dibangun berdasarkan [SLiMS (Senayan Library Management System)](https://slims.web.id) yang merupakan perangkat lunak manajemen perpustakaan open-source asal Indonesia.
- Terima kasih kepada seluruh tim pengembang SLiMS atas kontribusi luar biasa mereka kepada dunia perpustakaan Indonesia.
