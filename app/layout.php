<?php
function mi($href,$ikon,$label,$on,$badge=0,$blank=false){
 return '<a class="mi'.($on?' on':'').'" href="'.$href.'"'.($blank?' target="_blank"':'').'><span>'.$ikon.'</span>'.e($label).($badge?'<em>'.(int)$badge.'</em>':'').'</a>';}
// Halaman admin (sidebar). $m = dashboard|konfirmasi|pengurus|kader|alumni|import|password
function head($t,$m=''){global $cfg,$pdo;
 $n=0;foreach(KAT as $c=>$l)$n+=(int)$pdo->query("SELECT COUNT(*) FROM $c WHERE status='menunggu'")->fetchColumn();
 echo '<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.e($t).' – '.e($cfg['nama']).'</title><link rel="icon" href="assets/logo.png"><link rel="stylesheet" href="assets/style.css"></head><body><div class="app">';
 echo '<aside class="side noprint"><div class="brand"><img src="assets/logo.png" alt="">SI FM LOBAR</div>';
 echo '<div class="usr"><img src="assets/logo.png" alt=""><div><b>'.e($cfg['org']).'</b><br><span class="pill">Admin</span></div></div><nav>';
 echo mi('dashboard.php','📊','Dashboard',$m==='dashboard');
 echo mi('konfirmasi.php','✅','Konfirmasi',$m==='konfirmasi',$n);
 echo '<div class="gt">DATA ORGANISASI</div>';
 foreach(KAT as $c=>$l)echo mi('data.php?k='.$c,['pengurus'=>'🧑‍💼','kader'=>'🌱','alumni'=>'🎓'][$c],'Data '.$l,$m===$c);
 echo '<div class="gt">PENGELOLAAN</div>';
 echo mi('import.php','📥','Impor Register',$m==='import');
 echo mi('daftar.php','📝','Formulir Pendaftaran',false,0,true);
 echo mi('ganti-password.php','🔑','Ganti Password',$m==='password');
 echo mi('login.php?out=1','🚪','Keluar',false);
 echo '</nav></aside><div class="scrim" id="scrim"></div><div class="main"><div class="topbar noprint"><button id="tg" aria-label="Menu">☰</button><span class="sp"></span><span>'.date('d/m/Y H:i').'</span></div><main class="content">';
 if(!empty($_SESSION['f'])){echo '<p class="ok flash">'.e($_SESSION['f']).'</p>';unset($_SESSION['f']);}}
function foot(){echo '</main></div></div><script>var b=document.body;document.getElementById("tg").onclick=function(){b.classList.toggle(innerWidth<=820?"open":"hide")};document.getElementById("scrim").onclick=function(){b.classList.remove("open")}</script></body></html>';}
// Halaman tanpa menu (login & pendaftaran)
function head_public($t){$c=$GLOBALS['cfg'];
 echo '<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.e($t).' – '.e($c['nama']).'</title><link rel="icon" href="assets/logo.png"><link rel="stylesheet" href="assets/style.css"></head><body><div class="pubwrap"><div class="pub"><a class="back" href="index.php">← Beranda</a><div class="pubtop"><img src="assets/logo.png" alt="Logo"><b>'.e($c['nama']).'</b><span class="sub">'.e($c['org']).'</span></div>';}
function foot_public(){echo '</div></div></body></html>';}
