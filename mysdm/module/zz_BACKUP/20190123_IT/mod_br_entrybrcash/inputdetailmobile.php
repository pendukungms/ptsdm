<?PHP
$nourutid = "";
$noidurut = "";
$no=1;
$tampil = mysqli_query($cnit, "SELECT nobrid, nama, jumlah, qty, groupid FROM dbmaster.t_brid where kode=3 and aktif='Y' order by nobrid");
while ($uc=mysqli_fetch_array($tampil)){
    $ada=0;
    $tjml=1;
    if (!empty($uc['jumlah'])) $tjml=$uc['jumlah'];
    $tgpid=$uc['groupid'];
    $namaakun=$uc['nama'];
    
    for ($i=1; $i <=$tjml; $i++) {
        $nakunnya=$namaakun;
        if ($tjml>1) $nakunnya =$nakunnya." #".$i;
        
        $qtyhide="hidden";
        $qtyreadony="";
        $qtyjml="";
        if (!empty($uc['qty']) AND $uc['qty']==1) { $qtyhide="date"; $qtyjml=""; $qtyreadony=""; }

        $uqyu="";$unilai="";$unote="";$unote="";$total="";
        $nmidbl="e_idbl".$no; $nmnama="e_blnama".$no; $nmqty="e_qty".$no; $nmnilai="e_nilai".$no;
        $nmtotal="e_total".$no; $nmnote="e_note".$no;
        $uqyu=$qtyjml;
        
        if ($_GET['act']=="editdata"){
            if (!empty($nourutid)) {
                $noidurut = "AND nourut not in ($nourutid)";
            }
            $cari = mysqli_query($cnit, "SELECT * FROM dbmaster.t_ca1 where idca='$_GET[id]' and nobrid='$uc[nobrid]' $noidurut order by nourut");
            $ada = mysqli_num_rows($cari);
            if ($ada>0) {
                $c = mysqli_fetch_array($cari);
                $enour=$c['nourut'];
                $uqyu=$c['qty'];
                $unilai=$c['rp'];
                $unote=$c['notes'];

                $qtyjml=1;
                if (!empty($uc['qty']) AND $uc['qty']==1) { $qtyhide="date"; $qtyjml=""; $qtyreadony=""; }
                $uqyu="";$unilai="";$unote="";$unote="";$total="";
                $nmidbl="e_idbl".$no; $nmnama="e_blnama".$no; $nmqty="e_qty".$no; $nmnilai="e_nilai".$no;
                $nmtotal="e_total".$no; $nmnote="e_note".$no;

                $uqyu = date('Y-m-d', strtotime($c['tgl1']));
                $unilai = date('Y-m-d', strtotime($c['tgl2']));

                if (empty($c['tgl1']) OR $c['tgl1']=="0000-00-00") $uqyu="";
                if (empty($c['tgl2']) OR $c['tgl2']=="0000-00-00") $unilai="";

                //$uqyu=$c['tgl1'];
                //$unilai=$c['tgl2'];
                $unote=$c['notes'];
                $total=  $c['rptotal'];
                
                
                
                if (!empty($nourutid))
                    $nourutid = $nourutid.",".$enour;
                else
                    $nourutid = $enour;
            }
        }
        
        echo "<div>";
        echo "<div hidden><input type='text' name='$nmidbl' id='$nmidbl' value='$uc[nobrid]'>"
                . "<input type='text' id='$nmnama' name='$nmnama' class='input-sm' autocomplete='off' value='$uc[nama]'>"
                . "</div>";
        echo "<b>$nakunnya</b>";
        echo "<table id='tabelnobr' class='datatable table nowrap table-striped table-bordered' width='100%'>";
        
        $inputqty = "<input type='$qtyhide' size='15px' id='$nmqty' name='$nmqty' class='input-sm' autocomplete='off' value='$uqyu'>";
        $inputnilai = "<input type='$qtyhide' size='15px' id='$nmnilai' name='$nmnilai' class='input-sm' autocomplete='off' value='$unilai'>";
        if ($qtyhide!="hidden") {
            echo "<tr><td nowrap>Tanggal</td><td>$inputqty s/d. $inputnilai</td></tr>";
        }else{
            echo "<div hidden>$inputqty &nbsp; $inputnilai</div>";
        }
        $inputtotal = "<input type='text' size='10px' id='$nmtotal' name='$nmtotal' onblur='findTotal()' class='input-sm inputmaskrp2' autocomplete='off' value='$total' $qtyreadony>";
        $inputnote = "<input type='text' size='35px' id='$nmnote' name='$nmnote' class='input-sm' autocomplete='off' value='$unote'>";
        echo "<tr><td nowrap>Total (Rp.)</td><td>$inputtotal</td></tr>";
        echo "<tr><td nowrap>Note</td><td>$inputnote</td></tr>";
        
        echo "</table>";
        echo "</div>";
        
        $no++;
    }
}
echo "<input type='hidden' name='num_records' id='num_records' value='$no'>";
?>