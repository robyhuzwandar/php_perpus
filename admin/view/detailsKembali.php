<?php include '../inc/header.php'; ?>
<?php include '../inc/sidebar.php'; ?>
<?php 
	if (!isset($_GET['kodepinjam']) || $_GET['kodepinjam'] == NULL) {
		echo "<script>window.location='../view/pinjamlist.php'</script>";
	}else{
		$kodepinjam = $_GET['kodepinjam']);
		$insert_kem = $t->addToKembali($kodepinjam);
	}
?>
<section class="content">
<div class="panel panel-default">
	<div class="panel-heading">Info Pengembalian</div>
	<div class="panel-body">
		<div class="row">
		<?php 
			$getDetails = $t->getKembaliByKodePinjam($kodepinjam);
			if ($getDetails) {
				while ($result = $getDetails->fetch_assoc()) {
		?>
			<div class="col col-md-3">
				<img src="">
			</div>
			<div class="col col-md-8">
				<table>
					<tr>
						<td width="97%">Kode Pinjam</td>
						<td>:</td>
						<td><?php echo $result['kodepinjam']; ?></td>
					</tr>
					<tr>
						<td>Nim</td>
						<td>:</td>
						<td><?php 
						$mId = $t->getPinjamByKodePinjam($result['kodepinjam'])->fetch_assoc();
						$val = $m->getMById($mId['member_id'])->fetch_assoc();
						echo $val['nim'];?></td>
					</tr>
					<tr>
						<td>Nama</td>
						<td>:</td>
						<td><?php 
						$mId = $t->getPinjamByKodePinjam($result['kodepinjam'])->fetch_assoc();
						$val = $m->getMById($mId['member_id'])->fetch_assoc();
						echo $val['nama'];?></td>
					</tr>
					<tr>
						<td>Tanggal Pinjam</td>
						<td>:</td>
						<td><?php 
						$val = $t->getPinjamByKodePinjam($result['kodepinjam'])->fetch_assoc();
						echo $val['tglpinjam']; ?></td>
					</tr>
					<tr>
						<td>Batas Pinjam</td>
						<td>:</td>
						<td><?php 
						$val = $t->getPinjamByKodePinjam($result['kodepinjam'])->fetch_assoc();
						echo $val['batasPinjam']; ?></td>
					</tr>
					<tr>
						<td>Tanggal Kembali</td>
						<td>:</td>
						<td><?php echo $result['tglKembali']; ?></td>
					</tr>
					<tr>
						<td>Jumlah Pinjam</td>
						<td>:</td>
						<td><?php echo $result['kodepinjam']; ?></td>
					</tr>
					<tr>
						<td>Telat</td>
						<td>:</td>
						<td><?php echo $result['telat']; ?></td>
					</tr>
					<tr>
						<td>Denda</td>
						<td>:</td>
						<td><?php echo $result['denda']; ?></td>
					</tr>
					<tr>
						<td>Staf</td>
						<td>:</td>
						<td><?php 
						$sId = $t->getPinjamByKodePinjam($result['kodepinjam'])->fetch_assoc();
						$val = $m->getStafById($sId['admin_id'])->fetch_assoc();
						echo $val['nim'];?></td>
						</td>
					</tr>
				</table>
			</div>
			<?php } } ?>
		</div>
	</div>
</div>
</section>
<?php include '../inc/footer.php'; ?>