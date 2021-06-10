<p>
<hr>
Pilih Tampilan Data Kos-Kosan: <br>
1. <a href="<?= base_url('modul1/index/1')?>">Tabel</a> <br>
2. <a href="<?= base_url('modul1/index/2')?>">List</a> <br>
<?php
//cara menangkap variabel $data['pilihan'] yaitu $pilihan
if(isset($pilihan)){
echo "<h1> Data Material </h1>";
if($pilihan==1){
?>
<table border=1>
<tr>
<td>Id Material</td>
<td>Nama Material</td>
<td>Jenis</td>
</tr>
<?php
foreach($alatdanbahan as $row):
?>
<tr>
<td><?= $row['id_material'];?></td>
<td><?= $row['nama_material'];?></td>
<td><?= $row['jenis'];?></td>
</tr>
<?php
endforeach;
?>
</table>
<?php
}else{
echo "<ul>";
foreach($alatdanbahan as $row):
echo "<li>".$row['id_material']." - ".$row['nama_material']." - ".$row['jenis']."</li>";
endforeach;
echo "</ul>";
}
}
?><hr>
<p>