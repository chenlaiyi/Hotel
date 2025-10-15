<?php

use yii\db\Migration;

class m240417_014729_diandi_shop_order extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_shop_order}}', [
            'order_id' => "int(11) unsigned NOT NULL AUTO_INCREMENT",
            'order_type' => "int(11) NULL",
            'bloc_id' => "int(11) NULL",
            'store_id' => "int(11) NULL",
            'order_no' => "varchar(20) NOT NULL DEFAULT ''",
            'order_body' => "varchar(255) NULL",
            'total_price' => "decimal(10,2) unsigned NOT NULL",
            'pay_price' => "decimal(10,2) unsigned NOT NULL",
            'pay_status' => "tinyint(3) unsigned NOT NULL DEFAULT '10'",
            'pay_time' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'pay_type' => "varchar(255) NULL COMMENT '支付方式'",
            'express_price' => "decimal(10,2) unsigned NOT NULL",
            'express_company' => "varchar(50) NOT NULL DEFAULT ''",
            'express_no' => "varchar(50) NOT NULL DEFAULT ''",
            'delivery_status' => "tinyint(3) unsigned NOT NULL DEFAULT '0'",
            'delivery_time' => "varchar(20) NOT NULL DEFAULT '0' COMMENT '发货时间'",
            'pay_remark' => "varchar(255) NULL COMMENT '付款凭证'",
            'receipt_status' => "tinyint(3) unsigned NOT NULL DEFAULT '10'",
            'receipt_time' => "int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收货时间'",
            'order_status' => "tinyint(3) unsigned NOT NULL DEFAULT '10'",
            'transaction_id' => "varchar(30) NOT NULL DEFAULT ''",
            'user_id' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'wxapp_id' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'remark' => "varchar(255) NULL",
            'print_id' => "varchar(255) NULL",
            'refund_code' => "varchar(255) NULL",
            'refund_res' => "varchar(255) NULL",
            'refund_status' => "int(11) NULL",
            'create_time' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'update_time' => "int(11) unsigned NOT NULL DEFAULT '0'",
            'PRIMARY KEY (`order_id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");
        
        /* 索引设置 */
        $this->createIndex('order_no','{{%diandi_shop_order}}','order_no',1);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_shop_order}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

