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
<div class="page show goodguy">
    <div class="statustitle">
        已有 <span><?= $supports ?></span> 位朋友给予了我支持，距离成功达成目标还差<span> <?= $quota - $supports ?> </span>人！
    </div>
    <div class="comment">
        <?php
        if ($comments) {
            foreach ($comments as $comment) {
                $dateLabel = Utils::diffDateLabel($comment['add_time'], time());
                ?>
                <ul>
                    <h2><?= $comment['fullname'] ?>：<span><?= $dateLabel ?></span></h2>
                    <li><?= $comment['content'] ?></li>
                </ul>
            <?php
            }
        } ?>
        <ul>
            <h2>Lisa：<span>3小时前</span></h2>
            <li>顶哥们！</li>
        </ul>
        <ul>
            <h2>Jacky：<span>4小时前</span></h2>
            <li>老婆，我爱你！</li>
        </ul>
        <ul>
            <h2>Linda：<span>2小时前</span></h2>
            <li>够意思吧，我一看到，就为你支付了噢，希望你不要让我失望，人气哥哥！</li>
        </ul>
        <ul>
            <h2>Lisa：<span>3小时前</span></h2>
            <li>顶哥们！</li>
        </ul>
        <ul>
            <h2>Jacky：<span>4小时前</span></h2>
            <li>老婆，我爱你！</li>
        </ul>
        <ul>
            <h2>Linda：<span>2小时前</span></h2>
            <li>够意思吧，我一看到，就为你支付了噢，希望你不要让我失望，人气哥哥！</li>
        </ul>
        <ul>
            <h2>Lisa：<span>3小时前</span></h2>
            <li>顶哥们！</li>
        </ul>
        <ul>
            <h2>Jacky：<span>4小时前</span></h2>
            <li>老婆，我爱你！</li>
        </ul>
    </div>
</div>
</body>

</html>
