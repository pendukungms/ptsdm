<link href="css/inputselectbox.css" rel="stylesheet" type="text/css" />
<style>
    #nwinbaru .form-group, #nwinbaru .input-group, #nwinbaru .control-label {
        margin-bottom:3px;
    }
    #nwinbaru .control-label {
        font-size:12px;
    }
    #nwinbaru input[type=text] {
        box-sizing: border-box;
        color:#000;
        font-size:12px;
        height: 30px;
    }
    #nwinbaru select.soflow {
        font-size:12px;
        height: 30px;
    }
    #nwinbaru .disabledDiv {
        pointer-events: none;
        opacity: 0.4;
    }
    #nwinbaru .btn-primary {
        width:50px;
        height:30px;
        margin-right: 50px;
    }
</style>

<?PHP
    session_start();
    include "../../config/koneksimysqli.php";
    $act="input";
    $aksi="";
    $hari_ini = date("Y-m-d");
    
    $pidbrno=$_POST['uidbr'];
    $pidinput="";
    
    
    $sql = "SELECT * FROM hrd.klaim WHERE klaimId='$pidbrno'";
    $tampil= mysqli_query($cnmy, $sql);
    $row= mysqli_fetch_array($tampil);
    $pptgl=$row['tgl'];
    $tgl1 = date('d/m/Y', strtotime($pptgl));
    $pjumlah=$row['jumlah'];
    
    $pjumlahrpusul="";
    
    
$pjnspajak="Y";
$pkenapajak="";
$pnoseripajak="";
$ptglfakturpajak=date('d/m/Y', strtotime($hari_ini));
$pjmldpp=0;

$pjmlppn=0;
$pjmlrpppn=0;

$pjnspph="";
$pjmlpph=5;
$pjmlrppph=0;
$pjmlbulat=0;
$pjmlmaterai=0;
$pjenisdpp="";



$prpjumlahjasa=0;


$pchkjasa="";
$pchkatrika="";


?>


    <!--input mask -->
    <script src="js/inputmask.js"></script>


    
