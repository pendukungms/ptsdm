<?php
    ini_set("memory_limit","5000M");
    ini_set('max_execution_time', 0);
    
    session_start();
    include "../../config/koneksimysqli.php";
    
    $ptanggalminta = date("d F Y");
    
    $date1=$_POST['utgl'];
    $tgl1= date("Y-m-01", strtotime($date1));
    $ptanggal= date("d F Y", strtotime($date1));
    
    $bulan= date("Ym", strtotime($date1));
    $pidcabang=$_POST['ucabang'];
    $cket=$_POST['usts'];
    $ctipe=$_POST['utipe'];
    
    $cket=$_POST['usts'];
    $hidensudahapv2="";
    if ( (INT)$cket==4) $hidensudahapv2="hidden";
    
    
    $_SESSION['SPGMSTPRSMTIPE']=$ctipe;
    $_SESSION['SPGMSTPRSMCAB']=$pidcabang;
    $_SESSION['SPGMSTPRSMTGL']=date("F Y", strtotime($date1));
    
    include "../../module/md_m_spg_proses/caridata.php";
    $tmp01 = CariDataSPGBR("3", $bulan, $pidcabang, "", $cket);
  
    
    $ketemudata = mysqli_num_rows(mysqli_query($cnmy, "select * from $tmp01"));
        
    $jmlkerja = 0;
    $jmlkerja_aspr = 0;
    $query = "select * from dbmaster.t_spg_jmlharikerja WHERE DATE_FORMAT(periode,'%Y%m')='$bulan'";
    $tampilnp = mysqli_query($cnmy, $query);
    while ($np= mysqli_fetch_array($tampilnp)) {
        if (!empty($np['jumlah'])) $jmlkerja=$np['jumlah'];
        if (!empty($np['jml_aspr'])) $jmlkerja_aspr=$np['jml_aspr'];
    }
    
?>
<script src="js/inputmask.js"></script>

