function patchZero(m) {
    return m < 10 ? '0' + m : m
}

function timeFormat(timestamp, isSec) {
    var time = new Date(parseInt(timestamp) * 1000);
    var y = time.getFullYear();
    var m = time.getMonth() + 1;
    var d = time.getDate();
    var h = time.getHours();
    var mm = time.getMinutes();
    var s = isSec == false ? '' : ':' + patchZero(time.getSeconds());
    return y + '-' + patchZero(m) + '-' + patchZero(d) + ' ' + patchZero(h) + ':' + patchZero(mm) + s;
}


function formatAmount(val, row) {

    var type = typeof val;
    if (type == 'object') {
        return '0.00'
    } else {
        return parseFloat(val).toFixed(2);
    }
}

function formatTime(val, row) {
    var type = typeof val;
    if (type == 'object') {
        return '--'
    } else {
        return  timeFormat(val, true);
    }
}


function formatStatus(val, row) {
    var txt = 'on';
    if (val <= 0) {
        txt = '<span style="color:red;">off</span>';
    } else {
        txt = '<span style="color:green;">on</span>';
    }
    return txt;
}


function formatOrigin(val, row) {
    var val = parseInt(val);
    var txt = "--";
    if (val == 0) {
        txt = '自产';
    }

    if (val == 1) {
        txt = '进口';
    }
    return txt;
}

function formatPayType(val, row) {
    var val = parseInt(val);
    var txt = "--";
    if (val == 2) {
        txt = '支付宝';
    }
    return txt;
}

function formatPayState(val, row) {
    var val = parseInt(val);
    var txt = "--";
    if (val == -1) {
        txt = '支付失败';
    }

    if (val == 0) {
        txt = '未支付';
    }

    if (val == 1) {
        txt = '支付成功';
    }
    return txt;
}

function formatRefundState(val, row) {
    var val = parseInt(val);
    var txt = "--";
    if (val == -1) {
        txt = '退款失败';
    }

    if (val == 0) {
        txt = '未退款';
    }

    if (val == 1) {
        txt = '退款成功';
    }
    return txt;
}


function formatUserType(val, row) {
    var val = parseInt(val);
    var txt = "--";
    if (val <= 0) {
        txt = '<span style="color:red;">管理员</span>';
    } else {
        txt = '<span style="color:green;">会员</span>';
    }
    return txt;
}

