<?PHP

date_default_timezone_set('Asia/Jakarta');
ini_set("memory_limit","512M");
ini_set('max_execution_time', 0);

session_start();
if (!isset($_SESSION['USERID'])) {
    echo "ANDA HARUS LOGIN ULANG....";
    exit;
}

$pmodule=$_GET['module'];

include("config/koneksimysqli.php");
include("config/common.php");
include "config/fungsi_ubahget_id.php";


$puserid=$_SESSION['USERID'];
$now=date("mdYhis");
$tmp01 =" dbtemp.tmprptcutikry01_".$puserid."_$now ";
$tmp02 =" dbtemp.tmprptcutikry02_".$puserid."_$now ";
$tmp03 =" dbtemp.tmprptcutikry03_".$puserid."_$now ";
$tmp04 =" dbtemp.tmprptcutikry04_".$puserid."_$now ";
$tmp05 =" dbtemp.tmprptcutikry05_".$puserid."_$now ";
$tmp06 =" dbtemp.tmprptcutikry06_".$puserid."_$now ";
$tmp07 =" dbtemp.tmprptcutikry07_".$puserid."_$now ";


$pkryid = $_POST['cb_karyawan'];  
$ptahun = $_POST['e_tahun'];
$ptahunsebelum=(INT)$ptahun-1;
$pnamajabatan="";

$nsjenisid="";
if (isset($_POST['chkbox_jenis'])) $nsjenisid = $_POST['chkbox_jenis'];

$fjenisid="";
if (!empty($nsjenisid)) {
    foreach ($nsjenisid as $npidjns) {
        //if (!empty($pbrandid)) {
            $fjenisid .="'".$npidjns."',";
        //}
    }
    if (!empty($fjenisid)) $fjenisid=" (".substr($fjenisid, 0, -1).") ";
}


//masa kerja
$pthnsistem = date("Y");
$pmasakerja=date("Y-m-d");
if ($ptahun!=$pthnsistem) {
    $pmasakerja=$ptahun."-12-31";
}


$query = "select a.nama, a.jabatanId as jabatanid, b.nama as nama_jabatan from hrd.karyawan as a 
    LEFT join hrd.jabatan as b on a.jabatanId=b.jabatanId 
    where a.karyawanid='$pkryid'";
$tampilk=mysqli_query($cnmy, $query);
$rowk=mysqli_fetch_array($tampilk);
$pnamakarywanpl=$rowk['nama'];
$pnamajabatan=$rowk['nama_jabatan'];

/*
$sql = "select a.*, b.potong_cuti from hrd.karyawan_cuti_close as a LEFT JOIN hrd.jenis_cuti as b "
        . " on a.id_jenis=b.id_jenis WHERE a.tahun='$ptahunsebelum' ";
if (!empty($pkryid)) $sql .=" AND a.karyawanid='$pkryid' ";

$query = "create TEMPORARY table $tmp01 ($sql)"; 
mysqli_query($cnmy, $query);
$erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
*/


$query = "select a.karyawanId as karyawanid, a.nama as nama_karyawan, a.tglmasuk, a.tglkeluar, a.skar, "
        . " a.jabatanId as jabatanid, b.nama as nama_jabatan, a.divisiId as divisiid "
        . " FROM hrd.karyawan as a LEFT JOIN hrd.jabatan as b on a.jabatanId=b.jabatanId WHERE 1=1 ";
if (!empty($pkryid)) $query .=" AND a.karyawanid='$pkryid' ";
else{
    $query .= " AND (IFNULL(a.tglkeluar,'0000-00-00')='0000-00-00' OR IFNULL(a.tglkeluar,'')='') ";
    $query .=" AND LEFT(a.nama,4) NOT IN ('NN -', 'DR -', 'DM -', 'BDG ', 'OTH.', 'TO. ', 'BGD-', 'JKT ', 'MR -', 'MR S')  "
        . " and LEFT(a.nama,7) NOT IN ('NN DM - ', 'MR SBY1')  "
        . " and LEFT(a.nama,3) NOT IN ('TO.', 'TO-', 'DR ', 'DR-', 'JKT', 'NN-', 'TO ') "
        . " AND LEFT(a.nama,5) NOT IN ('OTH -', 'NN AM', 'NN DR', 'TO - ', 'SBY -', 'RS. P') "
        . " AND LEFT(a.nama,6) NOT IN ('SBYTO-', 'MR SBY') ";
}
$query = "create TEMPORARY table $tmp01 ($query)"; 
mysqli_query($cnmy, $query);
$erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

$query = "select DISTINCT a.*, c.potong_cuti, c.nama_jenis FROM hrd.t_cuti0 as a "
        . " LEFT JOIN hrd.t_cuti1 as b on a.idcuti=b.idcuti "
        . " LEFT JOIN hrd.jenis_cuti as c on a.id_jenis=c.id_jenis "
        . " WHERE IFNULL(a.stsnonaktif,'')<>'Y' ";
//if (!empty($fjenisid)) $query .=" AND a.id_jenis IN $fjenisid ";
$query .=" AND ( (YEAR(b.tanggal) = '$ptahun') "
        . " OR (YEAR(a.bulan1) = '$ptahun') OR (YEAR(a.bulan2) = '$ptahun') "
        . " )";
