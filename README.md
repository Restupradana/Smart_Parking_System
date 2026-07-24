# Smart Parking System

Sistem parkir pintar berbasis **IoT (Internet of Things)** yang mengintegrasikan mikrokontroler **ESP32** dengan aplikasi web **Laravel** untuk mengelola akses gerbang parkir secara otomatis menggunakan kartu **RFID** serta memantau ketersediaan slot parkir secara real-time menggunakan sensor **ultrasonik**.

## Fitur Utama

- **Akses Gerbang Otomatis** — Kartu RFID untuk masuk/keluar tanpa operator
- **Pemantauan Slot Real-Time** — Sensor ultrasonik mendeteksi status setiap slot parkir
- **Dashboard Admin** — Kelola pengguna, area parkir, slot, RFID cards, dan CMS landing page
- **Dashboard Petugas** — Pantau status slot, lihat denah parkir, dan riwayat RFID
- **Dashboard Pengguna** — Lihat ketersediaan slot dan riwayat parkir pribadi
- **Notifikasi Buzzer** — Feedback audio untuk akses diterima, ditolak, dan parkir penuh
- **OLED Display** — Menampilkan jumlah slot kosong pada panel hardware
- **Role-Based Access Control** — 3 level akses: Admin, Petugas, Pengguna

## Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────┐
│                    HARDWARE (ESP32)                      │
│                                                         │
│  ┌──────────┐  ┌──────────┐  ┌─────────┐  ┌────────┐  │
│  │ RFID     │  │ RFID     │  │ Sensor  │  │ Servo  │  │
│  │ Masuk    │  │ Keluar   │  │ Ultra-  │  │ Motor  │  │
│  │ (MFRC522)│  │ (MFRC522)│  │ sonik x4│  │ x2     │  │
│  └────┬─────┘  └────┬─────┘  └────┬────┘  └───┬────┘  │
│       │             │             │            │        │
│  ┌────┴─────────────┴─────────────┴────────────┴────┐  │
│  │              ESP32 Microcontroller                │  │
│  │         + OLED Display + Buzzer                  │  │
│  └──────────────────────┬───────────────────────────┘  │
│                         │ HTTP POST (JSON)              │
└─────────────────────────┼───────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│              DOCKER CONTAINERS                           │
│                                                         │
│  ┌────────────┐   ┌────────────┐   ┌────────────────┐  │
│  │   Nginx    │   │   App      │   │    MySQL       │  │
│  │   :8000    │──▶│ (PHP-FPM)  │──▶│    :3306       │  │
│  │            │   │ :9000      │   │                │  │
│  │ + static   │   │ + artisan  │   │  smart_parking │  │
│  │   files    │   │ + queue    │   │    database    │  │
│  └────────────┘   └────────────┘   └────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

## Alur Kerja

### Alur Masuk (Entry)
1. Pengguna menempelkan kartu RFID di gerbang masuk
2. ESP32 membaca UID kartu dan mengirim POST ke `/api/v1/rfid`
3. Laravel memvalidasi kartu (terdaftar, aktif, belum parkir)
4. Jika parkir penuh → akses ditolak, buzzer berbunyi
5. Jika valid → log RFID dibuat, servo membuka gerbang, buzzer berbunyi

### Alur Keluar (Exit)
1. Pengguna menempelkan kartu RFID di gerbang keluar
2. ESP32 mengirim POST ke `/api/v1/rfid` dengan gate `keluar`
3. Laravel memvalidasi sesi parkir aktif
4. Log keluar dibuat, servo membuka gerbang

### Pemantauan Slot
1. 4 sensor ultrasonik membaca jarak secara berkala (setiap 3 detik)
2. Status dikonfirmasi selama 5 detik untuk menghindari noise
3. Perubahan status dikirim ke `POST /api/v1/sensor`
4. Dashboard admin auto-refresh setiap 10 detik

## Tech Stack

### Backend
| Teknologi | Versi | Fungsi |
|-----------|-------|--------|
| PHP | ^8.2 | Bahasa server-side |
| Laravel | ^12.0 | PHP framework |
| Laravel Breeze | ^2.3 | Authentication scaffolding |
| Pusher PHP Server | ^7.2 | Real-time broadcasting (WebSocket) |
| SQLite | default | Database (MySQL/MariaDB juga didukung) |