<form method='POST' action='' id='d-form2' name='form2' data-parsley-validate class='form-horizontal form-label-left'>
    
    <div class='x_content' style="margin-left:-20px; margin-right:-20px;"><!-- overflow-x:auto; max-height: 500px; -->
        
            <div class="title_left">
                <h4 style="font-size : 12px; color: red;">
                    <?PHP
                        $noteket = strtoupper($cket);
                        $text="Data Yang Belum Approve";
                        if ($noteket=="2") $text="Data Yang Sudah Approve";
                        if ($noteket=="3") $text="Data Yang Sudah diPending";
                        if ($noteket=="4") $text="Data Yang Sudah Proses Manager";
                        echo "<b>$text</b>";
                    ?>
                </h4>
            </div>
        
            <div class="clearfix"></div>
        
            
        <b>Standar Hari Kerja SPG : <?PHP echo $jmlkerja; ?> Hari</b><br/>
        <b>Standar Hari Kerja ASPR : <?PHP echo $jmlkerja_aspr; ?> Hari</b>
        
        <table id='dtabelspgmgr' class='table table-striped table-bordered' width='100%'>
            <thead>
                <tr>
                <?PHP 
                    if ($cket=="1" AND $ketemudata>0) { 
                        $chkall = "<input type='checkbox' id='chkbtnbr' value='deselect' onClick=\"SelAllCheckBox('chkbtnbr', 'chkbox_br[]')\" checked />";
                    }else { 
                        $chkall = "<input type='checkbox' id='chkbtnbr' value='select' onClick=\"SelAllCheckBox('chkbtnbr', 'chkbox_br[]')\" />";
                    }
                    if ((INT)$cket==4) $chkall="";
                ?>
                <th align="center"><?PHP echo $chkall; ?></th>
                <th align="center">No</th>
                <th align="center">Cabang</th>
                <th align="center">Tgl. Pengajuan</th>
                <th align="center">Nama</th>
                
                <th align="center">Insentif</th>
                <th align="center">Insentif<br/>Tambahan</th>
                <th align="center">Gaji</th>
                
                <th align="center" nowrap>S</th>
                <th align="center" nowrap>I</th>
                <th align="center" nowrap>A</th>
                
                <th></th>
                <th align="center">Uang Makan</th>
                <!--
                <th align="center" colspan="2">Uang Makan</th>
                <th class="divnone"></th>
                -->
                
                <th align="center">Sewa Kendaraan</th>
                <th align="center">Pulsa</th>
                <th align="center">BBM</th>
                <th align="center">Parkir</th>
                <th align="center">Jumlah</th>
                <th align="center">Total</th>
                
                <th align="center">Jabatan</th>
                <th align="center">Area</th>
                <th align="center">Zona</th>
                <th align="center">Penempatan</th>
                </tr>
            </thead>
            <tbody>
                <?PHP
                $gtotaljml=0;
                $gtotaltot=0;
                $gtotaltrans=0;

                $no=1;
                $query = "select distinct icabangid, nama_cabang from $tmp01 order by nama_cabang";
                $tampil= mysqli_query($cnmy, $query);
                while ($row= mysqli_fetch_array($tampil)) {
                    $picabang=$row['icabangid'];
                    $pnmcabang=$row['nama_cabang'];
                    
                    $cari = mysqli_query($cnmy,"select idbrspg from $tmp01 WHERE icabangid='$picabang' order by nama_cabang, nama, id_spg, idbrspg LIMIT 1");
                    $rw= mysqli_fetch_array($cari);
                    $idno=$rw['idbrspg'];
                    
                    if ($cket=="1") { 
                        $cekbox = "<input type=checkbox value='$idno' name=chkbox_br[] checked>";
                    }else{
                        $cekbox = "<input type=checkbox value='$idno' name=chkbox_br[]>";
                    }

                    //if ($bolehapv==false) $cekbox="";//sudah approve atasan 2 / apv2
                    if ((INT)$cket==4) $cekbox="";
                        
                    echo "<tr>";
                    echo "<td nowrap>$cekbox</td>";
                    echo "<td nowrap>$no</td>";
                    echo "<td nowrap>$pnmcabang</td>";

                    $ilewat=false;
                    $query2 = "select * from $tmp01 WHERE icabangid='$picabang' order by nama_cabang, nama, id_spg, idbrspg";
                    $tampil2= mysqli_query($cnmy, $query2);
                    $jmlrow=mysqli_num_rows($tampil2);
                    $recno=1;
                    $ptotal=0;
                    while ($row2= mysqli_fetch_array($tampil2)) {
                        $idno=$row2['idbrspg'];
                        $pidspg=$row2['id_spg'];
                        $pnmspg=$row2['nama'];
                        $phk=$row2['hk'];
                        
                        $ptglpengajuan=date("d-m-Y", strtotime($row2['tglpengajuan']));
                        $ppenempatan=$row2['penempatan'];
                        $pnmarea=$row2['nama_area'];
                        $pnmzona=$row2['nama_zona'];
                        $pnmjabatan=$row2['nama_jabatan'];
                        
                        $pinsentif=number_format($row2['insentif'],0,",",",");
                        $pinsentif_tambahan=number_format($row2['insentif_tambahan'],0,",",",");
                        
                        $pgaji=number_format($row2['gaji'],0,",",",");
                        
                        $pjmlsakit=number_format($row2['jml_sakit'],0,",",",");
                        $pjmlizin=number_format($row2['jml_izin'],0,",",",");
                        $pjmlalpa=number_format($row2['jml_alpa'],0,",",",");
                    
                        $pmakan=number_format($row2['makan'],0,",",",");
                        $psewa=number_format($row2['sewa'],0,",",",");
                        $ppulsa=number_format($row2['pulsa'],0,",",",");
                        $pbbm=number_format($row2['bbm'],0,",",",");
                        $pparkir=number_format($row2['parkir'],0,",",",");
                        $pjumlah=number_format($row2['total'],0,",",",");

                        $ptotal=$ptotal+$row2['total'];

                        $gtotaljml=$gtotaljml+$row2['total'];

                        if ($cket=="1") { 
                            $cekbox = "<input type=checkbox value='$idno' name=chkbox_br[] checked>";
                        }else{
                            $cekbox = "<input type=checkbox value='$idno' name=chkbox_br[]>";
                        }

                        //if ($bolehapv==false) $cekbox="";//sudah approve atasan 2 / apv2
                        if ((INT)$cket==4) $cekbox="";
                    
                    
                        $psts=$row2['sts'];
                        $pcolor="";
                        if ($psts=="P") $pcolor="style='color:red';";
                        
                        
                        if ($ilewat==true) {
                            echo "<tr>";
                            echo "<td nowrap>$cekbox</td>";
                            echo "<td nowrap>$no</td>";
                            echo "<td nowrap></td>";
                        }
                        
                        echo "<td nowrap>$ptglpengajuan</td>";
                        echo "<td nowrap $pcolor>$pnmspg</td>";
                        
                        echo "<td nowrap align='right'>$pinsentif</td>";
                        echo "<td nowrap align='right'>$pinsentif_tambahan</td>";
                        
                        echo "<td nowrap align='right'>$pgaji</td>";
                        
                        echo "<td nowrap align='right'>$pjmlsakit</td>";
                        echo "<td nowrap align='right'>$pjmlizin</td>";
                        echo "<td nowrap align='right'>$pjmlalpa</td>";
                        
                        echo "<td nowrap align='center'>$phk</td>";
                        echo "<td nowrap align='right'>$pmakan</td>";
                        echo "<td nowrap align='right'>$psewa</td>";
                        echo "<td nowrap align='right'>$ppulsa</td>";
                        echo "<td nowrap align='right'>$pbbm</td>";
                        echo "<td nowrap align='right'>$pparkir</td>";
                        echo "<td nowrap align='right'><b>$pjumlah</b></td>";

                        $jmltotal="";
                        $jmltransfer="";

                        if ((double)$jmlrow==(double)$recno) {
                            $gtotaltot=$gtotaltot+$ptotal;
                            $jmltotal=number_format($ptotal,0,",",",");
                        }

                        echo "<td nowrap align='right'><b>$jmltotal</b></td>";

                        
                    
                        echo "<td nowrap $pcolor>$pnmjabatan</td>";
                        echo "<td nowrap $pcolor>$pnmarea</td>";
                        echo "<td nowrap $pcolor>$pnmzona</td>";
                        echo "<td nowrap $pcolor>$ppenempatan</td>";

                        echo "</tr>";
                        $ilewat=true;
                        $recno++;
                        $no++;
                    }
                }

                echo "<tr>";
                echo "<td colspan='23'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "</tr>";

                
                $gtotaljml=number_format($gtotaljml,0,",",",");
                $gtotaltot=number_format($gtotaltot,0,",",",");
                $gtotaltrans=number_format($gtotaltrans,0,",",",");

                echo "<tr>";
                echo "<td align='center' colspan='17'><b>GRAND TOTAL</b></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                echo "<td class='divnone'></td>";
                
                echo "<td align='right'><b>$gtotaljml</b></td>";
                echo "<td align='right'><b>$gtotaltot</b></td>";
                
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "</tr>";
                ?>
            </tbody>
        </table>
            
            
            
    </div>
    
    
    <!-- tanda tangan -->
    <?PHP
        if ((INT)$cket==1) {
            echo "<div class='col-sm-5'>";
            include "ttd_appvspgfinspv.php";
            echo "</div>";
        }elseif ((INT)$cket==2) {
            echo "<div class='col-sm-5'>";
            ?>
            <input type='button' class='btn btn-info btn-sm' id="s-submit" value="Un Proses" onclick='disp_confirm("hapus", "chkbox_br[]")'>
            <?PHP
            echo "</div>";
        }
    ?>
    
