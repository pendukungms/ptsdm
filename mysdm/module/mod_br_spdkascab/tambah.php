<link href="css/inputselectbox.css" rel="stylesheet" type="text/css" />
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
    .ui-datepicker-calendar2 {
        display: none;
    }
</style>

<?PHP

$idbr="";
$hari_ini = date("Y-m-d");
$tgl1 = date('d F Y', strtotime($hari_ini));
$eperiode1 = date('01 F Y', strtotime('-2 month', strtotime($hari_ini)));
//$eperiode1 = date('01 F Y', strtotime($hari_ini));
$eperiode2 = date('t F Y', strtotime($hari_ini));

$divisi="";
$keterangan="";
$jumlah="";
$jumlah_kb="";
$pkode="2";
$psubkode="39";
$pnomor="";
$pdivnomor="";

$idcardlogin=$_SESSION['IDCARD'];
$idajukan=$_SESSION['IDCARD'];

$jmle="";
$jmlh="";
$jmlpea="";
$jmlp="";
$jmlo="";
$jmlc="";

$act="input";

if ($_GET['act']=="editdata"){
    $act="update";
    
    $edit = mysqli_query($cnmy, "SELECT * FROM dbmaster.t_suratdana_br WHERE idinput='$_GET[id]'");
    $r    = mysqli_fetch_array($edit);
    $idbr=$r['idinput'];
    $tglberlku = date('d/m/Y', strtotime($r['tgl']));
    $tgl1 = date('d F Y', strtotime($r['tgl']));
    
    $eperiode1 = date('d F Y', strtotime($r['tglf']));
    $eperiode2 = date('d F Y', strtotime($r['tglt']));

    $pkode=$r['kodeid'];
    $psubkode=$r['subkode'];
    $pnomor=$r['nomor'];
    $pdivnomor=$r['nodivisi'];
    $jumlah=$r['jumlah'];
    $jumlah_kb=$r['jumlah2'];
    $divisi=$r['divisi'];
    
    $idajukan=$r['karyawanid'];
    
    $jenis = $r['lampiran'];
    $stspilihrpt = $r['sts'];
    $pjnsrpt = $r['jenis_rpt'];
    
    if ($r['pilih']=="N") $chkpilih="checked";
    
    
    $pilihperiodetipe=$r['periodeby'];
    if (empty($pilihperiodetipe)) $pilihperiodetipe="I";
    

    $tampil = mysqli_query($cnmy, "SELECT * FROM dbmaster.t_suratdana_br_d WHERE idinput='$_GET[id]'");
    while ($t    = mysqli_fetch_array($tampil)){
        if ($t['divisi']=="EAGLE") $jmle = $t['jumlah'];
        if ($t['divisi']=="PEACO") $jmlpea = $t['jumlah'];
        if ($t['divisi']=="PIGEO") $jmlp = $t['jumlah'];
        if ($t['divisi']=="HO") $jmlh = $t['jumlah'];
        if ($t['divisi']=="OTC") $jmlo = $t['jumlah'];
        if ($t['divisi']=="CAN") $jmlc = $t['jumlah'];
    }
    
}
?>

<script> window.onload = function() { document.getElementById("e_id").focus(); } </script>


