<div class="">

    <div class="col-md-12 col-sm-12 col-xs-12"><div class="title_left"><h3>Laporan Klaim Discount</h3></div></div><div class="clearfix"></div>
    <!--row-->
    <div class="row">
        <?php
        
        $fkaryawan=$_SESSION['IDCARD'];
        $pmyjabatanid=$_SESSION['JABATANID'];
        $pmygroupid=$_SESSION['GROUP'];
        $pigroup=$_SESSION['GROUP'];
        $pidcabangpil="";
        
    
        $aksi="eksekusi3.php";
        switch($_GET['act']){
            default:

                $hari_ini = date("Y-m-d");
                $tgl_pertama = date('01 F Y', strtotime($hari_ini));
                $tgl_akhir = date('d F Y', strtotime($hari_ini));
                ?>
                <script>
                    function disp_confirm(pText)  {
                        if (pText == "excel") {
                            document.getElementById("form1").action = "<?PHP echo "$aksi?module=$_GET[module]&act=input&idmenu=$_GET[idmenu]&ket=excel"; ?>";
                            document.getElementById("form1").submit();
                            return 1;
                        }else{
                            document.getElementById("form1").action = "<?PHP echo "$aksi?module=$_GET[module]&act=input&idmenu=$_GET[idmenu]&ket=bukan"; ?>";
                            document.getElementById("form1").submit();
                            return 1;
                        }
                    }
                </script>
                
                <style>
                    .grp-periode, .input-periode, .control-periode {
                        margin-bottom:2px;
                    }
                </style>
                
                
                <div class='col-md-12 col-sm-12 col-xs-12'>
                    <div class='x_panel'>
                        <form name='form1' id='form1' method='POST' action="<?PHP echo "$aksi?module=$_GET[module]&act=input&idmenu=$_GET[idmenu]"; ?>" enctype='multipart/form-data' target="_blank">
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <h2>
                                    <button type='button' class='btn btn-success' onclick="disp_confirm('')">Preview</button>
                                    <button type='button' class='btn btn-danger' onclick="disp_confirm('excel')">Excel</button>
                                    <a class='btn btn-default' href="<?PHP echo "?module=home"; ?>">Home</a>
                                </h2>
                                <div class='clearfix'></div>
                            </div>

                            <!--kiri-->
                            <div class='col-md-6 col-xs-12'>
                                <div class='x_panel'>
                                    <div class='x_content form-horizontal form-label-left'><br />
                                        
                                        
                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for='e_dokter'>Periode By <span class='required'></span></label>
                                            <div class='col-xs-9'>
                                                <select class='form-control input-sm' id="cb_tgltipe" name="cb_tgltipe">
                                                    <option value="1">Tanggal Transfer</option>
                                                    <option value="2" selected>Tanggal Input/Pengajuan</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class='form-group grp-periode'>
                                            <label class='control-label control-periode col-md-3 col-sm-3 col-xs-12' for='tgl01'>Periode <span class='required'></span></label>
                                            <div class='col-md-6'>
                                                <div class="form-group grp-periode">
                                                    <div class='input-group date grp-periode' id='tgl01'>
                                                        <input type='text' id='tgl01' name='e_periode01' required='required' class='form-control' placeholder='tgl awal' value='<?PHP echo $tgl_pertama; ?>' placeholder='dd mmm yyyy' Readonly>
                                                        <span class="input-group-addon">
                                                           <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                    <div class='input-group date' id='tgl02'>
                                                        <input type='text' id='tgl02' name='e_periode02' required='required' class='form-control' placeholder='tgl akhir' value='<?PHP echo $tgl_akhir; ?>' placeholder='dd mmm yyyy' Readonly>
                                                        <span class="input-group-addon">
                                                           <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12'>Distributor &nbsp;<input type="checkbox" id="chkbtndist" value="deselect" onClick="SelAllCheckBox('chkbtndist', 'chkbox_dist[]')" checked/><span class='required'></span></label>
                                            <div class='col-md-9 col-sm-9 col-xs-12'>
                                                <div id="kotak-multi5" class="jarak">
                                                <?PHP
                                                    //cBoxIsiDistributor("");
                                                if ($pigroup=="40") {
                                                    $pinsel="('0000000002', '0000000003', '0000000005', '0000000010', '0000000011', "
                                                            . "'0000000012', '0000000015', '0000000024', '0000000025', '0000000029', '0000000031')";
                                                }else{
                                                    $pinsel="('0000000002', '0000000003', '0000000005', '0000000010', '0000000011', "
                                                            . " '0000000015', '0000000017', '0000000029', '0000000031')";
                                                }
                                                $sql=mysqli_query($cnmy, "SELECT distinct Distid, nama, alamat1 from dbmaster.distrib0 WHERE"
                                                        . " Distid IN $pinsel order by Distid, nama");
                                                while ($Xt=mysqli_fetch_array($sql)){
                                                    $pdisid=$Xt['Distid'];
                                                    $pdisnm=$Xt['nama'];
                                                    $cidcek=(INT)$pdisid;
                                                    echo "<input type=checkbox value='$pdisid' name=chkbox_dist[] checked> $cidcek - $pdisnm<br/>";
                                                }
                                                
                                                $sql=mysqli_query($cnmy, "SELECT distinct Distid, nama, alamat1 from dbmaster.distrib0 WHERE"
                                                        . " Distid NOT IN $pinsel order by Distid, nama");
                                                echo "<br/>&nbsp;";
                                                while ($Xt=mysqli_fetch_array($sql)){
                                                    $pdisid=$Xt['Distid'];
                                                    $pdisnm=$Xt['nama'];
                                                    $cidcek=(INT)$pdisid;
                                                    
                                                    echo "<input type=checkbox value='$pdisid' name=chkbox_dist[] checked> $cidcek - $pdisnm<br/>";
                                                }
                                                    
                                                ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Report By <span class='required'></span></label>
                                            <div class='col-xs-9'>
                                                <select class='form-control' id="cb_reportby" name="cb_reportby">
                                                    <option value="D" selected>Detail</option>
                                                    <option value="S">Summary Permintaan Dana</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <!--end kiri-->
                            
                            

                        </form>
                    </div><!--end xpanel-->
                </div>
                
                
                <script>
                    function SelAllCheckBox(nmbuton, data){
                        var checkboxes = document.getElementsByName(data);
                        var button = document.getElementById(nmbuton);

                        if(button.value == 'select'){
                            for (var i in checkboxes){
                                checkboxes[i].checked = 'FALSE';
                            }
                            button.value = 'deselect'
                        }else{
                            for (var i in checkboxes){
                                checkboxes[i].checked = '';
                            }
                            button.value = 'select';
                        }

                        if (nmbuton=="chkbtndivprod"){
                            var mycek="";
                            for (var i in checkboxes){
                                if (checkboxes[i].checked) {
                                    mycek=mycek+"'"+checkboxes[i].value+"',";
                                }
                            }
                            if (mycek==""){
                                $("#kotak-multi3").html("");
                                return 0;
                            }
                            $.ajax({
                                type:"post",
                                url:"module/lap_br_klaim/viewdata.php?module=viewkodedivisi&data1="+mycek,
                                data:"udata1="+mycek,
                                success:function(data){
                                    $("#kotak-multi3").html(data);
                                }
                            });

                        }

                    }
                </script>

                
                <?PHP
            break;

            case "tambahbaru":

            break;
        }
        ?>
    </div>
    <!--end row-->
</div>