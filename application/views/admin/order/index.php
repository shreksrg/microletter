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
    <table id="dg" title="订单管理" style="width:100%; height:auto"
           data-options="rownumbers:true,singleSelect:true,method:'get',toolbar:'#toolbar',pageSize:20">
        <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true"></th>
            <th data-options="field:'id',width:80">ID</th>
            <th data-options="field:'sn',width:240,align:'left'">订单编码</th>
            <th data-options="field:'gross',width:120,align:'center'" formatter="formatAmount">订单金额</th>
            <th data-options="field:'quota',width:100,align:'center'">配额人数</th>
            <th data-options="field:'paids',width:100,align:'center'">支付人数</th>
            <th data-options="field:'status',width:100,align:'center'" formatter="formatStatus">状态</th>
            <th data-options="field:'expire',width:150,align:'left'" formatter="formatTime">截止日期</th>
            <th data-options="field:'add_time',width:150,align:'left'" formatter="formatTime">下单日期</th>
            <th data-options="field:'achieve_time',width:150,align:'left'" formatter="formatTime">成交日期</th>
            <!-- <th data-options="field:'type',width:60,align:'left'" formatter="formatType">用户类型</th>-->
        </tr>
        </thead>
    </table>

    <div id="toolbar" style="padding:5px;height:auto">
        <div style="margin-bottom:5px">
            <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editOrder();">编辑订单</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-cut" plain="true" onclick="editOrder();">编辑订单</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="showPayment();">查看支付</a>
        </div>
        <div>
            <div style="padding-bottom: 8px">
                订单编码: <input class="easyui-numberbox" name="order_sn" size="48" value="">
                订单价格: <input class="easyui-numberbox" name="min_gross"> ~ <input class="easyui-numberbox"
                                                                                 name="max_gross">
                订单状态: <select class="easyui-combobox" name="status" panelHeight="auto" style="width:100px">
                    <option value="">全部</option>
                    <option value="1">进行中</option>
                    <option value="2">交易成功</option>
                    <option value="3">交易结束</option>
                    <option value="0">交易关闭</option>
                </select>
            </div>
            下单日期: <input class="easyui-datebox" name="ab_time" style="width:80px">
            To: <input class="easyui-datebox" name="ae_time" style="width:80px">

            截止日期: <input class="easyui-datebox" name="eb_time" style="width:80px">
            To: <input class="easyui-datebox" name="ee_time" style="width:80px">

            <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch();return false;">Search</a>
        </div>
    </div>
</div>

<div id="dlg" class="easyui-dialog" title="支付记录"
     style="width:1002px;height:430px; overflow:hidden "
     data-options="buttons:'#dlg-buttons',modal:true,closed:true">
    <iframe id="frmPayment" name="frmPayment" src="" frameborder="0" width="100%" height="100%" scrolling="no"></iframe>
</div>

<div id="dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#dlg').dialog('close')">Close</a>
</div>


<script>


</script>

<script type="text/javascript">
    var _micro_dataGrid, _micro_pager;

    //订单查询
    function doSearch() {
        _micro_dataGrid.datagrid('load', {
            order_sn: $('[name=order_sn]').val(),
            min_gross: $('[name=min_gross]').val(),
            max_gross: $('[name=max_gross]').val(),
            status: $('[name=status]').val(),
            ab_time: $('[name=ab_time]').val(),
            ae_time: $('[name=ae_time]').val(),
            eb_time: $('[name=eb_time]').val(),
            ee_time: $('[name=ee_time]').val()
        });
    }

    //显示支付订单记录
    function showPayment() {
        var row = _micro_dataGrid.datagrid('getSelected');
        if (row) {
            $('#dlg').dialog('open')
            $('#frmPayment').attr('src', '<?=SITE_URL?>/admin/payman?orderId='+row.id)
            return false;
        }else{
            alert('请选择订单')
        }
    }


</script>
<script type="text/javascript">

    $(function () {

        _micro_dataGrid = $('#dg').datagrid({
            'url': '<?=SITE_URL?>/admin/orderman/orders',
            'pagination': true
        })

        _micro_pager = _micro_dataGrid.datagrid('getPager');

        _micro_pager.pagination({
            showPageList: true,
            layout: ['list', 'sep', 'first', 'prev', 'links', 'next', 'last', 'sep', 'refresh']
        })
        //  var pager = _micro_dataGrid.datagrid('getPager');    // get the pager of datagrid
    })

    //编辑订单
    function editOrder() {
        var row = _micro_dataGrid.datagrid('getSelected');
        if (row) {
            location.href = "<?=SITE_URL?>/admin/orderman/edit?id=" + row.id;
        } else {
            alert('请选择订单');
        }
    }


    // 删除订单
    function deleteOrder() {
        var rows = $('#dg').datagrid('getSelections');
        //console.log(rows);
        var ids = [];
        if (rows.length > 0) {
            $.messager.confirm('删除订单', '确定删除选择的订单?', function (r) {
                if (r) {
                    for (var i = 0 in rows) {
                        ids[i] = rows[i]['id'];
                    }
                    $.post('<?=SITE_URL?>/admin/orderman/drop', {'id': ids}, function (rep) {
                        if (rep.code == 0) {
                            $('#dg').datagrid('reload')
                        } else {
                            alert('删除失败')
                        }
                    }, 'json')
                }
            });
        } else {
            alert('请选择订单');
        }
    }
</script>
</body>
</html>