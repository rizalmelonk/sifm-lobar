<?php require 'config.php';need();
$id=(int)($_GET['id']??0);$err='';
$m=['nama'=>'','kategori'=>$_GET['k']??'pengurus','jabatan'=>'','asal'=>'','tahun'=>'','foto'=>''];
if($id){$s=$pdo->prepare('SELECT * FROM anggota WHERE id=?');$s->execute([$id]);$m=$s->fetch()?:exit('Data tidak ditemukan');}
if($_SERVER['REQUEST_METHOD']==='POST'){chk();
 if(($_POST['aksi']??'')==='hapus'){
  $i=(int)$_POST['id'];$f=$pdo->prepare('SELECT foto FROM anggota WHERE id=?');$f->execute([$i]);$f=$f->fetch();
  if($f&&$f['foto'])@unlink(__DIR__.'/uploads/'.basename($f['foto']));
  $pdo->prepare('DELETE FROM anggota WHERE id=?')->execute([$i]);header('Location: index.php');exit;}
 $n=trim($_POST['nama']);$k=$_POST['kategori'];$j=trim($_POST['jabatan']);$a=trim($_POST['asal']);$th=trim($_POST['tahun']);
 if($n===''||!isset(KAT[$k]))$err='Nama dan kategori wajib diisi.';
 elseif($th!==''&&!preg_match('/^\d{4}$/',$th))$err='Tahun harus 4 angka, contoh 2025.';
 $foto=$m['foto'];
 if(!$err&&!empty($_FILES['foto']['tmp_name'])&&$_FILES['foto']['error']===0){
  $i=@getimagesize($_FILES['foto']['tmp_name']);
  $ext=[IMAGETYPE_JPEG=>'jpg',IMAGETYPE_PNG=>'png',IMAGETYPE_WEBP=>'webp'][$i?$i[2]:0]??null;
  if(!$ext||$_FILES['foto']['size']>2097152)$err='Foto harus JPG, PNG, atau WEBP, maksimal 2 MB.';
  else{$baru=bin2hex(random_bytes(8)).'.'.$ext;move_uploaded_file($_FILES['foto']['tmp_name'],__DIR__.'/uploads/'.$baru);
   if($foto)@unlink(__DIR__.'/uploads/'.basename($foto));$foto=$baru;}}
 if(!$err){
  if($id)$pdo->prepare('UPDATE anggota SET nama=?,kategori=?,jabatan=?,asal=?,tahun=?,foto=? WHERE id=?')->execute([$n,$k,$j,$a,$th,$foto,$id]);
  else $pdo->prepare('INSERT INTO anggota(no_kta,nama,kategori,jabatan,asal,tahun,foto) VALUES(?,?,?,?,?,?,?)')->execute([nomor($pdo,$k,$th),$n,$k,$j,$a,$th,$foto]);
  header('Location: index.php?k='.$k);exit;}
 $m=['nama'=>$n,'kategori'=>$k,'jabatan'=>$j,'asal'=>$a,'tahun'=>$th,'foto'=>$foto];}
head($id?'Edit data':'Tambah data');?>
<form method="post" enctype="multipart/form-data" class="card narrow"><h2><?=$id?'Edit data':'Tambah data'?></h2>
<?php if($err):?><p class="err"><?=e($err)?></p><?php endif?>
<input type="hidden" name="t" value="<?=csrf()?>">
<label>Nama lengkap<input name="nama" value="<?=e($m['nama'])?>" required></label>
<label>Kategori<select name="kategori"><?php foreach(KAT as $c=>$l):?><option value="<?=$c?>"<?=$c===$m['kategori']?' selected':''?>><?=$l?></option><?php endforeach?></select></label>
<label>Jabatan / peran<input name="jabatan" value="<?=e($m['jabatan'])?>" placeholder="Contoh: Sekretaris"></label>
<label>Asal (kecamatan / desa)<input name="asal" value="<?=e($m['asal'])?>"></label>
<label>Tahun (periode atau angkatan)<input name="tahun" value="<?=e($m['tahun'])?>" maxlength="4" placeholder="2025"></label>
<label>Foto anggota (JPG/PNG/WEBP, maks 2 MB)<input type="file" name="foto" accept="image/*"></label>
<?php if($m['foto']):?><img class="prev" src="uploads/<?=e($m['foto'])?>" alt="Foto saat ini"><?php endif?>
<div class="acts"><a class="btn ghost" href="index.php">Batal</a><button class="btn">Simpan</button></div></form>
<?php foot();
