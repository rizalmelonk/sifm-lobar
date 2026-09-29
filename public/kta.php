<?php require __DIR__.'/../app/bootstrap.php';need();
$k=$_GET['k']??'';if(!isset(KAT[$k]))exit('Kategori tidak valid');
$s=$pdo->prepare("SELECT * FROM $k WHERE id=?");$s->execute([(int)($_GET['id']??0)]);$m=$s->fetch();
if(!$m){http_response_code(404);exit('Data tidak ditemukan');}
$ini=mb_strtoupper(implode('',array_map(fn($w)=>mb_substr($w,0,1),array_slice(preg_split('/\s+/',trim($m['nama'])),0,2))));

// Tautan verifikasi untuk QR
$tok=substr(hash_hmac('sha256',$k.'|'.$m['id'],KUNCI_VERIF),0,16);
$url=(!empty($_SERVER['HTTPS'])?'https':'http').'://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']),'/').'/verifikasi.php?k='.$k.'&id='.$m['id'].'&t='.$tok;

head('KTA '.$m['nama'],$k);
judul('Kartu Tanda Anggota','Pratinjau dan cetak KTA '.e($m['nama']).'.','<a class="btn ghost" href="data.php?k='.e($k).'">‹ Kembali</a><button class="btn" onclick="print()">🖨 Cetak KTA</button>');?>
<div class="ktawrap">
 <div class="kta">
  <div class="kt"><i></i><img src="assets/logo.png" alt=""><i></i></div>
  <div class="kbody"><dl>
   <div><dt>Nama</dt><dd><?=e($m['nama'])?></dd></div>
   <div><dt>Jabatan</dt><dd><?=e($m['jabatan']?:KAT[$k])?></dd></div>
   <div><dt>Asal</dt><dd><?=e($m['asal']?:'Lombok Barat')?></dd></div>
   <div><dt>Status / Tahun</dt><dd><?=e(KAT[$k].' '.$m['tahun'])?></dd></div></dl>
   <div class="kph"><?php if($m['foto']):?><img src="uploads/<?=e($m['foto'])?>" alt="Foto"><?php else:?><?=e($ini)?><?php endif?></div></div>
  <div class="knum">No. KTA <b><?=e($m['no_kta'])?></b></div>
  <div class="kband"><img src="assets/logo.png" alt=""><b>KARTU TANDA ANGGOTA</b><span>Front Mahasiswa Lombok Barat</span></div>
  <div class="ksig">Mengetahui,<br>Ketua Umum<div id="qr" class="kqr"></div><b><?=e(KETUA)?></b></div>
 </div><!-- /kta -->

 <div class="card noprint"><h2>Data anggota</h2>
  <dl class="dl"><dt>Nomor KTA</dt><dd><?=e($m['no_kta'])?></dd><dt>Nama</dt><dd><?=e($m['nama'])?></dd><dt>Kategori</dt><dd><?=KAT[$k]?></dd><dt>Jabatan</dt><dd><?=e($m['jabatan']?:'-')?></dd><dt>Asal</dt><dd><?=e($m['asal']?:'-')?></dd><dt>Nomor HP</dt><dd><?=e($m['no_hp']?:'-')?></dd><dt>Tahun</dt><dd><?=e($m['tahun']?:'-')?></dd><dt>Terdaftar</dt><dd><?=e(substr($m['dibuat'],0,10))?></dd></dl>
  <div class="acts"><a class="btn ghost" href="anggota.php?k=<?=e($k)?>&id=<?=$m['id']?>">Edit data</a></div>
  <p class="sub" style="margin:0">Tips cetak: pilih “Simpan sebagai PDF” atau printer, lalu aktifkan “Background graphics” agar warna kartu ikut tercetak.</p>
 </div>
</div><!-- /ktawrap -->

<style>
.kqr{width:64px;height:64px;margin:4px auto}
.kqr img,.kqr canvas{width:100%!important;height:100%!important}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>new QRCode(document.getElementById('qr'),{text:<?=json_encode($url)?>,width:128,height:128,correctLevel:QRCode.CorrectLevel.M});</script>
<?php foot();