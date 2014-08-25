<?php

class Utils
{
    public static function getDiffTime($startTime, $endTime)
    {
        $time = array();
        $diffSec = $endTime - $startTime;
        $diffDays = ($diffSec / 3600 / 24);
        $time['leftDays'] = intval($diffDays); //剩余天数
        $diffHours = ($diffDays - $time['leftDays']) * 24;
        $time['leftHours'] = intval($diffHours); //剩余小时数
        $diffMinutes = ($diffHours - $time['leftHours']) * 60;
        $time['leftMinutes'] = intval($diffMinutes); //剩余分钟
        $time['leftSeconds'] = ceil(($diffMinutes - $time['leftMinutes']) * 60); //剩余秒数

        return $time;

    }
}