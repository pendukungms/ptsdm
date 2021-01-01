<?php
    session_start();
    include "../../../config/koneksimysqli.php";
    
    $pses_grpuser=$_SESSION['GROUP'];
    $pses_divisi=$_SESSION['DIVISI'];
    $pses_idcard=$_SESSION['IDCARD'];
    
    
    $cket = $_POST['eket'];
    $mytgl1 = $_POST['uperiode1'];
    $mytgl2 = $_POST['uperiode2'];
    
    
    $tgl1= date("Y-m", strtotime($mytgl1));
    $tgl2= date("Y-m", strtotime($mytgl2));
    
    $now=date("mdYhis");
    $tmp01 =" dbtemp.RPTREKOTCFA01_".$_SESSION['USERID']."_$now ";
    $tmp02 =" dbtemp.RPTREKOTCFA02_".$_SESSION['USERID']."_$now ";
    $tmp03 =" dbtemp.RPTREKOTCFA03_".$_SESSION['USERID']."_$now ";
    
    $sql = "SELECT idinput, DATE_FORMAT(tgl,'%M %Y') bulan, DATE_FORMAT(tgl,'%d/%m/%Y') as tgl, DATE_FORMAT(tglf,'%M %Y') as tglf,
        divisi, kodeid, nama, subkode, subnama, jumlah, jumlah2, 
        nomor, nodivisi, pilih, karyawanid, jenis_rpt, userproses, ifnull(tgl_proses,'0000-00-00') tgl_proses, ifnull(tgl_dir,'0000-00-00') tgl_dir
        , ifnull(tgl_dir2,'0000-00-00') tgl_dir2, ifnull(tgl_apv1,'0000-00-00') tgl_apv1, ifnull(tgl_apv2,'0000-00-00') tgl_apv2, tglf as tglmasuk, tglf tglkeluar ";
    $sql.=" FROM dbmaster.v_suratdana_br ";
    $sql.=" WHERE 1=1 ";
    $sql.=" AND ( (Date_format(tgl, '%Y-%m') between '$tgl1' and '$tgl2') OR (Date_format(tglspd, '%Y-%m') between '$tgl1' and '$tgl2') ) ";
    
    $sql.=" AND IFNULL(stsnonaktif,'') <> 'Y' ";
    
    if ($pses_grpuser!="1" AND $pses_grpuser!="22" AND $pses_grpuser!="24" AND $pses_grpuser!="34") {
        $sql.=" AND IFNULL(userid,'')='$pses_idcard' ";
    }else{
        if ($pses_grpuser=="34") {//surabaya
            $sql.=" AND (IFNULL(tgl_apv1,'')<>'' OR Date_format(tgl, '%Y%m') <= '201908' )";
            $sql.=" AND CONCAT(kodeid, subkode) IN (select distinct CONCAT(kodeid, subkode) FROM dbmaster.t_kode_spd where IFNULL(sby,'')='Y' )";
            $sql.=" AND IFNULL(pilih,'')='Y' ";
            $sql.=" AND IFNULL(nomor,'')<>'' ";
        }
    }
    $sql.=" AND subkode NOT IN ('25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35') ";
    $sql.=" AND kodeid NOT IN ('3') ";
    //echo $sql;
    
    $query = "create TEMPORARY table $tmp01 ($sql)";
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
    
    mysqli_query($cnmy, "UPDATE $tmp01 set jumlah=IFNULL(jumlah,0)+IFNULL(jumlah2,0) WHERE subkode in ('01', '02', '20')");
    
    $query = "select a.idinput, a.stsinput, a.nodivisi, a.tanggal, a.nobukti, a.jumlah 
        from dbmaster.t_suratdana_bank a 
        JOIN $tmp01 b on a.idinput=b.idinput and a.subkode=b.subkode 
        WHERE a.stsnonaktif<>'Y' AND a.stsinput IN ('N', 'K') 
        AND a.tanggal=(select MIN(k.tanggal) FROM dbmaster.t_suratdana_bank k WHERE 
        a.idinput=k.idinput AND a.stsinput=k.stsinput)";
    $query = "create TEMPORARY table $tmp02 ($query)";
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
    
    
    mysqli_query($cnmy, "UPDATE $tmp01 SET tglmasuk=NULL, tglkeluar=NULL");
    
    $query = "UPDATE $tmp01 a SET a.tglmasuk=(select b.tanggal from $tmp02 b WHERE a.idinput=b.idinput AND b.stsinput='N' order by b.tanggal LIMIT 1)";
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
    
    $query = "UPDATE $tmp01 a SET a.tglkeluar=(select b.tanggal from $tmp02 b WHERE a.idinput=b.idinput AND b.stsinput='K' order by b.tanggal LIMIT 1)";
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; exit; }
    
