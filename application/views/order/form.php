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
    <title>填写基本信息</title>
    <link rel="stylesheet" type="text/css" href="/public/css/main.css">
    <script src="/public/js/jquery.min.js"></script>
    <script src="/public/js/main.js"></script>
</head>


<body>
<div class="page show">
    <form id="frmApply" action="<?= SITE_URL ?>/order/apply" class="submit">
        <ul><label>为了更快达成目标，给你的朋友说点什么吧：</label><textarea name="message" class="say">我发起了测人品，别让我的节操碎了一地!</textarea></ul>
        <ul><label>我的地址：</label><textarea name="address" class="address"></textarea></ul>
        <ul><label>我的手机：</label><input name="mobile" class="phone" type="text"/></ul>
        <ul><label>手机验证：</label><input name="captcha" class="code" type="text"/>
            <button class="resend" onclick="return false;">重新获取</button>
        </ul>
        <ul><label>我的姓名：</label><input name="fullName" class="name" type="text"/></ul>
        <input type="hidden" name="itemId" value="<?= $itemId ?>"/>
    </form>
    <button name="btnSubmit" type="button" class="finish btn">确定</button>
    <input type="hidden" name="url" value="<?= SITE_URL ?>/order/confirm"/>
</div>
</body>
<script>
    $('button[name=btnSubmit]').click(function () {
        var reChk = checkSubmitAll();
        if (reChk == true) {
            var url = $('#frmApply').attr('action');
            $.post(url, $('#frmApply').serializeArray(), function (rep) {
                if (rep.code == 0) {
                    var reUrl = $('input[name=url]').val() + '?orderId=' + rep.data.orderId;
                    var mobile = parseInt($.trim($('input[name=mobile]').val()));
                    document.cookie = "_urMobile=" + mobile + ";path=/";
                    location.href = reUrl;
                } else alert(rep.message);
            }, 'json')
        }
        return false;
    })
</script>
</html>
