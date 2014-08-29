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
    <title>人品大挑战</title>
    <link rel="stylesheet" type="text/css" href="/public/css/main.css">
    <script src="/public/js/jquery.min.js"></script>
    <script src="/public/js/main.js"></script>
</head>


<body>
<div class="loginbg show">
    <?php
    //$defUrl = SITE_URL . '/order/apply';
    $defUrl = SITE_URL . '/order/status';
    $redirect = isset($reUrl) && strlen($reUrl) > 0 ? SITE_URL . '/' . $reUrl : $defUrl;
    ?>
    <div class="loginbox">
        <form action="<?= SITE_URL ?>/login">
            <input name="username" class="username" placeholder="请输入手机号" type="text"/>
            <input name="password" class="password" placeholder="请输入验证码" type="text"/>
        </form>
        <input type="hidden" name="url" value="<?= $redirect ?>"/>
        <button class="loginbtn" type="button" name="btnLogin">登 录</button>
        <a href="#">重新获取验证码</a>
    </div>
</div>

</body>
<script>
    //用户登录验证
    $('button[name=btnLogin]').click(function () {
        var url = $('form').attr('action');
        $.post(url, $('form').serializeArray(), function ($rep) {
            console.log($rep);
            if ($rep.code == 0) {
                window.location.href = $('input[name=url]').val();
            } else {
                alertView($rep.message)
            }
        }, 'json')
        return false;
    })
</script>

</html>

