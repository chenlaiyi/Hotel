<?php

use yii\db\Migration;

class m240417_014729_diandi_place_rim extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_place_rim}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL COMMENT '公司ID'",
            'store_id' => "int(11) NULL COMMENT '商户ID'",
            'hotel_id' => "int(11) NULL COMMENT '酒店ID'",
            'location_p' => "int(11) NULL COMMENT '省'",
            'location_c' => "int(11) NULL COMMENT '市'",
            'location_a' => "int(11) NULL COMMENT '区'",
            'room_id' => "int(11) NULL COMMENT '房间ID'",
            'room_num' => "int(11) NULL COMMENT '房间数量'",
            'rim_type' => "int(11) NULL COMMENT '周边类型'",
            'is_hot' => "int(11) NULL COMMENT '是否热门'",
            'title' => "varchar(100) NULL COMMENT '周边名称'",
            'thumb' => "varchar(255) NULL COMMENT '周边图片'",
            'thumbs' => "varchar(255) NULL COMMENT '周边相册'",
            'desc' => "varchar(255) NULL COMMENT '周边简介'",
            'content' => "text NULL COMMENT '周边详情'",
            'level_star' => "decimal(11,2) NULL COMMENT '星级'",
            'comment_num' => "int(11) NULL COMMENT '评价数量'",
            'lng' => "decimal(11,4) NULL",
            'lat' => "decimal(11,4) NULL",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='酒店周边'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%diandi_place_rim}}',['id'=>'7','bloc_id'=>'51','store_id'=>'149','hotel_id'=>'1','location_p'=>'801','location_c'=>'802','location_a'=>'813','room_id'=>'0','room_num'=>'10','rim_type'=>'1','is_hot'=>'2','title'=>'周边名称1','thumb'=>'202310/07/07db9ce9-747a-3676-84ba-1d22c6f0cc22.png','thumbs'=>'a:1:{i:0;s:50:"202310/07/30d8720d-ebfa-3a95-b318-cbeb73e11e51.png";}','desc'=>'2','content'=>'2','level_star'=>NULL,'comment_num'=>NULL,'lng'=>NULL,'lat'=>NULL,'create_time'=>'2024-03-21 12:07:27','update_time'=>'2023-10-07 23:11:06']);
        $this->insert('{{%diandi_place_rim}}',['id'=>'8','bloc_id'=>'51','store_id'=>'149','hotel_id'=>'1','location_p'=>'801','location_c'=>'802','location_a'=>'813','room_id'=>'0','room_num'=>'10','rim_type'=>'2','is_hot'=>'2','title'=>'周边名称2','thumb'=>'202310/07/061af934-7711-319e-a0b6-ce349d47f063.png','thumbs'=>'a:1:{i:0;s:50:"202310/07/94c0f22b-c347-3361-ba3e-6fd92273c0da.png";}','desc'=>'2','content'=>'2','level_star'=>NULL,'comment_num'=>NULL,'lng'=>NULL,'lat'=>NULL,'create_time'=>'2024-03-21 12:07:27','update_time'=>'2023-10-07 23:11:26']);
        $this->insert('{{%diandi_place_rim}}',['id'=>'9','bloc_id'=>'51','store_id'=>'149','hotel_id'=>'1','location_p'=>'2670','location_c'=>'2741','location_a'=>'2743','room_id'=>'0','room_num'=>'10','rim_type'=>'3','is_hot'=>'2','title'=>'周边名称3','thumb'=>'202310/07/038889fb-da1f-3136-ad97-782eaef0732f.png','thumbs'=>'a:1:{i:0;s:50:"202310/07/ef696930-4834-3969-a372-6c8b1d8a2733.png";}','desc'=>'2','content'=>'2','level_star'=>NULL,'comment_num'=>NULL,'lng'=>NULL,'lat'=>NULL,'create_time'=>'2024-03-21 12:07:27','update_time'=>'2023-10-07 23:11:44']);
        $this->insert('{{%diandi_place_rim}}',['id'=>'10','bloc_id'=>'51','store_id'=>'149','hotel_id'=>'1','location_p'=>'351','location_c'=>'442','location_a'=>'446','room_id'=>'0','room_num'=>'10','rim_type'=>'4','is_hot'=>'2','title'=>'周边名称4','thumb'=>'202310/07/d3602e15-ac5d-3637-8928-351a2500d16a.png','thumbs'=>'a:1:{i:0;s:50:"202310/07/c5b60d7b-4853-3cb8-b14a-d9385c92509c.png";}','desc'=>'2','content'=>'2','level_star'=>NULL,'comment_num'=>NULL,'lng'=>NULL,'lat'=>NULL,'create_time'=>'2024-03-21 12:07:27','update_time'=>'2023-10-07 23:12:06']);
        $this->insert('{{%diandi_place_rim}}',['id'=>'11','bloc_id'=>'51','store_id'=>'149','hotel_id'=>'1','location_p'=>'351','location_c'=>'442','location_a'=>'447','room_id'=>'0','room_num'=>'10','rim_type'=>'5','is_hot'=>'2','title'=>'周边名称','thumb'=>'202310/07/64a9630e-c61a-39bf-bf87-229c6c7505a3.png','thumbs'=>'a:1:{i:0;s:50:"202310/07/1eb94a3a-4f0f-3d88-9ac7-866e91e26022.png";}','desc'=>'2','content'=>'2','level_star'=>NULL,'comment_num'=>NULL,'lng'=>NULL,'lat'=>NULL,'create_time'=>'2024-03-21 12:07:27','update_time'=>'2023-10-07 23:12:25']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_rim}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

