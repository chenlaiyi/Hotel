<?php

use yii\db\Migration;

class m240417_014729_diandi_shop_setting1 extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_shop_setting1}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'title' => "varchar(50) NULL COMMENT '商家名称'",
            'banner' => "varchar(255) NULL COMMENT '横幅背景'",
            'logo' => "varchar(255) NULL COMMENT '商家logo'",
            'intro' => "varchar(255) NULL COMMENT '简介'",
            'address' => "varchar(255) NULL COMMENT '商家地址'",
            'mobile' => "bigint(20) NULL COMMENT '商家电话'",
            'shippingDees' => "decimal(10,0) NULL COMMENT '基础配送费'",
            'startingPrice' => "decimal(10,2) NULL COMMENT '起送价'",
            'describe' => "varchar(255) NULL COMMENT '商家详情'",
            'create_time' => "int(11) NULL",
            'update_time' => "int(11) NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_shop_setting1}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

