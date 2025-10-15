<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-02-08 08:13:49
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-02-08 08:17:52
 */


/**
 * @link https://www.shopwind.net/
 * @copyright Copyright (c) 2018 ShopWind Inc. All Rights Reserved.
 *
 * This is not free software. Do not use it for commercial purposes.
 * If you need commercial operation, please contact us to purchase a license.
 * @license https://www.shopwind.net/license/
 */

namespace install\library;

use common\helpers\ResultHelper;
use Exception;
use yii;
use yii\helpers\FileHelper;

class BaseInstall
{

    /**
     * 数据库连接信息
     */
    public array $db_options = [];

    /**
     * 错误抓取
     */
    public $errors = null;

    public string $version = '1.0.0';

    /**
     * 构造函数
     * @param string $version
     * @param array $db_options
     */
    public function __construct(string $version = '', array $db_options = [])
    {
        if ($version){
            $this->version = $version;
        }
        if ($db_options){
            $this->db_options = $db_options;
        }
    }

    private function getDb(): yii\db\Connection
    {
        try {
            $dsn = $this->getDsn();
            $connection = new \yii\db\Connection([
                'dsn' => $dsn,
                'username' =>trim($this->db_options['db_user']),
                'password' =>trim($this->db_options['db_password']),
                'charset' => 'utf8',
            ]);
            $connection->open();
            return $connection;
        } catch (\Exception $e) {
            $this->errors = $e->getMessage();
            throw new Exception($e->getMessage());
        }
    }

    public function getDsn(): string
    {
        $db_host = trim($this->db_options['db_host']);
        $db_port = trim($this->db_options['db_port']);
        $db_name = trim($this->db_options['db_name']);
        return "mysql:host=$db_host;port=$db_port;dbname=$db_name";
    }


    /**
     * 判断数据库是否存在
     * @throws Exception
     */
    public function checkDb()
    {
        if (!$this->db_options['db_name']) {
            $this->errors = sprintf('database `%s` empty', $this->db_options['db_name']);
            return false;
        }
        $link = $this->getDb();
        $select_db_sql  = "SELECT * FROM information_schema.schemata WHERE schema_name = '{$this->db_options['db_name']}'";
        $result = $link->query($select_db_sql);
        if (!$result) {
            $this->errors = $link->error;
            return false;
        }
        return true;
    }

    /* 创建数据库 */
    public function createDb(): bool
    {
        $link = $this->getDb();
        if (!$this->checkDb()) {
            return false;
        }

        $sql = "CREATE DATABASE IF NOT EXISTS `{$this->db_options['db_name']}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
        $result = $link->query($sql);
        if (!$result) {
            $this->errors = $link->error;
            return false;
        }

        return true;
    }


    /**
     * 建立数据表结构
     */
    public function createTable()
    {
        // 设置超时时间，防止超时
        set_time_limit(0);
        // 设置执行内存
        ini_set('memory_limit', '1024M');

        $connection = $this->getDb();

        // 输出开始创建表进程
        $version = $this->version;
        $sqls = $this->getSql(Yii::getAlias('@install') . '/sql/' . $version . '/install.sql');
        if (empty($sqls)) {
            $this->errors = "SQL文件不存在或为空";
            return false;
        }

        $batchSize = 50; // 每批执行的SQL语句数量
        $totalSqls = count($sqls);
        $transaction = $connection->beginTransaction();

        try {
            for ($i = 0; $i < $totalSqls; $i += $batchSize) {
                $batch = array_slice($sqls, $i, $batchSize);
                foreach ($batch as $sql) {
                    $connection->createCommand($sql)->execute();
                }
                $transaction->commit(); // 提交事务
                $transaction = $connection->beginTransaction(); // 开始新的事务
            }
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            $this->errors = "Error creating tables: " . $e->getMessage();
            echo "Error creating tables: " . $e->getMessage() . "\n";
            return false;
        }
    }



    /**
     * 检测是否安装测试数据
     */
    public static function isInited()
    {
        $file = Yii::getAlias('@public') . '/data/initdata.lock';

        // 已经安装了
        if (file_exists($file)) {
            return true;
        }
        return false;
    }

    /**
     * 插入测试数据
     * 该操作必须再安装站点，取得DB实例后执行
     */
    public function initData($buyer_id, $seller_id)
    {
        $connection = Yii::$app->db;
        $sqls = $this->getSql(Yii::getAlias('@install') . '/sql/initdata.sql');
        foreach ($sqls as $sql) {
            $sql = $this->replacePrefix('dd_', $connection->tablePrefix, $sql);
            $connection->createCommand($sql)->execute();
        }
    }

    /**
     * 安装测试数据结束
     */
    public static function initend()
    {
        // 清空缓存
        Yii::$app->cache->flush();

        // 锁定文件
        touch(Yii::getAlias('@public') . '/data/initdata.lock');
    }


