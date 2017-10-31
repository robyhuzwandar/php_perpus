<?php include '../inc/header.php'; ?>
<?php include '../inc/sidebar.php'; ?>
<?php
	if (!isset($_GET['kId']) || $_GET['kId'] == NULL) {
		echo "<script> window.location = '../view/platformlist.php';</script>";
	}else{
		$id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['kId']);
	}
	if ($_SERVER['REQUEST_METHOD'] == 'POST') { //Menghasilkan metode yang dipakai untuk mengakses suatu halaman,EX:POST
		$denda = $_POST['denda'];
		$jm_hari = $_POST['jm_hari'];
		$pfUpdate = $t->ketentuanUpdate($denda, $jm_hari, $id);
	}
?>

<section class="content">
<div class="panel panel-default">	
	<div class="panel-heading">
		Tambah Platform
	</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					<?php 
					if (isset($pfUpdate)) {
						echo $pfUpdate;
					}
					?>
					<?php
				      $ket = $t->getKet();
				      if ($ket) {
				        while ($result = $ket->fetch_assoc()) {
				    ?>
					<form action="" method="post">
						<div class="form-group">
							<label>Denda</label>
							<input type="text" value="<?php echo $result['denda']; ?>" class="form-control" name="denda">
						</div>
						<div class="form-group">
							<label>Hari</label>
							<input type="number" value="<?php echo $result['jm_hari']; ?>" class="form-control" name="jm_hari">
						</div>
						<input type="submit" class="btn btn-primary" name="submit" value="Simpan">
					</form>
					<?php } } ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php include '../inc/footer.php'; ?>