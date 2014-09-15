<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="/public/js/ui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="/public/js/ui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="/public/js/ui/themes/common.css">
    <script type="text/javascript" src="/public/js/ui/jquery.min.js"></script>
    <script type="text/javascript" src="/public/js/ui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/public/js/ui/common.js?v=<?= time() ?>"></script>
</head>

<body>

<div>
    <table id="dg" title="项目管理" style="width:100%; height:auto"
           data-options="rownumbers:true,singleSelect:false,method:'get',toolbar:toolbar,pageSize:20">
        <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true"></th>
            <th data-options="field:'id',width:80">ID</th>
            <th data-options="field:'title',width:480,align:'left'">项目标题</th>
            <th data-options="field:'grade_name',width:120,align:'left'">人品等级</th>
            <th data-options="field:'gross',width:100,align:'center'" formatter="formatAmount">项目价格</th>
            <th data-options="field:'period',width:100,align:'center'">期限(小时)</th>
            <th data-options="field:'quota',width:80,align:'center'">配额(人)</th>
            <th data-options="field:'status',width:60,align:'center'" formatter="formatStatus">状态</th>
            <th data-options="field:'add_time',width:150,align:'left'" formatter="formatTime">创建时间</th>
        </tr>
        </thead>
    </table>
</div>
<script>


</script>

<script type="text/javascript">

    var toolbar = [
        {
            text: '新增项目',
            iconCls: 'icon-add',
            handler: function () {
                // $('#mainArea').html('增加商品')
                location.href = '<?=SITE_URL?>/admin/itemman/append';
            }
        },
        {
            text: '编辑项目',
            iconCls: 'icon-edit',
            handler: function () {
                editItem();
            }
        },
        {
            text: '删除项目',
            iconCls: 'icon-cut',
            handler: function () {
                deleteItem()
            }
        }

    ];
</script>
<script type="text/javascript">
    var _micro_dataGrid, _micro_pager;
    $(function () {
        //动态加载数据
        // $('#dg').datagrid()

        _micro_dataGrid = $('#dg').datagrid({
            'url': '<?=SITE_URL?>/admin/itemman/items',
            'pagination': true
        })

        _micro_pager = _micro_dataGrid.datagrid('getPager');

        _micro_pager.pagination({
            showPageList: true,
            layout: ['list', 'sep', 'first', 'prev', 'links', 'next', 'last', 'sep', 'refresh']
        })

        //  var pager = _micro_dataGrid.datagrid('getPager');    // get the pager of datagrid

    })

    //编辑商品
    function editItem() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            location.href = "<?=SITE_URL?>/admin/itemman/edit?id=" + parseInt(row.id);
        } else {
            alert('请选择项目');
        }
    }

    // 删除商品
    function deleteItem() {
        var rows = $('#dg').datagrid('getSelections');
        //console.log(rows);
        var ids = [];
        if (rows.length > 0) {
            $.messager.confirm('删除项目', '确定删除选择的项目?', function (r) {
                if (r) {
                    for (var i = 0 in rows) {
                        ids[i] = rows[i]['id'];
                    }
                    $.post('<?=SITE_URL?>/admin/itemman/drop', {'id': ids}, function (rep) {
                        if (rep.code == 0) {
                            $('#dg').datagrid('reload')
                        } else {
                            alert('删除失败')
                        }
                    }, 'json')

                }
            });
        } else {
            alert('请选择商品');
        }
    }
</script>
</body>
</html>