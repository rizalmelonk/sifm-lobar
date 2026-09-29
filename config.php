<?php
session_start();
$pdo=new PDO('mysql:host=127.0.0.1;dbname=sifm_lobar;charset=utf8mb4','root','123456',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
const KETUA='AHMAD RIZAL';
const KAT=['pengurus'=>'Pengurus','kader'=>'Kader','alumni'=>'Alumni'];
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function need(){if(empty($_SESSION['u'])){header('Location: login.php');exit;}}
function csrf(){return $_SESSION['t']??($_SESSION['t']=bin2hex(random_bytes(16)));}
function chk(){if(!hash_equals($_SESSION['t']??'',$_POST['t']??'')){http_response_code(400);exit('Token tidak valid. Muat ulang halaman.');}}
function nomor($pdo,$k,$y){
 $c=['pengurus'=>'P','kader'=>'K','alumni'=>'A'][$k];
 $q=$pdo->prepare("SELECT MAX(CAST(SUBSTRING_INDEX(no_kta,'/',-1) AS UNSIGNED)) m FROM anggota WHERE kategori=?");$q->execute([$k]);
 return sprintf('SIFM-LBR/%s/%s/%03d',$c,$y?:'0000',($q->fetch()['m']??0)+1);}
function head($t){
 echo '<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.e($t).' – Sistem Informasi FM Lobar</title><link rel="stylesheet" href="assets/style.css"></head><body><header class="noprint"><div class="w top"><img src="assets/logo.png" alt="Logo"><div><b>Sistem Informasi FM Lobar</b><small>Front Mahasiswa Lombok Barat</small></div>';
 if(!empty($_SESSION['u']))echo '<nav><a href="index.php">Beranda</a><a href="import.php">Impor register</a><a href="ganti-password.php">Password</a><a href="login.php?out=1">Keluar</a></nav>';
 echo '</div></header><main class="w">';}
function foot(){echo '</main></body></html>';}
