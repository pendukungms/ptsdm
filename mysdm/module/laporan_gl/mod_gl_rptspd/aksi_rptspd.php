
<?PHP
$ppilihbay="";
if (isset($_POST['cb_rptby'])) $ppilihbay=$_POST['cb_rptby'];

if ($ppilihbay=="S") {
	
    include "aksi_rptspdsumary.php";
	
}else{
	
	
    session_start();
    $ppilihrpt=$_GET['ket'];
    if ($ppilihrpt=="excel") {
        // Fungsi header dengan mengirimkan raw data excel
        header("Content-type: application/vnd-ms-excel");
        // Mendefinisikan nama file ekspor "hasil-export.xls"
        header("Content-Disposition: attachment; filename=REKAP SPD.xls");
    }
    
    $nmodule=$_GET['module'];
    
    include("config/koneksimysqli.php");
    include("config/common.php");
    $cnit=$cnmy;
?>

<html>
<head>
    <title>Rekap Surat Permintaan Dana</title>
    <?PHP if ($ppilihrpt!="excel") { ?>
        <meta http-equiv="Expires" content="Mon, 01 Apr 2019 1:00:00 GMT">
        <meta http-equiv="Pragma" content="no-cache">
        <link rel="shortcut icon" href="images/icon.ico" />
        <link href="css/laporanbaru.css" rel="stylesheet">
        <?php header("Cache-Control: no-cache, must-revalidate"); ?>
    <?PHP } ?>
</head>

<body>
    <form method='POST' action='<?PHP echo "?module=$_GET[module]&act=input&idmenu=$_GET[idmenu]"; ?>' id='demo-form2' name='form1' data-parsley-validate class='form-horizontal form-label-left' target="_blank">
    <?PHP
        include "config/koneksimysqli.php";
        include "config/fungsi_combo.php";
        
        $pses_grpuser=$_SESSION['GROUP'];
        $pses_divisi=$_SESSION['DIVISI'];
        $pses_idcard=$_SESSION['IDCARD'];
    
        if ($nmodule=="suratpdpreview") {
            $tgl01=$_POST['e_periode01'];
            $tgl02=$_POST['e_periode02'];
        }else{
            $tgl01=$_POST['bulan1'];
            $tgl02=$_POST['bulan2'];
        }
        
        $periode1= date("Ym", strtotime($tgl01));
        $periode2= date("Ym", strtotime($tgl02));

            
        $f_tgl=" AND DATE_FORMAT(tgl,'%Y%m') between '$periode1' AND '$periode2' ";
        
        $now=date("mdYhis");
        $tmp01 =" dbtemp.RPTREKOTCF01_".$_SESSION['USERID']."_$now ";
        $tmp02 =" dbtemp.RPTREKOTCF02_".$_SESSION['USERID']."_$now ";
        $tmp03 =" dbtemp.RPTREKOTCF03_".$_SESSION['USERID']."_$now ";
        $tmp04 =" dbtemp.RPTREKOTCF04_".$_SESSION['USERID']."_$now ";
        $tmp05 =" dbtemp.RPTREKOTCF05_".$_SESSION['USERID']."_$now ";
        $tmp06 =" dbtemp.RPTREKOTCF06_".$_SESSION['USERID']."_$now ";
        
        $query = "select a.idinput, a.tgl, a.tglspd, a.kodeid, b.nama, a.subkode, b.subnama, a.divisi, 
            IFNULL(a.nomor,'') nomor, a.nodivisi, a.nodivisi2, a.jumlah, a.jumlah2, kodeid2, subkode2, divisi2, CAST('' AS CHAR(1)) as tadj   
            from dbmaster.t_suratdana_br a JOIN dbmaster.t_kode_spd b on a.kodeid=b.kodeid and a.subkode=b.subkode
            WHERE IFNULL(a.stsnonaktif,'') <> 'Y' ";// AND a.kodeid NOT IN ('3')
        //echo "$query";exit;
        if ($pses_grpuser!="1" AND $pses_grpuser!="22" AND $pses_grpuser!="24" AND $pses_grpuser!="34" AND $pses_grpuser!="25" AND $pses_grpuser!="41") {//AND $pses_grpuser!="25" (anne)
            $query.=" AND IFNULL(userid,'')='$pses_idcard' ";
            $query.=" AND DATE_FORMAT(tgl,'%Y%m') between '$periode1' AND '$periode2' ";
            
        }else{

            if ($pses_grpuser=="25") {//34=surabaya
                $query.=" AND ( ( (DATE_FORMAT(tglspd,'%Y%m') between '$periode1' AND '$periode2') AND a.pilih='Y' ) "
                        . " OR ( (DATE_FORMAT(tgl,'%Y%m') between '$periode1' AND '$periode2') AND (a.pilih<>'Y' OR a.kodeid='3') ) "
                        . ")";
            }else{
                $query.=" AND (DATE_FORMAT(tglspd,'%Y%m') between '$periode1' AND '$periode2' "
                        . " OR (DATE_FORMAT(tgl,'%Y%m') between '$periode1' AND '$periode2' AND a.kodeid='3') ) ";
                $query.=" and a.pilih='Y' ";
            }
        }
        //echo $query;
        $query = "create TEMPORARY table $tmp01 ($query)";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        mysqli_query($cnmy, "UPDATE $tmp01 set jumlah=IFNULL(jumlah,0)+IFNULL(jumlah2,0) WHERE subkode in ('01', '02', '20')");
        
        
        
        
        
        //ADJUSTMENT
        $query = "SELECT a.idinput, a.tgl, a.tglspd, a.kodeid2 kodeid, a.subkode2 subkode, a.divisi2 divisi, "
                . " a.nomor, a.nodivisi2 nodivisi, a.nodivisi2, a.jumlah, a.jumlah2 "
                . " FROM $tmp01 a "
                . " where a.kodeid IN ('3')";
        $query = "create TEMPORARY table $tmp05 ($query)";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        
        $query="DELETE FROM $tmp01 where kodeid IN ('3')";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        /*
        $query = "select a.idinput, a.tgl, a.tglspd, a.kodeid, b.nama, a.subkode, b.subnama, a.divisi, 
            IFNULL(a.nomor,'') nomor, a.nodivisi, a.nodivisi2, a.jumlah, a.jumlah2  
            from dbmaster.t_suratdana_br a JOIN dbmaster.t_kode_spd b on a.kodeid=b.kodeid and a.subkode=b.subkode
            WHERE IFNULL(a.stsnonaktif,'') <> 'Y' AND a.nodivisi IN (select distinct IFNULL(nodivisi2,'') FROM $tmp05) ";
        $query = "create TEMPORARY table $tmp06 ($query)";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        
        $query="UPDATE $tmp05 a JOIN $tmp01 b
            on a.nomor=b.nomor SET a.tglspd=b.tglspd";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        
        $query="UPDATE $tmp05 SET kodeid=NULL, subkode=NULL, divisi=NULL";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        
        $query="UPDATE $tmp05 a JOIN $tmp06 b
            on a.nodivisi=b.nodivisi SET a.kodeid=b.kodeid, a.subkode=b.subkode, a.divisi=b.divisi";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        $query="UPDATE $tmp05 a SET a.kodeid='1', a.subkode='01', a.divisi=''";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        
        mysqli_query($cnmy, "DROP TABLE $tmp06");
         
          
         * 
         */
        
        $query = "select a.idinput, a.tgl, a.tglspd, a.kodeid, b.nama, a.subkode, b.subnama, a.divisi, 
            IFNULL(a.nomor,'') nomor, a.nodivisi, a.nodivisi2, a.jumlah, a.jumlah2  
            from $tmp05 a JOIN dbmaster.t_kode_spd b on a.kodeid=b.kodeid and a.subkode=b.subkode";
        $query = "create TEMPORARY table $tmp06 ($query)";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        $query = "UPDATE $tmp01 SET tadj='1'";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        $query = "INSERT INTO $tmp01 (idinput, tgl, tglspd, kodeid, nama, subkode, subnama, divisi, 
            nomor, nodivisi, nodivisi2, jumlah, jumlah2, tadj) 
            SELECT idinput, tgl, tglspd, kodeid, nama, subkode, subnama, divisi, 
            nomor, nodivisi, nodivisi2, jumlah, jumlah2, '3' tadj FROM $tmp06";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        //END ADJUSTMENT
        
        
        
        
        
        
        mysqli_query($cnmy, "UPDATE $tmp01 set nodivisi=idinput WHERE IFNULL(nodivisi,'')=''");
        
        //mysqli_query($cnmy, "UPDATE $tmp01 set tglspd=CURRENT_DATE() WHERE IFNULL(tglspd,'0000-00-00')='0000-00-00'");
        mysqli_query($cnmy, "UPDATE $tmp01 set tglspd=DATE_FORMAT(tgl,'%Y-%m-01') WHERE IFNULL(tglspd,'0000-00-00')='0000-00-00'");
        
        $query = "SELECT DISTINCT kodeid, nama, subkode, subnama, divisi, nodivisi, nomor FROM $tmp01";
        $query = "create TEMPORARY table $tmp02 ($query)";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        mysqli_query($cnmy, "UPDATE $tmp02 SET nodivisi=''");
        
        $query = "select kodeid, nama, subkode, subnama, divisi, nomor, COUNT(nomor) nodiv from $tmp02 group by 1,2,3,4,5,6";
        $query = "create TEMPORARY table $tmp04 ($query)";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        mysqli_query($cnmy, "UPDATE $tmp02 a SET a.nodivisi=(select b.nodiv From$tmp04 b WHERE a.kodeid=b.kodeid AND "
                . "a.subkode=b.subkode AND a.nomor=b.nomor and a.divisi=b.divisi LIMIT 1)");
        mysqli_query($cnmy, "UPDATE $tmp02 SET nomor='' where IFNULL(nodivisi,'1')='1' OR IFNULL(nodivisi,'0')='0'");
        
        mysqli_query($cnmy, "Delete From $tmp04");
        mysqli_query($cnmy, "ALTER TABLE $tmp04 modify column nodiv INT NOT NULL AUTO_INCREMENT PRIMARY KEY");
        mysqli_query($cnmy, "INSERT INTO $tmp04(kodeid, nama, subkode, subnama, divisi, nomor) SELECT kodeid, nama, subkode, subnama, divisi, nomor FROM $tmp02 WHERE IFNULL(nomor,'')<>''");
        mysqli_query($cnmy, "DELETE FROM $tmp02 WHERE IFNULL(nomor,'')<>''");
        mysqli_query($cnmy, "INSERT INTO $tmp02(kodeid, nama, subkode, subnama, divisi, nomor, nodivisi) SELECT kodeid, nama, subkode, subnama, divisi, nomor, nodiv FROM $tmp04");
        
        
        $query = "SELECT kodeid, nama, subkode, subnama, divisi, tglspd, nomor, nodivisi, jumlah FROM $tmp01 LIMIT 1";
        $query = "create TEMPORARY table $tmp03 ($query)";
        mysqli_query($cnmy, $query);
        mysqli_query($cnmy, "ALTER TABLE $tmp03 ADD nourut INT(4)");
        mysqli_query($cnmy, "DELETE FROM $tmp03");

        $x=1;
        $query = "select distinct tglspd, nomor from $tmp01 ORDER BY tglspd, nomor";
        $sql= mysqli_query($cnmy, $query);
        while ($r= mysqli_fetch_array($sql)) {
            $pnospd=$r['nomor'];
            $ptglspd=$r['tglspd'];
            
            $query = "INSERT INTO $tmp03 (nourut, kodeid, nama, subkode, subnama, divisi, tglspd, nomor, nodivisi, jumlah)"
                    . "SELECT $x nourut, kodeid, nama, subkode, subnama, divisi, tglspd, nomor, nodivisi, jumlah FROM $tmp01 WHERE "
                    . " tglspd='$ptglspd' AND nomor='$pnospd'";
            mysqli_query($cnmy, $query);
            $x++;
        }
        
        
        $fnodivisi="";
        echo "<table class='tjudul' width='100%'>";
        echo "<tr> <td width='300px'><b>Surat Permintaan Dana</b></td> <td> &nbsp; </td> <td>&nbsp;</td> </tr>";
        //echo "<tr> <td width='200px'>&nbsp; </td> <td> &nbsp; </td> <td>&nbsp;</td> </tr>";
        echo "</table>";
        echo "<br/>&nbsp;";
        
        
        
        ?>