?>

<form method='POST' action='' id='d-form4' name='form4' data-parsley-validate class='form-horizontal form-label-left'>
    
    
    <div class='x_content' style="margin-left:-20px; margin-right:-20px;">
        
            <div class="title_left">
                <h4 style="font-size : 12px;">
                    <?PHP
                        $noteket = strtoupper($cket);
                        $text="";
                        echo "<b>$text"
                                . "<p/>&nbsp;*) <span style='color:red;'>klik no divisi/nobr untuk melihat detail pengajuan</span></b>";
                    ?>
                </h4>
            </div>
        <div class="clearfix"></div>
        
        <table id='dtablecadir' class='table table-striped table-bordered' width='100%'>
            <thead>
                <tr>
                    <th width='10px'>No</th>
                    <th width='100px'>No Divisi/NOBR</th>
                    <th width='100px'>No SPD</th>
                    <th width='50px'>Jumlah</th>
                    <th width='30px'>Divisi</th>
                    <th width='50px'>Tgl Pengajuan</th>
                    <th width='50px'>Tgl. Masuk/SBY</th>
                    <th width='50px'>Tgl. Transfer</th>
                    <th width='30px'>Pengajuan</th>
                    <th width='30px'>Finance</th>
                    <th width='30px'>Checker</th>
                    <th width='30px'>Approved 1</th>
                    <th width='30px'>Approved 2</th>
                </tr>
            </thead>
            <tbody>
            <?PHP
                $sql = "select * from $tmp01 ";
                $sql.=" order by divisi, nodivisi, idinput";
                //echo $sql;
                $no=1;
                $tampil = mysqli_query($cnmy, $sql);
                while ($row= mysqli_fetch_array($tampil)) {
                    $idno=$row['idinput'];
                    $tglbuat = $row["tgl"];
                    $pdivisi = $row["divisi"];
                    $ndivisi=$pdivisi;
                    if (empty($pdivisi)) $ndivisi="ETHICAL";
                    
                    $ptglrptsby = $row["tglmasuk"];
                    $ptglkeluar = $row["tglkeluar"];
                    
                    if (!empty($ptglrptsby) AND $ptglrptsby<>"0000-00-00") $ptglrptsby =date("d M Y", strtotime($ptglrptsby));
                    if (!empty($ptglkeluar) AND $ptglkeluar<>"0000-00-00") $ptglkeluar =date("d M Y", strtotime($ptglkeluar));
                    
                    $pnodivisi = $row["nodivisi"];
                    $pnomorspd = $row["nomor"];
                    $pkode = $row["kodeid"];
                    $psubkode = $row["subkode"];
                    $nama = $row["nama"];
                    $subnama = $row["subnama"];
                    $pkaryawanid=$row['karyawanid'];
                    $pjenisrpt=$row["jenis_rpt"];
                    
                    $pmystsyginput="";
                    if ($pkaryawanid=="0000000566") {
                        $pmystsyginput=1;
                    }elseif ($pkaryawanid=="0000001043") {
                        $pmystsyginput=2;
                    }else{
                        if ( ($pkode=="1" AND $psubkode=="01") OR ($pkode=="2" AND $psubkode=="20") ) {//anne
                            $pmystsyginput=5;
                        }else{
                            if ($pkode=="1" AND $psubkode=="03") {//ria
                                $pmystsyginput=3;
                            }elseif ($pkode=="1" AND $psubkode=="05") {//ria CA SEWA
                                $pmystsyginput=7;
                            }elseif ($pkode=="1" AND $psubkode=="04") {//ria Insentif
                                $pmystsyginput=8;
                            }elseif ($pkode=="2" AND $psubkode=="21") {//marsis
                                $pmystsyginput=4;
                            }elseif ( ($pkode=="2" AND $psubkode=="22") OR ($pkode=="2" AND $psubkode=="23") ) {//marsis
                                $pmystsyginput=6;
                            }
                        }
                    }
        
                    $periode = $row["bulan"];
                    if ($pkode=="1" AND $psubkode=="04") {
                        $periode = $row["tglf"];
                    }
                    
                    $pbulan = $row["tglf"];
                    $jumlah = $row["jumlah"];
                    $ptgldir = $row["tgl_dir"];
                    $ptgldir2 = $row["tgl_dir2"];
                    $ptglfin = $row["tgl_proses"];
                    $papv1 = $row["tgl_apv1"];
                    $papv2 = $row["tgl_apv2"];
                    
                    $jumlah=number_format($jumlah,0,",",",");
                    
                    $pmymodule="";
                    $print=$pnodivisi;
                    if ($pdivisi=="OTC") {
                        if ( ($pkode=="1" AND $psubkode=="03") ) {
                            $pmymodule="module=rekapbiayarutinotc&act=input&idmenu=171&ket=bukan&ispd=$idno";
                        }elseif ( ($pkode=="2" AND $psubkode=="21") ) {
                            $pmymodule="module=rekapbiayaluarotc&act=input&idmenu=245&ket=bukan&ispd=$idno";
                        }else{
                            $pmymodule="module=lapbrotcpermo&act=input&idmenu=134&ket=bukan&ispd=$idno";
                        }
                    }else{
                        if ($pmystsyginput==1) {
                            $pmymodule="module=saldosuratdana&act=rekapbr&idmenu=192&ket=bukan&ispd=$idno&iid=$pmystsyginput";
                        }elseif ($pmystsyginput==2) {
                            if ($pjenisrpt=="D") {
                                $pmymodule="module=saldosuratdana&act=viewbrklaim&idmenu=192&ket=bukan&ispd=$idno&iid=$pmystsyginput";
                            }else{
                                $pmymodule="module=saldosuratdana&act=viewbr&idmenu=192&ket=bukan&ispd=$idno&iid=$pmystsyginput";
                            }
                        }elseif ($pmystsyginput==3) {
                            $pmymodule="module=rekapbiayarutin&act=input&idmenu=190&ket=bukan&ispd=$idno&iid=$pmystsyginput";
                        }elseif ($pmystsyginput==4) {
                            $pmymodule="module=rekapbiayaluar&act=input&idmenu=187&ket=bukan&ispd=$idno&iid=$pmystsyginput";
                        }elseif ($pmystsyginput==5) {
                            $pmymodule="module=saldosuratdana&act=rekapbr&idmenu=204&ket=bukan&ispd=$idno&iid=$pmystsyginput";
                        }elseif ($pmystsyginput==6) {
                            $pmymodule="module=spdkas&act=viewbrho&idmenu=205&ket=bukan&ispd=$idno&bln=$tglbuat";
                        }elseif ($pmystsyginput==7) {
                            $pmymodule="module=reportcasewa&act=rpt&idmenu=264&ket=bukan&ispd=$idno&bln=$tglbuat";
                        }elseif ($pmystsyginput==8) {
                            $pmymodule="module=mstprosesinsentif&act=input&idmenu=262&ket=bukan&ispd=$idno&bln=$tglbuat";
                        }
                    }
                    
                    if (!empty($pmymodule)) {
                        
                        $print="<a style='font-size:11px;' title='Print / Cetak' href='#' class='btn btn-info btn-xs' data-toggle='modal' "
                            . "onClick=\"window.open('eksekusi3.php?$pmymodule',"
                            . "'Ratting','width=800,height=500,left=400,top=100,scrollbars=yes,toolbar=yes,status=1,pagescrool=yes')\"> "
                            . "$pnodivisi</a>";
                        
                    }
                    
                    $apvdir="";
                    $apvdir2="";
                    $apvfin="";
                    $napv1="";
                    $napv2="";
                    
                    if (!empty($ptgldir) AND $ptgldir <> "0000-00-00") $apvdir=date("d F Y, h:i:s", strtotime($ptgldir));
                    if (!empty($ptgldir2) AND $ptgldir2 <> "0000-00-00") $apvdir2=date("d F Y, h:i:s", strtotime($ptgldir2));
                    if (!empty($ptglfin) AND $ptglfin <> "0000-00-00") $apvfin=date("d F Y, h:i:s", strtotime($ptglfin));
                    if (!empty($papv1) AND $papv1 <> "0000-00-00") $napv1=date("d F Y, h:i:s", strtotime($papv1));
                    if (!empty($papv2) AND $papv2 <> "0000-00-00") $napv2=date("d F Y, h:i:s", strtotime($papv2));
                    
                    if ($pkode=="1" AND ($psubkode=="04" OR $psubkode=="05")) {
                        $apvdir=""; $apvdir2=""; $napv2=""; $napv1="";
                    }
                    
                    echo "<tr>";
                    echo "<td>$no</td>";
                    echo "<td>$print</td>";
                    echo "<td>$pnomorspd</td>";
                    echo "<td>$jumlah</td>";
                    echo "<td>$ndivisi</td>";
                    echo "<td>$tglbuat</td>";
                    echo "<td>$ptglrptsby</td>";
                    echo "<td>$ptglkeluar</td>";
                    echo "<td>$nama</td>";
                    echo "<td>$napv1</td>";
                    echo "<td>$napv2</td>";
                    echo "<td>$apvdir</td>";
                    echo "<td>$apvdir2</td>";
                    
                    echo "</tr>";
                    $no++;
                    
                }
            ?>
            </tbody>
            
        </table>
        
        
    </div>
    
    
    <div class='clearfix'></div>
