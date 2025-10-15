<?php

use yii\db\Migration;

class m240417_014729_diandi_place_room_persions extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_place_room_persions}}', [
            'id' => "int(11) NOT NULL",
            'bloc_id' => "int(11) NULL COMMENT '公司ID'",
            'store_id' => "int(11) NULL COMMENT '商户ID'",
            'room_id' => "int(11) NULL COMMENT '房间ID'",
            'type' => "int(11) NULL COMMENT '人员类型0成人1儿童3客人'",
            'num' => "int(11) NULL COMMENT '容纳人数'",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=FIXED");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_room_persions}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

