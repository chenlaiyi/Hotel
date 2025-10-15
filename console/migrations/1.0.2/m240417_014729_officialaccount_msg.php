<?php

use yii\db\Migration;

class m240417_014729_officialaccount_msg extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%officialaccount_msg}}', [
            'id' => "bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            'appid' => "char(20) NOT NULL COMMENT 'appid'",
            'openid' => "varchar(32) NOT NULL COMMENT '微信用户ID'",
            'in_out' => "tinyint(1) unsigned NULL COMMENT '消息方向'",
            'msg_type' => "char(25) NULL COMMENT '消息类型'",
            'detail' => "varchar(255) NULL COMMENT '消息详情'",
            'create_time' => "varchar(30) NULL",
            'update_time' => "varchar(30) NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='微信消息'");
        
        /* 索引设置 */
        $this->createIndex('idx_appid','{{%officialaccount_msg}}','appid',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%officialaccount_msg}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

