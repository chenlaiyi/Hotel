<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-06-27 14:06:58
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-07-14 15:25:04
 */

namespace common\helpers;

use diandi\addons\models\ActionLog;
use Yii;

class loggingHelper
{
    /**
     * 检测目录并循环创建目录.
     *
     * @param $catalogue
     * @return true
     */
    public static function mkdirs($catalogue)
    {
        if (!file_exists($catalogue)) {
            self::mkdirs(dirname($catalogue));
            mkdir($catalogue, 0777);
        }

        return true;
    }

    /**
     * 写入日志.
     *
     * @param $moduleName
     * @param $path
     * @param $mark
     * @param array|string $content
     *
     * @return bool|int
     */
    public static function writeLog($moduleName, $path, $mark, array|string $content = [])
    {
       if (str_contains($path,'runtime')){
            $basePath = $path;
        }else{
           $appId = Yii::$app->id;
           list($app, $alia) = explode('-', $appId);
           $basePath = Yii::getAlias("@{$alia}/runtime/" . $moduleName . '/' . date('Y/m/d/') . $path . '.log');
       }
        self::mkdirs(dirname($basePath));
        @chmod($basePath, 0777);
        /**
         * 毫秒时间
         */
        $timeMicrotime = microtime(true);
        $time = date('m/d H:i:s').'/microtime:'.$timeMicrotime;
        // 加入内存使用情况
        $memoryInit = memory_get_usage() / 1024 / 1024;

        $memory = StringHelper::currency_format($memoryInit, 2) . 'mb';
        $contentTxt = '';
        if (is_array($content)) {
            $content['memory'] = $memory;
            $contentTxt = json_encode($content, JSON_UNESCAPED_UNICODE);
        } elseif (is_string($content)) {
            $contentTxt = $content . '//memory:' . $memory;
        }

        return file_put_contents($basePath, PHP_EOL . $time . '-' . $mark . ':' . $contentTxt, FILE_APPEND);
    }

    public static function actionLog($user_id, $operation, $logip)
    {
        $ActionLog = new ActionLog();
        $ActionLog->load([
            'user_id' => $user_id,
            'operation' => $operation,
            'logtime' => date('Y-m-d H:i:s', time()),
            'logip' => $logip,
        ], '');

        return $ActionLog->save();
    }
}
