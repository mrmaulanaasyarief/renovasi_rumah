<html>
<head>
	<title>Print Kuitansi</title>
	<style type="text/css">
			.lead {
				font-family: "Verdana";
				font-weight: bold;
			}
			.value {
				font-family: "Verdana";
			}
			.value-big {
				font-family: "Verdana";
				font-weight: bold;
				font-size: large;
			}
			.td {
				valign : "top";
			}

			/* @page { size: with x height */
			@page { size: 20cm 10cm; margin: 0px; }
			/*
			@page {
				size: A4;
				margin : 0px;
			}
			*/
	/*		@media print {
			  html, body {
			  	width: 210mm;
			  }
			}*/
			/*body { border: 2px solid #000000;  }*/
	</style>
</head>
<body>
    <?php
        foreach($kuitansi as $row):
            $no_kuitansi = $row->no_kuitansi;
            $jenis_pemesanan = $row->jenis_pemesanan;
            $besar_bayar = $row->besar_bayar;
            $tgl_bayar = $row->tgl_bayar;
        endforeach;
    ?>

	<br>
	<table border="1px">
		<tr>
			<td width="80px"><img src="<?= base_url('images/logo-removebg-preview.png') ?>" width="80px" /></td>
			<td>
				<table cellpadding="4">
					<tr>
						<td width="200px"><div class="lead">No kwitansi:</td>
						<td><div class="value"><?=$no_kuitansi?></div></td>
					</tr>
					<tr>
						<td><div class="lead">Telah terima dari:</div></td>
						<td><div class="value"><?=$nama_customer?></div></td>
					</tr>
					<tr>
						<td><div class="lead">Untuk Pembayaran:</div></td>
						<td><div class="value">Pesanan <?=$jenis_pemesanan?></div></td>
					</tr>
					<tr>
						<td><div class="lead">Tanggal:</div></td>
						<td><div class="value"><?=$tgl_bayar?></div></td>
					</tr>
					<tr>
						<td><div class="lead">Rupiah:</div></td>
						<td><div class="value-big"><?=rupiah($besar_bayar)?></div></td>
					</tr>
					<tr>
						<td><div class="lead">Uang Sejumlah:</div></td>
						<td><div class="value"><?=terbilang($besar_bayar)?></div></td>
					</tr>
					<tr>
						<td><div class="lead">Sisa Pembayaran:</div></td>
						<td><div class="value"><?=terbilang($sisa_bayar)?></div></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td><div class="lead">Kasir:</div></td>
						<td><?=$_SESSION['nama']?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>____________________________________________________</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>