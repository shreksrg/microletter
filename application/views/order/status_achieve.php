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


////////////////////////////////////////////

    $order = (array)$info['order'];
    $item = (array)$info['item'];
    $goods = (array)$info['goods'];

    $orderId = $order['id'];
    $time = Utils::getDiffTime($order['add_time'], $order['achieve_time']);
    $usedTime = Utils::formatLTimeLabel($time);

    $expire = date('m月d日 H:i', $order['expire']);
    $supports = $order['paids'];
    $lacks = $order['quota'] - $supports;

    ?>
    <div class="successtitle">
        我的人品还不错<br/><span><?= $usedTime ?></span>就达成了<span><?= $item['grade_name'] ?></span>的目标！ <br/>你敢试试吗！？
    </div>

    <div class="tableview">
        <section class="status1 fix">
            <img class="pic" src="<?= $goods['img'] ?>"/>

            <h2>[<?= $goods['origin'] ?>] <?= $goods['title'] ?></h2>

            <div>总价：<span class="price"><?= $order['gross'] ?></span>元</div>
            <div>筹集方式：<span class="collect"><?= $item['title'] ?></span></div>
        </section>
    </div>
    <div class="delivertips">商品将于2个工作日内快递至您填写的地址</div>
    <button type="button" class=" btn">分享到朋友圈测人品</button>
    <div class="line_box">
        <a class="gohome" href="<?= SITE_URL ?>/comment/show?type=1&orderId=<?= $orderId ?>">查看谁支持了我</a>
    </div>
</div>
</body>

</html>
