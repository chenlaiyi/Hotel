<?php
use yii\db\Migration;
class m231213_063915_diandi_place_room_slide extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_room_slide}}', [
            'id' => "int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '人脸招聘'",
            'bloc_id' => "int(11) NOT NULL COMMENT '人脸库组id'",
            'store_id' => "int(11) NOT NULL",
            'create_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00'",
            'update_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00'",
            'slide' => "varchar(255) NULL COMMENT '轮播图'",
            'room_id' => "int(11) NULL",
            'title' => "varchar(50) NULL COMMENT '幻灯片标题'",
            'type' => "int(11) NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='全局配置表'");
        /* 索引设置 */
        /* 表数据 */
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_room_slide}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
