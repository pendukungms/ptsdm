<?php
session_start();
if ($_GET['module']=="viewsubkode"){
    include "../../config/koneksimysqli.php";
    $pkode = trim($_POST['ukode']);
    $query = "select kodeid, subkode, subnama from dbmaster.t_kode_spd WHERE kodeid='$pkode'";
    $result = mysqli_query($cnmy, $query); 
    $record = mysqli_num_rows($result);
    for ($i=0;$i < $record;$i++) {
        $row = mysqli_fetch_array($result); 
        $subkode  = $row['subkode'];
        $namasub = $row['subnama'];
        echo "<option value=\"$subkode\">$subkode - $namasub</option>";
    }
}elseif ($_GET['module']=="viewnomorspd"){

    if ($_SESSION['IDCARD']!="0000000148") { // AND $_SESSION['IDCARD']!="0000001854"
        echo "";
        exit;
    }

    include "../../config/koneksimysqli.php";
    $tgl01=$_POST['utgl'];

    $bl= date("m", strtotime($tgl01));
    $byear= date("y", strtotime($tgl01));
    $bl=(int)$bl;
    $blromawi="I";
    if ($bl==1) $blromawi="I";
    if ($bl==2) $blromawi="II";
    if ($bl==3) $blromawi="III";
    if ($bl==4) $blromawi="IV";
    if ($bl==5) $blromawi="V";
    if ($bl==6) $blromawi="VI";
    if ($bl==7) $blromawi="VII";
    if ($bl==8) $blromawi="VIII";
    if ($bl==9) $blromawi="IX";
    if ($bl==10) $blromawi="X";
    if ($bl==11) $blromawi="XI";
    if ($bl==12) $blromawi="XII";

    $pdivsi = trim($_POST['udivisi']);
    $pkode = trim($_POST['ukode']);
    $nomorspd="";
    $tno=1;
    $awal=3;

        $query = "SELECT MAX(SUBSTRING_INDEX(nomor, '/', 1)) as pnomor FROM dbmaster.t_suratdana_br WHERE stsnonaktif<>'Y'";
        $showkan= mysqli_query($cnmy, $query);
        $ketemu= mysqli_num_rows($showkan);
        if ($ketemu>0){
            $sh= mysqli_fetch_array($showkan);
            if (!empty($sh['pnomor'])) { $tno=(INT)$sh['pnomor']; $tno++; }
        }

        $jml=  strlen($tno);
        $awal=$awal-$jml;

        if ($awal>=0)
            $tno=str_repeat("0", $awal).$tno;
        else
            $tno=$tno;

        $noslipurut=$tno."/UM-JKT/".$blromawi."/".$byear;


        $nomorspd=$noslipurut;

    echo $nomorspd;

}elseif ($_GET['module']=="viewnomorbukti"){
/*
    if ($_SESSION['IDCARD']=="0000000148") { //ane
        echo "";
        exit;
    }
*/
    include "../../config/koneksimysqli.php";
    $tgl01=$_POST['utgl'];
    $tahuninput= date("Y", strtotime($tgl01));
    
    $bl= date("m", strtotime($tgl01));
    $byear= date("y", strtotime($tgl01));
    $bl=(int)$bl;
    $blromawi="I";
    if ($bl==1) $blromawi="I";
    if ($bl==2) $blromawi="II";
    if ($bl==3) $blromawi="III";
    if ($bl==4) $blromawi="IV";
    if ($bl==5) $blromawi="V";
    if ($bl==6) $blromawi="VI";
    if ($bl==7) $blromawi="VII";
    if ($bl==8) $blromawi="VIII";
    if ($bl==9) $blromawi="IX";
    if ($bl==10) $blromawi="X";
    if ($bl==11) $blromawi="XI";
    if ($bl==12) $blromawi="XII";

    $pdivsi = trim($_POST['udivisi']);
    $pkode = trim($_POST['ukode']);
    $psubkode = trim($_POST['ukodesub']);
    $padvance = trim($_POST['uadvance']);
    
    if ($_SESSION['IDCARD']=="0000000148") {
        if ( ($pkode=="1" AND $psubkode=="01") OR ($pkode=="2" AND $psubkode=="20") ) {
        }else{
            echo "";
            exit;
        }
    }
    
    $nobuktinya="";
    $tno=1;
    $awal=3;

    if ($pdivsi=="OTC"){
        $query = "SELECT MAX(SUBSTRING_INDEX(nodivisi, '/', 1)) as pnomor FROM dbmaster.t_suratdana_br WHERE stsnonaktif<>'Y' AND YEAR(tgl)='$tahuninput' AND divisi='$pdivsi'";// AND kodeid='$pkode'
        $showkan= mysqli_query($cnmy, $query);
        $ketemu= mysqli_num_rows($showkan);
        if ($ketemu>0){
            $sh= mysqli_fetch_array($showkan);
            if (!empty($sh['pnomor'])) { $tno=(INT)$sh['pnomor']; $tno++; }
        }

        $jml=  strlen($tno);
        $awal=$awal-$jml;

        if ($awal>=0)
            $tno=str_repeat("0", $awal).$tno;
        else
            $tno=$tno;


        $noslipurut=$tno."/BR-OTC/".$blromawi."/".$byear;


        $nobuktinya=$noslipurut;
    }else{
        if ( ($pkode=="2" AND $psubkode=="21") OR ($pkode=="1" AND $psubkode=="03")) {
            $query = "SELECT MAX(SUBSTRING_INDEX(nodivisi, '/', 1)) as pnomor "
                    . " FROM dbmaster.t_suratdana_br WHERE stsnonaktif<>'Y' AND YEAR(tgl)='$tahuninput' AND kodeid='$pkode' AND subkode='$psubkode'";
            $showkan= mysqli_query($cnmy, $query);
            $ketemu= mysqli_num_rows($showkan);
            if ($ketemu>0){
                $sh= mysqli_fetch_array($showkan);
                if (!empty($sh['pnomor'])) { $tno=(INT)$sh['pnomor']; $tno++; }
            }

            $jml=  strlen($tno);
            $awal=$awal-$jml;

            if ($awal>=0)
                $tno=str_repeat("0", $awal).$tno;
            else
                $tno=$tno;


            $noslipurut=$tno."/LK/".$blromawi."/".$byear;
            if ($pkode=="1" AND $psubkode="03") $noslipurut=$tno."/RUTIN/".$blromawi."/".$byear;

            $nobuktinya=$noslipurut;
        }else{
            $pjenis="";
            if (isset($_POST['ujenis'])) $pjenis=$_POST['ujenis'];
            if ($_SESSION['IDCARD']=="0000000566" OR $_SESSION['IDCARD']=="0000001043" OR $_SESSION['IDCARD']=="0000000148"){
                $byear= date("Y", strtotime($tgl01));
                
                /*
                $ntglpilih = date('Y-m-d', strtotime($tgl01));
                $query = "select idinput, divisi, nodivisi from dbmaster.t_suratdana_br WHERE stsnonaktif<>'Y' "
                        . "and divisi='$pdivsi' and tgl='$ntglpilih' AND userid='$_SESSION[IDCARD]'";
                $tampilkan=mysqli_query($cnmy, $query);
                $ketemuada= mysqli_num_rows($tampilkan);
                if ($ketemuada>0) {
                    $ad= mysqli_fetch_array($tampilkan);
                    if (!empty($ad['nodivisi'])) $nobuktinya=$ad['nodivisi'];
                }else{
                */  
                    $fadv="";
                    //if ($_SESSION['IDCARD']=="0000001043") $fadv=" AND jenis_rpt='$padvance' ";
                    
                    $query = "SELECT MAX(SUBSTRING_INDEX(nodivisi, '/', 1)) as pnomor FROM dbmaster.t_suratdana_br WHERE stsnonaktif<>'Y' AND YEAR(tgl)='$tahuninput' AND "
                            . " userid='$_SESSION[IDCARD]' $fadv";//divisi='$pdivsi' AND 
                    $showkan= mysqli_query($cnmy, $query);
                    $ketemu= mysqli_num_rows($showkan);
                    if ($ketemu>0){
                        $sh= mysqli_fetch_array($showkan);
                        if (!empty($sh['pnomor'])) { $tno=(INT)$sh['pnomor']; $tno++; }
                    }

                    $jml=  strlen($tno);
                    $awal=$awal-$jml;

                    if ($awal>=0)
                        $tno=str_repeat("0", $awal).$tno;
                    else
                        $tno=$tno;

                    $ndiv="PEA";
                    if ($pdivsi=="HO") $ndiv="HO";
                    if ($pdivsi=="PIGEO") $ndiv="P";
                    if ($pdivsi=="EAGLE") $ndiv="E";
                    if ($pdivsi=="EAGLE"){ 
                        $byear= date("y", strtotime($tgl01));
                        if ($_SESSION['IDCARD']=="0000000566") {
                            $noslipurut=$tno."/BR $ndiv-PM/".$blromawi."/".$byear;
                        }else{
							$noslipurut=$tno."/BR $ndiv/".$blromawi."/".$byear;
						}
                    }else
                        $noslipurut=$tno."/BR-$ndiv/".$blromawi."/".$byear;

                    $nobuktinya=$noslipurut;
                }
                
            //}
        }
        
    }
    echo $nobuktinya;


}elseif ($_GET['module']=="hitungtotaldata"){

    include "../../config/koneksimysqli.php";
    $totalinput=0;
    $pact=$_POST['uact'];
    $pidinput=$_POST['eidinput'];
    $pdivisi=$_POST['udivisi'];
    $jenis=$_POST['ujenis'];
    $tgl01=$_POST['utgl'];
    $periode1= date("Y-m-d", strtotime($tgl01));
    $filterlampiran="";
    
    if ($pdivisi=="OTC") {
        $prtpsby=$_POST['erptsby'];
        
        if (!empty($jenis)) $filterlampiran = " and case when ifnull(lampiran,'N')='' then 'N' else lampiran end ='$jenis' ";
        
        $ftglnya = " DATE_FORMAT(tglbr,'%Y-%m-%d') ";
        if ( (INT)$prtpsby==2) $ftglnya = " DATE_FORMAT(tglrpsby,'%Y-%m-%d') ";
            
        $filsudahada="";
        if ($pact=="editdata") $filsudahada=" AND f.idinput<> '$pidinput' ";
        $query="SELECT SUM(jumlah) as jumlah, sum(realisasi) real1 from hrd.br_otc where "
                . " brOtcId not in (SELECT DISTINCT ifnull(brOtcId,'') from hrd.br_otc_reject) AND "
                . " $ftglnya = '$periode1' $filterlampiran"
                . " AND brOtcId NOT IN (SELECT DISTINCT ifnull(f.bridinput,'') from dbmaster.t_suratdana_br1 f JOIN "
                . " dbmaster.t_suratdana_br g on f.idinput=g.idinput WHERE "
                . " f.kodeinput='D' AND IFNULL(g.pilih,'')='Y' $filsudahada)";
        //SELECT DISTINCT ifnull(f.bridinput,'') from dbmaster.t_suratdana_br1 as f JOIN dbmaster.t_suratdana_br g on f.idinput=g.idinput WHERE f.kodeinput='D' AND IFNULL(g.pilih,'')='Y'
        
        $tampil= mysqli_query($cnmy, $query);
        $ketemu= mysqli_num_rows($tampil);
        if ($ketemu>0) {
            $tr= mysqli_fetch_array($tampil);
            
            if ( (INT)$prtpsby==2){
                if (!empty($tr['real1'])) $totalinput=$tr['real1'];
            }else{
                if (!empty($tr['jumlah'])) $totalinput=$tr['jumlah'];
            }
        }
    }
    echo $totalinput;

}elseif ($_GET['module']=="cariperiode1"){
    $pkode = trim($_POST['ukode']);
    $psubkode = trim($_POST['ukodesub']);
    
    $tgl01=$_POST['utgl'];
    $periode1= date("d F Y", strtotime($tgl01));
    
    $pd= date("d", strtotime($tgl01));
    $pm= date("m", strtotime($tgl01));
    $py= date("Y", strtotime($tgl01));
    
    //if ($_SESSION['IDCARD']=="0000000143") {
    if ($pkode=="1" AND $psubkode=="03") {
        $periode1= date("01 F Y", strtotime($tgl01));
        if ((int)$pd>=16) $periode1= date("16 F Y", strtotime($tgl01));
        
    //}elseif ($_SESSION['IDCARD']=="0000000329") {
    }elseif ($pkode=="2" AND $psubkode=="21") {
        $periode1= date("01 F Y", strtotime($tgl01));
    }
    echo $periode1;
    
}elseif ($_GET['module']=="cariperiode2"){
    $pkode = trim($_POST['ukode']);
    $psubkode = trim($_POST['ukodesub']);
    
    $tgl01=$_POST['utgl'];
    $periode1= date("d F Y", strtotime($tgl01));
    
    $pd= date("d", strtotime($tgl01));
    $pm= date("m", strtotime($tgl01));
    $py= date("Y", strtotime($tgl01));
    
    //if ($_SESSION['IDCARD']=="0000000143") {
    if ($pkode=="1" AND $psubkode=="03") {
        $periode1= date("01 F Y", strtotime($tgl01));
        if ((int)$pd>=16) $periode1= date("16 F Y", strtotime($tgl01));
        $periode1= date("15 F Y", strtotime($tgl01));
        if ((int)$pd>=16) $periode1= date("t F Y", strtotime($tgl01));
        
    //}elseif ($_SESSION['IDCARD']=="0000000329") {
    }elseif ($pkode=="2" AND $psubkode=="21") {
        $periode1= date("t F Y", strtotime($tgl01));
    }
    echo $periode1;
    
}elseif ($_GET['module']=="cariperiode3"){
    
    $pkode = trim($_POST['ukode']);
    $psubkode = trim($_POST['ukodesub']);
    
    $tglaslal=$_POST['uasal'];
    
    $tgl01=$_POST['utgl'];
    $periode1= date("d F Y", strtotime($tgl01));
    
    $pd= date("d", strtotime($tgl01));
    $pm= date("m", strtotime($tgl01));
    $py= date("Y", strtotime($tgl01));
    
    //if ($_SESSION['IDCARD']=="0000000143") {
    if ($pkode=="1" AND $psubkode=="03") {
        $periode1= date("01 F Y", strtotime($tgl01));
        if ((int)$pd>=16) $periode1= date("16 F Y", strtotime($tgl01));
        
    //}elseif ($_SESSION['IDCARD']=="0000000329") {
    }elseif ($pkode=="2" AND $psubkode=="21") {
        $periode1= date("01 F Y", strtotime($tgl01));
    }else{
        $periode1= $tglaslal;
    }
    echo $periode1;
    
}elseif ($_GET['module']=="hitungtotaldatarutin"){
    include "../../config/koneksimysqli.php";
    $totalinput=0;
    $pket=$_GET['ket'];
    
    $pact=$_POST['uact'];
    $pidinput=$_POST['eidinput'];
    $pdivisi=$_POST['udivisi'];
    
    $tgl01=$_POST['utgl'];
    $periode1= date("Y-m-d", strtotime($tgl01));
    
    $date1=$_POST['uper1'];
    $mytgl1= date("Y-m", strtotime($date1));
    
    $nd= date("d", strtotime($date1));
    
    $date2=$_POST['uper2'];
    $mytgl2= date("Y-m", strtotime($date2));
    
    $kdperiode="1";
    if ((INT)$nd>=16) $kdperiode="2";
    
    $fperiode = " AND DATE_FORMAT(br.bulan, '%Y-%m') = '$mytgl1' ";
    $fstsapv = " AND ifnull(br.tgl_fin,'') <> '' AND ifnull(br.tgl_fin,'0000-00-00') <> '0000-00-00' ";
    $fildivisi="";
    if (!empty($pdivisi)) $fildivisi=" AND br.divisi='$pdivisi'";
    
    $query = "SELECT sum(br.jumlah) jumlah 
        FROM dbmaster.t_brrutin0 AS br WHERE br.kode=1 AND br.stsnonaktif <> 'Y' AND br.divisi <> 'OTC' 
        AND br.kodeperiode='$kdperiode' $fperiode $fstsapv $fildivisi";
    $tampil= mysqli_query($cnmy, $query);
    $ketemu= mysqli_num_rows($tampil);
    if ($ketemu>0) {
        $tr= mysqli_fetch_array($tampil);
        if (!empty($tr['jumlah'])) $totalinput=$tr['jumlah'];
    }
    
    echo $totalinput;
    
}elseif ($_GET['module']=="hitungtotaldatalk"){
    include "../../config/koneksimysqli.php";
    $totalinput=0;
    $pket=$_GET['ket'];
    
    $pact=$_POST['uact'];
    $pidinput=$_POST['eidinput'];
    $pdivisi=$_POST['udivisi'];
    
    $tgl01=$_POST['utgl'];
    $periode1= date("Y-m-d", strtotime($tgl01));
    
    $date1=$_POST['uper1'];
    $mytgl1= date("Y-m", strtotime($date1));
    
    $date2=$_POST['uper2'];
    $mytgl2= date("Y-m", strtotime($date2));
    
    
    $fperiode = " AND ( (DATE_FORMAT(br.periode1, '%Y-%m') = '$mytgl1') OR (DATE_FORMAT(br.periode2, '%Y-%m') = '$mytgl1') ) ";
    $fstsapv = " AND ifnull(br.tgl_fin,'') <> '' AND ifnull(br.tgl_fin,'0000-00-00') <> '0000-00-00' ";
    $fildivisi="";
    if (!empty($pdivisi)) $fildivisi=" AND br.divisi='$pdivisi'";
    
    $stsreport = $_POST['sts_rpt'];
    
    $filstatuscls="";
    if (!empty($stsreport)) {
        $finsts=" AND idrutin IN ";
        if ($stsreport=="B") $finsts=" AND idrutin NOT IN ";
        
        $filstatuscls =" $finsts (select DISTINCT IFNULL(idrutin,'') idrutin from dbmaster.t_brrutin_ca_close WHERE "
                . " IFNULL(idrutin,'') <> '' AND DATE_FORMAT(bulan, '%Y-%m') = '$mytgl1' ";
        if ($stsreport=="C") {
            $filstatuscls=$filstatuscls." AND sts='C' ";
        }elseif ($stsreport=="S") {
            $filstatuscls=$filstatuscls." AND sts='S' ";
        }
        $filstatuscls=$filstatuscls." )";
    }
    
    $query = "SELECT sum(br.jumlah) jumlah 
        FROM dbmaster.t_brrutin0 AS br WHERE br.kode=2 AND br.stsnonaktif <> 'Y' AND 
        br.divisi <> 'OTC' $fperiode $fstsapv $filstatuscls $fildivisi";

    $tampil= mysqli_query($cnmy, $query);
    $ketemu= mysqli_num_rows($tampil);
    if ($ketemu>0) {
        $tr= mysqli_fetch_array($tampil);
        if (!empty($tr['jumlah'])) $totalinput=$tr['jumlah'];
    }
    
    echo $totalinput;
    
    
    
}elseif ($_GET['module']=="hitungtotaldataerni"){
    include "../../config/koneksimysqli.php";
    $totalinput=0;
    $pket=$_GET['ket'];
    
    $pact=$_POST['uact'];
    $pidinput=$_POST['eidinput'];
    $pdivisi=$_POST['udivisi'];
    
    $tgl01=$_POST['utgl'];
    $periode1= date("Y-m-d", strtotime($tgl01));
    
    $date1=$_POST['uper1'];
    $mytgl1= date("Y-m-d", strtotime($date1));
    
    $date2=$_POST['uper2'];
    $mytgl2= date("Y-m-d", strtotime($date2));
    
    $jenis=$_POST['ujenis'];
    $pertipe=$_POST['upertipe'];
    
    $filterlampiran="";
    
    if (!empty($jenis)) $filterlampiran = " and case when ifnull(lampiran,'N')='' then 'N' else lampiran end ='$jenis' ";
        
    $ftp="tgltrans";
    if ($pertipe=="I") $ftp="tgl";
    
    $fdivisi="";
    if (!empty($pdivisi)) $fdivisi=" AND divprodid='$fdivisi' ";
    $userid=$_SESSION['IDCARD'];
    
    $filsudahada="";
    if ($pact=="editdata") $filsudahada=" AND idinput<> '$pidinput' ";
    $query="SELECT SUM(jumlah) as jumlah from hrd.br0 where "
            . " brId not in (SELECT DISTINCT ifnull(brId,'') from hrd.br0_reject) AND "
            . " DATE_FORMAT($ftp,'%Y-%m-%d') between '$mytgl1' AND '$mytgl2' $filterlampiran"
            . " AND brId NOT IN (SELECT DISTINCT ifnull(bridinput,'') from dbmaster.t_suratdana_br1 WHERE kodeinput='A' $filsudahada) AND "
            . " COA4 IN (SELECT DISTINCT IFNULL(COA4,'') COA4 FROM dbmaster.coa_wewenang WHERE karyawanId='$userid') $fdivisi";
    $tampil= mysqli_query($cnmy, $query);
    $ketemu= mysqli_num_rows($tampil);
    if ($ketemu>0) {
        $tr= mysqli_fetch_array($tampil);
        if (!empty($tr['jumlah'])) $totalinput=$tr['jumlah'];
    }
        
    echo $totalinput;
}elseif ($_GET['module']=="hitungtotalcekboxerni"){
    include "../../config/koneksimysqli.php";
    $pnoid=$_POST['unoidbr'];
    $padvance=$_POST['uadvance'];
    $userid=$_SESSION['IDCARD'];
    $totalinput=0;
    if (!empty($pnoid)) {
        $now=date("mdYhis");
        $tmp01 =" dbtemp.DSETHK01_".$userid."_$now ";
        
        $query = "select brId, jumlah, jumlah1 from hrd.br0 where brId in $pnoid";
        $query = "create TEMPORARY table $tmp01 ($query)"; 
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
        
        if ($padvance=="A"){
        }else{
            mysqli_query($cnmy, "UPDATE $tmp01 SET jumlah=jumlah1 WHERE IFNULL(jumlah1,0)>0");
        }
        
        $query="SELECT SUM(jumlah) as jumlah from $tmp01";
        $tampil= mysqli_query($cnmy, $query);
        $ketemu= mysqli_num_rows($tampil);
        if ($ketemu>0) {
            $tr= mysqli_fetch_array($tampil);
            if (!empty($tr['jumlah'])) $totalinput=$tr['jumlah'];
        }
        
        mysqli_query($cnmy, "drop TEMPORARY table $tmp01");
    }
    
    echo $totalinput;
}elseif ($_GET['module']=="hitungtotalcekboxkd"){
    include "../../config/koneksimysqli.php";
    $pnoid=$_POST['unoidbr'];
    $padvance=$_POST['uadvance'];
    $userid=$_SESSION['IDCARD'];
    $totalinput=0;
    
    if (!empty($pnoid)) {
        $query="SELECT SUM(jumlah) as jumlah from hrd.klaim WHERE klaimId IN $pnoid";
        $tampil= mysqli_query($cnmy, $query);
        $ketemu= mysqli_num_rows($tampil);
        if ($ketemu>0) {
            $tr= mysqli_fetch_array($tampil);
            if (!empty($tr['jumlah'])) $totalinput=$tr['jumlah'];
        }
    }
    
    echo $totalinput;
    
}elseif ($_GET['module']=="viewdatajeniskode"){
    include "../../config/koneksimysqli.php";
    
    $pjeniskode=$_POST['ujeniskode'];
    
    $pkode="1";
    $psubkode="01";
    
    if ($pjeniskode=="K" OR $pjeniskode=="B") {
        $pkode="2";
        $psubkode="20";
    }elseif ($pjeniskode=="S") {
        $pkode="6";
        $psubkode="80";
    }elseif ($pjeniskode=="J") {
        $pkode="3";
        $psubkode="50";
    }
    ?>
        <div class='form-group'>
            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Kode <span class='required'></span></label>
            <div class='col-xs-5'>
                  <select class='form-control input-sm' id='cb_kode' name='cb_kode' onchange="ShowSubKode();" data-live-search="true">
                      <option value='' selected>-- Pilihan --</option>
                      <?PHP
                        $query = "select distinct kodeid, nama from dbmaster.t_kode_spd order by kodeid";

                        $tampil = mysqli_query($cnmy, $query);
                        while ($z= mysqli_fetch_array($tampil)) {
                            if ($z['kodeid']==$pkode)
                                echo "<option value='$z[kodeid]' selected>$z[nama]</option>";
                            else
                                echo "<option value='$z[kodeid]'>$z[nama]</option>";
                        }
                      ?>
                  </select>
            </div>
        </div>



        <div class='form-group'>
            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Sub Kode <span class='required'></span></label>
            <div class='col-xs-5'>
                  <select class='form-control input-sm' id='cb_kodesub' name='cb_kodesub' data-live-search="true" onchange="">
                      <option value='' selected>-- Pilihan --</option>
                      <?PHP
                      //if ($_GET['act']=="editdata"){
                        $query = "select distinct kodeid, subkode, subnama from dbmaster.t_kode_spd where kodeid='$pkode' order by subkode";

                        $tampil = mysqli_query($cnmy, $query);
                        while ($z= mysqli_fetch_array($tampil)) {
                            if ($z['subkode']==$psubkode)
                                echo "<option value='$z[subkode]' selected>$z[subkode] - $z[subnama]</option>";
                            else
                                echo "<option value='$z[subkode]'>$z[subkode] - $z[subnama]</option>";
                        }
                      //}
                      ?>
                  </select>
            </div>
        </div>
    <?PHP
    mysqli_close($cnmy);
}elseif ($_GET['module']=="viewdatajenislampiran"){
    $pjeniskode=$_POST['ujeniskode'];
    $plmp1="";
    $plmp2="selected";
    $plmp3="";
    if ($pjeniskode=="B" OR $pjeniskode=="S") {
        $plmp1="";
        $plmp2="";
        $plmp3="selected";
    }elseif ($pjeniskode=="K" OR $pjeniskode=="D" OR $pjeniskode=="J") {
        $plmp1="selected";
        $plmp2="";
        $plmp3="";
    }
    echo "<option value='' $plmp1>--All--</option>";
    echo "<option value='Y' $plmp2>Ya</option>";
    echo "<option value='N' $plmp3>Tidak</option>";
                                                    
}elseif ($_GET['module']=="viewdatanodivisiadjjenis"){
    include "../../config/koneksimysqli.php";
    $ajsnobr="";
    
    $userid=$_SESSION['IDCARD'];
    $pidkaryawan=$_SESSION['IDCARD'];
    $now=date("mdYhis");
    $tmp01 =" dbtemp.DSETHZE01_".$userid."_$now ";
    $fdivisi_batal="";
    if ($pidkaryawan=="0000001043") $fdivisi_batal=" AND divprodid ='EAGLE' ";
    if ($pidkaryawan=="0000000566") $fdivisi_batal=" AND divprodid NOT IN ('EAGLE', 'HO') ";;

    $query = "SELECT distinct brId from (
        select brId from hrd.br0 where batal='Y'  and year(tgl)>'2018' $fdivisi_batal 
        UNION
        select DISTINCT brId from dbmaster.backup_br0 WHERE 1=1 $fdivisi_batal 
        ) as xxx";
    $query = "create TEMPORARY table $tmp01 ($query)"; 
    mysqli_query($cnmy, $query);

    echo "<option value='' selected>-- Pilihan --</option>";
    $filbulansod_adj="";
    $query = "select divisi, nodivisi, jumlah from dbmaster.t_suratdana_br WHERE IFNULL(stsnonaktif,'')<>'Y' "
            . "and IFNULL(pilih,'') = 'Y' AND IFNULL(nodivisi,'')<>'' "
            . " AND karyawanid='$pidkaryawan' "
            . " AND idinput in (select distinct idinput from dbmaster.t_suratdana_br1 WHERE "
            . " bridinput IN (select distinct brId from $tmp01) ) $filbulansod_adj "
            . " ORDER BY 1,2";
    $tampil = mysqli_query($cnmy, $query);
    while ($z= mysqli_fetch_array($tampil)) {
        $pajsjmlbr=$z['jumlah'];
        if (!empty($pajsjmlbr)) $pajsjmlbr=number_format($pajsjmlbr,0);
        $pajsnobr=$z['nodivisi'];
        $pajsdivisi=$z['divisi'];
        if (empty($pajsdivisi)) $pajsdivisi= "ETHICAL";
        $pajsketjml = "$pajsnobr &nbsp;&nbsp (Rp. $pajsjmlbr)";//$pajsdivisi - 
        if (trim($pajsnobr)==trim($ajsnobr))
            echo "<option value='$pajsnobr' selected>$pajsketjml</option>";
        else
            echo "<option value='$pajsnobr'>$pajsketjml</option>";
    }

    mysqli_query($cnmy, "drop temporary table $tmp01");
    mysqli_close($cnmy);
    
}elseif ($_GET['module']=="viewdatapcmrp") {
    include "../../config/koneksimysqli.php";
    $pdivisi = $_POST['udivisid'];
    $ptgl = $_POST['utgl'];
    $ptahun= date("Y", strtotime($ptgl));
    
    $query="select jumlah from dbmaster.t_uangmuka where divisi='$pdivisi'";
    $tampil=mysqli_query($cnmy, $query);
    $a=mysqli_fetch_array($tampil);
    $pjmlpc=$a['jumlah'];
    
    $query="select jml_ots from dbmaster.t_outstanding_br where divisi='$pdivisi' AND tahun='$ptahun'";
    $tampil=mysqli_query($cnmy, $query);
    $a=mysqli_fetch_array($tampil);
    $pjmlots=$a['jml_ots'];
    
    $psisarp=(DOUBLE)$pjmlpc-(DOUBLE)$pjmlots;
    
    if (empty($pjmlpc)) $pjmlpc=0;
    if (empty($pjmlots)) $pjmlots=0;
    if (empty($psisarp)) $psisarp=0;
    
    echo "$pjmlpc|$pjmlots|$psisarp";
    mysqli_close($cnmy);
	
}elseif ($_GET['module']=="xxx"){
}elseif ($_GET['module']=="xxx"){
    
}
?>

