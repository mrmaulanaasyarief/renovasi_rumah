<p>
<hr>
Pilih Tampilan Data Kos-Kosan: <br>
1. <a href="<?= base_url('modul1/index/1')?>">Tabel</a> <br>
2. <a href="<?= base_url('modul1/index/2')?>">List</a> <br>
<?php
//cara menangkap variabel $data['pilihan'] yaitu $pilihan
if(isset($pilihan)){
echo "<h1> Data Kosan </h1>";
if($pilihan==1){
?>
<table border=1>
<tr>
<td>Id Kos</td>
<td>Nama Kos</td>
<td>Jenis Kos</td>
<td>Alamat</td>
</tr>
<?php
foreach($koskosan as $row):
?>
<tr>
<td><?= $row['id_kos'];?></td>
<td><?= $row['nama'];?></td>
<td><?= $row['jenis_kos'];?></td>
<td><?= $row['alamat'];?></td>
</tr>
<?php
endforeach;
?>
</table>
<?php
}else{
echo "<ul>";
foreach($koskosan as $row):
echo "<li>".$row['id_kos']." - ".$row['nama']." - ".$row['jenis_kos']." - ".$row['alamat']."</li>";
endforeach;
echo "</ul>";
}
}
?><hr>
<p>