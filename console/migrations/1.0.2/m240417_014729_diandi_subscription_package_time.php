<?php

use yii\db\Migration;

class m240417_014729_diandi_subscription_package_time extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_subscription_package_time}}', [
            'time_id' => "int(11) NOT NULL AUTO_INCREMENT COMMENT '时长ID'",
            'package_id' => "int(11) NULL COMMENT '套餐ID'",
            'time_name' => "varchar(255) NULL COMMENT '时长名称'",
            'time_description' => "varchar(255) NULL COMMENT '时长描述'",
            'time_price' => "decimal(10,2) NULL COMMENT '时长价格'",
            'time_length' => "int(11) NULL COMMENT '时长'",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'PRIMARY KEY (`time_id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='套餐时长'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_subscription_package_time}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

