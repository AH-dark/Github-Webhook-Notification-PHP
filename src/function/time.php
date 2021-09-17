<?php

/**
 * @describe 将UTC时间转换为常用时间表达
 * @param string $utcTime UTC格式时间 例如2021-09-17T20:43:46+08:00
 * @return string xxxx年xx月xx日 xx:xx:xx (UTF +xx:xx)
 */
function UTC_to_time(string $utcTime): string
{
    //  2021-09-17T20:43:46+08:00
    $utcTime = trim($utcTime);
    preg_match("/^(\d{4})-(\d{1,2})-(\d{1,2})T(\d{1,2}):(\d{1,2}):(\d{1,2})([+-]\d{1,2}):(\d{1,2})$/i", $utcTime, $time);
    echo "UTC to Time:\n";
    print_r($time);
    $_time = [
        "y" => (int)$time[1],
        "m" => (int)$time[2],
        "d" => (int)$time[3],
        "h" => (int)$time[4],
        "min" => (int)$time[5],
        "s" => (int)$time[6],
        "timezone" => $time[8] !== "00" ? $time[7] . ":" . $time[8] : $time[7]
    ];
    return "{$_time["y"]}年{$_time["m"]}月{$_time["d"]}日 {$_time["h"]}:{$_time["min"]}:{$_time["s"]} (UTF {$_time["timezone"]})";
}