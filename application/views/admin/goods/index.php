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
    <script type="text/javascript" src="/public/js/ui/common.js"></script>
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
            <th data-options="field:'source',width:100,align:'center'" formatter="formatOrigin">原产地</th>
            <th data-options="field:'price',width:100,align:'center'">商品价格</th>
            <th data-options="field:'status',width:60,align:'center'" formatter="formatStatus">状态</th>
            <th data-options="field:'add_time',width:150,align:'left'" formatter="formatTime">创建时间</th>
        </tr>
        </thead>
    </table>
</div>


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
            text: '编辑商品',
            iconCls: 'icon-edit',
            handler: function () {
                editGoods();
            }
        },
        {
            text: '删除商品',
            iconCls: 'icon-cut',
            handler: function () {
                deleteGoods()
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
            'url': '<?=SITE_URL?>/admin/goodsman/goods',
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
    function editGoods() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            location.href = "<?=SITE_URL?>/admin/goodsman/edit?id=" + parseInt(row.id);
        } else {
            alert('请选择商品');
        }
    }

    // 删除商品
    function deleteGoods() {
        var rows = $('#dg').datagrid('getSelections');
        //console.log(rows);
        var ids = [];
        if (rows.length > 0) {
            $.messager.confirm('删除商品', '确定删除选择的商品?', function (r) {
                if (r) {
                    for (var i = 0 in rows) {
                        ids[i] = rows[i]['id'];
                    }
                    $.post('<?=SITE_URL?>/admin/goodsman/drop', {'id': ids}, function (rep) {
                        if (rep.code == 0) {
                            // _micro_pager.pagination('refresh');
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