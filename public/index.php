<?php require __DIR__.'/../app/bootstrap.php';
$n=[];foreach(KAT as $c=>$l)$n[$c]=(int)$pdo->query("SELECT COUNT(*) FROM $c WHERE status='aktif'")->fetchColumn();
$tim=($cfg['tampil_pengurus']??true)?$pdo->query("SELECT nama,jabatan,foto FROM pengurus WHERE status='aktif' ORDER BY id LIMIT 12")->fetchAll():[];
$masuk=!empty($_SESSION['u']);?>
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($cfg['nama'])?></title><link rel="icon" href="assets/logo.png"><link rel="stylesheet" href="assets/style.css"></head>
<body class="lp">
<header class="hero">
 <div class="lp-nav"><img src="assets/logo.png" alt=""><b><?=e($cfg['nama'])?></b><span class="sp"></span>
  <a class="lk" href="<?=$masuk?'dashboard.php':'login.php'?>"><?=$masuk?'Dashboard':'Masuk Admin'?></a></div>
 <div class="in">
  <div>
   <h1>Data pengurus, kader, dan alumni <?=e($cfg['org'])?></h1>
   <p>Satu tempat untuk mendaftar, mendata, dan menerbitkan Kartu Tanda Anggota secara tertib.</p>
   <div class="cta"><a class="btn gold lgz" href="daftar.php">Daftar sebagai anggota</a><a class="btn line lgz" href="<?=$masuk?'dashboard.php':'login.php'?>"><?=$masuk?'Buka dashboard':'Masuk admin'?></a></div>
  </div>
  <div class="lg"><img src="assets/logo.png" alt="Logo <?=e($cfg['org'])?>"></div>
 </div>
</header>
<div class="lp-stats">
 <div><b><?=array_sum($n)?></b>Anggota aktif</div>
 <?php foreach(KAT as $c=>$l):?><div><b><?=$n[$c]?></b><?=$l?></div><?php endforeach?>
</div>
<section class="lp-sec"><h2>Cara mendaftar</h2><p class="sub">Tiga langkah, tanpa perlu akun.</p>
 <ol class="steps">
  <li><b>Isi formulir</b>Pilih daftar sebagai pengurus, kader, atau alumni, lalu lengkapi data dan foto.</li>
  <li><b>Menunggu konfirmasi</b>Admin memeriksa data yang masuk lalu menyetujuinya.</li>
  <li><b>KTA terbit</b>Setelah disetujui, Anda mendapat nomor anggota dan Kartu Tanda Anggota.</li>
 </ol>
</section>
<section class="lp-sec"><h2>Untuk siapa</h2><p class="sub">Tiga kelompok anggota yang tercatat di sistem ini.</p>
 <div class="kats"><div><span>🧑‍💼</span><b>Pengurus</b>Yang sedang memegang amanah kepengurusan organisasi.</div><div><span>🌱</span><b>Kader</b>Anggota yang sedang dibina dan dikembangkan.</div><div><span>🎓</span><b>Alumni</b>Anggota yang telah menyelesaikan masa kaderisasi dan kepengurusan.</div></div></section>
<?php if($tim):?>
<section class="lp-sec"><h2>Pengurus</h2><p class="sub">Pengurus <?=e($cfg['org'])?> saat ini.</p>
 <div class="team"><?php foreach($tim as $t):?>
  <div class="tm"><div class="av"><?php if($t['foto']):?><img src="uploads/<?=e($t['foto'])?>" alt=""><?php else:?><?=e(mb_strtoupper(mb_substr($t['nama'],0,1)))?><?php endif?></div><div class="nm"><?=e($t['nama'])?></div><div class="sub"><?=e($t['jabatan']?:'Pengurus')?></div></div>
 <?php endforeach?></div>
</section>
<?php endif?>
<section class="lp-sec"><div class="ctab"><div><h2>Belum terdaftar?</h2><p>Daftar sekarang dan dapatkan Kartu Tanda Anggota setelah disetujui admin.</p></div><a class="btn gold lgz" href="daftar.php">Daftar sekarang</a></div></section>
<footer class="lp-foot"><?=e($cfg['nama'])?> · <?=e($cfg['org'])?> · Nusa Tenggara Barat</footer>
</body></html>
