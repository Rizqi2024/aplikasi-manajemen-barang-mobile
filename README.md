# 📦 Sistem Manajemen Inventaris Barang & Mitra Supplier
> **Aplikasi Mobile Android (Kotlin) & Web Admin Panel + REST API (CodeIgniter 4)**

Proyek full-stack yang mengintegrasikan aplikasi mobile Android dengan backend web RESTful API untuk pengelolaan inventaris data barang dan mitra supplier secara real-time.

---

## 📑 Daftar Isi
- [Arsitektur & Gambaran Sistem](#-arsitektur--gambaran-sistem)
- [Fitur Utama](#-fitur-utama)
  - [1. Aplikasi Mobile Android](#1-aplikasi-mobile-android)
  - [2. Backend & Web Admin Panel](#2-backend--web-admin-panel)
- [Struktur Repositori](#-struktur-repositori)
- [Prasyarat Sistem](#-prasyarat-sistem)
- [Panduan Instalasi & Menjalankan](#-panduan-instalasi--menjalankan)
  - [Langkah 1: Setup Backend & Database](#langkah-1-setup-backend--database)
  - [Langkah 2: Menjalankan Aplikasi Android](#langkah-2-menjalankan-aplikasi-android)
- [Akun Pengujian (Seeder)](#-akun-pengujian-seeder)
- [Dokumentasi API](#-dokumentasi-api)
- [Troubleshooting & Solusi](#-troubleshooting--solusi)

---

## 🏛 Arsitektur & Gambaran Sistem

```
┌─────────────────────────────────┐           HTTP / REST JSON           ┌─────────────────────────────────┐
│     Aplikasi Mobile Android     │ <──────────────────────────────────> │     Backend CodeIgniter 4       │
│  - Android Studio (Kotlin)      │      Authorization: Bearer <token>   │  - RESTful API (Controllers/Api)│
│  - Retrofit 2 & Material 3      │                                      │  - AdminLTE 4 Web Admin Panel   │
│  - Dashboard, Barang, Supplier  │                                      │  - Database MySQL               │
└─────────────────────────────────┘                                      └─────────────────────────────────┘
```

---

## ✨ Fitur Utama

### 1. Aplikasi Mobile Android (`AplikasiBarang`)
* **Halaman Login Modern**:
  * Desain Material 3 dengan *Edge-to-Edge window insets*.
  * Show/Hide password toggle.
  * Kartu **Pengaturan Server API** langsung di layar login (memudahkan ganti IP Wi-Fi laptop tanpa perlu build ulang).
* **Manajemen Sesi & Keamanan**:
  * Penyimpanan token sesi otomatis menggunakan `SharedPreferences`.
  * Auto-login jika sesi masih aktif; redirect ke login jika sesi habis.
* **Dashboard Utama (Menu Hub)**:
  * Header sambutan personal: Avatar inisial nama, nama user, dan badge `ROLE • @username`.
  * Kartu statistik real-time: **Jenis Barang**, **Akumulasi Stok Unit**, dan **Mitra Supplier**.
  * Tombol navigasi modular ke **Data Barang** dan **Daftar Supplier**.
  * Dialog konfirmasi Logout aman.
* **Modul Data Barang**:
  * Menampilkan katalog barang, kode produk, kategori, harga satuan rupiah, dan stok.
  * Indikator warna stok otomatis: Hijau (Stok Aman), Kuning (Stok Menipis), Merah (Stok Habis).
  * Fitur pencarian instan (filter nama, kode, atau kategori barang).
* **Modul Daftar Supplier**:
  * Menampilkan mitra pemasok terdaftar beserta kode supplier, alamat, nomor telepon, dan email.
  * Kontak interaktif: ketuk nomor telepon untuk langsung membuka dialer panggilan; ketuk email untuk langsung membuka aplikasi email.
  * Fitur pencarian instan (filter nama, kode, alamat, atau kontak).

### 2. Backend & Web Admin Panel (`backend-app`)
* **Dashboard Admin Web**:
  * Kartu ringkasan metrik total barang, total stok fisik, total supplier, dan total user.
  * Tabel pratinjau 5 barang terbaru dan 5 supplier terbaru.
* **CRUD Data Barang Lengkap**:
  * Tambah barang baru (modal form dengan validasi server-side kode barang unik).
  * Edit data barang (pre-filled modal via JavaScript).
  * Hapus data barang dengan konfirmasi.
  * Pencarian data barang.
* **CRUD Data Supplier Lengkap**:
  * Tambah mitra supplier (kode unik, nama, alamat, nomor telepon, email).
  * Edit data supplier.
  * Hapus supplier dengan konfirmasi.
  * Pencarian data supplier.
* **RESTful API Endpoint**:
  * Endpoint publik: Login (`/api/login`), Lihat Barang (`/api/barang`).
  * Endpoint terlindungi: Supplier (`/api/supplier`) menggunakan filter autentikasi token (`AuthApiFilter`).
* **Database Migration & Seeder**:
  * Skema database dapat dibuat ulang kapan saja secara otomatis (`php spark migrate`).
  * Seeder anti-duplikat aman dijalankan berulang kali (`UserSeeder`, `SupplierSeeder`).

---

## 📂 Struktur Repositori

```text
.
├── AplikasiBarang/               # Source code project Android Studio (Kotlin)
│   ├── app/
│   │   ├── src/main/
│   │   │   ├── java/com/example/aplikasibarang/
│   │   │   │   ├── model/       # Data class (Barang, Supplier, Login, dll)
│   │   │   │   ├── network/     # ApiClient & ApiService (Retrofit)
│   │   │   │   ├── DashboardActivity.kt   # Halaman Dashboard Utama
│   │   │   │   ├── LoginActivity.kt       # Halaman Login
│   │   │   │   ├── MainActivity.kt        # Halaman Data Barang
│   │   │   │   ├── SupplierActivity.kt    # Halaman Data Supplier
│   │   │   │   ├── SessionManager.kt      # Pengelola sesi token lokal
│   │   │   │   └── ServerConfigDialog.kt  # Dialog ubah IP server dinamis
│   │   │   └── res/             # Layout XML, drawable ikon vektor, colors, themes
│   │   └── build.gradle.kts
│   └── build.gradle.kts
│
├── backend-app/                  # Source code project Backend CodeIgniter 4
│   ├── app/
│   │   ├── Config/              # Konfigurasi Routes, Filters, Database, dll
│   │   ├── Controllers/
│   │   │   ├── Api/             # Controller REST API (Auth, Barang, Supplier)
│   │   │   ├── Barang.php       # Controller Web Admin Barang
│   │   │   ├── Dashboard.php    # Controller Web Admin Dashboard
│   │   │   └── Supplier.php     # Controller Web Admin Supplier
│   │   ├── Database/
│   │   │   ├── Migrations/      # Migration tabel users, barang, supplier
│   │   │   └── Seeds/           # Seeder UserSeeder & SupplierSeeder
│   │   ├── Filters/             # AuthApiFilter (proteksi endpoint API)
│   │   ├── Models/              # BarangModel, SupplierModel, UserModel
│   │   └── Views/               # Tampilan AdminLTE (layout, dashboard, barang, supplier)
│   ├── public/                  # Aset statis AdminLTE, CSS, JS, Bootstrap Icons
│   └── spark                    # CLI CodeIgniter 4
│
└── README.md                     # Dokumentasi panduan lengkap
```

---

## 💻 Prasyarat Sistem

Sebelum menjalankan proyek, pastikan perangkat Anda memiliki:
1. **XAMPP / Web Server** dengan PHP versi **8.2+** dan **MySQL/MariaDB**.
2. **Android Studio** (disarankan versi Ladybug / Koala / terbaru) dengan SDK Android minimal API 24 (Android 7.0) hingga API 34+.
3. **Koneksi Wi-Fi yang sama** antara laptop dan HP fisik (jika melakukan testing di smartphone fisik).

---

## 🚀 Panduan Instalasi & Menjalankan

### Langkah 1: Setup Backend & Database

1. **Jalankan Apache & MySQL**:
   * Buka **XAMPP Control Panel**, lalu klik **Start** pada modul **Apache** dan **MySQL**.

2. **Buat Database**:
   * Buka browser dan masuk ke [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
   * Buat database baru bernama: `db_aplikasi` *(atau sesuaikan dengan konfigurasi `.env`)*.

3. **Buka Terminal di Folder Backend**:
   ```bash
   cd backend-app
   ```

4. **Jalankan Migration Database**:
   Perintah ini akan membuat tabel `users`, `barang`, dan `supplier` secara otomatis:
   ```bash
   php spark migrate
   ```

5. **Jalankan Seeder (Data Awal)**:
   ```bash
   php spark db:seed UserSeeder
   php spark db:seed SupplierSeeder
   ```

6. **Nyalakan Server Backend**:
   Gunakan parameter `--host 0.0.0.0` agar server dapat diakses oleh HP melalui jaringan Wi-Fi:
   ```bash
   php spark serve --host 0.0.0.0 --port 8080
   ```
   * Web Admin Panel dapat dibuka di browser laptop: **[http://localhost:8080/dashboard](http://localhost:8080/dashboard)**
   * Data Barang Web: **[http://localhost:8080/barang](http://localhost:8080/barang)**
   * Data Supplier Web: **[http://localhost:8080/supplier](http://localhost:8080/supplier)**

---

### Langkah 2: Menjalankan Aplikasi Android

1. Buka folder `AplikasiBarang` di **Android Studio**.
2. Biarkan Gradle melakukan sync dependensi hingga selesai (`BUILD SUCCESSFUL`).
3. **Tentukan Alamat Server**:
   * **Jika Menggunakan Emulator Android Studio**:
     * Alamat otomatis mengarah ke `http://10.0.2.2:8080/`.
   * **Jika Menggunakan HP Fisik (Real Device)**:
     * Cek alamat IP laptop Anda dengan perintah `ipconfig` di terminal laptop (misal: `192.168.1.221`).
     * Pastikan HP dan laptop terhubung ke Wi-Fi yang sama.
     * Anda dapat langsung mengubah alamat server dari layar Login HP dengan mengetuk kartu **"Alamat Server Backend (Ketuk untuk ubah)"** dan memasukkan `http://<IP_LAPTOP>:8080/`.
4. Klik tombol **Run ('app')** (Shift + F10) untuk menginstal aplikasi ke perangkat Anda.

---

## 🔑 Akun Pengujian (Seeder)

Gunakan akun default berikut untuk login pada aplikasi Android:

| Role | Username | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `Admin123` | Akses penuh inventaris |
| **Pengguna Biasa** | `pengguna` | `User123` | Akses operasional standar |

> *Catatan: Password bersifat case-sensitive (perhatikan huruf besar dan kecil).*

---

## 📡 Dokumentasi API

Base URL: `http://<IP_SERVER>:8080/api/`

| Method | Endpoint | Proteksi Token | Request Body | Deskripsi |
| :--- | :--- | :---: | :--- | :--- |
| `POST` | `/api/login` | Tidak | `{"username":"...", "password":"..."}` | Autentikasi user & mengembalikan sesi token |
| `GET` | `/api/barang` | Tidak | - | Mengambil seluruh daftar barang inventaris |
| `GET` | `/api/barang/{id}` | Tidak | - | Mengambil detail 1 barang berdasarkan ID |
| `POST` | `/api/barang` | Tidak | `{"kode_barang":"...", "nama_barang":"...", "stok":10, "harga":15000}` | Menambahkan barang baru via API |
| `PUT` | `/api/barang/{id}` | Tidak | JSON field barang | Memperbarui data barang via API |
| `DELETE` | `/api/barang/{id}` | Tidak | - | Menghapus barang via API |
| `GET` | `/api/supplier` | **Ya (Bearer)** | Header: `Authorization: Bearer <token>` | Mengambil seluruh daftar mitra supplier |

---

## 🛠 Troubleshooting & Solusi

| Masalah | Penyebab Umum | Solusi |
| :--- | :--- | :--- |
| **Notifikasi "Gagal terhubung" di HP** | Server backend belum aktif atau IP laptop salah/berubah | 1. Pastikan menjalankan `php spark serve --host 0.0.0.0 --port 8080`<br>2. Cek `ipconfig` di laptop, ubah IP di menu login HP. |
| **Unable to connect to database** | MySQL di XAMPP belum dijalankan | Buka XAMPP Control Panel, klik **Start** pada modul MySQL. |
| **Table 'barang' already exists** | Tabel sudah pernah dibuat manual di database | Jalankan migration terbaru yang sudah dilengkapi `IF NOT EXISTS`. |
| **Tampilan HP berbeda saat Dark Mode** | Tema gelap sistem menimpa warna teks | Tema aplikasi telah dikunci ke Material Light Palette di `themes.xml` dan `values-night` agar tetap terbaca jelas. |
