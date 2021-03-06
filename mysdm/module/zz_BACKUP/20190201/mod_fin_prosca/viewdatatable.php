
<?php
    session_start();
    include "../../config/koneksimysqli.php";
    
    $kodeinput = " AND kode=3 ";
    
    $isitipe = $_POST['ucbtipeisi'];
    $cket = $_POST['eket'];
    $mytgl1 = $_POST['uperiode1'];
    $mytgl2 = $_POST['uperiode2'];
    $karyawan = $_POST['ukaryawan'];
    $lvlposisi = $_POST['ulevel'];
    $divisi = $_POST['udiv'];
    $stsapv = $_POST['uketapv'];
    
    
    
    $_SESSION['PROSCAISI_TIPE'] = $isitipe;
    $_SESSION['PROSCAISI_KET'] = $cket;
    $_SESSION['PROSCAISI_TGL1'] = $mytgl1;
    $_SESSION['PROSCAISI_TGL2'] = $mytgl2;
    $_SESSION['PROSCAISI_KRY'] = $karyawan;
    $_SESSION['PROSCAISI_LVL'] = $lvlposisi;
    $_SESSION['PROSCAISI_DIV'] = $divisi;
    $_SESSION['PROSCAISI_STSAPV'] = $stsapv;
    
    $tgl1= date("Y-m", strtotime($mytgl1));
    $tgl2= date("Y-m", strtotime($mytgl2));
    
?>