if (!empty($pkryid)) $query .=" AND ( a.karyawanid='$pkryid' OR a.karyawanid IN ('ALL', 'ALLETH', 'ALLHO', 'ALLCHC') ) ";
$query = "create TEMPORARY table $tmp02 ($query)"; 
mysqli_query($cnmy, $query);
$erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }


$query = "select b.karyawanid, b.id_jenis, b.keperluan, b.potong_cuti, a.* "
        . " from hrd.t_cuti1 as a JOIN $tmp02 as b on a.idcuti=b.idcuti ";
$query = "create TEMPORARY table $tmp03 ($query)"; 
mysqli_query($cnmy, $query);
$erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }


//ada di tabel cuti tapi tidak ada di tabel tarikan karyawan, karena sudah nonaktif
$query = "create TEMPORARY table $tmp04 (select distinct karyawanid from $tmp01)"; 
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

if (empty($pkryid)) {
    $query = "INSERT INTO $tmp01 (karyawanid, nama_karyawan, tglmasuk, tglkeluar, skar, jabatanid, nama_jabatan, divisiid)"
            . " SELECT distinct a.karyawanId as karyawanid, b.nama, b.tglmasuk, b.tglkeluar, b.skar, "
            . " b.jabatanId as jabatanid, c.nama as nama_jabatan, b.divisiId as divisiid "
            . " FROM $tmp02 as a JOIN hrd.karyawan as b on a.karyawanid=b.karyawanId "
            . " LEFT JOIN hrd.jabatan as c on b.jabatanId=c.jabatanId "
            . " WHERE a.karyawanid NOT IN (select distinct karyawanid from $tmp04)";
    mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
}

mysqli_query($cnmy, "drop TEMPORARY table if EXISTS $tmp04");
$erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
// END cek data karyawan

//CUTI MASSAL
$query = "select distinct a.karyawanid, a.keperluan, a.id_jenis, b.tanggal, a.nama_jenis from $tmp02 as a "
        . " JOIN $tmp03 as b on a.idcuti=b.idcuti WHERE a.karyawanid IN ('ALL', 'ALLETH', 'ALLHO', 'ALLCHC')";
$query = "create TEMPORARY table $tmp04 ($query)"; 
mysqli_query($cnmy, $query);
$erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

$query = "DELETE $tmp02, $tmp03 FROM $tmp02 JOIN $tmp03 on $tmp02.idcuti=$tmp03.idcuti WHERE $tmp02.karyawanid IN ('ALL', 'ALLETH', 'ALLHO', 'ALLCHC')";
mysqli_query($cnmy, $query);
$erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
//END CUTI MASSAL


$query = "select a.*, b.nama_karyawan, b.tglmasuk, b.tglkeluar, b.tglkeluar as tglmasakerja, b.nama_jabatan, b.divisiid "
        . " from $tmp02 as a "
        . " LEFT JOIN $tmp01 as b on a.karyawanid=b.karyawanid ";
$query = "create TEMPORARY table $tmp05 ($query)"; 
mysqli_query($cnmy, $query);
$erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

$pkosongawal=false;
$query = "select * from $tmp02";
$tampilk=mysqli_query($cnmy, $query);
$ketmeuk= mysqli_num_rows($tampilk);
if ((INT)$ketmeuk<=0) {
    $pkosongawal=true;
    
    $sql = "INSERT INTO $tmp05 (karyawanid, divisiid, jabatanid, tglmasuk, nama_karyawan, nama_jabatan, idcuti, id_jenis) "
            . " select distinct karyawanid, divisiid, jabatanid, tglmasuk, nama_karyawan, nama_jabatan, '0', '0' FROM $tmp01";
    mysqli_query($cnmy, $sql); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
    
    $sql = "INSERT INTO $tmp03 (karyawanid, tanggal, idcuti, id_jenis) "
            . " select distinct karyawanid, current_date(), '0', '0' FROM $tmp01";
    mysqli_query($cnmy, $sql); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
    
    
    $query = "SELECT karyawanid, nama_karyawan, divisiid, jabatanid, tglmasuk, tglkeluar, "
            . " IFNULL(TIMESTAMPDIFF(YEAR, tglmasuk, '$pmasakerja'),0) AS jml_thn, "
            . " IFNULL(TIMESTAMPDIFF(MONTH, tglmasuk, '$pmasakerja'),0) AS jml_bln "
            . " FROM $tmp01";
}else{
    $query = "SELECT karyawanid, nama_karyawan, divisiid, jabatanid, tglmasuk, tglkeluar, "
            . " IFNULL(TIMESTAMPDIFF(YEAR, tglmasuk, '$pmasakerja'),0) AS jml_thn, "
            . " IFNULL(TIMESTAMPDIFF(MONTH, tglmasuk, '$pmasakerja'),0) AS jml_bln "
            . " FROM $tmp05";
}
$query = "create TEMPORARY table $tmp06 ($query)";
mysqli_query($cnmy, $query);
$erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }


$query = "update $tmp06 set tglkeluar='0000-00-00' WHERE YEAR(tglkeluar)>'$ptahun'";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

$query = "alter table $tmp06 add column jml_tambah INT (4)";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

