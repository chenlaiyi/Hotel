<?php
use yii\db\Migration;
class m231213_063915_diandi_place_landlord extends Migration
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
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='房东'");
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_landlord}}','store_id',0);
        /* 表数据 */
        $this->insert('{{%diandi_place_landlord}}',['id'=>'1','bloc_id'=>'91','store_id'=>'153','member_id'=>'1','user_id'=>'72','realname'=>'房东','language'=>'1','desc'=>'房东简介说明','content'=>'房东详细介绍','mobile'=>'17778984690','status'=>'0','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>'协议']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'8','bloc_id'=>'91','store_id'=>'153','member_id'=>'270','user_id'=>'72','realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'0','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>'<p>协议1</p>']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'9','bloc_id'=>'91','store_id'=>'153','member_id'=>'293','user_id'=>'72','realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'0','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>'<p>叶子莫玄羽</p>']);
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
