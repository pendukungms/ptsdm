<?php
    session_start();
    include "../../config/koneksimysqli.php";
    include "../../config/fungsi_sql.php";
    
    $module=$_GET['module'];
    $act=$_GET['act'];
    $idmenu=$_GET['idmenu'];
    $dbname = "dbmaster";
    $dbname2 = "dbimages";

    $idgam="";
    if (isset($_GET['idgam']))
        $idgam=$_GET['idgam'];
    

//HAPUS DATA
if ($module=='entrybrservicekendaraan' AND $act=='hapus')
{
    $kethapus= $_GET['kethapus'];
    if ($kethapus=="null") $kethapus="";
    if (!empty($kethapus)) $kethapus =", Ket Hapus : ".$kethapus;
    
    mysqli_query($cnmy, "update $dbname.t_service_kendaraan set stsnonaktif='Y', keterangan=CONCAT(keterangan,'$kethapus',', $_SESSION[IDCARD], ', NOW()) WHERE idservice='$_GET[id]'");
    
    mysqli_close($cnmy);
    
    header('location:../../media.php?module='.$module.'&idmenu='.$idmenu.'&act=complt');
}

elseif ($module=='entrybrservicekendaraan' AND $act=='hapusgambar')
{
    $kodenya=$_GET['id'];
    if (!empty($idgam)) {
        mysqli_query($cnmy, "delete from $dbname2.img_service_kendaraan WHERE nourut='$idgam' and idservice='$kodenya'");
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
        
        mysqli_close($cnmy);
    }
    header('location:../../media.php?module='.$module.'&idmenu='.$idmenu.'&act=uploaddok&id='.$kodenya);
}

elseif ($module=='entrybrservicekendaraan' AND $act=='uploaddok')
{
    include "../../config/fungsi_image.php";
    $kodenya=$_POST['e_id'];
    
    // save gambar
    $maximg=10;
    for ($i=1;$i<=$maximg;$i++) {
        $nmimg="image".$i;
        $lokasi_file    = $_FILES[$nmimg]['tmp_name'];
        $tipe_file      = $_FILES[$nmimg]['type'];
        $nama_file      = $_FILES[$nmimg]['name'];
        $acak           = rand(1,99);
        $nama_file_unik = $acak.$nama_file; 
        $nama_file_unik = strtolower(str_replace(" ","_",$nama_file_unik));

        
        if (!empty($lokasi_file)) {
            
            $file = saveimagetemp($nmimg, $nama_file_unik, "800");

            if (!empty($file)){

                mysqli_query($cnmy, "insert into $dbname2.img_service_kendaraan (idservice, gambar) values ('$kodenya', '$file')");
                $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
            }
        }
        
        $lokasi_file="";
        $nama_file_unik="";
    }
    
    mysqli_close($cnmy);
    
    header('location:../../media.php?module='.$module.'&idmenu='.$idmenu.'&act=uploaddok&id='.$kodenya);
}

