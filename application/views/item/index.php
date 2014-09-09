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
<div class="indexbg show"></div>
<div class="page">
    <div class="banner"><img src="/public/img/banner.png"/></div>
    <div class="tableview">
        <?php
        if ($list) {
            foreach ($list as $itemId => $itemObj) {
                $item = (array)$itemObj->row;
                $goods = (array)$itemObj->goods->row;
                if (!$goods) $goods = null;
                ?>
                <a href="<?= SITE_URL ?>/item/detail?id=<?= $itemId ?>">
                    <section class="status<?= $item['grade'] ?>">
                        <img class="pic" src="<?= $goods['img'] ?>"/>

                        <h2>【<?= Matcher::matchOrigin($goods['origin']) ?>】<?= $goods['title'] ?></h2>

                        <div>总价：<span class="price">  <?= sprintf('%.2f', $item['gross']) ?></span>元</div>
                        <div>筹集方式：<span class="collect"><?= $item['title'] ?></span></div>
                    </section>
                </a>
            <?php
            }
        } else {
            echo '暂无筹资项目';
        }
        ?>

    </div>
</div>
</body>

</html>
