<?php
    ini_set("memory_limit","5000M");
    ini_set('max_execution_time', 0);
    
session_start();
include "../../config/koneksimysqli.php";
include "../../config/fungsi_sql.php";

/// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
    0 =>'idca',
    1 =>'idca',
    2 => 'idca',
    3=> 'nama',
    4=> 'tgl',
    5=> 'periode',
    6=> 'jumlah',
    7=> 'keterangan',
    8=> 'jenis_ca'
);

$tgl1="";
if (isset($_GET['uperiode1'])) {
    $tgl1=$_GET['uperiode1'];
}
$tgl2="";
if (isset($_GET['uperiode2'])) {
    $tgl2=$_GET['uperiode2'];
}

$tgl1= date("Y-m", strtotime($tgl1));
$tgl2= date("Y-m", strtotime($tgl2));
//FORMAT(realisasi1,2,'de_DE') as 
// getting total number records without any search
$sql = "SELECT idca, DATE_FORMAT(tgl,'%d %M %Y') as tgl, DATE_FORMAT(tgl,'%d/%m/%Y') as periode, DATE_FORMAT(periode,'%M %Y') as bulan, "
        . " divisi, karyawanid, nama, areaid, nama_area, FORMAT(jumlah,0,'de_DE') as jumlah, keterangan, "
        . " jabatanid, tgl_atasan1, tgl_atasan2, tgl_atasan3, tgl_atasan4, validate, fin, jenis_ca, gbr_atasan1, gbr_atasan2 ";
$sql.=" FROM dbmaster.v_ca0 ";


$sql = "SELECT idca, DATE_FORMAT(tgl,'%d %M %Y') as tgl, DATE_FORMAT(tgl,'%d/%m/%Y') as periode, DATE_FORMAT(periode,'%M %Y') as bulan, "
        . " divisi, karyawanid, nama, areaid, areaid_o, FORMAT(jumlah,0,'de_DE') as jumlah, keterangan, "
        . " jabatanid, tgl_atasan1, tgl_atasan2, tgl_atasan3, tgl_atasan4, validate, fin, jenis_ca, tgl_fin, nama_karyawan ";
$sql.=" FROM dbmaster.v_ca0_mydata ";

$sql.=" WHERE stsnonaktif <> 'Y' "; //kode = 2 BIAYA RUTIN, kode = 1 BIAYA LUAR KOTA
$sql.=" AND Date_format(tgl, '%Y-%m') between '$tgl1' and '$tgl2' ";
if (!empty($_GET['uarea'])) $sql.=" and icabangid='$_GET[uarea]' ";
if (!empty($_GET['udivisi'])) 
    $sql.=" and (divisi='$_GET[udivisi]') ";
else{
    if ($_SESSION['ADMINKHUSUS']=="Y") {
        if (!empty($_SESSION['KHUSUSSEL'])) $sql .=" AND (divisi in $_SESSION[KHUSUSSEL])";
    }
}

if ($_SESSION['LVLPOSISI']=="FF1" OR $_SESSION['LVLPOSISI']=="FF2" OR $_SESSION['LVLPOSISI']=="FF3" OR $_SESSION['LVLPOSISI']=="FF4" OR $_SESSION['LVLPOSISI']=="FF5" OR $_SESSION['LVLPOSISI']=="FF6") $sql .=" and karyawanid ='$_SESSION[IDCARD]' ";
if ($_SESSION['IDCARD']=="0000000825" OR $_SESSION['IDCARD']=="0000001587") {
    
    if (!empty($_SESSION['AKSES_REGION'])) {
        $sql .=" and (karyawanid ='$_SESSION[IDCARD]' OR userid='$_SESSION[IDCARD]') ";
    }
    
}else{
    if ($_SESSION['JABATANID']==38) $sql .=" and karyawanid ='$_SESSION[IDCARD]' ";
}
/*
if ($_SESSION['LVLPOSISI']=="FF2") {
    $sql .=" and icabangid in (select icabangid from MKT.ispv0 where karyawanid='$_SESSION[IDCARD]') ";
}
if ($_SESSION['LVLPOSISI']=="FF3") {
    $sql .=" and icabangid in (select icabangid from MKT.idm0 where karyawanid='$_SESSION[IDCARD]') ";
}
 * 
 */
