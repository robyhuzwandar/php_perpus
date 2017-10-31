<?php 
  $ud = 1000;

  $tglPinjam = '2017-10-01';
  $batasPinjam = '2017-10-07';
  $tglKembali = '2017-10-08';

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

  // echo $y_pinjam;
  // echo $m_pinjam;
  // echo $d_pinjam;

  //jika dia meminjam pd bln yg sama, batasnya pada bulan yg sama, mengembalikan pd bulan yg sama namun telat pd tahun yg sama
  if ($m_pinjam == $m_kembali and $m_batas == $m_kembali and $d_batas < $d_kembali and $y_pinjam == $y_kembali) {
    $start_date = new DateTime($batasPinjam);
    $end_date = new DateTime($tglKembali);
    $interval = $start_date->diff($end_date);
    $telat = $interval->days;
    $denda = $telat * $ud;
    echo $telat.'<br>';
    echo $denda;
  }
  //meminjam namun ngembalikan pada tahun berikutnya
  else if ($y_batas < $y_kembali and $y_pinjam == $y_batas){
    $start_date = new DateTime($batasPinjam);
    $end_date = new DateTime($tglKembali);
    $interval = $start_date->diff($end_date);
    $telat = $interval->days;
    $denda = $telat * $ud;
    echo $telat.'<br>';
    echo $denda;
  }
  //jika dia meminjam pd bln yg sama, batasnya pada bulan yg sama, mengembalikan pd bulan berikutnya dan tahun yg sama
  else if ($m_pinjam == $m_batas and $m_batas < $m_kembali and $y_pinjam == $y_kembali){
    $start_date = new DateTime($batasPinjam);
    $end_date = new DateTime($tglKembali);
    $interval = $start_date->diff($end_date);
    $telat = $interval->days;
    $denda = $telat * $ud;
    echo $telat.'<br>';
    echo $denda;
  }
  //jika dia meminjam pada akhir bulan batasnya pada bulan yg sama namun dia mengembalikan pada awal bulan tahun berikutnya
  else if ($m_pinjam == $m_batas and $m_batas > $m_kembali and $y_batas < $y_kembali){
    $start_date = new DateTime($batasPinjam);
    $end_date = new DateTime($tglKembali);
    $interval = $start_date->diff($end_date);
    $telat = $interval->days;
    $denda = $telat * $ud;
    echo $telat.' hari<br>';
    echo $denda;
  }
  //jika dia meminjam pada akhir bulan batasnya pada awal bln tahun berikutnya dia mengembalikan pd bulan yg sama namun telat
  else if ($m_pinjam > $m_batas and $m_batas == $m_kembali and $y_batas > $y_pinjam and $d_batas < $d_kembali){
    $start_date = new DateTime($batasPinjam);
    $end_date = new DateTime($tglKembali);
    $interval = $start_date->diff($end_date);
    $telat = $interval->days;
    $denda = $telat * $ud;
    echo $telat.' hari<br>';
    echo $denda;
  }
  //jika dia meminjam pada akhir bulan batasnya pada awal bln tahun berikutnya dia mengembalikan pd bulan berikutnya 
  else if ($m_pinjam > $m_batas and $m_batas < $m_kembali and $y_batas > $y_pinjam ){
    $start_date = new DateTime($batasPinjam);
    $end_date = new DateTime($tglKembali);
    $interval = $start_date->diff($end_date);
    $telat = $interval->days;
    $denda = $telat * $ud;
    echo $telat.' hari<br>';
    echo $denda;
  }
  else{
    echo "denda 0";
  }
 ?>