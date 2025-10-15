<?php
use yii\db\Migration;
class m240108_234139_diandi_place_room_status extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_room_status}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL DEFAULT '0' COMMENT '公司ID'",
            'store_id' => "int(11) NULL DEFAULT '0' COMMENT '商户ID'",
            'hotel_id' => "int(11) NULL DEFAULT '0' COMMENT '酒店ID'",
            'room_id' => "int(11) NULL DEFAULT '0' COMMENT '房间ID'",
            'roomdate' => "datetime NULL COMMENT '房价日期'",
            'thisdate' => "datetime NULL COMMENT '当天日期'",
            'room_num' => "int(11) NULL COMMENT '房量'",
            'room_status' => "int(11) NULL DEFAULT '1' COMMENT '房态'",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8");
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_room_status}}','store_id',0);
        $this->createIndex('indx_hotelid','{{%diandi_place_room_status}}','hotel_id',0);
        $this->createIndex('indx_roomid','{{%diandi_place_room_status}}','room_id',0);
        $this->createIndex('indx_roomdate','{{%diandi_place_room_status}}','roomdate',0);
        /* 表数据 */
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_room_status}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
