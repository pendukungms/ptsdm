
<html>
<head>
  <title>Lihat Kartu Status</title>
  <?php 
	//<meta http-equiv="Expires" content="Mon, 01 Jan 2007 1:00:00 GMT">
	//<meta http-equiv="Pragma" content="no-cache">
	//header("Cache-Control: no-cache, must-revalidate"); 
  ?>
</head>
<script src="ks.js">
</script>

<body onload="initVar()">

<form id="frmKsView2">
<?php
	include("../../../config/koneksimysqli_it.php");
	include_once("../../../config/common.php");
	session_start();
	
	
	$dokterid = $_POST['dokterid'];//echo"$dokterid<br>";
	$bulan = $_POST['bulan']; 
	$bulan__ = $_POST['bulan']; 
	$srid = $_POST['srid']; //echo"$srid";
	$karyawanid = $_POST['karyawanid']; 	//echo"$karyawanid";
	$mr_id = $_POST['mr_id']; 
	$br = "";//$_POST['chkBR'];
	if($br==''){
		$br = 0;
	}
	// echo $br;
	
	if ($srid=="") {
	  $srid = $mr_id;
	}
	$jabatanid = $_SESSION['JABATANID'];

	if ($jabatanid=='04') {
		 echo "Error connection to database";
		exit;
	}
	
	// echo"$srid";
	// nama mr?
	$query = "select nama from hrd.karyawan where karyawanid='$srid'"; 
	// echo $query.'<br>';
	$result = mysqli_query($cnit, $query);
	$num_results = mysqli_num_rows($result);
	$row = mysqli_fetch_array($result);
	$srnm_ = $row['nama'];

	echo "<b>Kartu Status : $srnm_ - $srid</b><br>";
	
	//areaid
	$query = "select icabangid,areaid from hrd.karyawan where karyawanid='$srid'";
	// echo $query.'<br>';
	$result = mysqli_query($cnit, $query);
	$num_results = mysqli_num_rows($result);
	$row = mysqli_fetch_array($result);
	$icabangid = $row['icabangid']; //echo"$icabangid";
	$areaid = $row['areaid']; //echo"$areaid";

	$query = "
		select min(bulan) as awal 
		from hrd.ks1
	  	where srid='$srid' and dokterid='$dokterid'
	"; 
	 //echo $query.'<br>';
	$result = mysqli_query($cnit, $query);
	$num_results = mysqli_num_rows($result);
	$row = mysqli_fetch_array($result);
	$awal = $row['awal'];
	$akhir = $_POST['bulan']; 
	if ($awal=="") {
		$query = "
			select min(bulan) as awal 
			from hrd.ks1
			where dokterid='$dokterid'
		"; 
		// echo $query.'<br>';
		$result = mysqli_query($cnit, $query);
		$num_results = mysqli_num_rows($result);
		$row = mysqli_fetch_array($result);
		$awal = $row['awal'];
	}
	if ($awal=="") {
		$awal = $akhir;
	}
	
	//ambil saldo awal
	$query = "select tgl_trans,tgl,awal,cn from hrd.mr_dokt where karyawanid='$srid' and dokterid='$dokterid' order by tgl";
	// echo $query.'<br>';
	$result = mysqli_query($cnit, $query);
	$num_results = mysqli_num_rows($result);
	$row = mysqli_fetch_array($result);
	$tglawal_ = substr($row['tgl'],0,7);
	if ($tglawal_=="0000-00") {
		$tglawal_ = date('Y-').'01';
		$tahun = date('Y'); 
		$tahun1 = $tahun - 01;
		$tglawal1 = $tahun1.'-01'; 
	} else {
		$tglawal1 = $tglawal_;
	}
	$saldoawal_ = $row['awal']; 
	$cn = $row['cn'];

	if (($saldoawal_=="") or ($cn=="")) {
		$query = "select tgl,awal,cn from hrd.mr_dokt where dokterid='$dokterid'"; 
		// echo $query.'<br>';
		$result = mysqli_query($cnit, $query);
		$num_results = mysqli_num_rows($result);
		$row = mysqli_fetch_array($result);
		$tglawal_ = substr($row['tgl'],0,7); 
		if ($tglawal_=="0000-00") {
			$tglawal_ = date('Y-').'01'; 
			
		} 
		$saldoawal_ = $row['awal']; 
		$cn = $row['cn'];
	}

	// isi apt dispensing utk setiap mr
	$query = "select srid,aptid from hrd.mr_apt where srid=$srid and aptid=0000000000";
	// echo $query.'<br>';
	$result = mysqli_query($cnit, $query);
	$num_results = mysqli_num_rows($result);
	if ($num_results) {
	} else {
		$query = "insert into hrd.mr_apt (srid,aptid,apttype,nama,user1) values
				 ('$srid','0000000000','1','(dispensing)',$srid)"; 
		$result = mysqli_query($cnit, $query);
	}

	if ($dokterid == "*") {
		$query = "select ks1.*,dokter.nama as dokternm,dokter.cn,
				  MKT.iproduk.nama as prodnm,mr_apt.nama as aptnm
				  from hrd.ks1
				  left join hrd.dokter on ks1.dokterid=dokter.dokterid
				  left join MKT.iproduk on ks1.iprodid = MKT.iproduk.iProdId
				  left join hrd.mr_apt on (ks1.srid=mr_apt.srid and ks1.aptid=mr_apt.aptid)
				  where ks1.srid='$srid' and bulan<='$bulan'
				  order by dokternm,dokterid,bulan,aptnm,prodnm
		"; 
		//echo"$query";
	} else {
	
		$query = "select ks1.*,dokter.nama as dokternm,dokter.cn,
				  MKT.iproduk.nama as prodnm,mr_apt.nama as aptnm
				  from hrd.ks1
				  left join hrd.dokter on ks1.dokterid=dokter.dokterid
				  left join MKT.iproduk on ks1.iprodid = MKT.iproduk.iProdId
				  left join hrd.mr_apt on (ks1.srid=mr_apt.srid and ks1.aptid=mr_apt.aptid)
				  where ks1.srid='$srid' and ('$tglawal1' <= bulan and bulan<='$bulan') and ks1.dokterid='$dokterid'
				  order by dokternm,dokterid,bulan,aptnm,prodnm
		";
		//echo"$query";
    }
	
	// echo"<br>$query<br>";
	$query = "select nama as dokternm,dokterid from hrd.dokter where dokterid='$dokterid'";
	$result = mysqli_query($cnit, $query); //echo"$result";
	$row = mysqli_fetch_array($result);
	$dokternm_ = $row['dokternm'];
	$dokterid_ = $row['dokterid'];
	
