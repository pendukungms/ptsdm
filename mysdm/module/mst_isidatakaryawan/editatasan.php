<?PHP

$iservername = "203.142.71.82";
$iservername = "192.168.88.25";
$iusername = "root";
$ipassword = "sdmmysqlserver2017";
//$icnit = mysqli_connect($iservername, $iusername, $ipassword) or die("Connection failed: " . mysqli_connect_error());

//include "config/koneksimysqli_it.php";
//$icnit=$cnit;

include "config/koneksimysqli.php";
$icnit=$cnmy;

$pilogin=false;
if (isset($_GET['nlog'])) {
    if ($_GET['nlog']=="ilog") $pilogin=true;
}

$nidkaryawan="";
$nnmkaryawan="";
$nnmarea="";
$nidatasan="";
$nnmatasan="";

$atasanidspv="";
$nnmspv="";
$atasaniddm="";
$nnmdm="";
$atasanidsm="";
$nnmsm="";
$atasanidgsm="";
$nnmgsm="";

$pdivisiid="";
$jabatanid="";

$act="updateatasan";
$idnya=$_GET['id'];
//$idnya=$_SESSION['IDCARD'];

$query = "select a.karyawanId, a.nama, a.jabatanId, a.iCabangId, c.nama nmcabang, a.areaId, d.nama nmarea, a.divisiId, a.divisiId2, 
    b.divisi1, b.divisi2, b.divisi3, b.atasanId, i.nama nmatasan, b.spv, e.nama nmspv, b.dm, f.nama nmdm, b.sm, g.nama nmsm, b.gsm, h.nama nmgsm 
    from hrd.karyawan a LEFT JOIN dbmaster.t_karyawan_posisi b on 
    a.karyawanId=b.karyawanId 
    LEFT JOIN MKT.icabang c on a.iCabangId=c.iCabangId
    LEFT JOIN MKT.iarea d on a.areaId=d.areaId and a.iCabangId=d.iCabangId 
    LEFT JOIN hrd.karyawan e on b.spv=e.karyawanId 
    LEFT JOIN hrd.karyawan f on b.dm=f.karyawanId 
    LEFT JOIN hrd.karyawan g on b.sm=g.karyawanId 
    LEFT JOIN hrd.karyawan h on b.gsm=h.karyawanId 
    LEFT JOIN hrd.karyawan i on b.atasanId=i.karyawanId 
    WHERE a.karyawanid='$idnya'";

$tampil= mysqli_query($icnit, $query);
$ketemu = mysqli_num_rows($tampil);
if ($ketemu>0) {
    $row= mysqli_fetch_array($tampil);

    $nidkaryawan=$row['karyawanId'];
    $nnmkaryawan=$row['nama'];

    $nidatasan=$row['atasanId'];
    $nnmatasan=$row['nmatasan'];
    
    $atasanidspv=$row['spv'];
    $nnmspv=$row['nmspv'];
    $atasaniddm=$row['dm'];
    $nnmdm=$row['nmdm'];
    $atasanidsm=$row['sm'];
    $nnmsm=$row['nmsm'];
    $atasanidgsm=$row['gsm'];
    $nnmgsm=$row['nmgsm'];
    
    $pdivisiid=$row['divisiId'];
    $jabatanid=$row['jabatanId'];
    
}


$lblnmdm="DM";
$lblnmgsm="GSM";
if ($_SESSION['DIVISI']=="OTC") {
    $lblnmdm="AM";
    $lblnmgsm="HOS";    
}

$phiddenspv="";
$phiddendm="";
$phiddensm="";

if ($jabatanid=="15") {
}elseif ($jabatanid=="10" OR $jabatanid=="18") {
    $phiddenspv="hidden";
    
    $atasanidspv="";
    $nnmspv="";
}elseif ($jabatanid=="08") {
    $phiddenspv="hidden";
    $phiddendm="hidden";
    
    $atasanidspv="";
    $nnmspv="";
    $atasaniddm="";
    $nnmdm="";
}elseif ($jabatanid=="20") {
    $phiddenspv="hidden";
    $phiddendm="hidden";
    $phiddensm="hidden";
    
    $atasanidspv="";
    $nnmspv="";
    $atasaniddm="";
    $nnmdm="";
    $atasanidsm="";
    $nnmsm="";
}


?>

<link href="css/inputselectbox.css" rel="stylesheet" type="text/css" />
<link href="css/stylenew.css" rel="stylesheet" type="text/css" />

<script> window.onload = function() { document.getElementById("e_id").focus(); } </script>

