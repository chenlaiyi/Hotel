<?php

use yii\db\Migration;

class m240417_014729_officialaccount_msg_reply_rule extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%officialaccount_msg_reply_rule}}', [
            'rule_id' => "int(11) NOT NULL AUTO_INCREMENT",
            'appid' => "char(20) NULL DEFAULT '' COMMENT 'appid'",
            'rule_name' => "varchar(20) NOT NULL COMMENT '规则名称'",
            'match_value' => "varchar(200) NOT NULL COMMENT '匹配的关键词、事件等'",
            'exact_match' => "tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否精确匹配'",
            'reply_type' => "varchar(20) NOT NULL DEFAULT '1' COMMENT '回复消息类型'",
            'reply_content' => "varchar(1024) NOT NULL COMMENT '回复消息内容'",
            'status' => "tinyint(1) NOT NULL DEFAULT '1' COMMENT '规则是否有效'",
            'desc' => "varchar(255) NULL COMMENT '备注说明'",
            'effect_time_start' => "varchar(30) NULL",
            'effect_time_end' => "varchar(30) NULL",
            'priority' => "int(3) unsigned NULL DEFAULT '0' COMMENT '规则优先级'",
            'create_time' => "varchar(30) NULL",
            'update_time' => "varchar(30) NULL",
            'PRIMARY KEY (`rule_id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='自动回复规则'");
        
        /* 索引设置 */
        $this->createIndex('idx_appid','{{%officialaccount_msg_reply_rule}}','appid',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%officialaccount_msg_reply_rule}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

