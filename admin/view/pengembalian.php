<?php include '../inc/header.php'; ?>
<?php include '../inc/sidebar.php'; ?>

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
			  	  		<th>Kode Pinjam</th>
			  	  		<th>Nama Peminjam</th>
			  	  		<th>Tgl Pinjam</th>
			  	  		<th>Batas Pinjam</th>
			  	  		<th width="5%">Jumlah Pinjam</th>
			  	  		<th>Tanggal Kembali</th>
			  	  		<th>Telat</th>
			  	  		<th>Denda</th>
			  	  		<th>Staf</th>
			  	  		<th>Status</th>
			  	  		<th>Action</th>
			    	</tr>
			    </thead>
		        <tbody>
			        <?php
			        $i=0;
			        $pinjam = $t->getPinjamBystatus();
			        if ($pinjam) {
			          while ($result = $pinjam->fetch_assoc()) {
			            $i++;
			          ?>
			        <tr>
		    			<td><?php echo $i."."; ?></td>
		    			<td width="13%"><?php echo $result['kodepinjam'] ?></td>
		    			<td>
		    				<?php 
		    					$member = $m->getMById($result['member_id'])->fetch_assoc();
		    					echo $member['nama'];
		    				?>
		    			</td>
		    			<td><?php echo $result['tglpinjam']; ?></td>
		    			<td><?php echo $result['batasPinjam']; ?></td>
		    			<td>
		    				<?php 
		    					$jp = $t->JmPinjam($result['kodepinjam'])->fetch_row();
		    					echo $jp[0];
		    				?>
		    			</td>
		    			<td>
		    				<?php 
		    					$telat = $t->getKembaliByKodePinjam($result['kodepinjam'])->fetch_assoc();
		    					echo $telat['tglKembali'];
		    				?>
		    			</td>
		    			<td>
		    				<?php 
		    					$telat = $t->getKembaliByKodePinjam($result['kodepinjam'])->fetch_assoc();
		    					echo $telat['telat'].' Hari';
		    				?>
		    			</td>
		    			<td>
		    				<?php 
		    					$telat = $t->getKembaliByKodePinjam($result['kodepinjam'])->fetch_assoc();
		    					echo 'Rp. '.$telat['denda'];
		    				?>
		    			</td>
		    			<td>
		    				<?php 
		    					$staf = $s->getStafById($result['admin_id'])->fetch_assoc();
		    					echo $staf['nama'];
		    				?>
		    			</td>
		    			<td>
		    				<?php 
		    					if ($result['status'] == '0') {
		    						echo "<b><span style='color:red'>Pinjam</span></b>";
		    					}else if ($result['status'] == '1') {
		    						echo "<b><span style='color:green'>Kembali</span></b>";
		    					}
		    				?>
		    			</td>
				    	<td width="16%">

				            <a href='#myModal' class='btn btn-primary btn-sm' id='custId' data-toggle='modal' data-id="<?php echo  $result['kodepinjam']; ?>">Detail</a>

				    		<a  href="detailsKembali.php?kodepinjam=<?php echo $result['kodepinjam']; ?>" class='btn btn-success btn-sm'>Pengembalian</a>
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










