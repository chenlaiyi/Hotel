<?php
use yii\db\Migration;
class m240108_234139_diandi_place_type_list extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_type_list}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'place_type_id' => "int(11) NULL COMMENT '业务类型'",
            'title' => "varchar(50) NULL COMMENT '房型名称'",
            'bloc_id' => "int(11) NULL",
            'store_id' => "int(11) NULL",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='全局房型'");
        /* 索引设置 */
        /* 表数据 */
        $this->insert('{{%diandi_place_type_list}}',['id'=>'15','place_type_id'=>'15','title'=>'双床房','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-06-21 09:05:43','update_time'=>'2023-07-03 09:21:31']);
        $this->insert('{{%diandi_place_type_list}}',['id'=>'18','place_type_id'=>'15','title'=>'大床房','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-06-21 13:27:26','update_time'=>'2023-06-29 15:06:43']);
        $this->insert('{{%diandi_place_type_list}}',['id'=>'22','place_type_id'=>'15','title'=>'三床房','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-06-27 09:50:04','update_time'=>'2023-06-28 14:59:27']);
        $this->insert('{{%diandi_place_type_list}}',['id'=>'23','place_type_id'=>'15','title'=>'多床房','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-07-03 15:43:56','update_time'=>'2023-07-03 15:43:56']);
        $this->insert('{{%diandi_place_type_list}}',['id'=>'24','place_type_id'=>'15','title'=>'单人床房','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-07-08 23:59:13','update_time'=>'2023-07-08 23:59:13']);
        $this->insert('{{%diandi_place_type_list}}',['id'=>'25','place_type_id'=>'15','title'=>'特大床房','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-07-08 23:59:13','update_time'=>'2023-07-08 23:59:13']);
        $this->insert('{{%diandi_place_type_list}}',['id'=>'26','place_type_id'=>'24','title'=>'豪包','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-07-08 23:59:13','update_time'=>'2023-07-08 23:59:13']);
        $this->insert('{{%diandi_place_type_list}}',['id'=>'27','place_type_id'=>'24','title'=>'中包','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-07-08 23:59:13','update_time'=>'2023-07-08 23:59:13']);
        $this->insert('{{%diandi_place_type_list}}',['id'=>'28','place_type_id'=>'24','title'=>'大包','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-07-08 23:59:13','update_time'=>'2023-07-08 23:59:13']);
        $this->insert('{{%diandi_place_type_list}}',['id'=>'29','place_type_id'=>'24','title'=>'普包','bloc_id'=>'91','store_id'=>'153','create_time'=>'2023-07-08 23:59:13','update_time'=>'2023-07-08 23:59:13']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_type_list}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
