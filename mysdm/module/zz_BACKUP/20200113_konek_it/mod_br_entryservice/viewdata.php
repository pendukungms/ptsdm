<?php
session_start();
if ($_GET['module']=="caridivisi"){
    include "../../config/koneksimysqli.php";
    include "../../config/fungsi_sql.php";
    $pkaryawan=trim($_POST['umr']);
    $divisi = "";
    $pjabatanid = "";
    $rank = "";
    $lvlpos = "";
    $pdivisi = $_SESSION['DIVISI'];
    
    $cari = "select * from dbmaster.t_karyawan_posisi WHERE karyawanId='$pkaryawan'";
    $tampil = mysqli_query($cnmy, $cari);
    $ketemu = mysqli_num_rows($tampil);
    if ($ketemu>0) {
        $j= mysqli_fetch_array($tampil);
        $divisi = $j['divisiId'];
        $pjabatanid = $j['jabatanId'];
    }else{
        if ($_SESSION['DIVISIKHUSUS']!="Y")
            $pdivisi = getfieldcnit("select CONCAT(divisiId,',',divisiId2) as lcfields from hrd.karyawan where karyawanId='$pkaryawan'");

        $cdivisi = explode(",", $pdivisi);
        $divisi = $cdivisi[0];
        if (empty($cdivisi[0])) {
            $divisi = $cdivisi[1];
        }
        $pjabatanid = getfieldcnit("select jabatanId as lcfields from hrd.karyawan where karyawanId='$pkaryawan'");
    }
    
    if (!empty($pjabatanid)) {
        $rank = getfieldcnit("select rank as lcfields from hrd.jabatan where jabatanId='$pjabatanid'");
        $lvlpos = getfieldcnit("select LEVELPOSISI as lcfields from dbmaster.jabatan_level where jabatanId='$pjabatanid'");
        $lvlpos=trim($lvlpos);
    }
    echo "$pkaryawan,$pjabatanid,$rank,$lvlpos,$divisi";
}elseif ($_GET['module']=="viewdatadivisi"){
    include "../../config/koneksimysqli_it.php";
    $rank=trim($_POST['urank']);
    $kry=trim($_POST['umr']);
    $udivisi=trim($_POST['udivisi']);
    if (empty($udivisi)) $udivisi = "HO";
    
    if ($rank=="05" OR $rank==5)
        $sql= mysqli_query($cnit, "select distinct divisiid from MKT.imr0 where karyawanid='$kry'");
    elseif ($rank=="04" OR $rank==4)
        $sql= mysqli_query($cnit, "select distinct divisiid from MKT.ispv0 where karyawanid='$kry'");
    else
        $sql=mysqli_query($cnit, "SELECT DivProdId divisiid FROM MKT.divprod where br='Y' order by nama");
    
    $ketemu= mysqli_num_rows($sql);
    if ($ketemu==0) {
        $sql=mysqli_query($cnit, "SELECT DivProdId divisiid FROM MKT.divprod where br='Y' order by nama");
        $ketemu= mysqli_num_rows($sql);
    }
    
    
    echo "<option value=''>-- Pilihan --</option>";
    while ($Xt=mysqli_fetch_array($sql)){
        if ($ketemu==1)
            echo "<option value='$Xt[divisiid]' selected>$Xt[divisiid]</option>";
        else {
            if ($Xt['divisiid']==$udivisi)
                echo "<option value='$Xt[divisiid]' selected>$Xt[divisiid]</option>";
            else
                echo "<option value='$Xt[divisiid]'>$Xt[divisiid]</option>";
        }
    }
}elseif ($_GET['module']=="viewdataatasanspv"){
    include "../../config/koneksimysqli_it.php";
    $karyawan=trim($_POST['umr']);
    $query = "select distinct a.karyawanid karyawanId, nama from dbmaster.v_penempatanspv as a WHERE CONCAT(a.divisiid, a.icabangid, a.areaid) in 
        (select CONCAT(b.divisiid, b.icabangid, b.areaid) 
        from MKT.imr0 b where b.karyawanid='$karyawan')";
    $query .=" order by nama, karyawanId";
    $tampil = mysqli_query($cnit, $query);
    $ketemu = mysqli_num_rows($tampil);
    echo "<option value='' selected>blank_</option>";
    while($a=mysqli_fetch_array($tampil)){ 
        $sel="";
        if ($ketemu==1) $sel="selected";
        echo "<option value='$a[karyawanId]' $sel>$a[nama]</option>";
    }
}elseif ($_GET['module']=="viewdataatasandm"){
    include "../../config/koneksimysqli_it.php";
    $karyawan=trim($_POST['umr']);
    $query = "select distinct a.karyawanid karyawanId, nama from dbmaster.v_penempatandm as a WHERE CONCAT(a.icabangid) in 
        (select CONCAT(b.icabangid) 
        from MKT.ispv0 b where b.karyawanid='$karyawan')";
    $query .=" order by nama, karyawanId";
    $tampil = mysqli_query($cnit, $query);
    $ketemu = mysqli_num_rows($tampil);
    echo "<option value='' selected>blank_</option>";
    while($a=mysqli_fetch_array($tampil)){
        $sel="";
        if ($ketemu==1) $sel="selected";
        echo "<option value='$a[karyawanId]' $sel>$a[nama]</option>";
    }
}elseif ($_GET['module']=="viewdataatasansm"){
    include "../../config/koneksimysqli_it.php";
    $karyawan=trim($_POST['umr']);
    $query = "select distinct a.karyawanid karyawanId, nama from dbmaster.v_penempatansm as a WHERE CONCAT(a.icabangid) in 
        (select CONCAT(b.icabangid) 
        from MKT.idm0 b where b.karyawanid='$karyawan')";
    $query .=" order by nama, karyawanId";
    $tampil = mysqli_query($cnit, $query);
    $ketemu = mysqli_num_rows($tampil);
    echo "<option value='' selected>blank_</option>";
    while($a=mysqli_fetch_array($tampil)){
        $sel="";
        if ($ketemu==1) $sel="selected";
        echo "<option value='$a[karyawanId]' $sel>$a[nama]</option>";
    }
}elseif ($_GET['module']=="viewdatakendaraan"){
    include "../../config/koneksimysqli_it.php";
    include "../../config/fungsi_sql.php";
    $karyawan=trim($_POST['umr']);
    $filnopol="";
    $adakendaraan = getfieldcnit("select nopol as lcfields from dbmaster.t_kendaraan_pemakai where karyawanid='$karyawan' and stsnonaktif <> 'Y'");
    //if (!empty($adakendaraan))
        $filnopol=" AND nopol in (select distinct nopol from dbmaster.t_kendaraan_pemakai where karyawanid='$karyawan' and stsnonaktif <> 'Y')";
    
    $query = "select * from dbmaster.t_kendaraan WHERE 1=1 $filnopol ";
    $query .=" order by merk, tipe, nopol";
    $tampil = mysqli_query($cnit, $query);
    $ketemu = mysqli_num_rows($tampil);
    echo "<option value='' selected>-- Pilihan --</option>";
    while($a=mysqli_fetch_array($tampil)){
        if ($ketemu<=1)
            echo "<option value='$a[nopol]' selected>$a[nopol] - $a[merk] $a[tipe]</option>";
        else
            echo "<option value='$a[nopol]'>$a[nopol] - $a[merk] $a[tipe]</option>";
    }
}elseif ($_GET['module']=="xxx"){
    
}
?>