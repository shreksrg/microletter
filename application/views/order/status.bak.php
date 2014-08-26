<?php
$message = '我发起了测人品，别让我的节操碎了一地';
if (isset($detail['message'])) {
    $message = $detail['message'];
}

$goods = null;
if (isset($detail['goods'])) {
    $goods = $detail['goods'];
}

$leftDays = null;
if (isset($detail['leftDays'])) {
    $leftDays = $detail['leftDays'];
}

$expire = null;
if (isset($detail['expire'])) {
    $expire = $detail['expire'];
}

$lacks = null;
if (isset($detail['lacks'])) {
    $lacks = $detail['lacks'];
}

$consignee = null;
if (isset($detail['consignee'])) {
    $consignee = $detail['consignee'];
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,minimal-ui">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache, must-revalidate">

    <link rel="shortcut icon" href="img/favicon.ico">
    <meta http-equiv="expires" content="-1">
    <title>信息确认</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/main.js"></script>

</head>


<body>
<div class="page show">
    <header>
        <h4>你即将发起一个节操测试：</h4>

        <h3><?= $message ?></h3>
    </header>

    <div class="tableview">
        <section class="status1 fix">
            <img class="pic" src="img/item1.jpg"/>

            <h2><?= $goods['title'] ?></h2>

            <div>总价：<span class="price"><?= sprintf('%.2f', $goods['price']) ?></span>元</div>
            <div>元筹集方式：<span class="collect"><?= $goods['plan_desc'] ?></span></div>
        </section>
    </div>
    <div class="time">筹集截止时间：<span><?= $leftDays ?></span> 天 （<?= $expire ?>）<br/>离筹集成功还需<span> <?= $lacks ?> </span>人支持
    </div>
    <div class="information">联系人：<?= $consignee['consignee'] ?><br/>联系电话：<?= $consignee['mobile'] ?>
        <br/>收货地址：<?= $consignee['address'] ?><span
            class="tip">请确认收货地址，否则可能无法收到礼品</span></div>
    <!--<button type="button" class="share btn">点击右上方的按钮分享到朋友圈测人品</button>-->
    <div class="sharetipmask show"></div>
</div>
</body>

</html>
