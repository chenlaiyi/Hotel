<?php

function is_datetime_str($str)
{
    $r = false;

    if (! empty($str)) {
        $r = strtotime($str);
    }

    return $r;
}

function diff_hours($start_time, $end_time)
{
    $_start = strtotime($start_time);
    $_end   = strtotime($end_time);

    $r = number_format(($_end - $_start) / (60 * 60), 1);

    return $r;
}

function diff_days($start_time, $end_time)
{
    $_start = strtotime($start_time);
    $_end   = strtotime($end_time);

    $r = ceil(($_end - $_start) / (60 * 60 * 24));

    return $r;
}

/**
 * @author Michael Liang
 * @email  Liang15946@163.com
 * @date   2025-07-08
 * @desc   匹配数字，字母，符号，中文 其余的内容都替换
 * @param  [type]             $str [description]
 * @return [type]                  [description]
 */
function replace_str($str, $replace = '')
{
    // 使用正则表达式替换不符合条件的字符
    // \p{L} 匹配任何语言的所有字母
    // \p{N} 匹配任何语言的所有数字
    // [!-@[-`{-~] 匹配常见的符号
    // [\x{4e00}-\x{9fa5}] 匹配中文字符
    $reg    = '/[^a-zA-Z0-9\x{4e00}-\x{9fa5}]/u';
    $result = preg_replace($reg, $replace, $str);

    return $result;
}

function dd(...$args)
{
    echo '<pre>';

    foreach ($args as $item) {
        var_dump($item);
    }

    exit;
}

function format_bytes($bytes, $precision = 2)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow   = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, $precision) . ' ' . $units[$pow];
}