<div class="">

    <!--row-->
    <div class="row">
        
        <form method='POST' action='<?PHP echo "$aksi?module=$_GET[module]&act=input&idmenu=$_GET[idmenu]"; ?>' id='demo-form2' name='form1' data-parsley-validate class='form-horizontal form-label-left'>
            
            <input type='hidden' id='u_module' name='u_module' value='<?PHP echo $_GET['module']; ?>' Readonly>
            <input type='hidden' id='u_idmenu' name='u_idmenu' value='<?PHP echo $_GET['idmenu']; ?>' Readonly>
            
            <input type='hidden' id='u_act' name='u_act' value='<?PHP echo $act; ?>' Readonly>
            
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    
                    <div class='x_panel'>
                        <div class='x_content'>
                            <div class='col-md-12 col-sm-12 col-xs-12'>

                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>ID <span class='required'></span></label>
                                    <div class='col-md-4'>
                                        <input type='text' id='e_id' name='e_id' class='form-control col-md-7 col-xs-12' value='<?PHP echo $idbr; ?>' Readonly>
                                    </div>
                                </div>
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Kode <span class='required'></span></label>
                                    <div class='col-xs-5'>
                                          <select class='form-control input-sm' id='cb_kode' name='cb_kode' onchange="" data-live-search="true">
                                              <?PHP
                                                $query = "select distinct kodeid, nama from dbmaster.t_kode_spd where kodeid='$pkode' order by kodeid";
                                                $tampil = mysqli_query($cnmy, $query);
                                                while ($z= mysqli_fetch_array($tampil)) {
                                                    if ($z['kodeid']==$pkode)
                                                        echo "<option value='$z[kodeid]' selected>$z[nama]</option>";
                                                    else
                                                        echo "<option value='$z[kodeid]'>$z[nama]</option>";
                                                }
                                              ?>
                                          </select>
                                    </div>
                                </div>


                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Sub Kode <span class='required'></span></label>
                                    <div class='col-xs-5'>
                                          <select class='form-control input-sm' id='cb_kodesub' name='cb_kodesub' data-live-search="true" onchange="ShowNoBukti()">
                                              <?PHP
                                                $query = "select distinct kodeid, subkode, subnama from dbmaster.t_kode_spd where kodeid='$pkode' and subkode IN ('39') order by subkode";

                                                $tampil = mysqli_query($cnmy, $query);
                                                while ($z= mysqli_fetch_array($tampil)) {
                                                    if ($z['subkode']==$psubkode)
                                                        echo "<option value='$z[subkode]' selected>$z[subkode] - $z[subnama]</option>";
                                                    else
                                                        echo "<option value='$z[subkode]'>$z[subkode] - $z[subnama]</option>";
                                                }
                                              ?>
                                          </select>
                                    </div>
                                </div>
                                
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Tanggal Pengajuan Dana</label>
                                    <div class='col-md-3'>
                                        <div class='input-group date' id=''>
                                            <input type="text" class="form-control" id='e_tglberlaku' name='e_tglberlaku' autocomplete='off' required='required' placeholder='d F Y' value='<?PHP echo $tgl1; ?>'>
                                            <span class='input-group-addon'>
                                                <span class='glyphicon glyphicon-calendar'></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>No. Divisi / No. BR <span class='required'></span></label>
                                    <div class='col-xs-3'>
                                        <input type='text' id='e_nomordiv' name='e_nomordiv' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pdivnomor; ?>'>
                                    </div>
                                </div>
                                
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Pembuat <span class='required'></span></label>
                                    <div class='col-xs-5'>
                                          <select class='form-control input-sm' id='cb_karyawan' name='cb_karyawan' onchange="KosongkanDataPilKaryawan();" data-live-search="true">
                                              <?PHP 
                                                    //echo "<option value='' selected>-- Pilihan --</option>";
                                                    $query = "select karyawanId, nama From hrd.karyawan
                                                        WHERE 1=1 ";
                                                    $query .=" AND (karyawanid='$idajukan' OR karyawanid='$idcardlogin') ";
                                                    /*
                                                    $query .=" (IFNULL(tglkeluar,'0000-00-00')='0000-00-00' OR IFNULL(tglkeluar,'')='') ";
                                                    $query .=" AND LEFT(nama,4) NOT IN ('NN -', 'DR -', 'DM -', 'BDG ', 'OTH.', 'TO. ', 'BGD-', 'JKT ', 'MR -', 'MR S')  "
                                                            . " and LEFT(nama,7) NOT IN ('NN DM - ', 'MR SBY1')  "
                                                            . " and LEFT(nama,3) NOT IN ('TO.', 'TO-', 'DR ', 'DR-', 'JKT', 'NN-', 'TO ') "
                                                            . " AND LEFT(nama,5) NOT IN ('OTH -', 'NN AM', 'NN DR', 'TO - ', 'SBY -', 'RS. P') "
                                                            . " AND LEFT(nama,6) NOT IN ('SBYTO-', 'MR SBY') ";
                                                    $query .= " AND nama NOT IN ('ACCOUNTING')";
                                                    $query .= " AND karyawanid NOT IN ('0000002200', '0000002083')";
                                                    $query .= " ORDER BY nama";
                                                    
                                                    $query = "select distinct a.karyawanId, b.nama FROM "
                                                            . " dbmaster.t_kaskecilcabang a JOIN hrd.karyawan b on a.karyawanid=b.karyawanid WHERE "
                                                            . " IFNULL(a.stsnonaktif,'')<>'Y' ";
                                                     $query .= " ORDER BY b.nama";
                                                     * 
                                                     */
                                                    
                                                    $tampil = mysqli_query($cnmy, $query);
                                                    while ($z= mysqli_fetch_array($tampil)) {
                                                        $pkaryid=$z['karyawanId'];
                                                        $pkarynm=$z['nama'];
                                                        $pkryid=(INT)$pkaryid;
                                                        if ($z['karyawanId']==$idajukan)
                                                            echo "<option value='$pkaryid' selected>$pkarynm ($pkryid)</option>";
                                                        else
                                                            echo "<option value='$pkaryid'>$pkarynm ($pkryid)</option>";
                                                    }
                                                
                                              ?>
                                          </select>
                                    </div>
                                </div>
                                
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for='' style="color:blue;">Periode <span class='required'></span></label>
                                    <div class='col-md-5'>
                                        <div class='input-group date' id=''>
                                            <input type="text" class="form-control" id='e_periode1' name='e_periode1' autocomplete='off' required='required' placeholder='d F Y' value='<?PHP echo $eperiode1; ?>'>
                                            <span class='input-group-addon'>
                                                <span class='glyphicon glyphicon-calendar'></span>
                                            </span>
                                        
                                            <input type="text" class="form-control" id='e_periode2' name='e_periode2' autocomplete='off' required='required' placeholder='d F Y' value='<?PHP echo $eperiode2; ?>'>
                                            <span class='input-group-addon'>
                                                <span class='glyphicon glyphicon-calendar'></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="c_input">
                                    <div class='form-group'>
                                        <div id='loading2'></div>
                                        <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>
                                        <button type='button' class='btn btn-info btn-xs' onclick='CariData()'>Tampilkan Data</button> <span class='required'></span>
                                        </label>
                                        <div class='col-md-3'>
                                            <input type='text' id='e_jmlusulan' name='e_jmlusulan' autocomplete='off' class='form-control col-md-7 col-xs-12 inputmaskrp2' value='<?PHP echo $jumlah; ?>' Readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''> <span class='required'></span></label>
                                    <div class='col-xs-9'>
                                        <div class="checkbox">
                                            <button type='button' class='btn btn-success' onclick='disp_confirm("Simpan ?", "<?PHP echo $act; ?>")'>Save</button>
                                            <a class='btn btn-default' href="<?PHP echo "?module=$_GET[module]&idmenu=$_GET[idmenu]&act=$_GET[idmenu]"; ?>">Back</a>
                                        </div>
                                    </div>
                                </div>
                                
                                
                            </div>
                            
                            
                      
                            
                        </div>
                    </div>
                    

                </div>
            </div>
            
            
            
            <div id='loading3'></div>
            <div id="s_div">
                
                
            </div>
        
            
        </form>
        
    </div>
    <!--end row-->
