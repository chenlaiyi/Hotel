<?php
use yii\db\Migration;
class m240108_234139_diandi_place_recharge_list extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_recharge_list}}', [
            'id' => "int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '余额充值id'",
            'bloc_id' => "int(11) NULL COMMENT '人脸库组id'",
            'store_id' => "int(11) NULL",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'member_id' => "int(11) NULL COMMENT '充值用户id'",
            'recharge_id' => "int(11) NULL COMMENT '充值套餐列表id'",
            'price' => "decimal(10,2) NULL COMMENT '花费金额'",
            'balance' => "decimal(10,2) NULL COMMENT '余额'",
            'pay_time' => "datetime NULL COMMENT '支付时间'",
            'transaction_id' => "varchar(100) NULL COMMENT '微信订单编号'",
            'order_number' => "varchar(100) NULL COMMENT '订单编号'",
            'status' => "smallint(6) NULL COMMENT '订单状态：1.待付款 2.已完成 '",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='会员充值记录'");
        /* 索引设置 */
        /* 表数据 */
        $this->insert('{{%diandi_place_recharge_list}}',['id'=>'1','bloc_id'=>'0','store_id'=>'0','create_time'=>'0000-00-00 00:00:00','update_time'=>'0000-00-00 00:00:00','member_id'=>'2','recharge_id'=>'2','price'=>'2.00','balance'=>'2.00','pay_time'=>'2023-04-11 10:50:59','transaction_id'=>'2','order_number'=>'2','status'=>'2']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_recharge_list}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
