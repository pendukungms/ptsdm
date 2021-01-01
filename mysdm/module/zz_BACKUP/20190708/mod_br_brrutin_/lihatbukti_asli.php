<?PHP
    $printdate= date("d_m_Y");
    $jamnow=date("H_i_s");
    $kodepilih=1;
    if (isset($_GET['brid'])) {
        $noidnya= substr($_GET['brid'],0,3);
        if (trim($noidnya=="BLK")) $kodepilih=2;
    }
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
        
        <style>
        .zoomimags {
          padding: 50px;
          transition: transform .2s; /* Animation */
          margin: 0 auto;
        }

        .zoomimags:hover {
          transform: scale(2.5); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        }
        </style>
    </head>

    <body>
        <?PHP
            include "config/koneksimysqli.php";
            include_once("config/common.php");
            $judulnya="DATA BUKTI BIAYA RUTIN";
            if ($kodepilih==2) $judulnya="DATA BUKTI BIAYA LUAR KOTA";
        ?>
        <div id="div1">
            <center><h2><u><?PHP echo "$judulnya"; ?>, ID : <b><?PHP echo "$_GET[brid]"; ?></b></u></h2></center>
            
                    
                    <?PHP
                    $query = "select * from dbimages.img_brrutin1 where idrutin='$_GET[brid]'";
                    $tampil= mysqli_query($cnmy, $query);
                    $ketemu=mysqli_num_rows($tampil);
                    if ($ketemu>0) {
                        $no=1;
                        while ($i= mysqli_fetch_array($tampil)) {
                            $idgam=$i['nourut'];
                            $gambar=$i['gambar'];
            
                            $gambar2=$i['gambar2'];

                            if (!empty($gambar2)) {
                                $data="data:".$gambar2;
                                $data=str_replace(' ','+',$data);
                                list($type, $data) = explode(';', $data);
                                list(, $data)      = explode(',', $data);
                                $data = base64_decode($data);
                                $namapengaju="img_".$no."".$idgam."IDRUTXB_.png";
                                file_put_contents('images/tanda_tangan_base64/'.$namapengaju, $data);
                            }
                            
                            $lihat ="<a title='Lihat Gambar' href='#' class='btn btn-success btn-xs' data-toggle='modal' "
                                . "onClick=\"window.open('eksekusi3.php?module=$_GET[module]&id=$idgam&iprint=lihatgambar',"
                                . "'Ratting','width=700,height=500,left=500,top=100,scrollbars=yes,toolbar=yes,status=1,pagescrool=yes')\"> "
                                . "Lihat</a>";
                            echo "<div class='col-sm-2'><div class='form-group'>";
                            if (empty($gambar2))
                                echo '<img class="zoomimag" src="data:image/jpeg;base64,'.base64_encode( $gambar ).'" class="img-thumnail"/><br/>&nbsp;<br/>&nbsp;';
                            else
                                echo "<img class='zoomimag' src='images/tanda_tangan_base64/$namapengaju' class='img-thumnail'>";
                            //echo "<br/>$lihat";
                            //echo "<input type='button' class='btn btn-danger btn-xs' name='bhapus' value='Hapus' onclick=\"disp_confirm('Hapus ?', 'hapusgambar&idgam=$idgam&id=$_GET[brid]')\">";
                            echo "</div></div>";
                            $no++;
                        }
                    }
                    ?>
            
            
        </div>
    </body>
</html>