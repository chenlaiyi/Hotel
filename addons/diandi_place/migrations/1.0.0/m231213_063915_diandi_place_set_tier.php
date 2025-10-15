<?php
use yii\db\Migration;
class m231213_063915_diandi_place_set_tier extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_set_tier}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL COMMENT '公司ID'",
            'store_id' => "int(11) NULL DEFAULT '0' COMMENT '商户ID'",
            'title' => "varchar(255) NULL DEFAULT '' COMMENT '楼层编号'",
            'prefix' => "varchar(50) NULL COMMENT '楼层前缀'",
            'type_id' => "int(11) NOT NULL COMMENT '房源类型（酒店，公寓，名宿，茶室）'",
            'hotel_id' => "int(11) NULL DEFAULT '0' COMMENT '楼栋ID'",
            'create_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00'",
            'update_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='楼层'");
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_set_tier}}','store_id',0);
        /* 表数据 */
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'218','bloc_id'=>'91','store_id'=>'2','title'=>'6层','prefix'=>'001','type_id'=>'23','hotel_id'=>'106','create_time'=>'2023-11-19 14:03:34','update_time'=>'2023-11-19 14:03:34']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_set_tier}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
