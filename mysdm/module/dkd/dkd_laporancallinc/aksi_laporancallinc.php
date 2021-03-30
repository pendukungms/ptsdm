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


$puserid=$_SESSION['USERID'];
$now=date("mdYhis");
$tmp01 =" dbtemp.tmplapcallinc01_".$puserid."_$now ";
$tmp02 =" dbtemp.tmplapcallinc02_".$puserid."_$now ";


$pkryid = $_POST['cb_karyawan']; 
$pbln = $_POST['e_bulan'];

$pbulan = date('Y-m', strtotime($pbln));

$query = "select a.nama, a.jabatanId as jabatanid, b.nama as nama_jabatan from hrd.karyawan as a 
    LEFT join hrd.jabatan as b on a.jabatanId=b.jabatanId 
    where a.karyawanid='$pkryid'";
$tampilk=mysqli_query($cnmy, $query);
$rowk=mysqli_fetch_array($tampilk);
$pnamakarywanpl=$rowk['nama'];

$sql = "select a.idinput, a.jabatanid, a.tanggal, a.ketid, b.nama as nama_ket, a.real_user1, a.real_date1,
    b.pointMR, b.pointSpv, b.pointDM 
    FROM hrd.dkd_new0 as a JOIN hrd.ket as b on a.ketid=b.ketId 
    WHERE a.karyawanid='$pkryid'";
$sql .=" AND LEFT(a.tanggal,7)= '$pbulan'";
$query = "create TEMPORARY table $tmp01 ($sql)"; 
mysqli_query($cnmy, $query);
$erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }


$query = "select a.jabatanid as jabatanid, b.nama as nama_jabatan from $tmp01 as a 
    LEFT join hrd.jabatan as b on a.jabatanid=b.jabatanId ";
$tampilk=mysqli_query($cnmy, $query);
$rowk=mysqli_fetch_array($tampilk);
$pnamajabatan=$rowk['nama_jabatan'];
$pjabatanid=$rowk['jabatanid'];

$query = "ALTER TABLE $tmp01 ADD COLUMN jpoint DECIMAL(20,2), ADD totakv INT(4), ADD totvisit INT(4), ADD totjv INT(4), ADD totec INT(4), ADD sudahreal VARCHAR(1)";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }


if ($pjabatanid=="10" OR $pjabatanid=="18") {
    //$query = "UPDATE $tmp01 as a JOIN hrd.ket as b on a.ketid=b.ketId SET a.jpoint=b.pointSpv";
    $query = "UPDATE $tmp01 SET jpoint=pointSpv";
    mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
}elseif ($pjabatanid=="08") {
    //$query = "UPDATE $tmp01 as a JOIN hrd.ket as b on a.ketid=b.ketId SET a.jpoint=b.pointDM";
    $query = "UPDATE $tmp01 SET jpoint=pointDM";
    mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
}elseif ($pjabatanid=="15") {
    //$query = "UPDATE $tmp01 as a JOIN hrd.ket as b on a.ketid=b.ketId SET a.jpoint=b.pointMR";
    $query = "UPDATE $tmp01 SET jpoint=pointMR";
    mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
}


$query = "UPDATE $tmp01 SET totakv=1";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

$query = "UPDATE $tmp01 as a JOIN (select idinput, count(distinct dokterid) as jml FROM 
    hrd.dkd_new1 WHERE IFNULL(jenis,'')='' GROUP BY 1) as b on a.idinput=b.idinput SET a.totvisit=b.jml";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

$query = "UPDATE $tmp01 as a JOIN (select idinput, count(distinct dokterid) as jml FROM 
    hrd.dkd_new1 WHERE IFNULL(jenis,'') IN ('EC') GROUP BY 1) as b on a.idinput=b.idinput SET a.totec=b.jml";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }


$query = "UPDATE $tmp01 as a JOIN (select idinput, count(distinct dokterid) as jml FROM 
    hrd.dkd_new1 WHERE IFNULL(jenis,'') IN ('JV') GROUP BY 1) as b on a.idinput=b.idinput SET a.totjv=b.jml";
