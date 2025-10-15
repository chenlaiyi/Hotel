<?php

use yii\db\Migration;

class m240417_014729_diandi_subscription_help_topic extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_subscription_help_topic}}', [
            'topic_id' => "int(11) NOT NULL AUTO_INCREMENT",
            'category_pid' => "int(11) NULL",
            'category_id' => "int(11) NOT NULL",
            'topic_title' => "varchar(255) NOT NULL",
            'topic_content' => "text NULL",
            'read_count' => "int(11) NULL DEFAULT '0'",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'PRIMARY KEY (`topic_id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");
        
        /* 索引设置 */
        $this->createIndex('category_id','{{%diandi_subscription_help_topic}}','category_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_subscription_help_topic}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

