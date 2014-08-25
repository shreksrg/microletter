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
    <title>人品大挑战</title>
    <link rel="stylesheet" type="text/css" href="/public/css/main.css">
    <script src="/public/js/jquery.min.js"></script>
    <script src="/public/js/main.js"></script>
</head>


<body>
<div class="page show">
    <?php
    if ($detail) {
        $banner = $detail['img'] . '.banner.jpg';

        ?>
        <div class="itemdetail_banner">
            <img src="<?= $banner ?>"/>
        </div>
        <div class="itemdetail_text">
            <h2><?= $detail['title'] ?></h2>

            <div>总价：<span class="price"><?= $detail['gross'] ?></span> 元</div>
            <div>筹集方式：<span class="collect"><?= $detail['qu_desc'] ?></span></div>
            <a href="submit.html"></a>
        </div>
        <div class="itemdetail_title">商品详情<span>Product Details</span></div>
        <div class="itemdetail_pic">
            <?= $detail['desc'] ?>
        </div>
    <?php } ?>
</div>
</body>

</html>
