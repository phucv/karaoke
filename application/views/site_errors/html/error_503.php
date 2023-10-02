<!DOCTYPE html>
<html lang=en>
<head>
    <meta charset=utf-8>
    <meta name=viewport content="initial-scale=1, minimum-scale=1, width=device-width">
    <title>Bảo trì hệ thống!!</title>
    <style>
        * {
            margin: 0;
            padding: 0
        }

        html,
        code {
            font: 15px/22px arial, sans-serif
        }

        html {
            background: #fff;
            color: #222;
            padding: 15px
        }

        body {
            margin: 7% auto 0;
            max-width: 390px;
            min-height: 180px;
            padding: 30px 0 15px
        }

        * > body {
            background: url(<?php echo config_item('base_url')?>/site_errors/robot.png) 100% 5px no-repeat;
            padding-right: 205px
        }

        p {
            margin: 11px 0 22px;
            overflow: hidden
        }

        ins {
            color: #777;
            text-decoration: none
        }

        a img {
            border: 0
        }

        @media screen and (max-width: 772px) {
            body {
                background: none;
                margin-top: 0;
                max-width: none;
                padding-right: 0
            }
        }

        #logo {
            background: url(<?php echo config_item('base_url')?>/assets/images/site/logo.png) no-repeat;
            margin-left: -5px
        }

        code {
            font-weight: bold;
        }

        @media only screen and (min-resolution: 192dpi) {
            #logo {
                background: url(<?php echo config_item('base_url')?>/assets/images/site/logo.png) no-repeat 0% 0%/100% 100%;
                -moz-border-image: url(<?php echo config_item('base_url')?>/assets/images/site/logo.png) 0
            }
        }

        @media only screen and (-webkit-min-device-pixel-ratio: 2) {
            #logo {
                background: url(<?php echo config_item('base_url')?>/assets/images/site/logo.png) no-repeat;
                -webkit-background-size: 100% 100%
            }
        }

        #logo {
            display: inline-block;
            height: 50px;
            width: 150px
        }
    </style>
</head>
<body>
<a href="<?php echo config_item('base_url'); ?>"><span id=logo aria-label=CloudClass></span></a>
<p><b>503.Không thể truy cập hệ thống</b>
</p>
<p>Vui lòng liên hệ hotline: <strong>0247 308 1515</strong> nhánh số <b>3</b> để được hỗ trợ kỹ thuật.</p>
</body>
</html>