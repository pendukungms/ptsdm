
<?php
    date_default_timezone_set('Asia/Jakarta');
    if ($_GET['module']=='lapbrtransotc'){
        include 'module/lap_br_otctrans/aksi_brotctrans.php';
    }elseif ($_GET['module']=='lapbrotctransfhari'){
        include 'module/lap_br_otctranshari/aksi_brotctranshari.php';
    }elseif ($_GET['module']=='lapbrotcpermo'){
        if ($_GET['ket']=="excel")
            include 'module/lap_br_otcpermohonan/aksi_brotcpermo_excel.php';//aksi_brotcpermo_excel.php
        else
            include 'module/lap_br_otcpermohonan/aksi_brotcpermo.php';
    }elseif ($_GET['module']=='lapbrinputsbyotc'){
        include 'module/lap_br_otcinputsby/aksi_otcinputsby.php';
    }elseif ($_GET['module']=='lapslscabdiv'){
        if ($_POST['rb_rpttipe']=='M')
            include 'module/a_sales/sls_cabang_divisi/index.php';
        elseif ($_POST['rb_rpttipe']=='Y')
            include 'module/a_sales/sls_cabang_divisi_ytd/index.php';
    }elseif ($_GET['module']=='lapslsproddiv'){
        if ($_POST['rb_rpttipe']=='M')
            include 'module/a_sales/sls_cabang_divisi_unit/index.php';
        elseif ($_POST['rb_rpttipe']=='Y')
            include 'module/a_sales/sls_cabang_divisi_unit_ytd/index.php';
            
            
    }elseif ($_GET['module']=='lapslscabdivams'){
        if ($_POST['rb_rpttipe']=='M')
            include 'module/a_sales/sls_cabang_divisi_dist/index.php';
        elseif ($_POST['rb_rpttipe']=='Y')
            include 'module/a_sales/sls_cabang_divisi_dist_ytd/index.php';
        
        
    }elseif ($_GET['module']=='lapslsproddivams'){
        if ($_POST['rb_rpttipe']=='M')
            include 'module/a_sales/sls_cabang_divisi_dist_unit/index.php';
        elseif ($_POST['rb_rpttipe']=='Y')
            include 'module/a_sales/sls_cabang_divisi_dist_unit_ytd/index.php';
        
    }elseif ($_GET['module']=='lapslsspv'){
        if ($_POST['rb_rpttipe']=='M')
            include 'module/a_sales/slsspv/index.php';
        elseif ($_POST['rb_rpttipe']=='Y')
            include 'module/a_sales/slsspvytd/index.php';
        
    }elseif ($_GET['module']=='lapslsdm'){
        if ($_POST['rb_rpttipe']=='Y')
            include 'module/a_sales/slsdmytd/index.php';
        
    }elseif ($_GET['module']=='lapslsmr'){
        if ($_POST['rb_rpttipe']=='Y')
            include 'module/a_sales/slsmrytd/index.php';
            
    }elseif ($_GET['module']=='salesytddivisipm'){
        include 'module/a_sales/slsytdpm/index.php';
        
        
    }elseif ($_GET['module']=='entrybrbulan'){
        include 'module/mod_br_entrybrbulan/laporanbrbulan.php';
    }elseif ($_GET['module']=='entrybrrutin'){
        include 'module/mod_br_brrutin/laporanbrrutin.php';
        
    }elseif ($_GET['module']=='downloadrutinpdf'){
        include 'module/mod_br_brrutin/printpdf.php';
        
    }elseif ($_GET['module']=='pdfdownloadrutin'){
        include 'module/mod_br_brrutin/printpdf2.php';
        
    }elseif ($_GET['module']=='entrybrrutinotc'){
        include 'module/mod_br_brrutin/laporanbrrutin.php';
    }elseif ($_GET['module']=='entrybrluarkota'){
        include 'module/mod_br_entrybrluarkota/laporanbrluarkota.php';
    }elseif ($_GET['module']=='entrybrluarkotaotc'){
        include 'module/mod_br_entrybrluarkota/laporanbrluarkota.php';
    }elseif ($_GET['module']=='entrybrcash'){
        include 'module/mod_br_entrybrcash/laporanbrcash.php';
    }elseif ($_GET['module']=='entrybrcashotc'){
        include 'module/mod_br_entrybrcash/laporanbrcash.php';
    }elseif ($_GET['module']=='entrybrservicekendaraan'){
        include 'module/mod_br_entryservice/laporanbrservice.php';
    }elseif ($_GET['module']=='entrybrsewa'){
        include 'module/mod_br_entrysewa/laporanbrsewa.php';
        
    }elseif ($_GET['module']=='lapklaimpengobatan'){
        include 'module/laporan/mod_lap_pengobatan/aksi_lappengobatan.php';
        
        
    }elseif ($_GET['module']=='rptlamarealbr'){
        include 'module/data_lama/lap_br_realisasi/rlbr01.php';
    }elseif ($_GET['module']=='rptlamarealbredit'){
        include 'module/data_lama/lap_br_realisasi/rlbr02.php';
    }elseif ($_GET['module']=='rptlamarealbrbulan'){
        include 'module/data_lama/lap_br_realisasibulan/rprlar_1.php';
    }elseif ($_GET['module']=='rptlamabrdccdss'){
        include 'module/data_lama/lap_br_dccdss/rpbreq3.php';
    }elseif ($_GET['module']=='rptlamabrnon'){
        include 'module/data_lama/lap_br_nondccdss/rpbreq5.php';
    }elseif ($_GET['module']=='rptlamabrytddccdss'){
        include 'module/data_lama/lap_br_ytddccdss/rpbrthn1.php';
    }elseif ($_GET['module']=='rptlamabrrekapsby'){
        include 'module/data_lama/lap_br_rekapsby/rprlsby1.php';
    }elseif ($_GET['module']=='rptlamabrlapviasby'){
        include 'module/data_lama/lap_br_lapviasby/rpbrsby1.php';
    }elseif ($_GET['module']=='rptlamabrlapklaimdisbulan'){
        include 'module/data_lama/lap_br_lapklaimbulan/lpklaim1.php';
    }elseif ($_GET['module']=='rptlamabrlaprekapbr'){
        include 'module/data_lama/lap_br_lapbrrekap/rpbreq7.php';
    }elseif ($_GET['module']=='rptlamabrlapkeuangan'){
        include 'module/data_lama/lap_br_lapbrkeuangan/rpfnmrk1.php';
        
    }elseif ($_GET['module']=='apvrekapbr'){
        include 'module/data_lama/lap_apv_rekapbr/rptgltr1.php';
    }elseif ($_GET['module']=='apvrekapbrdisklaim'){
        include 'module/data_lama/lap_apv_rekapbrklaim/rptgltr3.php';
    }elseif ($_GET['module']=='apvrekapbracc'){
        include 'module/data_lama/lap_apv_rekapbracc/rptgacc1.php';
    }elseif ($_GET['module']=='apvrekapbrviasby'){
        include 'module/data_lama/lap_apv_rekapbraccsby/rpbrsby3.php';
    }elseif ($_GET['module']=='apvrekapbraccviasby'){
        include 'module/data_lama/lap_apv_rekapbraccsbyacc/rptgacc3.php';
        
    }elseif ($_GET['module']=='ethrealisasibrotc'){
        include 'module/data_lama/lap_eth_realisasibrotc/rlbrotc1.php';
    }elseif ($_GET['module']=='anneklaimkesehatan'){
        include 'module/data_lama/lap_anne_klaimkesehatan/rpklm011.php';
        
    }elseif ($_GET['module']=='otcrptlamaviewbrtrans'){
        include 'module/data_lama/otc_br_viewtrans/brotc11.php';
    }elseif ($_GET['module']=='otcrptlamaviewbrtgl'){
        include 'module/data_lama/otc_br_viewtgl/brotc21.php';
    }elseif ($_GET['module']=='otclaptrans'){
        include 'module/data_lama/otc_lap_brtransfer/rpbroan1.php';
    }elseif ($_GET['module']=='otclaprekaptrans'){
        include 'module/data_lama/otc_lap_rekaptrans/rptrnsf1.php';
    }elseif ($_GET['module']=='otclapinputsby'){
        include 'module/data_lama/otc_lap_sbyinputrpt/rpbosby1.php';
    }elseif ($_GET['module']=='otclapakhirsby'){
        include 'module/data_lama/otc_lap_sbyakhirrpt/rpbrasb1.php';
    }elseif ($_GET['module']=='otclaprekapbr'){
        include 'module/data_lama/otc_lap_rekapbr/rpdtbr01.php';
    }elseif ($_GET['module']=='otclaprekapbr2'){
        include 'module/data_lama/otc_lap_rekapbr2/rpdtbr01.php';
    }elseif ($_GET['module']=='otclaprekapbr3'){
        include 'module/data_lama/otc_lap_rekapbr3/rpdtbr01.php';
    }elseif ($_GET['module']=='kasisikas'){
        include 'module/data_lama/kas_isikas/rpdtbr01.php';
    }elseif ($_GET['module']=='kaslihatedit'){
        include 'module/data_lama/kas_kaslihatedit/kas11.php';
    }elseif ($_GET['module']=='kaslapkas'){
        include 'module/data_lama/kas_kaslap/rpkas5.php';
    }elseif ($_GET['module']=='kasrekap'){
        include 'module/data_lama/kas_kasrekap/rpkaskk1.php';
        
        
    }elseif ($_GET['module']=='lapbiayarutinotc'){
        include 'module/laporan/mod_lap_brrutinotc/aksi_lapbrrutinotc.php';
    }elseif ($_GET['module']=='lapbiayarutin'){
        include 'module/laporan/mod_lap_brrutin/aksi_lapbrrutin.php';
    }elseif ($_GET['module']=='rekapbiayarutinotc'){
        include 'module/laporan/mod_rekap_brrutinotc/aksi_rekapbrrutinotc.php';
    }elseif ($_GET['module']=='rekapbiayarutincaotc'){
        include 'module/mod_br_spdrutinotc/rptcarutin.php';
    }elseif ($_GET['module']=='rekapbiayarutin'){
        include 'module/laporan/mod_rekap_brrutin/aksi_rekapbrrutin.php';
    }elseif ($_GET['module']=='rekapbiayarutinnorek'){
        include 'module/laporan/mod_rekap_brrutin_rek/aksi_rekapbrrutin.php';
    }elseif ($_GET['module']=='rekapbiayaluarotc'){
        include 'module/laporan/mod_rekap_brluarotc/aksi_rekapbrluarotc.php';
    }elseif ($_GET['module']=='rekapbiayaluar'){
        include 'module/laporan/mod_rekap_brluar/aksi_rekapbrluar.php';
    }elseif ($_GET['module']=='lapbiayaluarotc'){
        include 'module/laporan/mod_lap_brluarotc/aksi_lapbrluarotc.php';
    }elseif ($_GET['module']=='laporanbiayarutinotc'){
        include 'module/laporan/mod_laporan_brrutinotc/aksi_lapbrrutinotc.php';
    }elseif ($_GET['module']=='lapbiayaluar'){
        include 'module/laporan/mod_lap_brluar/aksi_lapbrluar.php';
    }elseif ($_GET['module']=='lapbrca'){
        include 'module/laporan/mod_lap_brca/aksi_lapbrca.php';
    }elseif ($_GET['module']=='lapbrcaotc'){
        include 'module/laporan/mod_lap_brcaotc/aksi_lapbrcaotc.php';
    }elseif ($_GET['module']=='rekapbrcaotc'){
        include 'module/laporan/mod_rekap_brcaotc/aksi_rekapbrcaotc.php';
    }elseif ($_GET['module']=='rekapbrca'){
        include 'module/laporan/mod_rekap_brca/aksi_rekapbrca.php';
    }elseif ($_GET['module']=='realisasiblotc'){
        include 'module/laporan/mod_realisasiblotc/aksi_realisasiblotc.php';
    }elseif ($_GET['module']=='realisasibl'){
        include 'module/laporan/mod_realisasibl/aksi_realisasibl.php';
    }elseif ($_GET['module']=='lapsuratcalk'){
        include 'module/laporan/mod_lap_suratca/aksi_lapsuratca.php';
        
    }elseif ($_GET['module']=='lapbudgetcoa'){
        include 'module/laporan/mod_laporanbudgetcoa/aksi_laporanbudgetcoa.php';
    }elseif ($_GET['module']=='transferbrotc'){
        include 'module/laporan/mod_lapbrtransfer/aksi_lapbrtransfer.php';
    }elseif ($_GET['module']=='transferblotc'){
        include 'module/laporan/mod_lapbltransfer/aksi_lapbltransfer.php';
        
        
        
    }elseif ($_GET['module']=='datakaryawan'){
        include 'module/lap_m_karyawan/lihatdatakaryawan.php';
        
    }elseif ($_GET['module']=='finprosbiayarutin'){
        $iprintrut="";
        if (isset($_GET['iprint'])) {
            if ($_GET['iprint']=="editrutin"){
                include 'module/mod_fin_prosbiayarutin/editdatarutin.php';
                $iprintrut="true";
            }elseif ($_GET['iprint']=="isipajak"){
                include 'module/mod_fin_prosbiayarutin/pajakdatarutin.php';
                $iprintrut="true";
            }
        }
        if (empty($iprintrut))
            include 'module/mod_fin_prosbiayarutin/rekapdatarutin.php';
    }elseif ($_GET['module']=='finprosbiayaluar'){
        include 'module/mod_fin_prosbiayaluarkota/rekapdataluarkota.php';
    }elseif ($_GET['module']=='finprosca'){
        include 'module/mod_fin_prosca/editdataca.php';
        
        
    }elseif ($_GET['module']=='lapgl'){
        include 'module/laporan/mod_gl_laporan/aksi_lapgl.php';
        
    }elseif ($_GET['module']=='lapgeneralledgerx'){
        include 'module/laporan/mod_gl_laporan3/aksi_lapgl.php';
    }elseif ($_GET['module']=='lapgeneralledger'){
        include 'module/laporan_gl/mod_generalledger/aksi_generalledger.php';
        
    }elseif ($_GET['module']=='glreportspd'){
        include 'module/laporan_gl/mod_gl_rptspd/aksi_rptspd.php';
    }elseif ($_GET['module']=='suratpdpreview'){
        include 'module/laporan_gl/mod_gl_rptspd/aksi_rptspd.php';
    }elseif ($_GET['module']=='glreportspddetail'){
        include 'module/laporan_gl/mod_gl_rptspd/reportspd_detail.php';
        
    }elseif ($_GET['module']=='glrekapbank'){
        include 'module/laporan_gl/mod_gl_rekapbank/aksi_rekapbank.php';
    }elseif ($_GET['module']=='brdanabank'){
        include 'module/laporan_gl/mod_gl_rekapbank/aksi_rekapbank.php';
        
    }elseif ($_GET['module']=='cfrealisasidana'){
        include 'module/laporan_gl/mod_gl_cfrealisasi/aksi_cfrealisasi.php';
    }elseif ($_GET['module']=='realisasidana'){
        include 'module/laporan_gl/mod_gl_realisasidana/aksi_realisasidana.php';
        
    }elseif ($_GET['module']=='glrekapbr'){
        include 'module/laporan_gl/mod_gl_rekapbr/aksi_glrekapbr.php';
    }elseif ($_GET['module']=='glrekapbrotc'){
        include 'module/laporan_gl/mod_gl_rekapbrotc/aksi_glrekapbrotc.php';
    }elseif ($_GET['module']=='glrekapbrklaim'){
        include 'module/laporan_gl/mod_gl_rekapbrklaim/aksi_glrekapbrklaim.php';
    }elseif ($_GET['module']=='glrekapbrrutin'){
        include 'module/laporan_gl/mod_gl_rekapbrrutin/aksi_glrekapbrrutin.php';
    }elseif ($_GET['module']=='glrekapbrluarkota'){
        include 'module/laporan_gl/mod_gl_rekapbrlk/aksi_glrekapbrlk.php';
    }elseif ($_GET['module']=='glrekapbrkas'){
        include 'module/laporan_gl/mod_gl_rekapbrkas/aksi_glrekapbrkas.php';
        
    }elseif ($_GET['module']=='gldetailrekapbr'){
        include 'module/laporan_gl/mod_gl_rekapbrdtl/aksi_glrekapbrdtl.php';
    }elseif ($_GET['module']=='gldetailrekapbrotc'){
        include 'module/laporan_gl/mod_gl_rekapbrotcdtl/aksi_glrekapbrotcdtl.php';
    }elseif ($_GET['module']=='gldetailrekapbrklaim'){
        include 'module/laporan_gl/mod_gl_rekapbrklaimdtl/aksi_glrekapbrklaimdtl.php';
        
    }elseif ($_GET['module']=='glrealbiayamkt'){
        include 'module/laporan_gl/mod_gl_rbm/aksi_rbm.php';
    }elseif ($_GET['module']=='glrealbiayamktcab'){
        include 'module/laporan_gl/mod_gl_rbmcab/aksi_rbmcab.php';
        
    }elseif ($_GET['module']=='gllapbiayakendaraan'){
        include 'module/laporan_gl/mod_gl_biayakendaraan/aksi_biayakendaraan.php';
    }elseif ($_GET['module']=='gllapbiayakendaraanperjalanan'){
        include 'module/laporan_gl/mod_gl_biayakendaraanjalan/aksi_biayakendaraanjalan.php';
        
        
        
    }elseif ($_GET['module']=='spgrekapgaji'){
        include 'module/laporan/mod_spg_rekapgaji/aksi_spgrekapgaji.php';
    }elseif ($_GET['module']=='spglapgaji'){
        include 'module/laporan/mod_spg_lapgaji/aksi_spglapgaji.php';
        
        
        
    }elseif ($_GET['module']=='sbyrekapbm'){
        include 'module/surabaya/mod_sby_lap_rekapbm/aksi_laprekapbm.php';
    }elseif ($_GET['module']=='lapbudgetmarketing'){
        include 'module/mod_budget_laprealisasi/aksi_lapbudg_realisasi.php';
    }elseif ($_GET['module']=='lapbudgetmarketingvsrealisasi'){
        include 'module/mod_budget_laprealisasibudget/aksi_realisasi_budget.php';
    }elseif ($_GET['module']=='lapbudgetmarketingvsrealisasiotc'){
        include 'module/mod_budget_laprealisasibudgetotc/aksi_realisasi_budgetotc.php';
        
        
    }elseif ($_GET['module']=='spdotc'){
        if ($_GET['act']=='isitglrptsby'){
            include 'module/mod_br_spdotc/isirptsby.php';
        }else{
            include 'module/mod_br_spdotc/rpbrasb1.php';
        }
        
        
    }elseif ($_GET['module']=='saldosuratdana'){
        $iid="";
        if (isset($_GET['iid'])) $iid=$_GET['iid'];
        if ($iid==1) {
            include 'module/mod_br_spd/laporanerni.php';
        }elseif ($iid==2) {
            include 'module/mod_br_spd/laporanprita.php';
        }elseif ($iid==5) {
            include 'module/mod_br_suratpd/laporananne.php';
        }
    }elseif ($_GET['module']=='suratpd'){
        $print="";
        if (isset($_GET['iprint'])) {
            if ($_GET['iprint']=="print") $print="print";
        }
        if ($print=="print")
            include 'module/mod_br_suratpd/printspd.php';
        else
            include 'module/mod_br_suratpd/laporanbr.php';
        
        
    }elseif ($_GET['module']=='outlkcaethical'){
        include 'module/mod_br_otsdlkca_eth/rpt_otsd_lkca.php';
    }elseif ($_GET['module']=='outlkcaotc'){
        include 'module/mod_br_otsdlkca_otc/rpt_otsd_lkca.php';
        
    }elseif ($_GET['module']=='spgharikerja'){
        include 'module/md_m_spg_harikerja/spg_rpt.php';
        
    }elseif ($_GET['module']=='spdkas'){
        include 'module/mod_br_spdkas/spdkas_rpt.php';
    }elseif ($_GET['module']=='entrybrkasbon'){
        include 'module/mod_br_isikasbon/isikasbon_rpt.php';
        
    }elseif ($_GET['module']=='spdrutinotc'){
        include 'module/mod_br_spdrutinotc/rpt_spd_rutinotc.php';
        
    }elseif ($_GET['module']=='mstprosesinsentif'){
        include 'module/md_m_prosesdatainsentif/rpt_prosinc.php';
        
    }elseif ($_GET['module']=='laprutintahun'){
        include 'module/laporan/mod_rutin_pertahun/aksi_rutinpertahun.php';
    }elseif ($_GET['module']=='lapbrrutinotctahun'){
        include 'module/laporan/mod_rutin_pertahunotc/aksi_rutinpertahunotc.php';
    }elseif ($_GET['module']=='lapbrlkotctahun'){
        include 'module/laporan/mod_lk_pertahunotc/aksi_lkpertahunotc.php';
    }elseif ($_GET['module']=='lapkendaraandinas'){
        include 'module/laporan/mod_lap_kendaraan/aksi_lapkendaraan.php';
    }elseif ($_GET['module']=='reportcasewa'){
        include 'module/mod_br_spdrutineth/rpt_spd_casewa.php';
        
        
    }elseif ($_GET['module']=='laporangajispgotc'){
        include 'module/mod_br_spdotc/rpt_gajispgotc.php';
        
    }elseif ($_GET['module']=='rekapotsbr'){
        include 'module/laporan_gl/mod_gl_rekapots/aksi_rekapots.php';
    }elseif ($_GET['module']=='rekapotsbrotc'){
        include 'module/laporan_gl/mod_gl_rekapotsotc/aksi_rekapotsotc.php';
        
    }elseif ($_GET['module']=='rekapinsentifrekbank'){
        include 'module/laporan/mod_rekap_insentif_rek/aksi_rekapinsentif.php';
        
    }elseif ($_GET['module']=='lapbrpajak'){
        include 'module/laporan_gl/mod_gl_rekapbrpajak/aksi_rekapbrpajak.php';
        
    }elseif ($_GET['module']=='bukafilenya'){
        include 'bkf.php';
        
    }elseif ($_GET['module']=='closingbrlkca2'){
        include 'module/mod_br_closing_lkca_baru/rptcaclosing.php';
        
    }elseif ($_GET['module']=='printentrybrdcccabang'){
        include 'module/mod_br_entrybrdcccab/printdatabrcab.php';
        
    }elseif ($_GET['module']=='fincekprosesbrcab'){
        include 'module/mod_fin_cekprosbrcab/laporanbrcabfin.php';
        
        
        
        //khusus
    }elseif ($_GET['module']=='appdirpd'){
        include 'module/dir_apvspd/ttd_dir.php';
        
    }elseif ($_GET['module']=='xxx'){
        
    }
?>