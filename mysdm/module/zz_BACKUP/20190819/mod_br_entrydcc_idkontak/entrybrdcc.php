<?PHP
    $hari_ini = date("Y-m-d");
    $tgl_pertama = date('01 F Y', strtotime($hari_ini));
    $tgl_akhir = date('d F Y', strtotime($hari_ini));
    
    if (!empty($_SESSION['FINDDPERENTY1'])) $tgl_pertama = $_SESSION['FINDDPERENTY1'];
    if (!empty($_SESSION['FINDDPERENTY2'])) $tgl_akhir = $_SESSION['FINDDPERENTY2'];
    
    $fkaryawan=$_SESSION['IDCARD'];
    $fstsadmin=$_SESSION['STSADMIN'];
    $flvlposisi=$_SESSION['LVLPOSISI'];
    $fdivisi=$_SESSION['DIVISI'];
?>

<div class="">

    <div class="page-title"><div class="title_left"><h3>Entry Budget  Request DCC/DSS</h3></div></div><div class="clearfix"></div>
    <!--row-->
    <div class="row">

        <?php
        
        $aksi="module/mod_br_entrydcc/aksi_entrybrdcc.php";
        switch($_GET['act']){
            default:
                ?>
        
                
                <script type="text/javascript" language="javascript" >

                    function RefreshDataTabel() {
                        KlikDataTabel();
                    }

                    $(document).ready(function() {
                        KlikDataTabel();
                    } );

                    function KlikDataTabel() {
                        $("#c-data").html("");
                        $("#loading").html("");
                        var etipeproses=document.getElementById('cb_tipeisi').value;
                        if (etipeproses=="") {
                            PilihData1();
                        }else if (etipeproses=="A") {
                            PilihData2();
                        }else if (etipeproses=="B") {
                            PilihData3();
                        }
                    }

                    function PilihData1() {
                        var ket="";
                        var etipeproses=document.getElementById('cb_tipeisi').value;
                        var etgltipe=document.getElementById('cb_tgltipe').value;
                        var etgl1=document.getElementById('tgl1').value;
                        var etgl2=document.getElementById('tgl2').value;
                        var edivisi=document.getElementById('cb_divisi').value;
                        var eidc=<?PHP echo $_SESSION['USERID']; ?> ;
                        
                        $("#loading").html("<center><img src='images/loading.gif' width='50px'/></center>");
                        $.ajax({
                            type:"post",
                            url:"module/mod_br_entrydcc/viewdatatabel.php?module="+ket,
                            data:"eket="+ket+"&utgltipe="+etgltipe+"&uperiode1="+etgl1+"&uperiode2="+etgl2+"&udivisi="+edivisi+"&uidc="+eidc+"&utipeproses="+etipeproses,
                            success:function(data){
                                $("#c-data").html(data);
                                $("#loading").html("");
                            }
                        });
                    }

                    function PilihData2() {
                        var etipeproses=document.getElementById('cb_tipeisi').value;
                        var etgltipe=document.getElementById('cb_tgltipe').value;
                        var etgl1=document.getElementById('tgl1').value;
                        var etgl2=document.getElementById('tgl2').value;
                        var edivisi=document.getElementById('cb_divisi').value;
                        
                        var myurl = window.location;
                        var urlku = new URL(myurl);
                        var module = urlku.searchParams.get("module");
                        var act = urlku.searchParams.get("act");
                        var idmenu = urlku.searchParams.get("idmenu");
                        
                        $("#loading").html("<center><img src='images/loading.gif' width='50px'/></center>");
                        $.ajax({
                            type:"post",
                            url:"module/mod_br_entrydcc/viewdatatabelreal.php?module="+module+"&act="+act+"&idmenu="+idmenu+"&nmun="+idmenu,
                            data:"utgltipe="+etgltipe+"&uperiode1="+etgl1+"&uperiode2="+etgl2+"&udivisi="+edivisi+"&utipeproses="+etipeproses,
                            success:function(data){
                                $("#c-data").html(data);
                                $("#loading").html("");
                            }
                        });
                    }

                    function PilihData3() {
                        var etipeproses=document.getElementById('cb_tipeisi').value;
                        var etgltipe=document.getElementById('cb_tgltipe').value;
                        var etgl1=document.getElementById('tgl1').value;
                        var etgl2=document.getElementById('tgl2').value;
                        var edivisi=document.getElementById('cb_divisi').value;
                        
                        var myurl = window.location;
                        var urlku = new URL(myurl);
                        var module = urlku.searchParams.get("module");
                        var act = urlku.searchParams.get("act");
                        var idmenu = urlku.searchParams.get("idmenu");
                        
                        $("#loading").html("<center><img src='images/loading.gif' width='50px'/></center>");
                        $.ajax({
                            type:"post",
                            url:"module/mod_br_entrydcc/viewdatatabelsby.php?module="+module+"&act="+act+"&idmenu="+idmenu+"&nmun="+idmenu,
                            data:"utgltipe="+etgltipe+"&uperiode1="+etgl1+"&uperiode2="+etgl2+"&udivisi="+edivisi+"&utipeproses="+etipeproses,
                            success:function(data){
                                $("#c-data").html(data);
                                $("#loading").html("");
                            }
                        });
                    }

                </script>

                
                <div class='col-md-12 col-sm-12 col-xs-12'>
                    <div class='x_panel'>
                        
                        <div class='x_title'>
                            <h2><input class='btn btn-default' type=button value='Tambah Baru'
                                onclick="window.location.href='<?PHP echo "?module=$_GET[module]&idmenu=$_GET[idmenu]&act=tambahbaru"; ?>';">
                                <small></small>
                            </h2>
                            <div class='clearfix'></div>
                        </div>
                        

                        <div class='col-sm-2'>
                            Type Proses
                            <div class="form-group">
                                <select class='form-control input-sm' id="cb_tipeisi" name="cb_tipeisi">
                                    <option value="" selected>Input BR</option>
                                    <?PHP
                                    $sa=""; $sb=""; $sc="";
                                    if ($_SESSION['FINDDTIPE']=="A") $sa=" selected";
                                    if ($_SESSION['FINDDTIPE']=="B") $sb=" selected";
                                    if ($_SESSION['FINDDTIPE']=="C") $sc=" selected";
                                    ?>
                                    <option value="A" <?PHP echo $sa; ?>>Realisasi</option>
                                    <option value="B" <?PHP echo $sb; ?>>Input Tgl Rpt. SBY</option>
                                </select>
                            </div>
                        </div>

                        
                        <div class='col-sm-2'>
                            Periode By
                            <div class="form-group">
                                <select class='form-control input-sm' id="cb_tgltipe" name="cb_tgltipe">
                                    <?PHP
                                    $sa=""; $sb=""; $sc=""; $sd=""; $se="";
                                    if ($_SESSION['FINDDTGLTIPE']=="1") $sa=" selected";
                                    if ($_SESSION['FINDDTGLTIPE']=="2") $sb=" selected";
                                    if ($_SESSION['FINDDTGLTIPE']=="3") $sc=" selected";
                                    if ($_SESSION['FINDDTGLTIPE']=="4") $sd=" selected";
                                    if ($_SESSION['FINDDTGLTIPE']=="5") $se=" selected";
                                    if (empty($_SESSION['FINDDTGLTIPE'])) $sd="selected"
                                    ?>
                                    <option value="1" <?PHP echo $sa; ?>>Last Input / Update</option>
                                    <option value="2" <?PHP echo $sb; ?>>Tanggal Transfer</option>
                                    <option value="3" <?PHP echo $sc; ?>>Tanggal Terima</option>
                                    <option value="4" <?PHP echo $sd; ?>>Tanggal Input</option>
                                    <option value="5" <?PHP echo $se; ?>>Tanggal Rpt. SBY</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class='col-sm-2'>
                            Periode
                            <div class="form-group">
                                <div class='input-group date' id='tgl01'>
                                    <input type='text' id='tgl1' name='e_periode01' required='required' class='form-control input-sm' placeholder='tgl awal' value='<?PHP echo $tgl_pertama; ?>' placeholder='dd mmm yyyy' Readonly>
                                    <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class='col-sm-2'>
                           <small>s/d.</small>
                           <div class="form-group">
                               <div class='input-group date' id='tgl02'>
                                   <input type='text' id='tgl2' name='e_periode02' required='required' class='form-control input-sm' placeholder='tgl akhir' value='<?PHP echo $tgl_akhir; ?>' placeholder='dd mmm yyyy' Readonly>
                                   <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                   </span>
                               </div>
                           </div>
                       </div>
                        

                        <div class='col-sm-2'>
                            Divisi
                            <div class="form-group">
                                <?PHP
                                    ComboSelectIsiDivisiProdFilter("", "",
                                            "", "$fstsadmin", "$flvlposisi", "$fdivisi", "$_SESSION[FINDDDIV]");
                                ?>
                            </div>
                        </div>
                        
                        <div class='col-sm-2'>
                            <small>&nbsp;</small>
                           <div class="form-group">
                               <input type='button' class='btn btn-success  btn-xs' id="s-submit" value="Refresh" onclick="RefreshDataTabel()">
                           </div>
                       </div>
                       
                        
                        
                        
                        <div id='loading'></div>
                        <div id='c-data'>
                           
                        </div>
                       
                    </div>
                </div>
                
                <?PHP

            break;

            case "tambahbaru":
                include "tambah.php";
            break;

            case "editdata":
                include "edit.php";
            break;

            case "editterima":
                include "terima.php";
            break;

            case "edittransfer":
                include "transfer.php";
            break;

        }
        ?>

    </div>
    <!--end row-->
</div>

