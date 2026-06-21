# Panduan Instalasi

## 1. Pendahuluan

Dokumen ini merupakan panduan instalasi untuk proyek **Sistem Informasi Praktik Dokter Mandiri**, yang dikembangkan sebagai bagian dari Proyek Akhir mata kuliah Pemrograman SQL. Panduan ini ditujukan untuk memberikan instruksi yang jelas dan sistematis kepada pengguna dalam melakukan proses instalasi, konfigurasi, serta pengoperasian aplikasi pada lingkungan lokal.

Aplikasi ini dibangun menggunakan bahasa pemrograman **PHP 8.1** dengan **Apache** sebagai *web server*, serta **MySQL 8.0** sebagai sistem manajemen basis data. Seluruh komponen aplikasi dikemas dalam lingkungan **Docker** menggunakan **Docker Compose**, sehingga proses instalasi dapat dilakukan secara konsisten di berbagai sistem operasi tanpa memerlukan konfigurasi manual untuk setiap dependensi. Pendekatan kontainerisasi ini memastikan bahwa seluruh layanan — termasuk *web server*, basis data, dan antarmuka manajemen basis data (phpMyAdmin) — dapat dijalankan secara terisolasi dan terkoordinasi dalam satu perintah.

Kode sumber proyek ini tersedia secara publik pada repositori GitHub di alamat:
**https://github.com/Kyuteela/Proyek-Aplikasi-Praktek-Dokter**

---

## 2. Prasyarat (*Prerequisites*)

Sebelum memulai proses instalasi, pengguna perlu memastikan bahwa perangkat lunak berikut telah terinstal pada komputer yang akan digunakan:

- **Git** — Sistem kontrol versi yang digunakan untuk mengunduh (*clone*) kode sumber dari repositori GitHub. Git dapat diunduh melalui situs resmi di https://git-scm.com/downloads.

- **Docker Desktop** *(untuk Windows dan macOS)* — Aplikasi yang menyediakan lingkungan Docker lengkap beserta Docker Compose. Docker Desktop dapat diunduh melalui https://www.docker.com/products/docker-desktop/. Untuk pengguna **Linux**, instalasi dapat dilakukan melalui **Docker Engine** dan **Docker Compose** secara terpisah sesuai dokumentasi resmi Docker.

- **Docker Compose** — Alat untuk mendefinisikan dan menjalankan aplikasi multi-kontainer. Pada Docker Desktop versi terbaru, Docker Compose sudah terintegrasi secara bawaan dan dapat diakses melalui perintah `docker compose` (tanpa tanda hubung).

Selain itu, pengguna juga perlu memastikan bahwa **port-port berikut tidak sedang digunakan** oleh aplikasi lain pada komputer:

- **Port 8080** — Digunakan oleh layanan *web server* (Apache/PHP).
- **Port 8081** — Digunakan oleh layanan phpMyAdmin.
- **Port 3306** — Digunakan oleh layanan MySQL.

Apabila salah satu port tersebut telah digunakan oleh aplikasi lain, proses pembuatan kontainer akan mengalami kegagalan. Pengguna disarankan untuk menghentikan aplikasi yang menggunakan port tersebut terlebih dahulu sebelum melanjutkan.

---

## 3. Langkah-Langkah Instalasi

Berikut adalah langkah-langkah instalasi yang harus dilakukan secara berurutan:

### Langkah 1: *Clone* Repositori dari GitHub

Buka aplikasi terminal (Command Prompt, PowerShell, atau Terminal), kemudian jalankan perintah berikut untuk mengunduh kode sumber proyek dari repositori GitHub:

```bash
git clone https://github.com/Kyuteela/Proyek-Aplikasi-Praktek-Dokter.git
```

Perintah di atas akan membuat salinan lengkap repositori proyek ke dalam direktori lokal bernama `Proyek-Aplikasi-Praktek-Dokter`.

### Langkah 2: Masuk ke Direktori Proyek

Setelah proses *clone* selesai, navigasikan terminal ke dalam direktori proyek dengan perintah:

```bash
cd Proyek-Aplikasi-Praktek-Dokter
```

### Langkah 3: Membangun dan Menjalankan Kontainer

Jalankan perintah berikut untuk membangun *image* Docker dan menjalankan seluruh kontainer secara bersamaan di latar belakang (*detached mode*):

```bash
docker-compose up -d --build
```

Penjelasan opsi yang digunakan:

- **`-d`** (*detached*) — Menjalankan kontainer di latar belakang sehingga terminal tetap dapat digunakan.
- **`--build`** — Memastikan *image* Docker untuk layanan `web` dibangun ulang berdasarkan `Dockerfile` yang tersedia.

