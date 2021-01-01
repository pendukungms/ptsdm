<?php

    ini_set("memory_limit","10G");
    ini_set('max_execution_time', 0);
    
session_start();
$puser=$_SESSION['IDCARD'];
if (empty($puser)) {
    echo "ANDA HARUS LOGIN ULANG....!!!";
    exit;
}

    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $start = $time;
    
    
    $module=$_GET['module'];
    $act=$_GET['act'];
    $idmenu=$_GET['idmenu'];
    
    $cabang="LMP";
    $subdist="EP";
    
    $distributor="0000000002";
    $ptgl=$_POST['ubln'];
    
    $pbulan =  date("Ym", strtotime($ptgl));
    $bulan =  date("Y-m", strtotime($ptgl));
    
    
    if ($distributor!="0000000002") {
        echo "tidak ada data yang diproses...";
        exit;
    }
    
    echo "$distributor . $cabang . $bulan . $subdist<br/>";
    
    //ubah juga di _uploaddata
    include "../../config/koneksimysqli_it.php";
    $cnmy=$cnit;
    $dbname = "MKT";
    
    $plogit_akses=$_SESSION['PROSESLOGKONEK_IT'];//true or false || status awal true
    if ($plogit_akses==true) {
        include "../../config/koneksimysqli_it.php";
    }
    
    
    mysqli_query($cnmy, "DROP TABLE IF EXISTS $dbname.tmp_importfilesdt_ipms");
    mysqli_query($cnmy, "create table $dbname.tmp_importfilesdt_ipms (select *, CAST(NULL AS DECIMAL(20,2)) as hna from $dbname.mssales)");
    
    $query_up_prod = "UPDATE $dbname.tmp_importfilesdt_ipms a JOIN "
            . " (SELECT DISTINCT e.distid, e.`iProdId`, e.`eProdId`, i.`hna` FROM MKT.eproduk e INNER JOIN MKT.iproduk i ON "
            . " e.`iProdId` = i.`iProdId` WHERE e.distid='0000000002') b ON a.BRGID=b.eProdId AND '0000000002'=b.distid SET "
            . " a.hna=b.hna";
    mysqli_query($cnmy, $query_up_prod);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error UPDATE HNA : $erropesan"; exit; }
    
    
    $query ="select * from $dbname.tmp_importfilesdt_ipms where IFNULL(hna,0)=0";
    $tampil= mysqli_query($cnmy, $query);
    $ketemu=mysqli_num_rows($tampil);
    if ($ketemu>0) {
        echo "<div style='color:red;'><h1>ADA HNA 0....</h1></div>";
    }
	
	
    
    $totalcust=0;

    //dibuka
        $eksekusi=mysqli_query($cnmy, "CALL $dbname.cursor_ecust()");
    
    //IT
    if ($plogit_akses==true) {
        $eksekusi2=mysqli_query($cnit, "CALL $dbname.cursor_ecust()");
    }
    
    
        
    // sales
    $totalsalesqty=0;
    $totalsalessum=0;
    mysqli_query($cnmy, "delete from $dbname.salesspp where left(tgljual,7)='$bulan' and cabangid='$cabang' and subdist='$subdist' ");
    
    //IT
    if ($plogit_akses==true) {
        mysqli_query($cnit, "delete from $dbname.salesspp where left(tgljual,7)='$bulan' and cabangid='$cabang' and subdist='$subdist' ");
    }
    //END IT
    
    
    
    $qrysales="
        SELECT * FROM $dbname.mssales
    ";
    
    $tampil_sl= mysqli_query($cnmy, $qrysales);
    while ($data1= mysqli_fetch_array($tampil_sl)) {
        
        $custid=$data1['CUSTID'];
        $nojual=$data1['NOJUAL'];
        $brgid=$data1['BRGID'];
        $tgljual=$data1['TGLJUAL'];
        $harga0=mysqli_fetch_array(mysqli_query($cnmy, "
            SELECT i.`hna` 
            FROM MKT.eproduk e 
            INNER JOIN MKT.iproduk i ON e.`iProdId` = i.`iProdId` 
            WHERE e.`eProdId` = '$brgid' and  e.distid='0000000002'
        "));
        
        $harga=$harga0[0];
        $qbeli=$data1['QBELI'];
        $totale=$harga*$qbeli;
        if ($distributor=="0000000002"){
            $tabel="$dbname.salesspp";
        }
        
        
        //$tanggaljual=$tahun."-".$bulan."-".$tgl;
        $insert=mysqli_query($cnmy, "
            insert into $tabel(cabangid,custid,tgljual,brgid,harga,qbeli,fakturid,subdist) 
            values('$cabang','$custid','$tgljual','$brgid','$harga','$qbeli','$nojual','$subdist')
        ");

        if ($insert) {
            $totalsalesqty=$totalsalesqty+1;
            $totalsalessum=$totalsalessum+$totale;
        }
        
        //IT
        if ($plogit_akses==true) {
            $insert2=mysqli_query($cnit, "
                insert into $tabel(cabangid,custid,tgljual,brgid,harga,qbeli,fakturid,subdist) 
                values('$cabang','$custid','$tgljual','$brgid','$harga','$qbeli','$nojual','$subdist')
            ");
        }
        
        
    }
        
        
        
    echo "Total penjualan yg berhasil diinput: $totalsalessum , dengan jumlah no faktur sebanyak $totalsalesqty.<br>sekian dan terimakasih";
    
    
    mysqli_query($cnmy, "drop table $dbname.mssales");
    mysqli_query($cnmy, "drop table $dbname.msbar");
    mysqli_query($cnmy, "truncate table $dbname.subdist_mscust_ep");
    
    
    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $finish = $time;
    $total_time = round(($finish - $start), 4);
    
    echo "<br/>Selesai dalam ".$total_time." detik<br/>&nbsp;<br/>&nbsp;";
    
    mysqli_close($cnmy);
    
    //IT
    if ($plogit_akses==true) {
        mysqli_close($cnit);
    }
    
?>