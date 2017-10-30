<?php 
$filepath = realpath(dirname(__FILE__));
include_once ($filepath.'/../lib/Database.php');
include_once ($filepath.'/../lib/Format.php');

spl_autoload_register(function($class){
  include_once "classes/".$class.".php";
});
  $b = new Buku();
  $m = new Member();
?>

<?php 
class Transaksi
{
	private $db;
	public function __construct()
	{
		$this->db = new Database();
	}

	public function addToTemp($kodeBuku)
	{
		$kodeBuku = mysqli_real_escape_string($this->db->link, $kodeBuku);
	
		$query = "SELECT * FROM cart WHERE kodeBuku ='$kodeBuku'";
		$result = $this->db->select($query);
		if ($result) {
			$msg = "<span style='color:red'>Buku Sudah Masuk ke Cart</span>";
			return $msg;
		}else{
			$query = "INSERT INTO cart(kodeBuku) VALUES('$kodeBuku')";
			$insert_row = $this->db->insert($query);
			if ($insert_row) {
				$msg = "<span style='color:green'>Buku Sukses Masuk ke Cart </span>";
				return $msg;
			}else{
				$msg = "<span style='color:red'>Buku Gagal Masuk ke Cart</span>";
				return $msg;
			}
		}
	}

	public function getCart()
	{
		$query = "SELECT * FROM cart";
		$result = $this->db->select($query);
		return $result;
	}

	public function addToPinjam($id, $staf)
	{	
		$id = mysqli_real_escape_string($this->db->link, $id);
		$staf = mysqli_real_escape_string($this->db->link, $staf);
		$tgl1 = date('Y-m-d');
		$tgl_kembali = date('Y-m-d', strtotime('+7 days', strtotime($tgl1)));
		
		$query = "SELECT * FROM member WHERE id = '$id'";
		$value = $this->db->select($query)->fetch_assoc();
		$nim = $value['nim'];
		$kodepinjam = substr($nim, 7);
		$kodepinjam .= substr($tgl1, 4);

		// $query = "SELECT * FROM pinjam WHERE member_id = '$nim'";
		// $cekNim = $this->db->select($query);
		// if ($cekNim) {
		// 	$msg = "Nim masih dalam peminjaman";
		// 	return $msg;
		// }else{
			$query = "INSERT INTO pinjam(kodepinjam, tglpinjam, member_id, admin_id, tglKembali) VALUES('$kodepinjam','$tgl1', '$id', '$staf', '$tgl_kembali')";
			$insert_row = $this->db->insert($query);

			if ($insert_row) {

			$query = "SELECT * FROM cart";
			$getValue = $this->db->select($query);
			if ($getValue) {
				while ($result = $getValue->fetch_assoc()) {
						$kodeBuku = $result['kodeBuku'];
						$query = "INSERT INTO item(kodeBuku, kodepinjam) VALUES('$kodeBuku', '$kodepinjam')";
						$insert_row = $this->db->insert($query);
						if ($insert_row) {
							$query = "DELETE * FROM cart";
							$delCart = $this->db->delete($query);
							if ($delCart) {
								$msg = "<span style='color:green'>Buku Sukses Masuk ke daftar pinjam </span>";
								return $msg;	
							}
						}else{
							$msg = "<span style='color:red'>Buku Gagal Masuk ke daftar pinjam</span>";
							return $msg;
						}
					}
				}
			// $msg = "<span style='green'>Buku Sukses Masuk ke daftar pinjam </span>";
			// return $msg;
			}else{
				$msg = "<span style='red'>Buku Gagal Masuk ke daftar pinjam</span>";
				return $msg;
			}
		// }

		
		}

	public function getAllMember()
	{
		$query = "SELECT * FROM pinjam";
		$result = $this->db->select($query);
		return $result;
	}

	public function delPfBykb($kodeBuku)
  	{
  		$query = "DELETE FROM cart WHERE kodeBuku='$kodeBuku'";
	    $delete_row = $this->db->delete($query);
	    if ($delete_row) {
	    	$msg = "Data Berhasil di Hapus";
	    	return $msg;
	    }else {
	    	$msg = "Data Gagal di Hapus";
	    	return $msg;
  		}
	}

	public function getPinjam()
	{
		$query = "SELECT * FROM pinjam";
		$result = $this->db->select($query);
		return $result;
	}
}

?>