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

    public static function dateDiff($part, $begin, $end)
    {
        $diff = $end - $begin;

        switch ($part) {
            case "y":
                $retval = bcdiv($diff, (60 * 60 * 24 * 365));
                break;
            case "m":
                $retval = bcdiv($diff, (60 * 60 * 24 * 30));
                break;
            case "w":
                $retval = bcdiv($diff, (60 * 60 * 24 * 7));
                break;
            case "d":
                $retval = bcdiv($diff, (60 * 60 * 24));
                break;
            case "h":
                $retval = bcdiv($diff, (60 * 60));
                break;
            case "n":
                $retval = bcdiv($diff, 60);
                break;
            case "s":
                $retval = $diff;
                break;
        }
        return $retval;
    }

    public static function diffDateLabel($start, $expire)
    {
        $log = array();
        $unit = array('y', 'm', 'w', 'd', 'h', 'n', 's');
        $lang = array('y' => '年', 'm' => '月', 'w' => '周', 'd' => '天', 'h' => '小时', 'n' => '分', 's' => '秒');
        foreach ($unit as $abbr) {
            $log[$abbr] = self::dateDiff($abbr, $start, $expire);
        }
        $lang = array('y' => '年', 'm' => '月', 'w' => '周', 'd' => '天', 'h' => '小时', 'n' => '分', 's' => '秒');
        foreach ($log as $key => $value) {
            if ($value > 0) {
                return $value . $lang[$key] . '前';
                break;
            }
        }
    }

    /**
     * 格式化剩余时间标签
     */
    static public function formatLTimeLabel(Array $leftTime)
    {
        $time = $leftTime;
        $days = $time['leftDays'] > 0 ? $time['leftDays'] . '天' : '';
        $hours = $time['leftHours'] > 0 ? $time['leftHours'] . '小时' : '';
        $minutes = $time['leftMinutes'] > 0 ? $time['leftMinutes'] . '分' : '';
        $seconds = $time['leftSeconds'] > 0 ? $time['leftSeconds'] . '秒' : '';
        return $days . $hours . $minutes . $seconds;
    }
}