$query = "select distinct karyawanid, nama_karyawan, divisiid, jabatanid, tglmasuk, tglkeluar, jml_thn, jml_bln, id_jenis, nama_jenis, potong_cuti FROM $tmp06, hrd.jenis_cuti"; 
$query = "create TEMPORARY table $tmp07 ($query)";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

$query = "alter table $tmp07 add column jumlah INT(4), add column jml_tambah INT (4), add column jml_cuti INT (4), add column sisa_cuti INT (4)";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

$query = "UPDATE $tmp07 SET jumlah='12' WHERE id_jenis='01' AND IFNULL(jml_thn,0)>=1";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

$query = "UPDATE $tmp07 SET jumlah=jml_bln WHERE id_jenis='01' AND IFNULL(jml_thn,0)=0 AND IFNULL(jml_bln,0)>1 AND IFNULL(jml_bln,0)<=12";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }


$query ="SELECT id_jenis, dari, sampai, ifnull(free_cuti,0) as free_cuti FROM hrd.jenis_cuti_free_tambahan WHERE 1=1 "
        . " order by id_jenis, dari, sampai";
$tampilk=mysqli_query($cnmy, $query);
while ($rowk= mysqli_fetch_array($tampilk)) {
    $lidjenis=$rowk['id_jenis'];
    $ldari=$rowk['dari'];
    $lsampai=$rowk['sampai'];
    $lfreecuti=$rowk['free_cuti'];
    
    if (empty($lfreecuti)) $lfreecuti=0;
    
    if ($lidjenis=="11") {
        $query = "UPDATE $tmp07 SET jumlah='$lfreecuti' WHERE "
                . " ifnull(jml_thn,0)>='$ldari' AND ifnull(jml_thn,0)<='$lsampai' AND id_jenis='$lidjenis'";
        mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
    }else{
        if ((INT)$ldari==0 AND (INT)$lsampai==0) {
            $query ="UPDATE $tmp07 SET jumlah='$lfreecuti' WHERE  id_jenis='$lidjenis'";
        }else{
            $query ="UPDATE $tmp07 SET jumlah='$lfreecuti' WHERE "
                    . " ifnull(jml_thn,0)>='$ldari' AND ifnull(jml_thn,0)<='$lsampai' AND id_jenis='$lidjenis'";
        }
        if ($lidjenis=="08") {
            //echo "$query<br/>";
        }
        mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
    }
}


$query = "alter table $tmp02 add column jml_cuti INT(4)";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

$query = "UPDATE $tmp02 as a JOIN (select idcuti, count(distinct tanggal) as jml_cuti FROM $tmp03 GROUP BY 1) as b "
        . " on a.idcuti=b.idcuti SET a.jml_cuti=b.jml_cuti";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

$query = "UPDATE $tmp02 SET jml_cuti=1 WHERE id_jenis in ('02')";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

$query = "UPDATE $tmp07 as a JOIN $tmp02 as b on a.karyawanid=b.karyawanid AND a.id_jenis=b.id_jenis SET a.jml_cuti=b.jml_cuti";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

//cuti melahirkan
$query = "UPDATE $tmp07 SET jumlah=1 WHERE id_jenis in ('02') AND IFNULL(jml_cuti,0)>0";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

//cuti massal , 'ALLHO', 'ALLCHC' untuk marketing dulu
$query = "UPDATE $tmp07 as a JOIN 
	(SELECT id_jenis, count(DISTINCT tanggal) as jml_cuti FROM $tmp04 WHERE karyawanid IN ('ALL', 'ALLETH') 
	GROUP BY 1) as b on a.id_jenis=b.id_jenis SET a.jml_cuti=b.jml_cuti WHERE a.divisiid NOT IN ('HO', 'OTC', 'CHC')";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

$query = "UPDATE $tmp07 as a JOIN 
	(SELECT id_jenis, count(DISTINCT tanggal) as jml_cuti FROM $tmp04 WHERE karyawanid IN ('ALL', 'ALLHO') 
	GROUP BY 1) as b on a.id_jenis=b.id_jenis SET a.jml_cuti=b.jml_cuti WHERE a.divisiid IN ('HO')";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

$query = "UPDATE $tmp07 as a JOIN 
	(SELECT id_jenis, count(DISTINCT tanggal) as jml_cuti FROM $tmp04 WHERE karyawanid IN ('ALL', 'ALLCHC') 
	GROUP BY 1) as b on a.id_jenis=b.id_jenis SET a.jml_cuti=b.jml_cuti WHERE a.divisiid IN ('OTC', 'CHC')";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

//jabatan mkt
$query = "UPDATE $tmp07 as a JOIN 
	(SELECT id_jenis, count(DISTINCT tanggal) as jml_cuti FROM $tmp04 WHERE karyawanid IN ('ALL', 'ALLETH') 
	GROUP BY 1) as b on a.id_jenis=b.id_jenis SET a.jml_cuti=b.jml_cuti WHERE a.jabatanid IN ('15', '10', '18', '20', '05', '38')";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

// END CUTI MASSAL

$query = "UPDATE $tmp07 SET sisa_cuti=IFNULL(jumlah,0)-IFNULL(jml_cuti,0)";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }




$query = "alter table $tmp05 add column jml_thn INT(4), add column jml_bln INT(4), add column jmlcutithn INT(4), add column jmlcutifree INT (4)";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

