<?php require __DIR__.'/../app/bootstrap.php';need();
$msg='';$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){chk();
 $f=$_FILES['csv']['tmp_name']??'';
 if(!$f||!is_uploaded_file($f))$err='Pilih file CSV terlebih dahulu.';
 else{
  $h=fopen($f,'r');$first=fgets($h);rewind($h);
  $d=substr_count($first,';')>substr_count($first,',')?';':',';
  $ok=0;$skip=0;$no=0;
  $ins=[];foreach(KAT as $c=>$l)$ins[$c]=$pdo->prepare("INSERT INTO $c(no_kta,nama,jabatan,asal,tahun) VALUES(?,?,?,?,?)");
  $pdo->beginTransaction();
  while(($r=fgetcsv($h,0,$d))!==false){
   $no++;$r=array_map(fn($v)=>trim(preg_replace('/^\xEF\xBB\xBF/','',(string)$v)),$r);
   if($no===1&&strtolower($r[0])==='nama')continue;
   $n=$r[0]??'';$k=strtolower($r[1]??'');
   if($n===''||!isset(KAT[$k])){$skip++;continue;}
   $th=preg_match('/^\d{4}$/',$r[4]??'')?$r[4]:'';
   $ins[$k]->execute([nomor($pdo,$k,$th),$n,$r[2]??'',$r[3]??'',$th]);$ok++;}
  $pdo->commit();fclose($h);
  $msg="$ok data berhasil diimpor. $skip baris dilewati (nama kosong atau kategori tidak dikenal).";}}
head('Impor register','import');
judul('Impor Register','Masukkan banyak anggota sekaligus dari file Excel/CSV.');?>
<?php if($msg):?><p class="ok"><?=e($msg)?></p><?php endif?><?php if($err):?><p class="err"><?=e($err)?></p><?php endif?>
<div class="two">
 <div class="card"><h2>1. Siapkan file</h2>
  <p class="sub" style="margin:0">Simpan register dari Excel sebagai <b>CSV</b> dengan kolom berurutan seperti tabel berikut. Baris judul boleh ada.</p>
  <table class="tbl"><tr><th>Kolom</th><th>Isi</th><th>Contoh</th></tr>
   <tr><td>nama</td><td>Nama lengkap (wajib)</td><td>Ahmad Rizal</td></tr>
   <tr><td>kategori</td><td>pengurus, kader, atau alumni (wajib)</td><td>pengurus</td></tr>
   <tr><td>jabatan</td><td>Jabatan atau peran</td><td>Ketua Umum</td></tr>
   <tr><td>asal</td><td>Kecamatan / desa</td><td>Gerung</td></tr>
   <tr><td>tahun</td><td>4 angka</td><td>2025</td></tr></table>
  <p class="sub" style="margin:0">Nomor KTA dibuat otomatis. Foto ditambahkan kemudian lewat tombol Edit.</p>
  <a class="btn ghost" href="assets/contoh-register.csv" download>⬇ Unduh contoh format CSV</a></div>
 <form method="post" enctype="multipart/form-data" class="card"><h2>2. Unggah file</h2>
  <input type="hidden" name="t" value="<?=csrf()?>">
  <label class="drop">Pilih file CSV<input type="file" name="csv" accept=".csv,text/csv" required><span class="sub">Pemisah koma (,) atau titik koma (;) dikenali otomatis.</span></label>
  <button class="btn">Impor sekarang</button></form>
</div>
<?php foot();
