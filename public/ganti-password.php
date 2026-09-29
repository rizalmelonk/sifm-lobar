<?php require __DIR__.'/../app/bootstrap.php';need();
$msg='';$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){chk();
 $q=$pdo->prepare('SELECT * FROM admin WHERE username=?');$q->execute([$_SESSION['u']]);$r=$q->fetch();
 if(!password_verify($_POST['lama'],$r['password']))$err='Password lama salah.';
 elseif(strlen($_POST['baru'])<8)$err='Password baru minimal 8 karakter.';
 elseif($_POST['baru']!==($_POST['ulang']??''))$err='Konfirmasi password tidak sama.';
 else{$pdo->prepare('UPDATE admin SET password=? WHERE id=?')->execute([password_hash($_POST['baru'],PASSWORD_DEFAULT),$r['id']]);$msg='Password berhasil diganti.';}}
head('Ganti password','password');
judul('Ganti Password','Akun aktif: <b>'.e($_SESSION['u']).'</b>');?>
<div class="two">
 <form method="post" class="card"><h2>Password baru</h2>
  <?php if($msg):?><p class="ok"><?=e($msg)?></p><?php endif?><?php if($err):?><p class="err"><?=e($err)?></p><?php endif?>
  <input type="hidden" name="t" value="<?=csrf()?>">
  <label>Password lama<input type="password" name="lama" required></label>
  <label>Password baru (min. 8 karakter)<input type="password" name="baru" required></label>
  <label>Ulangi password baru<input type="password" name="ulang" required></label>
  <button class="btn">Simpan password</button></form>
 <div class="card"><h2>Tips keamanan</h2>
  <p class="sub" style="margin:0">Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol. Jangan memakai tanggal lahir atau nama organisasi. Ganti password segera setelah pertama kali masuk, dan jangan bagikan ke orang lain.</p></div>
</div>
<?php foot();
