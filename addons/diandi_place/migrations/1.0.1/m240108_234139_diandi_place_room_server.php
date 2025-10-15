<?php
use yii\db\Migration;
class m240108_234139_diandi_place_room_server extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_room_server}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL COMMENT '公司ID'",
            'store_id' => "int(11) NULL COMMENT '商户ID'",
            'hotel_id' => "int(11) NULL COMMENT '酒店ID'",
            'room_id' => "int(11) NULL COMMENT '房间ID'",
            'server_id' => "int(11) NULL",
            'room_type_id' => "int(11) NULL COMMENT '房型id'",
            'title' => "varchar(11) NULL COMMENT '服务名'",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='房间与服务关联表'");
        /* 索引设置 */
        /* 表数据 */
        $this->insert('{{%diandi_place_room_server}}',['id'=>'1','bloc_id'=>'91','store_id'=>'2','hotel_id'=>'106','room_id'=>'2','server_id'=>'38','room_type_id'=>NULL,'title'=>'美甲','create_time'=>'2023-11-19 14:13:55','update_time'=>'2023-11-19 14:13:55']);
        $this->insert('{{%diandi_place_room_server}}',['id'=>'2','bloc_id'=>'91','store_id'=>'2','hotel_id'=>'106','room_id'=>'1','server_id'=>'38','room_type_id'=>NULL,'title'=>'美甲','create_time'=>'2023-11-19 14:21:50','update_time'=>'2023-11-19 14:21:50']);
        $this->insert('{{%diandi_place_room_server}}',['id'=>'3','bloc_id'=>'91','store_id'=>'153','hotel_id'=>NULL,'room_id'=>'0','server_id'=>NULL,'room_type_id'=>'5','title'=>'美肤','create_time'=>'2023-12-30 02:00:57','update_time'=>'2023-12-30 02:00:57']);
        $this->insert('{{%diandi_place_room_server}}',['id'=>'4','bloc_id'=>'91','store_id'=>'153','hotel_id'=>NULL,'room_id'=>'0','server_id'=>NULL,'room_type_id'=>'5','title'=>'美甲','create_time'=>'2023-12-30 02:00:57','update_time'=>'2023-12-30 02:00:57']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_room_server}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