if ($_SESSION['LVLPOSISI']=="FF4") {
    $sql .=" and (icabangid in (select icabangid from MKT.ism0 where karyawanid='$_SESSION[IDCARD]') OR karyawanid='$_SESSION[IDCARD]') ";
}
if ($_SESSION['LVLPOSISI']=="FF7") {
    $sql .=" and karyawanid ='$_SESSION[IDCARD]' ";
    if (!empty($_SESSION['REGION'])) {
        //$sql .=" and (icabangid in (select icabangid from MKT.icabang where region='$_SESSION[REGION]') OR karyawanid='$_SESSION[IDCARD]') ";
    }else{
        //$sql .=" and karyawanid ='$_SESSION[IDCARD]' ";
    }
}

if (!empty($_SESSION['AKSES_JABATAN'])) {
    $sql .= " AND (jabatanid in ($_SESSION[AKSES_JABATAN]) OR karyawanid='$_SESSION[IDCARD]')";
}

$query=mysqli_query($cnmy, $sql) or die("mydata.php: get data");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

//$sql.=" WHERE 1=1 "; // ada

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
    $sql.=" AND ( idca LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR nama LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR nama_karyawan LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR DATE_FORMAT(periode,'%d/%m/%Y') LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR DATE_FORMAT(tgl,'%d/%m/%Y') LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR jumlah LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR keterangan LIKE '%".$requestData['search']['value']."%' )";
}
$query=mysqli_query($cnmy, $sql) or die("mydata.php: get data");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($cnmy, $sql) or die("mydata.php: get data");

