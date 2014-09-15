<?php
date_default_timezone_set('PRC'); //设置中国时区

echo date('Y-m-d H:i:s', '1410398972');
?>

<script>
    function getLocalTime(nS) {
        //return new Date(parseInt(nS) * 1000).toLocaleString().replace(/年|月/g, "/").replace(/日/g, " ");
        //  return new Date(parseInt(nS) * 1000).toLocaleString();
        return new Date(parseInt(nS) * 1000).toLocaleString();
    }
    // alert(getLocalTime(1410252156));

    function formatDate(now) {
        var year = now.getYear();
        var month = now.getMonth() + 1;
        var date = now.getDate();
        var hour = now.getHours();
        var minute = now.getMinutes();
        var second = now.getSeconds();
        return year + "-" + month + "-" + date + " " + hour + ":" + minute + ":" + second;
    }
    var d = new Date(1410252156);
    // alert(formatDate(d));

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
        return y + '-' + patchZero(m) + '-' + patchZero(d) + ' ' + patchZero(h) + ':' + patchZero(mm)  + s;
    }
    alert(timeFormat(1410398972, true))

</script>