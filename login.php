<?php require 'config.php';
if(isset($_GET['out'])){session_destroy();header('Location: login.php');exit;}
if(!$pdo->query('SELECT COUNT(*) c FROM users')->fetch()['c'])
 $pdo->prepare('INSERT INTO users(username,password) VALUES(?,?)')->execute(['admin',password_hash('admin123',PASSWORD_DEFAULT)]);
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){chk();
 $q=$pdo->prepare('SELECT * FROM users WHERE username=?');$q->execute([trim($_POST['u'])]);$r=$q->fetch();
 if($r&&password_verify($_POST['p'],$r['password'])){session_regenerate_id(true);$_SESSION['u']=$r['username'];header('Location: index.php');exit;}
 $err='Username atau password salah.';}
head('Masuk');?>
<form method="post" class="card narrow"><h2>Masuk</h2>
<?php if($err):?><p class="err"><?=e($err)?></p><?php endif?>
<input type="hidden" name="t" value="<?=csrf()?>">
<label>Username<input name="u" required autofocus></label>
<label>Password<input type="password" name="p" required></label>
<button class="btn">Masuk</button></form>
<?php foot();
