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
<div class="easyui-panel" title="编辑订单" style="width:100%; height: 100%">
    <?php
    if ($order) {

        ?>
        <div style="padding:10px 60px 20px 60px">
            <form id="ff" method="post">
                <input type="hidden" name="id" value="<?= $order['id'] ?>"/>
                <table cellpadding="5">
                    <tr>
                        <td>订单总价:</td>
                        <td><input class="easyui-numberbox" type="text" size="100" name="gross"
                                   data-options="required:true" value="<?= $order['gross'] ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>配额人数:</td>
                        <td><input class="easyui-numberbox" type="text" size="100" name="quota"
                                   data-options="required:true" value="<?= $order['quota'] ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>截止日期:</td>
                        <td>
                            <input class="easyui-datetimebox" type="text" name="expire"
                                   value="<?=date('m/d/Y H:i', $order['expire']) ?>"
                                   data-options="required:true" style="width:180px">
                        </td>
                    </tr>
                    <tr>
                        <td>状态:</td>
                        <td>
                            <select class="easyui-combobox" name="status" panelHeight="auto">
                                <option value="1" selected>发布</option>
                                <option value="0">关闭</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>备注:</td>
                        <td><input class="easyui-textbox" name="desc" value="<?= $order['desc'] ?>"
                                   data-options="multiline:true,required:false"
                                   style="width: 320px;height:120px"></td>
                    </tr>

                </table>
            </form>


            <div style="padding:5px 0;">
                <a id="btnSave" href="<?= SITE_URL ?>/admin/orderman/edit" class="easyui-linkbutton">保存</a>
                <a id="btnCancel" href="<?= SITE_URL ?>/admin/orderman" class="easyui-linkbutton">取消</a>
            </div>
        </div>
    <?php } else { ?>
        <div>用户不存在</div>
    <?php } ?>
</div>
</body>
<script>
    $('[name=expire]').datetimebox({showSeconds:false})
    $('[name=status]').val(<?=$order['status']?>);
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