elseif ($module=='entrybrservicekendaraan')
{
    $pkaryawan = $_POST['e_idkaryawan'];

    //AREA dan DIVISI SESUAI JABATAN
    
    $pjabatanid=$_POST['e_jabatan'];
    if (empty($pjabatanid))
        $pjabatanid = getfieldcnmy("select jabatanId as lcfields from dbmaster.t_karyawan_posisi where karyawanId='$pkaryawan'");
    if (empty($pjabatanid))
        $pjabatanid = getfieldcnmy("select jabatanId as lcfields from hrd.karyawan where karyawanId='$pkaryawan'");
    
    $pdivprodid="";
    if (isset($_POST['cb_divisi']))
        $pdivprodid = trim($_POST['cb_divisi']);
    
    if (empty($pdivprodid)) $pdivprodid = getfieldcnmy("select divisiId as lcfields from hrd.karyawan where karyawanId='$pkaryawan'");
    if (empty($pdivprodid)) $pdivprodid = getfieldcnmy ("select divisiId2 as lcfields from hrd.karyawan where karyawanId='$pkaryawan'");
    if (empty($pdivprodid)) $pdivprodid = "HO";
    if ($pdivprodid=="EP") $pdivprodid = "HO";
    if ($pdivprodid=="00000") $pdivprodid = "HO";
    
    if ((int)$pjabatanid==15) {
        $jmldiv = getfieldcnmy("select COUNT(DISTINCT divisiid) as lcfields from MKT.imr0 where aktif='Y' AND karyawanid='$pkaryawan'");
        if ((int)$jmldiv>1) {
            $pdivprodid="CAN";
        }
    }
    
    $pidcabang = "";
    $pareaid = "";
    
    if (isset($_POST['e_idarea']))
        $pareaid = trim($_POST['e_idarea']);
    
    if (!empty($pareaid)) {
        $areacabaang = explode(",",$pareaid);
        if (isset($areacabaang[0])) $pidcabang = trim($areacabaang[0]);
        if (isset($areacabaang[1])) $pareaid = trim($areacabaang[1]);
    }
    
    $jmldiv=1;
    if (empty($pareaid) AND $pdivprodid!="OTC") {
        if ((int)$pjabatanid==10 OR (int)$pjabatanid==18) {
            $sql="select DISTINCT icabangid, areaid from MKT.ispv0 WHERE aktif='Y' AND karyawanid='$pkaryawan' LIMIT 1";
            $tampil=mysqli_query($cnmy, $sql);
            $a = mysqli_fetch_array($tampil);
            $pidcabang = $a['icabangid'];
            $pareaid = $a['areaid'];
            $pdivprodid="CAN";
        }elseif ((int)$pjabatanid==20) {
            $pidcabang = getfieldcnmy("select icabangid as lcfields from MKT.ism0 where aktif='Y' AND karyawanid='$pkaryawan' limit 1");
            $pareaid = getfieldcnmy("select areaId as lcfields from MKT.iarea where aktif='Y' AND iCabangId='$pidcabang'");
            $pdivprodid="CAN";
        }elseif ((int)$pjabatanid==8) {
            $pidcabang = getfieldcnmy("select icabangid as lcfields from MKT.idm0 where aktif='Y' AND karyawanid='$pkaryawan' limit 1");
            $pareaid = getfieldcnmy("select areaId as lcfields from MKT.iarea where aktif='Y' AND iCabangId='$pidcabang'");
            $pdivprodid="CAN";
        }elseif ((int)$pjabatanid==15) {
            $sql="select DISTINCT icabangid, areaid from MKT.imr0 WHERE aktif='Y' AND karyawanid='$pkaryawan' LIMIT 1";
            $tampil=mysqli_query($cnmy, $sql);
            $a = mysqli_fetch_array($tampil);
            $pidcabang = $a['icabangid'];
            $pareaid = $a['areaid'];
            $jmldiv = getfieldcnmy("select COUNT(DISTINCT divisiid) as lcfields from MKT.imr0 where aktif='Y' AND karyawanid='$pkaryawan'");
            if ((int)$jmldiv>1) {
                $pdivprodid="CAN";
            }
        }else{
            $pidcabang = getfieldcnmy("select iCabangId as lcfields from dbmaster.t_karyawan_posisi where karyawanId='$pkaryawan'");
            $pareaid = getfieldcnmy("select areaId as lcfields from dbmaster.t_karyawan_posisi where karyawanId='$pkaryawan'");
        }
    }else{
        if ($pdivprodid=="OTC") {
            $pidcabang = getfieldcnmy("select iCabangId as lcfields from dbmaster.t_karyawan_posisi where karyawanId='$pkaryawan'");
            $pareaid = getfieldcnmy("select areaId as lcfields from dbmaster.t_karyawan_posisi where karyawanId='$pkaryawan'");
        }
    }
    if (empty(trim($pidcabang))) { $pidcabang="0000000001"; $pareaid="0000000001";}
    
    
    //echo "$pdivprodid, $pidcabang, $pareaid, $pjabatanid";exit;
    
    //END AREA dan DIVISI SESUAI JABATAN

    $date1 = str_replace('/', '-', $_POST['e_tgl']);
    $pp01 =  date("Y-m-d", strtotime($date1));
    
    $pnopol = $_POST['e_nopol'];
    $pket = $_POST['e_ket'];
    if (!empty($pket)) $pket = str_replace("'", " ", $pket);
    
    $pkm=str_replace(",","", $_POST['e_km']);
    $pjumlah=str_replace(",","", $_POST['e_totalsemua']);
    
    $pjabatanid = getfieldcnit("select jabatanId as lcfields from dbmaster.t_karyawan_posisi where karyawanId='$pkaryawan'");
    if (empty($pjabatanid))
        $pjabatanid = getfieldcnit("select jabatanId as lcfields from hrd.karyawan where karyawanId='$pkaryawan'");
        
    
    
    $patasan1 = $_POST['e_atasan'];
    $patasan2 = $_POST['e_atasan2'];
    $patasan3 = $_POST['e_atasan3'];
    $patasan4 = $_POST['e_atasan4'];
    $pelevel = trim($_POST['e_lvl']);
    
    $pwilayah="01";
    $pcabwil=  substr($pidcabang, 7,3);
    if ($pidcabang=="0000000001")
        $pwilayah="01";
    else{
        $reg=  getfieldcnit("select distinct region as lcfields from dbmaster.icabang where iCabangId='$pidcabang'");
        if ($pdivprodid=="OTC") {
            if ($reg=="B")
                $pwilayah="04";
            else
                $pwilayah="05";
        }else{
            if ($reg=="B")
                $pwilayah="02";
            else
                $pwilayah="03";
        }
    }
    
    $pwilgabungan=$pwilayah."-".$pcabwil;
    
    if ($act=="input") {
        $sql=  mysqli_query($cnmy, "select MAX(RIGHT(idservice,7)) as NOURUT from $dbname.t_service_kendaraan");
        $ketemu=  mysqli_num_rows($sql);
        $awal=7; $urut=1; $kodenya=""; $periode=date('Ymd');
        if ($ketemu>0){
            $o=  mysqli_fetch_array($sql);
            $urut=$o['NOURUT']+1;
            $jml=  strlen($urut);
            $awal=$awal-$jml;
            $kodenya="SVC".str_repeat("0", $awal).$urut;
        }else{
            $kodenya=$_POST['e_id'];
        }
    }else{
        $kodenya=$_POST['e_id'];
    }
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
    
    $nobrid="52";
    $coadet = getfieldcnit("select COA4 as lcfields from dbmaster.posting_coa_rutin where divisi='$pdivprodid' AND nobrid='$nobrid'");
    
    
    
    if (empty(trim($kodenya))) { echo "kode kosong"; exit; }
    //if (empty(trim($pdivprodid))) { echo "divisi kosong"; exit; }
    //if (empty(trim($pareaid))) { echo "area kosong"; exit; }
    if (empty(trim($pp01))) { echo "periode kosong"; exit; }
    
    
    if ($act=='input') {
        $query="insert into $dbname.t_service_kendaraan (idservice, karyawanid, icabangid, areaid, tgl, nobrid, kode)values"
                . "('$kodenya', '$pkaryawan', '$pidcabang', '$pareaid', Current_Date(), '$nobrid', 5)";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
    }
    
    
    
    $query = "update $dbname.t_service_kendaraan set karyawanid='$pkaryawan', "
             . " icabangid='$pidcabang', "
             . " areaid='$pareaid', "
             . " tglservice='$pp01', "
             . " keterangan='$pket', "							 
             . " jabatanid='$pjabatanid', "	
             . " divisi='$pdivprodid', "
             . " jumlah='$pjumlah', "
             . " km='$pkm', "
             . " nopol='$pnopol', "
             . " nobrid='$nobrid', "
             . " KODEWILAYAH='$pwilgabungan', "
             . " COA4='$coadet', "
             . " atasan1='$patasan1', "
             . " atasan2='$patasan2', "
             . " atasan3='$patasan3', "
             . " atasan4='$patasan4', "
             . " userid='$_SESSION[IDCARD]' where "
            . " idservice='$kodenya'"; 
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_query($cnmy, "delete from $dbname.t_service_kendaraan where idservice='$kodenya'"); echo $erropesan; exit; }
    
    
    $query = "";
    if ($pelevel=="FF2") {
        $query = "update $dbname.t_service_kendaraan set atasan1='$pkaryawan', tgl_atasan1=NOW() WHERE "
                . " idservice='$kodenya'";
    }elseif ($pelevel=="FF3") {
        $query = "update $dbname.t_service_kendaraan set atasan1='$pkaryawan', tgl_atasan1=NOW(), atasan2='$pkaryawan', tgl_atasan2=NOW() WHERE "
                . " idservice='$kodenya'";
    }elseif ($pelevel=="FF4") {
        $query = "update $dbname.t_service_kendaraan set atasan3='$pkaryawan', tgl_atasan3=NOW(), "
                . " atasan1='$pkaryawan', tgl_atasan1=NOW(), atasan2='$pkaryawan', tgl_atasan2=NOW() WHERE idservice='$kodenya'";
    }else{
        $nolevel=0;
        if (trim(substr($pelevel, 0, 2)=="FF")) {
            if (!empty(substr($pelevel, 2, 2))) {
                $nolevel=(int)substr($pelevel, 2, 2);
                if ($nolevel>4) {
                    $query = "update $dbname.t_service_kendaraan set atasan4='$pkaryawan', atasan3='$pkaryawan', tgl_atasan3=NOW(), "
                            . " atasan1='$pkaryawan', tgl_atasan1=NOW(), atasan2='$pkaryawan', tgl_atasan2=NOW() WHERE idservice='$kodenya'";
                }
            }
        }
    }
    if (!empty($query)) {
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
    }
    
    
    if (trim($pdivprodid)=="OTC") {
        $query = "update $dbname.t_service_kendaraan set divi='OTC', icabangid_o='$pidcabang', areaid_o='$pareaid'  where idservice='$kodenya'";
        mysqli_query($cnmy, $query);
    }
    
    if ( (int)$pjabatanid==38) {
        $query = "update $dbname.t_service_kendaraan set atasan1='$patasan2', tgl_atasan1=NOW() where idservice='$kodenya'";
        mysqli_query($cnmy, $query);
        
        
        $query = "SELECT distinct karyawanid, gsm FROM dbmaster.t_karyawan_app_gsm where karyawanid='$pkaryawan'";
        $ketemu= mysqli_num_rows(mysqli_query($cnmy, $query));
        if ($ketemu>0) {
            $ats= mysqli_fetch_array(mysqli_query($cnmy, $query));
            $atasangsm=$ats["gsm"];
            $query = "update $dbname.t_service_kendaraan set atasan1='', tgl_atasan1=NOW(),"
                    . "atasan2='', tgl_atasan2=NOW(), atasan3='', tgl_atasan3=NOW(), atasan4='$atasangsm' WHERE idservice='$kodenya'";
            mysqli_query($cnmy, $query);
        }
        
    }
        
    
    //MR jika SPV/AM nya NN
    if ((int)$pjabatanid==15) {
        $query = "SELECT distinct karyawanid FROM dbmaster.t_karyawan_apv where status='SPV' and karyawanid='$patasan1'";
        $ketemu= mysqli_num_rows(mysqli_query($cnmy, $query));
        if ($ketemu>0) {
            $query = "update $dbname.t_service_kendaraan set tgl_atasan1=NOW() WHERE idservice='$kodenya'";
            mysqli_query($cnmy, $query);
        }
    }
    
    
    //AM/SPV jika DM nya NN
    if ((int)$pjabatanid==10 OR (int)$pjabatanid==18) {
        $query = "SELECT distinct karyawanid FROM dbmaster.t_karyawan_apv where status='DM' and karyawanid='$patasan2'";
        $ketemu= mysqli_num_rows(mysqli_query($cnmy, $query));
        if ($ketemu>0) {
            $query = "update $dbname.t_service_kendaraan set tgl_atasan2=NOW() WHERE idservice='$kodenya'";
            mysqli_query($cnmy, $query);
        }
    }
    
    mysqli_close($cnmy);
    
    if ($act=="input")
        header('location:../../media.php?module='.$module.'&idmenu='.$idmenu.'&act=complete');
    else
        header('location:../../media.php?module='.$module.'&idmenu='.$idmenu.'&act=complete');
    
}
    
?>