</div>


<script type="text/javascript">
    $(function() {
        $('#e_tglberlaku').datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            firstDay: 1,
            dateFormat: 'dd MM yy',
            onSelect: function(dateStr) {
                ShowNoBukti();
                //document.getElementById('e_periode1').value=document.getElementById('e_tglberlaku').value;
                //document.getElementById('e_periode2').value=document.getElementById('e_tglberlaku').value;
            } 
        });
        
        $('#e_periode1').datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            firstDay: 1,
            dateFormat: 'dd MM yy',
            onSelect: function(dateStr) {
                //document.getElementById('e_periode2').value=document.getElementById('e_periode1').value;
            }
        });
        
        $('#e_periode2').datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            firstDay: 1,
            dateFormat: 'dd MM yy',
            onSelect: function(dateStr) {
                
            }
        });
    });
    
    
    function KosongkanDataPilKaryawan() {
        $("#s_div").html("");
        document.getElementById('e_jmlusulan').value="0";
    }
    function ShowNoBukti() {
        
        var ikode = document.getElementById('cb_kode').value;
        var ikodesub = document.getElementById('cb_kodesub').value;
        var itgl = document.getElementById('e_tglberlaku').value;
        
        $.ajax({
            type:"post",
            url:"module/mod_br_spdkascab/viewdata.php?module=viewnomorbukti",
            data:"ukode="+ikode+"&ukodesub="+ikodesub+"&utgl="+itgl,
            success:function(data){
                document.getElementById('e_nomordiv').value=data;
            }
        });
    }
    
    function CariData()  {
        //document.getElementById('e_jmlusulan_kb').value=0;
        var eidinput =document.getElementById('e_id').value;
        var eper1=document.getElementById('e_periode1').value;
        var eper2=document.getElementById('e_periode2').value;
        var esubkode=document.getElementById('cb_kodesub').value;
        var ekaryawanid=document.getElementById('cb_karyawan').value;
        
        var myurl = window.location;
        var urlku = new URL(myurl);
        var module = urlku.searchParams.get("module");
        var iact = urlku.searchParams.get("act");
        
        if (ekaryawanid=="") {
            alert("Pembuat harus diisi...!!!");
            return false;
        }
        
        $("#loading3").html("<center><img src='images/loading.gif' width='50px'/></center>");
        $.ajax({
            type:"post",
            url:"module/mod_br_spdkascab/datakascab.php?module=viewdatakas&ket=detail",
            data:"uper1="+eper1+"&uper2="+eper2+"&eidinput="+eidinput+"&uact="+iact+"&usubkode="+esubkode+"&ukaryawanid="+ekaryawanid,
            success:function(data){
                $("#s_div").html(data);
                $("#loading3").html("");
                //HitungTotalDariCekBox();
            }
        });
        
    }
    
    function disp_confirm(pText_,ket)  {
        var ijml =document.getElementById('e_jmlusulan').value;
        if(ijml==""){
            ijml="0";
        }
        if (ijml=="0") {
            alert("jumlah masih kosong...");
            return false;
        }
        
        var ekode =document.getElementById('cb_kode').value;
        var ekodesub =document.getElementById('cb_kodesub').value;
        var etgl1=document.getElementById('e_tglberlaku').value;

        if (ekode==""){
            alert("kode masih kosong....");
            return 0;
        }

        if (ekodesub==""){
            alert("sub kode masih kosong....");
            return 0;
        }
        
        ok_ = 1;
        if (ok_) {
            var r=confirm(pText_)
            if (r==true) {
                var myurl = window.location;
                var urlku = new URL(myurl);
                var module = urlku.searchParams.get("module");
                var idmenu = urlku.searchParams.get("idmenu");
                //document.write("You pressed OK!")
                document.getElementById("demo-form2").action = "module/mod_br_spdkascab/aksi_spdkascab.php?module="+module+"&act="+ket+"&idmenu="+idmenu;
                document.getElementById("demo-form2").submit();
                return 1;
            }
        } else {
            //document.write("You pressed Cancel!")
            return 0;
        }
    }
    
    $(document).ready(function() {
        <?PHP if ($_GET['act']=="tambahbaru"){ ?>
                ShowNoBukti();
        <?PHP } ?>
            
        <?PHP if ($_GET['act']=="editdata"){ ?>
                CariData();
        <?PHP } ?>
            
    } );
</script>