$data = array();
$no=1;
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
    $nestedData=array();
    
    $idno=$row['idca'];
    $nama=$row["nama"];
    
    $nnkaryawanid=$row["karyawanid"];
    $namakrynone=$row["nama_karyawan"];
    if (trim($nnkaryawanid)=="0000002200") {
        $nama=$namakrynone;
    }
	
	
    $namaarea="";
    //$namaarea=$row["nama_area"];
    $periode = $row["periode"];
    $bulan = $row["bulan"];
    $jumlah = $row["jumlah"];
    $ket = $row["keterangan"];
    
    $edit="<a class='btn btn-success btn-xs' href='?module=$_GET[module]&act=editdata&idmenu=$_GET[idmenu]&nmun=$_GET[nmun]&id=$idno'>Edit</a>";
    $hapus="<input type='button' class='btn btn-danger btn-xs' value='Hapus' onClick=\"ProsesData('hapus', '$idno')\">";
    $print = "<a title='Print / Cetak' href='#' class='btn btn-info btn-xs' data-toggle='modal' "
        . "onClick=\"window.open('eksekusi3.php?module=$_GET[module]&brid=$idno&iprint=print',"
        . "'Ratting','width=700,height=500,left=500,top=100,scrollbars=yes,toolbar=yes,status=1,pagescrool=yes')\"> "
        . "Print</a> 
    ";
    
    $t_ats1 = $row["tgl_atasan1"];
    $t_ats2 = $row["tgl_atasan2"];
    //$g_ats2 = $row["gbr_atasan2"];
    $g_ats2 = getfieldcnmy("select gbr_atasan2 as lcfields from dbmaster.t_ca0 where idca='$idno'");
    $t_ats3 = $row["tgl_atasan3"];
    $t_ats4 = $row["tgl_atasan4"];
    $pjabatanid=$row['jabatanid'];
    $lvlpengajuan = getfieldcnmy("select LEVELPOSISI as lcfields from dbmaster.v_level_jabatan where jabatanId='$pjabatanid'");
    $allbutton="$edit $hapus $print";
    
    if ($lvlpengajuan=="FF1") {
        if (!empty($t_ats1) OR !empty($t_ats2) OR !empty($t_ats3) OR !empty($t_ats4)) $allbutton="$print";
    }elseif ($lvlpengajuan=="FF2") {
        if ((!empty($t_ats2) AND empty($g_ats2)) AND empty($t_ats3)) {

        }else{
            if (!empty($t_ats2) OR !empty($t_ats3) OR !empty($t_ats4)) $allbutton="$print";
        }
    }elseif ($lvlpengajuan=="FF3") {
        if (!empty($t_ats3) OR !empty($t_ats4)) $allbutton="$print";
    }elseif ($lvlpengajuan=="FF4") {
        if (!empty($t_ats4)) $allbutton="$print";
    }
    
    $warna="btn btn-success btn-xs";
    $gambar = getfieldcnmy("select count(*) as lcfields from dbimages.img_ca0 where idca='$idno' LIMIT 1");
    if (empty($gambar)) $gambar=0;
    if ( (int)$gambar>0 ) $warna="btn btn-danger btn-xs";
    $upload = "<a class='$warna' href='?module=$_GET[module]&act=uploaddok&idmenu=$_GET[idmenu]&nmun=$_GET[nmun]&id=$idno'>Upload</a>";
    
    $jenisca="Biaya Rutin";
    if (trim($row['jenis_ca'])=="lk")
        $jenisca="Biaya Luar Kota";
        
    
    
    
    $apv1="";
    $apv2="";
    $apv3="";
    $apv4="";
    $apvfin="";
    if ((int)$pjabatanid==20 OR (int)$pjabatanid==5) {
    }else{
        if (!empty($row["tgl_atasan1"]) AND $row["tgl_atasan1"] <> "0000-00-00") $apv1=date("d F Y, h:i:s", strtotime($row["tgl_atasan1"]));
        if (!empty($row["tgl_atasan2"]) AND $row["tgl_atasan2"] <> "0000-00-00") $apv2=date("d F Y, h:i:s", strtotime($row["tgl_atasan2"]));
    }
    if (!empty($row["tgl_atasan3"]) AND $row["tgl_atasan3"] <> "0000-00-00") $apv3=date("d F Y, h:i:s", strtotime($row["tgl_atasan3"]));
    if (!empty($row["tgl_atasan4"]) AND $row["tgl_atasan4"] <> "0000-00-00") $apv4=date("d F Y, h:i:s", strtotime($row["tgl_atasan4"]));
    if (!empty($row["tgl_fin"]) AND $row["tgl_fin"] <> "0000-00-00") $apvfin=date("d F Y, h:i:s", strtotime($row["tgl_fin"]));
    if ((int)$pjabatanid==10 OR (int)$pjabatanid==18) {$apv1 = "";}
    if ((int)$pjabatanid==8) {$apv1 = ""; $apv2 = "";}
    if ((int)$pjabatanid==38) {$apv1 = ""; }
    
    
    $nestedData[] = $no;
    $nestedData[] = $allbutton;
    $nestedData[] = $idno;
    $nestedData[] = $nama;
    //$nestedData[] = $namaarea;
    $nestedData[] = $periode;
    $nestedData[] = $bulan;
    $nestedData[] = $jumlah;
    
    
    $nestedData[] = $upload;
    
    $nestedData[] = $ket;
    //$nestedData[] = $jenisca;
    
    $nestedData[] = $apv1;
    $nestedData[] = $apv2;
    $nestedData[] = $apv3;
    $nestedData[] = $apvfin;


    $data[] = $nestedData;
    $no=$no+1;
}



$json_data = array(
    "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
    "recordsTotal"    => intval( $totalData ),  // total number of records
    "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data"            => $data   // total data array
);

echo json_encode($json_data);  // send data as json format

?>
