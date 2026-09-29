<?php require __DIR__.'/../app/bootstrap.php';need();
$ak=[];$tg=0;
foreach(KAT as $c=>$l){$ak[$c]=(int)$pdo->query("SELECT COUNT(*) FROM $c WHERE status='aktif'")->fetchColumn();$tg+=(int)$pdo->query("SELECT COUNT(*) FROM $c WHERE status='menunggu'")->fetchColumn();}
$u="SELECT tahun,asal FROM pengurus WHERE status='aktif' UNION ALL SELECT tahun,asal FROM kader WHERE status='aktif' UNION ALL SELECT tahun,asal FROM alumni WHERE status='aktif'";
$th=$pdo->query("SELECT tahun k,COUNT(*) n FROM ($u) x WHERE tahun IS NOT NULL AND tahun<>'' GROUP BY tahun ORDER BY tahun DESC LIMIT 8")->fetchAll(PDO::FETCH_KEY_PAIR);
$as=$pdo->query("SELECT asal k,COUNT(*) n FROM ($u) x WHERE asal IS NOT NULL AND asal<>'' GROUP BY asal ORDER BY n DESC LIMIT 8")->fetchAll(PDO::FETCH_KEY_PAIR);
$kt=[];foreach(KAT as $c=>$l)$kt[$l]=$ak[$c];
function terbaru($pdo,$st){$q=[];foreach(KAT as $t=>$l)$q[]="SELECT '$t' k,id,nama,jabatan,foto,dibuat FROM $t WHERE status='$st'";
 return $pdo->query(implode(' UNION ALL ',$q).' ORDER BY dibuat DESC LIMIT 5')->fetchAll();}
$tunggu=terbaru($pdo,'menunggu');$baru=terbaru($pdo,'aktif');
head('Dashboard','dashboard');?>
<div class="banner"><div><h1>Selamat datang, <?=e($_SESSION['u'])?></h1><p><?=tgl()?> · <?=$tg?$tg.' pendaftaran menunggu konfirmasi':'Tidak ada pendaftaran yang menunggu'?></p></div>
 <div class="pact"><a class="btn gold" href="anggota.php">Tambah anggota</a><a class="btn line" href="konfirmasi.php">Konfirmasi<?=$tg?' ('.$tg.')':''?></a><a class="btn line" href="import.php">Impor register</a></div></div>
<div class="cards">
 <div class="sc"><div><b><?=array_sum($ak)?></b>Anggota Aktif</div><a href="data.php?k=pengurus">Lihat data ➜</a></div>
 <?php foreach(KAT as $c=>$l):?><div class="sc"><div><b><?=$ak[$c]?></b><?=$l?></div><a href="data.php?k=<?=$c?>">Lihat data ➜</a></div><?php endforeach?>
 <div class="sc warn"><div><b><?=$tg?></b>Menunggu Konfirmasi</div><a href="konfirmasi.php">Konfirmasi ➜</a></div>
</div>
<div class="charts">
 <div class="cc"><h3>Sebaran Kategori</h3><div class="bd"><?=donut($kt)?></div></div>
 <div class="cc"><h3>Sebaran Tahun / Angkatan</h3><div class="bd"><?=donut($th)?></div></div>
 <div class="cc"><h3>Sebaran Asal</h3><div class="bd"><?=donut($as)?></div></div>
</div>
<div class="panels">
<?php foreach([['Pendaftaran menunggu',$tunggu,'konfirmasi.php','Buka konfirmasi'],['Anggota terbaru',$baru,'data.php?k=pengurus','Lihat semua']] as [$jd,$rs,$ln,$lb]):?>
 <div class="cc"><h3><?=$jd?><a href="<?=$ln?>"><?=$lb?></a></h3>
  <?php foreach($rs as $r):?><div class="mini"><?=avatar($r['foto'],$r['nama'])?><div><div class="nm"><?=e($r['nama'])?></div><div class="sub"><?=KAT[$r['k']]?><?=$r['jabatan']?' · '.e($r['jabatan']):''?></div></div><span class="sub"><?=e(substr($r['dibuat'],0,10))?></span></div>
  <?php endforeach; if(!$rs):?><div class="empty"><span>📭</span>Belum ada data.</div><?php endif?></div>
<?php endforeach?>
</div>
<?php foot();
