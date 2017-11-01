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
		$tglpinjam = date('Y-m-d');
		$batasPinjam = date('Y-m-d', strtotime('+7 days', strtotime($tglpinjam)));
		
		$query = "SELECT * FROM member WHERE id = '$id'";
		$value = $this->db->select($query)->fetch_assoc();
		$nim = $value['nim'];
		$kodepinjam = substr($nim, 8);
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
		$query = "SELECT * FROM pinjam WHERE member_id = '$id' AND status='0'";
		$cekNim = $this->db->select($query);
		if ($cekNim) {
			$msg = "<span style='color:red'>Nim masih dalam peminjaman</span>";
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
				$msg = "<span style='color:green'>Buku Sukses Masuk ke daftar pinjam </span>";
				return $msg;
			}else{
				$msg = "<span style='color:red'>Buku Gagal Masuk ke daftar pinjam</span>";
				return $msg;
			}
		}

		
		}

	public function addToKembali($kodepinjam)
	{
		$kodepinjam = mysqli_real_escape_string($this->db->link, $kodepinjam);
		$query = "SELECT * FROM ketentuan";
		$getKet = $this->db->select($query)->fetch_assoc();
		$ud = $getKet['denda'];

		$query = "SELECT * FROM pinjam WHERE kodepinjam='$kodepinjam'";
		$getTgl = $this->db->select($query)->fetch_assoc();

		$tglPinjam = $getTgl['tglpinjam'];
		$batasPinjam = $getTgl['batasPinjam'];
		$tglKembali = date('Y-m-d');

	   //menghilangkan karakter pemisah 
		$tglPinjam = preg_replace("/[^a-zA-Z0-9]/", "", $tglPinjam);
		$batasPinjam = preg_replace("/[^a-zA-Z0-9]/", "", $batasPinjam);
		$tglKembali = preg_replace("/[^a-zA-Z0-9]/", "", $tglKembali);
	  //mengambil tahun
		$y_pinjam = substr($tglPinjam, 0,4);
		$y_batas = substr($batasPinjam, 0,4);
		$y_kembali = substr($tglKembali, 0,4);
	  //mengambil potongan bulan
		$m_pinjam = substr($tglPinjam, 4,2);
		$m_batas = substr($batasPinjam, 4,2);
		$m_kembali = substr($tglKembali, 4,2);
	  //mengambil tanggal
		$d_pinjam = substr($tglPinjam, 6);
		$d_batas = substr($batasPinjam, 6);
		$d_kembali = substr($tglKembali, 6);

	  //jika dia meminjam pd bln yg sama, batasnya pada bulan yg sama, mengembalikan pd bulan yg sama namun telat pd tahun yg sama
		if ($m_pinjam == $m_kembali and $m_batas == $m_kembali and $d_batas < $d_kembali and $y_pinjam == $y_kembali) {
			$start_date = new DateTime($batasPinjam);
			$end_date = new DateTime($tglKembali);
			$interval = $start_date->diff($end_date);
			$telat = $interval->days;
			$denda = $telat * $ud;
		}
	  //meminjam namun ngembalikan pada tahun berikutnya
		else if ($y_batas < $y_kembali and $y_pinjam == $y_batas){
			$start_date = new DateTime($batasPinjam);
			$end_date = new DateTime($tglKembali);
			$interval = $start_date->diff($end_date);
			$telat = $interval->days;
			$denda = $telat * $ud;
		}
	  //jika dia meminjam pd bln yg sama, batasnya pada bulan yg sama, mengembalikan pd bulan berikutnya dan tahun yg sama
		else if ($m_pinjam == $m_batas and $m_batas < $m_kembali and $y_pinjam == $y_kembali){
			$start_date = new DateTime($batasPinjam);
			$end_date = new DateTime($tglKembali);
			$interval = $start_date->diff($end_date);
			$telat = $interval->days;
			$denda = $telat * $ud;
		}
	  //jika dia meminjam pada akhir bulan batasnya pada bulan yg sama namun dia mengembalikan pada awal bulan tahun berikutnya
		else if ($m_pinjam == $m_batas and $m_batas > $m_kembali and $y_batas < $y_kembali){
			$start_date = new DateTime($batasPinjam);
			$end_date = new DateTime($tglKembali);
			$interval = $start_date->diff($end_date);
			$telat = $interval->days;
			$denda = $telat * $ud;
		}
	  //jika dia meminjam pada akhir bulan batasnya pada awal bln tahun berikutnya dia mengembalikan pd bulan yg sama namun telat
		else if ($m_pinjam > $m_batas and $m_batas == $m_kembali and $y_batas > $y_pinjam and $d_batas < $d_kembali){
			$start_date = new DateTime($batasPinjam);
			$end_date = new DateTime($tglKembali);
			$interval = $start_date->diff($end_date);
			$telat = $interval->days;
			$denda = $telat * $ud;
		}
	  //jika dia meminjam pada akhir bulan batasnya pada awal bln tahun berikutnya dia mengembalikan pd bulan berikutnya 
		else if ($m_pinjam > $m_batas and $m_batas < $m_kembali and $y_batas > $y_pinjam ){
			$start_date = new DateTime($batasPinjam);
			$end_date = new DateTime($tglKembali);
			$interval = $start_date->diff($end_date);
			$telat = $interval->days;
			$denda = $telat * $ud;
		}
		else{
			$telat = 0;
			$denda = 0;
		}
		

		$query = "INSERT INTO kembali(kodepinjam, tglKembali, telat, denda) VALUES('$kodepinjam','$tglKembali', '$telat', '$denda')";
		$insert_row = $this->db->insert($query);
		if ($insert_row) {
			$query = "UPDATE pinjam SET status='1' WHERE kodepinjam='$kodepinjam'";
			$update_row = $this->db->update($query);
		}
	}

	public function getAllPinjam()
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
		$query = "SELECT * FROM pinjam ORDER BY tglPinjam DESC";
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

	public function ketentuanUpdate($denda, $jm_hari, $id)
	{
		$denda = mysqli_real_escape_string($this->db->link, $denda);
		$jm_hari = mysqli_real_escape_string($this->db->link, $jm_hari);
		$id = mysqli_real_escape_string($this->db->link, $id);

		$query = "UPDATE ketentuan SET denda = '$denda', jm_hari = '$jm_hari' WHERE id='$id'";
		$update_row = $this->db->update($query);
		if ($update_row) {
			$msg = "<span style='color:green;'>Platform Berhasil di Update</span>";
	          return $msg;
		}else{
			$msg = "<span style='color:red;'>Platform gagal di Update!</span>";
	          return $msg;
		}
	}
	public function getKet()
	{
		$query = "SELECT * FROM ketentuan";
		$result = $this->db->select($query);
		return $result;
	}	

	public function getPinjamBystatus()
	{
		$query = "SELECT * FROM pinjam WHERE status ='1'";
		$result = $this->db->select($query);
		return $result;
	}

	
	
}


?>
