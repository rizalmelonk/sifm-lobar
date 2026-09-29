<?php require 'config.php';need();
$msg='';$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){chk();
 $q=$pdo->prepare('SELECT * FROM users WHERE username=?');$q->execute([$_SESSION['u']]);$r=$q->fetch();
 if(!password_verify($_POST['lama'],$r['password']))$err='Password lama salah.';
 elseif(strlen($_POST['baru'])<8)$err='Password baru minimal 8 karakter.';
 else{$pdo->prepare('UPDATE users SET password=? WHERE id=?')->execute([password_hash($_POST['baru'],PASSWORD_DEFAULT),$r['id']]);$msg='Password berhasil diganti.';}}
head('Ganti password');?>
<form method="post" class="card narrow"><h2>Ganti password</h2>
<?php if($msg):?><p class="ok"><?=e($msg)?></p><?php endif?><?php if($err):?><p class="err"><?=e($err)?></p><?php endif?>
<input type="hidden" name="t" value="<?=csrf()?>">
<label>Password lama<input type="password" name="lama" required></label>
<label>Password baru (min. 8 karakter)<input type="password" name="baru" required></label>
<button class="btn">Simpan</button></form>
<?php foot();
