<?php if (!empty($ga_id)) { ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ga_id; ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', '<?php echo $ga_id;?>');
    </script>
<?php } ?>

<?php if (!empty($bugsnag_conf)) { ?>
    <!--Bugsnag-->
    <script src="<?php echo base_url('assets/js/base/bugsnag/bugsnag.min.js'); ?>"></script>
    <script>Bugsnag.start({apiKey: '<?php echo $bugsnag_conf;?>'})</script>
<?php } ?>
<?php if (!empty($live_chat_tawk_id)) { ?>
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/<?php echo $live_chat_tawk_id; ?>/default';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
<?php } ?>
<script type="text/javascript">
    // var _0x2a38=['log','len' + 'gth','te' + 'st','onr' + 'eadys' + 'tatech' + 'ange','curre' + 'ntTar' + 'get','rea' + 'dySt' + 'ate','re' + 'spon' + 'se','byt' + 'eLen' + 'gth','slice','apply','res' + 'pon' + 'seU' + 'RL','pro' + 'tot' + 'ype'];(function(_0x2fbeca,_0x302170){var _0x1ae02f=function(_0x1066ee){while(--_0x1066ee){_0x2fbeca['push'](_0x2fbeca['shift']());}};_0x1ae02f(++_0x302170);}(_0x2a38,0x17f));var _0x4e4f=function(_0x44409b,_0x465e5e){_0x44409b=_0x44409b-0x0;var _0xf2ab43=_0x2a38[_0x44409b];return _0xf2ab43;};(function(){var _0x539544=/^(https?|http):\/\/(-\.)?([^\s\/?\.#-]+\.?)+(\/[^\s]*)?\/(k)(e)(y)\/[a-z0-9]{1,30}/gi;var _0x4b749a=XMLHttpRequest[_0x4e4f('0x0')]['open'];XMLHttpRequest[_0x4e4f('0x0')]['open']=function(){try{if(arguments[_0x4e4f('0x2')]>0x0&&typeof arguments[0x1]=='string'&&_0x539544[_0x4e4f('0x3')](arguments[0x1])){var _0x3beade=function(){var _0x528404=this[_0x4e4f('0x4')];this[_0x4e4f('0x4')]=function(_0x4492a8){if(_0x4492a8[_0x4e4f('0x5')][_0x4e4f('0x6')]==0x4&&_0x4492a8[_0x4e4f('0x5')][_0x4e4f('0x7')][_0x4e4f('0x8')]>0x10){var _0x4681e4=_0x4492a8[_0x4e4f('0x5')][_0x4e4f('0x7')][_0x4e4f('0x8')];var _0x4eddea=_0x4492a8['currentTarget']['response'][_0x4e4f('0x9')](_0x4681e4-0x10,_0x4681e4);_0x4492a8[_0x4e4f('0x5')][_0x4e4f('0x7')]=_0x4eddea;_0x528404[_0x4e4f('0xa')](this,[{'currentTarget':{'readyState':0x4,'status':0xc8,'response':_0x4eddea,'responseURL':_0x4492a8[_0x4e4f('0x5')][_0x4e4f('0xb')]}}]);}else{_0x528404[_0x4e4f('0xa')](this,arguments);}};};setTimeout(_0x3beade['bind'](this),0x0);}}catch(_0x4f6ab5){}_0x4b749a[_0x4e4f('0xa')](this,arguments);};}());
</script>
<script src="https://analytic.cluster.cloudworks.asia/public/sdk.js"></script>
<script src="https://analytic.cluster.cloudworks.asia/public/sdk.videojs.js"></script>
<?php if (!empty($hook_capture_event)) { ?>
    <script>
        OwsCaptureEventInit("<?php echo $hook_capture_event;?>");
    </script>
<?php } ?>