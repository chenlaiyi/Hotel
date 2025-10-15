<?php
use yii\db\Migration;
class m240108_234139_diandi_place_set_room_time extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_set_room_time}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL COMMENT '公司ID'",
            'store_id' => "int(11) NULL DEFAULT '0' COMMENT '商户ID'",
            'roome_id' => "int(11) NULL DEFAULT '0' COMMENT '房间ID'",
            'type' => "int(11) NOT NULL COMMENT '房源类型（酒店，公寓，名宿，茶室）'",
            'time_length' => "int(11) NULL DEFAULT '0' COMMENT '租约时长（年，天，月，时）'",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='房间租约时长'");
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_set_room_time}}','store_id',0);
        /* 表数据 */
        $this->insert('{{%diandi_place_set_room_time}}',['id'=>'8','bloc_id'=>'0','store_id'=>'0','roome_id'=>'0','type'=>'0','time_length'=>NULL,'create_time'=>'0000-00-00 00:00:00','update_time'=>'0000-00-00 00:00:00']);
        $this->insert('{{%diandi_place_set_room_time}}',['id'=>'9','bloc_id'=>'51','store_id'=>'0','roome_id'=>'0','type'=>'10','time_length'=>NULL,'create_time'=>'0000-00-00 00:00:00','update_time'=>'0000-00-00 00:00:00']);
        $this->insert('{{%diandi_place_set_room_time}}',['id'=>'5','bloc_id'=>'0','store_id'=>'0','roome_id'=>'2','type'=>'0','time_length'=>NULL,'create_time'=>'0000-00-00 00:00:00','update_time'=>'0000-00-00 00:00:00']);
        $this->insert('{{%diandi_place_set_room_time}}',['id'=>'6','bloc_id'=>'0','store_id'=>'0','roome_id'=>'3','type'=>'0','time_length'=>NULL,'create_time'=>'0000-00-00 00:00:00','update_time'=>'0000-00-00 00:00:00']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_set_room_time}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
