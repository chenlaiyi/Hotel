<?php
use yii\db\Migration;
class m240108_234139_diandi_place_comment extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_comment}}', [
            'id' => "int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id'",
            'bloc_id' => "int(11) NULL COMMENT '公司ID'",
            'store_id' => "int(11) NULL COMMENT '商户ID'",
            'hotel_id' => "int(11) NULL COMMENT '酒店ID'",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'member_id' => "int(11) NULL COMMENT '会员id'",
            'order_id' => "int(11) NULL COMMENT '订单id'",
            'room_id' => "int(11) NULL COMMENT '房间ID'",
            'labels' => "varchar(100) NULL COMMENT '评价标签'",
            'comment' => "varchar(255) NULL COMMENT '评论内容'",
            'star_num' => "int(11) NULL COMMENT '综合星级'",
            'thumbs' => "varchar(255) NULL COMMENT '评价图片'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='评论表'");
        /* 索引设置 */
        /* 表数据 */
        $this->insert('{{%diandi_place_comment}}',['id'=>'1','bloc_id'=>'38','store_id'=>'138','hotel_id'=>'1','create_time'=>'2023-06-08 16:51:16','update_time'=>'2023-06-08 16:51:16','member_id'=>'278','order_id'=>'23','room_id'=>'571','labels'=>'服务热情','comment'=>'评论内容','star_num'=>'4','thumbs'=>NULL]);
        $this->insert('{{%diandi_place_comment}}',['id'=>'8','bloc_id'=>'38','store_id'=>'138','hotel_id'=>'1','create_time'=>'2023-06-08 16:51:16','update_time'=>'2023-06-08 16:51:16','member_id'=>'278','order_id'=>'2','room_id'=>'571','labels'=>'房东干净','comment'=>'评论内容','star_num'=>'3','thumbs'=>NULL]);
        $this->insert('{{%diandi_place_comment}}',['id'=>'9','bloc_id'=>'38','store_id'=>'138','hotel_id'=>'1','create_time'=>'2023-06-08 16:51:16','update_time'=>'2023-06-08 16:51:16','member_id'=>'278','order_id'=>'3','room_id'=>'571','labels'=>'标签名称','comment'=>'评论内容','star_num'=>'3','thumbs'=>NULL]);
        $this->insert('{{%diandi_place_comment}}',['id'=>'10','bloc_id'=>'38','store_id'=>'138','hotel_id'=>'1','create_time'=>'2023-06-08 16:51:16','update_time'=>'2023-06-08 16:51:16','member_id'=>'278','order_id'=>'3','room_id'=>'571','labels'=>'标签名称','comment'=>'评论内容','star_num'=>'3','thumbs'=>NULL]);
        $this->insert('{{%diandi_place_comment}}',['id'=>'11','bloc_id'=>'38','store_id'=>'138','hotel_id'=>'1','create_time'=>'2023-06-08 16:51:16','update_time'=>'2023-06-08 16:51:16','member_id'=>'278','order_id'=>'3','room_id'=>'571','labels'=>'标签名称','comment'=>'评论内容','star_num'=>'3','thumbs'=>NULL]);
        $this->insert('{{%diandi_place_comment}}',['id'=>'12','bloc_id'=>'38','store_id'=>'138','hotel_id'=>'1','create_time'=>'2023-06-08 16:51:16','update_time'=>'2023-06-08 16:51:16','member_id'=>'278','order_id'=>'3','room_id'=>'571','labels'=>'标签名称','comment'=>'评论内容','star_num'=>'3','thumbs'=>NULL]);
        $this->insert('{{%diandi_place_comment}}',['id'=>'13','bloc_id'=>'38','store_id'=>'138','hotel_id'=>'1','create_time'=>'2023-06-08 16:51:16','update_time'=>'2023-06-08 16:51:16','member_id'=>'278','order_id'=>'3','room_id'=>'0','labels'=>'标签名称','comment'=>'评论内容','star_num'=>'3','thumbs'=>NULL]);
        $this->insert('{{%diandi_place_comment}}',['id'=>'14','bloc_id'=>'38','store_id'=>'138','hotel_id'=>'1','create_time'=>'2023-06-08 16:51:16','update_time'=>'2023-06-08 16:51:16','member_id'=>'278','order_id'=>'3','room_id'=>'0','labels'=>'房东干净','comment'=>'评论内容','star_num'=>'3','thumbs'=>NULL]);
        $this->insert('{{%diandi_place_comment}}',['id'=>'15','bloc_id'=>'38','store_id'=>'138','hotel_id'=>'1','create_time'=>'2023-06-08 16:51:16','update_time'=>'2023-06-08 16:51:16','member_id'=>'278','order_id'=>'3','room_id'=>'0','labels'=>'室内宽敞','comment'=>'评论内容','star_num'=>'3','thumbs'=>NULL]);
        $this->insert('{{%diandi_place_comment}}',['id'=>'16','bloc_id'=>'38','store_id'=>'138','hotel_id'=>'1','create_time'=>'2023-06-08 16:51:16','update_time'=>'2023-06-08 16:51:16','member_id'=>'278','order_id'=>'3','room_id'=>'0','labels'=>'标签名称','comment'=>'评论内容','star_num'=>'3','thumbs'=>NULL]);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_comment}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
