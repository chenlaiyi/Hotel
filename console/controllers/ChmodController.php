<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-07-02 12:49:11
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-07-15 11:49:30
 */

namespace console\controllers;

use common\helpers\FileHelper;
use common\helpers\ResultHelper;
use Yii;

// 使用示例： ./yii addons -addons=diandi_lottery -bloc_id=1 -store_id=3   job ninini

class ChmodController extends \yii\console\Controller
{
    public function actionIndex()
    {
        $baseDir = dirname(__FILE__) . '/../../';

        $dirs = ['api/runtime/', 'public/develop/assets/', 'public/admin/assets/', 'public/runtime/', 'public/assets/', 'public/attachment', 'console/runtime/', 'admin/runtime/', 'api/web/store/'];

        foreach ($dirs as $key => $value) {
            if (is_dir($baseDir . $value)) {
                chmod($baseDir . $value, 0777);
            } else {
                mkdir($baseDir . $value, 0777);
                chmod($baseDir . $value, 0777);
            }
            echo '目录' . $value . '权限设置成功' . PHP_EOL;
            sleep(1);
        }
    }

    public function actionClear()
    {
        $baseDir = dirname(__FILE__) . '/../../';

        $dirs = ['api/runtime', 'console/runtime', 'swoole/runtime', 'admin/runtime'];

        foreach ($dirs as $key => $value) {
            $logs = FileHelper::file_lists($baseDir . $value, 1, 'log');
            $datas = FileHelper::file_lists($baseDir . $value, 1, 'data');

            $lists = array_merge($logs, $datas);
            foreach ($lists as $k => $file) {
                echo '准备清理文件' . $file . PHP_EOL;
                FileHelper::file_delete($file);
            }
            if (is_dir($baseDir . $value)) {
                echo '准备清理目录' . $baseDir . $value . PHP_EOL;
                FileHelper::rmdirs($baseDir . $value, true);
            }
            ob_end_clean();
        }

        // 重新修复文件权限

        $baseDir = dirname(__FILE__) . '/../../';
        $dirs = ['api/runtime/', 'public/runtime/', 'public/assets/', 'public/attachment', 'swoole/runtime/', 'admin/runtime/', 'api/web/store/'];

        foreach ($dirs as $key => $value) {
            if (is_dir($baseDir . $value)) {
                chmod($baseDir . $value, 0777);
            } else {
                mkdir($baseDir . $value, 0777);
                chmod($baseDir . $value, 0777);
            }
            echo '目录' . $value . '权限设置成功' . PHP_EOL;
            sleep(1);
        }
    }


    function actionClearCatch()
    {
        try {
            Yii::$app->cache->flush();
            return ResultHelper::json(200, '清理成功');
        } catch (\Exception $e) {
            return ResultHelper::json(400, $e->getMessage());
        }
    }
}
