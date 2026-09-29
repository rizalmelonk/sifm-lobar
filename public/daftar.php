<?php require __DIR__.'/../app/bootstrap.php';
$err='';$ok=false;$v=['nama'=>'','kategori'=>'kader','jabatan'=>'','asal'=>'','no_hp'=>'','tahun'=>''];
if($_SERVER['REQUEST_METHOD']==='POST'){chk();
 foreach($v as $k=>$_)$v[$k]=trim($_POST[$k]??'');
 if(!empty($_POST['website']))exit; // jebakan spam
 if($v['nama']===''||!isset(KAT[$v['kategori']]))$err='Nama dan kategori wajib diisi.';
 elseif($v['tahun']!==''&&!preg_match('/^\d{4}$/',$v['tahun']))$err='Tahun harus 4 angka, contoh 2025.';
 elseif($v['no_hp']!==''&&!preg_match('/^[0-9+ \-]{8,20}$/',$v['no_hp']))$err='Nomor HP tidak valid.';
 else{$foto=simpan_foto($_FILES['foto']??[],$err,'');}
 if(!$err){$k=$v['kategori'];
  $pdo->prepare("INSERT INTO $k(nama,jabatan,asal,no_hp,tahun,foto,status) VALUES(?,?,?,?,?,?,'menunggu')")->execute([$v['nama'],$v['jabatan'],$v['asal'],$v['no_hp'],$v['tahun'],$foto]);
  $ok=true;}}
head_public('Pendaftaran');
if($ok):?><div class="card done"><div class="tick">✓</div><h2>Pendaftaran terkirim</h2><p class="sub" style="margin:0">Data Anda sudah masuk dan menunggu konfirmasi admin. Nomor KTA akan diterbitkan setelah disetujui.</p><a class="btn" href="index.php">Kembali ke beranda</a><a class="sub" href="daftar.php">Daftarkan orang lain</a></div>
<?php else:?>
<form method="post" enctype="multipart/form-data" class="card"><h2>Pendaftaran anggota</h2><p class="sub" style="margin:0">Isi data dengan benar. Admin akan memeriksa sebelum menerbitkan KTA.</p>
<?php if($err):?><p class="err"><?=e($err)?></p><?php endif?>
<input type="hidden" name="t" value="<?=csrf()?>"><input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
<label>Nama lengkap<input name="nama" value="<?=e($v['nama'])?>" required></label>
<label>Daftar sebagai<select name="kategori"><?php foreach(KAT as $c=>$l):?><option value="<?=$c?>"<?=$c===$v['kategori']?' selected':''?>><?=$l?></option><?php endforeach?></select></label>
<label>Jabatan / peran<input name="jabatan" value="<?=e($v['jabatan'])?>"></label>
<label>Asal (kecamatan / desa)<input name="asal" value="<?=e($v['asal'])?>"></label>
<label>Nomor HP / WhatsApp<input name="no_hp" value="<?=e($v['no_hp'])?>" inputmode="tel"></label>
<label>Tahun (periode atau angkatan)<input name="tahun" value="<?=e($v['tahun'])?>" maxlength="4" placeholder="2025"></label>
<label>Foto (JPG/PNG/WEBP, maks 2 MB)<input type="file" name="foto" accept="image/*"></label>
<button class="btn">Kirim pendaftaran</button>
<p class="sub" style="text-align:center;margin:0"><a href="login.php">Masuk admin</a></p></form>
<?php endif; foot_public();
