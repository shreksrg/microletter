<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>

<body onload="submit();">
<form id="alipayment" action="<?= SITE_URL ?>/payment/alipay" method="post" target="_blank">
    <div id="body" style="clear:left">
        <dl class="content">
            <dt>卖家支付宝帐户：</dt>
            <dd>
                <span class="null-star">*</span>
                <input size="30" name="WIDseller_email" value="mkh@hailiang.com"/>
                <span></span>
            </dd>
            <dt>商户订单号：</dt>
            <dd>
                <span class="null-star">*</span>
                <input size="30" name="WIDout_trade_no" value="<?= $order['pay_sn'] ?>"/>
                <span></span>
            </dd>
            <dt>订单名称：</dt>
            <dd>
                <span class="null-star">*</span>
                <input size="30" name="WIDsubject" value="<?= $order['pay_title'] ?>"/>
                <span></span>
            </dd>
            <dt>付款金额：</dt>
            <dd>
                <span class="null-star">*</span>
                <input size="30" name="WIDtotal_fee" value="<?= $order['amount'] ?>"/>
                <span></span>
            </dd>
            <dt></dt>
            <dd>

            </dd>
        </dl>
    </div>
</form>

</body>
<script>
    function submit() {
        document.getElementById('alipayment').submit();
    }
</script>
</html>