<?PHP

$pidmodule=$_GET['module'];
$pidmenu=$_GET['idmenu'];
$pidact=$_GET['act'];

$piduser=$_SESSION['USERID']; 
$pidcard=$_SESSION['IDCARD'];
$pkaryawanid=$_SESSION['IDCARD'];
$pidjbt=$_SESSION['JABATANID']; 
$pidgroup=$_SESSION['GROUP']; 
$pnamalengkap=$_SESSION['NAMALENGKAP'];

        
if ($pkaryawanid=="0000000962" OR $pkaryawanid=="0000001342"  OR $pkaryawanid=="0000002074") {
    $pidatasan = getfield("select atasanid2 as lcfields from hrd.karyawan WHERE karyawanid='$pkaryawanid'");
}else{
    $pidatasan = getfield("select atasanid as lcfields from hrd.karyawan WHERE karyawanid='$pkaryawanid'");
}
if (empty($pidatasan)) {
    $pidatasan = getfield("select atasanid as lcfields from dbmaster.t_karyawan_posisi WHERE karyawanid='$pkaryawanid'");
}
if (empty($pidatasan)) {
    $ptampil = mysqli_query($cnmy, "SELECT atasanid as atasanid FROM dbmaster.t_brrutin0 WHERE "
            . " kode=1 AND karyawanid='$pidcard' AND IFNULL(stsnonaktif,'')<>'Y' order by idrutin desc LIMIT 1 ");
    $nrow= mysqli_fetch_array($ptampil);
    $pidatasan=$nrow['atasanid'];
}

$pidinput="";


$hari_ini = date("Y-m-d");
$tgl_pertama = date('F Y', strtotime($hari_ini));
$tgl_kedua = date('F Y', strtotime('+1 month', strtotime($hari_ini)));


$pjeniscuti="01";//Tahunan
$pkeperluan="";
$ctglpilih="";

$act="input";
if ($pidact=="editdata"){
    $act="update";

    include "config/fungsi_ubahget_id.php";
    
    $pidinput_ec=$_GET['id'];
    $pidinput = decodeString($pidinput_ec);
    
    $edit = mysqli_query($cnmy, "SELECT * FROM hrd.t_cuti0 WHERE idcuti='$pidinput'");
    $jmlrw0=mysqli_num_rows($edit);
    if ((INT)$jmlrw0>0) {
        $r    = mysqli_fetch_array($edit);
        $pjeniscuti=$r['id_jenis'];
        $pkeperluan=$r['keperluan'];
        $pbln1=$r['bulan1'];
        $pbln2=$r['bulan2'];
        
        $pidatasan=$r['atasan4'];
        
        $tgl_pertama = date('F Y', strtotime($pbln1));
        $tgl_kedua = date('F Y', strtotime($pbln2));
        
        
        $query = "select distinct tanggal from hrd.t_cuti1 WHERE idcuti='$pidinput' order by tanggal";
        $tampil1=mysqli_query($cnmy, $query);
        $ketemu1=mysqli_num_rows($tampil1);
        if ((INT)$ketemu1>0) {
            while ($row1=mysqli_fetch_array($tampil1)) {
                $tgl_p=$row1['tanggal'];
                if (!empty($tgl_p)) {
                    $tgl_p = date('Y-m-d', strtotime($tgl_p));

                    $ctglpilih .="'".$tgl_p."',";
                }
            }
        }
    }



}

$ptglpilih = date('Y-m-d', strtotime($tgl_pertama));
$ptglpilih02 = date('Y-m-d', strtotime($tgl_kedua));

$query = "select nama from hrd.jabatan where jabatanId='$pidjbt'";
$ntampil=mysqli_query($cnmy, $query);
$nr=mysqli_fetch_array($ntampil);
$pnamajabatan=$nr['nama'];

$prbchkjenis1="";
$prbchkjenis2="";

if ($pjeniscuti=="02") $prbchkjenis2="checked";
else $prbchkjenis1="checked";

