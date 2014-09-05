<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<?php
//date_default_timezone_set('Asia/Shanghai');
//echo time();
echo date('Y-m-d H:i', time());
?>
<script>

    function timestamp_to_format(timestamp) {
        timestamp = timestamp + '000';
        timestamp = parseInt(timestamp);

        var newTime = new Date(timestamp);
        var new_y = newTime.getFullYear();
        var new_m = newTime.getMonth() + 1;
        var new_d = newTime.getDate();
        var new_h = newTime.getHours();
        var new_min = newTime.getMinutes();

        var new_time_format = new_y + '-' + new_m + '-' + new_d + ' ' + new_h + ':' + new_min;
        return new_time_format;

    }

    var time = timestamp_to_format('1469735348');
    alert(time);

    function getLocalTime(nS) {
        return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/, ' ');
    }
    //alert(Date(1469735348).toLocaleString());
    //  alert(formatDate(d));

    function getLocalTime(nS) {
        // return new Date(parseInt(nS) * 1000).toLocaleTimeString().replace(/年|月/g, "-").replace(/日/g, " ");
        return new Date(parseInt(nS) * 1000).toLocaleString();
    }

    // alert(getLocalTime(1409726271));

    Date.prototype.format = function (format) {
        /*
         * eg:format="YYYY-MM-dd hh:mm:ss";
         */
        var o = {
            "M+": this.getMonth() + 1, // month
            "d+": this.getDate(), // day
            "h+": this.getHours(), // hour
            "m+": this.getMinutes(), // minute
            "s+": this.getSeconds(), // second
            "q+": Math.floor((this.getMonth() + 3) / 3), // quarter
            "S": this.getMilliseconds()
            // millisecond
        }
        if (/(y+)/.test(format)) {
            format = format.replace(RegExp.$1, (this.getFullYear() + "")
                .substr(4 - RegExp.$1.length));
        }

        for (var k in o) {
            if (new RegExp("(" + k + ")").test(format)) {
                format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k]
                    : ("00" + o[k]).substr(("" + o[k]).length));
            }
        }
        return format;
    }
    // alert(new Date(1409726271).format('yyyy-MM-dd hh:mm'));
    //alert(new Date(1409726271).toLocaleTimeString().format('yyyy-MM-dd hh:mm'));
    //alert(time);
</script>
</body>
</html>