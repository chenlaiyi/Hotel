<?php
use yii\db\Migration;
class m240108_234139_diandi_place_landlord_type extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_landlord_type}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL DEFAULT '0' COMMENT '公司ID'",
            'store_id' => "int(11) NULL DEFAULT '0' COMMENT '商户ID'",
            'member_id' => "int(11) NULL COMMENT '会员ID'",
            'type_id' => "int(11) NULL COMMENT '业务类型'",
            'type_status' => "int(11) NULL COMMENT '是否开启'",
            'user_id' => "int(11) NULL COMMENT '管理员ID'",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='房东业务类型'");
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_landlord_type}}','store_id',0);
        /* 表数据 */
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'309','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'24','type_status'=>'1','user_id'=>'11','create_time'=>'2023-12-15 21:11:00','update_time'=>'2023-12-15 21:11:00']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'308','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'23','type_status'=>'0','user_id'=>'11','create_time'=>'2023-12-15 21:11:00','update_time'=>'2023-12-15 21:11:00']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'307','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'22','type_status'=>'0','user_id'=>'11','create_time'=>'2023-12-15 21:11:00','update_time'=>'2023-12-15 21:11:00']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'104','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'23','type_status'=>'1','user_id'=>'0','create_time'=>'2023-11-26 00:58:53','update_time'=>'2023-11-26 00:58:53']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'103','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'24','type_status'=>'0','user_id'=>'0','create_time'=>'2023-11-26 00:58:53','update_time'=>'2023-11-26 00:58:53']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'102','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'22','type_status'=>'0','user_id'=>'0','create_time'=>'2023-11-26 00:58:53','update_time'=>'2023-11-26 00:58:53']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'101','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'18','type_status'=>'0','user_id'=>'0','create_time'=>'2023-11-26 00:58:53','update_time'=>'2023-11-26 00:58:53']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'100','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'15','type_status'=>'0','user_id'=>'0','create_time'=>'2023-11-26 00:58:53','update_time'=>'2023-11-26 00:58:53']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'306','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'18','type_status'=>'0','user_id'=>'11','create_time'=>'2023-12-15 21:11:00','update_time'=>'2023-12-15 21:11:00']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'305','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'15','type_status'=>'0','user_id'=>'11','create_time'=>'2023-12-15 21:11:00','update_time'=>'2023-12-15 21:11:00']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'310','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'15','type_status'=>'0','user_id'=>'72','create_time'=>'2023-12-25 15:25:21','update_time'=>'2023-12-25 15:25:21']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'311','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'18','type_status'=>'0','user_id'=>'72','create_time'=>'2023-12-25 15:25:21','update_time'=>'2023-12-25 15:25:21']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'312','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'22','type_status'=>'0','user_id'=>'72','create_time'=>'2023-12-25 15:25:21','update_time'=>'2023-12-25 15:25:21']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'313','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'24','type_status'=>'0','user_id'=>'72','create_time'=>'2023-12-25 15:25:21','update_time'=>'2023-12-25 15:25:21']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'314','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'23','type_status'=>'1','user_id'=>'72','create_time'=>'2023-12-25 15:25:21','update_time'=>'2023-12-25 15:25:21']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'369','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'24','type_status'=>'1','user_id'=>'83','create_time'=>'2023-12-29 12:58:54','update_time'=>'2023-12-29 12:58:54']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'368','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'23','type_status'=>'1','user_id'=>'83','create_time'=>'2023-12-29 12:58:54','update_time'=>'2023-12-29 12:58:54']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'367','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'22','type_status'=>'1','user_id'=>'83','create_time'=>'2023-12-29 12:58:54','update_time'=>'2023-12-29 12:58:54']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'366','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'18','type_status'=>'1','user_id'=>'83','create_time'=>'2023-12-29 12:58:54','update_time'=>'2023-12-29 12:58:54']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'365','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','type_id'=>'15','type_status'=>'1','user_id'=>'83','create_time'=>'2023-12-29 12:58:54','update_time'=>'2023-12-29 12:58:54']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'364','bloc_id'=>'91','store_id'=>'153','member_id'=>'312','type_id'=>'24','type_status'=>'1','user_id'=>'0','create_time'=>'2023-12-29 12:33:34','update_time'=>'2023-12-29 12:33:34']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'363','bloc_id'=>'91','store_id'=>'153','member_id'=>'312','type_id'=>'23','type_status'=>'1','user_id'=>'0','create_time'=>'2023-12-29 12:33:34','update_time'=>'2023-12-29 12:33:34']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'362','bloc_id'=>'91','store_id'=>'153','member_id'=>'312','type_id'=>'22','type_status'=>'1','user_id'=>'0','create_time'=>'2023-12-29 12:33:34','update_time'=>'2023-12-29 12:33:34']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'361','bloc_id'=>'91','store_id'=>'153','member_id'=>'312','type_id'=>'18','type_status'=>'1','user_id'=>'0','create_time'=>'2023-12-29 12:33:34','update_time'=>'2023-12-29 12:33:34']);
        $this->insert('{{%diandi_place_landlord_type}}',['id'=>'360','bloc_id'=>'91','store_id'=>'153','member_id'=>'312','type_id'=>'15','type_status'=>'1','user_id'=>'0','create_time'=>'2023-12-29 12:33:34','update_time'=>'2023-12-29 12:33:34']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_landlord_type}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
