<?php use common\helpers\ArrayHelper; ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <style type="text/css">
    /* reset */
    html, body, div, span, h1, h2, h3, h4, h5, h6, p, pre, a, code, em, img, strong, b, i, ul, li{
        margin: 0;
        padding: 0;
        border: 0;
        font-size: 100%;
        font: inherit;
        vertical-align: baseline;
    }
    body {
        line-height: 1;
    }
    ul {
        list-style: none;
    }

    /* base */
    a {
        text-decoration: none;
    }
    a:hover{
        text-decoration: underline;
    }
    h1, h2, h3, p, img, ul li{
        font-family: Arial,sans-serif;
        color: #505050;
    }
    /*corresponds to min-width of 860px for some elements (.header .footer .element ...)*/
    @media screen and (min-width: 800px) {
        html,body{
            overflow-x: hidden;
        }
    }
    /* call stack */
    .call-stack {
        padding: 0 10px;
    }
    .call-stack ul li {
        padding: 6px 0;
        line-height: 26px;
    }
    .call-stack ul li:hover {
        background: #edf9ff;
    }
    </style>
</head>

<body>
    <div class="call-stack">
        <ul>
            <?php foreach ($message as $info): ?>
            <li>
                <span class="item-number"><?= $info ?></span>
            </li>
            <?php endforeach ?>
        </ul>
    </div>
</body>
</html>