Pada eksekusi pertama, Docker akan mengunduh *base image* yang diperlukan, yaitu `php:8.1-apache`, `mysql:8.0`, dan `phpmyadmin:latest`. Proses ini memerlukan koneksi internet dan durasinya bergantung pada kecepatan jaringan pengguna.

Konfigurasi Docker pada proyek ini terdiri dari dua file utama:

**a. Dockerfile**

`Dockerfile` digunakan untuk membangun *custom image* layanan `web`. File ini berbasis *image* `php:8.1-apache` dan menambahkan ekstensi `mysqli` yang diperlukan untuk koneksi PHP ke MySQL:

```dockerfile
FROM php:8.1-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
```

**b. docker-compose.yml**

File `docker-compose.yml` mendefinisikan tiga layanan (*service*) yang berjalan secara bersamaan:

1. **Service `web`** — Membangun *image* dari `Dockerfile` lokal, memetakan port `8080` pada *host* ke port `80` pada kontainer, serta melakukan *mount* direktori proyek ke `/var/www/html` agar perubahan kode langsung tercermin tanpa perlu membangun ulang *image*.

2. **Service `db`** — Menggunakan *image* `mysql:8.0` dengan konfigurasi *environment variable* `MYSQL_ROOT_PASSWORD=root123` dan `MYSQL_DATABASE=praktik_dokter`. Data disimpan pada *named volume* `db_data` untuk menjaga persistensi data meskipun kontainer dihentikan.

3. **Service `phpmyadmin`** — Menggunakan *image* `phpmyadmin:latest` yang terhubung ke *service* `db` melalui konfigurasi `PMA_HOST=db`. Layanan ini memetakan port `8081` pada *host* ke port `80` pada kontainer.

### Langkah 4: Verifikasi Status Kontainer

Setelah perintah pada Langkah 3 selesai dijalankan, verifikasi bahwa seluruh kontainer telah berjalan dengan baik menggunakan salah satu perintah berikut:

```bash
docker-compose ps
```

atau

```bash
docker ps
```

Apabila instalasi berhasil, terminal akan menampilkan tiga kontainer dengan status **Up**, masing-masing untuk layanan `web`, `db`, dan `phpmyadmin`.

`[Masukkan Tangkapan Layar: Output perintah docker-compose ps yang menampilkan ketiga kontainer berstatus Up]`

---

## 4. Impor Database

Setelah seluruh kontainer berjalan, langkah selanjutnya adalah mengimpor struktur dan data basis data ke dalam MySQL. Proyek ini menyertakan enam file SQL di dalam direktori `database/` yang harus diimpor secara berurutan.

### 4.1 Urutan File SQL

Berikut adalah daftar file SQL beserta fungsinya, yang harus diimpor sesuai urutan di bawah ini:

| No. | Nama File | Keterangan |
|:---:|---|---|
| 1 | `THP1-Database.sql` | Membuat struktur tabel (*Data Definition Language*) |
| 2 | `THP1-DataAwal.sql` | Mengisi data awal/master |
| 3 | `THP1-DataOperasional.sql` | Mengisi data operasional |
| 4 | `THP2-DML-Queries-Views.sql` | Membuat *view* dan *query* kompleks |
| 5 | `THP3-StoredPrograms.sql` | Membuat *stored procedure* dan *function* |
| 6 | `THP4-Triggers.sql` | Membuat *trigger* |

### 4.2 Impor Melalui phpMyAdmin

Berikut adalah langkah-langkah untuk mengimpor file SQL menggunakan antarmuka phpMyAdmin:

1. Buka *browser* dan akses phpMyAdmin melalui alamat:

   ```
   http://localhost:8081
   ```

2. Pada halaman login, masukkan kredensial berikut:
   - **Server:** `db`
   - **Username:** `root`
   - **Password:** `root123`

   > **Catatan:** Apabila phpMyAdmin telah dikonfigurasi dengan `PMA_USER` dan `PMA_PASSWORD` pada `docker-compose.yml`, proses login akan dilakukan secara otomatis.

   `[Masukkan Tangkapan Layar: Halaman login phpMyAdmin]`

3. Pada panel navigasi sebelah kiri, klik nama basis data **`praktik_dokter`** untuk memilihnya sebagai basis data aktif.

4. Klik tab **Import** pada menu bagian atas.

5. Klik tombol **Choose File** (atau **Pilih File**), kemudian pilih file SQL pertama yaitu `THP1-Database.sql` dari direktori `database/` pada proyek lokal.

6. Klik tombol **Go** (atau **Kirim**) untuk memulai proses impor.

