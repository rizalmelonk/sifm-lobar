<?php require 'config.php';need();
$s=$pdo->prepare('SELECT * FROM anggota WHERE id=?');$s->execute([(int)($_GET['id']??0)]);$m=$s->fetch();
if(!$m){http_response_code(404);exit('Data tidak ditemukan');}
$ini=mb_strtoupper(implode('',array_map(fn($w)=>mb_substr($w,0,1),array_slice(preg_split('/\s+/',trim($m['nama'])),0,2))));
head('KTA '.$m['nama']);?>
<div class="bar noprint"><a class="btn ghost" href="index.php?k=<?=e($m['kategori'])?>">Kembali</a><button class="btn" onclick="print()">Cetak KTA</button></div>
<div class="kta">
 <div class="kt"><i></i><img src="assets/logo.png" alt=""><i></i></div>
 <div class="kbody"><dl>
  <div><dt>Nama</dt><dd><?=e($m['nama'])?></dd></div>
  <div><dt>Jabatan</dt><dd><?=e($m['jabatan']?:KAT[$m['kategori']])?></dd></div>
  <div><dt>Asal</dt><dd><?=e($m['asal']?:'Lombok Barat')?></dd></div>
  <div><dt>Status / Tahun</dt><dd><?=e(KAT[$m['kategori']].' '.$m['tahun'])?></dd></div></dl>
  <div class="kph"><?php if($m['foto']):?><img src="uploads/<?=e($m['foto'])?>" alt="Foto"><?php else:?><?=e($ini)?><?php endif?></div></div>
 <div class="knum">No. KTA <b><?=e($m['no_kta'])?></b></div>
 <div class="kband"><img src="assets/logo.png" alt=""><b>KARTU TANDA ANGGOTA</b><span>Front Mahasiswa Lombok Barat</span></div>
 <div class="ksig">Pimpinan FM Lobar<br>Ketua Umum<img src="assets/tanda-tangan.png" alt="Tanda tangan"><b><?=e(KETUA)?></b></div>
</div>
<?php foot();
