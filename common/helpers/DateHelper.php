<?php
/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-15 16:10:15
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-04-02 20:14:33
 */

namespace common\helpers;

class DateHelper
{
    /**
     * 获取今日开始时间戳和结束时间戳.
     *
     * 语法：mktime(hour,minute,second,month,day,year) => (小时,分钟,秒,月份,天,年)
     */
    public static function today()
    {
        return [
            'start' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
            'end'   => mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1,
        ];
    }

    /**
     * 当天时间
     *
     * @return array
     */
    public static function todayStr()
    {
        return [
            'start' => date('Y-m-d 00:00:00', time()),
            'end'   => date('Y-m-d 23:59:59', time()),
        ];
    }

    /**
     * 昨日.
     *
     * @return array
     */
    public static function yesterday()
    {
        return [
            'start' => mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')),
            'end'   => mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1,
        ];
    }

    public static function yesterdayStr()
    {
        return [
            'start' => date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'))),
            'end'   => date('Y-m-d ', mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1),
        ];
    }

    /**
     * 这周.
     *
     * @return array
     */
    public static function thisWeek()
    {
        $length = 0;
        // 星期天直接返回上星期，因为计算周围 星期一到星期天，如果不想直接去掉
        if (date('w') == 0) {
            $length = 7;
        }

        return [
            'start' => mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - $length, date('Y')),
            'end'   => mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - $length, date('Y')),
        ];
    }

    /**
     * 上周.
     *
     * @return array
     */
    public static function lastWeek()
    {
        $length = 7;
        // 星期天直接返回上星期，因为计算周围 星期一到星期天，如果不想直接去掉
        if (date('w') == 0) {
            $length = 14;
        }

        return [
            'start' => mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - $length, date('Y')),
            'end'   => mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - $length, date('Y')),
        ];
    }

    /**
     * 本月.
     *
     * @return array
     */
    public static function thisMonth()
    {
        return [
            'start' => mktime(0, 0, 0, date('m'), 1, date('Y')),
            'end'   => mktime(23, 59, 59, date('m'), date('t'), date('Y')),
        ];
    }

    /**
     * 本月 时间日期
     *
     * @return array
     */
    public static function thisMonthStr()
    {
        return [
            'start' => date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), 1, date('Y'))),
            'end'   => date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('t'), date('Y'))),
        ];
    }

    /**
     * 上个月.
     *
     * @return array
     */
    public static function lastMonth()
    {
        $start = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));
        $end   = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));

        if (date('m', $start) != date('m', $end)) {
            $end -= 60 * 60 * 24;
        }

        return [
            'start' => $start,
            'end'   => $end,
        ];
    }

    /**
     * 某一天.
     *
     * @param [type] $day
     *
     * @return array|bool
     */
    public static function dayAgo($day)
    {
        if (!$day) {
            return false;
        }

        return [
            'start' => mktime(0, 0, 0, date('m', $day), date('d', $day), date('Y')),
            'end'   => mktime(23, 59, 59, date('m', $day), date('d', $day), date('Y')),
        ];
    }

    /**
     * 几个月前.
     *
     * @param int $month 月份
     *
     * @return array
     */
    public static function monthsAgo($month)
    {
        return [
            'start' => mktime(0, 0, 0, date('m') - $month, 1, date('Y')),
            'end'   => mktime(23, 59, 59, date('m') - $month, date('t'), date('Y')),
        ];
    }

    /**
     * 某年.
     *
     * @param $year
     *
     * @return array
     */
    public static function aYear($year, $type = 1)
    {
        $start_month = 1;
        $end_month   = 12;

        $start_time = $year . '-' . $start_month . '-1 00:00:00';
        $end_month  = $year . '-' . $end_month . '-1 23:59:59';
        $end_time   = date('Y-m-t H:i:s', strtotime($end_month));
        if ($type === 1) {
            return [
                'start' => strtotime($start_time),
                'end'   => strtotime($end_time),
            ];
        } else {
            return [
                'start' => $start_time,
                'end'   => $end_time,
            ];
        }
    }

    /**
     * 某月.
     *
     * @param int $year
     * @param int $month
     *
     * @return array
     */
    public static function aMonth($year = 0, $month = 0)
    {
        $year  = $year ?? date('Y');
        $month = $month ?? date('m');
        $day   = date('t', strtotime($year . '-' . $month));

        return [
            'start' => strtotime($year . '-' . $month),
            'end'   => mktime(23, 59, 59, $month, $day, $year),
        ];
    }

    /**
     * @param string $format
     *
     * @return mixed
     */
    public static function getWeekName(int $time, $format = '周')
    {
        $week     = date('w', $time);
        $weekname = ['日', '一', '二', '三', '四', '五', '六'];
        foreach ($weekname as &$item) {
            $item = $format . $item;
        }

        return $weekname[ $week ];
    }

    /**
     * 格式化时间戳.
     *
     * @param $time
     *
     * @return string
     */
    public static function formatTimestamp($time)
    {
        $min   = $time / 60;
        $hours = $time / 60;
        $days  = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min   = floor($min - ($days * 60 * 24) - ($hours * 60));

        return $days . ' 天 ' . $hours . ' 小时 ' . $min . ' 分钟 ';
    }

    /**
     * 时间戳.
     *
     * @param int $accuracy 精度 默认微妙
     *
     * @return int
     */
    public static function microtime($accuracy = 1000)
    {
        [$msec, $sec] = explode(' ', microtime());
        $msectime = (float) sprintf('%.0f', (floatval($msec) + floatval($sec)) * $accuracy);

        return $msectime;
    }

    /**
     * convert unix timestamp to ISO 8601 compliant date string.
     *
     * @param int $timestamp Unix time stamp
     * @param bool $utc Whether the time stamp is UTC or local
     *
     * @return mixed ISO 8601 date string or false
     */
    public static function timestamp_to_iso8601($timestamp, $utc = true)
    {
        $datestr = date('Y-m-d\TH:i:sO', $timestamp);
        $pos     = strrpos($datestr, '+');
        if ($pos === false) {
            $pos = strrpos($datestr, '-');
        }
        if ($pos !== false) {
            if (strlen($datestr) == $pos + 5) {
                $datestr = substr($datestr, 0, $pos + 3) . ':' . substr($datestr, -2);
            }
        }
        if ($utc) {
            $pattern = '/' .
                '([0-9]{4})-' .                  // centuries & years CCYY-
                '([0-9]{2})-' .                  // months MM-
                '([0-9]{2})' .                   // days DD
                'T' .                            // separator T
                '([0-9]{2}):' .                  // hours hh:
                '([0-9]{2}):' .                  // minutes mm:
                '([0-9]{2})(\.[0-9]*)?' .        // seconds ss.ss...
                '(Z|[+\-][0-9]{2}:?[0-9]{2})?' . // Z to indicate UTC, -/+HH:MM:SS.SS... for local tz's
                '/';

            if (preg_match($pattern, $datestr, $regs)) {
                return sprintf('%04d-%02d-%02dT%02d:%02d:%02dZ', $regs[ 1 ], $regs[ 2 ], $regs[ 3 ], $regs[ 4 ], $regs[ 5 ], $regs[ 6 ]);
            }

            return false;
        } else {
            return $datestr;
        }
    }

    /**
     * convert ISO 8601 compliant date string to unix timestamp.
     *
     * @param string $datestr ISO 8601 compliant date string
     *
     * @return mixed Unix timestamp (int) or false
     */
    public static function iso8601_to_timestamp($datestr)
    {
        $pattern = '/' .
            '([0-9]{4})-' .                  // centuries & years CCYY-
            '([0-9]{2})-' .                  // months MM-
            '([0-9]{2})' .                   // days DD
            'T' .                            // separator T
            '([0-9]{2}):' .                  // hours hh:
            '([0-9]{2}):' .                  // minutes mm:
            '([0-9]{2})(\.[0-9]+)?' .        // seconds ss.ss...
            '(Z|[+\-][0-9]{2}:?[0-9]{2})?' . // Z to indicate UTC, -/+HH:MM:SS.SS... for local tz's
            '/';
        if (preg_match($pattern, $datestr, $regs)) {
            // not utc
            if ($regs[ 8 ] != 'Z') {
                $op = substr($regs[ 8 ], 0, 1);
                $h  = substr($regs[ 8 ], 1, 2);
                $m  = substr($regs[ 8 ], strlen($regs[ 8 ]) - 2, 2);
                if ($op == '-') {
                    $regs[ 4 ] = $regs[ 4 ] + $h;
                    $regs[ 5 ] = $regs[ 5 ] + $m;
                } else if ($op == '+') {
                    $regs[ 4 ] = $regs[ 4 ] - $h;
                    $regs[ 5 ] = $regs[ 5 ] - $m;
                }
            }

            return gmmktime($regs[ 4 ], $regs[ 5 ], $regs[ 6 ], $regs[ 2 ], $regs[ 3 ], $regs[ 1 ]);
            //		return strtotime("$regs[1]-$regs[2]-$regs[3] $regs[4]:$regs[5]:$regs[6]Z");
        } else {
            return false;
        }
    }

    /**
     * sleeps some number of microseconds.
     *
     * @param string $usec the number of microseconds to sleep
     *
     * @deprecated
     */
    public static function usleepWindows($usec)
    {
        $start = gettimeofday();

        do {
            $stop       = gettimeofday();
            $timePassed = 1000000 * ($stop[ 'sec' ] - $start[ 'sec' ])
                + $stop[ 'usec' ] - $start[ 'usec' ];
        } while ($timePassed < $usec);
    }

    /**
     * 日期转时间戳.
     *
     * @param $value
     *
     * @return false|int
     */
    public static function dateToInt($value)
    {
        if (empty($value)) {
            return $value;
        }

        if (!is_numeric($value)) {
            return strtotime($value);
        }

        return $value;
    }

    /**
     * 时间戳转日期
     *
     * @param $value
     * @param string $format
     * @return false|int
     */
    public static function intToDate($value, $format = 'Y-m-d H:i:s')
    {
        if (empty($value)) {
            return date($format);
        }

        if (is_numeric($value)) {
            return date($format, $value);
        }

        return $value;
    }

    /**
     * 获取指定日期所在周的开始和结束时间
     *
     * @param $date
     * @param bool $isSundayFirst 是否以周日为每周第一天（默认否）
     * @return array
     * @throws \Exception
     */
    public static function getWeekRange($date, $isSundayFirst = false)
    {
        $dt = new \DateTime($date);

        // 获取周开始时间
        $startDay = $isSundayFirst ? 'Sunday last week' : 'Monday this week';
        $start    = clone $dt;
        $start->modify($startDay)->setTime(0, 0, 0);

        // 获取周结束时间
        $endDay = $isSundayFirst ? 'Saturday this week' : 'Sunday this week';
        $end    = clone $dt;
        $end->modify($endDay)->setTime(23, 59, 59);

        return [
            $start->format('Y-m-d H:i:s'),
            $end->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * 获取一个日期所在周的所有天数[2025-07-14,2025-07-15... 2025-07-20]
     *
     * @param $date
     * @return array
     * @throws \Exception
     */
    public static function getDaysInWeek($date)
    {
        $dateTime = new \DateTime($date);

        // 将日期设置为所在周的周一（因为默认可能以周日为一周开始，通过 modify 调整到周一）
        $dateTime->modify('this week monday');

        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $weekDays[] = $dateTime->format('Y-m-d');
            $dateTime->add(new \DateInterval('P1D'));
        }

        return $weekDays;
    }

    /**
     * 获取指定日期所在月的开始和结束时间
     *
     * @param $date
     * @return array
     * @throws \Exception
     */
    public static function getMonthRange($date)
    {
        $dt = new \DateTime($date);

        // 月开始时间：当月第一天 00:00:00
        $start = clone $dt;
        $start->modify('first day of this month')->setTime(0, 0, 0);

        // 月结束时间：当月最后一天 23:59:59
        $end = clone $dt;
        $end->modify('last day of this month')->setTime(23, 59, 59);

        return [
            $start->format('Y-m-d H:i:s'),
            $end->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * 获取一个日期所在月的所有天数[2025-07-01,2025-07-02... 2025-07-31]
     *
     * @param $date
     * @return array
     * @throws \Exception
     */
    public static function getDaysInMonth($date)
    {
        // 解析年月
        [$year, $month] = explode('-', $date);

        // 获取当月第一天和最后一天
        $startDate = new \DateTime("{$year}-{$month}-01");
        $endDate   = (clone $startDate)->modify('last day of this month');

        // 设置日期间隔为1天
        $interval = new \DateInterval('P1D');

        // 生成日期序列
        $period = new \DatePeriod($startDate, $interval, $endDate);

        // 转换为数组
        $days = [];
        foreach ($period as $date) {
            $days[] = $date->format('Y-m-d');
        }

        // 手动添加最后一天（避免因 DatePeriod 的排他性导致缺失）
        $days[] = $endDate->format('Y-m-d');

        return $days;
    }

    /**
     * 获取指定日期所在年份的周的日期范围(今年第一周....今年最后一周)
     *
     * @param string $date 日期字符串（格式：YYYY-MM-DD）
     * @return array [months => 月份数组, weeks => 周日期范围数组]
     */
    public static function getYearlyWeekRanges($date)
    {
        if (strpos($date, '-') !== false) {
            $dt   = new \DateTime($date);
            $year = $dt->format('Y');
        } else {
            $year = $date;
        }

        // 获取本年第一周的开始日期（可能属于上一年）
        $firstWeekStart = new \DateTime("{$year}-01-01");
        $firstWeekStart->modify('Monday this week');

        // 获取本年最后一周的结束日期（可能属于下一年）
        $lastWeekEnd = new \DateTime("{$year}-12-31");
        $lastWeekEnd->modify('Sunday this week');

        // 生成所有周的日期范围
        $weeks       = [];
        $currentDate = clone $firstWeekStart;

        while ($currentDate <= $lastWeekEnd) {
            $weekStart = clone $currentDate;
            $weekEnd   = clone $currentDate;
            $weekEnd->modify('+6 days');

            $weeks[] = [
                'start' => $weekStart->format('Y-m-d'),
                'end'   => $weekEnd->format('Y-m-d'),
                'label' => "{$weekStart->format('Y-m-d')}/{$weekEnd->format('Y-m-d')}",
            ];

            // 移动到下一周
            $currentDate->modify('+7 days');
        }

        return $weeks;
    }

    /**
     * 获取指定日期所在年份的所有月份的日期范围
     *
     * @param string $date 日期字符串（格式：YYYY-MM-DD）
     * @return array 月份日期范围数组（格式：YYYY-MM-01/YYYY-MM-最后一天）
     */
    public static function getYearlyMonthRanges($date)
    {
        if (strpos($date, '-') !== false) {
            $dt   = new \DateTime($date);
            $year = $dt->format('Y');
        } else {
            $year = $date;
        }

        $months = [];

        for ($month = 1; $month <= 12; $month++) {
            // 获取当月第一天
            $firstDay = new \DateTime("{$year}-{$month}-01");

            // 获取当月最后一天
            $lastDay = clone $firstDay;
            $lastDay->modify('last day of this month');

            $months[] = [
                'month' => $month,
                'start' => $firstDay->format('Y-m-d'),
                'end'   => $lastDay->format('Y-m-d'),
                'label' => "{$firstDay->format('Y-m-d')}/{$lastDay->format('Y-m-d')}",
            ];
        }
        return $months;
    }


    /**
     * 获取指定日期所在年份的每个月的开始时间和结束时间
     *
     * @param string $date 输入日期，如 '2025-07-17'
     * @return array        返回每个月的开始和结束时间（格式：Y-m-d H:i:s）
     */
    public static function getYearMonthsStartEnd($date)
    {
        // 解析为 DateTime 对象
        $datetime = new \DateTime($date);
        // 获取年份
        $year = $datetime->format('Y');

        $result = [];
        // 遍历 12 个月
        for ($month = 1; $month <= 12; $month++) {
            // 当月的第一天（开始时间）
            $start = new \DateTime("$year-$month-01 00:00:00");
            // 下个月的第一天，减去 1 秒即为当月的最后一天（结束时间）
            $end = (clone $start)->modify('+1 month -1 second');

            $result[] = [
                'month' => $month,  // 月份（1-12）
                'start' => $start->format('Y-m-d H:i:s'),
                'end'   => $end->format('Y-m-d H:i:s')
            ];
        }

        return $result;
    }

    public static function is_datetime_str($str)
    {
        $r = strtotime($str);

        return $r;
    }

    public static function diff_hours($start_time, $end_time)
    {
        $_start = strtotime($start_time);
        $_end   = strtotime($end_time);

        $r = number_format(($_end - $_start) / (60 * 60), 1);

        return $r;
    }

    public static function diff_days($start_time, $end_time)
    {
        $_start = strtotime($start_time);
        $_end   = strtotime($end_time);

        $r = ceil(($_end - $_start) / (60 * 60 * 24));

        return $r;
    }


    /**
     * 获取指定日期所在月的周末日期（支持仅获取周日或同时获取周六和周日）
     * @param string $date 指定日期（格式：YYYY-MM-DD）
     * @param bool $includeSaturday 是否包含周六（默认：true，即同时获取周六和周日）
     * @return array 包含周末日期的数组（格式：YYYY-MM-DD）
     */
    public static function getWeekendsInMonth($date, $includeSaturday = true) {
        // 解析日期并获取当月第一天和最后一天
        $dateObj = new \DateTime($date);
        $firstDay = clone $dateObj;
        $firstDay->modify('first day of this month');

        $lastDay = clone $dateObj;
        $lastDay->modify('last day of this month');

        // 存储结果的数组
        $weekends = [];

        // 遍历当月每一天
        $currentDay = clone $firstDay;
        while ($currentDay <= $lastDay) {
            // 获取当前日期是星期几（0=周日，6=周六）
            $dayOfWeek = (int)$currentDay->format('w');

            // 根据配置决定是否添加到结果
            if ($dayOfWeek === 0 || ($includeSaturday && $dayOfWeek === 6)) {
                $weekends[] = $currentDay->format('Y-m-d');
            }

            // 移动到下一天
            $currentDay->modify('+1 day');
        }

        return $weekends;
    }

}
