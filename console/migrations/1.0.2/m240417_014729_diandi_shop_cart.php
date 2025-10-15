<?php

use yii\db\Migration;

class m240417_014729_diandi_shop_cart extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_shop_cart}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'store_id' => "int(11) NULL",
            'bloc_id' => "int(11) NULL",
            'user_id' => "int(11) NULL COMMENT '用户id'",
            'goods_id' => "int(11) NULL COMMENT '商品id'",
            'spec_id' => "varchar(30) NULL DEFAULT '0' COMMENT '规格组合id'",
            'spec_val' => "varchar(30) NULL COMMENT '规格组合名称'",
            'number' => "int(11) NOT NULL DEFAULT '0' COMMENT '数量'",
            'goods_price' => "decimal(10,2) NULL",
            'total_price' => "decimal(10,2) NULL COMMENT '总价格'",
            'line_price' => "decimal(10,2) NULL",
            'create_time' => "int(11) NULL COMMENT '创建时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='购物车'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_shop_cart}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

