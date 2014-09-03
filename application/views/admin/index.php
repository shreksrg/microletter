<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Full Layout - jQuery EasyUI Demo</title>
    <link rel="stylesheet" type="text/css" href="/public/js/ui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="/public/js/ui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="/public/js/ui/themes/common.css">
    <script type="text/javascript" src="/public/js/ui/jquery.min.js"></script>
    <script type="text/javascript" src="/public/js/ui/jquery.easyui.min.js"></script>
</head>
<body class="easyui-layout">
<div data-options="region:'north',border:false" style="height:60px;background:#B3DFDA;padding:10px">
    人品大挑战管理
</div>
<div data-options="region:'west',split:true,title:'West'" style="width:150px;padding:10px;">

    <ul>
        <li><a class="btnNav" href="<?= SITE_URL ?>/admin/goodsman">商品管理</a></li>
        <li><a class="btnNav" href="<?= SITE_URL ?>/admin/itemman">项目管理</a></li>
        <li><a href="">订单管理</a></li>
        <li><a href="">退单管理</a></li>
        <li><a href="">用户管理</a></li>
    </ul>
</div>
<div data-options="region:'south',border:false" style="height:50px;background:#A9FACD;padding:10px;">上海明康汇 copyright
    2014~2015
</div>
<div id="mainArea" data-options="region:'center'">
    <iframe src="" frameborder="0" width="100%" height="100%"></iframe>
</div>
</body>
<script>
    $('.btnNav').click(function () {
        $('iframe').attr('src', $(this).attr('href'));
        return false;
    })
</script>
</html>