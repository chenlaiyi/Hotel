<?php

use yii\db\Migration;

class m240417_014729_diandi_subscription_package_service extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_subscription_package_service}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'package_id' => "int(11) NULL COMMENT '套餐ID'",
            'service_id' => "int(11) NULL COMMENT '服务ID'",
            'usage_limit' => "int(11) NULL COMMENT '使用限制'",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED");
        
        /* 索引设置 */
        $this->createIndex('package_id','{{%diandi_subscription_package_service}}','package_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_subscription_package_service}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

