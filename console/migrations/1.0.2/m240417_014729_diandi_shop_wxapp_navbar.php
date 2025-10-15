<?php

use yii\db\Migration;

class m240417_014729_diandi_shop_wxapp_navbar extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_shop_wxapp_navbar}}', [
            'wxapp_id' => "int(11) unsigned NOT NULL",
            'bloc_id' => "int(11) NULL",
            'store_id' => "int(11) NULL",
            'wxapp_title' => "varchar(100) NOT NULL DEFAULT ''",
            'top_text_color' => "tinyint(3) unsigned NOT NULL DEFAULT '10'",
            'top_background_color' => "varchar(10) NOT NULL DEFAULT ''",
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
        $this->dropTable('{{%diandi_shop_wxapp_navbar}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

