<?php

    session_start();
    include "../../config/koneksimysqli.php";
    include "../../config/fungsi_sql.php";
    
    $module=$_GET['module'];
    $act=$_GET['act'];
    $idmenu=$_GET['idmenu'];
    
    //$cnmy=$cnit;
    $dbname = "dbmaster";
    
// Hapus 
if ($module=='spdotc' AND $act=='hapus')
{   
    
    mysqli_query($cnmy, "update $dbname.t_spg_gaji_br0 set nodivisi=null WHERE nodivisi IN "
            . " (select distinct IFNULL(nodivisi,'') FROM $dbname.t_suratdana_br WHERE idinput='$_GET[id]' AND subkode='02')");
    
    mysqli_query($cnmy, "update $dbname.t_suratdana_br set stsnonaktif='Y', userid='$_SESSION[IDCARD]' WHERE idinput='$_GET[id]'");
    mysqli_query($cnmy, "DELETE FROm $dbname.t_suratdana_br1 WHERE idinput='$_GET[id]'");
    mysqli_query($cnmy, "DELETE FROm $dbname.t_suratdana_br_d WHERE idinput='$_GET[id]'");
    
    mysqli_close($cnmy);
    
    header('location:../../media.php?module='.$module.'&idmenu='.$idmenu.'&act=complt');
}
elseif ($module=='spdotc')
{
    if (empty($_SESSION['JMLRECSPD'])) $_SESSION['JMLRECSPD']=30;
    
    $pkode=$_POST['cb_kode'];
    $psubkode=$_POST['cb_kodesub'];
    
    $pmystsyginput="";
    
    
    $ppertipe=$_POST['cb_pertipe'];
    $pdivisi=$_POST['cb_divisi'];
    $pjenis=$_POST['cb_jenis'];
    
    $padvance=$_POST['cb_jenispilih'];//untuk kode dan sub kode, jenis_rpt
    
    
    $pnomor=$_POST['e_nomor'];
    //$ptgl = str_replace('/', '-', $_POST['e_tglberlaku']);
    $ptgl = $_POST['e_tglberlaku'];
    $periode1= date("Y-m-d", strtotime($ptgl));
    
    $pdivno=$_POST['e_nomordiv'];
    $pjumlah=str_replace(",","", $_POST['e_jmlusulan']);
    
    if ($act=="input") {
        $sql=  mysqli_query($cnmy, "select MAX(idinput) as NOURUT from $dbname.t_suratdana_br");
        $ketemu=  mysqli_num_rows($sql);
        $awal=7; $urut=1; $kodenya=""; $periode=date('Ymd');
        if ($ketemu>0){
            $o=  mysqli_fetch_array($sql);
            $urut=$o['NOURUT']+1;
            $kodenya=$urut;
        }else{
            $kodenya=$_POST['e_id'];
        }
    }else{
        $kodenya=$_POST['e_id'];
    }
    

    
    $pmytgl = $_POST['e_periode1'];
    $pmytg2 = $_POST['e_periode2'];
    
    $periodef = date("Y-m-d", strtotime($pmytgl));
    $periodet = date("Y-m-d", strtotime($pmytg2));
    

    $pkodeperiode="";
    $pstspilihan="";
    
    $pcoa="101-02-002";
    
    $prtpsby="";
    if (isset($_POST['chk_tglsby'])) {
        if ($_POST['chk_tglsby']=="N") {
            $ppertipe="S";
            $prtpsby="2";
        }
    }

   
    $fidbr="";
    foreach ($_POST['chk_brid'] as $nobrinput) {
        if (!empty($nobrinput) AND $nobrinput <> "0") {
            $fidbr=$fidbr."'".$nobrinput."',";
        }
    }
    
    //echo "$fidbr"; exit;
    //echo "$pcoa - idinput : $kodenya, $pdivisi, $pkode, $psubkode, jenis_rpt : $padvance, tgl : $periode1 - $pdivno - $pjumlah <br/>lamp : $pjenis, per by $ppertipe : $periodef - $periodet"; exit;
    
    
    
    if ($act=="input") {
        $query = "INSERT INTO $dbname.t_suratdana_br (idinput, divisi, kodeid, subkode, nomor, tgl, nodivisi, jumlah, "
                . " userid, coa4, lampiran, tglf, tglt, kodeperiode, sts, karyawanid, periodeby, jenis_rpt)values"
                . "('$kodenya', '$pdivisi', '$pkode', '$psubkode', '$pnomor', '$periode1', '$pdivno', '$pjumlah', "
                . " '$_SESSION[IDCARD]', '$pcoa', '$pjenis', '$periodef', '$periodet', '$pkodeperiode', '$pstspilihan', '$_SESSION[IDCARD]', '$ppertipe', '$padvance')";
    }else{
        $query = "UPDATE $dbname.t_suratdana_br SET lampiran='$pjenis', pilih='Y', coa4='$pcoa', divisi='$pdivisi', kodeid='$pkode', "
                . " subkode='$psubkode', nomor='$pnomor', tgl='$periode1', nodivisi='$pdivno', jumlah='$pjumlah', userid='$_SESSION[IDCARD]', "
                . " tglf='$periodef', tglt='$periodet', kodeperiode='$pkodeperiode', sts='$pstspilihan', periodeby='$ppertipe', jenis_rpt='$padvance' WHERE "
                . " idinput='$kodenya'";
    }
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
    
    if ($padvance=="B" OR $padvance=="C" OR $padvance=="V") {
        $query = "UPDATE $dbname.t_suratdana_br SET pilih='N' WHERE idinput='$kodenya'";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
    }
    
    /*
    //cash advance
    if (isset($_POST['e_chkpilih'])) {
        if ($_POST['e_chkpilih']=="N") {
            $query = "UPDATE $dbname.t_suratdana_br SET pilih='N' WHERE idinput='$kodenya'";
            mysqli_query($cnmy, $query);
        }
    }
    */
   
    
    
    
    $query = "DELETE FROM $dbname.t_suratdana_br_d WHERE idinput='$kodenya'";
    mysqli_query($cnmy, $query);
    
    $query = "INSERT INTO $dbname.t_suratdana_br_d (idinput,divisi,jumlah)values('$kodenya', '$pdivisi', '$pjumlah')";
    mysqli_query($cnmy, $query);
    
    
    $query = "DELETE FROM $dbname.t_suratdana_br1 WHERE idinput='$kodenya'";
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
    
    
    $purutan=1;
    $pkodeurutan=1;
        
    $kodeinput="D";//KODE BR OTC

    
    
    
    
    if (!empty($fidbr)) {
        $fidbr=substr($fidbr, 0, -1);
        $fidbr="(".$fidbr.")";        

        if (!empty($kodenya)){
            
            foreach ($_POST['chk_brid'] as $no_brid) {
                if (!empty($no_brid)) {
                    $pamount_rp=0;
                    if (isset($_POST['e_jmlusul'][$no_brid])) {
                        $pamount_rp=$_POST['e_jmlusul'][$no_brid];
                        $pamount_rp=str_replace(",","", $pamount_rp);
                        if (empty($pamount_rp)) $pamount_rp=0;
                    }
                    
                    $query = "INSERT INTO $dbname.t_suratdana_br1 (idinput, bridinput, kodeinput, urutan, amount)VALUES"
                            . "('$kodenya', '$no_brid', '$kodeinput', '$purutan', '$pamount_rp')";
                    mysqli_query($cnmy, $query);
                    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
                    
                    //echo "$no_brid : $pamount_rp<br/>";
                }
            }
            
        }
    }
    
    /*
    
        $nfildpilih = ", sum(jumlah) jumlah ";
        if ( (INT)$prtpsby==2) {
            $nfildpilih = ", sum(realisasi) jumlah ";
        }else{
            if ($pkode=="2" AND $ppertipe=="T") {
                $nfildpilih = ", sum(realisasi) jumlah ";
            }
        }

        $query="SELECT DISTINCT ifnull(brOtcId,'') nobrid $nfildpilih from hrd.br_otc where "
                . " brOtcId IN $fidbr GROUP BY 1 ORDER BY 1";
        $tampil= mysqli_query($cnmy, $query);
        $ketemu= mysqli_num_rows($tampil);
        if ($ketemu>0) {
            while ($tr= mysqli_fetch_array($tampil)) {
                $nobrinput=$tr['nobrid'];
                $namount=$tr['jumlah'];
                if (empty($namount)) $namount = "0";

                //eksekusi input
                $query = "INSERT INTO $dbname.t_suratdana_br1 (idinput, bridinput, kodeinput, urutan, amount)VALUES"
                        . "('$kodenya', '$nobrinput', '$kodeinput', '$pkodeurutan', '$namount')";
                mysqli_query($cnmy, $query);
                //$erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }

                if ($purutan==(INT)$_SESSION['JMLRECSPD']) {
                    $purutan=0;
                    $pkodeurutan++;
                }
                $purutan++;
            }

        }
        
    */
    
    
    mysqli_close($cnmy);
    
    header('location:../../media.php?module='.$module.'&idmenu='.$idmenu.'&act=complt');
}
?>
