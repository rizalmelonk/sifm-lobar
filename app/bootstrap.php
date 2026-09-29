<?php
// Dimuat oleh setiap halaman di folder public/
session_start();
$cfg=require __DIR__.'/config.php';
defined('KUNCI_VERIF') || define('KUNCI_VERIF','sifm-lobar-K7x9Qm2Zp4Lw8Vt3');
defined('KETUA') || define('KETUA','Ahmad Rizal');
const KAT=['pengurus'=>'Pengurus','kader'=>'Kader','alumni'=>'Alumni']; // = nama tabel di database
function gagal($x){
 http_response_code(500);
 exit('Koneksi database gagal. Pastikan MySQL menyala dan user/password di app/config.php benar. Detail: '.htmlspecialchars($x->getMessage()));}
$d=$cfg['db'];
$opt=[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC];
$dsn="mysql:host={$d['host']};charset=utf8mb4";
try{$pdo=new PDO("$dsn;dbname={$d['name']}",$d['user'],$d['pass'],$opt);}
catch(PDOException $x){
 if(($x->errorInfo[1]??0)==1049){ // database belum ada -> dibuat otomatis
  try{$pdo=new PDO($dsn,$d['user'],$d['pass'],$opt);
   $pdo->exec("CREATE DATABASE `{$d['name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
   $pdo->exec("USE `{$d['name']}`");}
  catch(PDOException $y){gagal($y);}
 }else gagal($x);
}
require __DIR__.'/helpers.php';
require __DIR__.'/layout.php';
require __DIR__.'/skema.php';
// Menyesuaikan database yang sudah ada (tanpa menghapus data), sekali per sesi
if(empty($_SESSION['skema_ok'])){try{pasang_skema($pdo);$_SESSION['skema_ok']=1;}catch(PDOException $x){gagal($x);}}
