
<?PHP
    $now=date("m/d/Y/h:i:s");
    $now=date("Ymd");
    ?>

    <style type="text/css">

        #signatureparent {
            color:darkblue;
            color:#000;
            background-color:darkgrey;
            /*max-width:600px;*/
            padding:20px;
            /*width:400px;*/
        }
        /*This is the div within which the signature canvas is fitted*/
        #signature {
            border: 2px dotted black;
            background-color:lightgrey;
        }
        .btn_sig{
            font-size: 16px;
            background: linear-gradient(#ffbc00 5%, #ffdd7f 100%);
            border: 1px solid #e5a900;
            color: #4E4D4B;
            font-weight: bold;
            cursor: pointer;
            width: 100px;
            border-radius: 5px;
            padding: 5px 0;
            outline: none;
            margin-top: 5px;
            margin-left: 1%;
            margin-bottom: 1%;
        }
        .btn_sig:hover{
            background: linear-gradient(#ffdd7f 5%, #ffbc00 100%);
        }
    </style>

<div>
    <div id="content"  class="main">
        
        
        <div><p hidden>Tampil :</p><div id="displayarea"></div></div>


        <div id="signatureparent">
            <div id="signature"></div>
        </div>
        <br/>
        <div id="tools"></div>

    </div>
    <div id="scrollgrabber"></div>
</div>
<script>
/*  @preserve
jQuery pub/sub plugin by Peter Higgins (dante@dojotoolkit.org)
Loosely based on Dojo publish/subscribe API, limited in scope. Rewritten blindly.
Original is (c) Dojo Foundation 2004-2010. Released under either AFL or new BSD, see:
http://dojofoundation.org/license for more information.
*/
(function($) {
        var topics = {};
        $.publish = function(topic, args) {
            if (topics[topic]) {
                var currentTopic = topics[topic],
                args = args || {};

                for (var i = 0, j = currentTopic.length; i < j; i++) {
                    currentTopic[i].call($, args);
                }
            }
        };
        $.subscribe = function(topic, callback) {
            if (!topics[topic]) {
                topics[topic] = [];
            }
            topics[topic].push(callback);
            return {
                "topic": topic,
                "callback": callback
            };
        };
        $.unsubscribe = function(handle) {
            var topic = handle.topic;
            if (topics[topic]) {
                var currentTopic = topics[topic];

                for (var i = 0, j = currentTopic.length; i < j; i++) {
                    if (currentTopic[i] === handle.callback) {
                        currentTopic.splice(i, 1);
                    }
                }
            }
        };
})(jQuery);
</script>
<?php
include('tanda_tangan_base64/src/mobile.php');
?>



    <script src="tanda_tangan_baru/src/jSignature.js"></script>
    <script src="tanda_tangan_baru/src/plugins/jSignature.CompressorBase30.js"></script>
    <script src="tanda_tangan_baru/src/plugins/jSignature.CompressorSVG.js"></script>
    <script src="tanda_tangan_baru/src/plugins/jSignature.UndoButton.js"></script> 
    <!--<script src="tanda_tangan_baru/src/plugins/signhere/jSignature.SignHere.js"></script> -->
    
    
<script>
function ReloadTandaTangan(){
        // This is the part where jSignature is initialized.
        //var $sigdiv = $("#signature").jSignature({'UndoButton':true})
        //var $sigdiv = $("#signature").jSignature({ 'UndoButton': true, 'width': 370, 'height': 400 })
        <?PHP
        if(mobile_device_detect(true,true,true,true,false,false)) {
            ?> var $sigdiv = $("#signature").jSignature({ 'UndoButton': true, 'width': 270, 'height': 300 }) <?PHP
        }else{
            ?> var $sigdiv = $("#signature").jSignature({ 'UndoButton': true, 'width': 370, 'height': 400 }) <?PHP
        }
        ?>
        
        
        // All the code below is just code driving the demo. 
        , $tools = $('#tools')
        , $extraarea = $('#displayarea')
        , pubsubprefix = 'jSignature.demo.'


        var export_plugins = $sigdiv.jSignature('listPlugins','export')
        , chops = ['<span><b></b></span>','']
        , name
        for(var i in export_plugins){
            if (export_plugins.hasOwnProperty(i)){
                    name = export_plugins[i]
                    chops.push('')

            }
        }
        chops.push('')

        $(chops.join('')).bind('change', function(e){
            if (e.target.value !== ''){
                    var data = $sigdiv.jSignature('getData', e.target.value)
                    $.publish(pubsubprefix + 'formatchanged')
                    if (typeof data === 'string'){
                            $('textarea', $tools).val(data)
                    } else if($.isArray(data) && data.length === 2){
                            $('textarea', $tools).val(data.join(','))
                            $.publish(pubsubprefix + data[0], data);
                    } else {
                            try {
                                    $('textarea', $tools).val(JSON.stringify(data))
                            } catch (ex) {
                                    $('textarea', $tools).val('Not sure how to stringify this, likely binary, format.')
                            }
                    }
            }
        }).appendTo($tools)

        $('<input type="button" value="Simpan" class="btn btn-success">').bind('click', function(){
                Tampilkan("image");
        }).appendTo($tools)

        $('<input type="button" value="Reset" class="btn btn-default">').bind('click', function(e){
                $sigdiv.jSignature('reset')
        }).appendTo($tools)
        

        $('<div hidden><textarea style="width:100%;height:7em;" name="txtgambar" id="txtgambar"></textarea></div>').appendTo($tools)

        $.subscribe(pubsubprefix + 'formatchanged', function(){
                $extraarea.html('')
        })

        function Tampilkan(e){
            if (e !== ''){                            
                var data = $sigdiv.jSignature('getData', e)
                $.publish(pubsubprefix + 'formatchanged')
                if (typeof data === 'string'){
                    $('textarea', $tools).val(data)
                } else if($.isArray(data) && data.length === 2){
                        $('textarea', $tools).val(data.join(','))
                        $.publish(pubsubprefix + data[0], data);
                } else {
                        try {
                                $('textarea', $tools).val(JSON.stringify(data))
                        } catch (ex) {
                                $('textarea', $tools).val('Not sure how to stringify this, likely binary, format.')
                        }
                }
            }
        }
        $.subscribe(pubsubprefix + 'image/png;base64', function(data) {
            ShowDataAtasan();
            ShowDataJumlah();
            
            var iid = document.getElementById('e_id').value;
            var itgl = document.getElementById('e_tglberlaku').value;
            var ibulan = document.getElementById('e_bulan').value;
            var ikry = document.getElementById('cb_karyawan').value;
            var icabid = document.getElementById('cb_cabang').value;
            var icoap = document.getElementById('cb_coa').value;
            var ijml = document.getElementById('e_jml').value;
            var iket = document.getElementById('e_ket').value;
            var isaldo=document.getElementById('e_saldorp').value;
            var isaldo_tbh=document.getElementById('e_saldorp_tambah').value;
            var irppcm=document.getElementById('e_pcrp').value;
			
			var ipcmasl=document.getElementById('e_rppc').value;//PC ASLI
            
            if (ikry=="") {
                alert("Pembuat masih kosong...");
                return false;
            }
            if (icabid=="") {
                alert("Cabang harus diisi...");
                return false;
            }
            if (icoap=="") {
                //alert("COA harus dipilih...");
                //return false;
            }
            if (ijml=="" || ijml=="0") {
                alert("Jumlah Permintaan Masih kosong...");
                return false;
            }
            if (iket=="") {
                //alert("keterangan harus diisi...");
                //return false;
            }
            
            var newchar = '';
            
            if (irppcm=="") irppcm="0";
            irppcm = irppcm.split(',').join(newchar);
            
            //PC ASL
            if (ipcmasl=="") ipcmasl="0";
            ipcmasl = ipcmasl.split(',').join(newchar);
            
            if (parseFloat(ipcmasl)<=0) {
                alert("Petty Cash NOL...");
                return false;
            }
    
            if (isaldo=="") isaldo="0";
            if (isaldo_tbh=="") isaldo_tbh="0";
            if (ijml=="") ijml="0";
            
            isaldo = isaldo.split(',').join(newchar);
            isaldo_tbh = isaldo_tbh.split(',').join(newchar);
            ijml = ijml.split(',').join(newchar);
            
            //alert(parseFloat(ijml));
            //alert(parseFloat(irppcm));
            
            //if (parseFloat(isaldo_tbh)<0) {
            if (parseFloat(ijml)>parseFloat(irppcm)) {
                alert("Total Rp. tidak boleh melebihi Petty Cash...\n\
Jika Saldo Minus, silakan minta tambahan saldo pc untuk dibuka.");
                return false;
            }
    
            
            $.ajax({
                type:"post",
                url:"module/mod_br_kaskecilcab/viewdata.php?module=cekdatasudahada",
                data:"utgl="+itgl+"&uid="+iid+"&ukry="+ikry+"&ucabid="+icabid+"&ucoap="+icoap+"&ubulan="+ibulan,
                success:function(data){
                    //var tjml = data.length;
                    //alert(data);
                    //return false;
                    
                    if (data=="boleh") {
    
                        //simpan data ke DB
                        var cmt = confirm('pastikan tanda tangan terisi....!!! jika sudah terisi klik OK');
                        if (cmt == false) {
                            return false;
                        }            

                        var uttd = data;//gambarnya

                        var myurl = window.location;
                        var urlku = new URL(myurl);
                        var module = urlku.searchParams.get("module");
                        var idmenu = urlku.searchParams.get("idmenu");
                        var act = urlku.searchParams.get("act");

                        var ket="input";
                        if (act=="editdata") ket="update";

                        document.getElementById("demo-form2").action = "module/mod_br_kaskecilcab/aksi_kaskecilcab.php?module="+module+"&act="+ket+"&idmenu="+idmenu;
                        document.getElementById("demo-form2").submit();
            
                    }else{
                        alert(data);
                    }
                }
            });
            
            
        });
}

$(document).ready(function() {
    ReloadTandaTangan();
})

</script>

<br/>&nbsp;
<input class='btn btn-default' type='button' name='buttonreload' value='Reload Tanda Tangan' onClick="ReloadTandaTangan()">
<div style="color:red;">*) jika tanda tangan tidak muncul klik tombol <b>Reload Tanda Tangan</b></div>