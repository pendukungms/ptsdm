<?PHP
    session_start();
    
    if (!isset($_SESSION['GROUP'])) {
        echo "ANDA HARUS LOGIN ULANG....!!!";
        exit;
    }
    $pidgroup_user=$_SESSION['GROUP'];
    
    $ppilihrpt=$_GET['ket'];
    
    if ($ppilihrpt=="dariinputanspd") {
        $_POST['tahun']=$_GET['utahun'];
        $_POST['divprodid']=$_GET['udivisi'];
        $_POST['ca_darispd']="N";
        $_POST['lampiran']="T";
    }
    
    $pdivprodid=$_POST['divprodid'];
    if ($ppilihrpt=="excel") {
        // Fungsi header dengan mengirimkan raw data excel
        header("Content-type: application/vnd-ms-excel");
        // Mendefinisikan nama file ekspor "hasil-export.xls"
        header("Content-Disposition: attachment; filename=REKAP OUTSTANDING DANA KLAIM $pdivprodid.xls");
    }
    
    $hariini=date("Y-m-d");
    $ptglview = date("d F Y", strtotime($hariini));
    
    include("config/koneksimysqli.php");
    include("config/common.php");
    $cnit=$cnmy;
    
    $now=date("mdYhis");
    $puserid=$_SESSION['USERID'];
    $tmp00 =" dbtemp.RPTREKOTOTSOTC00_".$puserid."_$now ";
    $tmp01 =" dbtemp.RPTREKOTOTSOTC01_".$puserid."_$now ";
    $tmp02 =" dbtemp.RPTREKOTOTSOTC02_".$puserid."_$now ";
    $tmp03 =" dbtemp.RPTREKOTOTSOTC03_".$puserid."_$now ";
    
    

    
    
    $ptahun=$_POST['tahun'];
    $pcadari=$_POST['ca_darispd'];
    
    $plampiran = $_POST['lampiran'];
    $fillamp="";
    
    if ($plampiran=='L') {
        $fillamp = " and (lampiran='Y' or tgltrm<>'0000-00-00-00') ";
    }elseif ($plampiran=='T') {
        $fillamp = " and ca='Y' and tgltrm='0000-00-00' ";
    }
    
    
    if ($pcadari=="Y") {
        
        $query = "select b.kodeid, b.subkode, a.bridinput, b.nodivisi, a.amount, CAST(NULL as DECIMAL(20,2)) as jml_adj, 
            CAST(NULL as CHAR(50)) nodivisi2, CAST(NULL as DECIMAL(20,2)) as amount2, CAST(NULL as DECIMAL(20,2)) as jml_adj2 
            from dbmaster.t_suratdana_br1 a
            JOIN dbmaster.t_suratdana_br b on a.idinput=b.idinput
            where b.stsnonaktif<>'Y' and ( (b.pilih='N' and b.jenis_rpt='B') OR CONCAT(b.kodeid,b.subkode) IN ('680') ) and a.kodeinput IN ('A', 'B', 'C') 
            and year(b.tgl)='$ptahun' AND b.divisi='$pdivprodid' and date_format(b.tgl,'%Y%m')>='201909'";
        $query = "create TEMPORARY table $tmp00 ($query)"; 
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        $query = "select a.bridinput, b.nodivisi, a.amount, CAST(NULL as DECIMAL(20,2)) as jml_adj from dbmaster.t_suratdana_br1 a
            JOIN dbmaster.t_suratdana_br b on a.idinput=b.idinput
             where b.stsnonaktif<>'Y' and b.jenis_rpt<>'B' and a.kodeinput IN ('A', 'B', 'C') 
             and a.bridinput in (select IFNULL(bridinput,'') from $tmp00 WHERE CONCAT(kodeid,subkode) NOT IN ('680'))";
        $query = "create TEMPORARY table $tmp01 ($query)"; 
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        $query = "UPDATE $tmp00 a JOIN $tmp01 b on a.bridinput=b.bridinput "
                . " SET a.nodivisi2=b.nodivisi, a.amount2=b.amount, a.jml_adj2=b.jml_adj WHERE CONCAT(a.kodeid,a.subkode) NOT IN ('680')";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        mysqli_query($cnit, "DROP TEMPORARY TABLE $tmp01");
        
        
        $query = "select brId, noslip, icabangid, tgl, tgltrans, kode, realisasi1, "
                . " jumlah, jumlah1, jumlah jumlah_asli, jumlah1 as jumlah1_asli, "
                . " aktivitas1, aktivitas2, dokterId, karyawanId, ccyId, tgltrm, lampiran, ca "
                . " from hrd.br0 WHERE IFNULL(batal,'')<>'Y' AND brId IN (select IFNULL(bridinput,'') from $tmp00)";
        $query = "create TEMPORARY table $tmp01 ($query)"; 
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        $query = "UPDATE $tmp01 SET jumlah=NULL, jumlah1=NULL"; 
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
    
        $query = "select a.*, d.nama nama_dokter, e.nama nama_karyawan, b.nama nama_cabang, "
                . " c.nama nama_kode, CAST('' as CHAR(50)) as nodivisi, CAST('' as CHAR(50)) as nodivisi2, "
                . " CAST('' as CHAR(10)) as kodeid, CAST('' as CHAR(10)) as subkode, CAST('Y' as CHAR(1)) as BT "
                . " from $tmp01 a LEFT JOIN mkt.icabang b on a.icabangid=b.icabangid "
                . " LEFT JOIN hrd.br_kode c on a.kode=c.kodeid "
                . " LEFT JOIN hrd.dokter d on a.dokterId=d.dokterId"
                . " LEFT JOIN hrd.karyawan e on a.karyawanId=e.karyawanId";
        $query = "create TEMPORARY table $tmp03 ($query)"; 
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
    
        
        $query = "UPDATE $tmp03 a JOIN $tmp00 b on a.brId=b.bridinput "
                . "SET a.nodivisi=b.nodivisi, a.nodivisi2=b.nodivisi2, a.jumlah=IFNULL(b.amount,0)+IFNULL(b.jml_adj,0), a.jumlah1=IFNULL(b.amount2,0)+IFNULL(b.jml_adj2,0)"; 
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        
        $query = "UPDATE $tmp03 a JOIN dbmaster.t_suratdana_bank b on a.nodivisi=b.nodivisi SET "
                . " a.tgltrans=b.tanggal WHERE IFNULL(b.stsnonaktif,'')<>'Y' and b.stsinput='K' and b.subkode not in ('29') AND "
                . " (IFNULL(a.tgltrans,'0000-00-00')='0000-00-00' OR a.tgltrans='')"; 
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        $query = "UPDATE $tmp03 SET tgltrans=tgl, BT='N' WHERE IFNULL(tgltrans,'0000-00-00')='0000-00-00' OR tgltrans=''"; 
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
    
        
        $query = "UPDATE $tmp03 a JOIN $tmp00 b on a.brId=b.bridinput AND a.nodivisi=b.nodivisi "
                . " SET a.kodeid=b.kodeid, a.subkode=b.subkode WHERE IFNULL(a.nodivisi,'')<>''"; 
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

        $query = "UPDATE $tmp03 SET kodeid='0', subkode='0' WHERE IFNULL(kodeid,'')<>'6'";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        
        $query = "UPDATE $tmp03 SET jumlah1=jumlah1_asli, nodivisi2='OP' WHERE "
                . " IFNULL(nodivisi2,'')='' AND ( (IFNULL(tgltrm,'0000-00-00')<>'0000-00-00' AND IFNULL(tgltrm,'')<>'') OR ( IFNULL(lampiran,'')='Y' AND IFNULL(ca,'')='N' AND IFNULL(tgltrm,'0000-00-00')='0000-00-00') )"
                . " AND CONCAT(kodeid,subkode) NOT IN ('680')";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
        $query = "UPDATE $tmp03 SET jumlah1=jumlah1_asli, nodivisi2='SR' WHERE "
                . " IFNULL(nodivisi2,'')='' AND ( (IFNULL(tgltrm,'0000-00-00')<>'0000-00-00' AND IFNULL(tgltrm,'')<>'') OR ( IFNULL(lampiran,'')='Y' AND IFNULL(ca,'')='N' AND IFNULL(tgltrm,'0000-00-00')='0000-00-00') )"
                . " AND CONCAT(kodeid,subkode) IN ('680')";
        mysqli_query($cnmy, $query);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
        
    
        goto querydarisps;
        
    }
    
    
    
    
    
    $query = "select brId, noslip, icabangid, tgl, tgltrans, kode, realisasi1, "
            . " jumlah, jumlah1, jumlah jumlah_asli, jumlah1 as jumlah1_asli, "
            . " aktivitas1, aktivitas2, dokterId, karyawanId, ccyId, tgltrm, lampiran, ca "
            . " from hrd.br0 WHERE IFNULL(via,'')<>'Y' AND IFNULL(batal,'')<>'Y' AND divprodid='$pdivprodid' AND YEAR(tgltrans)='$ptahun' $fillamp";
    $query = "create TEMPORARY table $tmp01 ($query)"; 
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
    
    $query = "UPDATE $tmp01 SET jumlah_asli=NULL, jumlah1_asli=NULL"; 
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
    
    $query = "select distinct a.bridinput, b.nodivisi, b.pilih, a.amount, a.jml_adj, b.kodeid, b.subkode "
            . " from dbmaster.t_suratdana_br1 a JOIN dbmaster.t_suratdana_br b on a.idinput=b.idinput "
            . " WHERE b.stsnonaktif<>'Y' AND a.kodeinput IN ('A', 'B', 'C') AND b.divisi<>'OTC' AND a.bridinput IN (select distinct IFNULL(brId,'') FROM $tmp01)";
    $query = "create TEMPORARY table $tmp02 ($query)";
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
    
    
    $query = "select a.*, d.nama nama_dokter, e.nama nama_karyawan, b.nama nama_cabang, c.nama nama_kode, "
            . " CAST('' as CHAR(50)) as nodivisi, CAST('' as CHAR(50)) as nodivisi2, "
            . " CAST('' as CHAR(10)) as kodeid, CAST('' as CHAR(10)) as subkode, CAST('Y' as CHAR(1)) as BT "
            . " from $tmp01 a LEFT JOIN mkt.icabang b on a.icabangid=b.icabangid "
            . " LEFT JOIN hrd.br_kode c on a.kode=c.kodeid "
            . " LEFT JOIN hrd.dokter d on a.dokterId=d.dokterId"
            . " LEFT JOIN hrd.karyawan e on a.karyawanId=e.karyawanId"
            . " ";//LEFT JOIN (select distinct bridinput, nodivisi FROM $tmp02 WHERE IFNULL(pilih,'')='Y') as d on a.brId=d.bridinput
    $query = "create TEMPORARY table $tmp03 ($query)"; 
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
    
    
    $query = "UPDATE $tmp03 a JOIN $tmp02 b on a.brId=b.bridinput "
            . "SET a.nodivisi=b.nodivisi WHERE b.pilih='Y'"; 
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
    
    $query = "UPDATE $tmp03 a JOIN $tmp02 b on a.brId=b.bridinput "
            . "SET a.nodivisi=b.nodivisi WHERE b.pilih='N' AND IFNULL(a.nodivisi,'')=''"; 
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
    
    
    
    $query = "UPDATE $tmp03 a JOIN $tmp02 b on a.brId=b.bridinput AND a.nodivisi=b.nodivisi "
            . " SET a.kodeid=b.kodeid, a.subkode=b.subkode WHERE IFNULL(a.nodivisi,'')<>''"; 
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
    
    if ($plampiran=='T') {
        $query = "UPDATE $tmp03 SET kodeid='0', subkode='0' WHERE IFNULL(kodeid,'')<>'6'";
    }else{
        $query = "UPDATE $tmp03 SET kodeid='0', subkode='0'";
    }
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }
    
    
querydarisps:
    
    $query = "UPDATE $tmp03 SET nama_cabang=icabangid WHERE IFNULL(nama_cabang,'')=''";
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo $erropesan; goto hapusdata; }

  
    
