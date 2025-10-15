<?php
use yii\db\Migration;
class m240108_234139_diandi_place_coupon extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_coupon}}', [
            'id' => "int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '卡券id'",
            'bloc_id' => "int(11) NULL COMMENT '公司ID'",
            'store_id' => "int(11) NULL COMMENT '商户ID'",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'name' => "varchar(100) NOT NULL COMMENT '卡券名称'",
            'explain' => "varchar(255) NULL COMMENT '卡券说明'",
            'type' => "smallint(6) NULL COMMENT '卡券类型  1：代金券 2：折扣券 '",
            'price' => "decimal(10,2) NULL COMMENT '卡券价格'",
            'use_start' => "datetime NULL COMMENT '时间限制-开始时间'",
            'use_end' => "datetime NULL COMMENT '时间限制-结束时间'",
            'enable_start' => "datetime NULL COMMENT '有效期开始时间'",
            'enable_end' => "datetime NULL COMMENT '有效期结束时间'",
            'inventory' => "int(11) NULL COMMENT '库存'",
            'use_num' => "smallint(6) NULL COMMENT '已使用数量'",
            'max_time' => "varchar(100) NULL COMMENT '消费时长'",
            'enable_store' => "varchar(100) NULL COMMENT '适用店铺'",
            'enable_week' => "varchar(255) NULL COMMENT '适用星期(分别对应1~7）'",
            'third_party' => "varchar(100) NULL COMMENT '第三方编号'",
            'all_num' => "int(11) NULL COMMENT '总发放量'",
            'max_num' => "int(11) NULL COMMENT '最多可购买数量'",
            'background' => "varchar(255) NULL COMMENT '卡券背景图'",
            'cash' => "decimal(10,2) NULL COMMENT '代金券金额'",
            'min_order_price' => "decimal(10,2) NULL COMMENT '使用最小限额'",
            'discount' => "float NULL COMMENT '折扣券折扣'",
            'coupon_img' => "varchar(255) NULL COMMENT '卡券图片'",
            'use_hourse' => "varchar(255) NULL COMMENT '使用房间'",
            'num_sort' => "int(11) unsigned NULL DEFAULT '0' COMMENT '排序'",
            'meal_type' => "int(11) NULL COMMENT '默认套餐类型'",
            'hotel_id' => "int(11) NULL COMMENT '酒店id'",
            'room_id' => "int(11) NULL COMMENT '房间/单位id'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='优惠券主表'");
        /* 索引设置 */
        /* 表数据 */
        $this->insert('{{%diandi_place_coupon}}',['id'=>'21','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-06-28 10:30:43','update_time'=>'2023-06-28 10:30:43','name'=>'12','explain'=>'12','type'=>'1','price'=>'12.00','use_start'=>'2023-06-28 10:30:40','use_end'=>'2023-06-30 00:00:00','enable_start'=>'2023-06-28 10:30:25','enable_end'=>'2023-06-30 00:00:00','inventory'=>NULL,'use_num'=>'12','max_time'=>'2023-06-28 10:30:33','enable_store'=>NULL,'enable_week'=>'6','third_party'=>'1221','all_num'=>'51','max_num'=>'12','background'=>'202306/28/911be52a-000d-3f40-8fd4-ac862e33c6f4.png','cash'=>'12.00','min_order_price'=>NULL,'discount'=>'2','coupon_img'=>'202306/28/28727d95-e6ef-3f2a-a18a-f78783a3ee6e.png','use_hourse'=>'0','num_sort'=>'115','meal_type'=>'1','hotel_id'=>NULL,'room_id'=>NULL]);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_coupon}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
