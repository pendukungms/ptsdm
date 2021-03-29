<?PHP
session_start();
$pmodule="";
if (isset($_GET['module'])) $pmodule=$_GET['module'];



if ($pmodule=="cekdatasudahada") {
    include "../../config/koneksimysqli.php";

    $pidinput=$_POST['uid'];
    $ptgl=$_POST['utgl'];
    $pkaryawanid=$_POST['ukaryawan'];

    $ptanggal= date("Y-m-d", strtotime($ptgl));

    $boleh="boleh";

    $query = "select tanggal from hrd.dkd_new0 where idinput<>'$pidinput' AND tanggal='$ptanggal' And karyawanid='$pkaryawanid'";
    $tampil=mysqli_query($cnmy, $query);
    $ketemu=mysqli_num_rows($tampil);
    if ((INT)$ketemu>0) {
        $boleh="Tanggal tersebut sudah ada..., silakan pilih tanggal yang lain";
    }

    mysqli_close($cnmy);

    echo $boleh;

}elseif ($pmodule=="viewdatadoktercabang") {
    include "../../config/koneksimysqli.php";
    $pidcab=$_POST['uidcab'];
    $pkodeid=$_POST['skode'];
    $pcabpilih=$_POST['ukdcab'];
    $pdoktpilih=$_POST['ukddokt'];

    $pkodecabang=$pidcab;
    if ((INT)$pkodeid==2) $pkodecabang=$pcabpilih;

    $query = "select `id` as iddokter, namalengkap, gelar, spesialis from dr.masterdokter WHERE 1=1 ";
    $query .=" AND icabangid='$pkodecabang' ";
    $query .=" order by namalengkap, `id`";
    $tampilket= mysqli_query($cnmy, $query);
    $ketemu=mysqli_num_rows($tampilket);
    if ((INT)$ketemu<=0) echo "<option value='' selected>-- Pilih --</option>";
    while ($du= mysqli_fetch_array($tampilket)) {
        $niddokt=$du['iddokter'];
        $nnmdokt=$du['namalengkap'];
        $ngelar=$du['gelar'];
        $nspesial=$du['spesialis'];
        if ($niddokt==$pdoktpilih)
            echo "<option value='$niddokt' selected>$nnmdokt ($ngelar), $nspesial</option>";
        else
            echo "<option value='$niddokt'>$nnmdokt ($ngelar), $nspesial</option>";

    }
    mysqli_close($cnmy);
}elseif ($pmodule=="viewdatakaryawancabjbt") {
    include "../../config/koneksimysqli.php";
    $pidcab=$_POST['uidcab'];
    $pidjbt=$_POST['uidjbt'];

    $pidkaryawan=$_SESSION['IDCARD'];
    $pidjabatan=$_SESSION['JABATANID'];
    $pidgroup=$_SESSION['GROUP'];
    $query_kry="";

    if (empty($pidcab)) {
        include "../../config/fungsi_sql.php";

        $pfilterkaryawan="";
        $pfilterkaryawan2="";
        $pfilterkry="";
        if ($pidjabatan=="38" OR $pidjabatan=="33" OR $pidjabatan=="05" OR $pidjabatan=="20" OR $pidjabatan=="08" OR $pidjabatan=="10" OR $pidjabatan=="18" OR $pidjabatan=="15") {

            $pnregion="";
            if ($pidkaryawan=="0000000159") $pnregion="T";
            elseif ($pidkaryawan=="0000000158") $pnregion="B";
            $pfilterkry=CariDataKaryawanByCabJbt2($pidkaryawan, $pidjabatan, $pnregion);

            if (!empty($pfilterkry)) {
                $parry_kry= explode(" | ", $pfilterkry);
                if (isset($parry_kry[0])) $pfilterkaryawan=TRIM($parry_kry[0]);
                if (isset($parry_kry[1])) $pfilterkaryawan2=TRIM($parry_kry[1]);
            }

        }


        if ($pidgroup=="1" OR $pidgroup=="24") {
            $query_kry = "select karyawanId as karyawanid, nama as nama 
                FROM hrd.karyawan WHERE 1=1 ";
            $query_kry .= " AND (IFNULL(tglkeluar,'0000-00-00')='0000-00-00' OR IFNULL(tglkeluar,'')='') ";
            $query_kry .=" AND LEFT(nama,4) NOT IN ('NN -', 'DR -', 'DM -', 'BDG ', 'OTH.', 'TO. ', 'BGD-', 'JKT ', 'MR -', 'MR S')  "
                    . " and LEFT(nama,7) NOT IN ('NN DM - ', 'MR SBY1')  "
                    . " and LEFT(nama,3) NOT IN ('TO.', 'TO-', 'DR ', 'DR-', 'JKT', 'NN-', 'TO ') "
                    . " AND LEFT(nama,5) NOT IN ('OTH -', 'NN AM', 'NN DR', 'TO - ', 'SBY -', 'RS. P') "
                    . " AND LEFT(nama,6) NOT IN ('SBYTO-', 'MR SBY') ";
            
        }else{
            $query_kry = "select karyawanId as karyawanid, nama as nama 
                FROM hrd.karyawan WHERE karyawanId IN $pfilterkaryawan ";
        }
        
        if (!empty($pidjbt)) {
            if ($pidjbt=="10")
                $query_kry .=" And jabatanId IN ('10', '18')";
            else
                $query_kry .=" And jabatanId='$pidjbt'";
        }

        $query_kry .=" ORDER BY nama";
        //echo "<option value='' selected>$pidjabatan | $pfilterkaryawan</option>";
    }else{

        if ($pidjabatan=="15") {
            $query_kry = "select karyawanId as karyawanid, nama as nama from hrd.karyawan WHERE karyawanid='$pidkaryawan'";
        }elseif ($pidjabatan=="10" OR $pidjabatan=="18") {
            if ($pidjbt=="10") {
                $query_kry = "select karyawanId as karyawanid, nama as nama from hrd.karyawan WHERE karyawanid='$pidkaryawan'";
            }elseif ($pidjbt=="15") {
                $query_kry = "select distinct a.karyawanid as karyawanid, c.nama as nama 
                    from mkt.imr0 as a JOIN mkt.ispv0 as b on a.icabangid=b.icabangid 
                    AND a.areaid=b.areaid and a.divisiid=b.divisiid 
                    JOIN hrd.karyawan as c on a.karyawanid=c.karyawanId 
                    WHERE b.karyawanid='$pidkaryawan' AND a.icabangid='$pidcab'";
                $query_kry .=" Order by c.nama";
            }else{
                $query_kry = "select distinct a.karyawanid FROM mkt.imr0 as a 
                    LEFT JOIN mkt.ispv0 as b on a.icabangid=b.icabangid 
                    AND a.areaid=b.areaid and a.divisiid=b.divisiid 
                    WHERE b.karyawanid='$pidkaryawan' AND a.icabangid='$pidcab'
                    UNION
                    select distinct karyawanid FROm mkt.ispv0 WHERE 
                    karyawanid='$pidkaryawan' AND icabangid='$pidcab'";
                $query_kry = "select a.karyawanid as karyawanid, b.nama as nama 
                    from ($query_kry) as a
                    JOIN hrd.karyawan as b on a.karyawanid=b.karyawanId";
                $query_kry .=" Order by b.nama";
            }
            
        }elseif ($pidjabatan=="08") {
            if ($pidjbt=="08") {
                $query_kry = "select karyawanId as karyawanid, nama as nama from hrd.karyawan WHERE karyawanid='$pidkaryawan'";
            }elseif ($pidjbt=="15") {
                $query_kry = "select distinct a.karyawanid as karyawanid, c.nama as nama 
                    from mkt.imr0 as a JOIN mkt.idm0 as b on a.icabangid=b.icabangid 
                    JOIN hrd.karyawan as c on a.karyawanid=c.karyawanId 
                    WHERE b.karyawanid='$pidkaryawan' AND a.icabangid='$pidcab'";
                $query_kry .=" Order by c.nama";
            }elseif ($pidjbt=="10") {
                $query_kry = "select distinct a.karyawanid as karyawanid, c.nama as nama 
                    from mkt.ispv0 as a JOIN mkt.idm0 as b on a.icabangid=b.icabangid 
                    JOIN hrd.karyawan as c on a.karyawanid=c.karyawanId 
                    WHERE b.karyawanid='$pidkaryawan' AND a.icabangid='$pidcab'";
                $query_kry .=" Order by c.nama";
            }else{
                $query_kry = "select distinct a.karyawanid FROM mkt.imr0 as a 
                    LEFT JOIN mkt.idm0 as b on a.icabangid=b.icabangid 
                    WHERE b.karyawanid='$pidkaryawan' AND a.icabangid='$pidcab'
                    UNION
                    select distinct a.karyawanid FROM mkt.ispv0 as a 
                    LEFT JOIN mkt.idm0 as b on a.icabangid=b.icabangid 
                    WHERE b.karyawanid='$pidkaryawan' AND a.icabangid='$pidcab'
                    UNION
                    select distinct karyawanid FROm mkt.idm0 WHERE 
                    karyawanid='$pidkaryawan' AND icabangid='$pidcab'";
                $query_kry = "select a.karyawanid as karyawanid, b.nama as nama 
                    from ($query_kry) as a
                    JOIN hrd.karyawan as b on a.karyawanid=b.karyawanId";
                $query_kry .=" Order by b.nama";
            }
        }elseif ($pidjabatan=="20") {
            if ($pidjbt=="20") {
                $query_kry = "select karyawanId as karyawanid, nama as nama from hrd.karyawan WHERE karyawanid='$pidkaryawan'";
            }elseif ($pidjbt=="15") {
                $query_kry = "select distinct a.karyawanid as karyawanid, c.nama as nama 
                    from mkt.imr0 as a JOIN mkt.ism0 as b on a.icabangid=b.icabangid 
                    JOIN hrd.karyawan as c on a.karyawanid=c.karyawanId 
                    WHERE b.karyawanid='$pidkaryawan' AND a.icabangid='$pidcab'";
                $query_kry .=" Order by c.nama";
            }elseif ($pidjbt=="10") {
                $query_kry = "select distinct a.karyawanid as karyawanid, c.nama as nama 
                    from mkt.ispv0 as a JOIN mkt.ism0 as b on a.icabangid=b.icabangid 
                    JOIN hrd.karyawan as c on a.karyawanid=c.karyawanId 
                    WHERE b.karyawanid='$pidkaryawan' AND a.icabangid='$pidcab'";
                $query_kry .=" Order by c.nama";
            }elseif ($pidjbt=="08") {
                $query_kry = "select distinct a.karyawanid as karyawanid, c.nama as nama 
                    from mkt.idm0 as a JOIN mkt.ism0 as b on a.icabangid=b.icabangid 
                    JOIN hrd.karyawan as c on a.karyawanid=c.karyawanId 
                    WHERE b.karyawanid='$pidkaryawan' AND a.icabangid='$pidcab'";
                $query_kry .=" Order by c.nama";
            }else{
                $query_kry = "select distinct a.karyawanid FROM mkt.imr0 as a 
                    LEFT JOIN mkt.ism0 as b on a.icabangid=b.icabangid 
                    WHERE b.karyawanid='$pidkaryawan' AND a.icabangid='$pidcab'
                    UNION
                    select distinct a.karyawanid FROM mkt.ispv0 as a 
                    LEFT JOIN mkt.ism0 as b on a.icabangid=b.icabangid 
                    WHERE b.karyawanid='$pidkaryawan' AND a.icabangid='$pidcab'
                    UNION
                    select distinct karyawanid FROm mkt.ism0 WHERE 
                    karyawanid='$pidkaryawan' AND icabangid='$pidcab'";
                $query_kry = "select a.karyawanid as karyawanid, b.nama as nama 
                    from ($query_kry) as a
                    JOIN hrd.karyawan as b on a.karyawanid=b.karyawanId";
                $query_kry .=" Order by b.nama";
            }
        }elseif ($pidjabatan=="05") {
            $pfregion="XXX";
            if ((INT)$pidkaryawan==158) $pfregion="B";
            elseif ((INT)$pidkaryawan==159) $pfregion="T";

            if ($pidjbt=="05") {
                $query_kry = "select karyawanId as karyawanid, nama as nama from hrd.karyawan WHERE karyawanid='$pidkaryawan'";
            }elseif ($pidjbt=="15") {
                $query_kry = "select distinct a.karyawanid as karyawanid, c.nama as nama 
                    from mkt.imr0 as a JOIN mkt.icabang as b on a.icabangid=b.icabangid 
                    JOIN hrd.karyawan as c on a.karyawanid=c.karyawanId 
                    WHERE b.region='$pfregion' AND a.icabangid='$pidcab'";
                $query_kry .=" Order by c.nama";
            }elseif ($pidjbt=="10") {
                $query_kry = "select distinct a.karyawanid as karyawanid, c.nama as nama 
                    from mkt.ispv0 as a JOIN mkt.icabang as b on a.icabangid=b.icabangid 
                    JOIN hrd.karyawan as c on a.karyawanid=c.karyawanId 
                    WHERE b.region='$pfregion' AND a.icabangid='$pidcab'";
                $query_kry .=" Order by c.nama";
            }elseif ($pidjbt=="08") {
                $query_kry = "select distinct a.karyawanid as karyawanid, c.nama as nama 
                    from mkt.idm0 as a JOIN mkt.icabang as b on a.icabangid=b.icabangid 
                    JOIN hrd.karyawan as c on a.karyawanid=c.karyawanId 
                    WHERE b.region='$pfregion' AND a.icabangid='$pidcab'";
                $query_kry .=" Order by c.nama";
            }elseif ($pidjbt=="20") {
                $query_kry = "select distinct a.karyawanid as karyawanid, c.nama as nama 
                    from mkt.ism0 as a JOIN mkt.icabang as b on a.icabangid=b.icabangid 
                    JOIN hrd.karyawan as c on a.karyawanid=c.karyawanId 
                    WHERE b.region='$pfregion' AND a.icabangid='$pidcab'";
                $query_kry .=" Order by c.nama";
            }
        }else{
            if ($pidjbt=="05") {
                $query_kry = "select karyawanId as karyawanid, nama as nama from hrd.karyawan WHERE karyawanid='$pidkaryawan'";
            }elseif ($pidjbt=="15") {
                $query_kry = "select distinct a.karyawanid as karyawanid, c.nama as nama 
                    from mkt.imr0 as a JOIN mkt.icabang as b on a.icabangid=b.icabangid 
                    JOIN hrd.karyawan as c on a.karyawanid=c.karyawanId 
                    WHERE a.icabangid='$pidcab'";
                $query_kry .=" Order by c.nama";
            }elseif ($pidjbt=="10") {
                $query_kry = "select distinct a.karyawanid as karyawanid, c.nama as nama 
                    from mkt.ispv0 as a JOIN mkt.icabang as b on a.icabangid=b.icabangid 
                    JOIN hrd.karyawan as c on a.karyawanid=c.karyawanId 
                    WHERE a.icabangid='$pidcab'";
                $query_kry .=" Order by c.nama";
            }elseif ($pidjbt=="08") {
                $query_kry = "select distinct a.karyawanid as karyawanid, c.nama as nama 
                    from mkt.idm0 as a JOIN mkt.icabang as b on a.icabangid=b.icabangid 
                    JOIN hrd.karyawan as c on a.karyawanid=c.karyawanId 
                    WHERE a.icabangid='$pidcab'";
                $query_kry .=" Order by c.nama";
            }elseif ($pidjbt=="20") {
                $query_kry = "select distinct a.karyawanid as karyawanid, c.nama as nama 
                    from mkt.ism0 as a JOIN mkt.icabang as b on a.icabangid=b.icabangid 
                    JOIN hrd.karyawan as c on a.karyawanid=c.karyawanId 
                    WHERE a.icabangid='$pidcab'";
                $query_kry .=" Order by c.nama";
            }else{
                $query_kry = "select distinct a.karyawanid FROM mkt.imr0 as a 
                    LEFT JOIN mkt.icabang as b on a.icabangid=b.icabangid 
                    WHERE a.icabangid='$pidcab'
                    UNION
                    select distinct a.karyawanid FROM mkt.ispv0 as a 
                    LEFT JOIN mkt.icabang as b on a.icabangid=b.icabangid 
                    WHERE a.icabangid='$pidcab'
                    UNION
                    select distinct a.karyawanid FROM mkt.idm0 as a 
                    LEFT JOIN mkt.icabang as b on a.icabangid=b.icabangid 
                    WHERE a.icabangid='$pidcab'
                    UNION
                    select distinct karyawanid FROm mkt.ism0 WHERE 
                    icabangid='$pidcab'";
                $query_kry = "select a.karyawanid as karyawanid, b.nama as nama 
                    from ($query_kry) as a
                    JOIN hrd.karyawan as b on a.karyawanid=b.karyawanId";
                $query_kry .=" Order by b.nama";
            }
        }

    }

    if (!empty($query_kry)) {
    $tampilket= mysqli_query($cnmy, $query_kry);
    $ketemu=mysqli_num_rows($tampilket);
    if ((INT)$ketemu<=0) echo "<option value='' selected>-- Pilih --</option>";
    
    
        while ($du= mysqli_fetch_array($tampilket)) {
            $pkaryid=$du['karyawanid'];
            $pkarynm=$du['nama'];
            $pkryid=(INT)$pkaryid;
            
            if ($pkaryid==$pidkaryawan)
                echo "<option value='$pkaryid' selected>$pkarynm ($pkryid)</option>";
            else
                echo "<option value='$pkaryid'>$pkarynm ($pkryid)</option>";

        }

    }else{
        echo "<option value='' selected>-- Pilih --</option>";
    }

    mysqli_close($cnmy);
}
?>