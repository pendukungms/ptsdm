<?PHP
    date_default_timezone_set('Asia/Jakarta');
    session_start();
    include "../../../config/koneksimysqli.php";
    include "../../../config/fungsi_combo.php";
    
    $fkaryawan="";
    $fuserid="";
    if (isset($_SESSION['IDCARD'])) $fkaryawan=$_SESSION['IDCARD'];
    if (isset($_SESSION['USERID'])) $fuserid=$_SESSION['USERID'];
    
    $idbr=$_POST['uidinput'];
    
    
    $query="select * from dbmaster.t_suratdana_bank where idinputbank='$idbr'";
    $tampil= mysqli_query($cnmy, $query);
    $nx= mysqli_fetch_array($tampil);
    $tgl1=$nx['tanggal'];
    $pjmlsudah=$nx['jumlah'];
    $pkodeid=$nx['kodeid'];
    $psubkode=$nx['subkode'];
    $pbridinput=$nx['brid'];
    $pdivisiid=$nx['divisi'];;
    $pnosliplama=$nx['noslip'];;
    $nparentidb= $nx["parentidbank"];
    
    $pnoslipbaru="";
    $pketerangan="";
    
    
    $hari_ini = date("Y-m-d");
    $tgl1 = date('d/m/Y', strtotime($hari_ini));
    
    $query = "select igroup from dbmaster.t_kode_spd_pengajuan WHERE subkode='$psubkode'";
    $tampil2= mysqli_query($cnmy, $query);
    $nx2= mysqli_fetch_array($tampil2);
    $pigroup=$nx2['igroup'];
    
    $pbolehsimpan="";
    $phiddennoslip="hidden";
    //cek data input
    if ($pigroup=="1") {
        if ($pdivisiid=="OTC") {
            $query = "select brOtcId as brid from hrd.br_otc WHERE brOtcId='$pbridinput' And karyawanid='$fkaryawan'";
        }else{
            $query = "select brId as brid from hrd.br0 WHERE brId='$pbridinput' And LPAD(user1, 10, '0')='$fkaryawan'";
        }
        $tampil3= mysqli_query($cnmy, $query);
        $ketemu3= mysqli_num_rows($tampil3);
        if ((INT)$ketemu3>0) { $pbolehsimpan="Y"; $phiddennoslip=""; }
    }
    
    $pbuttonsimpan="";
    if (!empty($nparentidb)) $pbuttonsimpan="hidden";
    
$act="input";
$aksi="";
?>

<!--input mask -->
<script src="js/inputmask.js"></script>

    
<script> window.onload = function() { document.getElementById("e_id").focus(); } </script>