### Frontend
| Teknologi | Versi | Fungsi |
|-----------|-------|--------|
| Tailwind CSS | ^3.1.0 | CSS framework |
| Alpine.js | ^3.4.2 | JavaScript framework |
| Vite | ^6.2.4 | Build tool & dev server |
| Axios | ^1.8.2 | HTTP client |

### Hardware (IoT)
| Komponen | Spesifikasi | Fungsi |
|----------|-------------|--------|
| ESP32 | WiFi-enabled MCU | Microcontroller utama |
| MFRC522 | RFID Reader | Pembaca kartu RFID (masuk + keluar) |
| HC-SR04 | Ultrasonic Sensor x4 | Deteksi status slot parkir |
| Servo Motor | SG90 x2 | Pengendali palang gerbang |
| SSD1306 OLED | 128x64 | Tampilan jumlah slot kosong |
| Buzzer | Passive | Feedback audio |

### Testing
| Tool | Fungsi |
|------|--------|
| Pest PHP | Unit & feature testing |
| Selenium | Browser automation testing |
| Locust | Load & performance testing |

### Containerization
| Tool | Versi | Fungsi |
|------|-------|--------|
| Docker | >= 20.10 | Container runtime |
| Docker Compose | >= 2.0 | Multi-container orchestration |
| Nginx | Alpine | Reverse proxy & static file server |
| MySQL | 8.0 | Database server |

---

## Installation & Setup Guide

### Prasyarat

Pastikan komputer Anda sudah terinstal:
- **PHP** >= 8.2 (dengan ekstensi: cURL, MBString, OpenSSL, PDO, Tokenizer, XML)
- **Composer** (package manager PHP)
- **Node.js** >= 18.x & npm
- **Git**
- **SQLite** (default) atau **MySQL/MariaDB** (opsional)

### 1. Clone Repository

```bash
git clone https://github.com/Restupradana/Smart_Parking_System.git
cd Smart_Parking_System
```

### 2. Instalasi Backend (Laravel)

```bash
# Install dependensi PHP
composer install

# Copy file environment
copy .env.example .env

# Generate application key
php artisan key:generate

# Buat database SQLite (jika menggunakan SQLite)
type nul > database\database.sqlite

# Jalankan migrasi database
php artisan migrate

# (Opsional) Jalankan seeder untuk data dummy
php artisan db:seed
```

### 3. Instalasi Frontend

```bash
# Install dependensi Node.js
npm install

# Build assets untuk production
npm run build
```

### 4. Konfigurasi Environment

Buka file `.env` dan sesuaikan konfigurasi berikut:

```env
APP_NAME="Smart Parking System"
APP_URL=http://localhost:8000

# Database (SQLite default, atau gunakan MySQL)
DB_CONNECTION=sqlite
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=smart_parking
# DB_USERNAME=root
# DB_PASSWORD=

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 5. Menjalankan Aplikasi

#### Mode Development (Semua sekaligus)
```bash
composer dev
```
Perintah ini menjalankan secara paralel:
- `php artisan serve` — Web server (http://localhost:8000)
- `php artisan queue:listen` — Queue worker
- `php artisan pail` — Real-time log viewer
- `npm run dev` — Vite dev server (hot reload)

#### Mode Manual (Terpisah)
```bash
# Terminal 1 — Web server
php artisan serve

# Terminal 2 — Vite dev server
npm run dev

# Terminal 3 — Queue worker
php artisan queue:listen --tries=1
```

### 6. Build untuk Production

```bash
# Build assets
npm run build

# Jalankan server
php artisan serve --host=0.0.0.0 --port=8000
```

### 7. Konfigurasi ESP32 (Hardware)

1. Buka file `arduino/smart_parking_system/smart_parking_system.ino` di **Arduino IDE** atau **PlatformIO**
2. Ubah konfigurasi WiFi dan API server:
   ```cpp
   const char* ssid = "Nama_WiFi_Anda";
   const char* password = "Password_WiFi_Anda";
   String serverName = "http://IP_KOMPUTER_ANDA:8000/api/v1";
   ```
3. Install library yang diperlukan di Arduino IDE:
   - `WiFi` (built-in ESP32)
   - `HTTPClient` (built-in ESP32)
   - `MFRC522` (by GitHubCommunity)
   - `ESP32Servo` (by Kevin Harrington)
   - `Adafruit SSD1306` & `Adafruit GFX Library`
4. Upload firmware ke ESP32

### 8. Menjalankan Testing

```bash
# Unit & Feature testing (Pest/PHPUnit)
composer test

