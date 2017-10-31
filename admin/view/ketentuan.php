
<?php include '../inc/header.php'; ?>
<?php include '../inc/sidebar.php'; ?>
<section class="content">
<div class="panel panel-default">
  <div class="panel-heading">
    List Platform
  </div>
  <div class="panel-body">
  <table class="table table-striped display" id="example" width="100%" cellspacing="0">
      <thead>
      <tr>
        <th>No.</th>
        <th>Uang Denda</th>
        <th>Jumlah Hari Peminjaman</th>
        <th>Action</th>
      </tr>
    </thead>
        <tbody>
      <?php
      $i=0;
      $ket = $t->getKet();
      if ($ket) {
        while ($result = $ket->fetch_assoc()) {
          $i++;
        ?>
      <tr>
        <td><?php echo $i."."; ?></td>
        <td><?php echo $result['denda']; ?></td>
        <td><?php echo $result['jm_hari']; ?></td>
        <td>
          <a href="../aksi/ketentuanUpdate.php?kId=<?php echo $result['id']; ?>"><button class="btn-primary btn-sm">Edit</button></a>
           
        </td>
      </tr>
      <?php } } ?>
    </tbody>
  </table>
  </div>
</div>
</section>
<?php include '../inc/footer.php'; ?>