mysqli_query($cnmy, $query); $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }


    $bulan_array=array(1=> "Januari", "Februari", "Maret", "April", "Mei", 
        "Juni", "Juli", "Agustus", "September", 
        "Oktober", "November", "Desember");

    $hari_array = array(
        'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    );

?>

<HTML>
<HEAD>
  <TITLE>Laporan Call Incentive</TITLE>
    <meta http-equiv="Expires" content="Mon, 01 Jan 2030 1:00:00 GMT">
    <meta http-equiv="Pragma" content="no-cache">
    <?php header("Cache-Control: no-cache, must-revalidate"); ?>
    <link rel="shortcut icon" href="images/icon.ico" />
    <style> .str{ mso-number-format:\@; } </style>
</HEAD>
<script>
</script>

<BODY onload="initVar()">

    <button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>

    <?PHP

    echo "<b>Nama : $pnamakarywanpl - $pkryid</b><br/>";
    echo "<b>Jabatan : $pnamajabatan</b><br/><br/>";

    echo "<table border='1' cellspacing='0' cellpadding='1'>";
        echo "<tr>";
            $header_ = add_space('Tanggal',40);
            echo "<th align='left'><small>$header_</small></th>";
            $header_ = add_space('Keterangan',60);
            echo "<th align='left'><small>$header_</small></th>";
            $header_ = add_space('Call',40);
            echo "<th align='left'><small>$header_</small></th>";
            $header_ = add_space('Point',40);
            echo "<th align='left'><small>$header_</small></th>";
        echo "</tr>";

        $no=1;
        $query = "select distinct idinput, tanggal, jpoint, totakv, totvisit, totjv, totec, sudahreal, nama_ket from $tmp01 order by tanggal";
        $tampil0=mysqli_query($cnmy, $query);
        while ($row0=mysqli_fetch_array($tampil0)) {
            $cidinput=$row0['idinput'];
            $ntgl=$row0['tanggal'];
            $ntotakv=$row0['totakv'];
            $ntotvisit=$row0['totvisit'];
            $ntotec=$row0['totec'];
            $ntotjv=$row0['totjv'];
            $nsudahreal=$row0['sudahreal'];
            $nnamaket=$row0['nama_ket'];
            $ntotpoint=$row0['jpoint'];

            $ntotpoint=number_format($ntotpoint,0,"","");
            $ntotvisit=number_format($ntotvisit,0,"","");

            if (empty($ntotakv)) $ntotakv=1;
            if (empty($ntotvisit)) $ntotvisit=0;
            if (empty($ntotec)) $ntotec=0;

            $ntanggal = date('l d F Y', strtotime($ntgl));

            $xhari = $hari_array[(INT)date('w', strtotime($ntgl))];
            $xtgl= date('d', strtotime($ntgl));
            $xbulan = $bulan_array[(INT)date('m', strtotime($ntgl))];
            $xthn= date('Y', strtotime($ntgl));

            $pkettotal="$ntotakv Activity, $ntotvisit Visit";
            if ((INT)$ntotec>0) {
                $pkettotal="$ntotakv Activity, $ntotvisit Visit, $ntotec Extra Call";
            }

            echo "<tr>";
            echo "<td nowrap>$xhari, $xtgl $xbulan $xthn</td>";
            echo "<td nowrap>$nnamaket</td>";
            echo "<td nowrap align='right'>$ntotvisit</td>";
            echo "<td nowrap align='right'>$ntotpoint</td>";
            echo "</tr>";

            $no++;
        }

    echo "</table>";



    ?>

</BODY>

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


</HTML>

<?PHP
hapusdata:
    mysqli_query($cnmy, "drop TEMPORARY table if EXISTS $tmp01");
    mysqli_query($cnmy, "drop TEMPORARY table if EXISTS $tmp02");
    mysqli_close($cnmy);
?>