<div class="">

    <!--row-->
    <div class="row">
        
        <form method='POST' action='<?PHP echo "$aksi?module=$_GET[module]&act=input&idmenu=$_GET[idmenu]"; ?>' id='demo-form2' name='form1' data-parsley-validate class='form-horizontal form-label-left'  enctype='multipart/form-data'>
        
            <input type='hidden' id='u_module' name='u_module' value='<?PHP echo $_GET['module']; ?>' Readonly>
            <input type='hidden' id='u_idmenu' name='u_idmenu' value='<?PHP echo $_GET['idmenu']; ?>' Readonly>
            
            <input type='hidden' id='u_act' name='u_act' value='<?PHP echo $act; ?>' Readonly>
            
            
            <div class='col-md-12 col-sm-12 col-xs-12'>
                
                <div class='x_panel'>
                
                    <div class='x_panel'>
                        <div class='x_content'>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>KARYAWAN ID <span class='required'></span></label>
                                    <div class='col-md-4'>
                                        <input type='text' id='e_id' name='e_id' class='form-control col-md-7 col-xs-12' value='<?PHP echo $idnya; ?>' Readonly>
                                        <input type='hidden' id='e_divisiid' name='e_divisiid' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pdivisiid; ?>' Readonly>
                                        <input type='hidden' id='e_jabatanid' name='e_jabatanid' class='form-control col-md-7 col-xs-12' value='<?PHP echo $jabatanid; ?>' Readonly>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for='e_nama'>NAMA <span class='required'></span></label>
                                    <div class='col-xs-5'>
                                        <input type='text' id='e_nama' name='e_nama' class='form-control col-md-7 col-xs-12' value='<?PHP echo $nnmkaryawan; ?>' Readonly>
                                    </div>
                                </div>
                                
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for='e_jabatan'>JABATAN <span class='required'></span></label>
                                    <div class='col-xs-5'>
                                        <select class='form-control input-sm' id='e_jabatan' name='e_jabatan' onchange="showDataKaryawan('tambahbaru', 'e_idkaryawan')" disabled="">
                                            <?PHP
                                            $sql=mysqli_query($icnit, "SELECT jabatanId, nama FROM hrd.jabatan order by jabatanId");
                                            $ketemu= mysqli_num_rows($sql);
                                            echo "<option value=''>-- Pilihan --</option>";
                                            while ($Xt=mysqli_fetch_array($sql)){
                                                if ($Xt['jabatanId']==$jabatanid)
                                                    echo "<option value='$Xt[jabatanId]' selected>$Xt[jabatanId] - $Xt[nama]</option>";
                                                else
                                                    echo "<option value='$Xt[jabatanId]'>$Xt[jabatanId] - $Xt[nama]</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                
                                <div <?PHP echo $phiddenspv; ?> class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for='e_spv'>SPV <span class='required'></span></label>
                                    <div class='col-xs-5'>
                                        <select class='form-control input-sm' id='e_spv' name='e_spv' onchange="ShowDataDM()">
                                            <?PHP
                                            $query ="select karyawanid, nama from hrd.karyawan where 1=1 "
                                                    . " AND (aktif='Y' OR karyawanid='$atasanidspv') ";
                                            if ($_SESSION['DIVISI']=="OTC"){
                                                $query .=" AND divisiid ='OTC' ";
                                                $query .=" AND karyawanId Not In (select distinct karyawanId from dbmaster.t_karyawanadmin) ";
                                            }else{
                                                //$query .=" AND jabatanid in ('10', '18')";
                                            }
                                            $query .=" AND LEFT(nama,4) NOT IN ('NN -', 'DR -', 'DM -', 'BDG ', 'OTH.')  and LEFT(nama,7) NOT IN ('NN DM - ')  and LEFT(nama,3) NOT IN ('TO.', 'TO-', 'DR ', 'DR-') AND LEFT(nama,5) NOT IN ('NN AM', 'NN DR') ";
                                            $query .=" ORDER BY nama";
                                            $sql=mysqli_query($icnit, $query);
                                            echo "<option value=''>-- Pilihan --</option>";
                                            while ($Xt=mysqli_fetch_array($sql)){
                                                $xid=$Xt['karyawanid'];
                                                $xnama=$Xt['nama'];
                                                
                                                if ($xid==$atasanidspv)
                                                    echo "<option value='$xid' selected>$xnama</option>";
                                                else
                                                    echo "<option value='$xid'>$xnama</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div <?PHP echo $phiddendm; ?> class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''><?PHP echo $lblnmdm; ?> <span class='required'></span></label>
                                    <div class='col-xs-5'>
                                        <select class='form-control input-sm' id='e_dm' name='e_dm' onchange="ShowDataSM()">
                                            <?PHP
                                            //PilihKaryawanAktif("", "-- Pilihan --", $atasaniddm, "Y", $_SESSION['STSADMIN'], "", "", "Y", $_SESSION['IDCARD'], "", "", "", "", "");
                                            ?>
                                            <?PHP
                                            $query ="select karyawanid, nama from hrd.karyawan where (aktif='Y' OR karyawanid='$atasaniddm') ";                                            
                                            if ($_SESSION['DIVISI']=="OTC"){
                                                $query .=" AND divisiid ='OTC' ";
                                                $query .=" AND karyawanId Not In (select distinct karyawanId from dbmaster.t_karyawanadmin) ";
                                            }else{
                                                $query .=" AND jabatanid in ('08', '39')";
                                            }
                                            $query .=" AND LEFT(nama,4) NOT IN ('NN -', 'DR -', 'DM -', 'BDG ', 'OTH.')  and LEFT(nama,7) NOT IN ('NN DM - ')  and LEFT(nama,3) NOT IN ('TO.', 'TO-', 'DR ', 'DR-') AND LEFT(nama,5) NOT IN ('NN AM', 'NN DR') ";
                                            $query .=" ORDER BY nama";
                                            
                                            $sql=mysqli_query($icnit, $query);
                                            echo "<option value=''>-- Pilihan --</option>";
                                            while ($Xt=mysqli_fetch_array($sql)){
                                                $xid=$Xt['karyawanid'];
                                                $xnama=$Xt['nama'];
                                                
                                                if ($xid==$atasaniddm)
                                                    echo "<option value='$xid' selected>$xnama</option>";
                                                else
                                                    echo "<option value='$xid'>$xnama</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div <?PHP echo $phiddensm; ?> class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>SM <span class='required'></span></label>
                                    <div class='col-xs-5'>
                                        <select class='form-control input-sm' id='e_sm' name='e_sm' onchange="ShowDataGSM()">
                                            <?PHP
                                            //PilihKaryawanAktif("", "-- Pilihan --", $atasanidsm, "Y", $_SESSION['STSADMIN'], "", "", "Y", $_SESSION['IDCARD'], "", "", "", "", "");
                                            ?>
                                            <?PHP
                                            $query ="select karyawanid, nama from hrd.karyawan where (aktif='Y' OR karyawanid='$atasanidsm')";
                                            if ($_SESSION['DIVISI']=="OTC"){
                                                $query .=" AND divisiid ='OTC' ";
                                                $query .=" AND karyawanId Not In (select distinct karyawanId from dbmaster.t_karyawanadmin) ";
                                                //$query .=" And jabatanId in (select distinct jabatanId from hrd.jabatan WHERE rank='03')";
                                            }else{
                                                $query .=" AND jabatanid in ('20')";
                                            }
                                            $query .=" AND LEFT(nama,4) NOT IN ('NN -', 'DR -', 'DM -', 'BDG ', 'OTH.')  and LEFT(nama,7) NOT IN ('NN DM - ')  and LEFT(nama,3) NOT IN ('TO.', 'TO-', 'DR ', 'DR-') AND LEFT(nama,5) NOT IN ('NN AM', 'NN DR') ";
                                            $query .=" ORDER BY nama";
                                            
                                            $sql=mysqli_query($icnit, $query);
                                            echo "<option value=''>-- Pilihan --</option>";
                                            while ($Xt=mysqli_fetch_array($sql)){
                                                $xid=$Xt['karyawanid'];
                                                $xnama=$Xt['nama'];
                                                
                                                if ($xid==$atasanidsm)
                                                    echo "<option value='$xid' selected>$xnama</option>";
                                                else
                                                    echo "<option value='$xid'>$xnama</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''><?PHP echo $lblnmgsm; ?> <span class='required'></span></label>
                                    <div class='col-xs-5'>
                                        <select class='form-control input-sm' id='e_gsm' name='e_gsm' onchange="">
                                            <?PHP
                                            //PilihKaryawanAktif("", "-- Pilihan --", $atasanidgsm, "Y", $_SESSION['STSADMIN'], "", "", "Y", $_SESSION['IDCARD'], "", "", "", "", "");
                                            ?>
                                            <?PHP
                                            $query ="select karyawanid, nama from hrd.karyawan where (aktif='Y' OR karyawanid='$atasanidgsm')";
                                            if ($_SESSION['DIVISI']=="OTC"){
                                                $query .=" AND divisiid ='OTC' ";
                                                $query .=" AND karyawanId Not In (select distinct karyawanId from dbmaster.t_karyawanadmin) ";
                                                $query .=" And jabatanId in (select distinct jabatanId from hrd.jabatan WHERE rank='02')";
                                            }else{
                                                $query .=" AND jabatanid in ('05')";
                                            }
                                            $query .=" ORDER BY nama";
                                            
                                            $sql=mysqli_query($icnit, $query);
                                            echo "<option value=''>-- Pilihan --</option>";
                                            while ($Xt=mysqli_fetch_array($sql)){
                                                $xid=$Xt['karyawanid'];
                                                $xnama=$Xt['nama'];
                                                
                                                if ($xid==$atasanidgsm)
                                                    echo "<option value='$xid' selected>$xnama</option>";
                                                else
                                                    echo "<option value='$xid'>$xnama</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                
                                
                                
                                <br/>&nbsp;
                                <!-- Save -->
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''> <span class='required'></span></label>
                                    <div class='col-xs-5'>
                                        <button type='button' class='btn btn-success' onclick='disp_confirm("Simpan ?", "<?PHP echo $act; ?>")'>Save</button>
                                        <input type='button' value='Back' onclick='self.history.back()' class='btn btn-default'>
                                    </div>
                                </div>
                                
                                
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                
            </div>
            
        </form>
        
        
    </div>
    
    
</div>


<script>
    
    function ShowDataDM() {
    
        var ispv = document.getElementById('e_spv').value;
        if (ispv=="") {
        }else{
            $.ajax({
                type:"post",
                url:"module/mst_isidatakaryawan/viewdata.php?module=viewdatadmnya",
                data:"uspv="+ispv,
                success:function(data){
                    $("#e_dm").html(data);
                    ShowDataSM();
                }
            });
        }
    }
    
    function ShowDataSM() {
        var ispv = document.getElementById('e_spv').value;
        var idm = document.getElementById('e_dm').value;
        if (idm=="" && ispv=="") {
        }else{
            $.ajax({
                type:"post",
                url:"module/mst_isidatakaryawan/viewdata.php?module=viewdatasmnya",
                data:"udm="+idm+"&uspv="+ispv,
                success:function(data){
                    $("#e_sm").html(data);
                    ShowDataGSM();
                }
            });
        }
    }
    
    function ShowDataGSM() {
        var ispv = document.getElementById('e_spv').value;
        var idm = document.getElementById('e_dm').value;
        var ism = document.getElementById('e_sm').value;
        if (ism=="" && idm=="" && ispv=="") {
        }else{
            $.ajax({
                type:"post",
                url:"module/mst_isidatakaryawan/viewdata.php?module=viewdatagsmnya",
                data:"usm="+ism+"&udm="+idm+"&uspv="+ispv,
                success:function(data){
                    $("#e_gsm").html(data);
                }
            });
        }
    }
    
    
    function disp_confirm(pText_, ket)  {
        var ejabatan =document.getElementById('e_jabatanid').value;
        var edivid =document.getElementById('e_divisiid').value;

        var ispv = document.getElementById('e_spv').value;
        var idm = document.getElementById('e_dm').value;
        var ism = document.getElementById('e_sm').value;
        var igsm = document.getElementById('e_gsm').value;
        
        
        if (edivid=="HO" || edivid=="OTC"){
            
        }else{
        
            if (ejabatan=="15") {
                if (ispv=="" && idm=="" && ism=="") {
                    alert("SPV atau DM atau SM masih kosong");
                    return false;
                }
            }

            if (ejabatan=="10" || ejabatan=="18") {
                if (idm=="" && ism=="") {
                    alert("DM atau SM masih kosong");
                    return false;
                }
            }

            if (ejabatan=="08") {
                if (ism=="") {
                    alert("SM masih kosong");
                    return false;
                }
            }

            if (ejabatan=="20" || ejabatan=="39") {
                if (igsm=="") {
                    alert("GSM masih kosong");
                    return false;
                }
            }
            
        }
        
        
        ok_ = 1;
        if (ok_) {
            var r=confirm(pText_)
            if (r==true) {
                //document.write("You pressed OK!")
                var myurl = window.location;
                var urlku = new URL(myurl);
                var module = urlku.searchParams.get("module");
                var idmenu = urlku.searchParams.get("idmenu");

                document.getElementById("demo-form2").action = "module/mst_isidatakaryawan/aksi_isidatakaryawan.php?module="+module+"&act="+ket+"&idmenu="+idmenu;
                document.getElementById("demo-form2").submit();
                return 1;
            }
        } else {
            //document.write("You pressed Cancel!")
            return 0;
        }
    }
    
</script>

<?PHP
mysqli_close($icnit);
?>