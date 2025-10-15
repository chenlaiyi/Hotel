<?php

use yii\db\Migration;

class m240417_014729_diandi_shop_wxapp extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_shop_wxapp}}', [
            'wxapp_id' => "int(11) unsigned NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL",
            'store_id' => "int(11) NULL",
            'app_name' => "varchar(50) NOT NULL DEFAULT ''",
            'app_id' => "varchar(50) NOT NULL DEFAULT ''",
            'app_secret' => "varchar(50) NOT NULL DEFAULT ''",
            'is_service' => "tinyint(3) unsigned NOT NULL DEFAULT '0'",
            'service_image_id' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'is_phone' => "tinyint(3) unsigned NOT NULL DEFAULT '0'",
            'phone_no' => "varchar(20) NOT NULL DEFAULT ''",
            'phone_image_id' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'mchid' => "varchar(50) NOT NULL DEFAULT ''",
            'apikey' => "varchar(255) NOT NULL DEFAULT ''",
            'create_time' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'update_time' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'PRIMARY KEY (`wxapp_id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_shop_wxapp}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

