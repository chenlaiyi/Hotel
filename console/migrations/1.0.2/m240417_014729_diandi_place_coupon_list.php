<?php

use yii\db\Migration;

class m240417_014729_diandi_place_coupon_list extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_place_coupon_list}}', [
            'id' => "int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '卡券消费记录id'",
            'bloc_id' => "int(11) NULL COMMENT '人脸库组id'",
            'store_id' => "int(11) NULL",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'member_id' => "int(11) NULL COMMENT '会员id'",
            'coupon_name' => "varchar(100) NOT NULL COMMENT '卡券名称'",
            'coupon_type' => "smallint(6) NULL COMMENT '卡券类型  1：代金券 2：时常卡  3：次卡 4：折扣券 5：体验券'",
            'coupon_id' => "int(11) NULL COMMENT '卡券id'",
            'order_id' => "int(11) NULL COMMENT '订单id'",
            'price' => "decimal(10,0) NULL COMMENT '卡券价格'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='卡券消费记录表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%diandi_place_coupon_list}}',['id'=>'1','bloc_id'=>'51','store_id'=>'150','create_time'=>'2024-03-21 12:07:27','update_time'=>'2024-03-21 12:07:27','member_id'=>'2','coupon_name'=>'2','coupon_type'=>'2','coupon_id'=>'2','order_id'=>'2','price'=>'2']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_coupon_list}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

