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
<body>
<div class="easyui-panel" title="编辑用户" style="width:100%; height: 100%">
    <?php
    if ($user) {
        //会员用户
        if ($user['type'] == 1) {
            ?>
            <div style="padding:10px 60px 20px 60px">
                <form id="ff" method="post">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>"/>
                    <table cellpadding="5">
                        <tr>
                            <td>会员姓名:</td>
                            <td><input class="easyui-textbox" type="text" size="100" name="fullname"
                                       data-options="required:true" value="<?= $user['fullname'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>手机:</td>
                            <td><input class="easyui-textbox" type="text" name="mobile" value="<?= $user['mobile'] ?>"
                                       data-options="required:true">
                            </td>
                        </tr>
                        <tr>
                            <td>状态:</td>
                            <td>
                                <select class="easyui-combobox" name="status">
                                    <option value="1" selected>发布</option>
                                    <option value="0">关闭</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>备注:</td>
                            <td><input class="easyui-textbox" name="desc" value="<?= $user['desc'] ?>"
                                       data-options="multiline:true,required:false"
                                       style="width: 320px;height:120px"></td>
                        </tr>

                    </table>
                </form>


        <?php } ?>

        <?php if ($user['type'] == 0) { ?>
            <div style="padding:10px 60px 20px 60px">
                <form id="ff" method="post">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>"/>
                    <table cellpadding="5">
                        <tr>
                            <td>用户名:</td>
                            <td><input class="easyui-textbox" type="text" size="100" name="username"
                                       data-options="required:true" value="<?= $user['username'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>状态:</td>
                            <td>
                                <select class="easyui-combobox" name="status">
                                    <option value="1" selected>发布</option>
                                    <option value="0">关闭</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>备注:</td>
                            <td><input class="easyui-textbox" name="desc" value="<?= $user['desc'] ?>"
                                       data-options="multiline:true,required:false"
                                       style="width: 320px;height:120px"></td>
                        </tr>

                    </table>
                </form>


        <?php } ?>

        <div style="padding:5px 0;">
            <a id="btnSave" href="<?= SITE_URL ?>/admin/userman/edit" class="easyui-linkbutton">保存</a>
            <a id="btnCancel" href="<?= SITE_URL ?>/admin/userman" class="easyui-linkbutton">取消</a>
        </div>
        </div>
    <?php } else { ?>
        <div>用户不存在</div>
    <?php } ?>
</div>
</body>
<script>
    $('[name=status]').val(<?=$info['status']?>);
</script>

<script>
    var _validate = false;
    $('#btnSave').click(function () {
        submitForm();
        return false;
    })

    function submitForm() {
        $('form').form('submit', {
            onSubmit: function () {
                var re = $(this).form('enableValidation').form('validate');
                if (re == true) {
                    $.post($('#btnSave').attr('href'), $('form').serializeArray(), function (rep) {
                        if (rep.code == 0) {
                            location.href = $('#btnCancel').attr('href');
                        } else {
                            alert(rep.message);
                            // alert('新增商品失败');
                        }
                    }, 'json')
                }
                return false;
            }
        });
    }


</script>
</html>