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
		$member_id = mysqli_real_escape_string($this->db->link, $id);
		$staf = mysqli_real_escape_string($this->db->link, $staf);
		$tglpinjam = date('Y-m-d');
		$batasPinjam = date('Y-m-d', strtotime('+7 days', strtotime($tglpinjam)));
		
		$query = "SELECT * FROM member WHERE id = '$id'";
		$value = $this->db->select($query)->fetch_assoc();
		$nim = $value['nim'];
		$kodepinjam = substr($nim, 7);
		$kodepinjam .= substr($tglpinjam, 4);
		$kodepinjam = preg_replace('/[^-a-zA-Z0-9_]/', '', $kodepinjam);

		$gpass=NULL;
		$n = 3; // jumlah karakter yang akan di bentuk.
		$chr = '783736ABCDEFGHIJKLMNOPQRSTU454VWXYZabcdefghijklmnopqrstuvqxyz0123456789';
		for($i=0;$i<$n;$i++){
			$rIdx = rand(1,strlen($chr));
			$gpass .=substr($chr,$rIdx,1);
		}
		$kodepinjam .= $gpass;
		$query = "SELECT * FROM pinjam WHERE member_id = '$nim'";
		$cekNim = $this->db->select($query);
		if ($cekNim) {
			$msg = "Nim masih dalam peminjaman";
			return $msg;
		}else{
			$query = "INSERT INTO pinjam(kodepinjam, tglpinjam, member_id, admin_id, batasPinjam) VALUES('$kodepinjam','$tglpinjam', '$member_id', '$staf', '$batasPinjam ')";
			$query = "INSERT INTO pinjam(kodepinjam, tglpinjam, member_id, admin_id, batasPinjam) VALUES('$kodepinjam','$tglpinjam', '$id', '$staf', '$batasPinjam ')";
			$insert_row = $this->db->insert($query);
			if ($insert_row) {
			$query = "SELECT * FROM cart";
			$getValue = $this->db->select($query);
			if ($getValue) {
				while ($result = $getValue->fetch_assoc()) {
						$kodeBuku = $result['kodeBuku'];
						$query = "INSERT INTO item(kodeBuku, kodepinjam) VALUES('$kodeBuku', '$kodepinjam')";
						$insert_row = $this->db->insert($query);
					}
				}
				$msg = "<span style='green'>Buku Sukses Masuk ke daftar pinjam </span>";
				return $msg;
			}else{
				$msg = "<span style='red'>Buku Gagal Masuk ke daftar pinjam</span>";
				return $msg;
			}
		}

		
		}

	public function addToKembali($kodepinjam)
	{
		$kodepinjam = mysqli_real_escape_string($this->db->link, $kodepinjam);
		$tglKembali = date('Y-m-d');
		
		

		$query = "INSERT INTO kembali(kodepinjam, tglKembali, telat, denda) VALUES('$kodepinjam','$tglKembali', '$telat', '$denda')";
			$insert_row = $this->db->insert($query);
			if ($insert_row) {
				$query = "UPDATE pinjam SET status='1' WHERE kodepinjam='$kodepinjam'";
				$update_row = $this->db->update($query);
			}
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

	public function getPinjamByKodePinjam($kodepinjam)
	{
		$query = "SELECT * FROM pinjam WHERE kodepinjam = '$kodepinjam'";
		$result = $this->db->select($query);
		return $result;
	}

	public function delCart()
	{
		$query = "DELETE FROM cart";
	    $delete_row = $this->db->delete($query);
	}

	public function JmPinjam($kodepinjam)
	{
		$query = "SELECT COUNT(*) FROM item WHERE kodepinjam = '$kodepinjam'";
		$jp = $this->db->select($query);		
		return $jp;
	}

	public function getItem($kodepinjam)
	{
		$query = "SELECT * FROM item WHERE kodepinjam='$kodepinjam' ";
		$result = $this->db->select($query);
		return $result;
	}

	public function getKembaliByKodePinjam($kodepinjam)
	{
		$query = "SELECT * FROM kembali WHERE kodepinjam='$kodepinjam'";
		$result = $this->db->select($query);
		return $result;
	}
}

?>