?>
<html>
<head>
    <?PHP 
        echo "<title>REKAP OUTSTANDING DANA KLAIM $pdivprodid</title>";
     
        if ($_GET['ket']!="excel") {
            echo "<meta http-equiv='Expires' content='Mon, 01 Jan 2019 1:00:00 GMT'>";
            echo "<meta http-equiv='Pragma' content='no-cache'>";
            echo "<link rel='shortcut icon' href='images/icon.ico' />";
            echo "<link href='css/laporanbaru.css' rel='stylesheet'>";
            
            header("Cache-Control: no-cache, must-revalidate");
        }
        
    ?>
    <style> .str{ mso-number-format:\@; padding-left:5px; } </style>
</head>

<body>
    
    <center><h2><u></u></h2></center>

    <div id="kotakjudul">
        <div id="isikiri">
            <table class='tjudul' width='100%'>
                <tr><td colspan="5"><h2>REKAP OUTSTANDING DANA KLAIM <?PHP echo "$pdivprodid"; ?></h2></td></tr>
                <tr><td colspan="5"></td></tr>
                <tr><td colspan="5">View Date : <i><?PHP echo "$ptglview"; ?></i></td></tr>
            </table>
        </div>
        <div id="isikanan">
            
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
    
    
<?PHP
$npkodeidpil="";
$queryhed = "select distinct kodeid from $tmp03 order by kodeid";
$tampilhed=mysqli_query($cnmy,$queryhed);
while ($rh= mysqli_fetch_array($tampilhed)) {
    $npkodeidpil=$rh['kodeid'];
    
    $pnmkodeid="PC-M";
    if ($npkodeidpil=="6") $pnmkodeid="KASBON SURABAYA";
    
    $ppilihjudul=true;
    if ($pcadari=="T") {
        $ppilihjudul=false;
        if ($plampiran=='T') {
            $ppilihjudul=true;
        }
    }
    
    if ($ppilihjudul==false) {
        $pnmkodeid="";
    }else{
        echo "<center><h2 style='font-size:14px; color:red;'>$pnmkodeid</h2></center>";
    }
?>
    
    <?PHP
    $pgrdjumlah=0;
    $pgrdreal=0;
    
    $pgrdjumlah_usd=0;
    $pgrdreal_usd=0;
    
    $pgrdjumlah_eur=0;
    $pgrdreal_eur=0;
    
    
    $pgrtotalblmreal_rp=0;
    $pgrtotalblmreal_rpop=0;
    $pgrtotalblmreal_rpbt=0;
    
    $query = "select distinct DATE_FORMAT(tgltrans,'%Y-%m') tgltrans from $tmp03 WHERE kodeid='$npkodeidpil' order by DATE_FORMAT(tgltrans,'%Y%m')";
    $tampil=mysqli_query($cnmy,$query);
    while ($row= mysqli_fetch_array($tampil)) {
        $ptgl=$row['tgltrans'];
        
        $pbulann=$row['tgltrans']."-01";
        $pbulann = date("F Y", strtotime($pbulann));
        echo "<h1 style='font-size:14px;'>Bulan : $pbulann</h1>";
    ?>
        <table id='datatable2' class='table table-striped table-bordered example_2' border="1px solid black">
            <thead>
                <tr style='background-color:#cccccc; font-size: 13px;'>
                <th align="center">No</th>
                <th align="center">ID</th>
                <th align="center">No BR/Divisi</th>
                <th align="center">No BR/Divisi<br/>Realisasi</th>
                <th align="center">Noslip</th>
                <th align="center">Yg Membuat</th>
                <th align="center">Tgl. Transfer</th>
                <th align="center">Posting</th>
                <th align="center">Keterangan</th>
                <th align="center">Nama Dokter</th>
                <th align="center">Nama Realisasi</th>
                <th align="center">IDR Usulan Rp.</th>
                <th align="center">IDR Realisasi Rp.</th>
                <th align="center">IDR Sisa Rp.</th>
                
                <th align="center">USD Usulan Rp.</th>
                <th align="center">USD Realisasi Rp.</th>
                <th align="center">USD Sisa Rp.</th>
                
                <th align="center">EUR Usulan Rp.</th>
                <th align="center">EUR Realisasi Rp.</th>
                <th align="center">EUR Sisa Rp.</th>
                
                <th align="center"></th>
                
                </tr>
            </thead>
            <tbody>
                <?PHP
                $ptotjumlah=0;
                $ptotreal=0;
                
                $ptotjumlah_usd=0;
                $ptotreal_usd=0;
                
                $ptotjumlah_eur=0;
                $ptotreal_eur=0;
                
                $ptotalblmreal_rp=0;
                $ptotalblmreal_rpop=0;
                $ptotalblmreal_rpbt=0;
                
                $no=1;
                $query = "select * from $tmp03 WHERE kodeid='$npkodeidpil' AND DATE_FORMAT(tgltrans,'%Y-%m')='$ptgl' ORDER BY tgltrans, noslip";
                $tampil1=mysqli_query($cnmy,$query);
                while ($row1= mysqli_fetch_array($tampil1)) {
                    $pidbr=$row1['brId'];
                    $pnodivisi=$row1['nodivisi'];
                    $pnodivisi_2=$row1['nodivisi2'];
                    $pnoslip=$row1['noslip'];
                    
                    $pidcabang=$row1['icabangid'];
                    $pnmcabang=$row1['nama_cabang'];
                    $pnmdokter=$row1['nama_dokter'];
                    $pnmkaryawan=$row1['nama_karyawan'];
        
                    $tgltransasli=$row1['tgltrans'];
                    $ptgltrans=$row1['tgltrans'];
                    if ($ptgltrans=="0000-00-00") $ptgltrans="";
                    if (!empty($ptgltrans) AND $ptgltrans<>"0000-00-00") $ptgltrans = date("d/m/Y", strtotime($ptgltrans));
                    
                    $pnmposting=$row1['nama_kode'];
                    $pketerangan=$row1['aktivitas1'];
                    $pnmreal=$row1['realisasi1'];
                    $pccyid=$row1['ccyId'];
                    
                    $pblmtrf=$row1['BT'];
                    
                    $pjumlah="";
                    $prealisasi="";
                    $psisa="";
                    
                    $pjumlah_usd="";
                    $prealisasi_usd="";
                    $psisa_usd="";
                    
                    $pjumlah_eur="";
                    $prealisasi_eur="";
                    $psisa_eur="";
                    
                    $pwarnafield="";
                    if ($pccyid=="USD") {
                        $pjumlah_usd=$row1['jumlah'];
                        $prealisasi_usd=$row1['jumlah1'];

                        $ptotjumlah_usd=(double)$ptotjumlah_usd+(double)$pjumlah_usd;
                        $ptotreal_usd=(double)$ptotreal_usd+(double)$prealisasi_usd;
                        $pgrdjumlah_usd=(double)$pgrdjumlah_usd+(double)$pjumlah_usd;
                        $pgrdreal_usd=(double)$pgrdreal_usd+(double)$prealisasi_usd;
                        $psisa_usd=(double)$pjumlah_usd-(double)$prealisasi_usd;
                        $pjumlah_usd=number_format($pjumlah_usd,0,",",",");
                        $prealisasi_usd=number_format($prealisasi_usd,0,",",",");
                        $psisa_usd=number_format($psisa_usd,0,",",",");
                    }elseif ($pccyid=="EUR") {
                        $pjumlah_eur=$row1['jumlah'];
                        $prealisasi_eur=$row1['jumlah1'];

                        $ptotjumlah_eur=(double)$ptotjumlah_eur+(double)$pjumlah_eur;
                        $ptotreal_eur=(double)$ptotreal_eur+(double)$prealisasi_eur;
                        $pgrdjumlah_eur=(double)$pgrdjumlah_eur+(double)$pjumlah_eur;
                        $pgrdreal_eur=(double)$pgrdreal_eur+(double)$prealisasi_eur;
                        $psisa_eur=(double)$pjumlah_eur-(double)$prealisasi_eur;
                        $pjumlah_eur=number_format($pjumlah_eur,0,",",",");
                        $prealisasi_eur=number_format($prealisasi_eur,0,",",",");
                        $psisa_eur=number_format($psisa_eur,0,",",",");
                    }else{
                        $pjumlah=$row1['jumlah'];
                        $prealisasi=$row1['jumlah1'];
                        
                        if ($pblmtrf=="N") {
                            $ptgltrans="";
                            $pgrtotalblmreal_rpbt=(double)$pgrtotalblmreal_rpbt+(double)$pjumlah;
                            $ptotalblmreal_rpbt=(double)$ptotalblmreal_rpbt+(double)$pjumlah;
                        }else{
                            if (empty($pnodivisi_2)) {
                                $pgrtotalblmreal_rp=(double)$pgrtotalblmreal_rp+(double)$pjumlah;
                                $ptotalblmreal_rp=(double)$ptotalblmreal_rp+(double)$pjumlah;
                            }elseif ($pnodivisi_2=="OP") {
                                $pwarnafield=" style='color:red;' ";
                                $pnodivisi_2="on process";
                                $pgrtotalblmreal_rpop=(double)$pgrtotalblmreal_rpop+(double)$pjumlah;
                                $ptotalblmreal_rpop=(double)$ptotalblmreal_rpop+(double)$pjumlah;
                            }
                        }
                        
                        $ptotjumlah=(double)$ptotjumlah+(double)$pjumlah;
                        $ptotreal=(double)$ptotreal+(double)$prealisasi;
                        $pgrdjumlah=(double)$pgrdjumlah+(double)$pjumlah;
                        $pgrdreal=(double)$pgrdreal+(double)$prealisasi;
                        $psisa=(double)$pjumlah-(double)$prealisasi;
                        $pjumlah=number_format($pjumlah,0,",",",");
                        $prealisasi=number_format($prealisasi,0,",",",");
                        $psisa=number_format($psisa,0,",",",");
                    }
                    
                    $pnwrap="";
                    if ($ppilihrpt=="excel") $pnwrap="nowrap";
                    if ($pnodivisi_2=="SR") $pnodivisi_2="";
                    
                    echo "<tr>";
                    echo "<td nowrap>$no</td>";
                    echo "<td class='str' nowrap>$pidbr</td>";
                    echo "<td nowrap>$pnodivisi</td>";
                    echo "<td nowrap $pwarnafield>$pnodivisi_2</td>";
                    echo "<td class='str' nowrap>$pnoslip</td>";
                    echo "<td nowrap>$pnmkaryawan</td>";
                    echo "<td nowrap>$ptgltrans</td>";
                    echo "<td nowrap>$pnmposting</td>";
                    echo "<td $pnwrap>$pketerangan</td>";
                    echo "<td>$pnmdokter</td>";
                    echo "<td nowrap>$pnmreal</td>";
                    echo "<td nowrap align='right'>$pjumlah</td>";
                    echo "<td nowrap align='right'>$prealisasi</td>";
                    echo "<td nowrap align='right'>$psisa</td>";
                    
                    echo "<td nowrap align='right'>$pjumlah_usd</td>";
                    echo "<td nowrap align='right'>$prealisasi_usd</td>";
                    echo "<td nowrap align='right'>$psisa_usd</td>";
                    
                    echo "<td nowrap align='right'>$pjumlah_eur</td>";
                    echo "<td nowrap align='right'>$prealisasi_eur</td>";
                    echo "<td nowrap align='right'>$psisa_eur</td>";
                    
                    
                    $pedit = "<a style='font-size:13px;' href='eksekusi3.php?module=rptlamarealbredit&brid=$pidbr&lampiran1=$plampiran&bulan=$tgltransasli' target='_blank'><small>Edit</small></a>";
                    $phapus = "<a style='font-size:13px;' href='module/data_lama/lap_br_realisasi/rlbr04.php?brid=$pidbr&tgltrans=$tgltransasli&divprodid=$pdivprodid' target='_blank'>Delete</a>";

                    if ($ppilihrpt=="excel" OR ($pidgroup_user=="22" OR $pidgroup_user=="24" OR $pidgroup_user=="34" OR $pidgroup_user=="41")) {
                        $pedit="";
                        $phapus="";
                    }
                    
                    if ($pcadari=="Y") {
                        echo "<td nowrap>&nbsp; &nbsp; &nbsp; &nbsp; </td>";
                    }else{
                        echo "<td nowrap>&nbsp; $pedit &nbsp; &nbsp; $phapus &nbsp; </td>";
                    }
                    
                    echo "</tr>";
                    
                    $no++;
                    
                }
                
                $ptotsisa=(double)$ptotjumlah-(double)$ptotreal;
                $ptotsisa_usd=(double)$ptotjumlah_usd-(double)$ptotreal_usd;
                $ptotsisa_eur=(double)$ptotjumlah_eur-(double)$ptotreal_eur;

                $ptotjumlah=number_format($ptotjumlah,0,",",",");
                $ptotreal=number_format($ptotreal,0,",",",");
                $ptotsisa=number_format($ptotsisa,0,",",",");
                    
                echo "<tr style='font-weight:bold;'>";
                echo "<td colspan='11' align='right'>TOTAL : </td>";
                echo "<td nowrap align='right'>$ptotjumlah</td>";
                echo "<td nowrap align='right'>$ptotreal</td>";
                echo "<td nowrap align='right'>$ptotsisa</td>";
                
                echo "<td nowrap align='right'>$ptotjumlah_usd</td>";
                echo "<td nowrap align='right'>$ptotreal_usd</td>";
                echo "<td nowrap align='right'>$ptotsisa_usd</td>";
                
                echo "<td nowrap align='right'>$ptotjumlah_eur</td>";
                echo "<td nowrap align='right'>$ptotreal_eur</td>";
                echo "<td nowrap align='right'>$ptotsisa_eur</td>";
                
                echo "<td nowrap align='right'></td>";
                
                echo "</tr>";
                
                
                if ($pcadari=="Y") {
                    
                    //TOTAL ON PROCESS
                    $ptotalblmreal_rpop=number_format($ptotalblmreal_rpop,0,",",",");

                    echo "<tr style='font-weight:bold;'>";
                    echo "<td colspan='11' align='right'>TOTAL ON PROCESS : </td>";
                    echo "<td nowrap align='right'>$ptotalblmreal_rpop</td>";
                    echo "<td nowrap align='right'></td>";
                    echo "<td nowrap align='right'></td>";

                    echo "<td nowrap align='right'></td>";
                    echo "<td nowrap align='right'></td>";
                    echo "<td nowrap align='right'></td>";

                    echo "<td nowrap align='right'></td>";
                    echo "<td nowrap align='right'></td>";
                    echo "<td nowrap align='right'></td>";

                    echo "<td nowrap align='right'></td>";


                    echo "</tr>";
                    
                    
                    //TOTAL BELUM REALISASI SAMA SEKALI
                    $ptotalblmreal_rp=number_format($ptotalblmreal_rp,0,",",",");

                    echo "<tr style='font-weight:bold;'>";
                    echo "<td colspan='11' align='right'>TOTAL BLM REALISASI : </td>";
                    echo "<td nowrap align='right'>$ptotalblmreal_rp</td>";
                    echo "<td nowrap align='right'></td>";
                    echo "<td nowrap align='right'></td>";

                    echo "<td nowrap align='right'></td>";
                    echo "<td nowrap align='right'></td>";
                    echo "<td nowrap align='right'></td>";

                    echo "<td nowrap align='right'></td>";
                    echo "<td nowrap align='right'></td>";
                    echo "<td nowrap align='right'></td>";

                    echo "<td nowrap align='right'></td>";


                    echo "</tr>";
                    
                    //TOTAL BELUM TRANSFER
                    $ptotalblmreal_rpbt=number_format($ptotalblmreal_rpbt,0,",",",");

                    echo "<tr style='font-weight:bold;'>";
                    echo "<td colspan='11' align='right'>TOTAL BLM TRANSFER : </td>";
                    echo "<td nowrap align='right'>$ptotalblmreal_rpbt</td>";
                    echo "<td nowrap align='right'></td>";
                    echo "<td nowrap align='right'></td>";

                    echo "<td nowrap align='right'></td>";
                    echo "<td nowrap align='right'></td>";
                    echo "<td nowrap align='right'></td>";

                    echo "<td nowrap align='right'></td>";
                    echo "<td nowrap align='right'></td>";
                    echo "<td nowrap align='right'></td>";

                    echo "<td nowrap align='right'></td>";


                    echo "</tr>";
                }
                
                ?>
            </tbody>
        </table>
        
    <?PHP
    }
    
    echo "<br/>&nbsp;";
    echo "<h2 style='font-size:16px;'>GRAND TOTAL $pnmkodeid : </h2>";
    ?>
    <table id='datatable2' class='table table-striped table-bordered example_2' border="1px solid black">
        <thead>
            <tr style='background-color:#cccccc; font-size: 13px;'>
            <th align="center"></th>
            <th align="center">IDR Usulan Rp.</th>
            <th align="center">IDR Realisasi Rp.</th>
            <th align="center">IDR Sisa Rp.</th>
            
            <th align="center">USD Usulan Rp.</th>
            <th align="center">USD Realisasi Rp.</th>
            <th align="center">USD Sisa Rp.</th>
            
            <th align="center">EUR Usulan Rp.</th>
            <th align="center">EUR Realisasi Rp.</th>
            <th align="center">EUR Sisa Rp.</th>
            </tr>
        </thead>
        <tbody>
            <?PHP
            
            $pgrdsisa=(double)$pgrdjumlah-(double)$pgrdreal;
            $pgrdsisa_usd=(double)$pgrdjumlah_usd-(double)$pgrdreal_usd;
            $pgrdsisa_eur=(double)$pgrdjumlah_eur-(double)$pgrdreal_eur;

            $pgrdjumlah=number_format($pgrdjumlah,0,",",",");
            $pgrdreal=number_format($pgrdreal,0,",",",");
            $pgrdsisa=number_format($pgrdsisa,0,",",",");

            $pgrdjumlah_usd=number_format($pgrdjumlah_usd,0,",",",");
            $pgrdreal_usd=number_format($pgrdreal_usd,0,",",",");
            $pgrdsisa_usd=number_format($pgrdsisa_usd,0,",",",");

            $pgrdjumlah_eur=number_format($pgrdjumlah_eur,0,",",",");
            $pgrdreal_eur=number_format($pgrdreal_eur,0,",",",");
            $pgrdsisa_eur=number_format($pgrdsisa_eur,0,",",",");
            
            echo "<tr style='font-weight:bold;'>";
            
            echo "<td nowrap align='right'>TOTAL : </td>";
            echo "<td nowrap align='right'>$pgrdjumlah</td>";
            echo "<td nowrap align='right'>$pgrdreal</td>";
            echo "<td nowrap align='right'>$pgrdsisa</td>";
            
            echo "<td nowrap align='right'>$pgrdjumlah_usd</td>";
            echo "<td nowrap align='right'>$pgrdreal_usd</td>";
            echo "<td nowrap align='right'>$pgrdsisa_usd</td>";
            
            echo "<td nowrap align='right'>$pgrdjumlah_eur</td>";
            echo "<td nowrap align='right'>$pgrdreal_eur</td>";
            echo "<td nowrap align='right'>$pgrdsisa_eur</td>";
            
            echo "</tr>";
            
            if ($pcadari=="Y") {
                
                $pgrtotalblmreal_rpop=number_format($pgrtotalblmreal_rpop,0,",",",");
                
                echo "<tr style='font-weight:bold;'>";
                echo "<td nowrap align='right'>TOTAL ON PROCESS : </td>";
                echo "<td nowrap align='right'>$pgrtotalblmreal_rpop</td>";
                echo "<td nowrap align='right'></td>";
                echo "<td nowrap align='right'></td>";

                echo "<td nowrap align='right'></td>";
                echo "<td nowrap align='right'></td>";
                echo "<td nowrap align='right'></td>";

                echo "<td nowrap align='right'></td>";
                echo "<td nowrap align='right'></td>";
                echo "<td nowrap align='right'></td>";

                echo "</tr>";
                
                
                
                
                $pgrtotalblmreal_rp=number_format($pgrtotalblmreal_rp,0,",",",");
                
                echo "<tr style='font-weight:bold;'>";
                echo "<td nowrap align='right'>TOTAL BLM REALISASI : </td>";
                echo "<td nowrap align='right'>$pgrtotalblmreal_rp</td>";
                echo "<td nowrap align='right'></td>";
                echo "<td nowrap align='right'></td>";

                echo "<td nowrap align='right'></td>";
                echo "<td nowrap align='right'></td>";
                echo "<td nowrap align='right'></td>";

                echo "<td nowrap align='right'></td>";
                echo "<td nowrap align='right'></td>";
                echo "<td nowrap align='right'></td>";

                echo "</tr>";
                
                $pgrtotalblmreal_rpbt=number_format($pgrtotalblmreal_rpbt,0,",",",");
                
                echo "<tr style='font-weight:bold;'>";
                echo "<td nowrap align='right'>TOTAL BLM TRANSFER : </td>";
                echo "<td nowrap align='right'>$pgrtotalblmreal_rpbt</td>";
                echo "<td nowrap align='right'></td>";
                echo "<td nowrap align='right'></td>";

                echo "<td nowrap align='right'></td>";
                echo "<td nowrap align='right'></td>";
                echo "<td nowrap align='right'></td>";

                echo "<td nowrap align='right'></td>";
                echo "<td nowrap align='right'></td>";
                echo "<td nowrap align='right'></td>";

                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;
    
<?PHP
}
?>
    
</body>

</html>

<?PHP
hapusdata:
    mysqli_query($cnit, "DROP TEMPORARY TABLE $tmp00");
    mysqli_query($cnit, "DROP TEMPORARY TABLE $tmp01");
    mysqli_query($cnit, "DROP TEMPORARY TABLE $tmp02");
    mysqli_query($cnit, "DROP TEMPORARY TABLE $tmp03");

    mysqli_close($cnmy);
?>