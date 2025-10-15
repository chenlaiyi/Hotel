<?php
use yii\db\Migration;
class m240108_234139_diandi_place_recharge extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_recharge}}', [
            'id' => "int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '充值活动列表id'",
            'bloc_id' => "int(11) NULL COMMENT '人脸库组id'",
            'store_id' => "int(11) NULL",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'price' => "decimal(10,2) NULL COMMENT '价格'",
            'give_money' => "decimal(10,2) NULL COMMENT '赠送金额'",
            'give_coupon_ids' => "varchar(100) NULL COMMENT '赠送卡券id集合'",
            'type' => "smallint(2) NULL COMMENT '是否为活动套餐：1.是 2否'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='充值套餐表'");
        /* 索引设置 */
        /* 表数据 */
        $this->insert('{{%diandi_place_recharge}}',['id'=>'1','bloc_id'=>'0','store_id'=>'0','create_time'=>'0000-00-00 00:00:00','update_time'=>'0000-00-00 00:00:00','price'=>'3.00','give_money'=>'3.00','give_coupon_ids'=>'3','type'=>'1']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_recharge}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