# Load testing (Locust) — membutuhkan Python
pip install locust
locust -f selenium/locustfile.py

# Selenium testing — membutuhkan Python & WebDriver
python selenium/test_selenium.py
```

---

## Containerization (Docker)

Project ini mendukung containerization menggunakan **Docker Compose** dengan 3 service:
- **app** — PHP 8.2-FPM (Laravel application + queue worker)
- **nginx** — Nginx web server (reverse proxy + static files)
- **mysql** — MySQL 8.0 database server

### Prasyarat Docker

- **Docker** >= 20.10
- **Docker Compose** >= 2.0

### Jalankan dengan Docker

```bash
# 1. Clone repository
git clone https://github.com/Restupradana/Smart_Parking_System.git
cd Smart_Parking_System

# 2. Build & jalankan semua container
docker-compose up -d --build

# 3. Jalankan migrate + seed (otomatis via entrypoint, atau manual)
docker-compose exec app php artisan migrate --seed

# 4. Aplikasi berjalan di
#    http://localhost:8000
```

### Perintah Docker Lainnya

```bash
# Lihat logs semua container
docker-compose logs -f

# Lihat logs hanya app
docker-compose logs -f app

# Stop containers (data MySQL tetap tersimpan)
docker-compose down

# Stop + hapus semua data volume
docker-compose down -v

# Rebuild container (WAJIB setelah perubahan kode)
docker-compose up -d --build

# Masuk ke container app (bash)
docker-compose exec app bash

# Jalankan artisan command
docker-compose exec app php artisan <command>

# Jalankan migration manual
docker-compose exec app php artisan migrate

# Jalankan seeder
docker-compose exec app php artisan db:seed
```

> **Penting:** Karena semua file sudah ada di dalam image (tidak ada volume mount), setiap kali Anda mengubah source code, Anda **wajib rebuild** dengan `docker-compose up -d --build` agar perubahan terlihat di aplikasi.

### Konfigurasi ESP32 dengan Docker

Saat menggunakan Docker, pastikan firmware ESP32 mengarah ke port **8000** (Nginx):

```cpp
String serverName = "http://IP_KOMPUTER_ANDA:8000/api/v1";
```

### Struktur Docker

```
Smart_Parking_System/
├── Dockerfile                  # Build image PHP-FPM + Laravel
├── docker-compose.yml          # Orchestration: app + nginx + mysql
├── .dockerignore               # File yang di-exclude dari build context
└── docker/
    ├── entrypoint.sh           # Startup script (wait MySQL, migrate)
    └── nginx/
        └── default.conf        # Konfigurasi Nginx server block
```

---

## API Endpoints

| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| POST | `/api/v1/rfid` | Validasi kartu RFID (gate masuk/keluar) | None |
| POST | `/api/v1/rid/scan` | Scan RFID untuk form tambah user (cache 15 detik) | None |
| GET | `/api/v1/rfid/latest` | Ambil RFID tag terakhir yang di-scan | None |
| POST | `/api/v1/sensor` | Update status slot parkir dari sensor ultrasonik | None |

---

## Struktur Database

| Tabel | Keterangan |
|-------|------------|
| `users` | Akun pengguna (name, email, wa_number, role_id) |
| `roles` | Role pengguna (admin, petugas, pengguna) |
| `parking_areas` | Area parkir (nama, lokasi) |
| `parking_slots` | Slot parkir (nomor, status: kosong/terisi) |
| `sensors` | Data sensor ultrasonik |
| `rfid_cards` | Kartu RFID terdaftar (UID, user_id, is_active) |
| `rfid_logs` | Log scan RFID (masuk/keluar) |
| `parking_transactions` | Transaksi parkir (waktu masuk/keluar) |
| `home_sections` | Konten landing page (CMS) |

---

## Kontributor

| Nama | GitHub |
|------|--------|
| **Puji Restu Pradana** | [@Restupradana](https://github.com/Restupradana) |

---

## License

This project is licensed under the MIT License.

---

## Catatan Penting

- **Docker Support** — Project ini sudah mendukung containerization dengan Docker Compose. Lihat section [Containerization (Docker)](#containerization-docker) untuk panduan lengkap.
- **API Security** — Endpoint API untuk ESP32 tidak menggunakan autentikasi (tanpa Sanctum/Passport). Pertimbangkan untuk menambahkan API token di production.
