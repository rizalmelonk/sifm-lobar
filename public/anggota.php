<?php require __DIR__.'/../app/bootstrap.php';need();
$id=(int)($_GET['id']??0);$err='';
$k=$_POST['kategori']??$_GET['k']??$_POST['k']??'pengurus';if(!isset(KAT[$k]))$k='pengurus';
$m=['nama'=>'','jabatan'=>'','asal'=>'','no_hp'=>'','tahun'=>'','foto'=>''];
if($id){$s=$pdo->prepare("SELECT * FROM $k WHERE id=?");$s->execute([$id]);$m=$s->fetch()?:exit('Data tidak ditemukan');}
if($_SERVER['REQUEST_METHOD']==='POST'){chk();
 if(($_POST['aksi']??'')==='hapus'){
  $i=(int)$_POST['id'];$f=$pdo->prepare("SELECT foto FROM $k WHERE id=?");$f->execute([$i]);$f=$f->fetch();
  if($f)hapus_foto($f['foto']);
  $pdo->prepare("DELETE FROM $k WHERE id=?")->execute([$i]);flash('Data '.strtolower(KAT[$k]).' dihapus.');header('Location: data.php?k='.$k);exit;}
 $n=trim($_POST['nama']);$j=trim($_POST['jabatan']);$a=trim($_POST['asal']);$hp=trim($_POST['no_hp']);$th=trim($_POST['tahun']);
 if($n==='')$err='Nama wajib diisi.';
 elseif($th!==''&&!preg_match('/^\d{4}$/',$th))$err='Tahun harus 4 angka, contoh 2025.';
 $foto=$m['foto'];
 if(!$err)$foto=simpan_foto($_FILES['foto']??[],$err,$m['foto']);
 if(!$err){
  if($id)$pdo->prepare("UPDATE $k SET nama=?,jabatan=?,asal=?,no_hp=?,tahun=?,foto=? WHERE id=?")->execute([$n,$j,$a,$hp,$th,$foto,$id]);
  else $pdo->prepare("INSERT INTO $k(no_kta,nama,jabatan,asal,no_hp,tahun,foto,status) VALUES(?,?,?,?,?,?,?,'aktif')")->execute([nomor($pdo,$k,$th),$n,$j,$a,$hp,$th,$foto]);
  flash($n.($id?' berhasil diperbarui.':' berhasil ditambahkan dengan nomor KTA.'));header('Location: data.php?k='.$k);exit;}
 $m=['nama'=>$n,'jabatan'=>$j,'asal'=>$a,'no_hp'=>$hp,'tahun'=>$th,'foto'=>$foto];}
head($id?'Edit data':'Tambah data',$k);
judul(($id?'Edit ':'Tambah ').'data '.strtolower(KAT[$k]),$id?'Perbarui data anggota. Nomor KTA tidak berubah.':'Lengkapi data anggota. Nomor KTA terbit otomatis.');?>
<form method="post" enctype="multipart/form-data" class="card form2">
<?php if($err):?><p class="err full"><?=e($err)?></p><?php endif?>
<input type="hidden" name="t" value="<?=csrf()?>">
<div class="photo full"><div class="ph" id="ph"><?php if($m['foto']):?><img src="uploads/<?=e($m['foto'])?>" alt="Foto saat ini"><?php else:?>📷<?php endif?></div>
 <label>Foto anggota<input type="file" name="foto" id="foto" accept="image/*"><span class="sub">JPG, PNG, atau WEBP, maksimal 2 MB. Disarankan foto berdiri tegak (rasio 3:4).</span></label></div>
<label class="full">Nama lengkap<input name="nama" value="<?=e($m['nama'])?>" required></label>
<?php if($id):?><input type="hidden" name="k" value="<?=e($k)?>"><?php else:?>
<label>Kategori<select name="kategori"><?php foreach(KAT as $c=>$l):?><option value="<?=$c?>"<?=$c===$k?' selected':''?>><?=$l?></option><?php endforeach?></select></label><?php endif?>
<label>Jabatan / peran<input name="jabatan" value="<?=e($m['jabatan'])?>" placeholder="Contoh: Sekretaris"></label>
<label>Asal (kecamatan / desa)<input name="asal" value="<?=e($m['asal'])?>"></label>
<label>Nomor HP / WhatsApp<input name="no_hp" value="<?=e($m['no_hp'])?>" inputmode="tel"></label>
<label>Tahun (periode atau angkatan)<input name="tahun" value="<?=e($m['tahun'])?>" maxlength="4" placeholder="2025"></label>
<div class="acts full"><a class="btn ghost" href="data.php?k=<?=e($k)?>">Batal</a><button class="btn">Simpan data</button></div></form>
<script>document.getElementById("foto").onchange=function(){var f=this.files[0];if(!f)return;var r=new FileReader();r.onload=function(e){document.getElementById("ph").innerHTML='<img src="'+e.target.result+'" alt="">'};r.readAsDataURL(f)}</script>
<?php foot();
