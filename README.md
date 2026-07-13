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
| Database | **MySQL** / **MariaDB** |
| Web Server | Apache / Nginx |
| Frontend | **Blade** + Vanilla CSS (Dark mode / Glassmorphism) |
| Visualisasi | **Chart.js** (CDN) |
| Barcode | **JsBarcode** (CDN) |

---

## 🚀 Memulai (Quick Start)

### Prasyarat
- PHP **>= 8.2** dengan ekstensi: `pdo`, `mbstring`, `xml`, `fileinfo`, `gd`
- **MySQL** atau **MariaDB** >= 10.5
- **Composer** >= 2.x
- Web Server: Apache / Nginx
- Git

### Langkah Instalasi

**1. Clone repositori:**
```bash
git clone https://github.com/sufyaldi/sirita_slims.git
cd sirita_slims
```

**2. Instal dependensi PHP:**
```bash
composer install --optimize-autoloader --no-dev
```

**3. Salin & konfigurasi file environment:**
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan nilai berikut:
```env
APP_URL=http://localhost

DB_HOST=127.0.0.1
DB_DATABASE=sirita_slims
DB_USERNAME=root
DB_PASSWORD=
```

**4. Generate application key:**
```bash
php artisan key:generate
```

**5. Jalankan migrasi & seeder database:**
```bash
# Jalankan semua migrasi tabel
php artisan migrate

# Seed data default (admin, GMD, dll)
php artisan db:seed

# (Opsional) Seed data dummy untuk demo visualisasi
php artisan db:seed --class=DummyDataSeeder
```

**6. Buat symlink storage:**
```bash
php artisan storage:link
```

**7. Jalankan server lokal (development):**
```bash
php artisan serve
```

**8. Akses aplikasi:**

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
php artisan test
```

**Hasil yang diharapkan:**
```
PASS  Tests\Feature\CirculationTest
  ✓ item can be checked out to member
  ✓ item return calculates overdue fines

Tests: 2 passed (5 assertions)
```

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

## 👤 Author

**Sufyaldy**
- 📧 Email: [sufyaldys@gmail.com](mailto:sufyaldys@gmail.com)
- 🐙 GitHub: [@sufyaldy](https://github.com/sufyaldi)

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah **GNU Affero General Public License v3.0 (AGPL-3.0)**.

> Lisensi AGPL-3.0 mewajibkan bahwa jika Anda menjalankan aplikasi ini sebagai layanan jaringan (misalnya via web), Anda **wajib** membagikan kode sumber modifikasi Anda kepada pengguna layanan tersebut.

Lihat file [LICENSE](LICENSE) untuk teks lengkap lisensi.

---

## 🙏 Kredit

- Proyek ini terinspirasi dan dibangun berdasarkan [SLiMS (Senayan Library Management System)](https://slims.web.id) yang merupakan perangkat lunak manajemen perpustakaan open-source asal Indonesia, juga dilisensikan di bawah AGPL-3.0.
- Terima kasih kepada seluruh tim pengembang SLiMS atas kontribusi luar biasa mereka kepada dunia perpustakaan Indonesia.
