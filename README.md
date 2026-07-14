# SIRITA OPAC 

> **SIRITA OPAC** adalah implementasi ulang sistem manajemen perpustakaan [SLiMS](https://slims.web.id) berbasis **Laravel 11**, dibangun dengan arsitektur modern, antarmuka yang premium, dan performa yang optimal.

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
| 🗃️ **Stok Opname** | Inventarisasi koleksi fisik berbasis scan barcode |
| 🌐 **OPAC** | Halaman publik pencarian & detail koleksi |
| ⚙️ **Master Data** | Manajemen penulis, penerbit, GMD, lokasi, bahasa, dll |

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|---|---|
| Backend | **Laravel 11** (PHP 8.2+) |
| Database | **MySQL** / **MariaDB** |
| Web Server | Apache / Nginx |
| Frontend | **Blade** + Vanilla CSS (Dark mode / Glassmorphism) |
| Visualisasi | **Chart.js** (CDN) |
| Barcode | **JsBarcode** (CDN) |

---

## 🚀 Panduan Instalasi (Quick Start)

### Prasyarat
- PHP **>= 8.2** dengan ekstensi: `pdo`, `mbstring`, `xml`, `fileinfo`, `gd`, `zip`
- **MySQL** atau **MariaDB** >= 10.5
- **Composer** >= 2.x
- Web Server: Apache / Nginx / XAMPP / Laragon

### Langkah Instalasi

**1. Masuk ke direktori proyek:**
```bash
cd sirita_opac
```

**2. Instal dependensi PHP via Composer:**
```bash
composer install --optimize-autoloader
```

**3. Buat file `.env` & atur koneksi database:**
Salin berkas `.env.example` menjadi `.env`. Sesuaikan konfigurasi database Anda langsung mengarah ke database SLiMS Native yang ingin digunakan:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_slims_native
DB_USERNAME=username_database
DB_PASSWORD=password_database
```

**4. Generate application key:**
```bash
php artisan key:generate
```

**5. Daftarkan Riwayat Tabel Migrasi SLiMS Native (Fake Run):**
Karena database SLiMS Native Anda sudah memiliki tabel-tabel utama (seperti `biblio`, `member`, `item`), kita harus menandainya sebagai "sukses" di Laravel agar tidak ditimpa/error saat migrasi berjalan. Jalankan skrip pembantu berikut:
```bash
php mark_migrations.php
```
*(Catatan: Pastikan file `mark_migrations.php` berada di folder root aplikasi Anda saat dijalankan).*

**6. Jalankan Migrasi Tabel Sistem Tambahan:**
Jalankan perintah berikut agar Laravel membuat tabel baru yang belum ada di database SLiMS Native (seperti `org_units`, `promotions`, `menus`, `pages`, dll):
```bash
php artisan migrate --force
```

**7. Buat Symlink Storage (Untuk Cover Buku & Lampiran):**
```bash
php artisan storage:link
```

**8. Jalankan Server Aplikasi:**
```bash
php artisan serve
```

**9. Akses Aplikasi:**
* **OPAC (Halaman Publik):** `http://localhost:8000`
* **Panel Admin:** `http://localhost:8000/admin` (Kredensial Default: `admin@sirita.ac.id` / `password` ).

---

## 🗂️ Struktur Direktori Penting

```
sirita_opac/
├── app/
│   ├── Http/Controllers/Admin/   # Controller admin per modul
│   ├── Models/                   # Eloquent Models (Disesuaikan skema SLiMS)
│   └── Plugins/SlimsMigrator/    # Plugin Migrasi Data SLiMS
├── database/
│   ├── migrations/               # Migrasi tabel sistem SIRITA & SLiMS
├── resources/views/
│   ├── admin/                    # Tampilan panel admin
│   ├── opac/                     # Tampilan OPAC publik
│   └── layouts/                  # Master layout admin & opac (pagination fix)
└── mark_migrations.php           # Script helper penanda migrasi tabel SLiMS
```

---

## 🧪 Pengujian Otomatis

```bash
php artisan test
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
- [x] Stock Take / Stok Opname *(Tersedia data dummy simulasi)*
- [ ] Multi-bahasa (i18n)
- [ ] API REST untuk integrasi pihak ketiga
- [ ] Role-based Access Control (RBAC) granular

---

## 🤝 Kontribusi

Kontribusi sangat disambut! Silakan buka _issue_ terlebih dahulu untuk mendiskusikan perubahan besar yang ingin Anda usulkan, lalu kirimkan _pull request_.

---

## 👤 Author
 
* **Sufyaldy** ([@sufyaldi](https://github.com/sufyaldi)) - 📧 [sufyaldys@gmail.com](mailto:sufyaldys@gmail.com)
* **Tim Dev TIPD IAIN Parepare**
* **Forum TIPD PTKIN**

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah **GNU Affero General Public License v3.0 (AGPL-3.0)**.

> Lisensi AGPL-3.0 mewajibkan bahwa jika Anda menjalankan aplikasi ini sebagai layanan jaringan (misalnya via web), Anda **wajib** membagikan kode sumber modifikasi Anda kepada pengguna layanan tersebut.

Lihat file [LICENSE](LICENSE) untuk teks lengkap lisensi.

---

## 🙏 Kredit

- Proyek ini terinspirasi dan dibangun berdasarkan [SLiMS (Senayan Library Management System)](https://slims.web.id) yang merupakan perangkat lunak manajemen perpustakaan open-source asal Indonesia, juga dilisensikan di bawah AGPL-3.0.
- Terima kasih kepada seluruh tim pengembang SLiMS atas kontribusi luar biasa mereka kepada dunia perpustakaan Indonesia.
