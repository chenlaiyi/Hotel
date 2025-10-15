<?php
use yii\db\Migration;
class m240108_234139_diandi_place_set_tier extends Migration
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
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='楼层'");
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_set_tier}}','store_id',0);
        /* 表数据 */
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'218','bloc_id'=>'91','store_id'=>'2','title'=>'6层','prefix'=>'001','type_id'=>'23','hotel_id'=>'106','create_time'=>'2023-11-19 14:03:34','update_time'=>'2023-11-19 14:03:34']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'219','bloc_id'=>'38','store_id'=>'2','title'=>'1','prefix'=>'1','type_id'=>'23','hotel_id'=>'106','create_time'=>'2023-12-14 14:06:27','update_time'=>'2023-12-14 14:06:27']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'220','bloc_id'=>'93','store_id'=>'3','title'=>'9','prefix'=>'06','type_id'=>'24','hotel_id'=>'108','create_time'=>'2023-12-15 10:09:19','update_time'=>'2023-12-15 10:09:19']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'221','bloc_id'=>'91','store_id'=>'153','title'=>'1','prefix'=>'','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:41:45','update_time'=>'2023-12-25 15:41:45']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'222','bloc_id'=>'91','store_id'=>'153','title'=>'2','prefix'=>'','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:41:45','update_time'=>'2023-12-25 15:41:45']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'223','bloc_id'=>'91','store_id'=>'153','title'=>'3','prefix'=>'','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:41:45','update_time'=>'2023-12-25 15:41:45']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'224','bloc_id'=>'91','store_id'=>'153','title'=>'1-1','prefix'=>'1','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:41:48','update_time'=>'2023-12-25 15:41:48']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'225','bloc_id'=>'91','store_id'=>'153','title'=>'1-2','prefix'=>'1','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:41:48','update_time'=>'2023-12-25 15:41:48']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'226','bloc_id'=>'91','store_id'=>'153','title'=>'1-3','prefix'=>'1','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:41:48','update_time'=>'2023-12-25 15:41:48']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'227','bloc_id'=>'91','store_id'=>'153','title'=>'1-1','prefix'=>'1','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:42:15','update_time'=>'2023-12-25 15:42:15']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'228','bloc_id'=>'91','store_id'=>'153','title'=>'1-2','prefix'=>'1','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:42:15','update_time'=>'2023-12-25 15:42:15']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'229','bloc_id'=>'91','store_id'=>'153','title'=>'1-3','prefix'=>'1','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:42:15','update_time'=>'2023-12-25 15:42:15']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'230','bloc_id'=>'91','store_id'=>'153','title'=>'1-1','prefix'=>'1','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:42:18','update_time'=>'2023-12-25 15:42:18']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'231','bloc_id'=>'91','store_id'=>'153','title'=>'1-2','prefix'=>'1','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:42:18','update_time'=>'2023-12-25 15:42:18']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'232','bloc_id'=>'91','store_id'=>'153','title'=>'1-3','prefix'=>'1','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:42:18','update_time'=>'2023-12-25 15:42:18']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'233','bloc_id'=>'91','store_id'=>'153','title'=>'1-1','prefix'=>'1','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:44:54','update_time'=>'2023-12-25 15:44:54']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'234','bloc_id'=>'91','store_id'=>'153','title'=>'1-2','prefix'=>'1','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:44:54','update_time'=>'2023-12-25 15:44:54']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'235','bloc_id'=>'91','store_id'=>'153','title'=>'1-3','prefix'=>'1','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:44:54','update_time'=>'2023-12-25 15:44:54']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'236','bloc_id'=>'91','store_id'=>'153','title'=>'1-1','prefix'=>'1','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:50:20','update_time'=>'2023-12-25 15:50:20']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'237','bloc_id'=>'91','store_id'=>'153','title'=>'1-2','prefix'=>'1','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:50:20','update_time'=>'2023-12-25 15:50:20']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'238','bloc_id'=>'91','store_id'=>'153','title'=>'1-3','prefix'=>'1','type_id'=>'23','hotel_id'=>'109','create_time'=>'2023-12-25 15:50:20','update_time'=>'2023-12-25 15:50:20']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'239','bloc_id'=>'91','store_id'=>'153','title'=>'100','prefix'=>'','type_id'=>'23','hotel_id'=>'110','create_time'=>'2023-12-28 17:05:31','update_time'=>'2023-12-28 17:05:31']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'240','bloc_id'=>'91','store_id'=>'153','title'=>'22','prefix'=>'','type_id'=>'23','hotel_id'=>'110','create_time'=>'2023-12-28 17:05:57','update_time'=>'2023-12-28 17:05:57']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'241','bloc_id'=>'91','store_id'=>'153','title'=>'001','prefix'=>'','type_id'=>'15','hotel_id'=>'112','create_time'=>'2023-12-29 12:09:22','update_time'=>'2023-12-29 12:09:22']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'242','bloc_id'=>'91','store_id'=>'153','title'=>'1','prefix'=>'','type_id'=>'15','hotel_id'=>'112','create_time'=>'2023-12-29 12:29:23','update_time'=>'2023-12-29 12:29:23']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'243','bloc_id'=>'91','store_id'=>'153','title'=>'1','prefix'=>'','type_id'=>'24','hotel_id'=>'115','create_time'=>'2023-12-29 13:01:45','update_time'=>'2023-12-29 13:01:45']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'244','bloc_id'=>'91','store_id'=>'153','title'=>'1','prefix'=>'','type_id'=>'18','hotel_id'=>'111','create_time'=>'2023-12-29 16:36:34','update_time'=>'2023-12-29 16:36:34']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'245','bloc_id'=>'91','store_id'=>'153','title'=>'2','prefix'=>'','type_id'=>'18','hotel_id'=>'111','create_time'=>'2023-12-29 16:36:38','update_time'=>'2023-12-29 16:36:38']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'246','bloc_id'=>'91','store_id'=>'153','title'=>'1','prefix'=>'','type_id'=>'18','hotel_id'=>'125','create_time'=>'2023-12-29 16:36:43','update_time'=>'2023-12-29 16:36:43']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'247','bloc_id'=>'91','store_id'=>'153','title'=>'1','prefix'=>'','type_id'=>'22','hotel_id'=>'116','create_time'=>'2023-12-29 17:14:51','update_time'=>'2023-12-29 17:14:51']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'248','bloc_id'=>'91','store_id'=>'153','title'=>'2','prefix'=>'','type_id'=>'22','hotel_id'=>'116','create_time'=>'2023-12-29 17:21:58','update_time'=>'2023-12-29 17:21:58']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'249','bloc_id'=>'91','store_id'=>'153','title'=>'3','prefix'=>'','type_id'=>'23','hotel_id'=>'110','create_time'=>'2023-12-30 00:39:00','update_time'=>'2023-12-30 00:39:00']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'250','bloc_id'=>'91','store_id'=>'153','title'=>'3','prefix'=>'','type_id'=>'22','hotel_id'=>'116','create_time'=>'2023-12-30 00:40:30','update_time'=>'2023-12-30 00:40:30']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'251','bloc_id'=>'91','store_id'=>'153','title'=>'1','prefix'=>'','type_id'=>'22','hotel_id'=>'129','create_time'=>'2023-12-30 01:52:45','update_time'=>'2023-12-30 01:52:45']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'252','bloc_id'=>'91','store_id'=>'153','title'=>'1','prefix'=>'','type_id'=>'22','hotel_id'=>'129','create_time'=>'2023-12-30 01:53:02','update_time'=>'2023-12-30 01:53:02']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'253','bloc_id'=>'91','store_id'=>'153','title'=>'1','prefix'=>'','type_id'=>'22','hotel_id'=>'129','create_time'=>'2023-12-30 01:53:09','update_time'=>'2023-12-30 01:53:09']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'254','bloc_id'=>'91','store_id'=>'153','title'=>'1','prefix'=>'','type_id'=>'22','hotel_id'=>'132','create_time'=>'2023-12-30 01:58:16','update_time'=>'2023-12-30 01:58:16']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'255','bloc_id'=>'91','store_id'=>'153','title'=>'1','prefix'=>'','type_id'=>'22','hotel_id'=>'133','create_time'=>'2024-01-01 13:56:28','update_time'=>'2024-01-01 13:56:28']);
        $this->insert('{{%diandi_place_set_tier}}',['id'=>'256','bloc_id'=>'91','store_id'=>'153','title'=>'2','prefix'=>'','type_id'=>'22','hotel_id'=>'133','create_time'=>'2024-01-01 13:58:25','update_time'=>'2024-01-01 13:58:25']);
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
