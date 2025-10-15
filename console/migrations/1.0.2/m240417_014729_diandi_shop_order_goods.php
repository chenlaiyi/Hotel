<?php

use yii\db\Migration;

class m240417_014729_diandi_shop_order_goods extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_shop_order_goods}}', [
            'order_goods_id' => "int(11) unsigned NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL",
            'store_id' => "int(255) NULL",
            'goods_id' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'goods_name' => "varchar(255) NOT NULL DEFAULT ''",
            'thumb' => "varchar(255) NOT NULL DEFAULT '0'",
            'stock_up' => "int(11) NULL DEFAULT '0' COMMENT '库存是否处理'",
            'deduct_stock_type' => "tinyint(3) unsigned NULL DEFAULT '20'",
            'spec_type' => "tinyint(3) unsigned NULL DEFAULT '0'",
            'spec_sku_id' => "varchar(255) NULL DEFAULT ''",
            'goods_spec_id' => "int(11) unsigned NULL DEFAULT '0'",
            'goods_attr' => "varchar(500) NULL DEFAULT ''",
            'content' => "longtext NULL",
            'goods_no' => "varchar(100) NULL DEFAULT ''",
            'goods_price' => "decimal(10,2) unsigned NOT NULL",
            'line_price' => "decimal(10,2) unsigned NOT NULL",
            'goods_weight' => "double unsigned NOT NULL DEFAULT '0'",
            'total_num' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'total_price' => "decimal(10,2) unsigned NOT NULL",
            'order_id' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'user_id' => "int(11) unsigned NOT NULL DEFAULT '0' COMMENT '下单人'",
            'wxapp_id' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'remark' => "varchar(255) NULL",
            'create_time' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'update_time' => "int(11) NULL",
            'PRIMARY KEY (`order_goods_id`)'
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
        $this->dropTable('{{%diandi_shop_order_goods}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

