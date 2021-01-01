<?PHP
    session_start();
    $ppilihrpt=$_GET['ket'];
    if ($ppilihrpt=="excel") {
        // Fungsi header dengan mengirimkan raw data excel
        header("Content-type: application/vnd-ms-excel");
        // Mendefinisikan nama file ekspor "hasil-export.xls"
        header("Content-Disposition: attachment; filename=REKAP SPD.xls");
    }
    
    include("config/koneksimysqli.php");
    include("config/common.php");
    $cnit=$cnmy;
?>


<head>
    <title>Rekap Surat Permintaan Dana</title>
</head>


    <form method='POST' action='<?PHP echo "?module=$_GET[module]&act=input&idmenu=$_GET[idmenu]"; ?>' id='demo-form2' name='form1' data-parsley-validate class='form-horizontal form-label-left' target="_blank">
    <?PHP
        include "config/koneksimysqli.php";
        include "config/fungsi_combo.php";
        $tgl01=$_POST['bulan1'];
        $periode1= date("Ym", strtotime($tgl01));
        
        $now=date("mdYhis");
        $tmp01 =" dbtemp.RPTREKOTCF01_".$_SESSION['USERID']."_$now ";
        $tmp02 =" dbtemp.RPTREKOTCF02_".$_SESSION['USERID']."_$now ";
        $tmp03 =" dbtemp.RPTREKOTCF03_".$_SESSION['USERID']."_$now ";
        $tmp04 =" dbtemp.RPTREKOTCF04_".$_SESSION['USERID']."_$now ";
        
        $query = "select a.idinput, a.tgl, a.tglspd, a.kodeid, b.nama, a.subkode, b.subnama, a.divisi, 
            IFNULL(a.nomor,'') nomor, a.nodivisi, a.jumlah 
            from dbmaster.t_suratdana_br a JOIN dbmaster.t_kode_spd b on a.kodeid=b.kodeid and a.subkode=b.subkode
            WHERE IFNULL(a.stsnonaktif,'') <> 'Y' and a.pilih='Y' AND 
            DATE_FORMAT(tglspd,'%Y%m')='$periode1'";
        //echo "$query";exit;
        $query = "create TEMPORARY table $tmp01 ($query)";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
        
        mysqli_query($cnmy, "UPDATE $tmp01 set nodivisi=idinput WHERE IFNULL(nodivisi,'')=''");
        
        mysqli_query($cnmy, "UPDATE $tmp01 set tglspd=tgl WHERE IFNULL(tglspd,'0000-00-00')='0000-00-00'");
        
        $query = "SELECT DISTINCT kodeid, nama, subkode, subnama, divisi, nodivisi, nomor FROM $tmp01";
        $query = "create TEMPORARY table $tmp02 ($query)";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
        mysqli_query($cnmy, "UPDATE $tmp02 SET nodivisi=''");
        
        $query = "select kodeid, nama, subkode, subnama, divisi, nomor, COUNT(nomor) nodiv from $tmp02 group by 1,2,3,4,5,6";
        $query = "create TEMPORARY table $tmp04 ($query)";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
        
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
        
        $njmlrec=0;
        
        echo "<table id='datatable' class='datatable table nowrap table-striped table-bordered' width='100%'>";
        $query = "select distinct tglspd, nomor FROM $tmp01 order by nomor, tglspd";
        $tampil=mysqli_query($cnmy, $query);
        echo "<thead>";
        echo "<tr>";
        echo "<td>&nbsp;</td>";
        echo "<td>&nbsp;</td>";
        while ($row= mysqli_fetch_array($tampil)) {
            $pnospd=$row['nomor'];
            echo "<td>&nbsp;</td>";
            echo "<td>&nbsp;</td>";
            echo "<td nowrap>No. : $pnospd</td>";
            $njmlrec++;
        }
        echo "</tr>";
        
        $query = "select distinct tglspd, nomor FROM $tmp01 order by nomor, tglspd";
        $tampil=mysqli_query($cnmy, $query);
        echo "<tr>";
        echo "<td>&nbsp;</td>";
        echo "<td>&nbsp;</td>";
        while ($row= mysqli_fetch_array($tampil)) {
            $ptglspd =date("d-M-Y", strtotime($row['tglspd']));
            echo "<td>No. Divisi</td>";
            echo "<td>&nbsp;</td>";
            echo "<td nowrap>Jakarta, $ptglspd</td>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        
        echo "<tr>";
        echo "<td>&nbsp;</td>";
        echo "<td>&nbsp;</td>";
        for ($x = 1; $x <= $njmlrec; $x++) {
            echo "<td>&nbsp;</td>";
            echo "<td>&nbsp;</td>";
            echo "<td>&nbsp;</td>";
        }
        echo "</tr>";
        
        //goto hapusdata;
        
        $query = "select kodeid, nama, sum(jumlah) jumlah FROM $tmp01 group by 1,2 order by kodeid, nama";
        $tampil=mysqli_query($cnmy, $query);
        
        while ($row= mysqli_fetch_array($tampil)) {
            $pkodeid=$row['kodeid'];
            $pnmkodeid=$row['nama'];
            //$pnama="Advance=Reimbursement";
            //if ((INT)$pkodeid==2) $pnama="KLAIM -SPD900 JUTA";
            $pnama="Advance";
            if ((INT)$pkodeid==2) $pnama="KLAIM -PETTY CASH 900 JUTA";
            
            echo "<tr>";
            echo "<td><b>$pnama</b></td>";
            echo "<td>&nbsp;</td>";
                
            //SUMMARY
                $njmlsisa=0;
                $query2 = "select distinct tglspd, nomor FROM $tmp01 order by nomor, tglspd";
                $tampil2=mysqli_query($cnmy, $query2);
                while ($row2= mysqli_fetch_array($tampil2)) {
                    $pnospd=$row2['nomor'];
                    $ptglspd=$row2['tglspd'];
                    
                    $query3 = "select SUM(jumlah) jumlah FROM $tmp01 WHERE kodeid='$pkodeid' AND nomor='$pnospd' and tglspd='$ptglspd'";
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
                    $psubkode=$row4['subkode'];
                    $pnmsub=$row4['subnama'];
                    $pdivisi=$row4['divisi'];
                    $ndivisino=$row4['nodivisi'];
                    
                    echo "<tr>";
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
                            
                            
                            $query6 = "select subkode, nodivisi, jumlah FROM $tmp01 WHERE kodeid='$pkodeid' AND nomor='$pnospd5' and tglspd='$ptglspd5' and IFNULL(divisi,'')='$pdivisi' AND subkode='$psubkode' $filnodiv LIMIT 1";
                            $tampil6=mysqli_query($cnmy, $query6);
                            $ketemu= mysqli_num_rows($tampil6);
                            if ($ketemu>0) {
                                while ($row6= mysqli_fetch_array($tampil6)) {
                                    $pnsubdiv=$row6['subkode'];
                                    $pnodivisi=$row6['nodivisi'];
                                    $pjumlah=$row6['jumlah'];
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
                                        if ($pdivisi=="OTC") $n_div=$pdivisi;
                                        echo "<td nowrap><a href='eksekusi3.php?module=glreportspddetail&act=input&idmenu=$_GET[idmenu]&ket=bukan&divisi=$n_div&nodivisi=$ndivisino' target='_blank'>$ndivisino</a></td>";
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
        $query = "select tglspd, nomor, sum(jumlah) jumlah FROM $tmp01 where kodeid NOT IN  ('3') group by 1,2 order by nomor, tglspd";
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
        $query = "select distinct tglspd, nomor FROM $tmp01 where kodeid NOT IN  ('3') order by nomor, tglspd";
        $tampilsmr=mysqli_query($cnmy, $query);
        echo "<tr>";
        echo "<td><b>TOTAL ADJUSMENT</b></td>";
        echo "<td>&nbsp;</td>";
        while ($smr= mysqli_fetch_array($tampilsmr)) {
            $pnospd_smr=$smr['nomor'];
            
            $query = "select tglspd, nomor, sum(jumlah) jumlah FROM $tmp01 where nomor='$pnospd_smr' AND kodeid IN ('3') group by 1,2 order by nomor, tglspd";
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
        
        mysqli_close($cnmy);
    ?>
    </form>
    <style>
        a {
          color: black;
        }
    </style>

<script>
    $(document).ready(function() {
        var dataTable = $('#datatable').DataTable( {
            "ordering": false,
            bFilter: false, bInfo: false, "bLengthChange": false, "bLengthChange": false,
            "bPaginate": false,
            "scrollY": 440,
            "scrollX": true ,
            fixedColumns:   {
                leftColumns: 1
            }
        } );
    });
</script>

<style>
    .divnone {
        display: none;
    }
    #datatablespggj th {
        font-size: 12px;
    }
    #datatablespggj td { 
        font-size: 11px;
    }
    .imgzoom:hover {
        -ms-transform: scale(3.5); /* IE 9 */
        -webkit-transform: scale(3.5); /* Safari 3-8 */
        transform: scale(3.5);

    }


    .form-group, .input-group, .control-label {
        margin-bottom:2px;
    }
    .control-label {
        font-size:12px;
    }
    #datatable input[type=text], #tabelnobr input[type=text] {
        box-sizing: border-box;
        color:#000;
        font-size:10px;
        height: 25px;
    }
    select.soflow {
        font-size:11px;
        height: 30px;
    }
    .disabledDiv {
        pointer-events: none;
        opacity: 0.4;
    }

    table.datatable, table.tabelnobr {
        color: #000;
        font-family: Helvetica, Arial, sans-serif;
        width: 100%;
        border-collapse:
        collapse; border-spacing: 0;
        font-size: 11px;
        border: 0px solid #000;
    }

    table.datatable td, table.tabelnobr td {
        border: 1px solid #000; /* No more visible border */
        height: 10px;
        transition: all 0.1s;  /* Simple transition for hover effect */
    }

    table.datatable th, table.tabelnobr th {
        background: #DFDFDF;  /* Darken header a bit */
        font-weight: bold;
    }

    table.datatable td, table.tabelnobr td {
        background: #FAFAFA;
    }

    /* Cells in even rows (2,4,6...) are one color */
    tr:nth-child(even) td { background: #F1F1F1; }

    /* Cells in odd rows (1,3,5...) are another (excludes header cells)  */
    tr:nth-child(odd) td { background: #FEFEFE; }

    tr td:hover.biasa { background: #666; color: #FFF; }
    tr td:hover.left { background: #ccccff; color: #000; }

    tr td.center1, td.center2 { text-align: center; }

    tr td:hover.center1 { background: #666; color: #FFF; text-align: center; }
    tr td:hover.center2 { background: #ccccff; color: #000; text-align: center; }
    /* Hover cell effect! */
    tr td {
        padding: -10px;
    }

</style>