<div class="">

    <div class="col-md-12 col-sm-12 col-xs-12"><div class="title_left"><h3>Evaluasi Sales Outlet</h3></div></div><div class="clearfix"></div>
    <!--row-->
    <div class="row">
        <?php
        include "config/koneksimysqli_ms.php";
        
        $pmyidcard=$_SESSION['IDCARD'];
        $pidkrypilih=$_SESSION['IDCARD'];
        $pmyjabatanid=$_SESSION['JABATANID'];
        
        
        $filtercabangbyadmin="";
        $query = "select distinct icabangid from hrd.rsm_auth WHERE karyawanid='$pmyidcard'";
        $tampil= mysqli_query($cnmy, $query);
        $ketemu= mysqli_num_rows($tampil);
        if ($ketemu>0) {
            while ($rs= mysqli_fetch_array($tampil)) {
                $picabid_=$rs['icabangid'];
                $filtercabangbyadmin .="'".$picabid_."',";
            }
            if (!empty($filtercabangbyadmin)) {
                $filtercabangbyadmin="(".substr($filtercabangbyadmin, 0, -1).")";
            }
        }
        
        if ($_SESSION['GROUP']=="1" OR $_SESSION['GROUP']=="24") {
            $filtercabangbyadmin="";
        }
        
        $filter_karyawan="";
        $query_cab_kry = "";
        if ($pmyjabatanid=="10" OR $pmyjabatanid=="18") {
            $query_cab_kry = "select DISTINCT karyawanid from sls.imr0 WHERE CONCAT(IFNULL(icabangid,''),IFNULL(areaid,'')) IN "
                    . " (select distinct CONCAT(IFNULL(icabangid,''),IFNULL(areaid,'')) FROM sls.ispv0 WHERE karyawanid='$pmyidcard')";
        }elseif ($pmyjabatanid=="08") {
            $query_cab_kry = "select DISTINCT karyawanid from sls.imr0 WHERE IFNULL(icabangid,'') IN "
                    . " (select distinct IFNULL(icabangid,'') FROM sls.idm0 WHERE karyawanid='$pmyidcard')";
        }elseif ($pmyjabatanid=="20") {
            $query_cab_kry = "select DISTINCT karyawanid from sls.imr0 WHERE IFNULL(icabangid,'') IN "
                    . " (select distinct IFNULL(icabangid,'') FROM sls.ism0 WHERE karyawanid='$pmyidcard')";
        }else{
            $query_cab_kry = "select DISTINCT karyawanid from sls.imr0 WHERE 1=1 ";
            if (!empty($filtercabangbyadmin)) $query_cab_kry .= " AND IFNULL(icabangid,'') IN $filtercabangbyadmin ";
        }
        
        if (!empty($query_cab_kry)) {
            $tampil= mysqli_query($cnms, $query_cab_kry);
            while ($rs= mysqli_fetch_array($tampil)) {
                $pikryid_=$rs['karyawanid'];
                
                $filter_karyawan .="'".$pikryid_."',";
                
            }
            
            if (!empty($filter_karyawan)) {
                $filter_karyawan="(".substr($filter_karyawan, 0, -1).")";
            }            
        }
        
        
        $aksi="eksekusi3.php";
        switch($_GET['act']){
            default:

                $hari_ini = date("Y-m-d");
                //$tgl_pertama=date('F Y', strtotime('-1 month', strtotime($hari_ini)));
                $tgl_pertama = date('Y', strtotime($hari_ini));
                ?>
                <script>
                    function disp_confirm(pText)  {
                        var eoutcb = document.getElementById("cb_outlet").value;
                        if (eoutcb=="") {
                            alert("outlet harus diisi....");
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
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Tahun <span class='required'></span></label>
                                            <div class='col-md-6'>
                                                <div class="form-group">
                                                    <div class='input-group date' id='thn01'>
                                                        <input type='text' id='tahun' name='tahun' required='required' class='form-control' placeholder='tgl awal' value='<?PHP echo $tgl_pertama; ?>' placeholder='dd mmm yyyy' Readonly>
                                                        <span class="input-group-addon">
                                                           <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12'>MR <span class='required'></span></label>
                                            <div class='col-md-9 col-sm-9 col-xs-12'>

                                                <select class='form-control' name='cb_mr' id='cb_mr' onchange="ShowDataCusId()">
                                                    <?PHP
                                                    $query_kry="";
                                                    if ($pmyjabatanid=="15") {
                                                        $query_kry = "select b.karyawanId, b.nama from ms.karyawan b WHERE karyawanId='$pmyidcard' order by b.nama";
                                                    }elseif ($pmyjabatanid=="10" OR $pmyjabatanid=="18" OR $pmyjabatanid=="08" OR $pmyjabatanid=="20") {
                                                        $query_kry = "select b.karyawanId, b.nama from ms.karyawan b WHERE b.karyawanid IN $filter_karyawan  order by b.nama";
                                                    }else{
                                                        $query_kry = "select b.karyawanId, b.nama from ms.karyawan b WHERE b.karyawanid IN $filter_karyawan  "
                                                                . " AND IFNULL(tglkeluar,'0000-00-00')='0000-00-00' OR IFNULL(tglkeluar,'')='' ";
                                                        $query_kry .=" ORDER BY b.nama";
                                                    }
                                                    
                                                    $no=1;
                                                    if (!empty($query_kry)) {
                                                        $tampil = mysqli_query($cnms, $query_kry);
                                                        $ketemu= mysqli_num_rows($tampil);
                                                        if ($ketemu==0) echo "<option value=''>-- Pilih --</option>";
                                                        while ($rx= mysqli_fetch_array($tampil)) {
                                                            $nidkry=$rx['karyawanId'];
                                                            $nnmkry=$rx['nama'];
                                                            if ($no==1) {
                                                                $pidkrypilih=$nidkry;
                                                                echo "<option value='$nidkry' selected>$nnmkry</option>";
                                                            }else{
                                                                echo "<option value='$nidkry'>$nnmkry</option>";
                                                            }
                                                            
                                                            $no++;
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                        </div>
                                        
                                        
                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12'>Outlet <span class='required'></span></label>
                                            <div class='col-md-9 col-sm-9 col-xs-12'>

                                                <select class='form-control' name='cb_outlet' id='cb_outlet'>
                                                    <?PHP
                                                    $query = "SELECT iCabangId, areaId, iCustId, nama from sls.icust WHERE CONCAT(iCabangId,areaId) IN "
                                                            . " (SELECT DISTINCT CONCAT(iCabangId,areaId) FROM sls.imr0 WHERE karyawanId='$pidkrypilih') "
                                                            . " order by nama";
                                                    $tampil = mysqli_query($cnms, $query);
                                                    $ketemu= mysqli_num_rows($tampil);
                                                    if ($ketemu==0) echo "<option value=''>-- Pilih --</option>";
                                                    while ($rx= mysqli_fetch_array($tampil)) {
                                                        $nidcab=$rx['iCabangId'];
                                                        $nidarea=$rx['areaId'];
                                                        $nidcustid=$rx['iCustId'];
                                                        $nidcustnm=$rx['nama'];
                                                        
                                                        $pigrpkode=$nidcab.$nidarea.$nidcustid;
                                                        
                                                        echo "<option value='$pigrpkode'>$nidcustnm</option>";
                                                        
                                                    }
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
                
                <script>
                    function ShowDataCusId() {
                        var emr = document.getElementById("cb_mr").value;
                        
                        $.ajax({
                            type:"post",
                            url:"module/sls_evalslsout/viewdata.php?module=caridatacustid",
                            data:"umr="+emr,
                            success:function(data){
                                $("#cb_outlet").html(data);
                            }
                        });
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