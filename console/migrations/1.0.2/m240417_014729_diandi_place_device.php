<?php

use yii\db\Migration;

class m240417_014729_diandi_place_device extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_place_device}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'title' => "varchar(50) NULL COMMENT '名称'",
            'mac' => "varchar(50) NULL COMMENT 'mac标识'",
            'cate_id' => "int(11) NULL COMMENT '子集ID'",
            'cate_pid' => "int(11) NULL COMMENT '父级分类ID'",
            'project_id' => "int(11) NULL COMMENT '项目ID'",
            'displayorder' => "int(11) NULL COMMENT '排序'",
            'type_id' => "int(11) NULL COMMENT '房源类型'",
            'hotel_id' => "int(11) NULL COMMENT '楼栋ID'",
            'tier_id' => "int(11) NULL COMMENT '楼层'",
            'room_id' => "int(11) NULL COMMENT '房间ID'",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_device}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

