<?php require 'config.php';need();
$msg='';$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){chk();
 $f=$_FILES['csv']['tmp_name']??'';
 if(!$f||!is_uploaded_file($f))$err='Pilih file CSV terlebih dahulu.';
 else{
  $h=fopen($f,'r');$first=fgets($h);rewind($h);
  $d=substr_count($first,';')>substr_count($first,',')?';':',';
  $ok=0;$skip=0;$no=0;
  $ins=$pdo->prepare('INSERT INTO anggota(no_kta,nama,kategori,jabatan,asal,tahun) VALUES(?,?,?,?,?,?)');
  $pdo->beginTransaction();
  while(($r=fgetcsv($h,0,$d))!==false){
   $no++;$r=array_map(fn($v)=>trim(preg_replace('/^\xEF\xBB\xBF/','',(string)$v)),$r);
   if($no===1&&strtolower($r[0])==='nama')continue;
   $n=$r[0]??'';$k=strtolower($r[1]??'');
   if($n===''||!isset(KAT[$k])){$skip++;continue;}
   $th=preg_match('/^\d{4}$/',$r[4]??'')?$r[4]:'';
   $ins->execute([nomor($pdo,$k,$th),$n,$k,$r[2]??'',$r[3]??'',$th]);$ok++;}
  $pdo->commit();fclose($h);
  $msg="$ok data berhasil diimpor, $skip baris dilewati (nama kosong atau kategori tidak dikenal).";}}
head('Impor register');?>
<form method="post" enctype="multipart/form-data" class="card narrow"><h2>Impor dari register</h2>
<?php if($msg):?><p class="ok"><?=e($msg)?></p><?php endif?><?php if($err):?><p class="err"><?=e($err)?></p><?php endif?>
<p class="sub">Simpan register dari Excel sebagai <b>CSV</b> dengan kolom berurutan: <b>nama, kategori, jabatan, asal, tahun</b>. Kategori diisi <i>pengurus</i>, <i>kader</i>, atau <i>alumni</i>. Nomor KTA dibuat otomatis. Foto ditambahkan lewat tombol Edit.</p>
<p><a href="contoh-register.csv" download>Unduh contoh format CSV</a></p>
<input type="hidden" name="t" value="<?=csrf()?>">
<label>File CSV<input type="file" name="csv" accept=".csv,text/csv" required></label>
<button class="btn">Impor</button></form>
<?php foot();
