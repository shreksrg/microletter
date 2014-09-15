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
    <table id="dg" title="用户管理" style="width:100%; height:auto"
           data-options="rownumbers:true,singleSelect:false,method:'get',toolbar:toolbar,pageSize:20">
        <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true"></th>
            <th data-options="field:'id',width:60">ID</th>
            <th data-options="field:'mobile',width:150,align:'center'">手机号</th>
            <th data-options="field:'type',width:80,align:'center'" formatter="formatUserType">用户类型</th>
            <th data-options="field:'fullname',width:150,align:'center'">用户姓名</th>
            <th data-options="field:'status',width:60,align:'center'" formatter="formatStatus">状态</th>
            <th data-options="field:'add_time',width:180,align:'left'" formatter="formatTime">创建时间</th>
        </tr>
        </thead>
    </table>
</div>

<div id="dlg" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons" modal="true">

    <form id="fm" method="post" novalidate>
        <div class="fitem">
            <label>新密码</label>
            <input name="password" class="easyui-textbox" required="true" type="password">
        </div>
        <div class="fitem">
            <label>确认密码</label>
            <input name="confirm" class="easyui-textbox" required="true" type="password">
        </div>
    </form>
</div>

<div id="dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePassword()"
       style="width:90px">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel"
       onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>

<script>




</script>

<script type="text/javascript">

    var toolbar = [
        {
            text: '新增会员',
            iconCls: 'icon-add',
            handler: function () {
                // $('#mainArea').html('增加商品')
                location.href = '<?=SITE_URL?>/admin/itemman/append';
            }
        },
        {
            text: '编辑会员',
            iconCls: 'icon-edit',
            handler: function () {
                editUser();
            }
        },
        {
            text: '设置密码',
            iconCls: 'icon-edit',
            handler: function () {
                var row = $('#dg').datagrid('getSelected');
                if (row) {
                    $('#dlg').dialog('open').dialog('setTitle', '设置密码');
                    $('#fm').form('clear');
                } else {
                    alert('请选择用户');
                }

            }
        },
        {
            text: '删除用户',
            iconCls: 'icon-cut',
            handler: function () {
                deleteUser()
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
            'url': '<?=SITE_URL?>/admin/userman/users',
            'pagination': true
        })

        _micro_pager = _micro_dataGrid.datagrid('getPager');

        _micro_pager.pagination({
            showPageList: true,
            layout: ['list', 'sep', 'first', 'prev', 'links', 'next', 'last', 'sep', 'refresh']
        })
        //  var pager = _micro_dataGrid.datagrid('getPager');    // get the pager of datagrid
    })

    //编辑会员
    function editUser() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            location.href = "<?=SITE_URL?>/admin/userman/edit?id=" + row.id;
        } else {
            alert('请选择用户');
        }
    }

    //保存编辑密码
    function savePassword() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            var url = "<?=SITE_URL?>/admin/userman/password?id=" + row.id;
            var pwd = $('[name=password]').val(), cpwd = $('[name=confirm]').val();

            $('#fm').form('submit', {
                onSubmit: function () {
                    var re = $(this).form('validate');
                    if (re != true) return false;
                    if (cpwd != pwd) {
                        alert('密码不一致');
                        return false;
                    }

                    $.post(url, {'password': pwd}, function (rep) {
                        if (rep.code == 0) {
                            alert('设置成功');
                            $('#dlg').dialog('close');
                        } else {
                            alert('设置失败')
                        }
                    }, 'json');
                    return false;
                }
            })

            /*  $('#fm').form('submit', {
             onSubmit: function () {
             var re = $(this).form('validate');
             if (re != true) return false;
             if (cpwd != pwd) return false;
             $.post(url, {'password': pwd}, function (rep) {
             if (rep.code == 0) {
             alert('设置成功');
             $('#dlg').dialog('close');
             } else {
             alert('设置失败')
             }
             }, 'json')
             return false;
             }
             )*/

        } else {
            alert('请选择用户');
        }
    }

    // 删除用户
    function deleteUser() {
        var rows = $('#dg').datagrid('getSelections');
        //console.log(rows);
        var ids = [];
        if (rows.length > 0) {
            $.messager.confirm('删除会员', '确定删除选择的会员?', function (r) {
                if (r) {
                    for (var i = 0 in rows) {
                        ids[i] = rows[i]['id'];
                    }
                    $.post('<?=SITE_URL?>/admin/userman/drop', {'id': ids}, function (rep) {
                        if (rep.code == 0) {
                            $('#dg').datagrid('reload')
                        } else {
                            alert('删除失败')
                        }
                    }, 'json')

                }
            });
        } else {
            alert('请选择用户');
        }
    }
</script>
</body>
</html>