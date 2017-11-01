<?php
spl_autoload_register(function($class){
  include_once "../../classes/".$class.".php";
});
  $b = new Buku();
  $m = new Member();
?>
<?php 
	$t = new Transaksi();
	$b = new Buku();
	if($_POST['rowid']) {
		$kodepinjam = $_POST['rowid'];
	}
?>
<table class="table table-bordered">
	<tr>
		<thead>
		<th>No</th>
		<th>Judul</th>
		<th>Penulis</th>
		<th>Tahun Terbit</th>
	</thead>
	</tr>
	<tr>
		<?php 
    		$no=0;
			$getItem = $t->getItem($kodepinjam);
			if ($getItem) {
				while ($result = $getItem->fetch_assoc()) {
					$no++;
		?>
		<tbody>
			<td><?php echo $no.'.'; ?></td>
			<td><?php
				$getBuku = $b->getBukuBykodeBuku($result['kodeBuku'])->fetch_assoc();
				echo $getBuku['judul'];
			?></td>

			<td><?php
				$getBuku = $b->getBukuBykodeBuku($result['kodeBuku'])->fetch_assoc();
				echo $getBuku['penulis'];
			?></td>

			<td><?php
				$getBuku = $b->getBukuBykodeBuku($result['kodeBuku'])->fetch_assoc();
				echo $getBuku['tahunTerbit'];
			?></td>
		</tbody>
			<?php } }  ?>
	</tr>

</table>