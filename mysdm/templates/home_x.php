<div class="right_col" role="main">
    <?PHP
    if ($_GET['module']=="user"){
        include 'module/mod_tools_users/users.php';
    }elseif ($_GET['module']=='employee'){
        //include 'module/mod_employee/employee.php';
    }elseif ($_GET['module']=='groupuser'){
        include 'module/mod_tools_groupuser/groupuser.php';
    }elseif ($_GET['module']=='menuutama'){
        include 'module/mod_tools_menu/menu.php';
    }elseif ($_GET['module']=='submenu'){
        include 'module/mod_tools_submenu/submenu.php';
    }elseif ($_GET['module']=='backupmysql'){
        include 'module/mod_tools_backdbmysql/setbackup.php';
    }elseif ($_GET['module']=='leveljabatan'){
        include 'module/mod_tools_lvljabatan/lvljabatan.php';
        
        
        
        
    }elseif ($_GET['module']=='entrybr'){
        include 'module/mod_br_entry/entrybr.php';
    }elseif ($_GET['module']=='entrybrdcc'){
        include 'module/mod_br_entrydcc/entrybrdcc.php';
    }elseif ($_GET['module']=='entrybrnon'){
        include 'module/mod_br_entrynon/entrybrnon.php';
    }elseif ($_GET['module']=='entrybrnoncabang'){
        include 'module/mod_br_entrynoncab/entrybrnoncab.php';
        
    }elseif ($_GET['module']=='entrybrdcccabang'){
        include 'module/mod_br_entrybrdcccab/entrybrdcccab.php';
        
        
    }elseif ($_GET['module']=='entrydatabudget'){
        include 'module/mod_bg_budget/budgetteam.php';
    }elseif ($_GET['module']=='entrydatabudgetotc'){
        include 'module/mod_bg_budgetotc/budgetteamotc.php';
    }elseif ($_GET['module']=='entrybrklaim'){
        include 'module/mod_br_entryklaim/entryklaim.php';
    }elseif ($_GET['module']=='entrybrluarkota'){
        include 'module/mod_br_entrybrluarkota/entrybrluarkota.php';
    }elseif ($_GET['module']=='entrybrcash'){
        include 'module/mod_br_entrybrcash/entrybrcash.php';
    }elseif ($_GET['module']=='brapprovespv'){
        include 'module/mod_br_apvspv/brapvspv.php';
    }elseif ($_GET['module']=='brapproveam'){
        include 'module/mod_br_apvam/brapvam.php';
    }elseif ($_GET['module']=='brvalidasi'){
        include 'module/mod_br_validasi/brvalidasi.php';
    }elseif ($_GET['module']=='barangkategori'){
        include 'module/mod_brg_kategori/kategori_brg.php';
    }elseif ($_GET['module']=='barangdata'){
        include 'module/mod_brg_barang/barang.php';
    }elseif ($_GET['module']=='supplier'){
        include 'module/mod_brg_supplier/supplier.php';
    }elseif ($_GET['module']=='gimicgroupprod'){
        include 'module/mod_brg_grpprod/grpprod.php';
    }elseif ($_GET['module']=='gimicstockopn'){
        include 'module/mod_brg_stockopn/stockopn.php';
    }elseif ($_GET['module']=='lapbrdcc'){
        include 'module/lap_br_dcc/lapbrdcc.php';
    }elseif ($_GET['module']=='lapbrrealisasi'){
        include 'module/lap_br_realisasi/lapbrrealisasi.php';
    }elseif ($_GET['module']=='lapbrrealisasidaerah'){
        include 'module/lap_br_realisasidaerah/lapbrrealisasidaerah.php';
    }elseif ($_GET['module']=='lapbrrealisasidaerahbulan'){
        include 'module/lap_br_realisasidaerahbln/lapbrrealisasidaerahbln.php';
    }elseif ($_GET['module']=='lapbrrealisasicabang'){
        include 'module/lap_br_realisasidaerahcab/lapbrrealisasidaerahcab.php';
    }elseif ($_GET['module']=='lapbrklaim'){
        include 'module/lap_br_klaim/lapbrklaim.php';
    }elseif ($_GET['module']=='lapbrytd'){
        include 'module/lap_br_ytd/brytd.php';
    }elseif ($_GET['module']=='breditcoa'){
        include 'module/mod_br_isicoa/brisicoa.php';
    }elseif ($_GET['module']=='breditcoaotc'){
        include 'module/mod_br_isicoaotc/brisicoaotc.php';
    }elseif ($_GET['module']=='entrybrbulan'){
        include 'module/mod_br_entrybrbulan/entrybrbulan.php';
    }elseif ($_GET['module']=='entrybrservicekendaraan'){
        include 'module/mod_br_entryservice/entryservice.php';
    }elseif ($_GET['module']=='entrybrsewa'){
        include 'module/mod_br_entrysewa/entrysewa.php';
        
    }elseif ($_GET['module']=='lapklaimpengobatan'){
        include 'module/laporan/mod_lap_pengobatan/lappengobatan.php';
    }elseif ($_GET['module']=='laprincianbrrutin'){
        include 'module/laporan/mod_lap_rincianbrrutin/laprincianbrrutin.php';
        
    }elseif ($_GET['module']=='saldosuratdana'){
        include 'module/mod_br_spd/spd.php';
    }elseif ($_GET['module']=='spd'){
        include 'module/mod_br_spd_eth/spd_eth.php';
    }elseif ($_GET['module']=='suratpd'){
        include 'module/mod_br_suratpd/suratpd.php';
    }elseif ($_GET['module']=='spdincentive'){
        include 'module/mod_br_spdincentive/spdincentive.php';
    }elseif ($_GET['module']=='spdkas'){
        include 'module/mod_br_spdkas/spdkas.php';
    }elseif ($_GET['module']=='spdotc'){
        include 'module/mod_br_spdotc/spdotc.php';
    }elseif ($_GET['module']=='spdrutinotc'){
        include 'module/mod_br_spdrutinotc/spdrutinotc.php';
    }elseif ($_GET['module']=='spdrutineth'){
        include 'module/mod_br_spdrutineth/spdrutineth.php';
    }elseif ($_GET['module']=='spdbpjs'){
        include 'module/mod_br_spdbpjs/spdbpjs.php';
        
        
        
        
    }elseif ($_GET['module']=='entrybrotc'){
        include 'module/mod_br_entryotc/entrybrotc.php';
    }elseif ($_GET['module']=='lapbrotc'){
        include 'module/lap_br_otc/brotc.php';
    }elseif ($_GET['module']=='lapbrtransotc'){
        include 'module/lap_br_otctrans/brotctrans.php';
    }elseif ($_GET['module']=='lapbrotcbulanan'){
        include 'module/lap_br_otcbulan/brotcbulan.php';
    }elseif ($_GET['module']=='lapbrotctransfhari'){
        include 'module/lap_br_otctranshari/brotctranshari.php';
    }elseif ($_GET['module']=='lapbrrekapotc'){
        include 'module/lap_br_otcrekap/brotcrekap.php';
    }elseif ($_GET['module']=='lapbrrekapotcall'){
        include 'module/lap_br_otcrekapall/brotc.php';
    }elseif ($_GET['module']=='lapbrotcpermo'){
        include 'module/lap_br_otcpermohonan/brotcpermo.php';
    }elseif ($_GET['module']=='lapbrinputsbyotc'){
        include 'module/lap_br_otcinputsby/otcinputsby.php';
    }elseif ($_GET['module']=='entrybrrutin'){
        include 'module/mod_br_brrutin/brrutin.php';
        
    }elseif ($_GET['module']=='entrybrrutinotc'){
        include 'module/mod_br_brrutinotc/brrutinotc.php';
    }elseif ($_GET['module']=='entrybrluarkotaotc'){
        include 'module/mod_br_entrybrluarkotaotc/entrybrluarkotaotc.php';
    }elseif ($_GET['module']=='entrybrcashotc'){
        include 'module/mod_br_entrybrcashotc/entrybrcashotc.php';
        
        
        
    }elseif ($_GET['module']=='datakaryawan'){
        include 'module/lap_m_karyawan/karyawan.php';
    }elseif ($_GET['module']=='datakaryawanlevel'){
        include 'module/lap_m_karyawan_lvl/karyawanlvl.php';
    }elseif ($_GET['module']=='lapbrkeuangan'){
        include 'module/lap_br_keuangan/keuangan.php';
    }elseif ($_GET['module']=='penempatanmr'){
        include 'module/md_m_penempatanmr/penempatanmr.php';
    }elseif ($_GET['module']=='penempatanspv'){
        include 'module/md_m_penempatanspv/penempatanspv.php';
    }elseif ($_GET['module']=='penempatandm'){
        include 'module/md_m_penempatandm/penempatandm.php';
    }elseif ($_GET['module']=='penempatansm'){
        include 'module/md_m_penempatansm/penempatansm.php';
    }elseif ($_GET['module']=='penempatan'){
        include 'module/md_m_penempatan/penempatan.php';
    }elseif ($_GET['module']=='penempatanmarketing'){
        include 'module/md_m_penempatanmkt/penempatanmkt.php';
    }elseif ($_GET['module']=='datakendaraan'){
        include 'module/md_m_kendaraan/kendaraan.php';
    }elseif ($_GET['module']=='pindahcustomer'){
        include 'module/md_m_pindahcust/pindahcust.php';
    }elseif ($_GET['module']=='dataspg'){
        include 'module/md_m_spg/spg.php';
    }elseif ($_GET['module']=='spgmastergaji'){
        include 'module/md_m_spg_gaji/spggaji.php';
    }elseif ($_GET['module']=='spgmastergajicabang'){
        include 'module/md_m_spg_gajicabang/spggajicabang.php';
    }elseif ($_GET['module']=='spgdatamastergaji'){
        include 'module/md_m_spg_gajispg/spggajimaster.php';
    }elseif ($_GET['module']=='spgbr'){
        include 'module/md_m_spg_br/spgbr.php';
    }elseif ($_GET['module']=='spgharikerja'){
        include 'module/md_m_spg_harikerja/spgharikerja.php';
    }elseif ($_GET['module']=='spgproses'){
        include 'module/md_m_spg_proses/spgproses.php';
    }elseif ($_GET['module']=='spgprosesfin'){
        include 'module/md_m_spg_prosesfin/spgprosesfin.php';
    }elseif ($_GET['module']=='spgprosesfinspv'){
        include 'module/md_m_spg_prosesfinspv/spgprosesfinspv.php';
    }elseif ($_GET['module']=='spgprosesmgr'){
        include 'module/md_m_spg_prosesmgr/spgprosesmgr.php';
    }elseif ($_GET['module']=='spgjmlharikerja'){
        include 'module/md_m_spg_jmlharikerja/jmlharikerja.php';
    }elseif ($_GET['module']=='importdataspg'){
        include 'module/md_m_spg_importdata/importdataspg.php';
        
        
    }elseif ($_GET['module']=='spgrekapgaji'){
        include 'module/laporan/mod_spg_rekapgaji/spgrekapgaji.php';
    }elseif ($_GET['module']=='spglapgaji'){
        include 'module/laporan/mod_spg_lapgaji/spglapgaji.php';
        
        
        
    }elseif ($_GET['module']=='coalevel1'){
        include 'module/mod_coa_coa1/coa1.php';
    }elseif ($_GET['module']=='coalevel2'){
        include 'module/mod_coa_coa2/coa2.php';
    }elseif ($_GET['module']=='coalevel3'){
        include 'module/mod_coa_coa3/coa3.php';
    }elseif ($_GET['module']=='coalevel4'){
        include 'module/mod_coa_coa4/coa4.php';
    }elseif ($_GET['module']=='coadata'){
        include 'module/mod_coa_coadata/coadata.php';
    }elseif ($_GET['module']=='coawewenang'){
        include 'module/mod_coa_wewenang/wewenang.php';
    }elseif ($_GET['module']=='postingcoa'){
        include 'module/mod_coa_posting/postingcoa.php';
    }elseif ($_GET['module']=='postingcoabiaya'){
        include 'module/mod_coa_postingbiaya/postingcoabiaya.php';
    }elseif ($_GET['module']=='postingcoakas'){
        include 'module/mod_coa_postingkas/postingcoakas.php';
    }elseif ($_GET['module']=='postingcoabr'){
        include 'module/mod_coa_postingbr/postingcoabr.php';
        
        
        
    }elseif ($_GET['module']=='salesytd'){
        include 'module/mod_sls_salesytd/salesytd.php';
    }elseif ($_GET['module']=='salesytddaerah'){
        include 'module/ytd_sls_daerah/ytd_sls_daerah.php';
    }elseif ($_GET['module']=='salesytdregion'){
        include 'module/ytd_sls_region/ytd_sls_region.php';
    }elseif ($_GET['module']=='salesytdsm'){
        include 'module/ytd_sls_sm/ytd_sls_sm.php';
    }elseif ($_GET['module']=='salesytddivisipm'){
        include 'module/ytd_sls_pm/ytd_sls_pm.php';
    }elseif ($_GET['module']=='lapslscabdiv'){
        include 'module/sls_divisicabang/slscabangdivisi.php';
    }elseif ($_GET['module']=='lapslsproddiv'){
        include 'module/sls_divisiprod/slsproddivisi.php';
    }elseif ($_GET['module']=='lapslscabdivams'){
        include 'module/sls_divisicabang_dist/slscabangdivisidist.php';
    }elseif ($_GET['module']=='lapslsproddivams'){
        include 'module/sls_divisiprod_dist/slsproddivisidist.php';
    }elseif ($_GET['module']=='lapslsspv'){
        include 'module/sls_slsspv/slsspv.php';
    }elseif ($_GET['module']=='lapslsdm'){
        include 'module/sls_slsdm/slsdm.php';
    }elseif ($_GET['module']=='lapslsmr'){
        include 'module/sls_slsmr/slsmr.php';
    }elseif ($_GET['module']=='lapslsdistytd'){
        include 'module/sls_distytd/distytd.php';
    }elseif ($_GET['module']=='slsperoutlet'){
        include 'module/sls_slsperoutlet/slsperoutlet.php';
    }elseif ($_GET['module']=='lapslspermr'){
        include 'module/sls_slspermr/slspermr.php';
    }elseif ($_GET['module']=='evalusasislsout'){
        include 'module/sls_evalslsout/evalslsout.php';
    }elseif ($_GET['module']=='rptytdcab'){
        include 'module/sls_slsytdpercab/slsytdpercab.php';
        
        
        
    }elseif ($_GET['module']=='cekselisihsales'){
        include 'module/md_m_slscekselisih/slscekselisih.php';
        
    }elseif ($_GET['module']=='lamacekselisihsales'){
        include 'module/md_m_slscekselisih_lama/slscekselisih.php';
        
        
        
    }elseif ($_GET['module']=='mktplanuc'){
        include 'module/mod_mkt_planuc/planuc.php';
        
        
        
    }elseif ($_GET['module']=='appmktbiayaluar'){
        include 'module/mod_apv_biayaluarkota/apvbiayaluarkota.php';
    }elseif ($_GET['module']=='appmktbiayarutin'){
        include 'module/mod_apv_biayarutin/apvbiayarutin.php';
    }elseif ($_GET['module']=='appmktca'){
        include 'module/mod_apv_ca/apvca.php';
    }elseif ($_GET['module']=='appmktservice'){
        include 'module/mod_apv_service/apvservice.php';
    }elseif ($_GET['module']=='appmktsewa'){
        include 'module/mod_apv_sewa/apvsewa.php';
        
    }elseif ($_GET['module']=='appmktcabiayaotc'){
        include 'module/mod_apv_cabiayaotc/apvcabiayaotc.php';
        
    }elseif ($_GET['module']=='finprosbiayaluar'){
        include 'module/mod_fin_prosbiayaluarkota/prosbiayaluarkota.php';
    }elseif ($_GET['module']=='finprosbiayarutin'){
        include 'module/mod_fin_prosbiayarutin/prosbiayarutin.php';
    }elseif ($_GET['module']=='finprosca'){
        include 'module/mod_fin_prosca/prosca.php';
    }elseif ($_GET['module']=='finprosservice'){
        include 'module/mod_fin_prosservice/prosservice.php';
    }elseif ($_GET['module']=='finprossewa'){
        include 'module/mod_fin_prossewa/prossewa.php';
        
    }elseif ($_GET['module']=='finproscabiayaotc'){
        include 'module/mod_fin_proscabiayaotc/proscabiayaotc.php';
        
    }elseif ($_GET['module']=='ttdspdfin'){
        include 'module/mod_fin_ttdspd/ttdspd.php';
        
        
    }elseif ($_GET['module']=='lapbiayarutinotc'){
        include 'module/laporan/mod_lap_brrutinotc/lapbrrutinotc.php';
    }elseif ($_GET['module']=='lapbiayarutin'){
        include 'module/laporan/mod_lap_brrutin/lapbrrutin.php';
    }elseif ($_GET['module']=='rekapbiayarutinotc'){
        include 'module/laporan/mod_rekap_brrutinotc/rekapbrrutinotc.php';
    }elseif ($_GET['module']=='rekapbiayarutin'){
        include 'module/laporan/mod_rekap_brrutin/rekapbrrutin.php';
    }elseif ($_GET['module']=='rekapbiayarutinnorek'){
        include 'module/laporan/mod_rekap_brrutin_rek/rekapbrrutin.php';
    }elseif ($_GET['module']=='rekapbiayaluarotc'){
        include 'module/laporan/mod_rekap_brluarotc/rekapbrluarotc.php';
    }elseif ($_GET['module']=='rekapbiayaluar'){
        include 'module/laporan/mod_rekap_brluar/rekapbrluar.php';
    }elseif ($_GET['module']=='lapbiayaluarotc'){
        include 'module/laporan/mod_lap_brluarotc/lapbrluarotc.php';
    }elseif ($_GET['module']=='laporanbiayarutinotc'){
        include 'module/laporan/mod_laporan_brrutinotc/lapbrrutinotc.php';
    }elseif ($_GET['module']=='lapbiayaluar'){
        include 'module/laporan/mod_lap_brluar/lapbrluar.php';
    }elseif ($_GET['module']=='lapbrca'){
        include 'module/laporan/mod_lap_brca/lapbrca.php';
    }elseif ($_GET['module']=='lapbrcaotc'){
        include 'module/laporan/mod_lap_brcaotc/lapbrcaotc.php';
    }elseif ($_GET['module']=='rekapbrcaotc'){
        include 'module/laporan/mod_rekap_brcaotc/rekapbrcaotc.php';
    }elseif ($_GET['module']=='rekapbrca'){
        include 'module/laporan/mod_rekap_brca/rekapbrca.php';
    }elseif ($_GET['module']=='realisasiblotc'){
        include 'module/laporan/mod_realisasiblotc/realisasiblotc.php';
    }elseif ($_GET['module']=='realisasibl'){
        include 'module/laporan/mod_realisasibl/realisasibl.php';
    }elseif ($_GET['module']=='lapsuratcalk'){
        include 'module/laporan/mod_lap_suratca/lapsuratca.php';
    }elseif ($_GET['module']=='transferbrotc'){
        include 'module/laporan/mod_lapbrtransfer/lapbrtransfer.php';
    }elseif ($_GET['module']=='transferblotc'){
        include 'module/laporan/mod_lapbltransfer/lapbltransfer.php';
        
    }elseif ($_GET['module']=='lapbudgetcoa'){
        include 'module/laporan/mod_laporanbudgetcoa/laporanbudgetcoa.php';
        
        
        
        
        
        
    }elseif ($_GET['module']=='rptlamarealbr'){
        include 'module/data_lama/lap_br_realisasi/brrealisasi.php';
    }elseif ($_GET['module']=='rptlamarealbrbulan'){
        include 'module/data_lama/lap_br_realisasibulan/brrealisasibulan.php';
    }elseif ($_GET['module']=='rptlamabrdccdss'){
        include 'module/data_lama/lap_br_dccdss/brdccdss.php';
    }elseif ($_GET['module']=='rptlamabrnon'){
        include 'module/data_lama/lap_br_nondccdss/nonbrdccdss.php';
    }elseif ($_GET['module']=='rptlamabrytddccdss'){
        include 'module/data_lama/lap_br_ytddccdss/ytdbrdccdss.php';
    }elseif ($_GET['module']=='rptlamabrrekapsby'){
        include 'module/data_lama/lap_br_rekapsby/rekapsby.php';
    }elseif ($_GET['module']=='rptlamabrlapviasby'){
        include 'module/data_lama/lap_br_lapviasby/lapviasby.php';
    }elseif ($_GET['module']=='rptlamabrlapklaimdisbulan'){
        include 'module/data_lama/lap_br_lapklaimbulan/lapklaimbulan.php';
    }elseif ($_GET['module']=='rptlamabrlaprekapbr'){
        include 'module/data_lama/lap_br_lapbrrekap/laprekapbr.php';
    }elseif ($_GET['module']=='rptlamabrlapkeuangan'){
        include 'module/data_lama/lap_br_lapbrkeuangan/lapbrkeuangan.php';
        
    }elseif ($_GET['module']=='apvrekapbr'){
        include 'module/data_lama/lap_apv_rekapbr/apvrekapbr.php';
    }elseif ($_GET['module']=='apvrekapbrdisklaim'){
        include 'module/data_lama/lap_apv_rekapbrklaim/apvrekapbrklaim.php';
    }elseif ($_GET['module']=='apvrekapbracc'){
        include 'module/data_lama/lap_apv_rekapbracc/rekapbracc.php';
    }elseif ($_GET['module']=='apvrekapbrviasby'){
        include 'module/data_lama/lap_apv_rekapbraccsby/rekapbraccsby.php';
    }elseif ($_GET['module']=='apvrekapbraccviasby'){
        include 'module/data_lama/lap_apv_rekapbraccsbyacc/rekapbraccsbyacc.php';
        
    }elseif ($_GET['module']=='ethrealisasibrotc'){
        include 'module/data_lama/lap_eth_realisasibrotc/realisasibrotc.php';
    }elseif ($_GET['module']=='anneklaimkesehatan'){
        include 'module/data_lama/lap_anne_klaimkesehatan/klaimkesehatan.php';
    }elseif ($_GET['module']=='annecuti'){
        include 'module/data_lama/lap_anne_cuti/cuti.php';
        
        
    }elseif ($_GET['module']=='otcrptlamaviewbrtrans'){
        include 'module/data_lama/otc_br_viewtrans/brviewtrans.php';
    }elseif ($_GET['module']=='otcrptlamaviewbrtgl'){
        include 'module/data_lama/otc_br_viewtgl/brviewtgl.php';
    }elseif ($_GET['module']=='otclaptrans'){
        include 'module/data_lama/otc_lap_brtransfer/lapbrtransfer.php';
    }elseif ($_GET['module']=='otclaprekaptrans'){
        include 'module/data_lama/otc_lap_rekaptrans/rekaptrans.php';
    }elseif ($_GET['module']=='otclapinputsby'){
        include 'module/data_lama/otc_lap_sbyinputrpt/sbyinputrpt.php';
    }elseif ($_GET['module']=='otclapakhirsby'){
        include 'module/data_lama/otc_lap_sbyakhirrpt/sbyakhirrpt.php';
    }elseif ($_GET['module']=='otclaprekapbr'){
        include 'module/data_lama/otc_lap_rekapbr/rekapbr.php';
    }elseif ($_GET['module']=='otclaprekapbr2'){
        include 'module/data_lama/otc_lap_rekapbr2/rekapbr.php';
    }elseif ($_GET['module']=='otclaprekapbr3'){
        include 'module/data_lama/otc_lap_rekapbr3/rekapbr.php';
    }elseif ($_GET['module']=='kasisikas'){
        include 'module/data_lama/kas_isikas/isikas.php';
    }elseif ($_GET['module']=='kaslihatedit'){
        include 'module/data_lama/kas_kaslihatedit/kaslihatedit.php';
    }elseif ($_GET['module']=='kaslapkas'){
        include 'module/data_lama/kas_kaslap/kaslap.php';
    }elseif ($_GET['module']=='kasrekap'){
        include 'module/data_lama/kas_kasrekap/kasrekap.php';
        
    }elseif ($_GET['module']=='entrybrkasbon'){
        include 'module/mod_br_isikasbon/isikasbon.php';
        
        
    }elseif ($_GET['module']=='lapgl'){
        include 'module/laporan/mod_gl_laporan/lapgl.php';
        
        
        
    }elseif ($_GET['module']=='lapgeneralledgerx'){
        include 'module/laporan/mod_gl_laporan3/lapgl.php';
    }elseif ($_GET['module']=='lapgeneralledger'){
        include 'module/laporan_gl/mod_generalledger/generalledger.php';
        
    }elseif ($_GET['module']=='glreportspd'){
        include 'module/laporan_gl/mod_gl_rptspd/rptspd.php';
    }elseif ($_GET['module']=='glrekapbank'){
        include 'module/laporan_gl/mod_gl_rekapbank/rekapbank.php';
    }elseif ($_GET['module']=='cfrealisasidana'){
        include 'module/laporan_gl/mod_gl_cfrealisasi/cfrealisasi.php';
    }elseif ($_GET['module']=='realisasidana'){
        include 'module/laporan_gl/mod_gl_realisasidana/realisasidana.php';
        
    }elseif ($_GET['module']=='glrekapbr'){
        include 'module/laporan_gl/mod_gl_rekapbr/glrekapbr.php';
    }elseif ($_GET['module']=='glrekapbrotc'){
        include 'module/laporan_gl/mod_gl_rekapbrotc/glrekapbrotc.php';
    }elseif ($_GET['module']=='glrekapbrklaim'){
        include 'module/laporan_gl/mod_gl_rekapbrklaim/glrekapbrklaim.php';
    }elseif ($_GET['module']=='glrekapbrrutin'){
        include 'module/laporan_gl/mod_gl_rekapbrrutin/glrekapbrrutin.php';
    }elseif ($_GET['module']=='glrekapbrluarkota'){
        include 'module/laporan_gl/mod_gl_rekapbrlk/glrekapbrlk.php';
    }elseif ($_GET['module']=='glrekapbrkas'){
        include 'module/laporan_gl/mod_gl_rekapbrkas/glrekapbrkas.php';
        
    }elseif ($_GET['module']=='gldetailrekapbr'){
        include 'module/laporan_gl/mod_gl_rekapbrdtl/glrekapbrdtl.php';
    }elseif ($_GET['module']=='gldetailrekapbrotc'){
        include 'module/laporan_gl/mod_gl_rekapbrotcdtl/glrekapbrotcdtl.php';
    }elseif ($_GET['module']=='gldetailrekapbrklaim'){
        include 'module/laporan_gl/mod_gl_rekapbrklaimdtl/glrekapbrklaimdtl.php';
        
    }elseif ($_GET['module']=='glrealbiayamkt'){
        include 'module/laporan_gl/mod_gl_rbm/rbm.php';
    }elseif ($_GET['module']=='glrealbiayamktcab'){
        include 'module/laporan_gl/mod_gl_rbmcab/rbmcab.php';
        
    }elseif ($_GET['module']=='gllapbiayakendaraan'){
        include 'module/laporan_gl/mod_gl_biayakendaraan/biayakendaraan.php';
    }elseif ($_GET['module']=='gllapbiayakendaraanperjalanan'){
        include 'module/laporan_gl/mod_gl_biayakendaraanjalan/biayakendaraanjalan.php';
    }elseif ($_GET['module']=='sumcf'){
        include 'module/laporan_gl/mod_gl_sumcf/sumcf.php';
        
        
        
        
    }elseif ($_GET['module']=='prosesdatatabel'){
        include 'module/admin/mod_adm_proses/prosestabel.php';
        
        
    }elseif ($_GET['module']=='sbyinputbm'){
        include 'module/surabaya/mod_sby_bm/bm.php';
    }elseif ($_GET['module']=='sbyrekapbm'){
        include 'module/surabaya/mod_sby_lap_rekapbm/laprekapbm.php';
    }elseif ($_GET['module']=='sbydatabudgetreq'){
        include 'module/surabaya/mod_sby_dbr/dbr.php';
        
    }elseif ($_GET['module']=='closingbrlkca'){
        include 'module/mod_br_closing_lkca/closing_lkca.php';
    }elseif ($_GET['module']=='closingbrlkca2'){
        include 'module/mod_br_closing_lkca_baru/closing_lkca.php';
        
    }elseif ($_GET['module']=='closingbrlkcaotc'){
        include 'module/mod_br_closing_lkcaotc/closing_lkcaotc.php';
    }elseif ($_GET['module']=='outlkcaethical'){
        include 'module/mod_br_otsdlkca_eth/otsd_lkca.php';
    }elseif ($_GET['module']=='outlkcaotc'){
        include 'module/mod_br_otsdlkca_otc/otsd_lkca.php';
        
        
    }elseif ($_GET['module']=='realisasibudgetmarketing'){
        include 'module/mod_budget_realisasi/budg_realisasi.php';
        
    }elseif ($_GET['module']=='lapbudgetmarketing'){
        include 'module/mod_budget_laprealisasi/lapbudg_realisasi.php';
    }elseif ($_GET['module']=='lapbudgetmarketingvsrealisasi'){
        include 'module/mod_budget_laprealisasibudget/realisasi_budget.php';
    }elseif ($_GET['module']=='lapbudgetmarketingvsrealisasiotc'){
        include 'module/mod_budget_laprealisasibudgetotc/realisasi_budgetotc.php';
        
    }elseif ($_GET['module']=='uangmuka'){
        include 'module/mod_budget_uk/uangmuka.php';
        
        
    }elseif ($_GET['module']=='appdirca'){
        include 'module/dir_apvca/apvcadir.php';
    }elseif ($_GET['module']=='appdirblk'){
        include 'module/dir_apvblk/apvblkdir.php';
    }elseif ($_GET['module']=='appdirrutin'){
        include 'module/dir_apvrutin/apvrutindir.php';
    }elseif ($_GET['module']=='appdirpd'){
        include 'module/dir_apvspd/apvspddir.php';
        
    }elseif ($_GET['module']=='ownerapvspd'){
        include 'module/dir_apvspd_owner/apvspdowner.php';
        
    }elseif ($_GET['module']=='prosescallinsentif'){
        include 'module/mst_prosesinsentif/prosesinsentif.php';
        
    }elseif ($_GET['module']=='brdanabank'){
        include 'module/mod_br_danabank/danabank.php';
        
    }elseif ($_GET['module']=='mstsupporttickets'){
        include 'module/md_m_tickets/tickets.php';
        
    }elseif ($_GET['module']=='mstprosesinsentif'){
        include 'module/md_m_prosesdatainsentif/prosesdatainsentif.php';
        
    }elseif ($_GET['module']=='laprutintahun'){
        include 'module/laporan/mod_rutin_pertahun/rutinpertahun.php';
    }elseif ($_GET['module']=='lapluarkotatahun'){
        include 'module/laporan/mod_lk_pertahun/lkpertahun.php';
    }elseif ($_GET['module']=='lapbrrutinotctahun'){
        include 'module/laporan/mod_rutin_pertahunotc/rutinpertahunotc.php';
    }elseif ($_GET['module']=='lapbrlkotctahun'){
        include 'module/laporan/mod_lk_pertahunotc/lkpertahunotc.php';
    }elseif ($_GET['module']=='lapkendaraandinas'){
        include 'module/laporan/mod_lap_kendaraan/lapkendaraan.php';
        
    }elseif ($_GET['module']=='otsservicekendaraanotc'){
        include 'module/mod_br_otsdsk_otc/otsd_sk.php';
        

    }elseif ($_GET['module']=='rekapotsbr'){
        include 'module/laporan_gl/mod_gl_rekapots/rekapots.php';
    }elseif ($_GET['module']=='rekapotsbrotc'){
        include 'module/laporan_gl/mod_gl_rekapotsotc/rekapotsotc.php';
    }elseif ($_GET['module']=='lapbrpajak'){
        include 'module/laporan_gl/mod_gl_rekapbrpajak/rekapbrpajak.php';
        
    }elseif ($_GET['module']=='rekapinsentifrekbank'){
        include 'module/laporan/mod_rekap_insentif_rek/rekapinsentif.php';
        
    }elseif ($_GET['module']=='fincekprosesbrcab'){
        include 'module/mod_fin_cekprosbrcab/cekprosbrcab.php';
    }elseif ($_GET['module']=='finprosesbrcab'){
        include 'module/mod_fin_prosbrcab/prosbrcab.php';
    }elseif ($_GET['module']=='apvbrdssdcccab'){
        include 'module/mod_apv_brcab/apvbrcab.php';
        
    }elseif ($_GET['module']=='targettahundaerah'){
        include 'module/mst_trg_thn_daerah/targetthndaerah.php';
        
    }elseif ($_GET['module']=='importdatasales'){
        include 'module/mst_import_sales/importsales.php';
        
    }elseif ($_GET['module']=='tgtuploadtargetcab'){
        include 'module/tgt_upload_tgt_cab/uploadtgtcab.php';
    }elseif ($_GET['module']=='tgtaksiuploadtargetcab'){
        include 'module/tgt_upload_tgt_cab/hasil_uploadtgtcab.php';
    }elseif ($_GET['module']=='tgtaksiisiresettargetarea'){
        include 'module/tgt_upload_tgt_cab/isitargetareakosong.php';
    }elseif ($_GET['module']=='tgttargetcab'){
        include 'module/tgt_tgt_cab/tgtcab.php';
        
    }elseif ($_GET['module']=='tgtuploadtargetarea'){
        include 'module/tgt_upload_tgt_area/uploadtgtarea.php';
    }elseif ($_GET['module']=='tgtaksiuploadtargetarea'){
        include 'module/tgt_upload_tgt_area/hasil_uploadtgtarea.php';
    }elseif ($_GET['module']=='tgttargetarea'){
        include 'module/tgt_tgt_area/tgtarea.php';
        
    }elseif ($_GET['module']=='tgtuploadtargetcabthn'){
        include 'module/tgt_upload_tgt_cabthn/uploadtgtcabthn.php';
    }elseif ($_GET['module']=='tgtaksiuploadtargetcabthn'){
        include 'module/tgt_upload_tgt_cabthn/hasil_uploadtgtcabthn.php';
        
    }elseif ($_GET['module']=='mstsesuaidatakry'){
        include 'module/mst_kry_sesuai_atasan/krysesuaiatasan.php';
    }elseif ($_GET['module']=='listantriantransfer'){
        include 'module/mod_fin_listantriantrf/listantriantrf.php';
    }elseif ($_GET['module']=='mstisidatakaryawan'){
        include 'module/mst_isidatakaryawan/isidatakaryawan.php';
    
        
    }else{
        include 'del_session.php';
        include 'ahome.php';
    }
    ?>
</div>