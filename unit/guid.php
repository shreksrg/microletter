<?php
function guid(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}

function uuid($prefix = '')
{
    $chars = md5(uniqid(mt_rand(), true));
    $uuid  = substr($chars,0,8) . '-';
    $uuid .= substr($chars,8,4) . '-';
    $uuid .= substr($chars,12,4) . '-';
    $uuid .= substr($chars,16,4) . '-';
    $uuid .= substr($chars,20,12);
    return $prefix . $uuid;
}

function fast_uuid($suffix_len = 3)
{
    //! 计算种子数的开始时间
    static $being_timestamp = 1392357936; // 2014-2-14

    $time = explode(' ', microtime());
    $id = ($time[1] - $being_timestamp) . sprintf('%06u', substr($time[0], 2, 6));
    if ($suffix_len > 0) {
        $id .= substr(sprintf('%010u', mt_rand()), 0, $suffix_len);
    }
    return $id;
}
echo fast_uuid();