7. Ulangi langkah 4 hingga 6 untuk setiap file SQL berikutnya, sesuai urutan yang telah disebutkan pada Subbab 4.1.

`[Masukkan Tangkapan Layar: Proses impor file SQL melalui tab Import di phpMyAdmin]`

### 4.3 Impor Melalui Terminal (Alternatif)

Sebagai alternatif, pengguna dapat mengimpor file SQL melalui terminal menggunakan perintah `docker exec`. Jalankan keenam perintah berikut secara berurutan dari direktori proyek:

```bash
docker exec -i proyek-aplikasi-praktek-dokter-db-1 mysql -u root -proot123 praktik_dokter < database/THP1-Database.sql
```

```bash
docker exec -i proyek-aplikasi-praktek-dokter-db-1 mysql -u root -proot123 praktik_dokter < database/THP1-DataAwal.sql
```

```bash
docker exec -i proyek-aplikasi-praktek-dokter-db-1 mysql -u root -proot123 praktik_dokter < database/THP1-DataOperasional.sql
```

```bash
docker exec -i proyek-aplikasi-praktek-dokter-db-1 mysql -u root -proot123 praktik_dokter < database/THP2-DML-Queries-Views.sql
```

```bash
docker exec -i proyek-aplikasi-praktek-dokter-db-1 mysql -u root -proot123 praktik_dokter < database/THP3-StoredPrograms.sql
```

```bash
docker exec -i proyek-aplikasi-praktek-dokter-db-1 mysql -u root -proot123 praktik_dokter < database/THP4-Triggers.sql
```

> **Catatan:** Nama kontainer `proyek-aplikasi-praktek-dokter-db-1` dapat berbeda tergantung pada nama direktori proyek. Pengguna dapat memverifikasi nama kontainer yang tepat melalui perintah `docker ps`. Alternatif lain, pengguna dapat menggunakan perintah `docker-compose exec -T db mysql ...` yang secara otomatis merujuk pada *service* `db` tanpa perlu mengetahui nama kontainer secara eksplisit.

---

## 5. Mengakses Aplikasi

Setelah seluruh kontainer berjalan dan basis data telah diimpor, pengguna dapat mengakses komponen-komponen aplikasi melalui alamat-alamat berikut:

| Komponen | Alamat Akses | Kredensial |
|---|---|---|
| **Aplikasi Web** | `http://localhost:8080` | *(sesuai akun pada sistem)* |
| **phpMyAdmin** | `http://localhost:8081` | Username: `root`, Password: `root123` |
| **MySQL (koneksi langsung)** | `localhost:3306` | Username: `root`, Password: `root123`, Database: `praktik_dokter` |

`[Masukkan Tangkapan Layar: Halaman utama aplikasi web di http://localhost:8080]`

`[Masukkan Tangkapan Layar: Tampilan dashboard phpMyAdmin setelah login]`

Koneksi antara aplikasi web dan basis data dikonfigurasi melalui file `config/koneksi.php` dengan parameter sebagai berikut:

```php
$conn = mysqli_connect(
    "db",           // Host (nama service Docker)
    "root",         // Username
    "root123",      // Password
    "praktik_dokter" // Nama database
);
```

Perlu dicatat bahwa *host* yang digunakan adalah `db` (bukan `localhost`), karena dalam lingkungan Docker Compose, setiap *service* saling berkomunikasi melalui nama *service* yang didefinisikan pada file `docker-compose.yml` sebagai *hostname* dalam jaringan internal Docker.

---

## 6. Menghentikan dan Menghapus Aplikasi

### 6.1 Menghentikan Kontainer

Untuk menghentikan seluruh kontainer tanpa menghapus data yang tersimpan pada *volume*, jalankan perintah berikut dari direktori proyek:

```bash
docker-compose down
```

Perintah ini akan menghentikan dan menghapus seluruh kontainer serta jaringan (*network*) yang dibuat oleh Docker Compose. Namun, data basis data MySQL tetap tersimpan pada *named volume* `db_data`, sehingga ketika kontainer dijalankan kembali menggunakan `docker-compose up -d`, seluruh data akan tetap tersedia tanpa perlu mengimpor ulang file SQL.

### 6.2 Menghentikan Kontainer dan Menghapus Volume Data

Apabila pengguna ingin menghapus seluruh data secara permanen, termasuk isi basis data, gunakan perintah berikut:

```bash
docker-compose down -v
```

Opsi **`-v`** akan menghapus *named volume* `db_data` yang menyimpan data MySQL. **Perhatian:** Setelah perintah ini dijalankan, seluruh data pada basis data akan hilang secara permanen dan pengguna perlu melakukan impor ulang file SQL apabila ingin menjalankan aplikasi kembali.

