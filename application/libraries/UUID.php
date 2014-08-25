<?php

class UUID
{
    static public function guid()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000); //optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"
            $uuid = substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12);
            return $uuid;
        }
    }

    static function fast_uuid($suffix_len = 3)
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

    static public function randString($len = 6)
    {
        $chars = 'ABDEFGHJKLMNPQRSTVWXYabdefghijkmnpqrstvwxy23456789'; // characters to build the password from
        mt_srand((double)microtime() * 1000000 * getmypid()); // http://yige.org/ seed the random number generater (must be done)
        $password = '';
        while (strlen($password) < $len) $password .= substr($chars, (mt_rand() % strlen($chars)), 1);
        return $password;
    }
}