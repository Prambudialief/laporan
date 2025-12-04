Instalasi & Setup

Berikut langkah untuk menjalankan secara lokal:

Clone repository ini
git clone https://github.com/Prambudialief/laporan.git
cd laporan

Persiapkan server lokal
Gunakan web server seperti Apache / Nginx, atau gunakan versi lokal seperti XAMPP, MAMP, Laragon, dsb. Pastikan PHP sudah terinstall.

Import database
Jika ada file .sql (misalnya laporan_2025-12-04_125727.sql), impor ke database MySQL kamu agar tabel dan struktur data tersedia.

Konfigurasi koneksi
Edit file konfigurasi database pada folder services/connection.php agar sesuai dengan kredensial MySQL lokal kamu (host, username, password, nama database).

Jalankan aplikasi
Akses melalui browser, misalnya http://localhost/laporan atau sesuai direktori tempat kamu meletakkan proyek.
