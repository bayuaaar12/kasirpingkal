# Kasir Pingkal

Kasir Pingkal adalah aplikasi kasir berbasis Laravel untuk mengelola produk, kategori, customer/member, user admin, dan transaksi penjualan. Aplikasi ini juga menyediakan API sederhana untuk registrasi dan deteksi customer member berbasis label wajah.

## Fitur Utama

- Login admin dan dashboard kasir.
- Manajemen produk, kategori, customer, user, dan transaksi.
- Detail transaksi dengan perhitungan item.
- Registrasi customer dengan gambar wajah.
- API deteksi member berdasarkan `face_label`.

## Teknologi

- PHP 8.0+
- Laravel 9
- MySQL
- Vite
- Laravel Sanctum

## Cara Menjalankan

1. Clone repository dan masuk ke folder proyek.

   ```bash
   git clone https://github.com/bayuaaar12/kasirpingkal.git
   cd kasirpingkal
   ```

2. Install dependency backend dan frontend.

   ```bash
   composer install
   npm install
   ```

3. Buat file environment dan generate app key.

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Atur koneksi database di `.env`.

   ```env
   DB_DATABASE=nama_database
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Jalankan migrasi dan seeder.

   ```bash
   php artisan migrate --seed
   ```

6. Jalankan aplikasi.

   ```bash
   php artisan serve
   npm run dev
   ```

7. Buka aplikasi di browser.

   ```text
   http://127.0.0.1:8000
   ```

## API Customer

- `POST /api/customers/register-face`
- `POST /api/customers/detect-member`

## Testing

Jalankan test dengan:

```bash
php artisan test
```

## Lisensi

Proyek ini menggunakan lisensi MIT. Lihat file [LICENSE](LICENSE) untuk detail.
