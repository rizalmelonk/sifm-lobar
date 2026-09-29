-- =====================================================
-- DATABASE: SISTEM INFORMASI FM LOBAR
-- Tabel: admin, pengurus, kader, alumni
-- Jalankan seluruh file ini sekali (HeidiSQL / phpMyAdmin)
-- =====================================================
-- Jika sebelumnya sudah menjalankan versi lama, hapus dulu dengan menghapus tanda -- di baris berikut:
-- DROP DATABASE IF EXISTS sifm_lobar;

CREATE DATABASE IF NOT EXISTS sifm_lobar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sifm_lobar;

-- Akun yang boleh masuk ke sistem.
-- Akun awal (admin / admin123) dibuat otomatis saat halaman login pertama dibuka.
CREATE TABLE IF NOT EXISTS admin(
 id INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(50) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Pengurus
CREATE TABLE IF NOT EXISTS pengurus(
 id INT AUTO_INCREMENT PRIMARY KEY,
 no_kta VARCHAR(40) NULL UNIQUE,
 nama VARCHAR(120) NOT NULL,
 jabatan VARCHAR(100),
 asal VARCHAR(100),
 no_hp VARCHAR(20),
 tahun VARCHAR(4),
 foto VARCHAR(100),
 status ENUM('menunggu','aktif') NOT NULL DEFAULT 'aktif',
 dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX(tahun)
) ENGINE=InnoDB;

-- Kader
CREATE TABLE IF NOT EXISTS kader(
 id INT AUTO_INCREMENT PRIMARY KEY,
 no_kta VARCHAR(40) NULL UNIQUE,
 nama VARCHAR(120) NOT NULL,
 jabatan VARCHAR(100),
 asal VARCHAR(100),
 no_hp VARCHAR(20),
 tahun VARCHAR(4),
 foto VARCHAR(100),
 status ENUM('menunggu','aktif') NOT NULL DEFAULT 'aktif',
 dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX(tahun)
) ENGINE=InnoDB;

-- Alumni
CREATE TABLE IF NOT EXISTS alumni(
 id INT AUTO_INCREMENT PRIMARY KEY,
 no_kta VARCHAR(40) NULL UNIQUE,
 nama VARCHAR(120) NOT NULL,
 jabatan VARCHAR(100),
 asal VARCHAR(100),
 no_hp VARCHAR(20),
 tahun VARCHAR(4),
 foto VARCHAR(100),
 status ENUM('menunggu','aktif') NOT NULL DEFAULT 'aktif',
 dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX(tahun)
) ENGINE=InnoDB;
