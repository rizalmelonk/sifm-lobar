<?php
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function need(){if(empty($_SESSION['u'])){header('Location: login.php');exit;}}
function csrf(){return $_SESSION['t']??($_SESSION['t']=bin2hex(random_bytes(16)));}
function chk(){if(!hash_equals($_SESSION['t']??'',$_POST['t']??'')){http_response_code(400);exit('Token tidak valid. Muat ulang halaman.');}}
// Nomor KTA berikutnya untuk tabel pengurus / kader / alumni
function nomor($pdo,$k,$y){
 if(!isset(KAT[$k]))throw new Exception('Kategori tidak valid');
 $c=['pengurus'=>'P','kader'=>'K','alumni'=>'A'][$k];
 $q=$pdo->query("SELECT MAX(CAST(SUBSTRING_INDEX(no_kta,'/',-1) AS UNSIGNED)) m FROM $k");
 return sprintf('SIFM-LBR/%s/%s/%03d',$c,$y?:'0000',($q->fetch()['m']??0)+1);}
// Simpan foto unggahan (JPG/PNG/WEBP, maks 2 MB). Mengembalikan nama file, atau $lama bila tidak ada foto baru.
function simpan_foto($f,&$err,$lama=''){
 if(empty($f['tmp_name'])||$f['error']!==0)return $lama;
 $i=@getimagesize($f['tmp_name']);
 $ext=[IMAGETYPE_JPEG=>'jpg',IMAGETYPE_PNG=>'png',IMAGETYPE_WEBP=>'webp'][$i?$i[2]:0]??null;
 if(!$ext||$f['size']>2097152){$err='Foto harus JPG, PNG, atau WEBP, maksimal 2 MB.';return $lama;}
 $baru=bin2hex(random_bytes(8)).'.'.$ext;
 move_uploaded_file($f['tmp_name'],__DIR__.'/../public/uploads/'.$baru);
 if($lama)@unlink(__DIR__.'/../public/uploads/'.basename($lama));
 return $baru;}
function hapus_foto($n){if($n)@unlink(__DIR__.'/../public/uploads/'.basename($n));}
// Diagram donat tanpa JavaScript. $data = ['label'=>jumlah]
function donut($data){
 $tot=array_sum($data);if(!$tot)return '<p class="sub">Belum ada data.</p>';
 $col=['#2f7fc1','#f2c500','#d22b2b','#3aa77a','#8e6bd6','#e58a2f','#5bb8c9','#9aa5ae'];
 $i=0;$a=0;$st=[];$lg='';
 foreach($data as $l=>$n){$c=$col[$i++%8];$b=$a+$n/$tot*100;$st[]="$c {$a}% {$b}%";$a=$b;$lg.='<li><i style="background:'.$c.'"></i>'.e($l).' <b>'.$n.'</b></li>';}
 return '<div class="donut" style="background:conic-gradient('.implode(',',$st).')"><span>'.$tot.'</span></div><ul class="leg">'.$lg.'</ul>';}
function tgl($t=null){$t=$t??time();$b=['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];$h=['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
 return $h[date('w',$t)].', '.date('j',$t).' '.$b[(int)date('n',$t)].' '.date('Y',$t);}
function flash($m){$_SESSION['f']=$m;}
// Kepala halaman: judul, sub-judul, dan tombol aksi (HTML)
function judul($t,$s='',$aksi=''){
 echo '<div class="phead"><div><h1 class="ptitle">'.e($t).'</h1>'.($s?'<p class="psub">'.$s.'</p>':'').'</div>'.($aksi?'<div class="pact">'.$aksi.'</div>':'').'</div>';}
// Kotak avatar: foto atau huruf pertama
function avatar($foto,$nama,$cls='av'){
 return '<div class="'.$cls.'">'.($foto?'<img src="uploads/'.e($foto).'" alt="">':e(mb_strtoupper(mb_substr($nama,0,1)))).'</div>';}
