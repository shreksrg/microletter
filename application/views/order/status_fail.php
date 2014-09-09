<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,minimal-ui">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache, must-revalidate">

    <link rel="shortcut icon" href="/public/img/favicon.ico">
    <meta http-equiv="expires" content="-1">
    <title>他的人品大挑战</title>
    <link rel="stylesheet" type="text/css" href="/public/css/main.css">
    <script src="/public/js/jquery.min.js"></script>
    <script src="/public/js/main.js"></script>

</head>


<body>
<div class="page show">
    <?php

    $order = (array)$info['order'];
    $item = (array)$info['item'];
    $goods = (array)$info['goods'];

    $orderId = $order['id'];

    $expire = date('m月d日 H:i', $order['expire']);
    $supports = $order['paids'];
    $lacks = $order['quota'] - $supports;

    ?>
    <div class="failtitle">
        我的人品稀烂！<br/>只有<?= $supports ?>位朋友给予了我支持，距离成功达成 <br/><span><?= $item['grade_name'] ?></span>目标还差
        <span><?= $lacks ?></span> 人！
    </div>

    <button id="btnRetry" type="button" class="retry btn">不信邪~再测一次！</button>
    <div class="line_box">
        <a class="gohome" href="###">分享到朋友圈</a>
        <a id="bvSupport" class="gohome" href="<?= SITE_URL ?>/comment/show?type=1&orderId=<?= $orderId ?>">查看谁支持了我</a>
    </div>
</div>
</body>
<script>
    $('#btnRetry').click(function () {
        location.href = "<?=SITE_URL?>/item";
        return false;
    })
</script>

</html>
