<table id="dg" title="Custom DataGrid Pager" style="width:100%;height:100%"
       data-options="rownumbers:true,singleSelect:false,method:'get',toolbar:toolbar">
    <thead>
    <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th data-options="field:'id',width:80">Item ID</th>
        <th data-options="field:'name',width:100">Product</th>
        <th data-options="field:'title',width:80,align:'right'">List Price</th>
        <th data-options="field:'price',width:80,align:'right'">Unit Cost</th>
        <th data-options="field:'status',width:240">Attribute</th>
        <th data-options="field:'status',width:60,align:'center'">Status</th>
    </tr>
    </thead>
</table>

<script type="text/javascript">

    var toolbar = [
        {
            text: 'Add',
            iconCls: 'icon-add',
            handler: function () {
                $('#mainArea').html('增加商品')
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
            pageSize: 20,
            layout: ['list', 'sep', 'first', 'prev', 'links', 'next', 'last', 'sep', 'refresh']
        })

        //  var pager = _micro_dataGrid.datagrid('getPager');    // get the pager of datagrid

    })
</script>