<?php

use yii\db\Migration;

class m240417_014729_diandi_place_server extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_place_server}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL COMMENT '公司ID'",
            'store_id' => "int(11) NULL COMMENT '商户ID'",
            'hotel_id' => "int(11) NULL COMMENT '酒店ID'",
            'title' => "varchar(50) NULL COMMENT '服务名称'",
            'thumb' => "varchar(250) NULL COMMENT '图标'",
            'room_id' => "int(11) NULL COMMENT '房间ID'",
            'desc' => "varchar(250) NULL COMMENT '服务说明'",
            'displayorder' => "int(11) NULL COMMENT '排序'",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='酒店服务'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%diandi_place_server}}',['id'=>'38','bloc_id'=>'91','store_id'=>'153','hotel_id'=>'0','title'=>'美甲','thumb'=>NULL,'room_id'=>'1413','desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2023-10-13 09:10:25','update_time'=>'2023-10-13 09:10:25']);
        $this->insert('{{%diandi_place_server}}',['id'=>'37','bloc_id'=>'91','store_id'=>'153','hotel_id'=>'0','title'=>'美肤','thumb'=>NULL,'room_id'=>'1413','desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2023-10-13 09:10:25','update_time'=>'2023-10-13 09:10:25']);
        $this->insert('{{%diandi_place_server}}',['id'=>'36','bloc_id'=>'91','store_id'=>'153','hotel_id'=>'0','title'=>'美甲','thumb'=>NULL,'room_id'=>'1412','desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2023-10-13 09:09:02','update_time'=>'2023-10-13 09:09:02']);
        $this->insert('{{%diandi_place_server}}',['id'=>'34','bloc_id'=>'91','store_id'=>'153','hotel_id'=>'0','title'=>'美甲','thumb'=>NULL,'room_id'=>'1411','desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2023-10-13 09:08:30','update_time'=>'2023-10-13 09:08:30']);
        $this->insert('{{%diandi_place_server}}',['id'=>'35','bloc_id'=>'91','store_id'=>'153','hotel_id'=>'0','title'=>'美肤','thumb'=>NULL,'room_id'=>'1412','desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2023-10-13 09:09:02','update_time'=>'2023-10-13 09:09:02']);
        $this->insert('{{%diandi_place_server}}',['id'=>'33','bloc_id'=>'91','store_id'=>'153','hotel_id'=>'0','title'=>'美肤','thumb'=>NULL,'room_id'=>'1411','desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2023-10-13 09:08:30','update_time'=>'2023-10-13 09:08:30']);
        $this->insert('{{%diandi_place_server}}',['id'=>'32','bloc_id'=>'91','store_id'=>'153','hotel_id'=>'0','title'=>'美甲','thumb'=>NULL,'room_id'=>'1410','desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2023-10-13 09:07:54','update_time'=>'2023-10-13 09:07:54']);
        $this->insert('{{%diandi_place_server}}',['id'=>'31','bloc_id'=>'91','store_id'=>'153','hotel_id'=>'0','title'=>'美肤','thumb'=>NULL,'room_id'=>'1410','desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2023-10-13 09:07:54','update_time'=>'2023-10-13 09:07:54']);
        $this->insert('{{%diandi_place_server}}',['id'=>'30','bloc_id'=>'91','store_id'=>'153','hotel_id'=>'0','title'=>'美甲','thumb'=>NULL,'room_id'=>'1409','desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2023-10-13 09:07:05','update_time'=>'2023-10-13 09:07:05']);
        $this->insert('{{%diandi_place_server}}',['id'=>'29','bloc_id'=>'91','store_id'=>'153','hotel_id'=>'0','title'=>'美肤','thumb'=>NULL,'room_id'=>'1409','desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2023-10-13 09:07:05','update_time'=>'2023-10-13 09:07:05']);
        $this->insert('{{%diandi_place_server}}',['id'=>'28','bloc_id'=>'91','store_id'=>'153','hotel_id'=>'0','title'=>'美甲','thumb'=>NULL,'room_id'=>'1408','desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2023-10-13 09:06:12','update_time'=>'2023-10-13 09:06:12']);
        $this->insert('{{%diandi_place_server}}',['id'=>'26','bloc_id'=>'91','store_id'=>'153','hotel_id'=>'0','title'=>'美甲','thumb'=>NULL,'room_id'=>'1407','desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2023-10-13 09:06:06','update_time'=>'2023-10-13 09:06:06']);
        $this->insert('{{%diandi_place_server}}',['id'=>'27','bloc_id'=>'91','store_id'=>'153','hotel_id'=>'0','title'=>'美肤','thumb'=>NULL,'room_id'=>'1408','desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2023-10-13 09:06:12','update_time'=>'2023-10-13 09:06:12']);
        $this->insert('{{%diandi_place_server}}',['id'=>'25','bloc_id'=>'91','store_id'=>'153','hotel_id'=>'0','title'=>'美肤','thumb'=>NULL,'room_id'=>'1407','desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2023-10-13 09:06:06','update_time'=>'2023-10-13 09:06:06']);
        $this->insert('{{%diandi_place_server}}',['id'=>'23','bloc_id'=>'91','store_id'=>'153','hotel_id'=>'0','title'=>'美肤','thumb'=>NULL,'room_id'=>'1406','desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2023-10-13 09:03:56','update_time'=>'2023-10-13 09:03:56']);
        $this->insert('{{%diandi_place_server}}',['id'=>'24','bloc_id'=>'91','store_id'=>'153','hotel_id'=>'0','title'=>'美甲','thumb'=>NULL,'room_id'=>'1406','desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2023-10-13 09:03:56','update_time'=>'2023-10-13 09:03:56']);
        $this->insert('{{%diandi_place_server}}',['id'=>'22','bloc_id'=>'91','store_id'=>'0','hotel_id'=>'0','title'=>'美肤','thumb'=>'202310/13/9c159be3-3b9a-3d37-8f04-65f17877a3ac.png','room_id'=>NULL,'desc'=>'23','displayorder'=>'3','create_time'=>'2023-10-13 09:00:34','update_time'=>'2023-10-13 09:00:34']);
        $this->insert('{{%diandi_place_server}}',['id'=>'21','bloc_id'=>'91','store_id'=>'0','hotel_id'=>'0','title'=>'美甲','thumb'=>'202310/13/47fd707f-23fd-3461-9dab-43d238fd3e13.png','room_id'=>NULL,'desc'=>'1223','displayorder'=>'2','create_time'=>'2023-10-13 08:59:57','update_time'=>'2023-10-13 08:59:57']);
        $this->insert('{{%diandi_place_server}}',['id'=>'39','bloc_id'=>'111','store_id'=>'0','hotel_id'=>NULL,'title'=>'机场接送1','thumb'=>'202402/28/bd20de44-3afa-3ed0-8f32-fb8bb0e9f03c.jpg','room_id'=>NULL,'desc'=>'121','displayorder'=>'1','create_time'=>'2024-02-28 15:59:07','update_time'=>'2024-02-28 16:07:42']);
        $this->insert('{{%diandi_place_server}}',['id'=>'40','bloc_id'=>'111','store_id'=>'0','hotel_id'=>NULL,'title'=>'免费停车','thumb'=>'202402/28/9dadeaf1-3355-3ba4-8ec3-ebdf2a1e9b1d.jpg','room_id'=>NULL,'desc'=>'免费停车','displayorder'=>'1','create_time'=>'2024-02-28 16:08:09','update_time'=>'2024-02-28 16:08:09']);
        $this->insert('{{%diandi_place_server}}',['id'=>'41','bloc_id'=>'111','store_id'=>'0','hotel_id'=>NULL,'title'=>'WiFi畅联','thumb'=>'202402/28/88089ccc-5d5c-35b3-9de4-626851896914.jpg','room_id'=>NULL,'desc'=>'WiFi畅联','displayorder'=>'1','create_time'=>'2024-02-28 16:09:00','update_time'=>'2024-02-28 16:09:00']);
        $this->insert('{{%diandi_place_server}}',['id'=>'42','bloc_id'=>'144','store_id'=>'0','hotel_id'=>NULL,'title'=>'555','thumb'=>NULL,'room_id'=>NULL,'desc'=>NULL,'displayorder'=>NULL,'create_time'=>'2024-03-21 12:06:34','update_time'=>'2024-03-21 12:06:34']);
        $this->insert('{{%diandi_place_server}}',['id'=>'43','bloc_id'=>'185','store_id'=>'0','hotel_id'=>NULL,'title'=>'','thumb'=>NULL,'room_id'=>NULL,'desc'=>NULL,'displayorder'=>'1','create_time'=>'2024-03-27 15:06:38','update_time'=>'2024-03-27 15:06:38']);
        $this->insert('{{%diandi_place_server}}',['id'=>'44','bloc_id'=>'189','store_id'=>'310','hotel_id'=>NULL,'title'=>'厕所啊','thumb'=>NULL,'room_id'=>NULL,'desc'=>'厕所啊','displayorder'=>NULL,'create_time'=>'2024-03-30 16:20:13','update_time'=>'2024-03-30 16:20:13']);
        $this->insert('{{%diandi_place_server}}',['id'=>'45','bloc_id'=>'201','store_id'=>'0','hotel_id'=>NULL,'title'=>'送餐','thumb'=>'','room_id'=>NULL,'desc'=>'','displayorder'=>NULL,'create_time'=>'2024-04-11 08:54:01','update_time'=>'2024-04-11 08:54:12']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_server}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

