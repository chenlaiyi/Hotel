<?php

use yii\db\Migration;

class m240417_014729_diandi_place_set_tier extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_place_set_tier}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL COMMENT '公司ID'",
            'store_id' => "int(11) NULL DEFAULT '0' COMMENT '商户ID'",
            'title' => "varchar(255) NULL DEFAULT '' COMMENT '楼层编号'",
            'prefix' => "varchar(50) NULL COMMENT '楼层前缀'",
            'type_id' => "int(11) NOT NULL COMMENT '房源类型（酒店，公寓，名宿，茶室）'",
            'hotel_id' => "int(11) NULL DEFAULT '0' COMMENT '楼栋ID'",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='楼层'");
        
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_set_tier}}','store_id',0);
        
        
        /* 表数据 */
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'218','bloc_id'=>'91','store_id'=>'2','title'=>'6层','prefix'=>'001','type_id'=>'23','hotel_id'=>'106','create_time'=>'2023-11-19 14:03:34','update_time'=>'2023-11-19 14:03:34']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'219','bloc_id'=>'91','store_id'=>'153','title'=>'1','prefix'=>'','type_id'=>'22','hotel_id'=>'107','create_time'=>'2024-01-15 17:17:12','update_time'=>'2024-01-15 17:17:12']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'220','bloc_id'=>'91','store_id'=>'153','title'=>'1','prefix'=>'','type_id'=>'15','hotel_id'=>'108','create_time'=>'2024-01-15 17:18:02','update_time'=>'2024-01-15 17:18:02']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'221','bloc_id'=>'98','store_id'=>'296','title'=>'1','prefix'=>'','type_id'=>'3','hotel_id'=>'111','create_time'=>'2024-02-01 22:28:25','update_time'=>'2024-02-01 22:28:25']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'222','bloc_id'=>'93','store_id'=>'2','title'=>'1','prefix'=>'a','type_id'=>'23','hotel_id'=>'106','create_time'=>'2024-02-02 01:18:02','update_time'=>'2024-02-02 13:09:37']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'223','bloc_id'=>'98','store_id'=>'297','title'=>'1','prefix'=>'nj','type_id'=>'1','hotel_id'=>'112','create_time'=>'2024-02-03 21:16:22','update_time'=>'2024-02-03 21:16:22']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'224','bloc_id'=>'98','store_id'=>'298','title'=>'1','prefix'=>'a','type_id'=>'3','hotel_id'=>'113','create_time'=>'2024-02-03 22:07:10','update_time'=>'2024-02-03 22:07:10']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'225','bloc_id'=>'106','store_id'=>'299','title'=>'1','prefix'=>'hb','type_id'=>'3','hotel_id'=>'114','create_time'=>'2024-02-10 19:18:25','update_time'=>'2024-02-10 19:18:25']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'226','bloc_id'=>'107','store_id'=>'300','title'=>'1','prefix'=>'a','type_id'=>'1','hotel_id'=>'115','create_time'=>'2024-02-16 16:20:02','update_time'=>'2024-02-16 16:20:02']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'227','bloc_id'=>'38','store_id'=>'302','title'=>'1','prefix'=>'h','type_id'=>'1','hotel_id'=>'116','create_time'=>'2024-02-16 17:32:26','update_time'=>'2024-02-16 17:32:26']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'228','bloc_id'=>'38','store_id'=>'303','title'=>'d','prefix'=>'01','type_id'=>'5','hotel_id'=>'117','create_time'=>'2024-02-19 09:01:11','update_time'=>'2024-02-19 09:01:11']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'229','bloc_id'=>'114','store_id'=>'303','title'=>'a','prefix'=>'1','type_id'=>'5','hotel_id'=>'117','create_time'=>'2024-02-19 09:59:49','update_time'=>'2024-02-19 09:59:49']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'230','bloc_id'=>'98','store_id'=>'296','title'=>'2','prefix'=>'','type_id'=>'2','hotel_id'=>'113','create_time'=>'2024-02-21 09:59:07','update_time'=>'2024-02-21 09:59:07']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'231','bloc_id'=>'98','store_id'=>'296','title'=>'3','prefix'=>'','type_id'=>'2','hotel_id'=>'113','create_time'=>'2024-02-21 11:01:21','update_time'=>'2024-02-21 11:01:21']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'232','bloc_id'=>'98','store_id'=>'296','title'=>'4','prefix'=>'b','type_id'=>'3','hotel_id'=>'111','create_time'=>'2024-02-21 12:18:57','update_time'=>'2024-02-21 12:18:57']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'233','bloc_id'=>'111','store_id'=>'306','title'=>'1','prefix'=>'1','type_id'=>'1','hotel_id'=>'120','create_time'=>'2024-02-28 15:52:46','update_time'=>'2024-02-28 15:52:46']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'234','bloc_id'=>'144','store_id'=>'309','title'=>'111','prefix'=>'d','type_id'=>'6','hotel_id'=>'122','create_time'=>'2024-03-21 12:07:47','update_time'=>'2024-03-21 12:16:31']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'235','bloc_id'=>'159','store_id'=>'308','title'=>'1','prefix'=>'001','type_id'=>'5','hotel_id'=>'124','create_time'=>'2024-03-28 09:06:42','update_time'=>'2024-03-28 09:06:42']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'236','bloc_id'=>'189','store_id'=>'310','title'=>'11','prefix'=>'222','type_id'=>'1','hotel_id'=>'125','create_time'=>'2024-03-30 16:20:00','update_time'=>'2024-03-30 16:20:00']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_set_tier}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

