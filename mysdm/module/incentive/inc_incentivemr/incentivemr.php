<?PHP include "config/cek_akses_modul.php"; ?>

<div class="">

    <div class="col-md-12 col-sm-12 col-xs-12"><div class="title_left"><h3>Incentive MR</h3></div></div><div class="clearfix"></div>
    <!--row-->
    <div class="row">
        <?php
        include "config/koneksimysqli_ms.php";
        $pidkaryawan=$_SESSION['IDCARD'];
        $pidjabatan=$_SESSION['JABATANID'];
        $pidgroup=$_SESSION['GROUP'];
        
        $aksi="eksekusi3.php";
        switch($_GET['act']){
            default:

                $hari_ini = date("Y-m-d");
                //$tgl_pertama=date('F Y', strtotime('-1 month', strtotime($hari_ini)));
                $tgl_pertama = date('F Y', strtotime($hari_ini));
                $pbulan= date('Y-m', strtotime($hari_ini));
                ?>
                <script>
                    function disp_confirm(pText)  {
                        var ekryid = document.getElementById("cb_karyawan").value;
                        if (ekryid=="") {
                            alert("MR harus diisi....");
                            return false;
                        }
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

                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#cbln01').on('change dp.change', function(e){
                            showDataKaryawan();
                        });
                    });

                    function showDataKaryawan() {
                        var ibulan = document.getElementById('cbln01').value;
                        $.ajax({
                            type:"post",
                            url:"module/incentive/viewdatainc.php?module=viewdatamridkary",
                            data:"ubulan="+ibulan,
                            beforeSend: function () {
                                //$('select[name=cb_karyawan]').attr('disabled',true)
                                $('select[name=cb_karyawan]').empty();
                                $('select[name=cb_karyawan]').append('<option value="loading" disabled selected>Loading...</option>');
                            },
                            success:function(data){
                                $("#cb_karyawan").html(data);
                            },
                            complete: function () {
                                //$('select[name=cb_karyawan]').attr('disabled',false)
                                $('select[name=cb_karyawan] option[value="loading"]').text('');
                            },
                            error: function () {
                                //$('select[name=cb_karyawan]').attr('disabled',false)
                                $('select[name=cb_karyawan] option[value="loading"]').text('');
                                alert('Something wrong. Try Again!')                
                            }
                        });
                    }
                    
                    $(document).ready(function() {
                        showDataKaryawan();
                    } );
                </script>

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
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for='cbln01'>Bulan <span class='required'></span></label>
                                            <div class='col-md-8'>
                                                <div class="form-group">
                                                    <div class='input-group date' id=''>
                                                        <input type='text' id='cbln01' name='bulan' required='required' class='form-control' placeholder='tgl awal' value='<?PHP echo $tgl_pertama; ?>' placeholder='dd mmm yyyy' Readonly>
                                                        <span class="input-group-addon">
                                                           <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class='form-group'>
                                        <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>MR <span class='required'></span></label>
                                            <div class='col-md-9 col-sm-9 col-xs-12'>
                                                <select class='form-control' name='cb_karyawan' id='cb_karyawan' onchange="">
                                                    <?PHP
                                                    
                                                    ?>
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
                
                


                
                <?PHP
            break;

            case "tambahbaru":

            break;
        }
        ?>
    </div>
    <!--end row-->
</div>