/*
	$namadokter = substr($dokternm_,0,-4); 
	*/
	
	
	echo '
	<br>Customer : '.$dokternm_.' - '.$dokterid_.'<br>
	<table border="1" cellspacing="0" cellpadding="1">
		<tr>
			<th align="left"><small>Bulan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</small></th>
	';

	$header_ = add_space('Apotik',40);
	echo '
		<th align="left"><small>'.$header_.'</small></th>
		<th><small>Jenis</small></th>
	';

	$header_ = add_space('Produk',40);
	echo '
			<th align="left"><small>'.$header_.'</small></th>
			<th align="center"><small>Qty</small></th>
			<th><small>HNA</small></th>
			<th><small>Value</small></th>
			<th align="center"><small>Jumlah</small></th>
			<th align="center"><small>Total</small></th>
			<th align="center"><small>Approved</small></th>
			<th align="center"><small>Average Value</small></th>
		</tr>
	';

	if ($tglawal_=="0000-00") {
		echo "<tr><td><small>&nbsp;</small></td>";  
	} else {
		echo "<tr><td><small>$tglawal1</small></td>"; 
	}

	echo '
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><small><b>SA</b></small></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><small><b>'.number_format($saldoawal_,0).'</b></small></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	';

	$total_ = $saldoawal_;
	$total_val = '';
	$mulai = $tglawal1;
	$jml_ttl = 0;
	while ($mulai <= $akhir) {
		$tot = $total_;  

		// echo "$srid/$tglawal1/$mulai/$dokterid/$cn<br>"; 
		$total__ = show_kartu($srid,$tglawal1,$mulai,$dokterid,$cn,$br); 

		$total_ = $total_ + $total__;
		$total_val = $total_val+$total__;
		// echo $total_.'<br>';
		// echo $total_val.'<br>';

		if($total_val != '' || $total_val > 0){
			$jml_ttl = $jml_ttl + 1;
		}
		// echo $jml_ttl.'<br>';

		$dummy1 = substr($mulai,0,4); 
		$dummy2 = substr($mulai,5,2) + 1; 
		if ($dummy2 > 12) {
			$dummy1 = $dummy1 + 1;
			$dummy2 = "01";
		} else {
		}  // end if
		$dummy2 = "0".$dummy2;
		$dummy2 = substr($dummy2,-2);
		$mulai = $dummy1.'-'.$dummy2;
		//echo"$mulai<br>";

		if ($tot == $total_) {
			echo '
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
			';

		} else {
			// echo $total_.'~'.$total_val.'~'.$jml_ttl.'<br>';

			// untuk hitung cari nilai rata2 nya
			if($jml_ttl == 1){
				$total_val = $total_;
			}else{
				$total_val = $total_val/2;
			}
			// echo $total_val.'<br>';

			echo '
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td align="right"><small><b>'.number_format($total_,0).'</b></small></td>
					<td>&nbsp</td>
					<td align="right"><small><b>'.number_format($total_val,0).'</b></small></td>
			';

			// agar nilainya kembali normal sebelum di jumlahkan dngn total berikutnya
			// $total_val = $total_val*$jml_ttl;
		}

		echo '
			</tr>
		';
    } // end while 		
	echo '
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="right"><small><b>Total :</b></small></td>
				<td align="right"><small><b>'.number_format($total_,0).'</b></small></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>
	';
	
	//do_show_menu($_SESSION['jabatanid'],'N');