</form>

<script>
    
    $(document).ready(function() {
        //alert(etgl1);
        var dataTable = $('#dtablecadir').DataTable( {
            //"stateSave": true,
            //"order": [[ 7, "desc" ]],
            "lengthMenu": [[10, 50, 100, -1], [10, 50, 100, "All"]],
            "displayLength": 10,
            "columnDefs": [
                { "visible": false },
                { "orderable": false, "targets": 0 },
                { "orderable": false, "targets": 1 },
                { className: "text-right", "targets": [3] },//right
                { className: "text-nowrap", "targets": [0, 1, 2, 3, 4, 5, 6,7,8,9,10,11,12] }//nowrap

            ],
            "language": {
                "zeroRecords": "Lihat Page di bawah!!! Jika ada Page, Pilih Page 1...!!! Jika tidak ada Page, maka data KOSONG..."
            },
            rowReorder: {
                selector: 'td:nth-child(3)'
            },
            responsive: true
        } );
        //$('div.dataTables_filter input', dataTable.table().container()).focus();
    } );
    
</script>


<style>
    .divnone {
        display: none;
    }
    #dtablecadir th {
        font-size: 13px;
    }
    #dtablecadir td { 
        font-size: 11px;
    }
    .imgzoom:hover {
        -ms-transform: scale(3.5); /* IE 9 */
        -webkit-transform: scale(3.5); /* Safari 3-8 */
        transform: scale(3.5);
        
    }
</style>

<?PHP
hapusdata:
    mysqli_query($cnmy, "DROP TEMPORARY TABLE $tmp01");
    mysqli_query($cnmy, "DROP TEMPORARY TABLE $tmp02");
    mysqli_query($cnmy, "DROP TEMPORARY TABLE $tmp03");

    mysqli_close($cnmy);
?>