<style>

table {
    text-align: left;
    position: relative;
    border-collapse: collapse; 
}

th {
    background: white;
    position: sticky;
    top: 0;
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
}

.th2 {
    background: white;
    position: sticky;
    top: 23;
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
    border-top: 1px solid #000;
}
</style>



        <?PHP
        $njmlrec=0;
        echo "<table id='datatable2' class='table table-striped table-bordered example_2' border='1px solid black'>";
        
        $query = "select distinct tglspd, nomor FROM $tmp01 order by nomor, tglspd";
        $tampil=mysqli_query($cnmy, $query);
        echo "<tr>";
        
        echo "<thead>";
        
        echo "<th>&nbsp;</th>";
        echo "<th>&nbsp;</th>";
        while ($row= mysqli_fetch_array($tampil)) {
            $pnospd=$row['nomor'];
            
            $nprint_spd_1=$pnospd;
            if ($ppilihrpt!="excel") {
                
                if ($pses_grpuser!="1" AND $pses_grpuser!="22" AND $pses_grpuser!="24" AND $pses_grpuser!="34" AND $pses_grpuser!="25") {
                }else{
                    $nprint_spd_1="<a title='Print / Cetak' href='#' class='btn btn-primary btn-xs' data-toggle='modal' "
                        . "onClick=\"window.open('eksekusi3.php?module=suratpd&brid=$pnospd&iprint=print',"
                        . "'Ratting','width=700,height=500,left=500,top=100,scrollbars=yes,toolbar=yes,status=1,pagescrool=yes')\"> "
                        . "$pnospd</a>";
                }
                
            }
            echo "<th>&nbsp;</th>";
            echo "<th>&nbsp;</th>";
            echo "<th nowrap>No. : $nprint_spd_1</th>";
            $njmlrec++;
        }
        echo "</tr>";
        
        $query = "select distinct tglspd, nomor FROM $tmp01 order by nomor, tglspd";
        $tampil=mysqli_query($cnmy, $query);
        echo "<tr>";
        echo "<th class='th2'>&nbsp;</th>";
        echo "<th class='th2'>&nbsp;</th>";
        while ($row= mysqli_fetch_array($tampil)) {
            $ptglspd =date("d-M-Y", strtotime($row['tglspd']));
            echo "<th class='th2'>No. Divisi</th>";
            echo "<th class='th2'>&nbsp;</th>";
            echo "<th class='th2' nowrap>Jakarta, $ptglspd</th>";
        }
        echo "</tr>";
        
        echo "<tr>";
        echo "<td>&nbsp;</td>";
        echo "<td>&nbsp;</td>";
        for ($x = 1; $x <= $njmlrec; $x++) {
            echo "<td>&nbsp;</td>";
            echo "<td>&nbsp;</td>";
            echo "<td>&nbsp;</td>";
        }
        echo "</tr>";
        
        echo "</thead>";
        
        //goto hapusdata;
        
        echo "<tbody>";
        
        $query = "select kodeid, nama, sum(jumlah) jumlah FROM $tmp01 WHERE IFNULL(tadj,'') NOT IN ('3') group by 1,2 order by kodeid, nama";
        $tampil=mysqli_query($cnmy, $query);
        
        while ($row= mysqli_fetch_array($tampil)) {
            $pkodeid=$row['kodeid'];
            $pnmkodeid=$row['nama'];
            //$pnama="Advance=Reimbursement";
            //if ((INT)$pkodeid==2) $pnama="KLAIM -SPD900 JUTA";
            $pnama="Advance";
            if ((INT)$pkodeid==2) $pnama="KLAIM - PETTY CASH 1,1 M";
            elseif ((INT)$pkodeid==6) $pnama="KASBON SURABAYA";
            
            echo "<tr>";
            echo "<td nowrap><b>$pnama</b></td>";
            echo "<td>&nbsp;</td>";
                
            //SUMMARY
                $njmlsisa=0;
                $query2 = "select distinct tglspd, nomor FROM $tmp01 order by nomor, tglspd";
                $tampil2=mysqli_query($cnmy, $query2);
                while ($row2= mysqli_fetch_array($tampil2)) {
                    $pnospd=$row2['nomor'];
                    $ptglspd=$row2['tglspd'];
                    
                    $query3 = "select SUM(jumlah) jumlah FROM $tmp01 WHERE IFNULL(tadj,'') NOT IN ('3') AND kodeid='$pkodeid' AND nomor='$pnospd' and tglspd='$ptglspd'";
                    $tampil3=mysqli_query($cnmy, $query3);
                    $ketemu= mysqli_num_rows($tampil3);
                    if ($ketemu>0) {
                        while ($row3= mysqli_fetch_array($tampil3)) {
                            $pjumlah=$row3['jumlah'];
                            $pjumlah=number_format($pjumlah,0,",",",");
                            if ($pjumlah==0) $pjumlah="";
                            echo "<td>&nbsp;</td>";
                            echo "<td>&nbsp;</td>";
                            echo "<td align='right' nowrap><b>$pjumlah</b></td>";
                        }
                    }else{
                        echo "<td>&nbsp;</td>";
                        echo "<td>&nbsp;</td>";
                        echo "<td>&nbsp;</td>";
                    }
                    
                }
                    
            echo "</tr>";
            //END SUMMARY
            
            echo "<tr>";
            echo "<td>&nbsp;</td>";
            echo "<td>&nbsp;</td>";
            for ($x = 1; $x <= $njmlrec; $x++) {
                echo "<td>&nbsp;</td>";
                echo "<td>&nbsp;</td>";
                echo "<td>&nbsp;</td>";
            }
            echo "</tr>";
            
            
            
                //DETAIL
            
                $query4 = "select distinct nodivisi, subkode, subnama, divisi FROM $tmp02 WHERE kodeid='$pkodeid' order by subkode, subnama, divisi";
                $tampil4=mysqli_query($cnmy, $query4);
                
                while ($row4= mysqli_fetch_array($tampil4)) {
                    $pstsadj="";//$row4['tadj'];
                    $psubkode=$row4['subkode'];
                    $pnmsub=$row4['subnama'];
                    $pdivisi=$row4['divisi'];
                    $ndivisino=$row4['nodivisi'];
                    
                    $nwarna_t="";
                    if ($pstsadj=="3") $nwarna_t=" style='color:red;' ";
                    echo "<tr $nwarna_t>";
                    echo "<td nowrap>$pnmsub $pdivisi</td>";
                    echo "<td>:</td>";
                    
                    
                        //$fnodivisi="";
                        $njmlsisa=0;
                        $query5 = "select distinct tglspd, nomor FROM $tmp01 order by nomor, tglspd";
                        $tampil5=mysqli_query($cnmy, $query5);
                        while ($row5= mysqli_fetch_array($tampil5)) {
                            $pnospd5=$row5['nomor'];
                            $ptglspd5=$row5['tglspd'];
                            
                            $filnodiv="";
                            if (!empty($fnodivisi)) {
                                $filnodiv=" AND nodivisi NOT IN (".substr($fnodivisi, 0, -1).")";
                            }
                            
                            
                            $query6 = "select idinput, subkode, nodivisi, jumlah, tadj FROM $tmp01 WHERE kodeid='$pkodeid' AND nomor='$pnospd5' and tglspd='$ptglspd5' and IFNULL(divisi,'')='$pdivisi' AND subkode='$psubkode' $filnodiv LIMIT 1";
                            $tampil6=mysqli_query($cnmy, $query6);
                            $ketemu= mysqli_num_rows($tampil6);
                            if ($ketemu>0) {
                                while ($row6= mysqli_fetch_array($tampil6)) {
                                    $pnidinput=$row6['idinput'];
                                    $pnsubdiv=$row6['subkode'];
                                    $pnodivisi=$row6['nodivisi'];
                                    $pjumlah=$row6['jumlah'];
                                    $pstsadj=$row6['tadj'];
                                    $pjumlah=number_format($pjumlah,0,",",",");
                                    $fnodivisi=$fnodivisi."'".$pnodivisi."',";
                                    
                                    $ndivisino=$pnodivisi;
                                    if ($pnsubdiv=="25" OR $pnsubdiv=="26" OR $pnsubdiv=="27" OR 
                                            $pnsubdiv=="28" OR $pnsubdiv=="29" OR $pnsubdiv=="30" OR $pnsubdiv=="31" OR $pnsubdiv=="32") {
                                        
                                        $ndivisino="";
                                    }
                                    
                                    if ($ppilihrpt=="excel" OR ($pkodeid=="2" AND ($pnsubdiv=="23x" OR $pnsubdiv=="22x"))) {
                                        echo "<td nowrap>$ndivisino</td>";
                                    }else{
                                        $n_div=$pdivisi;
                                        if ($pkodeid=="2" AND $pnsubdiv=="21") $n_div="LK";
                                        if ($pkodeid=="1" AND $pnsubdiv=="03") $n_div="RUTIN";
                                        if ($pkodeid=="2" AND $pnsubdiv=="22") $n_div="KAS";
                                        if ($pkodeid=="2" AND $pnsubdiv=="23") $n_div="KASCOR";
                                        if ($pkodeid=="1" AND $pnsubdiv=="04") $n_div="INSENTIF";
                                        if ($pdivisi=="OTC") $n_div=$pdivisi;
                                        
                                        $a_warna="nbiasa";
                                        if ($pstsadj==3) $a_warna="nwarna";
                                        echo "<td nowrap><a class='$a_warna' href='eksekusi3.php?module=glreportspddetail&act=input&idmenu=$_GET[idmenu]&ket=bukan&divisi=$n_div&nodivisi=$ndivisino&idinspd=$pnidinput' target='_blank'>$ndivisino</a></td>";
                                    }
                                    echo "<td nowrap>Rp.</td>";
                                    echo "<td align='right' nowrap>$pjumlah</td>";
                                    
                                }
                            }else{
                                echo "<td>&nbsp;</td>";
                                echo "<td>&nbsp;</td>";
                                echo "<td>&nbsp;</td>";
                            }
                            
                            
                            
                        }

                        
                        
                    echo "</tr>";
                }
                
                //END DETAIL
            
            echo "<tr>";
            echo "<td>&nbsp;</td>";
            echo "<td>&nbsp;</td>";
            for ($x = 1; $x <= $njmlrec; $x++) {
                echo "<td>&nbsp;</td>";
                echo "<td>&nbsp;</td>";
                echo "<td>&nbsp;</td>";
            }
            echo "</tr>";
            
        }
        
        
        $njmlrec=0;
        // total tanpa adjusment
        $query = "select tglspd, nomor, sum(jumlah) jumlah FROM $tmp01 where IFNULL(tadj,'') NOT IN  ('3') group by 1,2 order by nomor, tglspd";
        $tampilsmr=mysqli_query($cnmy, $query);
        echo "<tr>";
        echo "<td><b>TOTAL</b></td>";
        echo "<td>&nbsp;</td>";
        while ($smr= mysqli_fetch_array($tampilsmr)) {
            $pnospd_smr=$smr['nomor'];
            $pjumlah_smr=$smr['jumlah'];
            $pjumlah_smr=number_format($pjumlah_smr,0,",",",");
            echo "<td>&nbsp;</td>";
            echo "<td>&nbsp;</td>";
            echo "<td nowrap align='right'><b>$pjumlah_smr</b></td>";
            $njmlrec++;
        }
        echo "</tr>";
        
        
        
        // total adjusment
        $query = "select distinct tglspd, nomor FROM $tmp01 where IFNULL(tadj,'') NOT IN  ('3') order by nomor, tglspd";
        $tampilsmr=mysqli_query($cnmy, $query);
        echo "<tr>";
        echo "<td><b>TOTAL ADJUSMENT</b></td>";
        echo "<td>&nbsp;</td>";
        while ($smr= mysqli_fetch_array($tampilsmr)) {
            $pnospd_smr=$smr['nomor'];
            
            $query = "select tglspd, nomor, sum(jumlah) jumlah FROM $tmp01 where nomor='$pnospd_smr' AND IFNULL(tadj,'') IN ('3') group by 1,2 order by nomor, tglspd";
            $tampiladj=mysqli_query($cnmy, $query);
            $ketemuadj=mysqli_num_rows($tampiladj);
            if ($ketemuadj>0) {
                
                while ($adj= mysqli_fetch_array($tampiladj)) {
                    $pnospd_adj=$adj['nomor'];
                    $pjumlah_adj=$adj['jumlah'];
                    $pjumlah_adj=number_format($pjumlah_adj,0,",",",");
                    echo "<td>&nbsp;</td>";
                    echo "<td>&nbsp;</td>";
                    echo "<td nowrap align='right'><b>$pjumlah_adj</b></td>";
                }
                
            }else{
                echo "<td>&nbsp;</td>";
                echo "<td>&nbsp;</td>";
                echo "<td>&nbsp;</td>";
            }
        }
        echo "</tr>";
        
        
        
        
        $njmlrec=0;
        // grand total
        $query = "select tglspd, nomor, sum(jumlah) jumlah FROM $tmp01 group by 1,2 order by nomor, tglspd";
        $tampilsmr=mysqli_query($cnmy, $query);
        echo "<tr>";
        echo "<td><b>GRAND TOTAL</b></td>";
        echo "<td>&nbsp;</td>";
        while ($smr= mysqli_fetch_array($tampilsmr)) {
            $pnospd_smr=$smr['nomor'];
            $pjumlah_smr=$smr['jumlah'];
            $pjumlah_smr=number_format($pjumlah_smr,0,",",",");
            echo "<td>&nbsp;</td>";
            echo "<td>&nbsp;</td>";
            echo "<td nowrap align='right'><b>$pjumlah_smr</b></td>";
            $njmlrec++;
        }
        echo "</tr>";
        
        echo "</tbody>";
        
        echo "</table>";
    ?>
    <br/>&nbsp;
    <br/>&nbsp;
    <br/>&nbsp;

    <?PHP
    hapusdata:
        mysqli_query($cnmy, "DROP TEMPORARY TABLE $tmp01");
        mysqli_query($cnmy, "DROP TEMPORARY TABLE $tmp02");
        mysqli_query($cnmy, "DROP TEMPORARY TABLE $tmp03");
        mysqli_query($cnmy, "DROP TEMPORARY TABLE $tmp04");
        mysqli_query($cnmy, "DROP TEMPORARY TABLE $tmp05");
        mysqli_query($cnmy, "DROP TEMPORARY TABLE $tmp06");
        
        mysqli_close($cnmy);
    ?>
    </form>
    <style>
        .nbiasa {
          color: black;
        }
        .nwarna {
          color: red;
        }
    </style>
</body>
</html>

<?PHP
}
?>

