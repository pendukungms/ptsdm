<?php
    if (empty($_SESSION['IDCARD']) AND empty($_SESSION['NAMALENGKAP'])){
        echo "<center>Maaf, Anda Harus Login Ulang.<br>"; exit;
    }
    
    $kodepilih="2";
    if ($_GET['module']=="entrybrluarkota") $kodepilih="2";
    $printdate= date("d/m/Y");
    $jamnow=date("H:i:s");
    
    $bolehlewat="true";
?>
<html>
    <head>
        <?PHP if ($kodepilih==2) { ?>
            <title>Data Biaya Luar Kota <?PHP echo $printdate." ".$jamnow; ?></title>
        <?PHP }else{ ?>
            <title>Data Biaya Rutin <?PHP echo $printdate." ".$jamnow; ?></title>
        <?PHP } ?>
        <meta http-equiv="Expires" content="Mon, 01 Sep 2018 1:00:00 GMT">
        <meta http-equiv="Pragma" content="no-cache">
        <?php header("Cache-Control: no-cache, must-revalidate"); ?>
        <link rel="shortcut icon" href="../../images/icon.ico" />
        
        
        <!-- jQuery -->
        <script src="vendors/jquery/dist/jquery.min.js"></script>
        <!--input mask -->
        <script src="js/inputmask.js"></script>
        
        <script>
            
            function hit_total(pNilai_,pQty_,pTotal_) {
                nilai = document.getElementById(pNilai_).value;  
                qty = document.getElementById(pQty_).value;

                var newchar = '';
                var mynilai = nilai;  
                mynilai = mynilai.split(',').join(newchar);
                var myqty = qty;  
                myqty = myqty.split(',').join(newchar);

                total_ = mynilai * myqty;
                document.getElementById(pTotal_).value = total_;
                findTotal();


            }
            
            function findTotal(){
                var newchar = '';
                var a1 = document.getElementById('e_total1').value;
                var a2 = document.getElementById('e_total2').value;
                var a3 = document.getElementById('e_total3').value;
                var a4 = document.getElementById('e_total4').value;
                var a5 = document.getElementById('e_total5').value;
                var a6 = document.getElementById('e_total6').value;
                var a7 = document.getElementById('e_total7').value;
                var a8 = document.getElementById('e_total8').value;
                var a9 = document.getElementById('e_total9').value;
                
                a1 = a1.split(',').join(newchar);
                a2 = a2.split(',').join(newchar);
                a3 = a3.split(',').join(newchar);
                a4 = a4.split(',').join(newchar);
                a5 = a5.split(',').join(newchar);
                a6 = a6.split(',').join(newchar);
                a7 = a7.split(',').join(newchar);
                a8 = a8.split(',').join(newchar);
                a9 = a9.split(',').join(newchar);
                if (a1 === "") a1=0; if (a2 === "") a2=0; if (a3 === "") a3=0; if (a4 === "") a4=0;
                if (a5 === "") a5=0; if (a6 === "") a6=0; if (a7 === "") a7=0; if (a8 === "") a8=0;
                if (a9 === "") a9=0;


                tot =parseInt(a1)+parseInt(a2)+parseInt(a3)+parseInt(a4)+parseInt(a5)+parseInt(a6)
                    +parseInt(a7)+parseInt(a8)+parseInt(a9);
                document.getElementById('e_totalsemua').value = tot;
            }
    
            function SimpanData(idrutin, nourut, noid, eqty, qrp, etotal, ealasan, etotsemua) {
                var iidrutin = document.getElementById(idrutin).value;
                var inourut = document.getElementById(nourut).value;
                var inoid = document.getElementById(noid).value;
                var iqty = document.getElementById(eqty).value;
                var irp = document.getElementById(qrp).value;
                var itotal = document.getElementById(etotal).value;
                var ialasan = document.getElementById(ealasan).value;
                var itotsemua = document.getElementById(etotsemua).value;
                
                //alert(iidrutin+", "+inourut+", "+inoid+", "+iqty+", "+irp+", "+itotal+", "+ialasan+", "+itotsemua); return false;
                
                ok_ = 1;
                if (ok_) {
                    var r=confirm("Apakah akan menyimpan data...???")
                    if (r==true) {
                        //document.write("You pressed OK!")
                        var myurl = window.location;
                        var urlku = new URL(myurl);
                        var module = urlku.searchParams.get("module");
                        var idmenu = urlku.searchParams.get("idmenu");

                        $.ajax({
                            type:"post",
                            url:"module/mod_fin_prosbiayaluarkota/simpandataluarkota.php?module=simpandatanya",
                            data:"uidrutin="+iidrutin+"&unourut="+inourut+"&uinoid="+inoid+"&uiqty="+iqty+"&uirp="+irp+"&uitotal="+itotal+"&uialasan="+ialasan+"&uitotsemua="+itotsemua,
                            success:function(data){
                                alert(data);
                            }
                        });
                        
                        return 1;
                    }
                } else {
                    //document.write("You pressed Cancel!")
                    return 0;
                }
            }
            
            function RefreshHalaman() {
                document.location.reload(true);
            }
        </script>
        
        
        <script>
            function printContent(el){
                var restorepage = document.body.innerHTML;
                var printcontent = document.getElementById(el).innerHTML;
                document.body.innerHTML = printcontent;
                window.print();
                document.body.innerHTML = restorepage;
            }
        </script>
        
        <script>
            var EventUtil = new Object;
            EventUtil.formatEvent = function (oEvent) {
                    return oEvent;
            }


            function goto2(pForm_,pPage_) {
               document.getElementById(pForm_).action = pPage_;
               document.getElementById(pForm_).submit();

            }
        </script>
        
        <style>
        @page 
        {
            /*size: auto;   /* auto is the current printer page size */
            /*margin: 0mm;  /* this affects the margin in the printer settings */
            margin-left: 7mm;  /* this affects the margin in the printer settings */
            margin-right: 7mm;  /* this affects the margin in the printer settings */
            margin-top: 5mm;  /* this affects the margin in the printer settings */
            margin-bottom: 5mm;  /* this affects the margin in the printer settings */
            size: portrait;
        }
        </style>
        
        <style>
            body {
                font-family: "Times New Roman", Times, serif;
                font-size: 13px;
                border: 0px solid #000;
            }
            table.example_2 {
                color: #000;
                font-family: Helvetica, Arial, sans-serif;
                width: 100%;
                border-collapse:
                collapse; border-spacing: 0;
                font-size: 11px;
                border: 1px solid #000;
            }

            table.example_2 td, table.example_2 th {
                border: 1px solid #000; /* No more visible border */
                height: 28px;
                transition: all 0.3s;  /* Simple transition for hover effect */
                padding: 5px;
            }

            table.example_2 th {
                background: #DFDFDF;  /* Darken header a bit */
                font-weight: bold;
            }

            table.example_2 td {
                background: #FAFAFA;
            }

            /* Cells in even rows (2,4,6...) are one color */
            tr:nth-child(even) td { background: #F1F1F1; }

            /* Cells in odd rows (1,3,5...) are another (excludes header cells)  */
            tr:nth-child(odd) td { background: #FEFEFE; }

            tr td:hover.biasa { background: #666; color: #FFF; }
            tr td:hover.left { background: #ccccff; color: #000; }

            tr td.center1, td.center2 { text-align: center; }

            tr td:hover.center1 { background: #666; color: #FFF; text-align: center; }
            tr td:hover.center2 { background: #ccccff; color: #000; text-align: center; }
            /* Hover cell effect! */

            table {
                font-family: "Times New Roman", Times, serif;
                font-size: 11px;
            }
            table.tjudul {
                font-size: 13px;
                width: 97%;
            }


            #kotakjudul {
                border: 0px solid #000;
                width:100%;
                height: 1.3cm;
            }
            #isikiri {
                float   : left;
                width   : 49%;
                border-left: 0px solid #000;
            }
            #isikanan {
                text-align: right;
                float   : right;
                width   : 49%;
            }
            h2 {
                font-size: 15px;
            }
            h3 {
                font-size: 20px;
            }
        </style>
    </head>

    <body>
        <div id="div1">
            <?PHP
                include "config/koneksimysqli.php";
                include "config/fungsi_sql.php";
                include "config/library.php";
                $query = "select * from dbmaster.v_brrutin0 where idrutin='$_GET[brid]' order by nama, bulan, nama_area";
                $result = mysqli_query($cnmy, $query);
                $row = mysqli_fetch_array($result);
                $idbr=$row['idrutin'];
                $tglajukan=date("d-m-Y", strtotime($row['tgl']));
                //$tgl_idbr=date("Ymd", strtotime($row['tgl']))."-".(int)$idbr;
                $tgl_idbr=$idbr;
                $pkaryawan=$row['karyawanid'];
                $nama=$row['nama'];
                $namaarea=$row['nama_area'];
                $keterangan=$row['keterangan'];
                
                $phari=date("w", strtotime($row['tgl']));
                $pdate=date("d", strtotime($row['tgl']));
                $pbln=(int)date("m", strtotime($row['tgl']));
                $pthn=date("Y", strtotime($row['tgl']));
                
                $tglpengajuan=$seminggu[$phari]." ".$pdate." ".$nama_bln[$pbln]." ".$pthn;
                
                $phari1=date("w", strtotime($row['periode1']));
                $pdate1=date("d", strtotime($row['periode1']));
                $pbln1=(int)date("m", strtotime($row['periode1']));
                $pthn1=date("Y", strtotime($row['periode1']));
                
                $phari2=date("w", strtotime($row['periode2']));
                $pdate2=date("d", strtotime($row['periode2']));
                $pbln2=(int)date("m", strtotime($row['periode2']));
                $pthn2=date("Y", strtotime($row['periode2']));
                
                //$pp01 =  date("d F Y", strtotime($row['periode1']));
                //$pp02 =  date("d F Y", strtotime($row['periode2']));
                
                $pp01=$pdate1." ".$nama_bln[$pbln1]." ".$pthn1;
                $pp02=$pdate2." ".$nama_bln[$pbln2]." ".$pthn2;
                
                $pdivisi=$row['divisi'];
                
                $pjabatanid=$row['jabatanid'];
                $lvlpengajuan = getfield("select LEVELPOSISI as lcfields from dbmaster.v_level_jabatan where jabatanId='$pjabatanid'");
                
                $query = "SELECT distinct karyawanid, gsm FROM dbmaster.t_karyawan_app_gsm where karyawanid='$pkaryawan'";
                $ketemu= mysqli_num_rows(mysqli_query($cnmy, $query));
                if ($ketemu>0) {
                    $lvlpengajuan="FF4";
                }
                
                $patasan1=$row['atasan1'];
                $nmatasan1 = getfield("select nama as lcfields from hrd.karyawan where karyawanId='$patasan1'");
                $patasan2=$row['atasan2'];
                $nmatasan2 = getfield("select nama as lcfields from hrd.karyawan where karyawanId='$patasan2'");
                $patasan3=$row['atasan3'];
                $nmatasan3 = getfield("select nama as lcfields from hrd.karyawan where karyawanId='$patasan3'");
                $patasan4=$row['atasan4'];
                $nmatasan4 = getfield("select nama as lcfields from hrd.karyawan where karyawanId='$patasan4'");
                
                $gambar=$row['gambar'];
                $gbr1=$row['gbr_atasan1'];
                $gbr2=$row['gbr_atasan2'];
                $gbr3=$row['gbr_atasan3'];
                $gbr4=$row['gbr_atasan4'];
                
                if ($patasan4==$pkaryawan) $gambar=$row['gbr_atasan4'];
                
                $milliseconds = round(microtime(true) * 1000);
                $now_fil=date("mdYhis").$milliseconds;
                
                $namaajkn=$tglajukan;
                $namaspv="";
                $namadm="";
                $namasm="";
                $namagsm="";
                $gmrheight = "80px";
                
                if ($pdivisi=="OTC") {
                    $gambar="";
                    $gbr1="";
                    $gbr2="";
                    $gbr3="";
                    $gbr4="";
                    $lvlpengajuan = "";
                }
                
                if ($lvlpengajuan=="FF6" or $lvlpengajuan=="FF7" or $lvlpengajuan=="FF8" or $lvlpengajuan=="FF9") {
                    $gambar="";
                    $gbr1="";
                    $gbr2="";
                    $gbr3="";
                    $gbr4="";
                    $lvlpengajuan = "";
                }
                
                if (!empty($gambar)) {
                    $data="data:".$gambar;
                    $data=str_replace(' ','+',$data);
                    list($type, $data) = explode(';', $data);
                    list(, $data)      = explode(',', $data);
                    $data = base64_decode($data);
                    $namapengaju="img_".$idbr."PENGAJU_.png";
                    file_put_contents('images/tanda_tangan_base64/'.$namapengaju, $data);
                }
                
                if (!empty($gbr1)) {
                    $data="data:".$gbr1;
                    $data=str_replace(' ','+',$data);
                    list($type, $data) = explode(';', $data);
                    list(, $data)      = explode(',', $data);
                    $data = base64_decode($data);
                    $namaspv="img_".$idbr."SVP_.png";
                    file_put_contents('images/tanda_tangan_base64/'.$namaspv, $data);
                }
                
                if (!empty($gbr2)) {
                    $data="data:".$gbr2;
                    $data=str_replace(' ','+',$data);
                    list($type, $data) = explode(';', $data);
                    list(, $data)      = explode(',', $data);
                    $data = base64_decode($data);
                    $namadm="img_".$idbr."DM_.png";
                    file_put_contents('images/tanda_tangan_base64/'.$namadm, $data);
                }
                
                if (!empty($gbr3)) {
                    $data="data:".$gbr3;
                    $data=str_replace(' ','+',$data);
                    list($type, $data) = explode(';', $data);
                    list(, $data)      = explode(',', $data);
                    $data = base64_decode($data);
                    $namasm="img_".$idbr."SM_.png";
                    file_put_contents('images/tanda_tangan_base64/'.$namasm, $data);
                }
                
                if (!empty($gbr4)) {
                    $data="data:".$gbr4;
                    $data=str_replace(' ','+',$data);
                    list($type, $data) = explode(';', $data);
                    list(, $data)      = explode(',', $data);
                    $data = base64_decode($data);
                    $namagsm="img_".$idbr."SM_.png";
                    file_put_contents('images/tanda_tangan_base64/'.$namagsm, $data);
                }
                    
                

                
$ynow = date("Y");
$myperiode1= explode("-", $row['periode1']);
$ysel="0000";$msel="00";$dsel="00";
if (isset($myperiode1[0])) $ysel=$myperiode1[0];
if (isset($myperiode1[1])) $msel=$myperiode1[1];
if (isset($myperiode1[2])) $dsel=$myperiode1[2];
        
$bln0="";
$bln1="";
$bln2="";
$bln3="";
$bln4="";
$bln5="";
$bln6="";
$bln7="";
$bln8="";
$bln9="";
$bln10="";
$bln11="";
$bln12="";

if ((int)$msel=="00") $bln0="selected";
if ((int)$msel==1) $bln1="selected";
if ((int)$msel==2) $bln2="selected";
if ((int)$msel==3) $bln3="selected";
if ((int)$msel==4) $bln4="selected";
if ((int)$msel==5) $bln5="selected";
if ((int)$msel==6) $bln6="selected";
if ((int)$msel==7) $bln7="selected";
if ((int)$msel==8) $bln8="selected";
if ((int)$msel==9) $bln9="selected";
if ((int)$msel==10) $bln10="selected";
if ((int)$msel==11) $bln11="selected";
if ((int)$msel==12) $bln12="selected";

$ynow2 = date("Y");
$myperiode2= explode("-", $row['periode2']);
$ysel2="0000";$msel2="00";$dsel2="00";
if (isset($myperiode1[0])) $ysel2=$myperiode2[0];
if (isset($myperiode1[1])) $msel2=$myperiode2[1];
if (isset($myperiode1[2])) $dsel2=$myperiode2[2];

$bln20="";
$bln21="";
$bln22="";
$bln23="";
$bln24="";
$bln25="";
$bln26="";
$bln27="";
$bln28="";
$bln29="";
$bln210="";
$bln211="";
$bln212="";

if ((int)$msel2=="00") $bln20="selected";
if ((int)$msel2==1) $bln21="selected";
if ((int)$msel2==2) $bln22="selected";
if ((int)$msel2==3) $bln23="selected";
if ((int)$msel2==4) $bln24="selected";
if ((int)$msel2==5) $bln25="selected";
if ((int)$msel2==6) $bln26="selected";
if ((int)$msel2==7) $bln27="selected";
if ((int)$msel2==8) $bln28="selected";
if ((int)$msel2==9) $bln29="selected";
if ((int)$msel2==10) $bln210="selected";
if ((int)$msel2==11) $bln211="selected";
if ((int)$msel2==12) $bln212="selected";


                
            ?>
            
            <center>
                <img src="images/logo_sdm.jpg" height="70px">
                <h2>PT SURYA DERMATO MEDICA LABORATORIES</h2>
            </center>
            <hr/>
            <center>
                <h3>
                    <?PHP
                    if ($kodepilih==1)
                        echo "BIAYA RUTIN";
                    else
                        echo "BIAYA LUAR KOTA";
                    ?>
                </h3>
            </center>
            <div id="kotakjudul">
                <div id="isikiri">
                    <table class='tjudul' width='100%'>
                        <tr><td>ID</td><td>:</td><td nowrap><?PHP echo "<b>$tgl_idbr</b>"; ?></td></tr>
                        <tr><td>NAMA</td><td>:</td><td nowrap><?PHP echo "$nama"; ?></td></tr>
                        <?PHP
                        if ((int)$pjabatanid!=38 AND (int)$pjabatanid!=10 AND (int)$pjabatanid!=18 AND (int)$pjabatanid!=15 AND (int)$pjabatanid!=20 AND (int)$pjabatanid!=8) {
                        ?>
                        <!--<tr><td>AREA</td><td>:</td><td nowrap><?PHP echo "$namaarea"; ?></td></tr>-->
                        <?PHP
                        }
                        ?>
                        <tr><td>PERIODE</td><td>:</td><td nowrap><?PHP echo "$pp01 - $pp02"; ?></td></tr>
                        <tr><td></td><td></td><td nowrap>

                          <select class="input-sm form-control-sm" name="l_tgl" id="l_tgl">
                              <?PHP
                              for ($t=1;$t<=31;$t++) {
                                  $nntgl=$t;
                                  if ($t<10)$nntgl="0".$t;
                                  if ($t==$dsel)
                                    echo "<option value='$nntgl' selected>$nntgl</option>";
                                  else
                                    echo "<option value='$nntgl'>$nntgl</option>";
                              }
                              ?>
                          </select>

                          <select class="input-sm form-control-sm" name="l_bln" id="l_bln">
                              <?PHP
                                  echo "<option value='01' $bln1>January</option>";
                                  echo "<option value='02' $bln2>Februari</option>";
                                  echo "<option value='03' $bln3>Maret</option>";
                                  echo "<option value='04' $bln4>April</option>";
                                  echo "<option value='05' $bln5>Mei</option>";
                                  echo "<option value='06' $bln6>Juni</option>";
                                  echo "<option value='07' $bln7>Juli</option>";
                                  echo "<option value='08' $bln8>Agustus</option>";
                                  echo "<option value='09' $bln9>September</option>";
                                  echo "<option value='10' $bln10>Oktober</option>";
                                  echo "<option value='11' $bln11>November</option>";
                                  echo "<option value='12' $bln12>Desember</option>";
                              ?>
                          </select>

                          <select class="input-sm form-control-sm" name="l_thn" id="l_thn">
                              <?PHP
                              for ($t=2018;$t<=$ynow;$t++) {
                                  if ($t==$ysel)
                                    echo "<option value='$t' selected>$t</option>";
                                  else
                                    echo "<option value='$t'>$t</option>";
                              }
                              ?>
                          </select>
                                &nbsp; 
                                s/d. 
                                &nbsp; 
                                  <select class="input-sm form-control-sm" name="l_tgl2" id="l_tgl2">
                                      <?PHP
                                      for ($t=1;$t<=31;$t++) {
                                          $nntgl=$t;
                                          if ($t<10)$nntgl="0".$t;
                                          if ($t==$dsel2)
                                            echo "<option value='$nntgl' selected>$nntgl</option>";
                                          else
                                            echo "<option value='$nntgl'>$nntgl</option>";
                                      }
                                      ?>
                                  </select>
                                  
                                  <select class="input-sm form-control-sm" name="l_bln2" id="l_bln2">
                                      <?PHP
                                          echo "<option value='01' $bln21>January</option>";
                                          echo "<option value='02' $bln22>Februari</option>";
                                          echo "<option value='03' $bln23>Maret</option>";
                                          echo "<option value='04' $bln24>April</option>";
                                          echo "<option value='05' $bln25>Mei</option>";
                                          echo "<option value='06' $bln26>Juni</option>";
                                          echo "<option value='07' $bln27>Juli</option>";
                                          echo "<option value='08' $bln28>Agustus</option>";
                                          echo "<option value='09' $bln29>September</option>";
                                          echo "<option value='10' $bln210>Oktober</option>";
                                          echo "<option value='11' $bln211>November</option>";
                                          echo "<option value='12' $bln212>Desember</option>";
                                      ?>
                                  </select>
                                  
                                  <select class="input-sm form-control-sm" name="l_thn2" id="l_thn2">
                                      <?PHP
                                      for ($t=2018;$t<=$ynow2;$t++) {
                                          if ($t==$ysel2)
                                            echo "<option value='$t' selected>$t</option>";
                                          else
                                            echo "<option value='$t'>$t</option>";
                                      }
                                      ?>
                                  </select>
                                
                                
                                    <input type='button' id='btn_savetgl' name='btn_savetgl' value='Save' onclick="simpantanggal('<?PHP echo $idbr; ?>')">
                            </td></tr>
                        <?php
                        if ($kodepilih==2){
                            echo "<tr><td nowrap>KUNJUNGAN KE KOTA</td><td>:</td><td>$keterangan</td></tr>";
                        }
                        echo "<tr><td nowrap>&nbsp;</td><td>&nbsp;</td><td></td></tr>";
                        ?>
                    </table>
                    
                </div>
                <div id="isikanan">
                    
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
            
            <br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;
            <form id="form1">
            <span align="left"><input type="button" name="t_ref" id="t_ref" value="Refresh Halaman" onclick="RefreshHalaman()"></span><br/>&nbsp;
            <table id='example_2' class='table table-striped table-bordered example_2' width="100%" border="1px">
                <tr>
                    <th>No</th>
                    <th nowrap>Akun</th>
                    <th nowrap>Rp</th>
                    <th nowrap>Jumlah (Rp.) Ubah</th>
                    <th nowrap>Alasan Ubah</th>
                    <th></th>
                </tr>
                <tbody class='inputdatauc'>
                <?PHP
                $sudahadahotel=false;
                $myidkosong="";
                $total=0;
                $no=1;
                $nurut=1;
                $tampil = mysqli_query($cnmy, "SELECT nobrid, nama, jumlah, qty FROM dbmaster.t_brid where kode=$kodepilih and aktif='Y' order by nobrid");
                while ($uc=mysqli_fetch_array($tampil)){
                    $myidkosong=$uc['nobrid'];
                    $ada=0;
                    $tjml=1;
                    if (!empty($uc['jumlah'])) $tjml=$uc['jumlah'];
                    
                    if ($_GET["brid"]=="BRT0000036")
                        $cari = mysqli_query($cnmy, "SELECT * FROM dbmaster.t_brrutin1 where idrutin='$_GET[brid]' and nobrid=$uc[nobrid]");
                    else
                        $cari = mysqli_query($cnmy, "SELECT * FROM dbmaster.t_brrutin1 where idrutin='$_GET[brid]' and nobrid='$uc[nobrid]'");
                    
                    $ada = mysqli_num_rows($cari);
                    if ($ada>0) {
                        $xx=0;
                        
                        while ($c=mysqli_fetch_array($cari)){
                            $rptotal=number_format($c['rptotal'],0);
                            $rpnilai=number_format($c['rp'],0);
                            $jmlhari=number_format($c['qty'],0);
                            $satuan="";
                            if ($c['nobrid']=="25") $satuan="($jmlhari x $rpnilai)";
                            if ($c['nobrid']=="21") $sudahadahotel=true;
                            if (!empty($c['rptotal']))
                                $total=$total+$c['rptotal'];
                            
                            $nobridnya=$c['idrutin'];
                            $nurut=$c['nourut'];
                            $nobrid=$c['nobrid'];
                            $alasanedit=$c['alasanedit_fin'];
                            
                            echo "<tr>";
                            echo "<td nowrap>$no</td>";
                            echo "<td nowrap>$uc[nama] $satuan</td>";
                            echo "<td nowrap align='right'>$rptotal</td>";
                            
                            $readon="";
                            $phide="hidden";
                            $px="";
                            if ($nobrid=="25") { $readon="Readonly"; $phide="text"; $px="hari x";}
                            $nmqty="e_qty".$no;
                            $nmrp="e_rp".$no;
                            $nmtot="e_total".$no;
                            $nmalasan="e_alasan".$no;
                            $nmsave="e_save".$no;
                            
                            $nmidrutnya="e_idrutin".$nurut;
                            $nmnourutnya="e_nourut".$nurut;
                            $nmnoidnya="e_noid".$nurut;
                            
                            echo "<td nowrap><input type='$phide' size='1px' id='$nmqty' name='$nmqty' class='input-sm inputmaskrp2' autocomplete='off' onblur=\"hit_total('$nmqty','$nmrp','$nmtot')\" value='$jmlhari'>"
                                    . " $px <input type='$phide' size='10px' id='$nmrp' name='$nmrp' class='input-sm inputmaskrp2' autocomplete='off' onblur=\"hit_total('$nmqty','$nmrp','$nmtot')\" value='$rpnilai'>"
                                    . " <input type='text' size='12px' id='$nmtot' name='$nmtot' class='input-sm inputmaskrp2' autocomplete='off' onblur='findTotal()' value='$rptotal' $readon>"
                                    . "</td>";
                            echo "<td><input type='text' size='30px' id='$nmalasan' name='$nmalasan' class='input-sm' value='$alasanedit'></td>";
                            echo "<td>
                                    <input type='hidden' id='$nmidrutnya' name='$nmidrutnya' class='input-sm' autocomplete='off' value='$nobridnya' Readonly>
                                    <input type='hidden' id='$nmnourutnya' name='$nmnourutnya' class='input-sm' autocomplete='off' value='$nurut' Readonly>
                                    <input type='hidden' id='$nmnoidnya' name='$nmnoidnya' class='input-sm' autocomplete='off' value='$nobrid' Readonly>
                                        
                                    <input type='button' id='$nmsave' name='$nmsave' value='Save' onclick=\"SimpanData('$nmidrutnya', '$nmnourutnya', '$nmnoidnya', '$nmqty', '$nmrp', '$nmtot', '$nmalasan', 'e_totalsemua')\">
                                        
                                    </td>";
                            
                            echo "</tr>"; 
                            $no++;
                            $xx++;
                        }
                        $tjml=(int)$tjml-(int)$xx;
                    }
                    
                    for ($i=1; $i <=$tjml; $i++) {
                        
                        $nmidrutnya="e_idrutin".$no;
                        $nmnourutnya="e_nourut".$no;
                        $nmnoidnya="e_noid".$no;
                        $nmidrutnyakosong="<input type='hidden' id='$nmidrutnya' name='$nmidrutnya' class='input-sm' autocomplete='off' value='$idbr' Readonly>";
                        $nmnourutnyakosong="<input type='hidden' id='$nmnourutnya' name='$nmnourutnya' class='input-sm' autocomplete='off' value='' Readonly>";
                        $nmnoidnyakosong="<input type='hidden' id='$nmnoidnya' name='$nmnoidnya' class='input-sm' autocomplete='off' value='$myidkosong' Readonly>";
                        
                            $readon="";
                            $phide="hidden";
                            $px="";
                            if ($myidkosong=="25") { $readon="Readonly"; $phide="text"; $px="hari x";}
                            $nmqty="e_qty".$no;
                            $nmrp="e_rp".$no;
                            
                        $nmtot="e_total".$no;
                        $nmalasan="e_alasan".$no;
                        $nmsave="e_save".$no;
                        $inputestisi="text";
                        $inputestisibtn="button";
                        if ($sudahadahotel==true) { $inputestisi="hidden"; $inputestisibtn="hidden"; }
                        
                        $nmalasannya="<input type='$inputestisi' size='30px' id='$nmalasan' name='$nmalasan' class='input-sm' value=''>";
                        $nmsavenya="<input type='$inputestisibtn' id='$nmsave' name='$nmsave' value='Save' onclick=\"SimpanData('$nmidrutnya', '$nmnourutnya', '$nmnoidnya', '$nmqty', '$nmrp', '$nmtot', '$nmalasan', 'e_totalsemua')\">";
                        echo "<tr>";
                        echo "<td>$no</td>";
                        echo "<td>$uc[nama]</td>";
                        echo "<td>$nmidrutnyakosong $nmnourutnyakosong $nmnoidnyakosong</td>";
                        echo "<td><input type='$phide' size='1px' id='$nmqty' name='$nmqty' class='input-sm inputmaskrp2' autocomplete='off' onblur=\"hit_total('$nmqty','$nmrp','$nmtot')\" value='1'> "
                                . " $px "
                                . " <input type='$phide' size='10px' id='$nmrp' name='$nmrp' class='input-sm inputmaskrp2' autocomplete='off' onblur=\"hit_total('$nmqty','$nmrp','$nmtot')\" value='0'> "
                                . " <input type='$inputestisi' size='12px' id='$nmtot' name='$nmtot' class='input-sm inputmaskrp2' autocomplete='off' onblur='findTotal()' value='' $readon></td>";
                        echo "<td>$nmalasannya</td>";
                        echo "<td>$nmsavenya</td>";
                        echo "</tr>";
                        $no++;
                        
                        //hotel hanya satu, jika lebih hilangkan atau : $sudahadahotel=true, di atas juga ada
                        if ($myidkosong=="21") $sudahadahotel=true;
                    }
                    $sudahadahotel=false;
                }
                //Total
                $gtotal=number_format($total,0);
                echo "<tr>";
                echo "<td style='border:0px;'></td>";
                echo "<td align='right'>Total  </td>";
                echo "<td align='right'>$gtotal</td>";
                echo "<td><input type='text' id='e_totalsemua' name='e_totalsemua' class='form-control col-md-7 col-xs-12 inputmaskrp2' value='$gtotal' readonly></td>";
                echo "<td align='right'></td>";
                echo "<td align='right'></td>";
                echo "</tr>";
                
                if ($kodepilih==2222) {
                    echo "<tr>";
                    echo "<td style='border:0px;'></td>";
                    echo "<td align='right'>Usulan Uang LK </td>";
                    echo "<td align='right'>Rp. </td>";
                    echo "</tr>";
                    
                    echo "<tr>";
                    echo "<td style='border:0px;'></td>";
                    echo "<td align='right'>Sisa </td>";
                    echo "<td align='right'>Rp. </td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
            </form>
            <br/>&nbsp;
            <?PHP 
                if ($kodepilih==1)
                    echo "Note : $keterangan";
                else{
                    echo "<div align='right'>$tglpengajuan</div>";
                }
                
            ?>
            <br/>&nbsp;<br/>&nbsp;
            <center>
                <table class='tjudul' width='100%'>
                    <?PHP
                    if ($lvlpengajuan=="FF1") {
                        echo '
                        <tr>
                            <td align="center">
                                Disetujui NSM/AMD :
                                <br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;
                                (.............................)
                            </td>
                            <td align="center">
                                Diperiksa oleh SM :';
                                if (!empty($namasm))
                                    echo "<br/><img src='images/tanda_tangan_base64/$namasm' height='$gmrheight'><br/>";
                                else
                                    echo "<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;";
                                echo "<b><u>$nmatasan3</u></b>";
                        echo '</td>
                            <td align="center">
                                Diperiksa oleh DM :';
                                if (!empty($namadm))
                                    echo "<br/><img src='images/tanda_tangan_base64/$namadm' height='$gmrheight'><br/>";
                                else
                                    echo "<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;";
                                echo "<b><u>$nmatasan2</u></b>";
                        echo '</td>
                            <td align="center">
                                Diperiksa oleh Atasan :';
                                if (!empty($namaspv))
                                    echo "<br/><img src='images/tanda_tangan_base64/$namaspv' height='$gmrheight'><br/>";
                                else
                                    echo "<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;";
                                echo "<b><u>$nmatasan1</u></b>";
                        echo '</td>
                            <td align="center">
                                Yang Membuat :';
                                if (!empty($namapengaju))
                                    echo "<br/><img src='images/tanda_tangan_base64/$namapengaju' height='$gmrheight'><br/>";
                                else
                                    echo "<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;";
                                echo "<b><u>$nama</u></b>";
                        echo '</td>
                        </tr>
                        ';
                    }elseif ($lvlpengajuan=="FF2" OR $lvlpengajuan=="AD1") {
                        echo '
                        <tr>
                            <td align="center">
                                Disetujui NSM/AMD :
                                <br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;
                                (.............................)
                            </td>
                            <td align="center">
                                Diperiksa oleh SM :';
                                if (!empty($namasm))
                                    echo "<br/><img src='images/tanda_tangan_base64/$namasm' height='$gmrheight'><br/>";
                                else
                                    echo "<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;";
                                echo "<b><u>$nmatasan3</u></b>";
                        echo '</td>
                            <td align="center">
                                Diperiksa oleh Atasan :';
                                if (!empty($namadm))
                                    echo "<br/><img src='images/tanda_tangan_base64/$namadm' height='$gmrheight'><br/>";
                                else
                                    echo "<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;";
                                echo "<b><u>$nmatasan2</u></b>";
                        echo '</td>
                            <td align="center">
                                Yang Membuat :';
                                if (!empty($namapengaju))
                                    echo "<br/><img src='images/tanda_tangan_base64/$namapengaju' height='$gmrheight'><br/>";
                                else
                                    echo "<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;";
                                echo "<b><u>$nama</u></b>";
                        echo '</td>
                        </tr>
                        ';
                    }elseif ($lvlpengajuan=="FF3") {
                        echo '
                        <tr>
                            <td align="center">
                                Disetujui NSM/AMD :
                                <br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;
                                (.............................)
                            </td>
                            <td align="center">
                                Diperiksa oleh Atasan :';
                                if (!empty($namasm))
                                    echo "<br/><img src='images/tanda_tangan_base64/$namasm' height='$gmrheight'><br/>";
                                else
                                    echo "<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;";
                                echo "<b><u>$nmatasan3</u></b>";
                        echo '</td>
                            <td align="center">
                                Yang Membuat :';
                                if (!empty($namapengaju))
                                    echo "<br/><img src='images/tanda_tangan_base64/$namapengaju' height='$gmrheight'><br/>";
                                else
                                    echo "<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;";
                                echo "<b><u>$nama</u></b>";
                        echo '</td>
                        </tr>
                        ';
                    }elseif ($lvlpengajuan=="FF4") {
                        echo '
                        <tr>
                            <td align="center">
                                Menyetujui :
                                <br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;
                                (.............................)
                            </td>
                            <td align="center">
                                Diperiksa oleh Atasan :';
                                if (!empty($namagsm))
                                    echo "<br/><img src='images/tanda_tangan_base64/$namagsm' height='$gmrheight'><br/>";
                                else
                                    echo "<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;";
                                if (!empty($nmatasan4))
                                    echo "<b><u>$nmatasan4</u></b>";
                                else
                                    echo "(.............................)";
                        echo '</td>
                            <td align="center">
                                Yang Membuat :';
                                if (!empty($namapengaju))
                                    echo "<br/><img src='images/tanda_tangan_base64/$namapengaju' height='$gmrheight'><br/>";
                                else
                                    echo "<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;";
                                echo "<b><u>$nama</u></b>";
                        echo '</td>
                        </tr>
                        ';
                    }else{
                        echo '
                        <tr>
                            <td align="center">
                                Menyetujui :
                                <br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;
                                (.................................)
                            </td>
                            <td align="center">
                                Mengetahui :
                                <br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;
                                (.................................)
                            </td>
                            <td align="center">
                                Yang Membuat :';
                                if (!empty($namapengaju))
                                    echo "<br/><img src='images/tanda_tangan_base64/$namapengaju' height='$gmrheight'><br/>";
                                else
                                    echo "<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>&nbsp;";
                                echo "<b><u>$nama</u></b>";
                        echo '</td>
                        </tr>
                        ';
                    }
                    ?>
                </table>
            </center>
        </div>
        
        
        <!-- jquery.inputmask -->
        <script src="vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
        <script>
            function simpantanggal(irutin){
                var iday1 = document.getElementById('l_tgl').value;
                var imon1 = document.getElementById('l_bln').value;
                var iyear1 = document.getElementById('l_thn').value;
                
                var iday2 = document.getElementById('l_tgl2').value;
                var imon2 = document.getElementById('l_bln2').value;
                var iyear2 = document.getElementById('l_thn2').value;
                
                //alert(irutin+", Ada "+iday1+", "+imon1+", "+iyear1+" yang ke 2 : "+iday2+", "+imon2+", "+iyear2);
                
                ok_ = 1;
                if (ok_) {
                    var r=confirm("Apakah akan menyimpan data...???")
                    if (r==true) {
                        //document.write("You pressed OK!")
                        var myurl = window.location;
                        var urlku = new URL(myurl);
                        var module = urlku.searchParams.get("module");
                        var idmenu = urlku.searchParams.get("idmenu");

                        $.ajax({
                            type:"post",
                            url:"module/mod_fin_prosbiayaluarkota/simpandataluarkota.php?module=simpandataperiode",
                            data:"uidrutin="+irutin+"&utgl1="+iday1+"&umon1="+imon1+"&uyear1="+iyear1+"&utgl2="+iday2+"&umon2="+imon2+"&uyear2="+iyear2,
                            success:function(data){
                                alert(data);
                            }
                        });
                        
                        return 1;
                    }
                } else {
                    //document.write("You pressed Cancel!")
                    return 0;
                }
                
            }
        </script>
    </body>
</html>