<script> window.onload = function() { document.getElementById("e_jumlah").focus(); } </script>
<div id="nwinbaru">
<div class='modal-dialog modal-lg'>
    <!-- Modal content-->
    <div class='modal-content'>
        <div class='modal-header'>
            <button type='button' class='close' data-dismiss='modal'>&times;</button>
            <h4 class='modal-title'>Isi Data Pajak</h4>
        </div>

        
        
        <div class="">

            <!--row-->
            <div class="row">

                <form method='POST' action='<?PHP echo "$aksi?module=entrybrotcpajak&act=input&idmenu=87"; ?>' id='demo-form4' name='form4' data-parsley-validate class='form-horizontal form-label-left'>

                    <div class='col-md-12 col-sm-12 col-xs-12'>
                        <div class='x_panel'>

                            <div class='x_panel'>
                                <div class='x_content'>
                                    <div class='col-md-12 col-sm-12 col-xs-12'>


                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>ID <span class='required'></span></label>
                                            <div class='col-md-4'>
                                                <input type='text' id='e_brid' name='e_brid' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pidbrno; ?>' Readonly>
                                            </div>
                                        </div>
                                        
                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Jumlah USUL Rp. <span class='required'></span></label>
                                            <div class='col-md-4'>
                                                <input type='text' id='e_jumlahminta' name='e_jumlahminta' autocomplete='off' class='form-control col-md-7 col-xs-12 inputmaskrp2' value='<?PHP echo $pjumlah; ?>' >
                                                <input type='hidden' id='e_jumlahminta2' name='e_jumlahminta2' autocomplete='off' class='form-control col-md-7 col-xs-12 inputmaskrp2' value='<?PHP echo $pjumlah; ?>' Readonly>
                                            </div>
                                        </div>
                                        
                                <div id="n_input_div">
                                    
                                    
                                        <div hidden class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>IDINPUT <span class='required'></span></label>
                                            <div class='col-md-4'>
                                                <input type='text' id='e_idinput' name='e_idinput' class='form-control col-md-7 col-xs-12' value='<?PHP echo $pidinput; ?>' Readonly>
                                                <input type='text' id='e_jumlahminta3' name='e_jumlahminta3' autocomplete='off' class='form-control col-md-7 col-xs-12 inputmaskrp2' value='<?PHP echo "0"; ?>' Readonly>
                                            </div>
                                        </div>
                                        
                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Pajak <span class='required'></span></label>
                                            <div class='col-md-4'>
                                                <select class='form-control input-sm' id='cb_pajak' name='cb_pajak' onchange="">
                                                    <option value='Y' selected>Y</option>
                                                    <option value='N'>N</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        

                                    
                                        <div id="n_pajak1">


                                            <div  class='form-group'>
                                                <label class='control-label col-md-3 col-sm-3 col-xs-12' for='' style="color:blue;">Pengusaha Kena Pajak <span class='required'></span></label>
                                                <div class='col-md-6 col-sm-6 col-xs-12'>
                                                    <input type='text' id='e_kenapajak' name='e_kenapajak' class='form-control col-md-7 col-xs-12' autocomplete="off" value='<?PHP echo $pkenapajak; ?>'>
                                                </div>
                                            </div>

                                            <div class='form-group'>
                                                <label class='control-label col-md-3 col-sm-3 col-xs-12' for='' style="color:blue;">No Seri Faktur Pajak <span class='required'></span></label>
                                                <div class='col-md-6 col-sm-6 col-xs-12'>
                                                    <input type='text' id='e_noserifp' name='e_noserifp' class='form-control col-md-7 col-xs-12' autocomplete="off" value='<?PHP echo $pnoseripajak; ?>'>
                                                </div>
                                            </div>


                                            <div class='form-group'>
                                                <label class='control-label col-md-3 col-sm-3 col-xs-12' for='' style="color:blue;">Tgl Faktur Pajak </label>
                                                <div class='col-md-6 col-sm-6 col-xs-12'>
                                                    <div class='input-group date' id='mytgl01'>
                                                        <input type="text" class="form-control" id='mytgl05' name='e_tglpajak' required='required' placeholder='dd/MM/yyyy' data-inputmask="'mask': '99/99/9999'" value='<?PHP echo $ptglfakturpajak; ?>'>
                                                        <span class='input-group-addon'>
                                                            <span class='glyphicon glyphicon-calendar'></span>
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>

                                            <!--- untuk jasa -->
                                            <div hidden class='form-group'>
                                                <label class='control-label col-md-3 col-sm-3 col-xs-12' for='' style="color:blue;">&nbsp;<span class='required'></span></label>
                                                <div class='col-md-6 col-sm-6 col-xs-12'>
                                                    <input type="checkbox" value="jasa" id="chk_jasa" name="chk_jasa" onclick="cekBoxPilihDPP('chk_jasa')" <?PHP echo $pchkjasa; ?>> DPP Dari Jumlah Awal
                                                    <br/>
                                                    <input type="checkbox" value="atrika" id="chk_atrika" name="chk_atrika" onclick="cekBoxPilihDPP('chk_atrika')" <?PHP echo $pchkatrika; ?>> DPP Khusus (Atrika, dll)
                                                </div>
                                            </div>

                                            <div id="n_pajakjasa">

                                                <div class='form-group'>
                                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' for='' style="color:blue;"><u>Jumlah Awal (Rp.)</u> <span class='required'></span></label>
                                                    <div class='col-md-6 col-sm-6 col-xs-12'>
                                                        <input type='text' id='e_rpjmljasa' name='e_rpjmljasa' autocomplete='off' class='form-control col-md-7 col-xs-12 inputmaskrp2' value='<?PHP echo $prpjumlahjasa; ?>' onblur="HitungJumlahDPP()">
                                                    </div><!--disabled='disabled'-->
                                                </div>

                                            </div>
                                            <!--- END untuk jasa -->

                                            <div class='form-group'>
                                                <label class='control-label col-md-3 col-sm-3 col-xs-12' for='' style="color:blue;">DPP (Rp.) <span class='required'></span></label>
                                                <div class='col-md-6 col-sm-6 col-xs-12'>
                                                    <input type='text' id='e_jmldpp' name='e_jmldpp' autocomplete='off' class='form-control col-md-7 col-xs-12 inputmaskrp2' value='<?PHP echo $pjmldpp; ?>' onblur="HitungJumlah()">
                                                </div><!--disabled='disabled'-->
                                            </div>

                                            <div class='form-group'>
                                                <label class='control-label col-md-3 col-sm-3 col-xs-12' for='' style="color:blue;">PPN (%) <span class='required'></span></label>
                                                <div class='col-md-6 col-sm-6 col-xs-12'>
                                                    <input type='text' id='e_jmlppn' name='e_jmlppn' autocomplete='off' class='form-control col-md-7 col-xs-12 inputmaskrp2' value='<?PHP echo $pjmlppn; ?>' onblur="HitungPPN()">
                                                    <input type='hidden' id='e_jmlrpppn' name='e_jmlrpppn' autocomplete='off' class='form-control col-md-7 col-xs-12 inputmaskrp2' value='<?PHP echo $pjmlrpppn; ?>' Readonly>
                                                </div><!--disabled='disabled'-->
                                            </div>


                                            <div class='form-group'>
                                                <label class='control-label col-md-3 col-sm-3 col-xs-12' for='' style="color:blue;">PPH <span class='required'></span></label>
                                                <div class='col-xs-9'>
                                                    <div style="margin-bottom:2px;">
                                                        <select class='soflow' name='cb_pph' id='cb_pph' onchange="ShowPPH()">
                                                            <?php
                                                            $ketPPH21="PPH21 (DPP*5%*50%) atau (JML AWAL*5%*50%)";
                                                            $ketPPH23="PPH23 (DPP*2%) atau (JML AWAL*2%)";

                                                            $ketPPH22="PPH21 (DPP*6%*50%) atau (JML AWAL*6%*50%)";

                                                            if ($pjnspph=="pph21") {
                                                                echo "<option value=''></option>";
                                                                echo "<option value='pph21' selected>$ketPPH21</option>";
                                                                echo "<option value='pph23'>$ketPPH23</option>";
                                                                echo "<option value='pph22'>$ketPPH22</option>";
                                                            }elseif ($pjnspph=="pph23") {
                                                                echo "<option value=''></option>";
                                                                echo "<option value='pph21'>$ketPPH21</option>";
                                                                echo "<option value='pph23' selected>$ketPPH23</option>";
                                                                echo "<option value='pph22'>$ketPPH22</option>";
                                                            }elseif ($pjnspph=="pph22") {
                                                                echo "<option value=''></option>";
                                                                echo "<option value='pph21'>$ketPPH21</option>";
                                                                echo "<option value='pph23'>$ketPPH23</option>";
                                                                echo "<option value='pph22' selected>$ketPPH22</option>";
                                                            }else{
                                                                echo "<option value='' selected></option>";
                                                                echo "<option value='pph21'>$ketPPH21</option>";
                                                                echo "<option value='pph23'>$ketPPH23</option>";
                                                                echo "<option value='pph22'>$ketPPH22</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                        <input type='hidden' id='e_jmlpph' name='e_jmlpph' autocomplete='off' class='form-control col-md-7 col-xs-12 inputmaskrp2' value='<?PHP echo $pjmlpph; ?>' readonly>
                                                        <input type='hidden' id='e_jmlrppph' name='e_jmlrppph' autocomplete='off' class='form-control col-md-7 col-xs-12 inputmaskrp2' value='<?PHP echo $pjmlrppph; ?>' Readonly>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class='form-group'>
                                                <label class='control-label col-md-3 col-sm-3 col-xs-12' for='' style="color:blue;">Pembulatan <span class='required'></span></label>
                                                <div class='col-md-6 col-sm-6 col-xs-12'>
                                                    <input type='text' id='e_jmlbulat' name='e_jmlbulat' autocomplete='off' class='form-control col-md-7 col-xs-12 inputmaskrp2' value='<?PHP echo $pjmlbulat; ?>' onblur="HitungJumlahUsulan()">
                                                </div><!--disabled='disabled'-->
                                            </div>


                                            <div class='form-group'>
                                                <label class='control-label col-md-3 col-sm-3 col-xs-12' for='' style="color:blue;">Biaya Materai (Rp.) <span class='required'></span></label>
                                                <div class='col-md-6 col-sm-6 col-xs-12'>
                                                    <input type='text' id='e_jmlmaterai' name='e_jmlmaterai' autocomplete='off' class='form-control col-md-7 col-xs-12 inputmaskrp2' value='<?PHP echo $pjmlmaterai; ?>' onblur="HitungJumlahUsulan()">
                                                </div><!--disabled='disabled'-->
                                            </div>

                                        </div>
                                    
                                    
                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''>Jumlah <span class='required'></span></label>
                                            <div class='col-xs-9'>
                                                <input type='text' id='e_jmlusulan' name='e_jmlusulan' autocomplete='off' class='form-control col-md-7 col-xs-12 inputmaskrp2' value="<?PHP echo $pjumlahrpusul; ?>">
                                            </div><!--disabled='disabled'-->
                                        </div>
                                    
                                    
                                        <div class='form-group'>
                                            <label class='control-label col-md-3 col-sm-3 col-xs-12' for=''> <span class='required'></span></label>
                                            <div class='col-xs-9'>
                                                <div class="checkbox">
                                                    <button type='button' id='nm_btn_save' class='btn btn-success' onclick='disp_confirm_pajak("Simpan ?", "<?PHP echo $act; ?>")'>Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    
                                </div>
                                        


                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>


                </form>
                
                
                <div class='col-md-12 col-sm-12 col-xs-12' style="">
                    <div class='x_content'>
                        <div class='x_panel'>
                        <div id='xxpanel'>
                            <table id='dtableviewpajak' class='table table-striped table-bordered' width='100%'>
                                <thead>
                                    <tr>
                                        <th width='5px'>NO</th>
                                        <th width='20px'></th>
                                        <th width='20px' nowrap>ID</th>
                                        <th width='20px'>JUMLAH USUL</th>
                                        <th width='20px'>JUMLAH AWAL</th>
                                        <th width='20px'>DPP</th>
                                        <th width='20px'>PPN</th>
                                        <th width='20px'>PPN RP</th>
                                        <th width='20px'>PPH</th>
                                        <th width='20px'>PPH RP</th>
                                        
                                        <th width='20px'>PENGUSAHA</th>
                                        <th width='20px'>NOSERI</th>
                                        <th width='20px'>TGL. FP</th>
                                        
                                        <th width='30px'>TANGGAL</th>
                                        <th width='20px'>NOSLIP</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?PHP
                                        $sql = "SELECT '0' as idinput, klaimId, tgl, noslip, jumlah, nama_pengusaha, noseri, tgl_fp, "
                                               . " jasa_rp, dpp, ppn, ppn_rp, pph_jns, pph, pph_rp, jenis_dpp, "
                                               . " pembulatan, noseri_pph, tgl_fp_pph, dpp_pph FROM hrd.klaim WHERE "
                                               . " klaimId='$pidbrno' AND pajak='Y' AND (IFNULL(nama_pengusaha,'')<>'' OR "
                                               . " IFNULL(noseri,'')<>'' OR IFNULL(jasa_rp,'')<>'' OR IFNULL(dpp,0)<>0 OR IFNULL(ppn,0)<>0)  "
                                               . " UNION ";
                                        $sql .= "SELECT a.idinput, a.klaimId, b.tgl, b.noslip, b.jumlah, a.nama_pengusaha, a.noseri, a.tgl_fp, "
                                               . " a.jasa_rp, a.dpp, a.ppn, a.ppn_rp, a.pph_jns, a.pph, a.pph_rp, a.jenis_dpp, "
                                               . " a.pembulatan, a.noseri_pph, a.tgl_fp_pph, a.dpp_pph "
                                               . " from dbmaster.t_klaim_pajak a JOIN hrd.klaim b on a.klaimId=b.klaimId WHERE "
                                               . " a.klaimId='$pidbrno' ";
                                        $query = "SELECT * FROM ($sql) as tbl ";
                                        $query .=" order by idinput";
                                        $tampil=mysqli_query($cnmy, $query) or die("mydata.php: get data");
                                        $no=1;
                                        while( $row=mysqli_fetch_array($tampil) ) {  // preparing an array
                                            
                                            $ni_idinput=$row['idinput'];
                                            $ni_brid=$row['klaimId'];
                                            $ni_tgl = $row["tgl"];
                                            $ni_noslip = $row["noslip"];
                                            $ni_jumlah = $row["jumlah"];
                                            $ni_nmpengusaha = $row["nama_pengusaha"];
                                            $ni_noseri = $row["noseri"];
                                            $ni_tglfp = $row["tgl_fp"];
                                            $ni_jumlahawal = $row["jasa_rp"];
                                            $ni_dpp = $row["dpp"];
                                            $ni_ppn = $row["ppn"];
                                            $ni_ppnrp = $row["ppn_rp"];
                                            $ni_pphjns = $row["pph_jns"];
                                            $ni_pph = $row["pph"];
                                            $ni_pphrp = $row["pph_rp"];
                                            
                                            
                                            $ni_tgl= date("d/m/Y", strtotime($ni_tgl));
                                            
                                            if (!empty($ni_tglfp) AND $ni_tglfp<>"0000-00-00") $ni_tglfp= date("d/m/Y", strtotime($ni_tglfp));
                                            
                                            $ni_jumlah=number_format($ni_jumlah,0,",",",");
                                            $ni_jumlahawal=number_format($ni_jumlahawal,0,",",",");
                                            $ni_dpp=number_format($ni_dpp,0,",",",");
                                            $ni_ppnrp=number_format($ni_ppnrp,0,",",",");
                                            $ni_pphrp=number_format($ni_pphrp,0,",",",");
                                            
                                            $ni_hapus="<input type='button' value='Hapus' class='btn btn-danger btn-xs' onClick=\"disp_hapusdata_pajak('Hapus Data..?', '$ni_idinput')\">";
                                            $ni_edit="<input type='button' value='Edit' class='btn btn-success btn-xs' onClick=\"disp_edit_data('Edit ..?', '$ni_idinput')\">";
                                            
                                            if ($ni_idinput=="0") {
                                                $ni_hapus="";
                                                $ni_edit="";
                                            }
                                            
                                            echo "<tr>";
                                            echo "<td nowrap>$no<t/d>";
                                            echo "<td nowrap>$ni_edit $ni_hapus<t/d>";
                                            echo "<td nowrap>$ni_brid<t/d>";
                                            echo "<td nowrap align='right'>$ni_jumlah<t/d>";
                                            
                                            echo "<td nowrap align='right'>$ni_jumlahawal<t/d>";
                                            echo "<td nowrap align='right'>$ni_dpp<t/d>";
                                            echo "<td nowrap align='right'>$ni_ppn<t/d>";
                                            echo "<td nowrap align='right'>$ni_ppnrp<t/d>";
                                            echo "<td nowrap align='right'>$ni_pphjns<t/d>";
                                            echo "<td nowrap align='right'>$ni_pphrp<t/d>";
                                            
                                            echo "<td nowrap>$ni_nmpengusaha<t/d>";
                                            echo "<td nowrap>$ni_noseri<t/d>";
                                            echo "<td nowrap>$ni_tglfp<t/d>";
                                            
                                            echo "<td nowrap>$ni_tgl<t/d>";
                                            echo "<td nowrap>$ni_noslip<t/d>";
                                            
                                            
                                            echo "</tr>";
                                            $no=$no+1;
                                        }
                                    ?>
                                </body>
                            </table>
                            
                        </div>
                        </div>
                    </div>
                </div>
                
                
                
            </div>
            <!--end row-->
        </div>
        
        
        <div class='modal-footer'>
            <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
        </div>
    </div>
</div>
</div>

<!-- jquery.inputmask -->
<script src="vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
<!-- Custom Theme Scripts -->


<script>
    
    $(document).ready(function() {
        var dataTable = $('#dtableviewpajak').DataTable( {
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            "ordering": false,
            "order": [[ 0, "desc" ]],
            "lengthMenu": [[10, 50, 100, -1], [10, 50, 100, "All"]],
            "displayLength": -1,
            "columnDefs": [
                { "visible": false },
                { "orderable": false, "targets": 0 },
                { "orderable": false, "targets": 1 },
                { className: "text-right", "targets": [3,4,5,6,7,8,9] },//right
                { className: "text-nowrap", "targets": [0, 1, 2, 3, 4, 5,6,7,8,9,10,11,12,13,14] }//nowrap

            ],
            "language": {
                "zeroRecords": "Lihat Page di bawah!!! Jika ada Page, Pilih Page 1...!!! Jika tidak ada Page, maka data KOSONG..."
            },
            
            /*,
            rowReorder: {
                selector: 'td:nth-child(3)'
            },
            responsive: true*/
        } );
        $('div.dataTables_filter input', dataTable.table().container()).focus();
    } );
    
    
    $('#mytgl01, #mytgl02').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: 'DD/MM/YYYY'
    });
    
    
    
    function disp_confirm_pajak(pText_,nid)  {
        // cb_pajak,e_kenapajak,e_noserifp,mytgl05,e_rpjmljasa,e_jmldpp,e_jmlppn,e_jmlrpppn,cb_pph,e_jmlpph,e_jmlrppph,e_jmlbulat,e_jmlmaterai,e_jmlusulan
        var eact="inputdatapajak";
        var eidbr = document.getElementById("e_brid").value;
        var eidinput = document.getElementById("e_idinput").value;
        var cbpajak = document.getElementById("cb_pajak").value;
        var ekenapajak = document.getElementById("e_kenapajak").value;
        var enoserifp = document.getElementById("e_noserifp").value;
        var etglfp = document.getElementById("mytgl05").value;
        var erpjmljasa = document.getElementById("e_rpjmljasa").value;
        var ejmldpp = document.getElementById("e_jmldpp").value;
        var ejmlppn = document.getElementById("e_jmlppn").value;
        var ejmlrpppn = document.getElementById("e_jmlrpppn").value;
        var cbpph = document.getElementById("cb_pph").value;
        var ejmlpph = document.getElementById("e_jmlpph").value;
        var ejmlrppph = document.getElementById("e_jmlrppph").value;
        var ejmlbulat = document.getElementById("e_jmlbulat").value;
        var ejmlmaterai = document.getElementById("e_jmlmaterai").value;
        var ejmlusulan = document.getElementById("e_jmlusulan").value;
        var ejmltptotminta = document.getElementById("e_jumlahminta").value;
        var chkjasa = document.getElementById('chk_jasa').checked;
        var chkatrika = document.getElementById('chk_atrika').checked;
        
        
        var chkjsa="";
        if (chkjasa==true) {
            chkjsa="jasa";
        }
        
        var chkatk="";
        if (chkatrika==true) {
            chkatk="atrika";
        }
        
        
        if (ejmltptotminta=="") {
            ejmltptotminta="0";
        }
        
        if (ejmltptotminta=="0") {
            alert("Jumlah Minta Masih NOL....");
            return false;
        }
        
        
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
                    url:"module/mod_br_entryklaim/simpan_pajakklaim.php?module="+module+"&act="+eact+"&idmenu="+idmenu,
                    data:"uidbr="+eidbr+"&uidinput="+eidinput+"&cbpajak="+cbpajak+"&ekenapajak="+ekenapajak+"&enoserifp="+enoserifp+
                         "&etglfp="+etglfp+"&erpjmljasa="+erpjmljasa+"&ejmldpp="+ejmldpp+"&ejmlppn="+ejmlppn+"&ejmlrpppn="+ejmlrpppn+
                         "&cbpph="+cbpph+"&ejmlpph="+ejmlpph+"&ejmlrppph="+ejmlrppph+"&ejmlbulat="+ejmlbulat+"&ejmlmaterai="+ejmlmaterai+
                         "&ejmlusulan="+ejmlusulan+"&chk_atrika="+chkatk+"&ujmltptotminta="+ejmltptotminta+"&chk_jasa="+chkjsa,
                    success:function(data){
                        if (data.length > 2) {
                            alert(data);
                        }
                        nm_btn_save.style.display='none';
                        $('#myModal').modal('hide');
                    }
                });
            }
        } else {
            //document.write("You pressed Cancel!")
            return 0;
        }
    }

    
    function disp_edit_data(pText_,nid)  {
        $.ajax({
            type:"post",
            url:"module/mod_br_entryklaim/viewpajakedit.php?module=caridatapajak",
            data:"uid="+nid,
            success:function(data){
                $("#n_input_div").html(data);
            }
        });
    }
    
    
    function disp_hapusdata_pajak(pText_,nid)  {
        //alert(nid); return false;
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
                    url:"module/mod_br_entryklaim/simpan_pajakklaim.php?module="+module+"&act=hapus&idmenu="+idmenu,
                    data:"uid="+nid,
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

<script>
    
    $(document).ready(function() {
        ShowPajak();
        
        var myurl = window.location;
        var urlku = new URL(myurl);
        var module = urlku.searchParams.get("module");
        var idmenu = urlku.searchParams.get("idmenu");
        var idact = urlku.searchParams.get("act");
        
        //baru kalau salah delete aja
        if (idact=="editdata") {
            var epajak = document.getElementById('cb_pajak').value;
            if (epajak=="" || epajak=="N"){
            }else{
                cekBoxPilihDPP('chk_jasa');
                cekBoxPilihDPP('chk_atrika');
            }
        }
        //END baru kalau salah delete aja
        
        
    } );
    
    function ShowPajak(){
        var myurl = window.location;
        var urlku = new URL(myurl);
        var nact = urlku.searchParams.get("act");
        
        var epajak = document.getElementById('cb_pajak').value;

        if (epajak=="" || epajak=="N"){
            n_pajak1.style.display = 'none';
        }else{
            n_pajak1.style.display = 'block';
            if (nact!="editdata") {
                ShowInputJasa();
            }else{//baru kalau salah delete aja
                cekBoxPilihDPP('chk_jasa');
                cekBoxPilihDPP('chk_atrika');
            }
        }
        
        
        
        //document.getElementById('e_kenapajak').focus();
        /*
        if (epajak==""){
            n_pajak.classList.add("disabledDiv");
        }else{
            n_pajak.classList.remove("disabledDiv");
        }
        */
    }
    
    function cekBoxPilihDPP(nmcekbox){
        var nm = document.getElementById(nmcekbox);
        var chkjasa = document.getElementById('chk_jasa');
        var chkatrika = document.getElementById('chk_atrika');
        if (nm.checked) {
            if (nm.value=="jasa") {
                chkatrika.checked='';
            }else if (nm.value=="atrika") {
                chkjasa.checked='';
            }
        }
        ShowInputJasa();
    }
    
    function ShowInputJasa(){
        var echkjasa = document.getElementById("chk_jasa").checked;
        var echkatrika = document.getElementById("chk_atrika").checked;
        if (echkjasa==true || echkatrika==true) {
            n_pajakjasa.style.display = 'block';
        }else{
            n_pajakjasa.style.display = 'none';
        }
        HitungJumlah();
    }
    
    
    function HitungJumlahDPP(){
        var newchar = '';
        var e_totrpdpp = "0";
        erpjmldpp = document.getElementById("e_rpjmljasa").value;
        if (erpjmldpp!="" && erpjmldpp != "0") {
            var nrpjmldpp = erpjmldpp; 
            nrpjmldpp = nrpjmldpp.split(',').join(newchar);
            e_totrpdpp=nrpjmldpp*10/100;
        }
        document.getElementById("e_jmldpp").value=e_totrpdpp;
        HitungJumlah();
    }
    
    
    function HitungJumlah(){
        HitungPPN();
        HitungPPH();
        HitungJumlahUsulan();
    }

    function HitungPPN(){
        var newchar = '';
        var e_totrpppn = "0";

        ejmldpp = document.getElementById("e_jmldpp").value;
        if (ejmldpp!="" && ejmldpp != "0") {
            var njmldpp = ejmldpp; 
            njmldpp = njmldpp.split(',').join(newchar);

            eppn = document.getElementById("e_jmlppn").value;
            if (eppn!="" && eppn != "0") {
                var nppn = eppn; 
                nppn = nppn.split(',').join(newchar);

                e_totrpppn = njmldpp * nppn / 100;
            }

        }

        document.getElementById("e_jmlrpppn").value = e_totrpppn;
        HitungPPH();
    }

    function ShowPPH(){
        document.getElementById("e_jmlpph").value = "0";
        document.getElementById("e_jmlpph").value = "0";
        document.getElementById("e_jmlrppph").value = "0";
        

        
        var epph = document.getElementById("cb_pph").value;
        if (epph=="pph21") {
            document.getElementById("e_jmlpph").value = "5";
            HitungPPH();
        }else if (epph=="pph23") {
            document.getElementById("e_jmlpph").value = "2";
            HitungPPH();
        }else if (epph=="pph22") {
            document.getElementById("e_jmlpph").value = "6";
            HitungPPH();
        }else{
            document.getElementById("e_jmlpph").value = "0";
            document.getElementById("e_jmlrppph").value = "0";
            HitungJumlahUsulan();
        }
    }
    
    
    function HitungPPH(){
        var newchar = '';
        
        
        var echkjasa = document.getElementById("chk_jasa").checked;
        var echkatrika = document.getElementById("chk_atrika").checked;
        var erpjmljasa="0";
        if (echkjasa==true || echkatrika==true) {
            erpjmljasa=document.getElementById("e_rpjmljasa").value;
            var nrpjmljasa = erpjmljasa;
            erpjmljasa = nrpjmljasa.split(',').join(newchar);
            if (erpjmljasa=="") { erpjmljasa="0" }
        }
        
        
        
        var e_totrppph = "0";
        var epph = document.getElementById("cb_pph").value;
        
        if (epph!="") {
            ejmldpp = document.getElementById("e_jmldpp").value;
            if (ejmldpp!="" && ejmldpp != "0") {
                var njmldpp = ejmldpp; 
                njmldpp = njmldpp.split(',').join(newchar);

                
                var idpp_pilih=njmldpp;
                if (echkatrika==true) {
                    idpp_pilih=erpjmljasa;
                }
                
                e_totrppph = idpp_pilih;
                
                if (epph=="pph21") {
                    npph = "5";
                    e_totrppph = (idpp_pilih * npph / 100)*50/100;   
                }else if (epph=="pph23") {
                    npph = "2";
                    e_totrppph = (idpp_pilih * npph / 100);
                }else if (epph=="pph22") {
                    npph = "6";
                    e_totrppph = (idpp_pilih * npph / 100)*50/100;
                }
            }
        }
        document.getElementById("e_jmlrppph").value = e_totrppph;
        HitungJumlahUsulan();
    }


    function HitungJumlahUsulan(){

        var newchar = '';
        
        var echkjasa = document.getElementById("chk_jasa").checked;
        var echkatrika = document.getElementById("chk_atrika").checked;
        var erpjmljasa="0";
        if (echkjasa==true || echkatrika==true) {
            erpjmljasa=document.getElementById("e_rpjmljasa").value;
            var nrpjmljasa = erpjmljasa;
            erpjmljasa = nrpjmljasa.split(',').join(newchar);
            if (erpjmljasa=="") { erpjmljasa="0" }
        }
        
        
        ejmldpp = document.getElementById("e_jmldpp").value;
        var e_totrpusulan = ejmldpp;
        erpppn = document.getElementById("e_jmlrpppn").value;
        erppph = document.getElementById("e_jmlrppph").value;
        erpbulat = document.getElementById("e_jmlbulat").value;
        erpmaterai = document.getElementById("e_jmlmaterai").value;
        if (erpppn=="") erpppn="0";
        if (erppph=="") erppph="0";
        if (erpbulat=="") erpbulat="0";
        if (erpmaterai=="") erpmaterai="0";

        var epph = document.getElementById("cb_pph").value;

        var njmldpp = ejmldpp; 
        njmldpp = njmldpp.split(',').join(newchar);

        var nrpppn = erpppn; 
        nrpppn = nrpppn.split(',').join(newchar);

        var nrppph = erppph; 
        nrppph = nrppph.split(',').join(newchar);

        var nrpbulat = erpbulat; 
        nrpbulat = nrpbulat.split(',').join(newchar);

        var nrpmaterai = erpmaterai; 
        nrpmaterai = nrpmaterai.split(',').join(newchar);
        
        var idpp_pilih=njmldpp;
        /*if (echkjasa==true) {
            idpp_pilih=erpjmljasa;
        }*/
        
        if (epph=="pph21" || epph=="pph23" || epph=="pph22") {
            e_totrpusulan=( ( parseInt(idpp_pilih)+parseInt(nrpppn) - parseInt(nrppph) ) );
        }else{
            e_totrpusulan=( ( parseInt(idpp_pilih)+parseInt(nrpppn)));
        }
        e_totrpusulan=parseInt(e_totrpusulan)+parseInt(nrpbulat)+parseInt(nrpmaterai);
        
        
        
        if (echkjasa==true) {
            e_totrpusulan=parseInt(e_totrpusulan);//-parseInt(njmldpp)
            e_totrpusulan=parseInt(erpjmljasa)+parseInt(e_totrpusulan);
        }else if (echkatrika==true) {
            e_totrpusulan=parseInt(e_totrpusulan)-parseInt(njmldpp);
            e_totrpusulan=parseInt(erpjmljasa)+parseInt(e_totrpusulan);
        }
        
        
        document.getElementById("e_jmlusulan").value = e_totrpusulan;
        
        
        //tambahan
        ejmlrptotusul = document.getElementById("e_jumlahminta2").value;
        if (ejmlrptotusul=="") {
            ejmlrptotusul="0";
        }
        var njmltotminta = ejmlrptotusul; 
        njmltotminta = njmltotminta.split(',').join(newchar);
        
        ejmlmintaedit = document.getElementById("e_jumlahminta3").value;
        if (ejmlmintaedit=="") {
            ejmlmintaedit="0";
        }
        var njmlmintaedit = ejmlmintaedit; 
        njmlmintaedit = njmlmintaedit.split(',').join(newchar);
        
        ntotalmintarp=parseInt(njmltotminta)+parseInt(e_totrpusulan)-parseInt(njmlmintaedit);
        document.getElementById("e_jumlahminta").value=ntotalmintarp;
        
        
        

    }
</script>

<style>
    .divnone {
        display: none;
    }
    #dtableviewpajak th {
        font-size: 13px;
    }
    #dtableviewpajak td { 
        font-size: 11px;
    }
    .imgzoom:hover {
        -ms-transform: scale(3.5); /* IE 9 */
        -webkit-transform: scale(3.5); /* Safari 3-8 */
        transform: scale(3.5);
        
    }
    
        #nwinbaru #dtableviewpajak input[type=text], #nwinbaru #tabelnobr input[type=text] {
            box-sizing: border-box;
            color:#000;
            font-size:11px;
            height: 25px;
        }
        #nwinbaru select.soflow {
            font-size:12px;
            height: 30px;
        }
        #nwinbaru .disabledDiv {
            pointer-events: none;
            opacity: 0.4;
        }

        #nwinbaru table.dtableviewpajak, #nwinbaru table.tabelnobr {
            color: #000;
            font-family: Helvetica, Arial, sans-serif;
            width: 100%;
            border-collapse:
            collapse; border-spacing: 0;
            font-size: 11px;
            border: 0px solid #000;
        }

        #nwinbaru table.dtableviewpajak td, #nwinbaru table.tabelnobr td {
            border: 1px solid #000; /* No more visible border */
            height: 10px;
            transition: all 0.1s;  /* Simple transition for hover effect */
        }

        #nwinbaru table.dtableviewpajak th, #nwinbaru table.tabelnobr th {
            background: #DFDFDF;  /* Darken header a bit */
            font-weight: bold;
        }

        #nwinbaru table.dtableviewpajak td, #nwinbaru table.tabelnobr td {
            background: #FAFAFA;
        }

        /* Cells in even rows (2,4,6...) are one color */
        #nwinbaru tr:nth-child(even) #nwinbaru td { background: #F1F1F1; }

        /* Cells in odd rows (1,3,5...) are another (excludes header cells)  */
        #nwinbaru tr:nth-child(odd) #nwinbaru td { background: #FEFEFE; }

        #nwinbaru tr td:hover.biasa { background: #666; color: #FFF; }
        #nwinbaru tr td:hover.left { background: #ccccff; color: #000; }

        #nwinbaru tr td.center1, #nwinbaru td.center2 { text-align: center; }

        #nwinbaru tr td:hover.center1 { background: #666; color: #FFF; text-align: center; }
        #nwinbaru tr td:hover.center2 { background: #ccccff; color: #000; text-align: center; }
        /* Hover cell effect! */
        #nwinbaru tr td {
            padding: -10px;
        }
</style>