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
    <title>他的人品大挑战</title>
    <link rel="stylesheet" type="text/css" href="/public/css/main.css">
    <script src="/public/js/jquery.min.js"></script>
    <script src="/public/js/main.js"></script>

</head>


<body>
<div class="page show">
    <?php

    $user = (array)$user;
    $order = (array)$info['order'];
    $item = (array)$info['item'];
    $goods = (array)$info['goods'];

    $orderId = $order['id'];
    $time = Utils::getDiffTime(time(), $order['expire']);
    $leftTime = Utils::formatLTimeLabel($time);

    $expire = date('m月d日 H:i', $order['expire']);
    $lacks = $order['quota'] - $order['paids'];

    ?>
    <header>
        <h4>你的朋友 <?= $user['fullname'] ?> 发起了节操测试：</h4>

        <h3><?= $order['message'] ?></h3>
    </header>

    <div class="tableview">
        <section class="status1 fix">
            <img class="pic" src="<?= $goods['img'] ?>"/>

            <h2>[<?= Matcher::matchOrigin($goods['origin']) ?>] <?= $goods['title'] ?></h2>

            <div>总价：<span class="price"><?= sprintf('%.2f', $order['gross']) ?></span>元</div>
            <div>元筹集方式：<span class="collect"><?= $item['title'] ?></span></div>
        </section>
    </div>
    <?php
    if ($state == 'on') {
        ?>
        <div class="time">筹集截止时间：<span><?= $leftTime ?></span> （<?= $expire ?>）<br/>离筹集成功还需<span> <?= $lacks ?> </span>人支持
        </div>
        <div class="conts">如果觉得Ta人品还行，可以点击下方按钮支持他。<br/>到达截止时间还未筹集成功，所有款项将被退还。</div>
        <div class="line_box">
            <button type="button" id="btnRefuse" class="disagree btn">支持个毛~</button>
            <button type="button" id="btnSupport" class="agree btn">人品还行支持他</button>
            <input type="hidden" name="orderId" value="<?= $orderId ?>"/>
        </div>
        <script>
            var siteUrl = '<?=SITE_URL?>';
            var orderId = $('input[name=orderId]').val();
            //支付订单
            $('#btnSupport').click(function () {
                var url = siteUrl + '/payment?orderId=' + orderId;
                location.href = url;
                return false;

            })

            //拒绝订单
            $('#btnRefuse').click(function () {
                var url = siteUrl + '/comment/message?orderId=' + orderId + '&payId=0';
                location.href = url;
                return false;
            })
        </script>
    <?php } ?>


    <?php
    if ($state == 'achieve') {
        ?>
        <!--测试进成功 start-->
        <div class="time">该项挑战已于 <span><?= date('Y年m月d日', $order['achieve_time']) ?></span> 结束</div>
        <div class="status_win"></div>
        <!--over-->
    <?php } ?>

    <?php
    if ($state == 'fail') {
        ?>
        <!--测试进失败 start-->
        <div class="time">该项挑战已于 <span><?= $expire ?></span> 结束</div>
        <div class="status_lose"></div>
        <!--over-->
    <?php } ?>

    <div class="line_box">
        <a class="gohome" href="<?= SITE_URL ?>/item">发起的人品大挑战</a>
    </div>
</div>
</body>

</html>
