<?php
// Membuat tabel yang belum ada dan menambah kolom baru pada tabel lama. Aman dijalankan berulang.
function pasang_skema($pdo){
 $pdo->exec("CREATE TABLE IF NOT EXISTS admin(id INT AUTO_INCREMENT PRIMARY KEY,username VARCHAR(50) NOT NULL UNIQUE,password VARCHAR(255) NOT NULL,dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB");
 foreach(KAT as $t=>$_){
  $pdo->exec("CREATE TABLE IF NOT EXISTS $t(id INT AUTO_INCREMENT PRIMARY KEY,no_kta VARCHAR(40) NULL UNIQUE,nama VARCHAR(120) NOT NULL,jabatan VARCHAR(100),asal VARCHAR(100),no_hp VARCHAR(20),tahun VARCHAR(4),foto VARCHAR(100),status ENUM('menunggu','aktif') NOT NULL DEFAULT 'aktif',dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,INDEX(tahun)) ENGINE=InnoDB");
  $ada=function($c)use($pdo,$t){return (bool)$pdo->query("SHOW COLUMNS FROM $t LIKE '$c'")->fetch();};
  if(!$ada('no_hp'))$pdo->exec("ALTER TABLE $t ADD COLUMN no_hp VARCHAR(20) NULL");
  if(!$ada('status'))$pdo->exec("ALTER TABLE $t ADD COLUMN status ENUM('menunggu','aktif') NOT NULL DEFAULT 'aktif'");
  $pdo->exec("ALTER TABLE $t MODIFY no_kta VARCHAR(40) NULL");
 }
}
