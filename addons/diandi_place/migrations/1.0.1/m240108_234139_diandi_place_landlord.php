<?php
use yii\db\Migration;
class m240108_234139_diandi_place_landlord extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_landlord}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL DEFAULT '0' COMMENT '公司ID'",
            'store_id' => "int(11) NULL DEFAULT '0' COMMENT '商户ID'",
            'member_id' => "int(11) NULL COMMENT '会员ID'",
            'user_id' => "int(11) NULL",
            'realname' => "varchar(255) NULL DEFAULT '' COMMENT '真实姓名'",
            'language' => "int(11) NULL COMMENT '房东默认语言'",
            'desc' => "varchar(255) NULL DEFAULT '' COMMENT '房东简介'",
            'content' => "text NULL COMMENT '房东描述'",
            'mobile' => "varchar(255) NULL DEFAULT '' COMMENT '手机号'",
            'status' => "int(11) NULL DEFAULT '0' COMMENT '用户状态'",
            'icard_code' => "varchar(255) NULL COMMENT '身份证号码'",
            'icard_front' => "varchar(200) NULL DEFAULT '' COMMENT '身份证正面'",
            'icard_back' => "varchar(200) NULL DEFAULT '' COMMENT '身份证反面'",
            'contract' => "text NULL COMMENT '长租协议'",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='房东'");
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_landlord}}','store_id',0);
        /* 表数据 */
        $this->insert('{{%diandi_place_landlord}}',['id'=>'1','bloc_id'=>'91','store_id'=>'153','member_id'=>'305','user_id'=>'83','realname'=>'房东','language'=>'1','desc'=>'房东简介说明','content'=>'房东详细介绍','mobile'=>'17778984690','status'=>'0','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>'协议','create_time'=>NULL,'update_time'=>NULL]);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'8','bloc_id'=>'91','store_id'=>'153','member_id'=>'312','user_id'=>'83','realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>'<p>协议1</p>','create_time'=>NULL,'update_time'=>NULL]);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'10','bloc_id'=>'91','store_id'=>'153','member_id'=>'544','user_id'=>NULL,'realname'=>'王春生','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'3','icard_code'=>'610629198811084216','icard_front'=>'https://www.dandicloud.cn/attachment/202401/04/1b22febe-4836-387d-8ff7-8e14eb83a9f3.png','icard_back'=>'https://www.dandicloud.cn/attachment/202401/04/c7e874a5-42bb-33b2-897d-284d02308346.png','contract'=>NULL,'create_time'=>'2024-01-04 08:10:06','update_time'=>'2024-01-04 11:20:36']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'11','bloc_id'=>'91','store_id'=>'153','member_id'=>'545','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'create_time'=>'2024-01-05 11:29:04','update_time'=>'2024-01-05 11:29:04']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'12','bloc_id'=>'91','store_id'=>'153','member_id'=>'545','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'create_time'=>'2024-01-05 11:29:04','update_time'=>'2024-01-05 11:29:04']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'13','bloc_id'=>'91','store_id'=>'153','member_id'=>'545','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'create_time'=>'2024-01-05 11:29:04','update_time'=>'2024-01-05 11:29:04']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'14','bloc_id'=>'91','store_id'=>'153','member_id'=>'546','user_id'=>NULL,'realname'=>'墨白水','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'3','icard_code'=>'3504','icard_front'=>'https://www.dandicloud.cn/attachment/202401/05/21aae16b-15c4-33b5-885f-a6faa36315e1.jpg','icard_back'=>'https://www.dandicloud.cn/attachment/202401/05/585f3d2d-54a1-326c-ad47-46162be261cf.jpg','contract'=>NULL,'create_time'=>'2024-01-05 11:37:07','update_time'=>'2024-01-05 11:37:40']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_landlord}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