</form>

<?PHP
mysqli_query($cnmy, "drop table $tmp01");
//mysqli_query($cnmy, "drop TEMPORARY table $tmp02");
?>



<script>
    $(document).ready(function() {
        <?PHP if ($cket=="1" AND $ketemudata>0) { ?>
            
        <?PHP } ?>
        var dataTable = $('#dtabelspgmgr').DataTable( {
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "ordering": false,
            "order": [[ 0, "desc" ]],
            "lengthMenu": [[10, 50, 100, -1], [10, 50, 100, "All"]],
            "displayLength": -1,
            "columnDefs": [
                { "visible": false },
                { "orderable": false, "targets": 0 },
                { "orderable": false, "targets": 1 },
                { className: "text-right", "targets": [5,6,7,8,9,10,11,12,13] },//right
                { className: "text-nowrap", "targets": [0, 1, 2, 3, 4, 5,6,7,8,9,10,11,12,13,14,15,16,17] }//nowrap

            ],
            "language": {
                "zeroRecords": "Lihat Page di bawah!!! Jika ada Page, Pilih Page 1...!!! Jika tidak ada Page, maka data KOSONG..."
            },
            "scrollY": 440,
            "scrollX": true/*,
            rowReorder: {
                selector: 'td:nth-child(3)'
            },
            responsive: true*/
        } );
        $('div.dataTables_filter input', dataTable.table().container()).focus();
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
    
    
    function disp_confirm(ket, cekbr){
        
        var cmt = confirm('Apakah akan melakukan unproses ...?');
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
        
        
        var txt="";
        if (ket=="reject" || ket=="pending") {
            var textket = prompt("Masukan alasan "+ket+" : ", "");
            if (textket == null || textket == "") {
                txt = textket;
            } else {
                txt = textket;
            }
        }
       
        
        $("#loading2").html("<center><img src='images/loading.gif' width='50px'/></center>");
        $.ajax({
            type:"post",
            url:"module/md_m_spg_prosesfinspv/viewdata.php?module="+ket,
            data:"unoidbr="+allnobr,
            success:function(data){
                $("#loading2").html("");
                
                RefreshDataTabel('2')
                alert(data);
            }
        });
        
    }
    
    
</script>

<style>
    .divnone {
        display: none;
    }
    #dtabelspgmgr th {
        font-size: 13px;
    }
    #dtabelspgmgr td { 
        font-size: 11px;
    }
    .imgzoom:hover {
        -ms-transform: scale(3.5); /* IE 9 */
        -webkit-transform: scale(3.5); /* Safari 3-8 */
        transform: scale(3.5);
        
    }
</style>