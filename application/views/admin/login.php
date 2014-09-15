<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= MCIRO_WEBSITE_TITLE ?></title>
    <link rel="stylesheet" type="text/css" href="/public/js/ui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="/public/js/ui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="/public/js/ui/themes/common.css">
    <script type="text/javascript" src="/public/js/ui/jquery.min.js"></script>
    <script type="text/javascript" src="/public/js/ui/jquery.easyui.min.js"></script>
</head>
<body>
<div style="margin-top: 10em"></div>
<div style="width: 600px; margin:0 auto">
    <h2>人品大挑战微信应用管理V1.0</h2>


    <div style="margin:20px 0;"></div>
    <div class="easyui-panel" title="管理员登录" style="width:400px; margin: 0 auto">
        <div style="padding:10px 60px 20px 60px">
            <form id="ff" action="<?= SITE_URL ?>/admin/loginman" class="easyui-form" method="post"
                  data-options="novalidate:true">
                <table cellpadding="5">
                    <tr>
                        <td>用户名:</td>
                        <td><input class="easyui-textbox" type="text" name="username" data-options="required:true">
                        </td>
                    </tr>
                    <tr>
                        <td>密 码:</td>
                        <td><input class="easyui-textbox" type="password" name="password"
                                   data-options="required:true"></td>
                    </tr>

                </table>
            </form>
            <input type="hidden" name="reUrl" value="<?= SITE_URL ?>/admin/panel"/>

            <div style="text-align:center;padding:5px">
                <a style="widows: 200px;" href="javascript:void(0)" class="easyui-linkbutton"
                   onclick="submitForm()">登录</a>
            </div>
        </div>
    </div>
</div>
<script>
    function submitForm() {
        $('#ff').form('submit', {
            onSubmit: function () {
                var reVal = $(this).form('enableValidation').form('validate');
                if (reVal) {
                    $.post($('#ff').attr('action'), $('#ff').serializeArray(), function (rep) {
                        if (rep.code == 0) {
                            location.href = $('[name=reUrl]').val();
                        } else alert('用户名或密码错误');
                    }, 'json')
                }
                return false;
            }
        });
    }
    function clearForm() {
        $('#ff').form('clear');
    }
</script>
</body>

</html>