
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
        <div hidden id="signatureparent">
            <div id="signature"></div>
        </div>
        <div id="tools" style="margin-top:10px;"></div>


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

        $('<input type="button" value="Save" class="btn btn-success">').bind('click', function(){
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
            //simpan data ke DB
            var icar=document.getElementById('e_idkaryawan').value;
            
            if (icar=="") {
                alert("Yang membuat masih kosong...");
                return false;
            }
            
            if (icar=="0000002200" || icar=="0000002083" || icar=="2200" || icar=="2083") {
                var sid = document.getElementById('e_kdkrynone').value;
                var snmlama = document.getElementById('e_nmkrynone2').value;
                var snmbaru = document.getElementById('e_nmkrynone').value;
                
                if (snmbaru=="") {
                    alert("nama masih kosong...");
                    return false;
                }
                
                var icnmlama=snmlama.toUpperCase();
                var icnmbaru=snmbaru.toUpperCase();
                
                if (icnmlama != icnmbaru) {
                    document.getElementById('e_kdkrynone').value="";
                    sid="";
                }
                
                if (sid=="") {
                    var cidcab = document.getElementById('cb_idcabang').value;
                    var cidarea = document.getElementById('cb_idarea').value;
                    var cidatasan1 = document.getElementById('cb_idspv').value;
                    var cidatasan2 = document.getElementById('cb_idam').value;
                    var cidatasan4 = document.getElementById('cb_idhos').value;
                    
                    if (cidcab=="") {
                        alert("cabang masih kosong...");
                        return false;
                    }
                    
                    if (cidarea=="") {
                        alert("area masih kosong...");
                        return false;
                    }
                    
                    if (cidatasan1=="" && cidatasan2=="" && cidatasan4=="") {
                        alert("atasan masih kosong...");
                        return false;
                    }
                    
                    
                }
            }else{
                
            }
            
            
            var etotsem =document.getElementById('e_totalsemua').value;
            if (etotsem === "") etotsem=0;
            if (parseInt(etotsem)==0) {
                alert("Total Rupiah Masih Kosong....");
                return 0;
            }
        
        
            var myurl = window.location;
            var urlku = new URL(myurl);
            var module = urlku.searchParams.get("module");
            var idmenu = urlku.searchParams.get("idmenu");
            
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
            
            
            document.getElementById("demo-form2").action = "module/mod_br_entrybrcashotcho/aksi_entrybrcashotcho.php?module="+module+"&act=input"+"&idmenu="+idmenu;
            document.getElementById("demo-form2").submit();
            
            
            
        });
}

$(document).ready(function() {
    ReloadTandaTangan();
})

</script>

<br/>&nbsp;
<div hidden>
    <input class='btn btn-default' type='button' name='buttonreload' value='Reload Tanda Tangan' onClick="ReloadTandaTangan()">
    <div style="color:red;">*) jika tanda tangan tidak muncul klik tombol <b>Reload Tanda Tangan</b></div>
</div>