    public function replacePrefix($orig, $target, $sql)
    {
        return str_replace('`' . $orig, '`' . $target, $sql);
    }

    public function getSql($file)
    {
        if (!file_exists($file)) {
            return ResultHelper::json(400, 'sql文件不存在：' . $file );
        }
        $contents = file_get_contents($file);
        $contents = str_replace("\r\n", "\n", $contents);
        $contents = trim(str_replace("\r", "\n", $contents));
        $result = $items = [];
        $items = explode(";\n", $contents);
        foreach ($items as $item) {
            $string = '';
            $item = trim($item);
            $lines = explode("\n", $item);
            foreach ($lines as $line) {
                if (isset($line[0]) && $line[0] == '#') {
                    continue;
                }
                if (isset($line[1]) && $line[0] . $line[1] == '--') {
                    continue;
                }

                $string .= $line;
            }
            if ($string) {
                $result[] = $string;
            }
        }
        return $result;
    }

    /**
     * 检查环境
     */
    public function checkEnv($required)
    {
        $result = array('detail' => [], 'compatible' => true, 'msg' => []);
        foreach ($required as $key => $value) {
            $checker = $value['checker'];
            $method = $this->$checker();
            $result['detail'][$key] = array(
                'required' => $value['required'],
                'current' => $method['current'],
                'result' => $method['result'] ? 'pass' : 'failed',
            );
            if (!$method['result']) {
                $result['compatible'] = false;
                $result['msg'][] = $key . '_error';
            }
        }
        return $result;
    }

    /**
     * 检查文件是否可写
     */
    public function checkFile($file)
    {
        if (!is_array($file)) $file = array($file);
        $result = array('detail' => [], 'compatible' => true, 'msg' => []);
        foreach ($file as $key => $value) {
            $writabled = $this->isWriteabled(dirname(__DIR__) . '/' . $value);
            $result['detail'][] = [
                'file' => $value,
                'result' => $writabled ? 'pass' : 'failed',
                'current' => $writabled ? 'writable' : 'unwritable',
            ];
            if (!$writabled) {
                $result['compatible'] = false;
                $result['msg'][] = 'file_error' . $value;
            }
        }
        return $result;
    }

    /**
     * 检查文件是否可写
     */
    public function isWriteabled($file)
    {
        if (!file_exists($file)) {
            // 不存在，如果创建失败，则不可写
            if (!FileHelper::createDirectory($file)) {
                return false;
            }
        }
        // 非Windows服务器
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            return is_writable($file);
        }

        // 在Windows的服务器上可能会存在问题，待发现
        if (is_dir($file)) {
            // 如果是目录，则尝试创建文件并修改
            $trail = substr($file, -1);
            if ($trail == '/' || $trail == '\\') {
                $tmpfile = $file . '_temp_file.txt';
            } else {
                $tmpfile = $file . '/' . '_temp_file.txt';
            }
            // 尝试创建文件
            if (false === @touch($tmpfile)) {
                // 不可写
                return false;
            }
            // 创建文件成功
            // 尝试修改该文件
            if (false === @touch($tmpfile)) {
                return false;
            }
            // 修改文件成功
            // 删除文件
            @unlink($tmpfile);
            return true;
        } else {
            // 如果是文件，则尝试修改文件
            if (false === @touch($file)) {
                // 修改不成功，不可写
                return false;
            } else {
                // 修改成功，可写
                return true;
            }
        }
    }

    /**
     * 复制文件
     */
    public function copyFiles()
    {
        FileHelper::copyDirectory(Yii::getAlias('@install') . '/initdata', Yii::getAlias('@public') . '/data');
    }

    /**
     * 检查PHP版本
     */
    public function phpChecker()
    {
        return array(
            'current' => PHP_VERSION,
            'result' => (PHP_VERSION >= 5.4),
        );
    }

    /* 检查GD版本 */
    public function gdChecker()
    {
        $result = array('current' => null, 'result' => false);
        $gd_info = function_exists('gd_info') ? gd_info() : [];
        $result['current'] = empty($gd_info['GD Version']) ? 'gd_missing' : $gd_info['GD Version'];
        $result['result'] = empty($gd_info['GD Version']) ? false : true;

        return $result;
    }

    /* 显示进程 */
    public function showProcess($msg, $result = true, $script = '')
    {
        $class = $result ? 'successed' : 'failed';
        $status = $result ? 'successed' : 'failed';
        return [
            'msg' => $msg,
            'class' => $class,
            'status' => $status,
            'script' => $script,
        ];
    }

    /**
     * 创建网站管理员账号
     */
    public function createAdmin($post = null)
    {
        $link = $this->getDb();
        $client = $this->getClient();
        $client->createCommand($link, $post);
    }

}
