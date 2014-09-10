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
    <title>支付确认</title>
    <link rel="stylesheet" type="text/css" href="/public/css/main.css">
    <script src="/public/js/jquery.min.js"></script>
    <script src="/public/js/main.js"></script>
</head>


<body>
<div class="page show">
    <header class="fix">
        <h4 class="fix">
            <center>我愿意支持<?= $originator ?>发起的人品测试</center>
        </h4>
        <h3 class="fix">需要支付<span><?= sprintf('%.2f', $amount) ?></span>元</h3>
    </header>
    <div class="tableview">
        <section class="status1 fix">
            <img class="pic" src="<?= $goods['img'] ?>"/>

            <h2>[<?= Matcher::matchOrigin($goods['origin']) ?>] <?= $goods['title'] ?></h2>

            <div>总价：<span class="price"><?= $order['gross'] ?></span>元</div>
            <div>筹集方式：<span class="collect"><?= $item['title'] ?></span></div>
        </section>
    </div>
    <form action="<?= SITE_URL ?>/payment" method="post">
        <input type="hidden" name="orderId" value="<?= $order['id'] ?>"/>
        <input type="hidden" name="type" value="2"/>
        <button type="submit" id="btnSubmit" class="pay btn">确定支付</button>
    </form>
</div>
</body>
<script>
    /*$('#btnSubmit').click(function () {
     return true;
     })*/
</script>

</html>
