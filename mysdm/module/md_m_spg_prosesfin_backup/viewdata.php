<?php
    session_start();
if ($_GET['module']=="gantitombol") {
    $ptipe=$_POST['utipe'];
    ?>
        <div id="c_tombol">
            <div class='col-sm-6'>
                <small>&nbsp;</small>
               <div class="form-group">
                   <?PHP
                   if ($ptipe=="A") {
                       ?>
                       <input type='button' class='btn btn-success btn-xs' id="s-submit" value="Belum Proses" onclick="RefreshDataTabel('1')">&nbsp;
                       <input type='button' class='btn btn-info btn-xs' id="s-submit" value="Sudah Proses" onclick="RefreshDataTabel('2')">&nbsp;
                       <input type='button' class='btn btn-danger btn-xs' id="s-submit" value="Pending" onclick="RefreshDataTabel('3')">&nbsp;
                       <input type='button' class='btn btn-default btn-xs' id="s-submit" value="Sudah Proses MNG" onclick="RefreshDataTabel('4')">&nbsp;
                       <?PHP
                   }else{
                       ?>
                       <input type='button' class='btn btn-success btn-xs' id="s-submit" value="Belum Proses" onclick="DataPD('1')">&nbsp;
                       <input type='button' class='btn btn-info btn-xs' id="s-submit" value="Sudah Proses" onclick="DataPD('2')">&nbsp;
                       <?PHP
                   }
                   ?>
               </div>
           </div>
       </div>
    <?PHP

}elseif ($_GET['module']=="hitungtotalcekbox") {
    include "../../config/koneksimysqli.php";
    
    $pnoid=$_POST['unoidbr'];
    
    
    $totalinput=0;
    
    $query="SELECT SUM(total) as jumlah from dbmaster.t_spg_gaji_br0 WHERE idbrspg IN $pnoid";
    $tampil= mysqli_query($cnmy, $query);
    $ketemu= mysqli_num_rows($tampil);
    if ($ketemu>0) {
        $tr= mysqli_fetch_array($tampil);
        if (!empty($tr['jumlah'])) $totalinput=$tr['jumlah'];
    }
    echo $totalinput;
    
}elseif ($_GET['module']=="simpan") {
    $berhasil="Tidak ada data yang diproses...";
    include "../../config/koneksimysqli.php";
    $apvid=$_SESSION['IDCARD'];
    
    if (empty($apvid)) {
        echo "Anda harus login ulang....";
        exit;
    }
    
    $pnoid=$_POST['unoidbr'];
    $ptipests=$_POST['utipests'];
    
    $gbrapv=$_POST['uttd'];
    
    //, sts='$ptipests'
    $query = "UPDATE dbmaster.t_spg_gaji_br0 set apv2='$apvid', apvtgl2=NOW(), apvgbr2='$gbrapv' WHERE "
            . " idbrspg IN $pnoid";
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo "ERROR...."; exit; }
    
    $berhasil = "data berhasil diproses";
    
    echo $berhasil;
}elseif ($_GET['module']=="simpanpending") {
    $berhasil="Tidak ada data yang diproses...";
    include "../../config/koneksimysqli.php";
    $apvid=$_SESSION['IDCARD'];
    
    if (empty($apvid)) {
        echo "Anda harus login ulang....";
        exit;
    }
    
    $pnoid=$_POST['unoidbr'];
    $ptipests=$_POST['utipests'];
    
    
    $query = "UPDATE dbmaster.t_spg_gaji_br0 set apv2='$apvid', apvtgl2=NOW(), sts='$ptipests' WHERE "
            . " idbrspg IN $pnoid";
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo "ERROR...."; exit; }
    
    $berhasil = "data berhasil diproses";
    
    echo $berhasil;
}elseif ($_GET['module']=="hapus") {
    $berhasil="Tidak ada data yang diunproses...";
    include "../../config/koneksimysqli.php";
    
    $pnoid=$_POST['unoidbr'];
    
    //, sts=''
    $query = "UPDATE dbmaster.t_spg_gaji_br0 set apv2=NULL, apvtgl2=NULL, apvgbr2=NULL WHERE "
            . " idbrspg IN $pnoid";
    mysqli_query($cnmy, $query);
    $erropesan = mysqli_error($cnmy); if (!empty($erropesan)) { echo "ERROR...."; exit; }
    
    $berhasil = "data berhasil diunproses";
    
    echo $berhasil;
}elseif ($_GET['module']=="xxx") {
    
}
?>
