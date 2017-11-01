<?php include '../inc/header.php'; ?>
<?php include '../inc/sidebar.php'; ?>
<?php 
  if (isset($_GET['delsfId'])) {
    $id = $_GET['delsfId'];
    $dels = $s->delStafById($id);
  }
?>
<section class="content">
<div class="panel panel-default">
	<div class="panel-heading">
		List Peminjaman
	</div>
	<div class="panel-body">
		<form method="post" action="">
			<table id="tabel-data" class="table table-striped table-bordered" width="100%" cellspacing="0">
				<thead>
			        <tr>
			  	  		<th>No.</th>
			  	  		<th>Nama</th>
			  	  		<th>Email</th>
			  	  		<th>User</th>
			  	  		<th>Pass</th>
			  	  		<th>Foto</th>
			  	  		<th>Action</th>
			    	</tr>
			    </thead>
		        <tbody>
			        <?php
			        $i=0;
			        $staf = $s->getAllstaf();
			        if ($staf) {
			          while ($result = $staf->fetch_assoc()) {
			            $i++;
			          ?>
			        <tr>
		    			<td><?php echo $i."."; ?></td>
		    			<td width="13%"><?php echo $result['nama'] ?></td>
		    			<td width="13%"><?php echo $result['email'] ?></td>
		    			<td><?php echo $result['user']; ?></td>
		    			<td><?php echo $result['pass']; ?></td>
		    			<td><img width="30%" src="../<?php echo $result['foto']; ?>"></td>
				    	<td>
				          <a href="../aksi/stafUpdate.php?sId=<?php echo $result['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
				          <a onclick="return confirm('Yakin untuk Hapus Data ?')" href="staflist.php?delsfId=<?php echo $result['id']; ?>"><button class="btn-danger btn-sm">Hapus</button></a>  
				        </td>
			    	</tr>
			        <?php } } ?>
		      	</tbody>
		   </table>
		</form>
	     <div class="modal fade" id="myModal" role="dialog">
	        <div class="modal-dialog" role="document">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button type="button" class="close" data-dismiss="modal">&times;</button>
	                    <h4 class="modal-title">Detail Barang</h4>
	                </div>
	                <div class="modal-body">
	                    <div class="fetched-data"></div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>	
</div>
</section>
<?php include '../inc/footer.php'; ?>










