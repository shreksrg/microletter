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

<div>
    <table id="dg" title="商品管理" style="width:100%; height:auto"
           data-options="rownumbers:true,singleSelect:false,method:'get',toolbar:toolbar,pageSize:20">
        <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true"></th>
            <th data-options="field:'id',width:80">ID</th>
            <th data-options="field:'title',width:480,align:'left'">商品标题</th>
            <th data-options="field:'source',width:120,align:'left'">原产地</th>
            <th data-options="field:'price',width:80,align:'left'">商品价格</th>
            <th data-options="field:'status',width:60,align:'center'" formatter="formatStatus">状态</th>
        </tr>
        </thead>
    </table>
</div>
<script>
    function formatStatus(val, row) {
        var txt = 'on';
        if (val <= 0) {
            txt = '<span style="color:red;">off</span>';
        } else {
            txt = '<span style="color:green;">on</span>';
        }
        return txt;
    }

</script>

<script type="text/javascript">

    var toolbar = [
        {
            text: '新增商品',
            iconCls: 'icon-add',
            handler: function () {
                // $('#mainArea').html('增加商品')
                location.href = '<?=SITE_URL?>/admin/goodsman/append';
            }
        },
        {
            text: 'Edit',
            iconCls: 'icon-edit',
            handler: function () {
                alert('edit')
            }
        },
        {
            text: 'Cut',
            iconCls: 'icon-cut',
            handler: function () {
                alert('cut')
            }
        },
        '-',
        {
            text: 'Save',
            iconCls: 'icon-save',
            handler: function () {
                alert('save')
            }
        }
    ];
</script>
<script type="text/javascript">
    $(function () {
        //动态加载数据
        // $('#dg').datagrid()

        var _micro_dataGrid = $('#dg').datagrid({
            'url': '<?=SITE_URL?>/admin/goodsman/goods',
            'pagination': true
        })

        var _micro_pager = _micro_dataGrid.datagrid('getPager');

        _micro_pager.pagination({
            showPageList: true,
            layout: ['list', 'sep', 'first', 'prev', 'links', 'next', 'last', 'sep', 'refresh']
        })

        //  var pager = _micro_dataGrid.datagrid('getPager');    // get the pager of datagrid

    })
</script>
</body>
</html>