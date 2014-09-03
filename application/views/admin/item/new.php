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
<div class="easyui-panel" title="新增项目" style="width:100%; height: 100%">
    <div style="padding:10px 60px 20px 60px">
        <form id="ff" method="post">
            <table cellpadding="5">
                <tr>
                    <td>标题:</td>
                    <td><input class="easyui-textbox" type="text" size="100" name="title" data-options="required:true">
                    </td>
                </tr>
                <tr>
                    <td>人品等级:</td>
                    <td>
                        <select class="easyui-combobox" name="grade">
                            <option value="1">人品稀薄</option>
                            <option value="2">马马虎虎</option>
                            <option value="3">人气爆棚</option>
                            <option value="4">神仙降临</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>总价:</td>
                    <td><input class="easyui-textbox" type="text" name="gross" data-options="required:true">
                    </td>
                </tr>
                <tr>
                    <td>配额:</td>
                    <td><input class="easyui-textbox" type="text" name="quota" data-options="required:true">
                    </td>
                </tr>
                <tr>
                    <td>期限(小时):</td>
                    <td><input class="easyui-textbox" type="text" name="period" data-options="required:true">
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
                    <td><input class="easyui-textbox" name="desc" data-options="multiline:true,required:true"
                               style="width: 320px;height:120px"></td>
                </tr>
            </table>

            <div style="padding-top: 36px;padding-bottom: 12px;">项目商品 <input type="button" id="btnSelGoods"
                                                                             value="从商品库中选择"/></div>
            <table cellpadding="5">
                <input type="hidden" name="goods_id" value="0"/>
                <tr>
                    <td>标题:</td>
                    <td><input class="easyui-textbox" type="text" size="100" name="goods_title"
                               data-options="required:true">
                    </td>
                </tr>
                <tr>
                    <td>产地:</td>
                    <td>
                        <select class="easyui-combobox" name="goods_source">
                            <option value="0">自产</option>
                            <option value="1">进口</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>价格:</td>
                    <td><input class="easyui-textbox" type="text" name="goods_price" data-options="required:true">
                    </td>
                </tr>
                <tr>
                    <td>图片:</td>
                    <td><input class="easyui-textbox" type="text" name="goods_img" data-options="required:true">
                    </td>
                </tr>

                <tr>
                    <td>描述:</td>
                    <td><input class="easyui-textbox" name="goods_desc" data-options="multiline:true,required:true"
                               style="width: 320px;height:120px"></td>
                </tr>

            </table>
        </form>
        <div style="padding:5px 0;">
            <a id="btnSave" href="<?= SITE_URL ?>/admin/itemman/append" class="easyui-linkbutton">保存</a>
            <a id="btnCancel" href="<?= SITE_URL ?>/admin/itemman" class="easyui-linkbutton">取消</a>
        </div>
    </div>

</div>

<div id="dlg" class="easyui-dialog" title="选择商品"
     style="width:808px;height:430px; overflow:hidden "
     data-options="buttons:'#dlg-buttons',modal:true,closed:true">
    <iframe id="frmGoods" name="frmGoods" src="" frameborder="0" width="100%" height="100%" scrolling="no"></iframe>
</div>

<div id="dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:showVar()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#dlg').dialog('close')">Close</a>
</div>

</body>
<script>
    var _validate = false;
    $('#btnSave').click(function () {
        submitForm();
        return false;
    })

    $('#btnSelGoods').click(function () {
        $('#dlg').dialog('open')
        $('#frmGoods').attr('src', '<?=SITE_URL?>/admin/itemman/select?page=1')
        return false;
    })

    function showVar() {
        var _micro_dataGrid = window.frames['frmGoods']._micro_dataGrid;
        var row = _micro_dataGrid.datagrid('getSelected');
        if (row) {
            $('#ff').form('disableValidation').form('load', {
                goods_id: row.id,
                goods_title: row.title,
                goods_source: row.source,
                goods_price: row.price,
                goods_img: row.img,
                goods_desc: row.desc
            });
            $('#dlg').dialog('close');
        }
    }

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