function show_kartu($psrid,$ptglawal_,$pbulan,$pdokterid,$pcn,$br) {
  //kartu status	
  
 // echo "pbulan==$pbulan";
	if ($psrid=="") {
		$query = "select ks1.*,MKT.iproduk.nama as prodnm,mr_apt.nama as aptnm, mr_dokt.awal
				  from hrd.ks1
				  left join MKT.iproduk on ks1.iprodid = MKT.iproduk.iProdId
				  left join hrd.mr_apt on (ks1.srid=mr_apt.srid and ks1.aptid=mr_apt.aptid)
				  left join hrd.mr_dokt on (ks1.srid=mr_dokt.karyawanid and ks1.dokterid=mr_dokt.dokterid)
				  where (bulan='$pbulan') and ks1.dokterid='$pdokterid'
				  order by bulan,aptnm,prodnm "; 
	} else {  
		$query = "select ks1.*,MKT.iproduk.nama as prodnm,mr_apt.nama as aptnm, mr_dokt.awal, cn.cn
				  from hrd.ks1 
				  left join MKT.iproduk on ks1.iprodid = MKT.iproduk.iProdId
				  left join hrd.mr_apt on (ks1.srid=mr_apt.srid and ks1.aptid=mr_apt.aptid)
				  left join hrd.mr_dokt on (ks1.srid=mr_dokt.karyawanid and ks1.dokterid=mr_dokt.dokterid)
				  left join hrd.cn on ks1.srid=cn.karyawanid and ks1.dokterid=cn.dokterid
				  where ks1.srid='$psrid' and (bulan='$pbulan') and ks1.dokterid='$pdokterid' and left(cn.tgl,7)='$pbulan'
				  order by bulan,aptnm,prodnm";//echo"$query";
    } 
	
	//echo"$query<br><br>";
	$result = mysqli_query($cnit, $query); 
	$num_results = mysqli_num_rows($result); //echo"num==$num_results<br>";
	
	if ($num_results==0) {
		$query = "select icabangid,areaid,divisiid,divisiid2 from karyawan where karyawanid='$psrid'";
		// echo "$query<br>";
		$result = mysqli_query($cnit, $query);
		$num_results = mysqli_num_rows($result);
		$row = mysqli_fetch_array($result);
		$icabangid = $row['icabangid']; 
		$areaid = $row['areaid']; 
		$divisiid = $row['divisiid']; 
		$divisiid2 = $row['divisiid2']; 
		
		// $query = "select ks1.*,MKT.iproduk.nama as prodnm,mr_apt.nama as aptnm, mr_dokt.awal,karyawan.icabangid,karyawan.areaid,karyawan.divisiid,karyawan.divisiid2
		// 		  from hrd.ks1
		// 		  left join MKT.iproduk on ks1.iprodid = MKT.iproduk.iProdId
		// 		  left join hrd.mr_apt on (ks1.srid=mr_apt.srid and ks1.aptid=mr_apt.aptid)
		// 		  left join hrd.mr_dokt on (ks1.srid=mr_dokt.karyawanid and ks1.dokterid=mr_dokt.dokterid)
		// 		  left join hrd.karyawan on ks1.srid=karyawan.karyawanid 
		// 		  where karyawan.areaid='$areaid' and karyawan.icabangid='$icabangid' and karyawan.divisiid='$divisiid' and karyawan.divisiid2='$divisiid2' and (bulan='$pbulan') and ks1.dokterid='$pdokterid'
		// 		  order by bulan,aptnm,prodnm "; //echo"$query";

		$query = "select ks1.*,MKT.iproduk.nama as prodnm,mr_apt.nama as aptnm, karyawan.icabangid,karyawan.areaid,karyawan.divisiid,karyawan.divisiid2
				  from hrd.ks1
				  left join MKT.iproduk on ks1.iprodid = MKT.iproduk.iProdId
				  left join hrd.mr_apt on (ks1.srid=mr_apt.srid and ks1.aptid=mr_apt.aptid)
				  left join hrd.karyawan on ks1.srid=karyawan.karyawanid 
				  where karyawan.areaid='$areaid' and karyawan.icabangid='$icabangid' and karyawan.divisiid='$divisiid' and karyawan.divisiid2='$divisiid2' and (bulan='$pbulan') and ks1.dokterid='$pdokterid' and ks1.srid='$psrid'
				  order by bulan,aptnm,prodnm 
		"; 
		//echo"$query";
		$result = mysqli_query($cnit, $query); 
		$num_results = mysqli_num_rows($result); 
	}
	//echo"$query<br><br>";

	$row = mysqli_fetch_array($result);
	$i = 1;
	$mtotal_ = 0;
	// print_r($row);
	while ($i <= $num_results) {
		$bulan_ = $row['bulan']; 
		$first_ = 1;
	
		echo "
			<tr>
				<td><small>$bulan_</small></td>
				<td>&nbsp;</td>
				<td>&nbsp</td>
				<td>&nbsp</td>
				<td>&nbsp</td>
				<td>&nbsp;</td>
				<td>&nbsp</td>
				<td>&nbsp</td>
				<td>&nbsp</td>
				<td>&nbsp</td>
				<td>&nbsp</td>
			</tr>
		";

		while ( ($bulan_==$row['bulan']) ) {
			$cn = $row['cn']; //echo"$bulan_////$cn<br>";
			$qty = $row['qty'];
			$iprodid = $row['iprodid'];
			$namaapt = $row['aptnm'];
			$kodeapt = $row['aptid'];
			
			if ($cn == '') {
				$query_sa = " select cn from hrd.cn where karyawanid='$psrid' and dokterid='$pdokterid' and tgl<='$pbulan' order by tgl desc"; 
				//echo"$query_sa<br>";
				$result_sa = mysqli_query($cnit, $query_sa);
				$num_results_sa = mysqli_num_rows($result_sa);
				if ($num_results_sa) {
					$row_sa = mysqli_fetch_array($result_sa);
					$cn = $row_sa['cn']; 
				}	
			}
			if ($cn == '') {
				$cn = $pcn;
			}
			//ambil cn
			$str_ = "select * from hrd.mr_dokt_a where karyawanid='$psrid' and dokterid='$pdokterid' and left(tgl,7)<='$pbulan'
			         order by tgl desc"; 
			// echo "$str_<br>";
			$res2_ = mysqli_query($cnit, $str_); 
			$records_ = mysqli_num_rows($res2_);
			$row2_ = mysqli_fetch_array($res2_);
			$cn = $row2_['cn'];
			
			// echo "cn=$cn ~~ bulan = $pbulan";
		
			
			if ($cn > '20') {
				$sim = 'A+';
			} else {
				if ($cn == '20') {
					$sim = 'A';
				} else {
					if ($cn > '15' and $cn < '20') {
						$sim = 'B+';
					} else {
						if ($cn == '15') {
							$sim = 'B';
						} else {
							if ($cn > '10' and $cn < '15') {
								$sim = 'C+';
							} else {
								if ($cn == '10') {
									$sim = 'C';
								} else {
									if ($cn > '5' and $cn < '10') {
										$sim = 'D+';
									} else {
										if ($cn == '5') {
											$sim = 'D';
										} else {
											if ($cn < '5') {
												$sim = 'E';
											} else {
											}												
										}
									}
								}
							}
						}
					}
				}
			}
			
			//echo "cn =$bulan_ //$cn<br>";
			
			
			echo "<tr>";
		
			if ($first_) {
				echo "<td><small>SIM = $sim</small></td>";
				$first_ = 0;
			} else {
				echo "<td><small>&nbsp;</small></td>";
			}
		//	echo "<td><small>".$row['aptnm']."</small></td>";
			echo "<td><small>$namaapt</small></td>";
			$jumlah_ = 0;
			if ($row['apttype']=="1") {
				echo '<td align="center"><small>D</small></td>';
			} else {
				echo '<td align="center"><small>R</small></td>';
			}
			echo '
				<td><small>'.$row['prodnm'].'</small></td>
				<td align="center"><small>'.number_format($row['qty'],0).'</small></td>
				<td align="right"><small>'.number_format($row['hna'],0).'</small></td>
			';
			$value = 0;
			$value =$row['qty'] * $row['hna'];
			
			echo '<td align="right"><small>'.number_format($value,0)."</small></td>";
			if ($row['apttype']=="1") {
				$jumlah_ = 0 - ($value * ($cn/100)); 
			} else {
				$jumlah_ = 0 - ($value * 0.8 * ($cn/100)); 
			}
			echo '
				<td align="right"><small>'.number_format($jumlah_,0).'</small></td>
				<td align="center"><small>&nbsp;</small></td>
			';	
			if ($row['approved']=="Y") {
				echo '
					<td align="center"><small>Ya</small></td>
				';
			} else {
				echo '
					<td align="center"><small>&nbsp;</small></td>
					<td align="center"><small>&nbsp;</small></td>
				';
			}	 
			$mtotal_ = $mtotal_ + $jumlah_;
			$mvalue_ = $mvalue_ + $value;
		
			//echo "$pbulan///$psrid///$pdokterid///$cn///$iprodid///$qty///$jumlah_<br>";
			
			echo "</tr>";
			$row = mysqli_fetch_array($result);
			$i++;			  
		} 		
		echo '
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="right"><small><b>'.number_format($mvalue_,0).'</b></small></td>
				<td align="right"><small><b>'.number_format($mtotal_,0).'</b></small></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		';	
    } // eof  i<= num_results
	
	// ambil br per bulan
	
	//cek ks2 rep2
	$query_ck1 = "select rep1 from ks2 where rep2='$psrid' and dokterid='$pdokterid'";  
	// echo "$query_ck1<br>";
	// echo"cek ks2 = $query_ck1 <br>";
	
	$result_ck1 = mysqli_query($cnit, $query_ck1);
	$num_results_ck1 = mysqli_num_rows($result_ck1);
	$row_ck1 = mysqli_fetch_array($result_ck1);
	$rep1_ = $row_ck1['rep1'];
	//mrid='$psrid' and
	//wh20130516, koreksi br tidak muncul di ks
	// echo $rep1_;
	$queryBr1 = "
		SELECT br0.*, br_kode.ks
	    FROM hrd.br0 
	    JOIN hrd.br_kode on br0.kode=br_kode.kodeid 
		WHERE mrid='$psrid' and dokterid='$pdokterid' and left(tgl,7)='$pbulan' and br_kode.ks='Y'
		ORDER BY tgl
	"; 

	$queryBr1 = "
		SELECT br0.*, br_kode.ks
	    FROM hrd.br0 
	    JOIN hrd.br_kode on br0.kode=br_kode.kodeid 
		WHERE mrid='$rep1_' AND dokterid='$pdokterid' AND left(tgl,7)='$pbulan' AND br_kode.ks='Y'
		ORDER BY tgl
	";
	// 	$queryBr1 = "
	// 	SELECT br0.*, br_kode.ks
	//     FROM hrd.br0 
	//     JOIN hrd.br_kode on br0.kode=br_kode.kodeid 
	// 	WHERE dokterid='$pdokterid' AND left(tgl,7)='$pbulan' AND br_kode.ks='Y'
	// 	ORDER BY tgl
	// "; 
	
	// echo"$queryBr1 <br>";
	$resultBr1 = mysqli_query($cnit, $queryBr1);
	$num_resultsBr1 = mysqli_num_rows($resultBr1); 
	$rowBr1 = mysqli_fetch_array($resultBr1);
	$j = 1;
	// print_r($num_resultsBr1);
	
	if($br == 0){
		while ($j <= $num_resultsBr1) {
			$tglBr = substr($rowBr1['tgl'],0,7);
			$ccyId = $rowBr1['ccyId'];
			$jumlah = $rowBr1['jumlah']; 
			$jumlah1 = $rowBr1['jumlah1']; 
			$batal = $rowBr1['batal'];
			$thnccy = substr($rowBr1['tgl'],0,4);
			echo $br.'-'.$jumlah;
			echo "
				<tr>
					<td><small>$tglBr</small></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td><b>KI</b></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
			";
			
			if ($jumlah1==0) {
				if ($ccyId<>'IDR') {
					$query_cc = "select nilai from hrd.ccy where ccyId='$ccyId' and tahun='$thnccy'";
					$result_cc = mysqli_query($cnit, $query_cc);
					$num_results_cc = mysqli_num_rows($result_cc);
					if ($num_results_cc) {
						 $row_cc = mysqli_fetch_array($result_cc);
						 $nilai_cc = $row_cc['nilai'];
					}
					if ($batal == 'Y') {
						$jumlah = 0;
						echo "
							<td align=right><b><small>$ccyId ".number_format($jumlah,0)."</small></td>
							<td>&nbsp;</td>
						";
					} else {
						echo "
							<td align=right><b><small>$ccyId ".number_format($jumlah,0)."</small></td>
							<td>&nbsp;</td>
						";
					}
					$jumlah2 = $jumlah * $nilai_cc;
				} else {
					if ($batal == 'Y' ) {
						$jumlah = 0;
						echo "
							<td align=right><b><small>".number_format($jumlah,0)."</small></td>
							<td>&nbsp;</td>
						";
					} else {
						echo "
							<td align=right><b><small>".number_format($jumlah,0)."</small></td>
							<td>&nbsp;</td>
						";
					}
					$jumlah2 = $jumlah;
				}

			} else {

				if ($ccyId<>'IDR') {
					$query_cc = "select nilai from ccy where ccyId='$ccyId'";
					$result_cc = mysqli_query($cnit, $query_cc);
					$num_results_cc = mysqli_num_rows($result_cc);
					if ($num_results_cc) {
						 $row_cc = mysqli_fetch_array($result_cc);
						 $nilai_cc = $row_cc['nilai'];
					}
					if ($batal=='Y') {
						$jumlah1 = 0;
						echo "
							<td align=right><b><small>$ccyId ".number_format($jumlah1,0)."</small></td>
							<td>&nbsp;</td>
						";
					} else {
						echo "
							<td align=right><b><small>$ccyId ".number_format($jumlah1,0)."</small></td>
							<td>&nbsp;</td>
						";
					}
					$jumlah2 = $jumlah1 * $nilai_cc;
				} else {
					if ($batal=='Y') {
						$jumlah1 = 0;
						echo "
							<td align=right><b><small>".number_format($jumlah1,0)."</small></td>
							<td>&nbsp;</td>
						";
					} else {
						echo "
							<td align=right><b><small>".number_format($jumlah1,0)."</small></td>
							<td>&nbsp;</td>
						";
					}
					$jumlah2 = $jumlah1;
				}
			}
			echo "
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			";
			$mtotal_ = $mtotal_ + $jumlah2; 
			$rowBr1 = mysqli_fetch_array($resultBr1);
			$j++;
	    }
	}
	
	//cek ks2 rep1
	$query_ck2 = "select * from hrd.ks2 where rep1='$psrid' and dokterid='$pdokterid'"; // echo"$query_ck";
	$result_ck2 = mysqli_query($cnit, $query_ck2);
	$num_results_ck2 = mysqli_num_rows($result_ck2); 
	// echo"$query_ck2";

	if ($num_results_ck2<>'0') {
		
	} else {
		$query_ck = "select karyawanid from hrd.mr_dokt where dokterid='$pdokterid'"; // echo"$query_ck";
		$result_ck = mysqli_query($cnit, $query_ck);
		$num_results_ck = mysqli_num_rows($result_ck);
		$row_ck = mysqli_fetch_array($result_ck);
		$karyawanid_ = $row_ck['karyawanid'];
		//mrid='$psrid' and
		$queryBr = "
			select br0.*, br_kode.ks
			from hrd.br0 
			join hrd.br_kode on br0.kode=br_kode.kodeid 
			where mrid='$psrid' and dokterid='$pdokterid' and left(tgl,7)='$pbulan' and br_kode.ks='Y'
			order by tgl
		"; 
		// echo"$queryBr";

		$resultBr = mysqli_query($cnit, $queryBr); 
		$num_resultsBr = mysqli_num_rows($resultBr); 
		$rowBr = mysqli_fetch_array($resultBr);
		$j = 1;
		// print_r($rowBr);

		if($br == 0){
			while ($j <= $num_resultsBr) { 
				$tglBr = substr($rowBr['tgl'],0,7);
				$ccyId = $rowBr['ccyId'];
				$jumlah = $rowBr['jumlah']; 
				$jumlah1 = $rowBr['jumlah1']; 
				$batal = $rowBr['batal']; 
				$aktivitas1 = $rowBr['aktivitas1']; 
				
				echo "
					<tr>
						<td><small>$tglBr</small></td>
						<td><b>KI</b></td>
						<td>&nbsp;</td>
						<td><b><small>&nbsp;</small></b></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
				";
				
				if ($jumlah1==0) {
					if ($ccyId<>'IDR') {
						$query_cc = "select nilai from ccy where ccyId='$ccyId'";
						$result_cc = mysqli_query($cnit, $query_cc);
						$num_results_cc = mysqli_num_rows($result_cc);
						if ($num_results_cc) {
							 $row_cc = mysqli_fetch_array($result_cc);
							 $nilai_cc = $row_cc['nilai'];
						}
						if ($batal == 'Y') {
							$jumlah = 0;
							echo "
								<td align=right><b><small>$ccyId ".number_format($jumlah,0)."</small></td>
								<td>&nbsp;</td>
							";
						} else {
							echo "
								<td align=right><b><small>$ccyId ".number_format($jumlah,0)."</small></td>
								<td>&nbsp;</td>
							";
						}
						$jumlah2 = $jumlah * $nilai_cc;
					} else {
						if ($batal == 'Y' ) {
							$jumlah = 0;
							echo "
								<td align=right><b><small>".number_format($jumlah,0)."</small></td>
								<td>&nbsp;</td>
							";
						} else {
							echo "
								<td align=right><b><small>".number_format($jumlah,0)."</small></td>
								<td>&nbsp;</td>
							";
						}
						$jumlah2 = $jumlah;
					}

				} else {
					
					if ($ccyId<>'IDR') {
						$query_cc = "select nilai from ccy where ccyId='$ccyId'";
						$result_cc = mysqli_query($cnit, $query_cc);
						$num_results_cc = mysqli_num_rows($result_cc);
						if ($num_results_cc) {
							 $row_cc = mysqli_fetch_array($result_cc);
							 $nilai_cc = $row_cc['nilai'];
						}
						if ($batal=='Y') {
							$jumlah1 = 0;
							echo "
								<td align=right><b><small>$ccyId ".number_format($jumlah1,0)."</small></td>
								<td>&nbsp;</td>
							";
						} else {
							echo "
								<td align=right><b><small>$ccyId ".number_format($jumlah1,0)."</small></td>
								<td>&nbsp;</td>
							";
						}
						$jumlah2 = $jumlah1 * $nilai_cc;
					} else {
						if ($batal=='Y') {
							$jumlah1 = 0;
							echo "
								<td align=right><b><small>".number_format($jumlah1,0)."</small></td>
								<td>&nbsp;</td>
							";
						} else {
							echo "
								<td align=right><b><small>".number_format($jumlah1,0)."</small></td>
								<td>&nbsp;</td>
							";
						}
						$jumlah2 = $jumlah1;
					}
				}

				echo "
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					</tr>
				";
				$mtotal_ = $mtotal_ + $jumlah2; 
				$rowBr = mysqli_fetch_array($resultBr);
				$j++;
		    }
		}
	}
	
    //cetak subtotal
	mysqli_free_result($resultBr);
	return $mtotal_;

} // end function 

?>

</form>
</body>
</html>