$pkdcoo="0000002403";
$pnamacoo="EVI KOSINA SANTOSO";

$phiddenatasan1="";
$phiddenatasan2="hidden";
?>
<div class="">

    
    <!--row-->
    <div class="row">


        <div class='col-md-12 col-sm-12 col-xs-12'>
            
            <div class='x_panel'>

                <form method='POST' action='<?PHP echo "$aksi?module=$pidmodule&act=input&idmenu=$pidmenu"; ?>' 
                        id='form_data1' name='form1' data-parsley-validate class='form-horizontal form-label-left'  enctype='multipart/form-data'>
                        
                    
                    <div class='col-md-12 col-sm-12 col-xs-12'>
                        <h2>
                            <a class='btn btn-default' href="<?PHP echo "?module=$pidmodule&idmenu=$pidmenu&act=$pidmenu"; ?>">Back</a>
                        </h2>
                        <div class='clearfix'></div>
                    </div>



                    <!--kiri-->
                    <div class='col-md-6 col-xs-12'>
                        
                        <div class='x_panel'>
                            <div class='x_content form-horizontal form-label-left'>
                                

                                <div hidden class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>ID <span class='required'></span></label>
                                    <div class='col-md-6'>
                                        <input type='text' id='e_id' name='e_id' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pidinput; ?>' Readonly>
                                        <input type='text' id='e_idcarduser' name='e_idcarduser' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pidcard; ?>' Readonly>
                                        <input type='text' id='e_idjbt' name='e_idjbt' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pidjbt; ?>' Readonly>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Nama <span class='required'></span></label>
                                    <div class='col-md-6'>
                                        <input type='text' id='e_namauser' name='e_namauser' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pnamalengkap; ?>' Readonly>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Jabatan <span class='required'></span></label>
                                    <div class='col-md-6'>
                                        <input type='text' id='e_namajbt' name='e_namajbt' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pnamajabatan; ?>' Readonly>
                                    </div>
                                </div>


                                <hr/>

                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Jenis <span class='required'></span></label>
                                    <div class='col-xs-4'>
                                        <select class='soflow' name='cb_jeniscuti' id='cb_jeniscuti' onchange="ShowPeriode()">
                                            <?php
                                            
                                            $query = "select id_jenis, nama_jenis From hrd.jenis_cuti WHERE IFNULL(aktif,'')='Y' AND IFNULL(publish,'')='Y' order by id_jenis";
                                            $tampilket= mysqli_query($cnmy, $query);
                                            while ($du= mysqli_fetch_array($tampilket)) {
                                                $nidjns=$du['id_jenis'];
                                                $nnmjns=$du['nama_jenis'];

                                                if ($nidjns==$pjeniscuti)  
                                                    echo "<option value='$nidjns' selected>$nnmjns</option>";
                                                else
                                                    echo "<option value='$nidjns'>$nnmjns</option>";

                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div hidden class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Jenis <span class='required'></span></label>
                                    <div class='col-xs-4'>
                                        <input type="radio" name="rb_jenis" id="rb_jenis1" value="01" <?PHP echo $prbchkjenis1; ?> onchange="ShowPeriode()"> Cuti Reguler <br/>
                                        <input type="radio" name="rb_jenis" id="rb_jenis2" value="02" <?PHP echo $prbchkjenis2; ?> onchange="ShowPeriode()"> Cuti Melahirkan
                                    </div>
                                </div>

                                <div id='div_akv'>
                                    
                                    <div class='form-group'>
                                        <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Keperluan <span class='required'></span></label>
                                        <div class='col-md-9'>
                                        <textarea class='form-control' id="e_keperluan" name='e_keperluan' maxlength="300"><?PHP echo $pkeperluan; ?></textarea>
                                        </div>
                                    </div>

                                </div>
                                
                                <div id="div_atasan" <?PHP echo $phiddenatasan1; ?>>
                                    
                                    <div class='form-group'>
                                        <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Atasan <span class='required'></span></label>
                                        <div class='col-xs-7'>
                                            <select class='form-control input-sm' id='e_atasan' name='e_atasan' onchange="">
                                                <?PHP
                                                    echo "<option value='' selected>-- Pilihan --</option>";
                                                    $query = "select karyawanId as karyawanid, nama as nama From hrd.karyawan WHERE karyawanid='$pidatasan'";
                                                    $query .= " ORDER BY nama";
                                                    $tampil = mysqli_query($cnmy, $query);
                                                    $ketemu= mysqli_num_rows($tampil);

                                                    if ((DOUBLE)$ketemu==0) {
                                                        $query = "select karyawanId as karyawanid, nama as nama From hrd.karyawan WHERE 1=1 ";
                                                        $query .= " AND (IFNULL(tglkeluar,'0000-00-00')='0000-00-00' OR IFNULL(tglkeluar,'')='') ";
                                                        $query .=" AND LEFT(nama,4) NOT IN ('NN -', 'DR -', 'DM -', 'BDG ', 'OTH.', 'TO. ', 'BGD-', 'JKT ', 'MR -', 'MR S')  "
                                                                . " and LEFT(nama,7) NOT IN ('NN DM - ', 'MR SBY1')  "
                                                                . " and LEFT(nama,3) NOT IN ('TO.', 'TO-', 'DR ', 'DR-', 'JKT', 'NN-', 'TO ') "
                                                                . " AND LEFT(nama,5) NOT IN ('OTH -', 'NN AM', 'NN DR', 'TO - ', 'SBY -', 'RS. P') "
                                                                . " AND LEFT(nama,6) NOT IN ('SBYTO-', 'MR SBY') ";
                                                        $query .= " AND nama NOT IN ('ACCOUNTING')";
                                                        $query .= " AND karyawanid NOT IN ('0000002200', '0000002083')";
                                                        $query .= " ORDER BY nama";
                                                        $tampil = mysqli_query($cnmy, $query);
                                                    }

                                                    while ($z= mysqli_fetch_array($tampil)) {
                                                        $pkaryid=$z['karyawanid'];
                                                        $pkarynm=$z['nama'];
                                                        $pkryid=(INT)$pkaryid;

                                                        if ($pkaryid==$pidatasan)
                                                            echo "<option value='$pkaryid' selected>$pkarynm ($pkryid)</option>";
                                                        else
                                                            echo "<option value='$pkaryid'>$pkarynm ($pkryid)</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div id="div_atasan2" <?PHP echo $phiddenatasan2; ?>>
                                    <div class='form-group'>
                                        <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>COO <span class='required'></span></label>
                                        <div class='col-xs-9'>
                                            <input type='hidden' id='e_kdcoo' name='e_kdcoo' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pkdcoo; ?>' Readonly>
                                            <input type='text' id='e_namacoo' name='e_namacoo' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pnamacoo; ?>' Readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>&nbsp; <span class='required'></span></label>
                                    <div class='col-xs-9'>
                                        *) Apabila atasan tidak sesuai, mohon untuk disesuaikan terlebih dahulu sebelum disimpan.
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <!--kanan-->
                    <div class='col-md-6 col-xs-12'>
                        
                        <div class='x_panel'>
                            <div class='x_content form-horizontal form-label-left'>
                                
                                <div id="div_periode">
                                    
                                    <div class='form-group'>
                                        <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Bulan <span class='required'></span></label>
                                        <div class='col-md-6'>
                                            <div class='input-group date' id='cbln01'>
                                                <input type="text" class="form-control" id='e_bulan01' name='e_bulan01' autocomplete='off' required='required' placeholder='F Y' value='<?PHP echo $tgl_pertama; ?>' Readonly>
                                                <span class='input-group-addon'>
                                                    <span class='glyphicon glyphicon-calendar'></span>
                                                </span>

                                            </div>
                                        </div>
                                    </div>

                                    <div class='form-group'>
                                        <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Tanggal <span class='required'></span></label>
                                        <div class='col-xs-9'>
                                            <div id="div_tgl">
                                                <?PHP
                                                    $p_tgl = date('d', strtotime($ptglpilih));
                                                    $p_akh = date('t', strtotime($ptglpilih));
                                                    //echo "$ctglpilih dan $ptglpilih";
                                                    $pchkpilih="";
                                                    if (strpos($ctglpilih, $ptglpilih)==true) $pchkpilih="checked";
                                                    echo "<input type='checkbox' name='chktgl[]' value='$ptglpilih' $pchkpilih> $p_tgl &nbsp; &nbsp; ";

                                                    $nom=2;
                                                    for ($ix=1;$ix<(INT)$p_akh;$ix++) {
                                                        $ptglpilih = date('Y-m-d', strtotime('+1 days', strtotime($ptglpilih)));

                                                        $pchkpilih="";
                                                        if (strpos($ctglpilih, $ptglpilih)==true) $pchkpilih="checked";

                                                        $p_tgl = date('d', strtotime($ptglpilih));
                                                        echo "<input type='checkbox' name='chktgl[]' value='$ptglpilih' $pchkpilih> $p_tgl &nbsp; &nbsp; ";
                                                        if ($nom>5) {echo "<br/>"; $nom=0;}
                                                        $nom++;
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    
                                    <div id="div_bulan2">
                                        
                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>s/d. Bulan <span class='required'></span></label>
                                            <div class='col-md-6'>
                                                <div class='input-group date' id='cbln02X'>
                                                    <input type="text" class="form-control" id='e_bulan02' name='e_bulan02' autocomplete='off' required='required' placeholder='F Y' value='<?PHP echo $tgl_kedua; ?>' Readonly>
                                                    <span class='input-group-addon'>
                                                        <span class='glyphicon glyphicon-calendar'></span>
                                                    </span>

                                                </div>
                                            </div>
                                        </div>


                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Tanggal <span class='required'></span></label>
                                            <div class='col-xs-9'>
                                                <div id="div_tgl2">
                                                    <?PHP
                                                        $p_tgl = date('d', strtotime($ptglpilih02));
                                                        $p_akh = date('t', strtotime($ptglpilih02));

                                                        $p_b01 = date('Ym', strtotime($ptglpilih));
                                                        $p_b02 = date('Ym', strtotime($ptglpilih02));

                                                        $pchkpilih="";
                                                        if (strpos($ctglpilih, $ptglpilih02)==true AND $p_b01<>$p_b02) $pchkpilih="checked";
                                                        echo "<input type='checkbox' name='chktgl[]' value='$ptglpilih02' $pchkpilih> $p_tgl &nbsp; &nbsp; ";

                                                        $nom=2;
                                                        for ($ix=1;$ix<(INT)$p_akh;$ix++) {
                                                            $ptglpilih02 = date('Y-m-d', strtotime('+1 days', strtotime($ptglpilih02)));

                                                            $pchkpilih="";
                                                            if (strpos($ctglpilih, $ptglpilih02)==true AND $p_b01<>$p_b02) $pchkpilih="checked";

                                                            $p_tgl = date('d', strtotime($ptglpilih02));
                                                            echo "<input type='checkbox' name='chktgl[]' value='$ptglpilih02' $pchkpilih> $p_tgl &nbsp; &nbsp; ";
                                                            if ($nom>5) {echo "<br/>"; $nom=0;}
                                                            $nom++;
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                                
                                
                            </div>
                        </div>
                    </div>

                    
                    <div class='clearfix'></div>
                    <div class='x_panel'>
                        <div class='x_content'>
                            
                            
                            <div class='col-md-12 col-sm-12 col-xs-12'>

                                <?PHP
                                if ($pidact=="editdata" ) {
                                ?>
                                    <div class='form-group'>
                                        <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>&nbsp; <span class='required'></span></label>
                                        <div class='col-md-4'>
                                            <button type='button' class='btn btn-success' onclick='disp_confirm("Simpan ?", "<?PHP echo $act; ?>")'>Update</button>
                                        </div>
                                    </div>
                                <?PHP
                                }else{
                                    echo "<div class='col-sm-5'>";
                                    include "module/marketing/mkt_formcutiho/ttd_formcutiho.php";
                                    echo "</div>";
                                }
                                ?>


                            </div>

                        </div>
                    </div>


                </form>

            </div>

        </div>


    </div>

</div>




<script>
    $(document).ready(function() {
        $('#cbln01').on('change dp.change', function(e){
            ShowTanggalPilih();
            var ijenis = document.getElementById('cb_jeniscuti').value;
            if (ijenis=="02") {
            }else{
                ShowBulan2();
            }
        });
        
        $('#cbln02').on('change dp.change', function(e){
            ShowTanggalPilih2();
        });
    });
    
    function ShowBulan2() {
        var etgl =document.getElementById('e_bulan01').value;
        
        $.ajax({
            type:"post",
            url:"module/marketing/viewdatamkt.php?module=viewdatabulan2",
            data:"utgl="+etgl,
            success:function(data){
                $("#div_bulan2").html(data);
            }
        });
    }
    
    function ShowTanggalPilih() {
        var etgl =document.getElementById('e_bulan01').value;
        
        $.ajax({
            type:"post",
            url:"module/marketing/viewdatamkt.php?module=viewdatatanggal",
            data:"utgl="+etgl,
            success:function(data){
                $("#div_tgl").html(data);
            }
        });
    }
    
    function ShowTanggalPilih2() {
        var etgl =document.getElementById('e_bulan02').value;
        
        $.ajax({
            type:"post",
            url:"module/marketing/viewdatamkt.php?module=viewdatatanggal",
            data:"utgl="+etgl,
            success:function(data){
                $("#div_tgl2").html(data);
            }
        });
    }
    
    function ShowPeriode_ex()  {
        $("#div_periode").html("");
        setTimeout(function () {
            ShowPeriode()
        }, 200);
        
    }
    
    function ShowPeriode() {
        var iid = document.getElementById('e_id').value;
        
        var ijenis = document.getElementById('cb_jeniscuti').value;
        /*
        var radios = document.getElementsByName('rb_jenis');
        var ijenis="01";
        for (var i = 0, length = radios.length; i < length; i++) {
            if (radios[i].checked) {
                ijenis=radios[i].value;
                break;
            }
        }
        */
        
        $.ajax({
            type:"post",
            url:"module/marketing/viewdatamkt.php?module=tampilperiodepilih",
            data:"uid="+iid+"&ujenis="+ijenis,
            success:function(data){
                $("#div_periode").html(data);
            }
        });
        
    }
    
</script>

<script type="text/javascript">
    $(document).ready(function() {
        var myurl = window.location;
        var urlku = new URL(myurl);
        var module = urlku.searchParams.get("module");
        var idmenu = urlku.searchParams.get("idmenu");
        var iact = urlku.searchParams.get("act");
        var ijenis = document.getElementById('cb_jeniscuti').value;
        /*
        var radios = document.getElementsByName('rb_jenis');
        var ijenis="01";
        for (var i = 0, length = radios.length; i < length; i++) {
            if (radios[i].checked) {
                ijenis=radios[i].value;
                break;
            }
        } 
        */
       if (iact=="editdata" && ijenis=="02") {
            setTimeout(function () {
                ShowPeriode()
            }, 200);
       }
    } );
                    
    function disp_confirm(pText_,ket)  {
        var iid = document.getElementById('e_id').value;
        var ikeperluan = document.getElementById('e_keperluan').value;
        
        var ijenis = document.getElementById('cb_jeniscuti').value;
        /*
        var radios = document.getElementsByName('rb_jenis');
        var ijenis="01";
        for (var i = 0, length = radios.length; i < length; i++) {
            if (radios[i].checked) {
                ijenis=radios[i].value;
                break;
            }
        } 
        */
        //alert(ijenis); return false;
            
        var ikry = document.getElementById('e_idcarduser').value;
        var ibln1 = document.getElementById('e_bulan01').value;
        var ibln2 = document.getElementById('e_bulan02').value;
        

        if (ikry=="") {
            alert("Anda harus login ulang...");
            return false;
        }
        

        var chk_arr =  document.getElementsByName("chktgl[]");
        var chklength = chk_arr.length;             
        var itglpilih="";
        for(k=0;k< chklength;k++)
        {
            if (chk_arr[k].checked == true) {
                //itglpilih =itglpilih + "'"+chk_arr[k].value+"',";
                itglpilih =itglpilih + chk_arr[k].value+",";
            }
        }
        
        if (ijenis=="02") {//melahirkan
            
        }else{
            if (ikeperluan=="") {
                alert("Keperluan harus diisi...");
                return false;
            }
            
            if (itglpilih.length > 0) {
                var lastIndex = itglpilih.lastIndexOf(",");
                //itglpilih = "("+itglpilih.substring(0, lastIndex)+")";
                itglpilih = itglpilih.substring(0, lastIndex);
            }else{
                alert("Tidak ada tanggal yang dipilih...!!!");
                return false;
            }
        }
            
        //alert(itglpilih); return false;
            
        var myurl = window.location;
        var urlku = new URL(myurl);
        var module = urlku.searchParams.get("module");
        var idmenu = urlku.searchParams.get("idmenu");
        var iact = urlku.searchParams.get("act");
        //alert(iact);
        $.ajax({
            type:"post",
            url:"module/marketing/viewdatamkt.php?module=cekdatasudahada",
            data:"uact="+iact+"&uid="+iid+"&ukry="+ikry+"&utglpilih="+itglpilih+"&ujenis="+ijenis+"&ubln1="+ibln1+"&ubln2="+ibln2,
            success:function(data){
                //var tjml = data.length;
                //alert(data);
                //return false;

                if (data=="boleh") {

                    ok_ = 1;
                    if (ok_) {
                        var r=confirm(pText_)
                        if (r==true) {
                            //document.write("You pressed OK!")
                            document.getElementById("form_data1").action = "module/marketing/mkt_formcutiho/aksi_formcutiho.php?module="+module+"&act="+ket+"&idmenu="+idmenu;
                            document.getElementById("form_data1").submit();
                            return 1;
                        }
                    } else {
                        //document.write("You pressed Cancel!")
                        return 0;
                    }

                }else{
                    alert(data);
                }
            }
        });
        
        
        
    }
</script>

<link href="css/inputselectbox.css" rel="stylesheet" type="text/css" />
<link href="css/stylenew.css" rel="stylesheet" type="text/css" />

<style>
    .form-group, .input-group, .control-label {
        margin-bottom:3px;
    }
    .control-label {
        font-size:12px;
    }
    input[type=text] {
        box-sizing: border-box;
        color:#000;
        font-size:12px;
        height: 30px;
    }
    select.soflow {
        font-size:12px;
        height: 30px;
    }
    .disabledDiv {
        pointer-events: none;
        opacity: 0.4;
    }
    .btn-primary {
        width:50px;
        height:30px;
        margin-right: 50px;
    }
    .disabledDiv {
        pointer-events: none;
        opacity: 0.4;
    }
</style>

<style>
    .divnone {
        display: none;
    }
    #dtabel th {
        font-size: 13px;
    }
    #dtabel td { 
        font-size: 11px;
    }
</style>

<style>

table {
    text-align: left;
    position: relative;
    border-collapse: collapse;
    background-color:#FFFFFF;
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