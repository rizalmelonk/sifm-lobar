SISTEM INFORMASI FM LOBAR (v3 - dashboard + konfirmasi pendaftaran)
===================================================================
ALUR
  1. Calon pengurus / kader / alumni mengisi  public/daftar.php  (tanpa login).
  2. Data masuk dengan status "menunggu".
  3. Admin membuka menu "Konfirmasi Pendaftaran" -> Setujui (terbit nomor KTA) atau Tolak.
  4. Data yang disetujui tampil di Dashboard, Data Pengurus / Kader / Alumni, dan bisa cetak KTA.

STRUKTUR FOLDER
  sifm-lobar/
  |-- index.php               mengarahkan ke folder public
  |-- app/                    kode inti (tidak bisa dibuka dari peramban)
  |   |-- config.php             database, nama ketua, nama organisasi
  |   |-- bootstrap.php, helpers.php, layout.php, skema.php (penyesuaian database otomatis)
  |-- public/
  |   |-- login.php              masuk admin
  |   |-- daftar.php             formulir pendaftaran (publik)
  |   |-- index.php              DASHBOARD (kartu statistik + diagram)
  |   |-- konfirmasi.php         setujui / tolak pendaftaran
  |   |-- data.php               daftar pengurus / kader / alumni (?k=pengurus|kader|alumni)
  |   |-- anggota.php            tambah / edit / hapus
  |   |-- kta.php                kartu tanda anggota + cetak
  |   |-- import.php, ekspor.php impor register CSV / ekspor CSV
  |   |-- ganti-password.php
  |   |-- assets/, uploads/
  |-- database/
      |-- database.sql           cadangan struktur (opsional, aplikasi membuatnya otomatis)
      |-- data-contoh.sql        opsional

PASANG / PERBARUI DI LARAGON
  1. Salin isi public\uploads lama (jika ada foto), hapus folder lama, ekstrak folder ini ke C:\laragon\www\
  2. Laragon: Start All. TIDAK perlu impor/migrasi SQL manual: saat halaman pertama dibuka,
     aplikasi otomatis membuat database & tabel yang belum ada dan menambah kolom baru
     (no_hp, status) pada tabel lama tanpa menghapus data.
  3. Buka http://sifm-lobar.test  -> masuk admin / admin123 -> ganti password.
  Formulir pendaftaran publik: http://sifm-lobar.test/public/daftar.php
  Jika MySQL memakai password, isi di app/config.php.
