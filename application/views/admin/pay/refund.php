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
    <table id="dg" style="width:100%; height:auto" title="退款管理"
           data-options="rownumbers:false,singleSelect:false,method:'get',pageSize:20" toolbar="#toolbar">
        <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true"></th>
            <th data-options="field:'id',width:60">ID</th>
            <th data-options="field:'refund_sn',width:180,align:'left'">退款单号</th>
            <th data-options="field:'pay_sn',width:180,align:'left'">支付单号</th>
            <th data-options="field:'out_sn',width:180,align:'left'">交易单号</th>
            <th data-options="field:'amount',width:60,align:'center'" formatter="formatAmount">退款金额</th>
            <th data-options="field:'type',width:80,align:'center'" formatter="formatPayType">退款方式</th>
            <th data-options="field:'status',width:80,align:'center'" formatter="formatRefundState">退款状态</th>
            <th data-options="field:'pay_time',width:160,align:'left'" formatter="formatTime">支付日期</th>
            <th data-options="field:'refund_time',width:160,align:'left'" formatter="formatTime">退款日期</th>
        </tr>
        </thead>
    </table>
    <div id="toolbar" style="padding:5px;height:auto">
        <div style="margin-bottom:5px">
            <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="refreshRefund();">刷新退款记录</a>
            <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="doRefund();">退款</a>
        </div>
        <div>
            <div style="padding-bottom: 8px">
                退款单号: <input class="easyui-numberbox" name="refund_sn" size="48" value="">
                支付单号: <input class="easyui-numberbox" name="pay_sn" size="48" value="">
                退款金额: <input class="easyui-numberbox" name="min_amount"> ~ <input class="easyui-numberbox"
                                                                                  name="max_amount">
                退款状态: <select class="easyui-combobox" name="status" panelHeight="auto" style="width:100px">
                    <option value="">全部</option>
                    <option value="0">未退款</option>
                    <option value="1">已退款</option>
                </select>
            </div>
            支付日期: <input class="easyui-datebox" name="pb_time" style="width:80px">
            To: <input class="easyui-datebox" name="pe_time" style="width:80px">

            退款日期: <input class="easyui-datebox" name="rb_time" style="width:80px">
            To: <input class="easyui-datebox" name="re_time" style="width:80px">

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
    var _micro_dataGrid, _micro_pager;

    //订单查询
    function doSearch() {
        _micro_dataGrid.datagrid('load', {
            order_sn: $('[name=order_sn]').val(),
            min_amount: $('[name=min_amount]').val(),
            max_amount: $('[name=max_amount]').val(),
            status: $('[name=status]').val(),
            rb_time: $('[name=rb_time]').val(),
            re_time: $('[name=re_time]').val(),
            pb_time: $('[name=pb_time]').val(),
            pe_time: $('[name=pe_time]').val()
        });
    }

    //显示支付订单记录
    function showPayment() {
        var row = _micro_dataGrid.datagrid('getSelected');
        if (row) {
            $('#dlg').dialog('open')
            $('#frmPayment').attr('src', '<?=SITE_URL?>/admin/payman?orderId=' + row.id)
            return false;
        } else {
            alert('请选择订单')
        }
    }

    //执行退款
    function doRefund() {
        var rows = _micro_dataGrid.datagrid('getSelections');
        if (rows.length > 0) {
            var ids = [];
            for (var i = 0 in rows) {
                ids[i] = rows[i]['id'];
            }
            $.post('<?=SITE_URL?>/admin/payman/refund?r=do', {'id': ids}, function (rep) {
                if (rep.code == 0) {
                    _micro_dataGrid.datagrid('reload')
                } else {
                    alert('退款操作失败');
                }
            }, 'json');
            $('#frmPayment').attr('src', '<?=SITE_URL?>/admin/payman?orderId=' + row.id)
            return false;
        } else {
            alert('请选择退款单')
        }
    }


</script>
<script type="text/javascript">

    $(function () {

        _micro_dataGrid = $('#dg').datagrid({
            'url': '<?=SITE_URL?>/admin/payman/refund?r=list',
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

    function refreshRefund() {
        $.get("<?=SITE_URL?>/admin/payman/refund?r=gen", null, function (rep) {
            if (rep.code == 0) {
                _micro_dataGrid.datagrid('reload');
                alert('刷新成功')
            } else {
                alert('刷新完成')
            }
        }, 'json')
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