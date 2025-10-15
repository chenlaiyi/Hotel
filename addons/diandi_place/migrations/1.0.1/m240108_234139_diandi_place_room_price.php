<?php
use yii\db\Migration;
class m240108_234139_diandi_place_room_price extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_room_price}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL DEFAULT '0' COMMENT '公司ID'",
            'store_id' => "int(11) NULL DEFAULT '0' COMMENT '商户ID'",
            'hotel_id' => "int(11) NULL DEFAULT '0' COMMENT '酒店ID'",
            'room_id' => "int(11) NULL DEFAULT '0' COMMENT '房间ID'",
            'room_date' => "int(11) NULL DEFAULT '0' COMMENT '房价日期'",
            'thisdate' => "varchar(255) NOT NULL DEFAULT '' COMMENT '当天日期'",
            'price' => "decimal(11,2) NULL COMMENT '房价'",
            'status' => "int(11) NULL DEFAULT '1' COMMENT '房间状态'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8");
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_room_price}}','store_id',0);
        $this->createIndex('indx_hotelid','{{%diandi_place_room_price}}','hotel_id',0);
        $this->createIndex('indx_roomid','{{%diandi_place_room_price}}','room_id',0);
        $this->createIndex('indx_roomdate','{{%diandi_place_room_price}}','room_date',0);
        /* 表数据 */
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_room_price}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
