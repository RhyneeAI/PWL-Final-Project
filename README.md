# My Fanel POS

Sistem POS multi-cabang untuk My Fanel. Dibangun dengan Laravel + Blade + PostgreSQL.

## Fitur

- **Master Data**: Produk, Supplier, Kategori, Cabang, Pengguna
- **Transaksi**: Stok Masuk, Stok Keluar, Penjualan
- **Laporan**: Penjualan (PDF/Excel), filter tanggal & cabang
- **Dashboard**: Ringkasan penjualan, grafik, order terbaru
- **Role**: Owner, Supervisor, Kasir, Gudang

## Requirements

- PHP 8.3+
- PostgreSQL 16+
- Composer
- Node.js & NPM

## Instalasi

```bash
composer install
cp .env.example .env   # atur database PostgreSQL
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install && npm run build
php artisan serve
```

## Login

| Username         | Role       | Password   |
|------------------|------------|------------|
| owner            | Owner      | `password` |
| supervisor-bdg   | Supervisor | `password` |
| kasir-bdg        | Kasir      | `password` |
| gudang-bdg       | Gudang     | `password` |

(Username untuk cabang lain: `-jkt`, `-sby`, `-smg`, `-yog`)

## Test

```bash
php artisan test
```
