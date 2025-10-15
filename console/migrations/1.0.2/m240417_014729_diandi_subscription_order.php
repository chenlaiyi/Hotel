<?php

use yii\db\Migration;

class m240417_014729_diandi_subscription_order extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_subscription_order}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'title' => "varchar(255) NULL COMMENT '标题'",
            'bloc_id' => "int(11) NULL",
            'package_id' => "int(11) NULL COMMENT '套餐ID'",
            'time_id' => "int(11) NULL COMMENT '时长'",
            'start_time' => "datetime NULL COMMENT '服务开始时间'",
            'end_time' => "datetime NULL COMMENT '服务结束时间'",
            'user_id' => "int(11) NULL COMMENT '用户ID'",
            'order_number' => "varchar(50) NULL COMMENT '订单编号'",
            'transaction_id' => "varchar(100) NULL COMMENT '微信支付单号'",
            'order_status' => "int(11) NULL COMMENT '订单状态0未支付1已支付2已到期'",
            'is_give' => "int(11) NULL DEFAULT '0' COMMENT '0非赠送1赠送'",
            'pay_price' => "decimal(10,2) NULL COMMENT '支付金额'",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");
        
        /* 索引设置 */
        $this->createIndex('package_id','{{%diandi_subscription_order}}','package_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_subscription_order}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

