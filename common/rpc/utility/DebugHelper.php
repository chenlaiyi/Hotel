<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-09-04 00:11:18
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-30 22:54:38
 */

namespace common\rpc\utility;


use common\helpers\loggingHelper;
use Yii;

class DebugHelper
{
    public static function backtrace(): void
    {
        if (self::isCoroutine()) {
            $array = debug_backtrace();
            foreach ($array as $row) {
                if (isset($row['file'])) {
                    var_dump($row['file'] . ':' . $row['line'] . '行,调用方法:' . $row['function']);
                }
            }
        }
    }

    /**
     * 控制台调试内容显示.
     *
     * @param [type] $remark  备注
     * @param string|array $content
     * @param string $path [info,error,warning]
     * @return void
     * @date 2022-09-08
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public static function consoleWrite($remark, string|array $content = '', string $path = 'info'): void
    {
        $memoryInit = memory_get_usage() / 1024 / 1024;
        $time = date('H:i:s', time());
        if (self::isCoroutine()) {
            if (is_array($content)) {
                $content = json_encode($content, JSON_UNESCAPED_UNICODE);
            }

            echo "#$time#[$remark]#$content#内存消耗:[$memoryInit]MB" . PHP_EOL;
            $path = Yii::getAlias('@console/runtime/rpc/'.$path);
            LoggingHelper::writeLog('rpc',$path,$remark,$content);
        }else{
            echo "#$time#[$remark]#$content#内存消耗:[$memoryInit]MB" . PHP_EOL;
        }
    }


    /**
     * 控制台分割线
     *
     * @param [type] $remark
     *
     * @return void
     * @date 2022-09-08
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public static function consoleCrosswise($remark): void
    {
        if (self::isCoroutine()) {
            $memoryInit = memory_get_usage() / 1024 / 1024;
            $time = date('H:i:s', time());
            echo "#$time#[$remark]#----------------内存消耗:[$memoryInit]MB-------------------------" . PHP_EOL;
        }
    }

    public static function isCoroutine(): bool
    {
        $cid = \Swoole\Coroutine::getCid();

        return $cid > 0;
    }
}
