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
</style>

<script type="text/javascript">
    $(function() {
        $('#e_tglberlaku').datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            firstDay: 1,
            dateFormat: 'dd MM yy',
            onSelect: function(dateStr) {
                //ShowNoBukti();
            } 
        });
    });
</script>




<script>
    
    $(document).ready(function() {
        var myurl = window.location;
        var urlku = new URL(myurl);
        var iact = urlku.searchParams.get("act");
        if (iact=="editdata") {
            //CariDataBarang();
        }
    } );
                    
    function ShowDataCabang() {
        var edivsi =document.getElementById('cb_divisi').value;
        var esdhtmpl =document.getElementById('e_sdhtmpl').value;
        var edivawal =document.getElementById('e_divawal').value;
        
        var myurl = window.location;
        var urlku = new URL(myurl);
        var iact = urlku.searchParams.get("act");
        if (iact=="editdata") {
            //esdhtmpl="";
        }
        
        if (esdhtmpl==""){
        }else{
            //alert("data yang sudah tampil akan dikosongkan...."); return false;
            
            pText_="Sudah ada barang yang ditampilakan.\n\
Jika divisi diubah, makan barang akan dikosongkan.\n\
Apakah akan melanjutkan merubah divisi...?";
            
            var r=confirm(pText_)
            if (r==true) {
                //$("#s_div").html("");
                $(".inputdata").html("");
                document.getElementById('e_sdhtmpl').value="";
                document.getElementById('e_idbrg').value="";
                document.getElementById('e_nmbrg').value="";
                document.getElementById('e_jmlstock').value="";
                document.getElementById('e_jmlqty').value="";
            } else {
                //document.write("You pressed Cancel!")
                ShowDataDivisi();
                return 0;
            }
            
        }
        
        $.ajax({
            type:"post",
            url:"module/mod_brg_keluarbrg/viewdata.php?module=viewdatacabang",
            data:"udivsi="+edivsi,
            success:function(data){
                $("#cb_cabang").html(data);
                document.getElementById('e_divawal').value=edivsi;
                $("#cb_area").html("<option value=''>--All--</option>");
            }
        });
    }
    
    function ShowDataArea() {
        var edivsi =document.getElementById('cb_divisi').value;
        var ecabang =document.getElementById('cb_cabang').value;
        
        $.ajax({
            type:"post",
            url:"module/mod_brg_keluarbrg/viewdata.php?module=viewdataarea",
            data:"udivsi="+edivsi+"&ucabang="+ecabang,
            success:function(data){
                $("#cb_area").html(data);
            }
        });
    }
    
    
    function ShowDataDivisi() {
        var edivawal =document.getElementById('e_divawal').value;
        var edivwwn =document.getElementById('e_wwnpilihan').value;
        
        $.ajax({
            type:"post",
            url:"module/mod_brg_keluarbrg/viewdata.php?module=viewdatadivisi",
            data:"udivawal="+edivawal+"&udivwwn="+edivwwn,
            success:function(data){
                $("#cb_divisi").html(data);
            }
        });
        
    }
    
    function CariDataBarang() {
        var eidinput =document.getElementById('e_id').value;
        var edivisi =document.getElementById('cb_divisi').value;
        var ecabang=document.getElementById('cb_cabang').value;
        var etgl=document.getElementById('e_tglberlaku').value;
        
        if (edivisi=="") {
            alert("divisi masih kosong....");
            return false;
        }
        
        
        if (eidinput=="") {
            document.getElementById('e_totjml').value="";
        }
        
        var myurl = window.location;
        var urlku = new URL(myurl);
        var module = urlku.searchParams.get("module");
        var iact = urlku.searchParams.get("act");
        var idmenu = urlku.searchParams.get("idmenu");

        $("#loading3").html("<center><img src='images/loading.gif' width='50px'/></center>");
        $.ajax({
            type:"post",
            url:"module/mod_brg_keluarbrg/databarang.php?module=viewdatabarang&module="+module+"&act="+iact+"&idmenu="+idmenu,
            data:"uidinput="+eidinput+"&udivisi="+edivisi+"&ucabang="+ecabang+"&utgl="+etgl,
            success:function(data){
                document.getElementById('e_sdhtmpl').value="1";
                $("#s_div").html(data);
                $("#loading3").html("");
            }
        });
    }
    
    
    function CekDataStock(sStock, sJumlah) {
        var sjmlstock =document.getElementById(sStock).value;
        var sjml =document.getElementById(sJumlah).value;
        
        var ijmlstock = sjmlstock.replace(/\,/g,'');
        var ijml = sjml.replace(/\,/g,'');
                
        if (parseFloat(ijml) > parseFloat(ijmlstock)) {
            document.getElementById(sJumlah).value=sjmlstock;
            sjml=sjmlstock;
            //alert("Jumlah Minta Melebihi Stock...!!!");
        }
        HitungJumlahDataKeluar(sjml);
    }
    
    function HitungJumlahDataKeluar(sKeluar) {
        var sjmldata =document.getElementById('e_totjml').value;
        if (sjmldata=="") {
            sjmldata="0";
        }
        var ijmldata = sjmldata.replace(/\,/g,'');
        ijmldata=parseFloat(ijmldata)+parseFloat(sKeluar);
        document.getElementById('e_totjml').value=ijmldata;
        
    }
    
    function HitungTotalJumlah() {
        
        var sjml=0;
        var sjmlawal=0;
        var stotaljml=0;
        var chk_arr1 =  document.getElementsByName('chkbox_br[]');
        var chklength1 = chk_arr1.length; 
        
        for(k=0;k< chklength1;k++)
        {
            if (chk_arr1[k].checked == true) {
                sjml="0";
                sjmlawal="0";
                var kata = chk_arr1[k].value;
                sjml =document.getElementById('txt_njml['+kata+']').value;
                sjmlawal =document.getElementById('txt_njmstock['+kata+']').value;
                
                var ijml = sjml.replace(/\,/g,'');
                var ijmlawal = sjmlawal.replace(/\,/g,'');
                if (parseFloat(ijml) > parseFloat(ijmlawal)) {
                    document.getElementById('txt_njml['+kata+']').value=sjmlawal;
                    sjml=sjmlawal;
                }else{
                }
                var ijml = sjml.replace(/\,/g,'');
                stotaljml=parseFloat(stotaljml)+parseFloat(ijml);
                
            }
        }
        
        document.getElementById('e_totjml').value=stotaljml;
    }
    
    function disp_confirm(pText_,ket)  {
        var eidinput =document.getElementById('e_id').value;
        var edivisi =document.getElementById('cb_divisi').value;
        var ecabang=document.getElementById('cb_cabang').value;
        var etotjml=document.getElementById('e_totjml').value;
        var etgl=document.getElementById('e_tglberlaku').value;
        var earea=document.getElementById('cb_area').value;
        
        if (edivisi=="") {
            alert("divisi masih kosong....");
            return false;
        }
        
        if (ecabang=="") {
            alert("cabang masih kosong....");
            return false;
        }
        
        if (edivisi=="OT" || edivisi=="OTC" || edivisi=="CHC") {
            if (ecabang=="JKT_RETAIL" || ecabang=="JKT_MT") {
            }else{
                if (earea=="") {
                    alert("area masih kosong....");
                    //return false;
                }
            }
        }
        
        
        if (etotjml=="" || etotjml=="0") {
            alert("Jumlah minta masih kosong...");
            return false;
        }
        
        
        $.ajax({
            type:"post",
            url:"module/mod_brg_keluarbrg/viewdata.php?module=cekdataposting",
            data:"utgl="+etgl+"&udivisi="+edivisi+"&uidinput="+eidinput,
            success:function(data){
                //var tjml = data.length;
                //alert(data);
                //return false;
                
                if (data=="boleh") {
        
                    ok_ = 1;
                    if (ok_) {
                        var r=confirm(pText_)
                        if (r==true) {
                            var myurl = window.location;
                            var urlku = new URL(myurl);
                            var module = urlku.searchParams.get("module");
                            var idmenu = urlku.searchParams.get("idmenu");
                            //document.write("You pressed OK!")
                            document.getElementById("demo-form2").action = "module/mod_brg_keluarbrg/aksi_keluarbrg.php?module="+module+"&act="+ket+"&idmenu="+idmenu;
                            document.getElementById("demo-form2").submit();
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



<script>
    function getDataBarang(data1, data2, data3){
        var eidinput =document.getElementById('e_id').value;
        var edivisi =document.getElementById('cb_divisi').value;
        var ecabang=document.getElementById('cb_cabang').value;
        var etgl=document.getElementById('e_tglberlaku').value;
        
        if (edivisi=="") {
            alert("Divisi harus diisi...");
        }
        $.ajax({
            type:"post",
            url:"module/mod_brg_keluarbrg/viewdata_barang.php?module=viewdatabarang",
            data:"udata1="+data1+"&udata2="+data2+"&udata3="+data3+"&uidinput="+eidinput+
                    "&udivisi="+edivisi+"&ucabang="+ecabang+"&utgl="+etgl,
            success:function(data){
                $("#myModal").html(data);
                document.getElementById(data1).value="";
                document.getElementById(data2).value="";
                document.getElementById(data3).value="";
            }
        });
    }
    
    function getDataModalBarang(fildnya1, fildnya2, fildnya3, d1, d2, d3){
        document.getElementById(fildnya1).value=d1;
        document.getElementById(fildnya2).value=d2;
        document.getElementById(fildnya3).value=d3;
        document.getElementById("e_jmlqty").focus();
    }
</script>


<script>
    $(document).ready(function(){
        $("#add_new").click(function(){
            $(".entry-form").fadeIn("fast");
        });

        $("#close").click(function(){
            $(".entry-form").fadeOut("fast");
        });

        $("#cancel").click(function(){
            $(".entry-form").fadeOut("fast");
        });
        
        $(".add-row").click(function(){
            
            var newchar = '';
            var i_idbrg = $("#e_idbrg").val();
            var i_nmbrg = $("#e_nmbrg").val();
            var i_stock = $("#e_jmlstock").val();
            var i_jml = $("#e_jmlqty").val();
            
            if (i_jml=="") i_jml="0";
            
            if (i_jml=="0") {
                alert("Jumlah Qty masih kosong...");
                return false;
            }
            
            var arkdbrgada = document.getElementsByName('m_idbrg[]');
            for (var i = 0; i < arkdbrgada.length; i++) {
                var ikdbrg = arkdbrgada[i].value;
                if (ikdbrg==i_idbrg) {
                    return false;
                }
            }
            
            
            
            var istock=i_stock.replace(",","");
            var myistock = istock;  
            myistock = myistock.split(',').join(newchar);
            
            var ijml=i_jml.replace(",","");
            var myjml = ijml;  
            myjml = myjml.split(',').join(newchar);
            
            
            if (parseFloat(myjml)>parseFloat(myistock)) {
                alert("Jumlah melebihi stock...");
                return false;
            }
            
            var markup;
            markup = "<tr>";
            markup += "<td nowrap><input type='checkbox' name='record'></td>";
            markup += "<td nowrap class='divnone'><input type='checkbox' name='chkbox_br[]' id='chkbox_br["+i_idbrg+"]' value='"+i_idbrg+"' checked></td>";
            markup += "<td nowrap>" + i_idbrg + "<input type='hidden' id='m_idbrg["+i_idbrg+"]' name='m_idbrg[]' value='"+i_idbrg+"'></td>";
            markup += "<td nowrap>" + i_nmbrg + "<input type='hidden' id='m_nmbrg["+i_idbrg+"]' name='m_nmbrg[]' value='"+i_nmbrg+"'></td>";
            markup += "<td nowrap align='right'>" + i_stock + "<input type='hidden' id='txt_njmstock["+i_idbrg+"]' name='txt_njmstock["+i_idbrg+"]' value='"+i_stock+"'></td>";
            markup += "<td nowrap align='right'>" + i_jml + "<input type='hidden' id='txt_njml["+i_idbrg+"]' name='txt_njml["+i_idbrg+"]' value='"+i_jml+"'></td>";
            markup += "</tr>";
            $("table tbody.inputdata").append(markup);
            
            document.getElementById('e_sdhtmpl').value="1";
            document.getElementById('e_totjml').value=i_jml;
            
        });
        
        $(".delete-row").click(function(){
            
            var ilewat = false;
            $("table tbody.inputdata").find('input[name="record"]').each(function(){
                if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                    ilewat = true;
                }
            });

            if (ilewat == true) {
                
            }
            
        });
        
        
    });
</script>


<?PHP

$idbr="";
$hari_ini = date("Y-m-d");
$tgl1 = date('d F Y', strtotime($hari_ini));
$tgl2 = date('t/m/Y', strtotime($hari_ini));
$tglberlku = date('m/Y', strtotime($hari_ini));

$tgl_pertama = date('01 F Y', strtotime($hari_ini));
$tgl_terakhir = date('t F Y', strtotime($hari_ini));

$pdivisiid="";
$npdivisiid="";
$pcabangid="";
$pareaid="";
$pkaryawanid=$_SESSION['IDCARD'];
$pnotes="";
$psudahtampil="";
$ptotjml="";

$pgetact=$_GET['act'];
$act="input";
if ($_GET['act']=="editdata"){
    $act="update";
    $idbr=$_GET['id'];
    
    $query = "SELECT * FROM dbmaster.t_barang_keluar WHERE IDKELUAR='$idbr'";
    $tampil= mysqli_query($cnmy, $query);
    $row= mysqli_fetch_array($tampil);
    
    $pdivisiid=$row['DIVISIID'];
    
        $nquery="select PILIHAN from dbmaster.t_divisi_gimick where DIVISIID='$pdivisiid'";
        $ntampil= mysqli_query($cnmy, $nquery);
        $nrs= mysqli_fetch_array($ntampil);

        $npdivisiid=$nrs['PILIHAN'];
    
    
    $hari_ini=$row['TANGGAL'];
    $tgl1 = date('d F Y', strtotime($hari_ini));
    
    $pkaryawanid=$row['KARYAWANID'];
    $pcabangid=$row['ICABANGID'];
    if ($npdivisiid=="OT") $pcabangid=$row['ICABANGID_O'];
    
    $pareaid=$row['AREAID'];
    if ($npdivisiid=="OT" OR $npdivisiid=="OTC" OR $npdivisiid=="CHC") $pareaid=$row['AREAID_O'];
    
    $pnotes=$row['NOTES'];
    
    $psudahtampil="1";
    $ptotjml="1";

    
}


?>


<!-- Modal -->
<div class='modal fade' id='myModal' role='dialog'></div>

<script> window.onload = function() { document.getElementById("e_id").focus(); } </script>

<div class="">

    <!--row-->
    <div class="row">
        
        <form method='POST' action='<?PHP echo "$aksi?module=$_GET[module]&act=input&idmenu=$_GET[idmenu]"; ?>' id='demo-form2' name='form1' data-parsley-validate class='form-horizontal form-label-left' enctype='multipart/form-data'>
        
        
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    
                    <div class='x_panel'>
                        <div class='x_content'>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                
                                
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>ID <span class='required'></span></label>
                                    <div class='col-md-3'>
                                        <input type='text' id='e_id' name='e_id' class='form-control col-md-7 col-xs-12' value='<?PHP echo $idbr; ?>' Readonly>
                                    </div>
                                </div>
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Tanggal Pengajuan</label>
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
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Group Produk <span class='required'></span></label>
                                    <div class='col-xs-3'>
                                        <select class='form-control input-sm' id='cb_divisi' name='cb_divisi' data-live-search="true" onchange="ShowDataCabang()">
                                            <?PHP 
                                              if ($ppilihanwewenang=="AL") echo "<option value='' selected>--Pilihan--</option>";
                                              $query = "select distinct DIVISIID, DIVISINM from dbmaster.t_divisi_gimick WHERE IFNULL(STSAKTIF,'')='Y' ";//AND IFNULL(STS,'')='M'
                                              if ($ppilihanwewenang=="AL") {
                                              }else{
                                                  $query .=" AND PILIHAN='$ppilihanwewenang' ";
                                              }
                                              if ($pgetact=="editdata") $query .=" AND DIVISIID='$pdivisiid' ";
                                              $query .=" ORDER BY DIVISINM";
                                              $tampil= mysqli_query($cnmy, $query);
                                              while ($row= mysqli_fetch_array($tampil)) {
                                                  $npdivid=$row['DIVISIID'];
                                                  $npdivnm=$row['DIVISINM'];
                                                  if ($npdivnm=="OTC") $npdivnm="CHC";

                                                  if ($npdivid==$pdivisiid)
                                                        echo "<option value='$npdivid' selected>$npdivnm</option>";
                                                  else
                                                      echo "<option value='$npdivid'>$npdivnm</option>";
                                              }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Cabang <span class='required'></span></label>
                                    <div class='col-xs-3'>
                                        
                                            <select class='form-control input-sm' id='cb_cabang' name='cb_cabang' onchange="ShowDataArea()" data-live-search="true">
                                              <option value="" selected>--Pilihan--</option>
                                                <?PHP
                                                if ($pgetact=="editdata") {
                                                    if ($npdivisiid=="OT" OR $npdivisiid=="OTC" OR $npdivisiid=="CHC") {
                                                        $query = "select icabangid_o as icabangid, nama as nama from dbmaster.v_icabang_o WHERE aktif='Y' ";
                                                    }else{
                                                        $query = "select icabangid as icabangid, nama as nama from MKT.icabang WHERE aktif='Y' ";
                                                    }
                                                }else{
                                                    if ($ppilihanwewenang!="AL") {
                                                        if ($ppilihanwewenang=="OT" OR $ppilihanwewenang=="OTC" OR $ppilihanwewenang=="CHC") {
                                                            $query = "select icabangid_o as icabangid, nama as nama from dbmaster.v_icabang_o WHERE aktif='Y' ";
                                                        }else{
                                                          $query = "select icabangid as icabangid, nama as nama from MKT.icabang WHERE aktif='Y' ";
                                                        }
                                                    }
                                                }
                                                
                                                
                                                if (!empty($query)) {
                                                    $query .=" ORDER BY nama";
                                                    $tampil= mysqli_query($cnmy, $query);
                                                    while ($row= mysqli_fetch_array($tampil)) {
                                                        $npidcab=$row['icabangid'];
                                                        $npnmcab=$row['nama'];

                                                        if ($npidcab==$pcabangid)
                                                              echo "<option value='$npidcab' selected>$npnmcab</option>";
                                                        else
                                                            echo "<option value='$npidcab'>$npnmcab</option>";
                                                    }
                                                }
                                                ?>
                                          </select>
                                    </div>
                                </div>
                                
                                
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Area <span class='required'></span></label>
                                    <div class='col-xs-3'>
                                        
                                          <select class='form-control input-sm' id='cb_area' name='cb_area' data-live-search="true">
                                              <option value="" selected>--Pilihan--</option>
                                                <?PHP
                                                if ($pgetact=="editdata") {
                                                    if ($npdivisiid=="OT" OR $npdivisiid=="OTC" OR $npdivisiid=="CHC") {
                                                        $query = "select icabangid_o as icabangid, areaid_o as areaid, Nama as nama from MKT.iarea_o WHERE icabangid_o='$pcabangid' AND (aktif='Y' OR areaid_o='$pareaid') ";
                                                    }else{
                                                        $query = "select icabangid as icabangid, areaid as areaid, nama as nama from MKT.iarea WHERE icabangid='$pcabangid' AND (aktif='Y' OR areaid='$pareaid') ";
                                                    }
                                                    
                                                    $query .=" ORDER BY nama";
                                                    $tampil= mysqli_query($cnmy, $query);
                                                    while ($row= mysqli_fetch_array($tampil)) {
                                                        $npidarea=$row['areaid'];
                                                        $npnmarea=$row['nama'];

                                                        if ($npidarea==$pareaid)
                                                              echo "<option value='$npidarea' selected>$npnmarea</option>";
                                                        else
                                                            echo "<option value='$npidarea'>$npnmarea</option>";
                                                    }
                                                }
                                                
                                                
                                                ?>
                                          </select>
                                    </div>
                                </div>
                                
                                
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Yg. Mengajukan <span class='required'></span></label>
                                    <div class='col-xs-3'>
                                        <select class='form-control input-sm' id='cb_karyawan' name='cb_karyawan' data-live-search="true">
                                            <?PHP 
                                            //$pkaryawanid
                                            $query = "select karyawanid, nama from hrd.karyawan WHERE aktif='Y' and (tglkeluar='0000-00-00' OR IFNULL(tglkeluar,'')='') ";
                                            $query .=" AND LEFT(nama,4) NOT IN ('NN -', 'DR -', 'DM -', 'BDG ', 'OTH.')  and LEFT(nama,7) NOT IN ('NN DM - ')  and LEFT(nama,3) NOT IN ('TO.', 'TO-', 'DR ', 'DR-') AND LEFT(nama,5) NOT IN ('NN AM', 'NN DR') ";
                                            $query .=" AND karyawanId not in (select distinct IFNULL(karyawanId,'') from dbmaster.t_karyawanadmin) ";
                                            $query .=" AND karyawanId not in ('0000002200', '0000002083')";
                                            $query .=" AND karyawanid not in (select distinct IFNULL(karyawanid,'') from dbmaster.t_karyawanadmin) ";
                                            $query .=" ORDER BY nama, karyawanid";
                                            $tampil= mysqli_query($cnmy, $query);
                                            while ($row= mysqli_fetch_array($tampil)) {
                                                $npidkry=$row['karyawanid'];
                                                $npnmkry=$row['nama'];

                                                if ($npidkry==$pkaryawanid)
                                                      echo "<option value='$npidkry' selected>$npnmkry</option>";
                                                else
                                                    echo "<option value='$npidkry'>$npnmkry</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Notes <span class='required'></span></label>
                                    <div class='col-xs-5'>
                                        <input type='text' id='e_notes' name='e_notes' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pnotes; ?>' >
                                    </div>
                                </div>
                                
                                
                                <div hidden class='form-group'>
                                    <div id='loading2'></div>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>
                                        
                                        <div id="div_sdh_tmpil">
                                            &nbsp;
                                        </div>
                                        
                                        <div id="div_sdhtampil">
                                            <button type='button' class='btn btn-info btn-xs' onclick='CariDataBarang()'>Tampilkan Data</button> <span class='required'></span>
                                        </div>
                                        
                                        
                                    </label>
                                    <div class='col-md-3'>
                                        <input type='hidden' id='e_sdhtmpl' name='e_sdhtmpl' class='form-control col-md-7 col-xs-12' value='<?PHP echo $psudahtampil; ?>' Readonly>
                                        <input type='hidden' id='e_divawal' name='e_divawal' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pdivisiid; ?>' Readonly>
                                        <input type='hidden' id='e_totjml' name='e_totjml' class='form-control col-md-7 col-xs-12 inputmaskrp2' value='<?PHP echo $ptotjml; ?>' Readonly>
                                    </div>
                                </div>
                                
                                
                            </div>
                            
                        </div>
                    </div>



                    <div class='col-md-6 col-xs-12'>
                        <div class='x_panel'>
                            <div class='x_content form-horizontal form-label-left'>
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Barang <span class='required'></span></label>
                                    <div class='col-xs-6'>
                                        <div class='input-group '>
                                        <span class='input-group-btn'>
                                            <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#myModal' onClick="getDataBarang('e_idbrg', 'e_nmbrg', 'e_jmlstock')">Pilih!</button>
                                        </span>
                                        <input type='text' class='form-control' id='e_idbrg' name='e_idbrg' value='<?PHP //echo $pbrnoid; ?>' Readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>&nbsp; <span class='required'></span></label>
                                    <div class='col-xs-9'>
                                        <input type='text' id='e_nmbrg' name='e_nmbrg' class='form-control col-md-7 col-xs-12' value='<?PHP //echo $pketerangan; ?>' Readonly>
                                    </div>
                                </div>
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Stock <span class='required'></span></label>
                                    <div class='col-xs-6'>
                                        <input type='text' id='e_jmlstock' name='e_jmlstock' class='form-control col-md-7 col-xs-12 inputmaskrp2' value='<?PHP //echo $pketerangan; ?>' readonly>
                                    </div>
                                </div>
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Jumlah <span class='required'></span></label>
                                    <div class='col-xs-6'>
                                        <input type='text' id='e_jmlqty' name='e_jmlqty' class='form-control col-md-7 col-xs-12 inputmaskrp2' onblur="CekDataStock('e_jmlstock', 'e_jmlqty')" value='<?PHP //echo $pketerangan; ?>' >
                                    </div>
                                </div>
                                
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>&nbsp; <span class='required'></span></label>
                                    <div class='col-xs-9'>
                                        <button type='button' class='btn btn-dark btn-xs add-row' onclick='TambahDataBarang("")'>&nbsp; &nbsp; &nbsp; Tambah &nbsp; &nbsp; &nbsp;</button>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    
                </div>
                
                
            </div>
            
            <div id='loading3'></div>
            <div id="s_div">
                
                <div class='x_content'>
                    <table id='datatablestockopn' class='table table-striped table-bordered' width='100%'>
                        <thead>
                            <tr>
                                <th width='5px' nowrap></th>
                                <th width='10px' align='center' class='divnone'></th><!--class='divnone' -->
                                <th width='20px' align='center'>Kode</th>
                                <th width='200px' align='center'>Nama Barang</th>
                                <th width='20px' align='center'>Stock</th>
                                <th width='40px' align='center'>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class='inputdata'>
                            <?PHP
                            if ($pgetact=="editdata") {
                                $query = "select a.IDKELUAR, a.IDBARANG, b.NAMABARANG, a.STOCK, a.JUMLAH "
                                        . " from dbmaster.t_barang_keluar_d a JOIN dbmaster.t_barang b "
                                        . " on a.IDBARANG=b.IDBARANG WHERE a.IDKELUAR='$idbr'";
                                $tampild=mysqli_query($cnmy, $query);
                                while ($nrd= mysqli_fetch_array($tampild)) {
                                    $pidbrg=$nrd['IDBARANG'];
                                    $pnmbrg=$nrd['NAMABARANG'];
                                    $pjmlstock=$nrd['STOCK'];
                                    $pjmlqty=$nrd['JUMLAH'];
                                    
                                    echo "<tr>";
                                    echo "<td nowrap><input type='checkbox' name='record'></td>";
                                    echo "<td nowrap class='divnone'><input type='checkbox' name='chkbox_br[]' id='chkbox_br[$pidbrg]' value='$pidbrg' checked></td>";
                                    echo "<td nowrap>$pidbrg<input type='hidden' id='m_idbrg[$pidbrg]' name='m_idbrg[]' value='$pidbrg'></td>";
                                    echo "<td nowrap>$pnmbrg<input type='hidden' id='m_nmbrg[$pidbrg]' name='m_nmbrg[]' value='$pnmbrg'></td>";
                                    echo "<td nowrap align='right'>$pjmlstock<input type='hidden' id='txt_njmstock[$pidbrg]' name='txt_njmstock[$pidbrg]' value='$pjmlstock'></td>";
                                    echo "<td nowrap align='right'>$pjmlqty<input type='hidden' id='txt_njml[$pidbrg]' name='txt_njml[$pidbrg]' value='$pjmlqty'></td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <button type='button' class='btn btn-danger btn-xs delete-row' >&nbsp; &nbsp; Hapus &nbsp; &nbsp;</button>
                </div>
                
            </div>
            
            
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    

                            
                                
                            
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''> <span class='required'></span></label>
                                    <div class='col-xs-9'>
                                        <div class="checkbox">
                                            <button type='button' class='btn btn-success' onclick='disp_confirm("Simpan ?", "<?PHP echo $act; ?>")'>Save</button>
                                            <input type='button' value='Back' onclick='self.history.back()' class='btn btn-default'>
                                        </div>
                                    </div>
                                </div>
                            


                </div>
            </div>
            
        </form>
        
    </div>
    
    
</div>


<style>
    .divnone {
        display: none;
    }
    #datatablestockopn th {
        font-size: 13px;
    }
    #datatablestockopn td { 
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