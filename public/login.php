<?php require __DIR__.'/../app/bootstrap.php';
if(isset($_GET['out'])){session_destroy();header('Location: login.php');exit;}
if(!$pdo->query('SELECT COUNT(*) c FROM admin')->fetch()['c'])
 $pdo->prepare('INSERT INTO admin(username,password) VALUES(?,?)')->execute(['admin',password_hash('admin123',PASSWORD_DEFAULT)]);
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){chk();
 $q=$pdo->prepare('SELECT * FROM admin WHERE username=?');$q->execute([trim($_POST['u'])]);$r=$q->fetch();
 if($r&&password_verify($_POST['p'],$r['password'])){session_regenerate_id(true);$_SESSION['u']=$r['username'];header('Location: dashboard.php');exit;}
 $err='Username atau password salah.';}
head_public('Masuk');?>
<form method="post" class="card"><h2>Masuk admin</h2><p class="sub" style="margin:0">Khusus pengelola sistem.</p>
<?php if($err):?><p class="err"><?=e($err)?></p><?php endif?>
<input type="hidden" name="t" value="<?=csrf()?>">
<label>Username<input name="u" required autofocus></label>
<label>Password<input type="password" name="p" required></label>
<button class="btn">Masuk</button>
<p class="sub" style="text-align:center;margin:0">Belum terdaftar? <a href="daftar.php">Daftar sebagai pengurus, kader, atau alumni</a></p></form>
<?php foot_public();