<div class='modal-dialog modal-lg'>
    <!-- Modal content-->
    <div class='modal-content'>
        <div class='modal-header'>
            <button type='button' class='close' data-dismiss='modal'>&times;</button>
            <h4 class='modal-title'>Transfer Ulang Bank</h4>
        </div>
        
        
        
        <div class="">

            <!--row-->
            <div class="row">

                <form method='POST' action='<?PHP echo "$aksi?module=brdanabank&act=input&idmenu=258"; ?>' id='demo-form6' name='form6' data-parsley-validate class='form-horizontal form-label-left'>
                

                    <div class='col-md-12 col-sm-12 col-xs-12'>
                        <div class='x_panel'>
                            
                            
                            <div class='x_panel'>
                                <div class='x_content'>
                                    <div class='col-md-12 col-sm-12 col-xs-12'>

                                        <div  class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>ID <span class='required'></span></label>
                                            <div class='col-md-4'>
                                                <input type='text' id='e_id' name='e_id' class='form-control col-md-7 col-xs-12' value='<?PHP echo $idbr; ?>' Readonly>
                                                <input type='hidden' id='e_groupid' name='e_groupid' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pigroup; ?>' Readonly>
                                                <input type='hidden' id='e_bridinput' name='e_bridinput' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pbridinput; ?>' Readonly>
                                                <input type='hidden' id='e_nosliplama' name='e_nosliplama' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pnosliplama; ?>' Readonly>
                                                <input type='hidden' id='e_divisiid' name='e_divisiid' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pdivisiid; ?>' Readonly>
                                                <input type='hidden' id='e_bolehsimpanbr' name='e_bolehsimpanbr' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pbolehsimpan; ?>' Readonly>
                                                <input type='hidden' id='e_userinput' name='e_userinput' class='form-control col-md-7 col-xs-12' value='<?PHP echo $fuserid; ?>' Readonly>
                                                <input type='hidden' id='e_kryinput' name='e_kryinput' class='form-control col-md-7 col-xs-12' value='<?PHP echo $fkaryawan; ?>' Readonly>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Tgl. Keluar </label>
                                            <div class='col-md-3'>
                                                <div class='input-group date' id='mytgl01'>
                                                    <input type="text" class="form-control" id='e_tglkeluar' name='e_tglkeluar' autocomplete='off' required='required' placeholder='dd/MM/yyyy' data-inputmask="'mask': '99/99/9999'" value='<?PHP echo $tgl1; ?>'>
                                                    <span class='input-group-addon'>
                                                        <span class='glyphicon glyphicon-calendar'></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Jml. Keluar (Rp.) <span class='required'></span></label>
                                            <div class='col-md-4'>
                                                <input type='text' id='e_jmlsisa' name='e_jmlsisa' autocomplete='off' class='form-control col-md-7 col-xs-12 inputmaskrp2' value='<?PHP echo $pjmlsudah; ?>' Readonly>
                                            </div>
                                        </div>
                                        
                                        
                                        <div <?PHP echo $phiddennoslip; ?> class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Noslip Baru <span class='required'></span></label>
                                            <div class='col-md-4'>
                                                <input type='text' id='e_noslipbaru' name='e_noslipbaru' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pnoslipbaru; ?>'>
                                            </div>
                                        </div>
                                        
                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Keterangan <span class='required'></span></label>
                                            <div class='col-md-4'>
                                                <input type='text' id='e_ket' name='e_ket' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pketerangan; ?>'>
                                            </div>
                                        </div>
                                        
                                        <div <?PHP echo $pbuttonsimpan; ?> class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''> <span class='required'></span></label>
                                            <div class='col-xs-9'>
                                                <div class="checkbox">
                                                    <button type='button' class='btn btn-success' onclick='disp_confirm_keluar_tu("Simpan ?", "<?PHP echo $act; ?>")'>Save</button>
                                                </div>
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
        
        
    </div>
    
    
</div>



<script>
    $('#mytgl01, #mytgl02').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'DD/MM/YYYY'
    });
    
    function disp_confirm_keluar_tu(pText_,ket)  {
        var eact=ket;
        var egroup = document.getElementById('e_groupid').value;
        var ebrid = document.getElementById('e_bridinput').value;
        var enosliplama = document.getElementById('e_nosliplama').value;
        var edivisi = document.getElementById('e_divisiid').value;
        var ebolehsimpanbr = document.getElementById('e_bolehsimpanbr').value;
        var eid = document.getElementById('e_id').value;
        var etglkeluar = document.getElementById("e_tglkeluar").value;
        var eketerangan = document.getElementById("e_ket").value;
        var enoslipbaru = document.getElementById("e_noslipbaru").value;
        var euserinput = document.getElementById("e_userinput").value;
        var ekryinput = document.getElementById("e_kryinput").value;
        //alert(etglkeluar); return false;
        ok_ = 1;
        if (ok_) {
            var r=confirm(pText_)
            if (r==true) {
                var myurl = window.location;
                var urlku = new URL(myurl);
                var module = urlku.searchParams.get("module");
                var idmenu = urlku.searchParams.get("idmenu");


                $.ajax({
                    type:"post",
                    url:"module/budget/bgt_danabank/simpan_bank_tu.php?module="+module+"&act="+eact+"&idmenu="+idmenu,
                    data:"uid="+eid+"&utglkeluar="+etglkeluar+"&unoslipbaru="+enoslipbaru+"&uketerangan="+eketerangan+"&ugroup="+egroup+
                            "&ubrid="+ebrid+"&udivisi="+edivisi+"&ubolehsimpanbr="+ebolehsimpanbr+"&uuserinput="+euserinput+"&ukryinput="+ekryinput+
                            "&unosliplama="+enosliplama,
                    success:function(data){
                        if (data.length > 2) {
                            alert(data);
                        }
                        $('#myModal').modal('hide');
                    }
                });
                
             
                
            }
        } else {
            //document.write("You pressed Cancel!")
            return 0;
        }
    }
    
    
    
</script>
<?PHP
mysqli_close($cnmy);
?>