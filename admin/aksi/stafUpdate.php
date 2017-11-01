<?php include '../inc/header.php'; ?>
<?php include '../inc/sidebar.php'; ?>
<?php
	if (!isset($_GET['sId']) || $_GET['sId'] == NULL) {
		echo "<script> window.location = '../view/staflist.php';</script>";
	}else{
		$id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['sId']);
	}
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
		$updateStaf	 = $s->updateStaf($_POST, $_FILES, $id);
	}
?>
<section class="content">
<div class="panel panel-default">
	<div class="panel-heading">
		Tambah Buku
	</div>
	<div class="panel-body">
	<?php 
		if (isset($updateStaf)) {
			echo $updateStaf;
		}
	?>
		<div class="row">
			<div class="col-md-6">
				<?php
				    $s = $s->getStafById($id);
				    if ($s) {
				      while ($result = $s->fetch_assoc()) {
			    ?>
				<form action="" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label>Nama</label>
						<input type="text" value="<?php echo $result['nama'] ?>" class="form-control" name="nama">
					</div>

					<div class="form-group">
						<label>Email</label>
						<input type="text" value="<?php echo $result['email'] ?>" class="form-control" name="email">
					</div>

					<div class="form-group">
						<label>User</label>
						<input type="text" value="<?php echo $result['user'] ?>" class="form-control" name="user">
					</div>

					<div class="btn-group">
					<label>Pilih Level</label><br>
					  <select id="select" name="level">
					  	<option selected="">Pilih Tingkat</option>
					  	<option value="0">Kepala Perpustakaan</option>
					  	<option value="1">Staff</option>
					  </select>
					</div>
					<div class="form-group">
						<br>
						<label>Foto</label>
						<img src="../<?php echo $result['foto'] ?>" height="45px" width="60px"><br><br>
						<input type="file" class="" name="foto">
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
