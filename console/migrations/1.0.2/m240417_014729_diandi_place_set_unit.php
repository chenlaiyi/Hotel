<?php

use yii\db\Migration;

class m240417_014729_diandi_place_set_unit extends Migration
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
            'type_id' => "int(11) NOT NULL COMMENT '房源类型（酒店，公寓，名宿，茶室）'",
            'lease_type' => "int(11) NULL COMMENT '承租类型'",
            'time_type' => "int(11) NULL COMMENT '租期'",
            'time_length' => "int(11) NULL COMMENT '租约类型'",
            'hotel_id' => "int(11) NULL DEFAULT '0' COMMENT '楼栋ID'",
            'tier_id' => "int(11) NULL COMMENT '楼层ID'",
            'room_num' => "int(11) NULL COMMENT '房间数量'",
            'toilet_num' => "int(11) NULL COMMENT '卫生间数量'",
            'area' => "decimal(11,2) NULL COMMENT '面积'",
            'status' => "int(11) NULL",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='单元'");
        
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_set_unit}}','store_id',0);
        
        
        /* 表数据 */
        $this->insert('{{%diandi_place_set_unit}}',['id'=>'1','bloc_id'=>'98','store_id'=>'296','title'=>'4334','type_id'=>'2','lease_type'=>'230','time_type'=>NULL,'time_length'=>'0','hotel_id'=>'0','tier_id'=>'113','room_num'=>'1','toilet_num'=>'1','area'=>NULL,'status'=>NULL,'create_time'=>'2024-02-21 11:24:58','update_time'=>'2024-02-21 11:24:58']);
        $this->insert('{{%diandi_place_set_unit}}',['id'=>'2','bloc_id'=>'98','store_id'=>'296','title'=>'102','type_id'=>'2','lease_type'=>NULL,'time_type'=>'0','time_length'=>'0','hotel_id'=>'113','tier_id'=>'230','room_num'=>'1','toilet_num'=>'1','area'=>'0.00','status'=>NULL,'create_time'=>'2024-02-21 11:36:03','update_time'=>'2024-02-21 11:36:03']);
        $this->insert('{{%diandi_place_set_unit}}',['id'=>'3','bloc_id'=>'98','store_id'=>'296','title'=>'103','type_id'=>'2','lease_type'=>NULL,'time_type'=>'0','time_length'=>'0','hotel_id'=>'113','tier_id'=>'230','room_num'=>'1','toilet_num'=>'1','area'=>'0.00','status'=>NULL,'create_time'=>'2024-02-21 11:39:27','update_time'=>'2024-02-21 11:39:27']);
        $this->insert('{{%diandi_place_set_unit}}',['id'=>'4','bloc_id'=>'98','store_id'=>'296','title'=>'单元2','type_id'=>'2','lease_type'=>NULL,'time_type'=>'0','time_length'=>'0','hotel_id'=>'113','tier_id'=>'230','room_num'=>'1','toilet_num'=>'1','area'=>'12.00','status'=>'1','create_time'=>'2024-02-21 12:10:14','update_time'=>'2024-02-21 12:10:14']);
        $this->insert('{{%diandi_place_set_unit}}',['id'=>'5','bloc_id'=>'98','store_id'=>'296','title'=>'公寓单元','type_id'=>'3','lease_type'=>'0','time_type'=>'0','time_length'=>'0','hotel_id'=>'111','tier_id'=>'221','room_num'=>'1','toilet_num'=>'1','area'=>'0.00','status'=>'1','create_time'=>'2024-02-21 12:19:39','update_time'=>'2024-02-21 12:19:39']);
        
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

