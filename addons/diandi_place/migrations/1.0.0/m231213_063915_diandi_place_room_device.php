<?php
use yii\db\Migration;
class m231213_063915_diandi_place_room_device extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_room_device}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL",
            'store_id' => "int(11) NULL",
            'type_id' => "int(11) NULL COMMENT '设备细分类型'",
            'hotel_type' => "int(11) NULL COMMENT '楼栋类型'",
            'hotel_id' => "int(11) NULL COMMENT '楼栋ID'",
            'tier_id' => "int(11) NULL COMMENT '楼层'",
            'room_id' => "int(11) NULL COMMENT '房间ID'",
            'title' => "varchar(50) NULL COMMENT '名称'",
            'project_id' => "int(11) NULL COMMENT '项目ID'",
            'mac' => "varchar(50) NULL COMMENT 'mac标识'",
            'device_id' => "varchar(255) NULL COMMENT '设备编号'",
            'lock_type' => "tinyint(11) NULL COMMENT '门锁类型'",
            'device_type' => "tinyint(1) NULL COMMENT '设备类型'",
            'manufactor_id' => "int(11) NULL COMMENT '厂家'",
            'displayorder' => "int(11) NULL COMMENT '排序'",
            'device_status' => "text NULL COMMENT '设备实时状态'",
            'create_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00'",
            'update_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00'",
            'status' => "tinyint(4) NULL COMMENT '状态 1已绑定 2未绑定'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='房间设备'");
        /* 索引设置 */
        $this->createIndex('project_id','{{%diandi_place_room_device}}','project_id',0);
        /* 表数据 */
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_room_device}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
