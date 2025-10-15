<?php

use yii\db\Migration;

class m240417_014729_officialaccount_template_msg_log extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%officialaccount_template_msg_log}}', [
            'log_id' => "bigint(11) NOT NULL AUTO_INCREMENT COMMENT 'ID'",
            'appid' => "char(20) NOT NULL COMMENT 'appid'",
            'touser' => "varchar(50) NULL COMMENT '用户openid'",
            'template_id' => "varchar(50) NULL COMMENT 'templateid'",
            'data' => "varchar(255) NULL COMMENT '消息数据'",
            'url' => "varchar(255) NULL COMMENT '消息链接'",
            'miniprogram' => "varchar(255) NULL COMMENT '小程序信息'",
            'send_time' => "varchar(30) NULL COMMENT '发送时间'",
            'send_result' => "varchar(50) NULL COMMENT '发送结果'",
            'PRIMARY KEY (`log_id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微信模版消息发送记录'");
        
        /* 索引设置 */
        $this->createIndex('idx_appid','{{%officialaccount_template_msg_log}}','appid',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%officialaccount_template_msg_log}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