$query = "UPDATE $tmp05 as a JOIN $tmp07 as b on a.karyawanid=b.karyawanid SET a.jml_thn=b.jml_thn, a.jml_bln=b.jml_bln";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }


if (!empty($fjenisid)) {
    if ($pkosongawal == false) {
        $query = "DELETE FROM $tmp05 WHERE id_jenis NOT IN $fjenisid";
        mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }    
    }
}

?>

<HTML>
<HEAD>
  <TITLE>Report Data Cuti</TITLE>
    <meta http-equiv="Expires" content="Mon, 01 Jan 2030 1:00:00 GMT">
    <meta http-equiv="Pragma" content="no-cache">
    <?php header("Cache-Control: no-cache, must-revalidate"); ?>
    <link rel="shortcut icon" href="images/icon.ico" />
    <style> .str{ mso-number-format:\@; } </style>
    
    
    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    
    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
</HEAD>
<script>
</script>

<BODY onload="initVar()" style="margin-left:10px; color:#000; background-color:#fff;">
    
    <div class='modal fade' id='myModal' role='dialog'></div>
    <button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>

    <?PHP

    echo "<b>Report Data Cuti/Izin/Up Country Ethical</b><br/>";
    //echo "<b>Nama : $pnamakarywanpl - $pkryid</b><br/>";
    //echo "<b>Jabatan : $pnamajabatan</b><br/>";
    echo "<b>Tahun : $ptahun</b><br/>";
    echo "<hr/>";

    $totcall=0;
    $totpoint1=0;
    $totpoint2=0;
    
    
    echo "<table id='tbltable' border='1' cellspacing='0' cellpadding='1'>";
        
        echo "<tr>";
            
            echo "<th align='left'><small>No</small></th>";
            echo "<th align='left'><small>Nama Karyawan</small></th>";
            echo "<th align='left'><small>Jabatan</small></th>";
            echo "<th align='left'><small>Tgl. Masuk</small></th>";
            echo "<th align='left'><small>Masa Kerja</small></th>";
            echo "<th align='left'><small>Jenis Cuti</small></th>";
            echo "<th align='left'><small>Tanggal</small></th>";
            echo "<th align='left'><small>Keperluan</small></th>";
            
        echo "</tr>";
        
        $no=1;
        $query = "select distinct karyawanid, nama_karyawan, divisiid, jabatanid, nama_jabatan, tglmasuk, jml_thn, jml_bln FROM $tmp05 ORDER BY nama_karyawan";
        $tampil0=mysqli_query($cnmy, $query);
        while ($row0= mysqli_fetch_array($tampil0)) {
            $pidkaryawan=$row0['karyawanid'];
            $pnmkaryawan=$row0['nama_karyawan'];
            $pnmjabatan=$row0['nama_jabatan'];
            $ptglmasuk=$row0['tglmasuk'];
            $ndivisipilih=$row0['divisiid'];
            
            $pmskrjathn=$row0['jml_thn'];
            $pmskrjabln=$row0['jml_bln'];
            
            $pmasakerja="0";
            if ((INT)$pmskrjathn>0) $pmasakerja=$pmskrjathn." tahun";
            else{
                if ((INT)$pmskrjabln>0) $pmasakerja=$pmskrjabln." bulan";
            }
    
            if ($ptglmasuk=="0000-00-00") $ptglmasuk="";
            if (!empty($ptglmasuk)) $ptglmasuk=date("d/m/Y", strtotime($ptglmasuk));
            
            $nidkry=(INT)$pidkaryawan;
            
            echo "<tr class='fbreak'>";
            echo "<td nowrap>$no</td>";
            echo "<td nowrap>$pnmkaryawan ($nidkry)</td>";
            echo "<td nowrap>$pnmjabatan</td>";
            echo "<td nowrap>$ptglmasuk</td>";
            echo "<td nowrap>$pmasakerja</td>";
            
            $plewat0=false;
            $query = "select distinct id_jenis, nama_jenis FROM $tmp05 WHERE karyawanid='$pidkaryawan' ORDER BY nama_jenis";
            $tampil=mysqli_query($cnmy, $query);
            while ($row= mysqli_fetch_array($tampil)) {
                $pidjenis_=$row['id_jenis'];
                $pnmjenis_=$row['nama_jenis'];
                
                if ($plewat0==false) {
                    
                    echo "<td nowrap>$pnmjenis_</td>";
                    
                }else{
                    echo "<tr>";
                    echo "<td nowrap>&nbsp;</td>";
                    echo "<td nowrap>&nbsp;</td>";
                    echo "<td nowrap>&nbsp;</td>";
                    echo "<td nowrap>&nbsp;</td>";
                    echo "<td nowrap>&nbsp;</td>";
                    echo "<td nowrap>$pnmjenis_</td>";
                }
                $plewat0=true;
                    
                $plewat1=false;
                $query = "select distinct idcuti, id_jenis, nama_jenis, keperluan, bulan1, bulan2 FROM $tmp05 WHERE karyawanid='$pidkaryawan' AND id_jenis='$pidjenis_' ORDER BY nama_jenis";
                $tampil1=mysqli_query($cnmy, $query);
                while ($row1= mysqli_fetch_array($tampil1)) {
                    $pidcuti=$row1['idcuti'];
                    $pidjenis=$row1['id_jenis'];
                    $pnmjenis=$row1['nama_jenis'];
                    $pkeperluan=$row1['keperluan'];
                    $pbln1=$row1['bulan1'];
                    $pbln2=$row1['bulan2'];

                    $pbln1= date("d F Y", strtotime($pbln1));
                    $pbln2= date("d F Y", strtotime($pbln2));

                    
                    if ($plewat1==false) {
                        
                    }else{
                        echo "<tr>";
                        echo "<td nowrap>&nbsp;</td>";
                        echo "<td nowrap>&nbsp;</td>";
                        echo "<td nowrap>&nbsp;</td>";
                        echo "<td nowrap>&nbsp;</td>";
                        echo "<td nowrap>&nbsp;</td>";
                        echo "<td nowrap>&nbsp;</td>";
                    }
                    $plewat1=true;

                    $plewat2=false;
                    $query = "select * FROM $tmp03 WHERE karyawanid='$pidkaryawan' AND id_jenis='$pidjenis' AND idcuti='$pidcuti' ORDER BY tanggal";
                    $tampil2=mysqli_query($cnmy, $query);
                    $ketemu2= mysqli_num_rows($tampil2);
                    if ((INT)$ketemu2==0) {
                        
                        echo "<td nowrap>$pbln1 s/d. $pbln2</td>";
                        echo "<td >$pkeperluan</td>";
                        
                        echo "</tr>";
                    }else{


                        while ($row2= mysqli_fetch_array($tampil2)) {
                            $ntgl=$row2['tanggal'];
                            $ntgl= date("d-m-Y", strtotime($ntgl));

                            $pkeperluan=$row2['keperluan'];
                            
                            if ($pkosongawal == true) {
                                $ntgl="";
                                $pkeperluan="";
                            }
                            
                            if ($plewat2==false) {
                                echo "<td nowrap>$ntgl</td>";
                                echo "<td >$pkeperluan</td>";
                                
                                echo "</tr>";
                            }else{
                                echo "<tr>";
                                echo "<td nowrap>&nbsp;</td>";
                                echo "<td nowrap>&nbsp;</td>";
                                echo "<td nowrap>&nbsp;</td>";
                                echo "<td nowrap>&nbsp;</td>";
                                echo "<td nowrap>&nbsp;</td>";
                                echo "<td nowrap>&nbsp;</td>";
                                echo "<td nowrap>$ntgl</td>";
                                echo "<td >$pkeperluan</td>";
                                
                                echo "</tr>";
                            }
                            $plewat2=true;
                        }

                    }

                }
            
            }
             
            
            $pjmlcutiskr=0;
            $pjmlcutifree=0;
            
            if (empty($pjmlcutiskr)) $pjmlcutiskr=0;
            if (empty($pjmlcutifree)) $pjmlcutifree=0;
            
            
            
            
            //Cuti Massal , 'ALLHO', 'ALLCHC'
            if (strpos($fjenisid, "00")) {
                
                $query ="select distinct tanggal, keperluan FROM $tmp04 WHERE id_jenis in ('00') ";
                if ($ndivisipilih=="HO") {
                    $query .=" AND karyawanid IN ('ALL', 'ALLHO') ";
                }elseif ($ndivisipilih=="OTC" OR $ndivisipilih=="CHC") {
                    $query .=" AND karyawanid IN ('ALL', 'ALLCHC') ";
                }else{
                    $query .=" AND karyawanid IN ('ALL', 'ALLETH') ";
                }
                $query .=" order by tanggal";
                $tampil_m=mysqli_query($cnmy, $query);
                $ketemu_m= mysqli_num_rows($tampil_m);
                if ((INT)$ketemu_m>0) {
                    echo "<tr>";
                    echo "<td nowrap>&nbsp;</td>";
                    echo "<td nowrap>&nbsp;</td>";
                    echo "<td nowrap>&nbsp;</td>";
                    echo "<td nowrap>&nbsp;</td>";
                    echo "<td nowrap>&nbsp;</td>";
                    echo "<td >Cuti Massal</td>";

                    $ilewat_m=false;
                    while ($row_m= mysqli_fetch_array($tampil_m)) {

                        $ntgl=$row_m['tanggal'];
                        $ntgl= date("d-m-Y", strtotime($ntgl));

                        $pkeperluan=$row_m['keperluan'];

                        if ($ilewat_m==false) {
                            echo "<td nowrap>$ntgl</td>";
                            echo "<td >$pkeperluan</td>";

                            echo "</tr>";

                        }else{
                            echo "<tr>";
                            echo "<td nowrap>&nbsp;</td>";
                            echo "<td nowrap>&nbsp;</td>";
                            echo "<td nowrap>&nbsp;</td>";
                            echo "<td nowrap>&nbsp;</td>";
                            echo "<td nowrap>&nbsp;</td>";
                            echo "<td ></td>";
                            echo "<td nowrap>$ntgl</td>";
                            echo "<td >$pkeperluan</td>";

                            echo "</tr>";
                        }
                        $ilewat_m=true;

                    }
                }
            
            }
            
            
            $no++;
            
        }

    echo "</table>";

    
    echo "<br/><br/>";
    
    
    $free_reguler=0;
    $free_masakerja=0;
    $cuti_reguler=0;
    $cuti_izin=0;
    $cuti_massal=0;
    $free_menikah=0;
    $cuti_menikah=0;
    $free_istlahiran=0;
    $cuti_istlahiran=0;
    $free_ortuwafat=0;
    $cuti_ortuwafat=0;
    
    $sisa_cuti=0;
    
    
    
    if (!empty($pkryid)) {
        $query = "select * from $tmp05";
        $tampiln=mysqli_query($cnmy, $query);
        $ketmeun= mysqli_num_rows($tampiln);
        if ((INT)$ketmeun<=0) {
            $sql = "INSERT INTO $tmp05 (karyawanid, divisiid, jabatanid, tglmasuk, nama_karyawan, jml_thn, jml_bln) "
                    . " select distinct karyawanid, divisiid, jabatanid, tglmasuk, nama_karyawan, jml_thn, jml_bln FROM $tmp07";
            mysqli_query($cnmy, $sql); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        }
    }
    
    echo "<table id='tbltable' border='1' cellspacing='0' cellpadding='1'>";
    
    $query = "select distinct karyawanid, nama_karyawan, tglmasuk, jml_thn, jml_bln FROM $tmp05 ORDER BY nama_karyawan, karyawanid";
    $tampil_s= mysqli_query($cnmy, $query);
    while ($row_s= mysqli_fetch_array($tampil_s)) {
        $pidkaryawan=$row_s['karyawanid'];
        $pnmkaryawan=$row_s['nama_karyawan'];
        
        $ptglmasuk=$row_s['tglmasuk'];
        $pmskrjathn=$row_s['jml_thn'];
        $pmskrjabln=$row_s['jml_bln'];

        $pmasakerja="0";
        if ((INT)$pmskrjathn>0) $pmasakerja=$pmskrjathn." tahun";
        else{
            if ((INT)$pmskrjabln>0) $pmasakerja=$pmskrjabln." bulan";
        }

        if ($ptglmasuk=="0000-00-00") $ptglmasuk="";
        if (!empty($ptglmasuk)) $ptglmasuk=date("d/m/Y", strtotime($ptglmasuk));
        
        $pclsbreak ="class='fbreak'";
        
        $p2020_cuti20=0;
        $p2020_cuti_janmrt21=0;
        $p2020_cuti_apr21=0;
        $p2020_cuti_janapr21=0;
        $p2020_free_tbh=0;
        $p2020_free_reg=0;
        $p2020_free_masker=0;
        $p2020_cuti_sisa=0;
        $p2020_cuti_dari_it=0;
        
        if ((INT)$ptahun<=2021) {
            
            $query = "select cuti_2020, cuti_jan_maret_2021, cuti_april_2021,"
                    . " free_tambahan, free_reguler, free_masakerja FROM hrd.t_cuti_it WHERE karyawanid='$pidkaryawan'";
            $tampil_1=mysqli_query($cnmy, $query);
            $ketemu_1= mysqli_num_rows($tampil_1);
            if ((INT)$ketemu_1>0) {
                $row_1= mysqli_fetch_array($tampil_1);
                
                $p2020_cuti20=$row_1['cuti_2020'];
                $p2020_cuti_janmrt21=$row_1['cuti_jan_maret_2021'];
                $p2020_cuti_apr21=$row_1['cuti_april_2021'];
                
                $p2020_free_tbh=$row_1['free_tambahan'];
                $p2020_free_reg=$row_1['free_reguler'];
                $p2020_free_masker=$row_1['free_masakerja'];
                
                if (empty($p2020_cuti20)) $p2020_cuti20=0;
                if (empty($p2020_cuti_janmrt21)) $p2020_cuti_janmrt21=0;
                if (empty($p2020_cuti_apr21)) $p2020_cuti_apr21=0;
                if (empty($p2020_free_tbh)) $p2020_free_tbh=0;
                if (empty($p2020_free_reg)) $p2020_free_reg=0;
                if (empty($p2020_free_masker)) $p2020_free_masker=0;
                
                $p2020_free_reg=(INT)$p2020_free_reg+(INT)$p2020_free_tbh;
                
                
                $p2020_cuti_sisa=(INT)$p2020_free_reg-(INT)$p2020_cuti20;
                
                $p2020_cuti_janapr21=(INT)$p2020_cuti_janmrt21+(INT)$p2020_cuti_apr21;
                
                if (empty($p2020_cuti_janapr21)) $p2020_cuti_janapr21=0;
                
                /*
                $p2020_free_masker=0;
                $p2020_cuti_sisa=10;
                $p2020_cuti_janmrt21=11;
                $p2020_cuti_janapr21=$p2020_cuti_janmrt21;
                //*/
                
                if ((INT)$p2020_cuti_sisa>(INT)$p2020_cuti_janmrt21) {
                    $p2020_cuti_sisa=$p2020_cuti_janmrt21;
                }else{
                    
                }
                
                $p2020_cuti_sisa=(INT)$p2020_cuti_sisa+(INT)$p2020_free_masker;
                
            }
            
            
            // cuti dibuat minus
            
            $p2020_cuti_janapr21=-1*(INT)$p2020_cuti_janapr21;
             
            //sisa cuti tahun sebelumnya
            echo "<tr $pclsbreak>";
            echo "<td nowrap>$pnmkaryawan</td>";
            echo "<td>Sisa Cuti Tahun $ptahunsebelum</td>";

            echo "<td nowrap align='right'>$p2020_cuti_sisa</td>";

            echo "</tr>";
            
            $pnmkaryawan="";
            $pclsbreak="";
            
            if ((INT)$ptahun==2021 AND (INT)$p2020_cuti_janapr21<>0) {
                //cuti tahun 2021 manual
                echo "<tr>";
                echo "<td nowrap>$pnmkaryawan</td>";
                echo "<td>Cuti Tahun $ptahun (Form Manual) s/d. April 2021</td>";

                echo "<td nowrap align='right'>$p2020_cuti_janapr21</td>";

                echo "</tr>";
            }
            
            $p2020_cuti_dari_it=(INT)$p2020_cuti_sisa+(INT)$p2020_cuti_janapr21;
        
        }



            
        
        $query = "select distinct id_jenis, jumlah, jml_cuti FROM $tmp07 WHERE karyawanid='$pidkaryawan'";
        $tampil1= mysqli_query($cnmy, $query);
        while ($row1= mysqli_fetch_array($tampil1)) {
        
            $pidjenis=$row1['id_jenis'];
            $pjumlah=$row1['jumlah'];
            $pjmlcuti=$row1['jml_cuti'];

            if (empty($pjumlah)) $pjumlah=0;
            if (empty($pjmlcuti)) $pjmlcuti=0;
            
            $pjmlcuti=-1*(INT)$pjmlcuti;
            
            if ($pidjenis=="01") {//reguler
                $free_reguler=$pjumlah;
                $cuti_reguler=$pjmlcuti;
            }elseif ($pidjenis=="11") {//tambahan masa kerja
                $free_masakerja=$pjumlah;
            }elseif ($pidjenis=="03") {//izin
                $cuti_izin=$pjmlcuti;
            }elseif ($pidjenis=="00") {//massal
                $cuti_massal=$pjmlcuti;
            }elseif ($pidjenis=="07") {//cuti menikah
                $free_menikah=$pjumlah;
                $cuti_menikah=$pjmlcuti;
            }elseif ($pidjenis=="08") {//cuti istri melahirkan
                $free_istlahiran=$pjumlah;
                $cuti_istlahiran=$pjmlcuti;
            }elseif ($pidjenis=="09") {//cuti istri melahirkan
                $free_ortuwafat=$pjumlah;
                $cuti_ortuwafat=$pjmlcuti;
            }
            
        
        }
        
        //free reguler
        echo "<tr $pclsbreak>";
        echo "<td nowrap>$pnmkaryawan</td>";
        echo "<td>Free Cuti Reguler $ptahun</td>";
        echo "<td nowrap align='right'>$free_reguler</td>";
        echo "</tr>";
        
        $pnmkaryawan="";
        $pclsbreak="";
        
        //free tambahan masa kerja
        if ((INT)$free_masakerja>0) {
            echo "<tr>";
            echo "<td nowrap></td>";
            echo "<td>Free Cuti Masa Kerja $pmasakerja</td>";
            echo "<td nowrap align='right'>$free_masakerja</td>";
            echo "</tr>";
        }else{
            $free_masakerja=0;
        }
        
        //cuti massal
        if ((INT)$cuti_massal<>0) {
            echo "<tr>";
            echo "<td nowrap></td>";
            echo "<td>Cuti Massal </td>";
            echo "<td nowrap align='right'>$cuti_massal</td>";
            echo "</tr>";
        }
        
        //cuti reguler
        echo "<tr>";
        echo "<td nowrap></td>";
        echo "<td>Cuti Reguler </td>";
        echo "<td nowrap align='right'>$cuti_reguler</td>";
        echo "</tr>";
        
        //cuti izin
        if ((INT)$cuti_izin<>0) {
            echo "<tr>";
            echo "<td nowrap></td>";
            echo "<td>Izin </td>";
            echo "<td nowrap align='right'>$cuti_izin</td>";
            echo "</tr>";
        }
        
        //cuti menikah
        if ((INT)$cuti_menikah<>0) {
            echo "<tr>";
            echo "<td nowrap></td>";
            echo "<td>Free Cuti Menikah </td>";
            echo "<td nowrap align='right'>$free_menikah</td>";
            echo "</tr>";
            
            echo "<tr>";
            echo "<td nowrap></td>";
            echo "<td>Cuti Menikah </td>";
            echo "<td nowrap align='right'>$cuti_menikah</td>";
            echo "</tr>";
            
            $totsisa=(INT)$free_menikah+(INT)$cuti_menikah;
            if ((INT)$totsisa<=(INT)$free_menikah) {
                $free_menikah=0;
                $cuti_menikah=0;
            }
        }else{
            $free_menikah=0;
        }
        
        //cuti istri melahirkan
        if ((INT)$cuti_istlahiran<>0) {
            echo "<tr>";
            echo "<td nowrap></td>";
            echo "<td>Free Cuti Istri Melahirkan </td>";
            echo "<td nowrap align='right'>$free_istlahiran</td>";
            echo "</tr>";
            
            echo "<tr>";
            echo "<td nowrap></td>";
            echo "<td>Cuti Istri Melahirkan </td>";
            echo "<td nowrap align='right'>$cuti_istlahiran</td>";
            echo "</tr>";
            
            $totsisa=(INT)$free_istlahiran+(INT)$cuti_istlahiran;
            if ((INT)$totsisa<=(INT)$free_istlahiran) {
                $free_istlahiran=0;
                $cuti_istlahiran=0;
            }
        }else{
            $free_istlahiran=0;
        }
        
        //cuti orang tua meninggal
        if ((INT)$cuti_ortuwafat<>0) {
            echo "<tr>";
            echo "<td nowrap></td>";
            echo "<td>Free Orang tua meninggal </td>";
            echo "<td nowrap align='right'>$free_ortuwafat</td>";
            echo "</tr>";
            
            echo "<tr>";
            echo "<td nowrap></td>";
            echo "<td>Cuti Orang tua meninggal </td>";
            echo "<td nowrap align='right'>$cuti_ortuwafat</td>";
            echo "</tr>";
            
            $totsisa=(INT)$free_ortuwafat+(INT)$cuti_ortuwafat;
            if ((INT)$totsisa<=(INT)$free_ortuwafat) {
                $free_ortuwafat=0;
                $cuti_ortuwafat=0;
            }
            
        }else{
            $free_ortuwafat=0;
        }
        
    
    
        $sisa_cuti=0;
    
        $jml_cuti=(INT)$cuti_reguler+(INT)$cuti_izin+(INT)$cuti_massal+(INT)$cuti_menikah+(INT)$cuti_istlahiran+(INT)$cuti_ortuwafat;
        $jml_free=(INT)$free_reguler+(INT)$free_masakerja+(INT)$free_menikah+(INT)$free_istlahiran+(INT)$free_ortuwafat;
        
        $sisa_cuti=(INT)$jml_free+(INT)$jml_cuti;//karena jml cuti minus jadi ditambah kecuali kalau plus jml cutinya
        
        
        $pjmlsisacuti_sbl_sdh=(INT)$sisa_cuti+(INT)$p2020_cuti_dari_it;

                
        echo "<tr style='font-weight:bold;'>";
        echo "<td nowrap>&nbsp;</td>";
        echo "<td>Sisa Cuti Tahun $ptahun</td>";
        echo "<td nowrap align='right'>$pjmlsisacuti_sbl_sdh</td>";
        echo "</tr>";
        
        
        //
        echo "<tr>";
        echo "<td nowrap>&nbsp;</td>";
        echo "<td>&nbsp;</td>";
        echo "<td nowrap align='right'>&nbsp;</td>";
        echo "</tr>";
            
    }
    
    
    echo "</table>";
    
    echo "<br/><br/><br/><br/><br/>";
    
    ?>

