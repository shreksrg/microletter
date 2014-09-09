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
    <title>测试商品详情</title>
    <link rel="stylesheet" type="text/css" href="/public/css/main.css">
    <script src="/public/js/jquery.min.js"></script>
    <script src="/public/js/main.js"></script>
</head>

<body>
<div class="page show">
    <?php
    $itemId = 0;
    if ($item->row) {
        $itemRow = (array)$item->row;
        $itemId = $itemRow['id'];
        $goodsRow = $item->goods->row;
        $goodsRow = $goodsRow ? (array)$goodsRow : null;
        ?>
        <div class="itemdetail_banner">
            <img src="<?= $goodsRow['img'] ?>.cover.jpg"/>
        </div>
        <div class="itemdetail_text">
            <h2>【<?=Matcher::matchOrigin($goodsRow['origin']) ?>】 <?= $goodsRow['title'] ?></h2>

            <div>总价：<span class="price"><?= sprintf('%.2f', $itemRow['gross']) ?></span> 元</div>
            <div>筹集方式：<span class="collect"><?= $itemRow['title'] ?></span></div>
            <a id="itemId" href="<?= SITE_URL ?>/order/apply"></a>
        </div>
        <div class="itemdetail_title">商品详情<span>Product Details</span></div>
        <div class="itemdetail_pic">
            <?= $goodsRow['desc'] ?>
        </div>
    <?php } ?>
</div>
</body>
<script>
    $('#itemId').click(function () {
        document.cookie = "itemId=<?=$itemId?>" + ";path=/";
        return true;
    })

</script>
</html>
