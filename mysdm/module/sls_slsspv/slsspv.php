<div class="">

    <div class="col-md-12 col-sm-12 col-xs-12"><div class="title_left"><h3>Report Sales Per Supervisor</h3></div></div><div class="clearfix"></div>
    <!--row-->
    <div class="row">
        <?php
        $aksi="eksekusi3.php";
        switch($_GET['act']){
            default:
                
                $pbukadarihp=$_SESSION['MOBILE'];
                $ptargetblank=" target=\"_blank\" ";
                if ($pbukadarihp=="Y") $ptargetblank="";

                $hari_ini = date("Y-m-d");
                $tgl_pertama=date('F Y', strtotime('-1 month', strtotime($hari_ini)));
                //$tgl_pertama = date('F Y', strtotime($hari_ini));
                ?>
                <div class='col-md-12 col-sm-12 col-xs-12'>
                    <div class='x_panel'>
                        <form name='form1' id='form1' method='POST' action="<?PHP echo "$aksi?module=$_GET[module]&act=input&idmenu=$_GET[idmenu]"; ?>" enctype='multipart/form-data' <?PHP echo $ptargetblank; ?> >
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <h2>
                                    <button type='submit' class='btn btn-success'>Preview</button>
                                </h2>
                                <div class='clearfix'></div>
                            </div>

                            <!--kiri-->
                            <div class='col-md-6 col-xs-12'>
                                <div class='x_panel'>
                                    <div class='x_content form-horizontal form-label-left'><br />
                                        
                                        
                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for='cbln01'>Periode <span class='required'></span></label>
                                            <div class='col-md-6'>
                                                <div class="form-group">
                                                    <div class='input-group date' id='cbln01'>
                                                        <input type='text' id='cbln01' name='bulan' required='required' class='form-control' placeholder='tgl awal' value='<?PHP echo $tgl_pertama; ?>' placeholder='dd mmm yyyy' Readonly>
                                                        <span class="input-group-addon">
                                                           <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12'>SPV / AM <span class='required'></span></label>
                                            <div class='col-md-9 col-sm-9 col-xs-12'>

                                                <select class='form-control' name='spv' id='spv'>
                                                    <?PHP
                                                    include "config/koneksimysqli_it.php";
                                                    $query = "select distinct karyawanId, nama from hrd.karyawan where jabatanId in (18, 10) order by nama";
                                                    $tampil = mysqli_query($cnit, $query);
                                                    echo "<option value=''>--Pilih--</option>";
                                                    while ($ir=  mysqli_fetch_array($tampil)) {
                                                        echo "<option value='$ir[karyawanId]'>$ir[nama]</option>";
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                        </div>
                                        
                                        <div class='form-group'>
                                        <label class='control-label col-md-3 col-sm-3 col-xs-12' for='e_ket'>Report By <span class='required'></span></label>
                                        <div class='col-md-6 col-sm-6 col-xs-12'>
                                                <div class='btn-group' data-toggle='buttons'>
                                                    <label class='btn btn-default'><input type='radio' class='flat' name='rb_rpttipe' id='rb_rptby1' value='M'> MTD </label>
                                                    <label class='btn btn-default'><input type='radio' class='flat' name='rb_rpttipe' id='rb_rptby2' value='Y' checked> YTD </label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                            </div>
                            <!--end kiri-->

                        </form>
                    </div><!--end xpanel-->
                </div>
                <?PHP
            break;

            case "tambahbaru":

            break;
        }
        ?>
    </div>
    <!--end row-->
</div>