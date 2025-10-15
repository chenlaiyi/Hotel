<?php
use yii\db\Migration;
class m231213_063915_diandi_place_type extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_type}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'title' => "varchar(50) NULL",
            'template_type' => "int(11) NULL",
            'bloc_id' => "int(11) NULL",
            'store_id' => "int(11) NULL",
            'create_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00'",
            'update_time' => "datetime NULL DEFAULT '0000-00-00 00:00:00'",
            'is_default' => "tinyint(1) NULL DEFAULT '0' COMMENT '是否默认 0|1'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT");
        /* 索引设置 */
        /* 表数据 */
        $this->insert('{{%diandi_place_type}}',['id'=>'15','title'=>'酒店','template_type'=>'1','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-06-21 09:05:43','update_time'=>'2023-07-03 09:21:31','is_default'=>'1']);
        $this->insert('{{%diandi_place_type}}',['id'=>'18','title'=>'民宿','template_type'=>'1','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-06-21 13:27:26','update_time'=>'2023-06-29 15:06:43','is_default'=>'0']);
        $this->insert('{{%diandi_place_type}}',['id'=>'22','title'=>'公寓','template_type'=>'2','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-06-27 09:50:04','update_time'=>'2023-06-28 14:59:27','is_default'=>'0']);
        $this->insert('{{%diandi_place_type}}',['id'=>'23','title'=>'茶室','template_type'=>'4','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-07-03 15:43:56','update_time'=>'2023-07-03 15:43:56','is_default'=>'0']);
        $this->insert('{{%diandi_place_type}}',['id'=>'24','title'=>'棋牌室','template_type'=>'1','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-07-08 23:59:13','update_time'=>'2023-07-08 23:59:13','is_default'=>'0']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_type}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