---

## 7. Pemecahan Masalah (*Troubleshooting*)

Berikut adalah beberapa permasalahan umum yang mungkin ditemui selama proses instalasi beserta solusinya:

### 7.1 Port Sudah Digunakan oleh Aplikasi Lain

**Gejala:** Muncul pesan kesalahan `Bind for 0.0.0.0:8080 failed: port is already allocated` atau pesan serupa saat menjalankan `docker-compose up`.

**Penyebab:** Port 8080, 8081, atau 3306 sudah digunakan oleh aplikasi lain yang sedang berjalan pada komputer, seperti XAMPP, WAMP, atau layanan MySQL lokal.

**Solusi:**

- Identifikasi aplikasi yang menggunakan port tersebut. Pada **Windows**, gunakan perintah:

  ```bash
  netstat -ano | findstr :8080
  ```

  Pada **Linux/macOS**, gunakan perintah:

  ```bash
  lsof -i :8080
  ```

- Hentikan aplikasi yang menggunakan port tersebut, kemudian jalankan ulang `docker-compose up -d`.

- Sebagai alternatif, ubah *port mapping* pada file `docker-compose.yml`. Misalnya, untuk mengubah port layanan web dari `8080` menjadi `9090`:

  ```yaml
  ports:
    - "9090:80"
  ```

### 7.2 Kontainer `db` Belum Sepenuhnya Siap (*Race Condition*)

**Gejala:** Aplikasi web menampilkan pesan kesalahan koneksi basis data (*Connection refused*) meskipun seluruh kontainer terlihat berstatus **Up**.

**Penyebab:** Layanan MySQL pada kontainer `db` memerlukan waktu beberapa detik untuk menyelesaikan proses inisialisasi setelah kontainer dimulai. Meskipun kontainer sudah berstatus **Up**, layanan MySQL di dalamnya mungkin belum sepenuhnya siap menerima koneksi.

**Solusi:**

- Tunggu beberapa saat (sekitar 10–30 detik), kemudian *refresh* halaman aplikasi web.

- Periksa log kontainer `db` untuk memastikan bahwa MySQL telah siap:

  ```bash
  docker-compose logs db
  ```

  Pastikan terdapat pesan `ready for connections` pada keluaran log sebelum mengakses aplikasi.

### 7.3 Koneksi Database Gagal

**Gejala:** Halaman aplikasi menampilkan pesan `Koneksi gagal: ...` atau halaman kosong.

**Penyebab:** Konfigurasi koneksi pada file `config/koneksi.php` tidak sesuai, atau kontainer `db` tidak berjalan.

**Solusi:**

- Verifikasi bahwa kontainer `db` berjalan dengan perintah `docker-compose ps`.

- Pastikan parameter koneksi pada `config/koneksi.php` menggunakan konfigurasi berikut:
  - Host: `db`
  - User: `root`
  - Password: `root123`
  - Database: `praktik_dokter`

- Pastikan bahwa *host* yang digunakan adalah `db` (nama *service* pada Docker Compose), **bukan** `localhost` atau `127.0.0.1`.

### 7.4 *Permission Error* pada Volume Docker

**Gejala:** File tidak dapat dibaca atau ditulis oleh kontainer, atau muncul kesalahan *Permission denied* pada log kontainer.

**Penyebab:** Terdapat ketidaksesuaian hak akses (*permission*) antara pengguna pada sistem *host* dan pengguna di dalam kontainer Docker.

**Solusi:**

- Pada **Linux**, pastikan direktori proyek memiliki hak akses yang memadai:

  ```bash
  chmod -R 755 .
  ```

- Pada **Windows/macOS**, pastikan direktori proyek berada di dalam lokasi yang diizinkan oleh Docker Desktop untuk di-*share* sebagai volume. Pengaturan ini dapat diperiksa melalui menu **Docker Desktop → Settings → Resources → File Sharing**.

### 7.5 Perubahan pada Kode Tidak Terlihat di Browser

**Gejala:** Setelah melakukan perubahan pada file PHP, hasil perubahan tidak tampil di *browser*.

**Penyebab:** *Cache* pada *browser* menyimpan versi halaman yang lama.

**Solusi:**

- Lakukan *hard refresh* pada *browser* dengan menekan **Ctrl + F5** (Windows) atau **Cmd + Shift + R** (macOS).

- Apabila perubahan tetap tidak terlihat, coba *restart* kontainer *web*:

  ```bash
  docker-compose restart web
  ```

---