<form method='POST' action='' id='d-form2' name='d-form2' data-parsley-validate class='form-horizontal form-label-left'>
    
    
    <div class='x_content'>
        <?PHP if (!empty($isitipe)) { ?>
        <div class='col-md-12 col-sm-12 col-xs-12'>
            <div class='x_panel'>
                <?PHP 
                if ($isitipe=="A") { 
                    echo "<div class='col-sm-3'>";
                        echo "<b>PPN %</b>";
                        echo "<div class='form-group'>";
                            echo "<div class='input-group date' id=''>";
                            echo "<input type='text' class='form-control inputmaskrp2' id='e_ppn' name='e_ppn' required='required' placeholder='ppn' value=''>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                }elseif ($isitipe=="B") {
                    $hari_ini = date("Y-m-d");
                    $tglberlku = date('d F Y', strtotime($hari_ini));
                    echo "<div class='col-sm-3'>";
                        echo "<b>Tanggal Transfer</b>";
                        echo "<div class='form-group'>";
                            echo "<div class='input-group date' id=''>";
                                echo "<input type='text' class='form-control' id='e_tgltrans' name='e_tgltrans' autocomplete='off' value='$tglberlku' />";
                                echo "<span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                }
                ?>
                
                <div class='col-sm-3'>
                    <small>&nbsp;</small>
                   <div class="form-group">
                       <input style="font-weight: bold; border:1px solid #000; color:#000;" type='button' class='btn btn-default btn-sm' id="s-submit" value="&nbsp;Save&nbsp;" onclick='disp_confirm("Simpan...?", "<?PHP echo "$isitipe"; ?>")'>
                   </div>
               </div>
                
            </div>
        </div>
        <?PHP } ?>
    </div>
    
    
    <div class='x_content'>
        
            <div class="title_left">
                <h4 style="font-size : 12px;">
                    <?PHP
                        $noteket = strtoupper($cket);
                        $apvby = "";
                        if ($lvlposisi=="FF2") $apvby = "SPV / AM";
                        if ($lvlposisi=="FF3") $apvby = "DM";
                        if ($lvlposisi=="FF4") $apvby = "SM";
                        if (!empty($apvby)) $apvby = ".&nbsp; &nbsp; Status Karyawan : $apvby";
                        $text="";
                        if ($noteket=="APPROVE") $text="Data Yang Belum DiProses";
                        if ($noteket=="UNAPPROVE") $text="Data Yang Sudah DiProses";
                        if ($noteket=="REJECT") $text="Data Yang DiReject";
                        if ($noteket=="PENDING") $text="Data Yang DiPending";
                        if ($noteket=="SEMUA") $text="Data Yang Belum dan Sudah Proses";
                        if ($noteket=="BELUMAPVSM") $text="Data Yang Belum Approve SM";
                        
                        echo "<b>$text $apvby</b>";
                    ?>
                </h4>
            </div><div class="clearfix">
        </div>
        <table id='datatableproscaisi' class='table table-striped table-bordered' width='100%'>
            <thead>
                <tr>
                    <th width='20px'>
                        <input type="checkbox" id="chkbtnbr" value="select" 
                        onClick="SelAllCheckBox('chkbtnbr', 'chkbox_br[]')" />
                    </th>
                    <th width='40px'>ID</th>
                    <th width='100px'>Yg Membuat</th>
                    <th width='60px'>Jumlah</th>
                    <th width='40px'>PPN</th>
                    <th width='50px'>Tgl Input</th>
                    <th width='120px'>Periode</th>
                    <th width='30px'>Bukti</th>
                    <th width='100px'>Keterangan</th>
                    <th width='30px'>Divisi</th>
                    <th width='30px'>Approve SPV/AM</th>
                    <th width='30px'>Approve DM</th>
                    <th width='30px'>Approve SM</th>
                    <th width='30px'>Approve GSM</th>
                    <th width='30px'>Tgl. Transfer</th>
                </tr>
            </thead>
            <tbody>
                <?PHP
                $sql = "SELECT idca, DATE_FORMAT(tgl,'%d %M %Y') as tgl, periode as bulan, DATE_FORMAT(periode,'%d/%m/%Y') as periode, "
                        . " divisi, karyawanid, nama, areaid, nama_area, FORMAT(jumlah,0,'de_DE') as jumlah, keterangan, "
                        . " ppn ";
                $sql.=" FROM dbmaster.v_ca0 ";
                
                $sql="SELECT br.idca, DATE_FORMAT(br.tgltrans,'%d/%m/%Y') as tgltrans, DATE_FORMAT(br.tgl,'%d %M %Y') as tgl, DATE_FORMAT(br.periode,'%M %Y') as bulan, DATE_FORMAT(br.tgl,'%d/%m/%Y') as periode, 
                    br.divisi, br.karyawanid, k.nama, br.areaid, FORMAT(br.jumlah,0,'de_DE') as jumlah, br.keterangan, br.ppn,
                    i.idca bukti,
                    ifnull(br.tgl_atasan1,'0000-00-00') tgl_atasan1,
                    br.gbr_atasan1,
                    ifnull(br.tgl_atasan2,'0000-00-00') tgl_atasan2,
                    br.gbr_atasan2,
                    ifnull(br.tgl_atasan3,'0000-00-00') tgl_atasan3,
                    br.gbr_atasan3,
                    ifnull(tgl_atasan4,'0000-00-00') tgl_atasan4,
                    br.gbr_atasan4,
                    br.jabatanid,
                    DATE_FORMAT(br.periode,'%Y%m') as mybulannya 
                    FROM dbmaster.t_ca0 br 
                    LEFT JOIN hrd.karyawan AS k ON br.karyawanid = k.karyawanId 
                    LEFT JOIN (SELECT distinct DISTINCT idca from dbimages.img_ca0) as i on i.idca=br.idca ";
                $sql.=" WHERE 1=1  ";
                $sql.=" AND Date_format(br.tgl, '%Y-%m') between '$tgl1' and '$tgl2' ";
                
                if (!empty($divisi)) $sql.=" and br.divisi in $divisi ";
                
                if (strtoupper($cket)!= "REJECT") $sql.=" AND br.stsnonaktif <> 'Y' ";
                
                if ( (strtoupper($cket)!="SEMUA") ) {
                    if (strtoupper($cket)=="REJECT") {
                        $sql.=" AND br.stsnonaktif = 'Y' ";
                    }elseif (strtoupper($cket)=="BELUMAPVSM") {
                        $sql.=" AND ifnull(br.tgl_atasan3,'') = '' and ifnull(br.fin,'') = '' ";
                    }else{
                        $sql.=" AND ifnull(br.tgl_atasan3,'') <> '' ";//AND (br.jabatanid <> '20' OR (br.jabatanid = '20' AND ifnull(br.tgl_atasan4,'') <> '') )
                        if (strtoupper($cket)=="APPROVE") {
                            $sql.=" AND ifnull(br.tgl_fin,'') = '' ";
                        }elseif (strtoupper($cket)=="UNAPPROVE") {
                            $sql.=" AND ifnull(br.tgl_fin,'') <> '' ";
                        }elseif (strtoupper($cket)=="PENDING") {

                        }
                    }
                }
                
                
                $sql.=" order by DATE_FORMAT(br.periode,'%Y%m'), idca";
                //echo $sql;
                $no=1;
                $tampil = mysqli_query($cnmy, $sql);
                while ($row= mysqli_fetch_array($tampil)) {
                    $idno=$row['idca'];
                    $tglbuat = $row["tgl"];
                    $nama = $row["nama"];
                    $pbukti = $row["bukti"];
                    //$nmarea = $row["nama_area"];
                    $tgltransfer="";
                    if (!empty($row["tgltrans"]))
                        $tgltransfer = $row["tgltrans"];
                    $bulan = date("F Y", strtotime($row["bulan"]));
                    $periode = $row["periode"];
                    $pbulan = $row["bulan"];
                    $jumlah = $row["jumlah"];
                    $ppn = $row["ppn"];
                    $keterangan = $row["keterangan"];
                    $pdivisi = $row["divisi"];
                    $cekbox = "<input type=checkbox value='$idno' name=chkbox_br[]>";
                    
                    $print="<a title='Print / Cetak' href='#' class='btn btn-info btn-xs' data-toggle='modal' "
                        . "onClick=\"window.open('eksekusi3.php?module=entrybrcash&brid=$idno&iprint=print',"
                        . "'Ratting','width=700,height=500,left=500,top=100,scrollbars=yes,toolbar=yes,status=1,pagescrool=yes')\"> "
                        . "$idno</a>";
                    
                    $bukti="";
                    if (!empty($pbukti)) {
                        $bukti="<a title='lihat bukti' href='#' class='btn btn-danger btn-xs' data-toggle='modal' "
                            . "onClick=\"window.open('eksekusi3.php?module=entrybrcash&brid=$idno&iprint=bukti',"
                            . "'Ratting','width=700,height=500,left=500,top=100,scrollbars=yes,toolbar=yes,status=1,pagescrool=yes')\"> "
                            . "Lihat</a>";
                    }
                     
                    
                    $apv1="";
                    $apv2="";
                    $apv3="";
                    $apv4="";
                    if (!empty($row["gbr_atasan1"]) AND $row["tgl_atasan1"] <> "0000-00-00") $apv1=date("d F Y, h:i:s", strtotime($row["tgl_atasan1"]));
                    if (!empty($row["gbr_atasan2"]) AND $row["tgl_atasan2"] <> "0000-00-00") $apv2=date("d F Y, h:i:s", strtotime($row["tgl_atasan2"]));
                    if (!empty($row["gbr_atasan3"]) AND $row["tgl_atasan3"] <> "0000-00-00") $apv3=date("d F Y, h:i:s", strtotime($row["tgl_atasan3"]));
                    if (!empty($row["gbr_atasan4"]) AND $row["tgl_atasan4"] <> "0000-00-00") $apv4=date("d F Y, h:i:s", strtotime($row["tgl_atasan4"]));
                    
                    $edit="";
                    if (strtoupper($cket)=="APPROVE" OR strtoupper($cket)=="BELUMAPVSM") {
                        $edit="<a title='lihat bukti' href='#' class='btn btn-success btn-xs' data-toggle='modal' "
                            . "onClick=\"window.open('eksekusi3.php?module=$_GET[module]&idmenu=$_GET[idmenu]&brid=$idno&iprint=editrutin',"
                            . "'Ratting','width=1000,height=600,left=200,top=50,scrollbars=yes,toolbar=yes,status=1,pagescrool=yes')\"> "
                            . "Edit</a>";
                    }
                    
                    $pjabat = $row["jabatanid"];
                    if ((int)$pjabat==20 OR (int)$pjabat==5) {
                        if (empty($row["gbr_atasan4"]) AND $row["tgl_atasan4"] == "0000-00-00") {
                            $cekbox="";
                        }
                    }
                    
                    echo "<tr>";
                    echo "<td>$cekbox</td>";
                    echo "<td>$print $edit</td>";
                    echo "<td>$nama</td>";
                    echo "<td>$jumlah</td>";
                    echo "<td>$ppn</td>";
                    echo "<td>$periode</td>";
                    echo "<td nowrap>$pbulan</td>";
                    echo "<td>$bukti</td>";
                    echo "<td>$keterangan</td>";
                    echo "<td>$pdivisi</td>";
                    echo "<td>$apv1</td>";
                    echo "<td>$apv2</td>";
                    echo "<td>$apv3</td>";
                    echo "<td>$apv4</td>";
                    echo "<td>$tgltransfer</td>";
                    echo "</tr>";
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>
    
<?PHP
if (strtoupper($cket)=="UNAPPROVE" OR strtoupper($cket)=="BELUMAPVSM") {
?>
    <div class='clearfix'></div>
    <div class="well" style="overflow: auto; margin-top: -5px; margin-bottom: 5px; padding-top: 10px; padding-bottom: 6px;">
        <?PHP
        if (strtoupper($cket)=="APPROVE") {
            ?>
            <!--<input class='btn btn-default' type='Submit' name='buttonapv' value='Approve'>-->
            <!--<input class='btn btn-danger' type='button' name='buttonapv' value='Reject' 
                   onClick="ProsesData('reject', 'chkbox_br[]')"> dipindah ke ttd-->
            <input class='btn btn-default' type='hidden' name='buttonapv' value='Pending' 
                   onClick="ProsesData('pending', 'chkbox_br[]')">
            <?PHP
        }elseif (strtoupper($cket)=="UNAPPROVE") {
            ?>
            <input class='btn btn-success' type='button' name='buttonapv' value='UnApprove' 
                   onClick="ProsesData('unapprove', 'chkbox_br[]')">
            <?PHP
        }elseif (strtoupper($cket)=="REJECT") {
        }elseif (strtoupper($cket)=="PENDING") {
        }elseif (strtoupper($cket)=="SEMUA") {
        }elseif (strtoupper($cket)=="BELUMAPVSM") {
            ?>
            <input class='btn btn-danger' type='button' name='buttonapv' value='Reject' 
                   onClick="ProsesData('reject', 'chkbox_br[]')">
            <?PHP
        }
        ?>
    </div>
<?PHP
}
?>
    
    <div class='clearfix'></div>


    <!-- tanda tangan -->
    <?PHP
        if (strtoupper($cket)=="APPROVE") {
            echo "<div class='col-sm-5'>";
            include "ttd_prosca.php";
            echo "</div>";
        }
    ?>
</form>

<script>
    $(document).ready(function() {
        //alert(etgl1);
        var dataTable = $('#datatableproscaisi').DataTable( {
            "stateSave": true,
            "order": [[ 0, "desc" ]],
            "lengthMenu": [[10, 50, 100, -1], [10, 50, 100, "All"]],
            "displayLength": 10,
            "columnDefs": [
                { "visible": false },
                { "orderable": false, "targets": 0 },
                { "orderable": false, "targets": 1 },
                { className: "text-right", "targets": [3, 4] },//right
                { className: "text-nowrap", "targets": [0, 1, 2, 3, 4, 5] }//nowrap

            ],
            "language": {
                "zeroRecords": "Lihat Page di bawah!!! Jika ada Page, Pilih Page 1...!!! Jika tidak ada Page, maka data KOSONG..."
            },
            rowReorder: {
                selector: 'td:nth-child(3)'
            },
            responsive: true
        } );
        $('div.dataTables_filter input', dataTable.table().container()).focus();
        $(".inputmaskrp2").inputmask({ 'alias' : 'decimal', rightAlign: false, 'groupSeparator': '.','autoGroup': true });
    } );
    
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
    }
    
    function ProsesData(ket, cekbr){
        var cmt = confirm('Apakah akan melakukan proses '+ket+' ...?');
        if (cmt == false) {
            return false;
        }
        var chk_arr =  document.getElementsByName(cekbr);
        var chklength = chk_arr.length;             
        var allnobr="";
        for(k=0;k< chklength;k++)
        {
            if (chk_arr[k].checked == true) {
                allnobr =allnobr + "'"+chk_arr[k].value+"',";
            }
        }
        if (allnobr.length > 0) {
            var lastIndex = allnobr.lastIndexOf(",");
            allnobr = "("+allnobr.substring(0, lastIndex)+")";
        }else{
            alert("Tidak ada data yang diproses...!!!");
            return false;
        }
        
        var txt;
        if (ket=="reject" || ket=="pending") {
            var textket = prompt("Masukan alasan "+ket+" : ", "");
            if (textket == null || textket == "") {
                txt = textket;
            } else {
                txt = textket;
            }
        }
        
        var ekaryawan=document.getElementById('e_idkaryawan').value;
        var elevel=document.getElementById('e_lvlposisi').value;
            
        var myurl = window.location;
        var urlku = new URL(myurl);
        var module = urlku.searchParams.get("module");
        var idmenu = urlku.searchParams.get("idmenu");
        
        $.ajax({
            type:"post",
            url:"module/mod_fin_prosca/aksi_prosca.php?module="+module+"&idmenu="+idmenu+"&act="+ket,
            data:"ket=approve"+"&unobr="+allnobr+"&ukaryawan="+ekaryawan+"&ulevel="+elevel+"&ketrejpen="+txt,
            success:function(data){
                pilihData(ket);
                alert(data);
            }
        });
        
    }
    
    
    function disp_confirm(pText_, act)  {
        var chk_arr =  document.getElementsByName("chkbox_br[]");
        var chklength = chk_arr.length;             
        var allnobr="";
        for(k=0;k< chklength;k++)
        {
            if (chk_arr[k].checked == true) {
                allnobr =allnobr + "'"+chk_arr[k].value+"',";
            }
        }
        if (allnobr.length > 0) {
            var lastIndex = allnobr.lastIndexOf(",");
            allnobr = "("+allnobr.substring(0, lastIndex)+")";
        }else{
            alert("Tidak ada data yang diproses...!!!");
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
                //document.write("You pressed OK!")
                document.getElementById("d-form2").action = "module/mod_fin_prosca/aksi_prosca.php?module="+module+"&idmenu="+idmenu+"&act="+act;
                document.getElementById("d-form2").submit();
                return 1;
            }
        } else {
            //document.write("You pressed Cancel!")
            return 0;
        }
    }
    
</script>

<script>
                                    
    $(document).ready(function() {

        $('#e_tgltrans').datepicker({
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd MM yy',
            onSelect: function(dateStr) {

            }

        });
    });

</script>

<style>
    .divnone {
        display: none;
    }
    #datatableproscaisi th {
        font-size: 13px;
    }
    #datatableproscaisi td { 
        font-size: 11px;
    }
    .imgzoom:hover {
        -ms-transform: scale(3.5); /* IE 9 */
        -webkit-transform: scale(3.5); /* Safari 3-8 */
        transform: scale(3.5);
        
    }
</style>