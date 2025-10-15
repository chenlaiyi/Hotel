<?php
use yii\db\Migration;
class m240108_234139_diandi_place_set_unit extends Migration
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
            'status' => "int(11) NULL DEFAULT '1' COMMENT '入住状态'",
            'room_num' => "int(11) NULL COMMENT '几室'",
            'toilet_num' => "int(11) NULL COMMENT '几卫'",
            'area' => "decimal(11,2) NULL COMMENT '面积'",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='单元'");
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_set_unit}}','store_id',0);
        /* 表数据 */
        $this->insert('{{%diandi_place_set_unit}}',['id'=>'3','bloc_id'=>'91','store_id'=>'153','title'=>'单位名称','type'=>'22','lease_type'=>'0','time_type'=>'0','time_length'=>'0','hotel_id'=>'129','tier_id'=>'0','status'=>'1','room_num'=>NULL,'toilet_num'=>NULL,'area'=>NULL,'create_time'=>'2024-01-01 11:32:38','update_time'=>'2024-01-01 11:32:38']);
        $this->insert('{{%diandi_place_set_unit}}',['id'=>'4','bloc_id'=>'91','store_id'=>'153','title'=>'测试单位','type'=>'22','lease_type'=>'0','time_type'=>'0','time_length'=>'0','hotel_id'=>'124','tier_id'=>'0','status'=>'1','room_num'=>NULL,'toilet_num'=>NULL,'area'=>NULL,'create_time'=>'2024-01-01 12:02:19','update_time'=>'2024-01-01 12:02:19']);
        $this->insert('{{%diandi_place_set_unit}}',['id'=>'5','bloc_id'=>'91','store_id'=>'153','title'=>'合租单位1','type'=>'22','lease_type'=>'0','time_type'=>'0','time_length'=>'0','hotel_id'=>'129','tier_id'=>'0','status'=>'1','room_num'=>NULL,'toilet_num'=>NULL,'area'=>NULL,'create_time'=>'2024-01-01 12:19:57','update_time'=>'2024-01-01 12:19:57']);
        $this->insert('{{%diandi_place_set_unit}}',['id'=>'6','bloc_id'=>'91','store_id'=>'153','title'=>'合租001','type'=>'22','lease_type'=>'0','time_type'=>'0','time_length'=>'0','hotel_id'=>'129','tier_id'=>'251','status'=>'1','room_num'=>NULL,'toilet_num'=>NULL,'area'=>NULL,'create_time'=>'2024-01-01 12:25:37','update_time'=>'2024-01-01 12:25:37']);
        $this->insert('{{%diandi_place_set_unit}}',['id'=>'7','bloc_id'=>'91','store_id'=>'153','title'=>'合租002','type'=>'22','lease_type'=>'0','time_type'=>'0','time_length'=>'0','hotel_id'=>'129','tier_id'=>'251','status'=>'1','room_num'=>NULL,'toilet_num'=>NULL,'area'=>NULL,'create_time'=>'2024-01-01 13:56:05','update_time'=>'2024-01-01 13:56:05']);
        $this->insert('{{%diandi_place_set_unit}}',['id'=>'8','bloc_id'=>'91','store_id'=>'153','title'=>'合租-1','type'=>'22','lease_type'=>'1','time_type'=>'0','time_length'=>'0','hotel_id'=>'133','tier_id'=>'255','status'=>'1','room_num'=>NULL,'toilet_num'=>NULL,'area'=>NULL,'create_time'=>'2024-01-01 13:58:54','update_time'=>'2024-01-01 13:58:54']);
        $this->insert('{{%diandi_place_set_unit}}',['id'=>'9','bloc_id'=>'91','store_id'=>'153','title'=>'d01','type'=>'22','lease_type'=>'1','time_type'=>'0','time_length'=>'0','hotel_id'=>'133','tier_id'=>'255','status'=>'1','room_num'=>NULL,'toilet_num'=>NULL,'area'=>NULL,'create_time'=>'2024-01-01 17:17:06','update_time'=>'2024-01-01 17:17:06']);
        $this->insert('{{%diandi_place_set_unit}}',['id'=>'10','bloc_id'=>'91','store_id'=>'153','title'=>'d002','type'=>'22','lease_type'=>'1','time_type'=>'0','time_length'=>'0','hotel_id'=>'133','tier_id'=>'255','status'=>'1','room_num'=>NULL,'toilet_num'=>NULL,'area'=>NULL,'create_time'=>'2024-01-01 17:28:46','update_time'=>'2024-01-01 17:28:46']);
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
