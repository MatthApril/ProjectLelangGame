<h1 align="center">⚖️ Proyek LelangGame</h1>

<p align="center">
    <img src="readme_img/LogoWarna-RemoveBg.png" width="30%" alt="homepage">
</p>

## 🛍️ Tentang Aplikasi
LelangGame adalah marketplace terpercaya tempat kamu bisa jual-beli item game dengan mudah dan cepat.

Tak perlu khawatir soal keamanan; setiap transaksi dilindungi oleh sistem kami. Jika barang yang diterima tidak sesuai, kamu bisa langsung mengajukan komplain. Tim admin kami akan meninjau setiap kasus secara adil untuk memastikan hakmu terlindungi, termasuk proses refund jika terjadi kendala. 
</br> </br>
Kelebihan Aplikasi ini:
- Fitur Chat Langsung: Hubungi penjual secara real-time untuk menanyakan detail item, stok, atau sekadar bernegosiasi sebelum melakukan pembayaran.
- Transaksi Aman (Sistem Rekber): Dana pembeli ditahan oleh sistem dan hanya akan diteruskan ke penjual setelah barang dikonfirmasi telah diterima.
- Perlindungan Refund & Komplain: Tak perlu takut tertipu. Jika barang tidak sesuai, tim admin kami siap memproses komplain dan mengembalikan dana secara adil.
- Ekosistem Terpercaya: Setiap transaksi diawasi oleh admin profesional untuk memastikan kenyamanan bagi penjual maupun pembeli.
- Transparansi Status: Pantau status pesanan dan proses pengiriman item secara real-time di dalam aplikasi.

Nikmati pengalaman trading yang transparan, aman, dan tanpa rasa was-was. Gabung sekarang dan mulai transaksi pertamamu!
</br> </br>

## 📚 Guide Book
<p> Klik tombol dibawah ini untuk mendownload Buku Panduan! </p>
<a href="readme_img/GuideBook.pdf?raw=true" download="LelangGame_GuideBook.pdf"> 
    <img src="https://img.shields.io/badge/⬇️-Unduh Panduan-1E90FF.svg?style=flat"
         alt="Unduh Panduan"
         width="200">
</a>
</br> </br>

## ✨ Fitur - Fitur Aplikasi
### 🔒 Keamanan Akun Berlapis
Sistem autentikasi berlapis untuk menjaga akun tetap aman setiap saat.
- Verifikasi **OTP via Email**
- **Captcha** untuk menangkal bot
- **Rate Limiter (Throttling)** untuk mencegah brute-force

---

### ⚡ Sistem Lelang Barang (Auction)
Dapatkan harga terbaik untuk item langka dan edisi terbatas.
- Penjual menentukan **harga dasar** dan **batas waktu**
- Pembeli melakukan **bidding kompetitif**
- Pemenang ditentukan **otomatis saat waktu habis**

---

### 👨‍💻 User-to-User Marketplace
Semua pengguna bisa menjadi penjual dengan mudah.
- Upgrade akun menjadi **Penjual**
- Unggah item game dan atur harga
- Monetisasi hobi bermain game

---

### 🔑 Fitur Remember Me
Kenyamanan tanpa mengorbankan keamanan.
- Login tetap aktif
- Sesi pengguna tetap aman

---

### 🛒 Keranjang Belanja (Add to Cart)
Belanja banyak item dalam satu proses.
- Gabungkan produk dari **berbagai penjual**
- **Satu kali checkout** untuk semua item

---

### ⭐ Rating & Ulasan Produk / Toko
Membangun kepercayaan dan reputasi yang transparan.
- Rating **bintang**
- Ulasan setelah transaksi selesai
- Referensi kualitas bagi pengguna lain

---

### 💬 Chat Real-Time
Komunikasi instan tanpa reload halaman.
- Teknologi **WebSocket (Laravel Reverb)**
- Diskusi & negosiasi langsung antara penjual dan pembeli

---

### 🔔 Notifikasi Terintegrasi
Tidak ada aktivitas yang terlewat.
- Status pesanan
- Pesan baru
- Peringatan saat **tawaran lelang terlampaui**

---

### 🛡️ Sistem Komplain & Resolusi
Perlindungan maksimal untuk pembeli.
- Pengajuan komplain resmi
- Review oleh **Admin**
- Keputusan **refund yang adil**

---

### 💳 Pembayaran Otomatis
Pembayaran cepat, aman, dan fleksibel.
- Integrasi **Midtrans**
- Transfer Bank
- E-Wallet (Gopay / OVO)
- **QRIS**
- Status pembayaran **real-time**

---

### 📊 Laporan Transaksi
Kelola keuangan dengan profesional.
- Unduh laporan **Excel**
- Unduh laporan **PDF**
- Cocok untuk pembukuan pribadi
</br> </br>

## 🛠️ Instalasi dan konfigurasi
### ⚙️ Konfigurasi Environment (.env)
Buat file `.env` dengan menyalin dari `.env.example`, lalu sesuaikan nilainya dengan konfigurasi lokal Anda.
```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

# PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE="nama_database_anda"
DB_USERNAME="username_database_anda"
DB_PASSWORD="password_database_anda"

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME='username_email_anda'
MAIL_PASSWORD='kode_akses_sharing_email_anda'
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="username_email_anda"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

BROADCAST_CONNECTION=reverb

REVERB_APP_ID='id_unik_aplikasi_reverb_anda'
REVERB_APP_KEY='kunci_publik_untuk_koneksi_klien'
REVERB_APP_SECRET='kunci_rahasia_untuk_koneksi_server'

REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

MIDTRANS_MERCHANT_ID='midtrans_merchant_id_anda'
MIDTRANS_CLIENT_KEY='midtrans_client_key_anda'
MIDTRANS_SERVER_KEY='midtrans_server_key_anda'

```

### 📦 Packages Yang Dipakai
Sebelum menjalankan nya install dulu package di bawah ini di terminal anda!

1. Package Vendor
```
composer i 
```
2. Package Node Modules (Web Socket)
```
npm i 
```
3. Build Web Socket Terlebih Dahulu!
```
npm run build
```
4. Link ke Folder Storage Proyek
```
php artisan storage:link
```

### 🚀 Cara Run Proyek

</br> </br>

### ❗ Kemungkinan Error Instalasi
</br> </br>

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**


