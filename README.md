<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/muhamadayeshaaulia/cafe-mami-vue/actions">
    <img src="https://github.com/muhamadayeshaaulia/cafe-mami-vue/new/main?filename=.github%2Fworkflows%2Fnode.js.yml&workflow_template=ci%2Fnode.js" alt="Build Status">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
  </a>
</p>

## Tentang Proyek

**Cafe Mami Vue** adalah aplikasi manajemen kafe berbasis web yang dibangun dengan:

- **Backend**: Laravel 11
- **Frontend**: Vue.js 3 + Inertia.js
- **UI**: Bootstrap CSS
- **HTTP Client**: Axios
- **Notifikasi**: SweetAlert2
- **Email**: SMTP Gmail
- **Export/Import Data**: Maatwebsite/Excel
- **Autentikasi**: Laravel Fortify

Proyek ini dirancang untuk mempermudah pemilik kafe dalam mengelola menu, menerima pesanan, dan memantau transaksi secara digital.

---

## Fitur Utama

- 🛠️ **Manajemen Menu**: Tambah, edit, hapus item menu dengan mudah.
- 📦 **Manajemen Pesanan**: Terima dan kelola pesanan pelanggan secara real-time.
- 📧 **Notifikasi Email**: Kirim konfirmasi pesanan melalui email menggunakan SMTP Gmail.
- 📊 **Export/Import Excel**: Impor dan ekspor data menu dan pesanan dalam format Excel.
- 🎨 **Antarmuka Responsif**: Desain UI yang responsif dan ramah pengguna menggunakan Bootstrap CSS.
- 🔐 **Keamanan dan Autentikasi**: Menggunakan Laravel Fortify untuk registrasi, login, reset password, dan proteksi otentikasi.

---

## Teknologi yang Digunakan

- **Backend**: Laravel 11
- **Frontend**: Vue.js 3, Inertia.js
- **UI**: Bootstrap CSS
- **HTTP Client**: Axios
- **Notifikasi**: SweetAlert2
- **Export/Import Data**: Maatwebsite/Excel
- **Email**: SMTP Gmail
- **Database**: MySQL / PostgreSQL
- **Auth**: Laravel Fortify

---

## Laravel Fortify

Laravel Fortify adalah backend authentication service untuk Laravel. Pada proyek ini, Fortify digunakan untuk:

- Registrasi pengguna baru
- Login dan logout
- Verifikasi email
- Reset password
- Proteksi route menggunakan middleware `auth` dan `verified`

### Contoh Penggunaan Helper

```php
use Illuminate\Support\Facades\Auth;

$user = Auth::user();
