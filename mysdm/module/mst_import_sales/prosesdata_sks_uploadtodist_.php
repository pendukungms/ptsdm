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
    
    $distributor="0000000031";
    $ptgl=$_POST['ubln'];
    
    $pbulan =  date("Ym", strtotime($ptgl));
    $bulan =  date("Y-m", strtotime($ptgl));
    
    
    if ($distributor!="0000000031") {
        echo "tidak ada data yang diproses...";
        exit;
    }
    
    
    //ubah juga di _uploaddata
    include "../../config/koneksimysqli_it.php";
    $cnmy=$cnit;
    $dbname = "MKT";
    
    $plogit_akses=$_SESSION['PROSESLOGKONEK_IT'];//true or false || status awal true
    if ($plogit_akses==true) {
        include "../../config/koneksimysqli_it.php";
    }

    
    
    
    mysqli_query($cnmy, "DROP TABLE IF EXISTS $dbname.tmp_importprodsks_ipms");
    
    $qryproduk="
        SELECT DISTINCT `kode barang`,`keterangan barang`,hna 
        FROM $dbname.importsks
        WHERE LEFT(`tgl faktur faktur`,7) = '$bulan' 
            AND CONCAT('$distributor', `kode barang`) NOT IN "
            . " (SELECT CONCAT('$distributor', eprodid) FROM MKT.eproduk WHERE distid='$distributor')
        ";
    mysqli_query($cnmy, "create table $dbname.tmp_importprodsks_ipms ($qryproduk)");
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error INSER tmp_importprodsks_ipms : $erropesan"; exit; }
    
    
        mysqli_query($cnmy, "DROP TABLE IF EXISTS $dbname.tmp_importcustsks_ipms_cusid00pil");
        mysqli_query($cnmy, "create table $dbname.tmp_importcustsks_ipms_cusid00pil (select *, CAST('' as CHAR(5)) as noidcabid from $dbname.importsks)");
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error create tmp_importcustsks_ipms_cusid00pil : $erropesan"; exit; }
        
        mysqli_query($cnmy, "UPDATE $dbname.tmp_importcustsks_ipms_cusid00pil SET noidcabid='01' WHERE `cabang`='SMG'");
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error update SMG : $erropesan"; exit; }
        mysqli_query($cnmy, "UPDATE $dbname.tmp_importcustsks_ipms_cusid00pil SET noidcabid='02' WHERE `cabang`='PWK'");
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error update PWK : $erropesan"; exit; }
        mysqli_query($cnmy, "UPDATE $dbname.tmp_importcustsks_ipms_cusid00pil SET noidcabid='03' WHERE `cabang`='SBY'");
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error update SBY : $erropesan"; exit; }
        mysqli_query($cnmy, "UPDATE $dbname.tmp_importcustsks_ipms_cusid00pil SET noidcabid='04' WHERE `cabang`='MKS'");
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error update MKS : $erropesan"; exit; }
        mysqli_query($cnmy, "UPDATE $dbname.tmp_importcustsks_ipms_cusid00pil SET noidcabid='05' WHERE `cabang`='PLU'");
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error update PLU : $erropesan"; exit; }
        mysqli_query($cnmy, "UPDATE $dbname.tmp_importcustsks_ipms_cusid00pil SET noidcabid='06' WHERE `cabang`='DPS'");
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error update DPS : $erropesan"; exit; }
        mysqli_query($cnmy, "UPDATE $dbname.tmp_importcustsks_ipms_cusid00pil SET noidcabid='07' WHERE `cabang`='TGL'");
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error update TGL : $erropesan"; exit; }
        mysqli_query($cnmy, "UPDATE $dbname.tmp_importcustsks_ipms_cusid00pil SET noidcabid='' WHERE IFNULL(noidcabid,'')=''");
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error update SMG : $erropesan"; exit; }
    
	
	
	mysqli_query($cnmy, "alter table MKT.tmp_importcustsks_ipms_cusid00pil add column nama_2 varchar(300), add column kodeid_2 varchar(300)");
	$erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error ALTER tmp_importcustsks_ipms : $erropesan"; exit; }
	
	$query_up = "UPDATE MKT.tmp_importcustsks_ipms_cusid00pil as a JOIN MKT.ecust as b on '$distributor'=b.distid AND 
		IFNULL(a.`no. pelanggan`,'')=IFNULL(b.ecustid,'') AND 
		IFNULL(a.`noidcabid`,'')=IFNULL(b.cabangid,'') SET a.nama_2=b.nama, a.kodeid_2=b.ecustid";
	mysqli_query($cnmy, $query_up);
	$erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error UPDATE tmp_importcustsks_ipms : $erropesan"; exit; }	
	
	
	
	
    mysqli_query($cnmy, "DROP TABLE IF EXISTS $dbname.tmp_importcustsks_ipms");
	
			//lama
			$qrycust="
				SELECT DISTINCT `cabang`, `no. pelanggan`,`nama pelanggan`,`alamat 1 pelanggan`,`kota pelanggan`, HEADER  
				FROM $dbname.tmp_importcustsks_ipms_cusid00pil 
				WHERE LEFT(`tgl faktur faktur`,7) = '$bulan' AND 
					CONCAT('$distributor', IFNULL(`no. pelanggan`,''), IFNULL(`noidcabid`,'')) NOT IN 
						(SELECT CONCAT(IFNULL(distid,''), IFNULL(ecustid,''), IFNULL(cabangid,'')) FROM MKT.ecust WHERE distid='$distributor')
			";// AND cabang = '$cabang' AND cabangid IN ('00', '01', '02', '03', '04', '05', '06', '07')
			
	//baru untuk memisahkan antara OTC CHC dan ETHICAL di field header
	$qrycust="SELECT DISTINCT `cabang`, `no. pelanggan`,`nama pelanggan`,`alamat 1 pelanggan`,`kota pelanggan`, HEADER 
			FROM $dbname.tmp_importcustsks_ipms_cusid00pil WHERE IFNULL(kodeid_2,'')<>''";
	
    mysqli_query($cnmy, "create table $dbname.tmp_importcustsks_ipms ($qrycust)");
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error INSER tmp_importcustsks_ipms : $erropesan"; exit; }
    
    
    //echo "$dbname.tmp_importcustsks_ipms_cusid00pil"; exit;
	
	
    unset($pproduk_baru);//kosongkan array
    unset($pcustomer_baru);//kosongkan array
	unset($pcustomer_baru_eth);//kosongkan array
    

	
	
	/* hilangkan kesini
	
    $totalproduk=0;
    // -----------------------------  insert produk
    $pinsertsave=false;
    $qryproduk="
        SELECT DISTINCT `kode barang` brgid,`keterangan barang` nama,hna harga 
        FROM $dbname.tmp_importprodsks_ipms";
    
    $tampil_pr= mysqli_query($cnmy, $qryproduk);
    while ($data1= mysqli_fetch_array($tampil_pr)) {
        
        $brgid=$data1['brgid'];
        $namaproduk=mysqli_real_escape_string($cnmy, $data1['nama']);
        $hna=$data1['harga'];
        
        $pinst_prod_data[] = "('$distributor','$brgid','$namaproduk',$hna,'Y','Y')";
        
        $pproduk_baru[] = "berhasil input produk baru -> $namaproduk ~ $brgid ~ $hna <br>";
        //echo "berhasil input produk baru -> $namaproduk ~ $brgid ~ $hna <br>";
        $totalproduk=$totalproduk+1;
        
        $pinsertsave=true;
        
        
    }

    
    if ($pinsertsave==true) {
        
        $query_prod_ins = "INSERT INTO MKT.eproduk(distid,eprodid,nama,hna,aktif,oldflag) VALUES "
                . "".implode(', ', $pinst_prod_data);

        mysqli_query($cnmy, $query_prod_ins);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error INSER eproduk : $erropesan"; exit; }
    
        //IT
        if ($plogit_akses==true) {
            mysqli_query($cnit, $query_prod_ins);
            $erropesan = mysqli_error($cnit); if (!empty($erropesan)) { mysqli_close($cnit); echo "IT... Error INSER eproduk : $erropesan"; exit; }
        }
        //END IT
        
    }
    
    hilangkan kesini */
    
    
    
    
    
    
    $totalcust=0;
    // -----------------------------  insert customer
    $pinsertsave=false;
	$pinsertsave_eth=false;
    $qrycust="
        SELECT DISTINCT HEADER as header, `cabang` cabang, `no. pelanggan` custid,`nama pelanggan` nama,`alamat 1 pelanggan` alamat1,`kota pelanggan` kota
        FROM $dbname.tmp_importcustsks_ipms
    ";// AND cabang = '$cabang' WHERE LEFT(`tgl faktur faktur`,7) = '$bulan'
    
    $tampil_cu= mysqli_query($cnmy, $qrycust);
    while ($data1= mysqli_fetch_array($tampil_cu)) {
        
		$pdivisiprod=$data1['header'];
		
        $ncabang=$data1['cabang'];
        $ecust=$data1['custid'];
		
        $enama=mysqli_real_escape_string($cnmy, $data1['nama']);
        $alamat=mysqli_real_escape_string($cnmy, $data1['alamat1']);
        $kota=mysqli_real_escape_string($cnmy, $data1['kota']);

                $ncabang_id = '';

                if($ncabang=='SMG'){
                  $ncabang_id = '01';
                }elseif($ncabang=='PWK'){
                  $ncabang_id = '02';
                }elseif($ncabang=='SBY'){
                  $ncabang_id = '03';
                }elseif($ncabang=='MKS'){
                  $ncabang_id = '04';
                }elseif($ncabang=='PLU'){
                  $ncabang_id = '05';
                }elseif($ncabang=='DPS'){
                  $ncabang_id = '06';
                }elseif($ncabang=='TGL'){
                  $ncabang_id = '07';
                }else{
                  $ncabang_id = '00';
                }
              
            if ($pdivisiprod=="OTC" OR $pdivisiprod=="CHC") {
				$pinst_cust_data[] = "('$distributor','$ncabang_id','$ecust','$enama','$alamat','$kota','Y','Y')";
				
				$pinsertsave=true;
			}else{
				$pcustomer_baru_eth[] = "('$distributor','$ncabang_id','$ecust','$enama','$alamat','$kota','Y','Y')";
				
				$pinsertsave_eth=true;
			}
            
            $pcustomer_baru[] = "berhasil input cust baru -> $ncabang - $ncabang_id - $ecust - $enama - $alamat <br>";
			
            $totalcust=$totalcust+1;
            
			
    }
    
	echo "$pinsertsave<br/>$pinsertsave_eth";
    mysqli_close($cnmy); exit;//hilangkan disini
	
	
    
    if ($pinsertsave==true) {
        
        $query_cust_ins = "INSERT INTO MKT.ecust(distid,cabangid,ecustid,nama,alamat1,kota,oldflag,aktif) VALUES "
                . "".implode(', ', $pinst_cust_data);

        mysqli_query($cnmy, $query_cust_ins);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error INSER ecust : $erropesan"; exit; }
        
        //IT
        if ($plogit_akses==true) {
            mysqli_query($cnit, $query_cust_ins);
            $erropesan = mysqli_error($cnit); if (!empty($erropesan)) { mysqli_close($cnit); echo "IT... Error INSER ecust : $erropesan"; exit; }
        }
        //END IT
        
        
    }
    
	if ($pinsertsave_eth==true) {
		
        $query_cust_ins = "INSERT INTO MKT.ecust(distid,cabangid,ecustid,nama_eth_sks,alamat1_eth_sks,kota_eth_sks,oldflag,aktif) VALUES "
                . "".implode(', ', $pinst_cust_data);

        mysqli_query($cnmy, $query_cust_ins);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error INSER ecust : $erropesan"; exit; }
		
        //IT
        if ($plogit_akses==true) {
            mysqli_query($cnit, $query_cust_ins);
            $erropesan = mysqli_error($cnit); if (!empty($erropesan)) { mysqli_close($cnit); echo "IT... Error INSER ecust : $erropesan"; exit; }
        }
        //END IT
		
	}
    
    
    
    // -----------------------------  insert sales
    
    
    $totalsalesqty=0;
    $totalsalessum=0;
    mysqli_query($cnmy, "DELETE FROM $dbname.salessks WHERE left(tgljual,7)='$bulan' AND "
            . " IFNULL(cabangid,'') IN ('01', '02', '03', '04', '05', '06', '07', '00')");//cabangid = '$cabang_id'
    
    //'$cabang_id' cabangid,`no. pelanggan` custid, ....
    
    $pinsertsave=false;
    $qrysales="
        SELECT cabang,`no. pelanggan` custid,`tgl faktur faktur` tgljual,`kode barang` brgid,hna harga,`kuantitas` qbeli,'0' qbonus,`no. faktur faktur` fakturid
        FROM $dbname.importsks
        WHERE LEFT(`tgl faktur faktur`,7) = '$bulan'
    ";// AND cabang = '$cabang'
    
    $tampil_sl= mysqli_query($cnmy, $qrysales);
    while ($data1= mysqli_fetch_array($tampil_sl)) {
        
        $ncabang=$data1['cabang'];
        
        //$cabangid=$data1['cabangid'];
        $custid=$data1['custid'];
        $nojual=$data1['fakturid'];
        $brgid=$data1['brgid'];
        $tgljual=$data1['tgljual'];
        $harga=$data1['harga'];
        $qbeli=$data1['qbeli'];
        $qbonus=$data1['qbonus'];
        $totale=$harga*$qbeli;

                $ncabang_id = '';

                if($ncabang=='SMG'){
                  $ncabang_id = '01';
                }elseif($ncabang=='PWK'){
                  $ncabang_id = '02';
                }elseif($ncabang=='SBY'){
                  $ncabang_id = '03';
                }elseif($ncabang=='MKS'){
                  $ncabang_id = '04';
                }elseif($ncabang=='PLU'){
                  $ncabang_id = '05';
                }elseif($ncabang=='DPS'){
                  $ncabang_id = '06';
                }elseif($ncabang=='TGL'){
                  $ncabang_id = '07';
                }else{
                  $ncabang_id = '00';
                }
                
            $pinst_sales_data[] = "('$ncabang_id','$custid','$tgljual','$brgid','$harga','$qbeli','$nojual','$qbonus')";
            $pinsertsave=true;
            
            $totalsalesqty=$totalsalesqty+1;
            $totalsalessum=$totalsalessum+$totale;
            
            
        /*
        // echo $cabangid.'~'.$custid.'~'.$nojual.'~'.$brgid.'~'.$tgljual.'~'.$harga.'~'.$qbeli.'~'.$qbonus.'~'.$totale.'~'.'<br>';
        $insert=mysqli_query($cnmy, "INSERT INTO $dbname.salessks(cabangid,custid,tgljual,brgid,harga,qbeli,fakturid,qbonus) VALUES "
                . " ('$ncabang_id','$custid','$tgljual','$brgid','$harga','$qbeli','$nojual','$qbonus')");
        if ($insert){
            $totalsalesqty=$totalsalesqty+1;
            $totalsalessum=$totalsalessum+$totale;
        }
        */
        
    }
    
    if ($pinsertsave==true) {
        
        $query_sls_ins = "INSERT INTO $dbname.salessks(cabangid,custid,tgljual,brgid,harga,qbeli,fakturid,qbonus) VALUES "
                . "".implode(', ', $pinst_sales_data);

        mysqli_query($cnmy, $query_sls_ins);
        $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { mysqli_close($cnmy); echo "Error INSER sales : $erropesan"; exit; }
        
        
        //IT
        if ($plogit_akses==true) {
            mysqli_query($cnit, "DELETE FROM $dbname.salessks WHERE left(tgljual,7)='$bulan' AND "
                    . " IFNULL(cabangid,'') IN ('01', '02', '03', '04', '05', '06', '07', '00')");//cabangid = '$cabang_id'
            
            mysqli_query($cnit, $query_sls_ins);
            $erropesan = mysqli_error($cnit); if (!empty($erropesan)) { mysqli_close($cnit); echo "IT... Error INSER sales : $erropesan"; exit; }
        }
        //END IT
        
    }
    
    
    $query = "SELECT s.tgljual, s.fakturId, s.brgid, s.harga, s.qbeli FROM $dbname.salessks s 
        JOIN (SELECT * FROM MKT.eproduk WHERE IFNULL(iprodid,'')='' AND distid='$distributor') ep
        ON s.brgid=ep.eprodid
        WHERE LEFT(tgljual,7)='$bulan'";
    $tampil= mysqli_query($cnmy, $query);
    $ketemu= mysqli_num_rows($tampil);
    if ($ketemu>0) {
        echo "<div>";
        echo "<h3>Produk yang belum dimapping...</h3>";
        echo "<table width='100%' border='1px'>";
            
            echo "<tr>";
            echo "<th align='center'>No</th>";
            echo "<th align='center'>Tgl. Jual</th>";
            echo "<th align='center'>No. Faktur</th>";
            echo "<th align='center'>Id Brg</th>";
            echo "<th align='center'>Harga</th>";
            echo "<th align='center'>Qty</th>";
            echo "</tr>";
            
            $no=1;
            while($row= mysqli_fetch_array($tampil)) {
                $nvtgljual=$row['tgljual'];
                $nvfakturid=$row['fakturId'];
                $nvbrgid=$row['brgid'];
                $nvharga=$row['harga'];
                $nvqbeli=$row['qbeli'];
                
                echo "<tr>";
                echo "<td align='left'>$no</td>";
                echo "<td align='left'>$nvtgljual</td>";
                echo "<td align='left'>$nvfakturid</td>";
                echo "<td align='left'>$nvbrgid</td>";
                echo "<td align='right'>$nvharga</td>";
                echo "<td align='right'>$nvqbeli</td>";
                echo "</tr>";
                
                $no++;

            }
        echo "</table>";
        echo "</div>";
    }
    
    echo "<hr/>Total Produk baru yg berhasil diinput: $totalproduk<br>";
    
    if (isset($pproduk_baru)) echo implode(', ', $pproduk_baru);
    
    echo "<hr><br>Total Customer baru yg berhasil diinput: $totalcust<br>";
    
    if (isset($pcustomer_baru)) echo implode(', ', $pcustomer_baru);
    

    echo "<hr><br>
      Total penjualan yg berhasil diinput : $totalsalessum , dengan jumlah no faktur sebanyak $totalsalesqty.<br>
      <hr>
    ";

    //mysqli_query($cnmy, "DELETE FROM $dbname.importsks WHERE LEFT(`tgl faktur faktur`,7) = '$bulan' AND cabang = '$cabang'");
    
    
    
    $cabang="SMG";
    $cabang_id = '01';
    
    
    $cabang="PWK";
    $cabang_id = '02';
    
    
    
    $cabang="SBY";
    $cabang_id = '03';
    
    
    
    $cabang="MKS";
    $cabang_id = '04';
    
    
    
    $cabang="PLU";
    $cabang_id = '05';
    
    
    
    $cabang="DPS";
    $cabang_id = '06';
    
    
    
    $cabang="TGL";
    $cabang_id = '07';
    
    
    
    
    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $finish = $time;
    $total_time = round(($finish - $start), 4);
    
    echo "<br/>Selesai dalam ".$total_time." detik<br/>&nbsp;<br/>&nbsp;";
    
    
    
    mysqli_query($cnmy, "DELETE FROM $dbname.tmp_importprodsks_ipms");
    mysqli_query($cnmy, "DELETE FROM $dbname.tmp_importcustsks_ipms");
	mysqli_query($cnmy, "DELETE FROM $dbname.tmp_importcustsks_ipms_cusid00pil");
    
    mysqli_close($cnmy);
    
    //IT
    if ($plogit_akses==true) {
        mysqli_close($cnit);
    }
?>