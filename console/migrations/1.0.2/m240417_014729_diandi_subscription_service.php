<?php

use yii\db\Migration;

class m240417_014729_diandi_subscription_service extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_subscription_service}}', [
            'service_id' => "int(11) NOT NULL AUTO_INCREMENT COMMENT '服务ID'",
            'service_name' => "varchar(255) NULL COMMENT '服务名称'",
            'service_description' => "varchar(255) NULL COMMENT '服务描述'",
            'service_price' => "decimal(10,2) NULL COMMENT '服务价格'",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'PRIMARY KEY (`service_id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_subscription_service}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

