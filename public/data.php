<?php require __DIR__.'/../app/bootstrap.php';need();
$k=$_GET['k']??'pengurus';if(!isset(KAT[$k]))$k='pengurus';
$q=trim($_GET['q']??'');$t=$_GET['t']??'';
$w="status='aktif'";$p=[];
if($q!==''){$w.=' AND (nama LIKE ? OR asal LIKE ? OR jabatan LIKE ? OR no_kta LIKE ?)';array_push($p,"%$q%","%$q%","%$q%","%$q%");}
if($t!==''){$w.=' AND tahun=?';$p[]=$t;}
$per=20;$pg=max(1,(int)($_GET['p']??1));
$c=$pdo->prepare("SELECT COUNT(*) FROM $k WHERE $w");$c->execute($p);$tot=(int)$c->fetchColumn();
$hal=max(1,(int)ceil($tot/$per));$pg=min($pg,$hal);$off=($pg-1)*$per;
$s=$pdo->prepare("SELECT * FROM $k WHERE $w ORDER BY tahun DESC,nama LIMIT $per OFFSET $off");$s->execute($p);$rows=$s->fetchAll();
$ys=$pdo->query("SELECT DISTINCT tahun FROM $k WHERE status='aktif' AND tahun<>'' ORDER BY tahun DESC")->fetchAll(PDO::FETCH_COLUMN);
$ikon=['pengurus'=>'🧑‍💼','kader'=>'🌱','alumni'=>'🎓'];
$url=fn($n)=>'data.php?'.http_build_query(['k'=>$k,'q'=>$q,'t'=>$t,'p'=>$n]);
head('Data '.KAT[$k],$k);
judul('Data '.KAT[$k],$tot.' anggota aktif'.($q!==''||$t!==''?' sesuai filter':''),'<a class="btn" href="anggota.php?k='.e($k).'">+ Tambah data</a>');?>
<form class="tools" method="get"><input type="hidden" name="k" value="<?=e($k)?>">
 <input type="search" name="q" value="<?=e($q)?>" placeholder="Cari nama, asal, jabatan, atau no. KTA">
 <select name="t" onchange="this.form.submit()"><option value="">Semua tahun</option><?php foreach($ys as $v):?><option<?=$v===$t?' selected':''?>><?=e($v)?></option><?php endforeach?></select>
 <button class="btn ghost">Cari</button><?php if($q!==''||$t!==''):?><a class="btn ghost" href="data.php?k=<?=e($k)?>">Reset</a><?php endif?>
 <a class="btn ghost push" href="ekspor.php?k=<?=e($k)?>">Ekspor CSV</a></form>
<div class="list">
 <?php if($rows):?><div class="row thead"><div></div><div>Nama</div><div class="c3">Asal / Kontak</div><div class="c3">Tahun</div><div class="acts">Aksi</div></div><?php endif?>
<?php foreach($rows as $m):?>
 <div class="row">
  <?=avatar($m['foto'],$m['nama'])?>
  <div><div class="nm"><?=e($m['nama'])?></div><div class="sub"><?=e($m['jabatan']?:KAT[$k])?> · <?=e($m['no_kta'])?></div></div>
  <div class="sub c3"><?=e($m['asal']?:'-')?><?=$m['no_hp']?'<br>'.e($m['no_hp']):''?></div><div class="c3"><span class="tag"><?=e($m['tahun']?:'-')?></span></div>
  <div class="acts"><a class="btn sm" href="kta.php?k=<?=$k?>&id=<?=$m['id']?>">KTA</a><a class="btn sm ghost" href="anggota.php?k=<?=$k?>&id=<?=$m['id']?>">Edit</a>
   <form method="post" action="anggota.php" onsubmit="return confirm('Hapus data ini?')"><input type="hidden" name="t" value="<?=csrf()?>"><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="k" value="<?=e($k)?>"><input type="hidden" name="id" value="<?=$m['id']?>"><button class="btn sm ghost del">Hapus</button></form></div>
 </div>
<?php endforeach; if(!$rows):?><div class="empty"><span><?=$ikon[$k]?></span><b>Belum ada data <?=strtolower(KAT[$k])?></b><br>Klik “Tambah data”, impor dari register, atau setujui pendaftaran baru.</div><?php endif?>
</div>
<?php if($hal>1):?><div class="pager"><span class="sub">Menampilkan <?=$off+1?>–<?=min($off+$per,$tot)?> dari <?=$tot?></span><div>
 <?php if($pg>1):?><a href="<?=$url($pg-1)?>">‹ Sebelumnya</a><?php endif?>
 <?php for($i=max(1,$pg-2);$i<=min($hal,$pg+2);$i++):?><a href="<?=$url($i)?>"<?=$i===$pg?' class="on"':''?>><?=$i?></a><?php endfor?>
 <?php if($pg<$hal):?><a href="<?=$url($pg+1)?>">Berikutnya ›</a><?php endif?></div></div><?php endif?>
<?php foot();
