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


<div class="page show">
    <?php

    ///////////////////////////////////////////////////////

    $order = (array)$info['order'];
    $item = (array)$info['item'];
    $goods = (array)$info['goods'];

    $orderId = $order['id'];
    $time = Utils::getDiffTime(time(), $order['expire']);
    $leftTime = Utils::formatLTimeLabel($time);

    $expire = date('m月d日 H:i', $order['expire']);
    $supports = $order['paids'];
    $lacks = $order['quota'] - $supports;

    ?>
    <header>
        <h4> [<?=$fullName?>] 发起了节操测试：</h4>

        <h3><?= $order['message'] ?></h3>
    </header>

    <div class="tableview">
        <section class="status1 fix">
            <img class="pic" src="<?= $goods['img'] ?>"/>

            <h2>[<?= $goods['origin'] ?>] <?= $goods['title'] ?></h2>

            <div>总价：<span class="price"><?= sprintf('%.2f', $order['gross']) ?></span>元</div>
            <div>筹集方式：<span class="collect"><?= $item['title'] ?></span></div>
        </section>
    </div>
    <div class="statustitle fix">
        已有 <span><?= $order['paids'] ?></span>
        位朋友给予了我支持，距离成功达成目标还差<span> <?= $lacks ?> </span>人！<br/>筹集截止时间：<span><?= $leftTime ?></span>
    </div>
    <div class="line_box">
        <button id="bvRefuse" type="button" class="badguy btn">贬我的人(<?= $abandon ?>人)</button>
        <button id="bvSupport" type="button" class="goodguy btn">支持我的人(<?= $supports ?>人)</button>
    </div>

</div>
</body>
<script>
    $('#bvRefuse').click(function () {
        var num = parseInt(<?=$abandon?>);
        // if (num > 0)
        location.href = "<?=SITE_URL?>/comment/show?type=0&orderId=<?=$orderId?>";
    })

    $('#bvSupport').click(function () {
        var num = parseInt(<?=$supports?>);
        // if (num > 0)
        location.href = "<?=SITE_URL?>/comment/show?type=1&orderId=<?=$orderId?>";
        return false;
    })


</script>

<script>
    var shareData = {
        'imgUrl': "http://<?=SERVER_NAME?>/public/img/wexingimg.jpg",
        'link': "<?=SITE_URL?>/item/order?id=<?=$orderId?>",
        'title': "<?=$message?>",
        'desc': "我的人品挑战测试"
    };

    _namespace_micro.winxinShare(shareData);
</script>

</html>
