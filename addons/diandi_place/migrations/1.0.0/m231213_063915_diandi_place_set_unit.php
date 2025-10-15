<?php
use yii\db\Migration;
class m231213_063915_diandi_place_set_unit extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_set_unit}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL COMMENT '公司ID'",
            'store_id' => "int(11) NULL DEFAULT '0' COMMENT '商户ID'",
            'title' => "varchar(255) NULL DEFAULT '' COMMENT '单元编号'",
            'type' => "int(11) NOT NULL COMMENT '房源类型（酒店，公寓，名宿，茶室）'",
            'lease_type' => "int(11) NULL COMMENT '承租类型'",
            'time_type' => "int(11) NULL COMMENT '租期'",
            'time_length' => "int(11) NULL COMMENT '租约类型'",
            'hotel_id' => "int(11) NULL DEFAULT '0' COMMENT '楼栋ID'",
            'tier_id' => "int(11) NULL COMMENT '楼层ID'",
            'create_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00'",
            'update_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='单元'");
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_set_unit}}','store_id',0);
        /* 表数据 */
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_set_unit}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
