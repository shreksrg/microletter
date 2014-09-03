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
<div class="easyui-panel" title="编辑商品" style="width:100%; height: 100%">
    <?php
    if ($info) {

        ?>
        <div style="padding:10px 60px 20px 60px">
            <form id="ff" method="post">
                <input type="hidden" name="id" value="<?= $info['id'] ?>"/>
                <table cellpadding="5">
                    <tr>
                        <td>标题:</td>
                        <td><input class="easyui-textbox" type="text" size="100" name="title"
                                   data-options="required:true" value="<?= $info['title'] ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>产地:</td>
                        <td>
                            <select class="easyui-combobox" name="source">
                                <option value="0">自产</option>
                                <option value="1">进口</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>价格:</td>
                        <td><input class="easyui-textbox" type="text" name="price" value="<?= $info['price'] ?>"
                                   data-options="required:true">
                        </td>
                    </tr>
                    <tr>
                        <td>图片:</td>
                        <td><input class="easyui-textbox" type="text" name="img" value="<?= $info['img'] ?>"
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
                        <td>描述:</td>
                        <td><input class="easyui-textbox" name="desc" value="<?= $info['desc'] ?>"
                                   data-options="multiline:true,required:true"
                                   style="width: 320px;height:120px"></td>
                    </tr>

                </table>
            </form>
            <div style="padding:5px 0;">
                <a id="btnSave" href="<?= SITE_URL ?>/admin/goodsman/edit" class="easyui-linkbutton">保存</a>
                <a id="btnCancel" href="<?= SITE_URL ?>/admin/goodsman" class="easyui-linkbutton">取消</a>
            </div>
        </div>

    <?php } else { ?>
        <div>商品不存在</div>
    <?php } ?>
</div>
</body>
<script>
    $('[name=source]').val(<?=$info['source']?>);
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