<?php
use yii\db\Migration;
class m240108_234139_diandi_place_order_member extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_order_member}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL DEFAULT '0' COMMENT '公司ID'",
            'store_id' => "int(11) NULL DEFAULT '0' COMMENT '商户ID'",
            'order_id' => "int(11) NULL",
            'hotel_id' => "int(11) NULL",
            'room_id' => "int(11) NULL COMMENT '房间ID'",
            'member_id' => "int(11) NULL COMMENT '会员ID'",
            'face_img' => "varchar(255) NULL COMMENT '脸部照片'",
            'realname' => "varchar(255) NULL DEFAULT '' COMMENT '真实姓名'",
            'mobile' => "varchar(255) NULL DEFAULT '' COMMENT '手机号'",
            'status' => "int(11) NULL DEFAULT '0' COMMENT '用户状态'",
            'icard_code' => "varchar(255) NULL COMMENT '身份证号码'",
            'icard_front' => "varchar(200) NULL DEFAULT '' COMMENT '身份证正面'",
            'icard_back' => "varchar(200) NULL DEFAULT '' COMMENT '身份证反面'",
            'is_vip' => "int(11) NULL DEFAULT '0' COMMENT '是否是会员'",
            'check_in' => "int(11) NULL COMMENT '校验结果'",
            'personGuid' => "varchar(255) NULL",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'start_time' => "datetime NULL COMMENT '开始时间'",
            'end_time' => "datetime NULL COMMENT '结束时间'",
            'allow_add_key' => "tinyint(1) NULL COMMENT '允许添加钥匙 0|1'",
            'notice' => "tinyint(1) NULL COMMENT '短信通知 0|1'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8");
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_order_member}}','store_id',0);
        /* 表数据 */
        $this->insert('{{%diandi_place_order_member}}',['id'=>'1','bloc_id'=>'91','store_id'=>'153','order_id'=>'1','hotel_id'=>NULL,'room_id'=>NULL,'member_id'=>'0','face_img'=>NULL,'realname'=>'晚上','mobile'=>'17778984990','status'=>'1','icard_code'=>'','icard_front'=>'','icard_back'=>'','is_vip'=>'0','check_in'=>'0','personGuid'=>NULL,'create_time'=>'2023-10-30 19:51:58','update_time'=>'2023-10-30 19:51:58','start_time'=>'2023-10-30 19:51:48','end_time'=>'2023-11-01 19:51:48','allow_add_key'=>NULL,'notice'=>'0']);
        $this->insert('{{%diandi_place_order_member}}',['id'=>'2','bloc_id'=>'91','store_id'=>'153','order_id'=>'2','hotel_id'=>NULL,'room_id'=>NULL,'member_id'=>'0','face_img'=>NULL,'realname'=>'王春生','mobile'=>'17778984690','status'=>'1','icard_code'=>'','icard_front'=>'','icard_back'=>'','is_vip'=>'0','check_in'=>'0','personGuid'=>NULL,'create_time'=>'2023-10-30 19:53:29','update_time'=>'2023-10-30 19:53:29','start_time'=>'2023-10-30 19:53:22','end_time'=>'2023-10-30 21:53:22','allow_add_key'=>NULL,'notice'=>'0']);
        $this->insert('{{%diandi_place_order_member}}',['id'=>'3','bloc_id'=>'91','store_id'=>'153','order_id'=>'3','hotel_id'=>NULL,'room_id'=>NULL,'member_id'=>'0','face_img'=>NULL,'realname'=>'王春生','mobile'=>'17778984690','status'=>'1','icard_code'=>'','icard_front'=>'','icard_back'=>'','is_vip'=>'0','check_in'=>'0','personGuid'=>NULL,'create_time'=>'2023-10-30 19:54:59','update_time'=>'2023-10-30 19:54:59','start_time'=>'2023-10-30 19:54:50','end_time'=>'2023-11-04 19:54:50','allow_add_key'=>NULL,'notice'=>'0']);
        $this->insert('{{%diandi_place_order_member}}',['id'=>'4','bloc_id'=>'91','store_id'=>'153','order_id'=>'4','hotel_id'=>NULL,'room_id'=>NULL,'member_id'=>'0','face_img'=>NULL,'realname'=>'23','mobile'=>'23','status'=>'1','icard_code'=>'','icard_front'=>'','icard_back'=>'','is_vip'=>'0','check_in'=>'0','personGuid'=>NULL,'create_time'=>'2023-10-30 19:57:29','update_time'=>'2023-10-30 19:57:29','start_time'=>'2023-10-30 19:57:25','end_time'=>'2023-11-20 19:57:25','allow_add_key'=>NULL,'notice'=>'0']);
        $this->insert('{{%diandi_place_order_member}}',['id'=>'5','bloc_id'=>'91','store_id'=>'153','order_id'=>NULL,'hotel_id'=>'109','room_id'=>'9','member_id'=>'0','face_img'=>NULL,'realname'=>'王春生','mobile'=>'17778984690','status'=>'1','icard_code'=>'','icard_front'=>'','icard_back'=>'','is_vip'=>'0','check_in'=>'0','personGuid'=>NULL,'create_time'=>'2023-12-26 14:37:37','update_time'=>'2023-12-26 14:37:37','start_time'=>'2023-12-26 14:36:17','end_time'=>'2023-12-27 14:36:23','allow_add_key'=>NULL,'notice'=>'0']);
        $this->insert('{{%diandi_place_order_member}}',['id'=>'6','bloc_id'=>'91','store_id'=>'153','order_id'=>NULL,'hotel_id'=>'109','room_id'=>'9','member_id'=>'0','face_img'=>NULL,'realname'=>'王春生','mobile'=>'17778984690','status'=>'1','icard_code'=>'','icard_front'=>'','icard_back'=>'','is_vip'=>'0','check_in'=>'0','personGuid'=>NULL,'create_time'=>'2023-12-28 10:10:12','update_time'=>'2023-12-28 10:10:12','start_time'=>'2023-12-28 10:05:00','end_time'=>'2023-12-29 10:05:00','allow_add_key'=>NULL,'notice'=>'0']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_order_member}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