</BODY>

    <!-- jQuery -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="build/js/custom.min.js"></script>

   
    <style>
        #myBtn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 30px;
            z-index: 99;
            font-size: 18px;
            border: none;
            outline: none;
            background-color: red;
            color: white;
            cursor: pointer;
            padding: 15px;
            border-radius: 4px;
            opacity: 0.5;
        }

        #myBtn:hover {
            background-color: #555;
        }

    </style>

    <style>
        #tbltable {
            border-collapse: collapse;
        }
        th {
            font-size : 16px;
            padding:5px;
            background-color: #ccccff;
        }
        tr td {
            font-size : 12px;
        }
        tr td {
            padding : 3px;
        }
        tr:hover {background-color:#f5f5f5;}
        thead tr:hover {background-color:#cccccc;}
        .fbreak {
            background-color:#f5f5f5;
        }
    </style>

    <script>
        // SCROLL
        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {scrollFunction()};
        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                document.getElementById("myBtn").style.display = "block";
            } else {
                document.getElementById("myBtn").style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
        // END SCROLL
    </script>
    
    <script>
        function LiatNotes(ests, enourut, eidkry, etgl, edoktid){
            $.ajax({
                type:"post",
                url:"module/dkd/dkd_reportpalnreal/lihatnotes.php?module=viewnotes",
                data:"usts="+ests+"&unourut="+enourut+"&uidkry="+eidkry+"&utgl="+etgl+"&udoktid="+edoktid,
                success:function(data){
                    $("#myModal").html(data);
                }
            });
        }
        function LiatKomentar(ests, enourut, eidkry, etgl, edoktid){
            $.ajax({
                type:"post",
                url:"module/dkd/dkd_reportpalnreal/lihatkomentar.php?module=viewnotes",
                data:"usts="+ests+"&unourut="+enourut+"&uidkry="+eidkry+"&utgl="+etgl+"&udoktid="+edoktid,
                success:function(data){
                    $("#myModal").html(data);
                }
            });
        }
    </script>

</HTML>

<?PHP
hapusdata:
    mysqli_query($cnmy, "drop TEMPORARY table if EXISTS $tmp01");
    mysqli_query($cnmy, "drop TEMPORARY table if EXISTS $tmp02");
    mysqli_query($cnmy, "drop TEMPORARY table if EXISTS $tmp03");
    mysqli_query($cnmy, "drop TEMPORARY table if EXISTS $tmp04");
    mysqli_query($cnmy, "drop TEMPORARY table if EXISTS $tmp05");
    mysqli_query($cnmy, "drop TEMPORARY table if EXISTS $tmp06");
    mysqli_query($cnmy, "drop TEMPORARY table if EXISTS $tmp07");
    mysqli_close($cnmy);
?>