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
    <title>我的人品大挑战</title>
    <link rel="stylesheet" type="text/css" href="/public/css/main.css">
    <script src="/public/js/jquery.min.js"></script>
    <script src="/public/js/main.js"></script>

</head>


<body>
<a style="display:block;width:30px;height:90px;position:absolute;left:0;top:0;" href="fail.html"></a>
<a style="display:block;width:30px;height:90px;position:absolute;right:0;top:0" href="success.html"></a>

<div class="page show">
    <?php
    $order = $info['order'];
    $orderRow = (array)$order->row;

    $consignee = $info['consignee'];

    $item = $info['item'];
    $itemRow = (array)$item->row;

    $goodsRow = $info['goods'];

    $supports = $info['supportNum'];
    $lacks = $info['quota'] - $supports;
    $leftTime = $info['leftTime']

    ?>
    <header>
        <h4>你的朋友 <?= $consignee['consignee'] ?> 发起了节操测试：</h4>

        <h3><?= $orderRow['message'] ?></h3>
    </header>

    <div class="tableview">
        <section class="status1 fix">
            <img class="pic" src="<?= $goodsRow['img'] ?>"/>

            <h2>[<?= $goodsRow['origin'] ?>] <?= $goodsRow['title'] ?></h2>

            <div>总价：<span class="price"><?= sprintf('%.2f', $goodsRow['gross']) ?></span>元</div>
            <div>元筹集方式：<span class="collect"><?= $itemRow['title'] ?></span></div>
        </section>
    </div>
    <div class="statustitle fix">
        已有 <span><?= $supports ?></span>
        位朋友给予了我支持，距离成功达成目标还差<span> <?= $lacks ?> </span>人！<br/>筹集截止时间：<span><?= $leftTime ?></span>
    </div>
    <div class="line_box">
        <button type="button" class="badguy btn">贬我的人(<?= $info['abandon'] ?>人)</button>
        <button type="button" class="goodguy btn">支持我的人(<?= $supports ?>人)</button>
    </div>

</div>
</body>

</html>
