<?php

use yii\db\Migration;

class m240417_014729_diandi_place_coupon_order_list extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_place_coupon_order_list}}', [
            'id' => "int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '人脸招聘'",
            'bloc_id' => "int(11) NULL COMMENT '人脸库组id'",
            'store_id' => "int(11) NULL",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'member_id' => "int(11) NULL COMMENT '会员id'",
            'coupon_id' => "int(11) NULL COMMENT '卡券id'",
            'price' => "decimal(10,2) NULL COMMENT '价格'",
            'coupon_name' => "varchar(255) NULL COMMENT '卡券名'",
            'coupon_type' => "smallint(6) NULL COMMENT '卡券类型  1：代金券 2：时常卡  3：次卡 4：折扣券 5：体验券'",
            'transaction_id' => "varchar(100) NULL COMMENT '微信订单编号'",
            'order_number' => "varchar(100) NULL COMMENT '订单编号'",
            'pay_time' => "datetime NULL COMMENT '购买时间'",
            'pay_type' => "smallint(6) NULL COMMENT '支付方式：1.现金支付 2.余额支付 3其他平台购买'",
            'status' => "smallint(6) NULL COMMENT '订单状态：1.待付款 2.已完成 '",
            'balance' => "decimal(10,2) NULL COMMENT '余额'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='卡券购买记录表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%diandi_place_coupon_order_list}}',['id'=>'1','bloc_id'=>'0','store_id'=>'0','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27','member_id'=>'1680710400','coupon_id'=>'2','price'=>'2.00','coupon_name'=>'2','coupon_type'=>'2','transaction_id'=>'2','order_number'=>'2','pay_time'=>'2023-07-13 00:00:00','pay_type'=>'3','status'=>'1','balance'=>'2.00']);
        $this->insert('{{%diandi_place_coupon_order_list}}',['id'=>'2','bloc_id'=>'51','store_id'=>'149','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27','member_id'=>'1680624000','coupon_id'=>'3','price'=>'3.00','coupon_name'=>'3','coupon_type'=>'4','transaction_id'=>'3','order_number'=>'3','pay_time'=>'2023-04-10 16:34:08','pay_type'=>'2','status'=>'2','balance'=>'3.00']);
        $this->insert('{{%diandi_place_coupon_order_list}}',['id'=>'3','bloc_id'=>'51','store_id'=>'150','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27','member_id'=>'1680019200','coupon_id'=>'2','price'=>'2.00','coupon_name'=>'2','coupon_type'=>'3','transaction_id'=>'2','order_number'=>'2','pay_time'=>'2023-04-10 16:34:14','pay_type'=>'2','status'=>'1','balance'=>'2.00']);
        $this->insert('{{%diandi_place_coupon_order_list}}',['id'=>'4','bloc_id'=>'51','store_id'=>'149','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27','member_id'=>'1680105600','coupon_id'=>'2','price'=>'2.00','coupon_name'=>'2','coupon_type'=>'1','transaction_id'=>'2','order_number'=>'2','pay_time'=>'2023-04-10 16:34:23','pay_type'=>'2','status'=>'2','balance'=>'2.00']);
        $this->insert('{{%diandi_place_coupon_order_list}}',['id'=>'5','bloc_id'=>'51','store_id'=>'149','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27','member_id'=>'1680710400','coupon_id'=>'3','price'=>'3.00','coupon_name'=>'3','coupon_type'=>'5','transaction_id'=>'3','order_number'=>'3','pay_time'=>'2023-04-10 16:34:28','pay_type'=>'2','status'=>'2','balance'=>'3.00']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_coupon_order_list}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

