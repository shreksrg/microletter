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
    <script type="text/javascript" src="/public/js/ui/common.js?v=<?=time()?>"></script>

</head>

<body>

<div>
    <table id="dg" style="width:100%; height:auto"
           data-options="rownumbers:false,singleSelect:true,method:'get',pageSize:10">
        <thead>
        <tr>
            <th data-options="field:'id',width:60">ID</th>
            <th data-options="field:'pay_sn',width:180,align:'left'">支付编码</th>
            <th data-options="field:'out_sn',width:180,align:'left'">外部交易号</th>
            <th data-options="field:'amount',width:100,align:'center'" formatter="formatAmount">支付金额</th>
            <th data-options="field:'type',width:80,align:'center'" formatter="formatPayType">支付方式</th>
            <th data-options="field:'status',width:80,align:'center'" formatter="formatPayState">支付状态</th>
            <th data-options="field:'add_time',width:160,align:'left'" formatter="formatTime">创建日期</th>
            <th data-options="field:'pay_time',width:160,align:'left'" formatter="formatTime">支付日期</th>
        </tr>
        </thead>
    </table>
</div>




<script>


</script>


<script type="text/javascript">
    var _micro_dataGrid, _micro_pager;
    var myname = 'shrek';
    $(function () {
        //动态加载数据
        // $('#dg').datagrid()

        _micro_dataGrid = $('#dg').datagrid({
            'url': '<?=SITE_URL?>/admin/payman/pays?orderId=<?=$orderId?>',
            'pagination': true
        })

        _micro_pager = _micro_dataGrid.datagrid('getPager');

        _micro_pager.pagination({
            showPageList: true,
            layout: ['list', 'sep', 'first', 'prev', 'links', 'next', 'last', 'sep', 'refresh']
        })

        //  var pager = _micro_dataGrid.datagrid('getPager');    // get the pager of datagrid

    })



    // 删除支付项
    function deletePay() {
        var rows = $('#dg').datagrid('getSelections');
        //console.log(rows);
        var ids = [];
        if (rows.length > 0) {
            $.messager.confirm('删除商品', '确定删除选择的商品?', function (r) {
                if (r) {
                    for (var i = 0 in rows) {
                        ids[i] = rows[i]['id'];
                    }
                    $.post('<?=SITE_URL?>/admin/payman/drop', {'id': ids}, function (rep) {
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