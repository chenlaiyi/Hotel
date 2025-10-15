<?php

use yii\db\Migration;

class m240417_014729_diandi_place_room_slide extends Migration
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
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'slide' => "text NULL COMMENT '轮播图'",
            'room_id' => "int(11) NULL",
            'title' => "varchar(50) NULL COMMENT '幻灯片标题'",
            'type' => "int(11) NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='全局配置表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%diandi_place_room_slide}}',['id'=>'1','bloc_id'=>'98','store_id'=>'296','create_time'=>'2024-02-05 15:28:07','update_time'=>'2024-02-05 15:30:44','slide'=>'a:5:{i:0;s:50:"202402/05/19997c7f-bc2d-3e88-b064-54c6cbc9d5c9.jpg";i:1;s:50:"202402/05/65ef7d2b-6b33-3628-89db-e9c20b587160.jpg";i:2;s:50:"202402/05/90b30a59-775c-34ae-b8b6-420bea3ad39e.jpg";i:3;s:50:"202402/05/cfc74014-add3-3d48-b536-9bcaa0de4e37.jpg";i:4;s:50:"202402/05/f520ab87-5361-3fb8-bef1-ec77f3d2d94a.jpg";}','room_id'=>NULL,'title'=>'大房间','type'=>'1']);
        $this->insert('{{%diandi_place_room_slide}}',['id'=>'2','bloc_id'=>'98','store_id'=>'296','create_time'=>'2024-02-05 15:39:11','update_time'=>'2024-02-05 15:39:11','slide'=>'a:5:{i:0;s:50:"202402/05/a8e89ead-0f69-3344-b6f5-05150337028a.jpg";i:1;s:50:"202402/05/5a157e4d-33cb-390c-bd68-64ac3ea19f99.jpg";i:2;s:50:"202402/05/6acaca29-ab16-332b-be50-24240a2d0697.jpg";i:3;s:50:"202402/05/7987507e-cffc-3292-8cfa-35ae2f209fb5.jpg";i:4;s:50:"202402/05/9650a83a-9956-3ee4-a04d-ac7b446ed751.jpg";}','room_id'=>NULL,'title'=>'客厅','type'=>'1']);
        
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

