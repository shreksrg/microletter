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
    <title>成功支持</title>
    <link rel="stylesheet" type="text/css" href="/public/css/main.css">
    <script src="/public/js/jquery.min.js"></script>
    <script src="/public/js/main.js"></script>

</head>


<body>
<div class="page show">
    <div class="agreetitle">
        你已经<span>成功支持</span>了您的朋友 <span><?= $consignee ?></span><br/>你可以给Ta发送留言
    </div>
    <form action="<?= SITE_URL ?>/comment/message" class="submit">
        <ul><label>我的姓名：</label><input name="fullName" class="name" type="text"/></ul>
        <ul><label>留言内容：</label><textarea name="content" class="say">我刚支持了你的人品测试，怎么样够朋友吧~</textarea></ul>

        <input type="hidden" name="token" value="<?= $token ?>"/>
        <input type="hidden" name="orderId" value="<?= $orderId ?>"/>
        <input type="hidden" name="payId" value="<?= $payId ?>"/>
    </form>
    <button id="btnSendMsg" type="button" class="sending btn">发送留言</button>
    <div class="line_box">
        <a class="gohome" href="<?= SITE_URL ?>/item">发起的人品大挑战</a>
    </div>
</div>
</body>
<script>

    var _validation = false;
    _namespace_micro.closeMaskCall = function () {
        if (_validation === true)
            location.href = "<?=SITE_URL?>/item";
    }

    _namespace_micro.comment = {'chkForm': function () {
        if ($.trim($('input[name=fullName]').val()) == "") {
            alertView("请填写您的姓名");
            return  false;
        }
        if ($.trim($('[name=content]').val()) == "") {
            alertView("请填写您的留言");
            return  false;
        }
        _validation = true;
    }}

    $('#btnSendMsg').click(function () {
        var frm = $('form');
        _namespace_micro.comment.chkForm();
        if (_validation === false)  return false
        $.post(frm.attr('action'), frm.serializeArray(), function (rep) {
            if (rep.code == 0) {
                alertView("支持成功！立即开始我的人品测试");
            } else {
                var errCode = rep.code;
                alertView(rep.message)
            }
        }, 'json')
        return false;
    })
</script>

</html>
