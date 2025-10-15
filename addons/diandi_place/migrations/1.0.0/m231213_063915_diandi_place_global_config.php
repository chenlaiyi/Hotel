<?php
use yii\db\Migration;
class m231213_063915_diandi_place_global_config extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_global_config}}', [
            'id' => "int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '人脸招聘'",
            'bloc_id' => "int(11) NULL COMMENT '人脸库组id'",
            'store_id' => "int(11) NULL",
            'create_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间'",
            'update_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间'",
            'mumber_scale' => "varchar(255) NOT NULL COMMENT '会员积分比例'",
            'vip_scale' => "varchar(255) NOT NULL COMMENT 'vip积分比例'",
            'store_introduce' => "text NULL COMMENT '商户简介'",
            'admin_ids' => "varchar(100) NULL COMMENT '管理员'",
            'sms_order_template' => "varchar(100) NULL COMMENT '短信订单通知模板'",
            'sms_order_sign' => "varchar(30) NULL COMMENT '短信订单通知签名'",
            'sms_mobiles' => "varchar(255) NULL COMMENT '短信通知手机号，逗号隔开'",
            'order_create_template' => "varchar(100) NULL COMMENT '订单下单小程序订阅模板'",
            'order_end_template' => "varchar(100) NULL COMMENT '订单到期小程序订阅模板'",
            'recharge_template' => "varchar(100) NULL COMMENT '充值成功通知模板'",
            'renew_template' => "varchar(100) NULL COMMENT '续费通知模板'",
            'index_thumb' => "varchar(255) NULL COMMENT '首页头部图片'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='全局配置表'");
        /* 索引设置 */
        /* 表数据 */
        $this->insert('{{%diandi_place_global_config}}',['id'=>'1','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-10-07 22:27:26','update_time'=>'2023-10-07 22:27:26','mumber_scale'=>'2','vip_scale'=>'2','store_introduce'=>'2','admin_ids'=>'2','sms_order_template'=>'2','sms_order_sign'=>'2','sms_mobiles'=>'2','order_create_template'=>'2','order_end_template'=>'2','recharge_template'=>'2','renew_template'=>'2','index_thumb'=>'202310/07/91501cf3-6bf8-3185-bc49-390dd7f95995.png']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_global_config}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
