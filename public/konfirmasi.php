<?php require __DIR__.'/../app/bootstrap.php';
// Halaman PUBLIK: jangan panggil need() agar pemindai QR tidak diminta login
$k=$_GET['k']??'';$id=(int)($_GET['id']??0);$t=$_GET['t']??'';
$ok=false;$m=null;
if(isset(KAT[$k])){
  $benar=substr(hash_hmac('sha256',$k.'|'.$id,KUNCI_VERIF),0,16);
  if(hash_equals($benar,$t)){
    $s=$pdo->prepare("SELECT nama,jabatan,asal,tahun,no_kta FROM $k WHERE id=?");
    $s->execute([$id]);$m=$s->fetch();$ok=(bool)$m;
  }
}
?><!doctype html><html lang="id"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Verifikasi KTA</title>
<style>body{font-family:system-ui,sans-serif;background:#f3ede4;margin:0;padding:24px}
.box{max-width:420px;margin:auto;background:#fff;border-radius:12px;padding:24px;text-align:center}
.ok{color:#15803d}.no{color:#b91c1c}dl{text-align:left}dt{font-size:12px;color:#777}dd{margin:0 0 10px;font-weight:600}
img{max-height:70px}</style></head><body><div class="box">
<?php if($ok):?>
  <h2 class="ok">✔ KTA Sah</h2>
  <p>Kartu ini terdaftar di Front Mahasiswa Lombok Barat.</p>
  <dl>
   <dt>No. KTA</dt><dd><?=e($m['no_kta'])?></dd>
   <dt>Nama</dt><dd><?=e($m['nama'])?></dd>
   <dt>Jabatan</dt><dd><?=e($m['jabatan']?:KAT[$k])?></dd>
   <dt>Asal</dt><dd><?=e($m['asal']?:'Lombok Barat')?></dd>
   <dt>Tahun</dt><dd><?=e($m['tahun'])?></dd>
  </dl>
  <p>Disahkan oleh<br><b><?=e(KETUA)?></b>, Ketua Umum</p>
  <img src="assets/stempel.png" alt="Stempel"> <img src="assets/tanda-tangan.png" alt="Tanda tangan">
<?php else:?>
  <h2 class="no">✖ KTA Tidak Valid</h2>
  <p>Data tidak ditemukan atau QR telah diubah.</p>
<?php endif?>
</div></body></html>