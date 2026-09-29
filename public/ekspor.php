<?php require __DIR__.'/../app/bootstrap.php';need();
$k=$_GET['k']??'';if(!isset(KAT[$k]))exit('Kategori tidak valid');
header('Content-Type: text/csv; charset=utf-8');header('Content-Disposition: attachment; filename="'.$k.'-fm-lobar.csv"');
echo "\xEF\xBB\xBF";$o=fopen('php://output','w');
fputcsv($o,['nama','kategori','jabatan','asal','tahun','no_hp','no_kta']);
foreach($pdo->query("SELECT * FROM $k WHERE status='aktif' ORDER BY tahun DESC,nama") as $r)fputcsv($o,[$r['nama'],$k,$r['jabatan'],$r['asal'],$r['tahun'],$r['no_hp'],$r['no_kta']]);
