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
    <script type="application/javascript" src="/public/js/iscroll.js"></script>
    <script type="application/javascript" src="/public/js/iscroll_config.js"></script>
</head>


<body>
<div id="wrapper">
    <div id="scroller">
        <div id="pullDown">
            <span class="pullDownIcon"></span><span class="pullDownLabel">下拉刷新...</span>
        </div>

        <div class="statustitle">
            已有 <span><?= $supports ?></span> 位朋友给予了我支持，距离成功达成目标还差<span> <?= $quota - $supports ?> </span>人！
        </div>

        <div id="thelist" class="comment">
            <?php
            if ($comments) {
                foreach ($comments as $comment) {
                    $dateLabel = Utils::diffDateLabel($comment['add_time'], time());
                    ?>
                    <ul>
                        <h2><?= strlen($comment['fullname'])>0 ?$comment['fullname']:"你的朋友" ?>：<span><?= $dateLabel ?></span></h2>
                        <li><?= $comment['comment'] ?></li>
                    </ul>
                <?php
                }
            } else {
                echo '尚无评论';
            } ?>
        </div>

        <div id="pullUp">
            <span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多...</span>
        </div>

    </div>
</div>

<div id="footer"></div>
</body>
<script>
    var _commentType =<?=$type?>;
    var _orderId =<?=$orderId?>;
    var _requestUrl = '<?=SITE_URL?>/comment/getList';

    _namespace_micro_comment.contentLoad();
</script>
</html>
