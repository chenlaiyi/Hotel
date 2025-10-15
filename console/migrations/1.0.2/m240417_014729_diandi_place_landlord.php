<?php

use yii\db\Migration;

class m240417_014729_diandi_place_landlord extends Migration
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
            'is_admin' => "int(11) NULL DEFAULT '0' COMMENT '是否是总账户'",
            'create_time' => "datetime NULL",
            'update_time' => "datetime NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='房东'");
        
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_landlord}}','store_id',0);
        
        
        /* 表数据 */
        $this->insert('{{%diandi_place_landlord}}',['id'=>'1','bloc_id'=>'91','store_id'=>'153','member_id'=>'1','user_id'=>'72','realname'=>'房东','language'=>'1','desc'=>'房东简介说明','content'=>'房东详细介绍','mobile'=>'17778984690','status'=>'0','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>'协议','is_admin'=>'0','create_time'=>'2024-02-01 00:12:38','update_time'=>'2024-02-01 00:12:38']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'8','bloc_id'=>'91','store_id'=>'153','member_id'=>'270','user_id'=>'72','realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'0','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>'<p>协议1</p>','is_admin'=>'0','create_time'=>'2024-02-01 00:12:38','update_time'=>'2024-02-01 00:12:38']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'9','bloc_id'=>'91','store_id'=>'153','member_id'=>'293','user_id'=>'72','realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'0','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>'<p>叶子莫玄羽</p>','is_admin'=>'0','create_time'=>'2024-02-01 00:12:38','update_time'=>'2024-02-01 00:12:38']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'10','bloc_id'=>'98','store_id'=>'296','member_id'=>'544','user_id'=>'83','realname'=>'王春生','language'=>NULL,'desc'=>'','content'=>'','mobile'=>'','status'=>'3','icard_code'=>'610629198811084216','icard_front'=>'202401/31/cc906149-6c82-3e76-8dd7-1a9f47ff7b99.jpg','icard_back'=>'/202401/15/f6910f44-3c9f-37eb-b773-fcc2ee47b9d0.jpg','contract'=>'','is_admin'=>'1','create_time'=>'2024-02-01 00:12:38','update_time'=>'2024-02-02 13:12:31']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'13','bloc_id'=>'106','store_id'=>'299','member_id'=>'548','user_id'=>'119','realname'=>'潘金友','language'=>NULL,'desc'=>'','content'=>'','mobile'=>'12','status'=>'3','icard_code'=>'320123197705040659','icard_front'=>'/202401/31/decd1373-3bc9-331e-903b-d537f75fee25.jpg','icard_back'=>'/202401/31/ad51a1e0-8813-3ada-86ec-0cc448293afa.jpg','contract'=>'','is_admin'=>'1','create_time'=>'2024-02-01 00:12:38','update_time'=>'2024-02-06 15:19:05']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'16','bloc_id'=>'91','store_id'=>'153','member_id'=>'549','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-02-01 00:12:38','update_time'=>'2024-02-01 00:12:38']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'17','bloc_id'=>'91','store_id'=>'153','member_id'=>'550','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-02-01 00:12:38','update_time'=>'2024-02-01 00:12:38']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'18','bloc_id'=>'91','store_id'=>'153','member_id'=>'550','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-02-01 00:12:38','update_time'=>'2024-02-01 00:12:38']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'19','bloc_id'=>'91','store_id'=>'153','member_id'=>'556','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-02-01 00:12:38','update_time'=>'2024-02-01 00:12:38']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'20','bloc_id'=>'91','store_id'=>'153','member_id'=>'556','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-02-01 00:12:38','update_time'=>'2024-02-01 00:12:38']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'21','bloc_id'=>'91','store_id'=>'153','member_id'=>'556','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-02-01 00:12:38','update_time'=>'2024-02-01 00:12:38']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'22','bloc_id'=>'91','store_id'=>'153','member_id'=>'556','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-02-01 00:12:38','update_time'=>'2024-02-01 00:12:38']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'23','bloc_id'=>'91','store_id'=>'153','member_id'=>'557','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-02-01 00:12:38','update_time'=>'2024-02-01 00:12:38']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'24','bloc_id'=>'91','store_id'=>'153','member_id'=>'557','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-02-01 00:12:38','update_time'=>'2024-02-01 00:12:38']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'25','bloc_id'=>'91','store_id'=>'153','member_id'=>'558','user_id'=>NULL,'realname'=>'于军','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'3','icard_code'=>'130104198405271519','icard_front'=>'https://www.dandicloud.cn/attachment/202402/08/f8c916bb-ba33-3f7e-888b-b61507a5a767.png','icard_back'=>'https://www.dandicloud.cn/attachment/202402/08/50e4dd7b-2e90-361d-94ae-e6325b434b9f.png','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-02-01 00:12:38','update_time'=>'2024-02-08 22:52:29']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'26','bloc_id'=>'91','store_id'=>'153','member_id'=>'558','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-02-01 00:12:38','update_time'=>'2024-02-01 00:12:38']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'27','bloc_id'=>'91','store_id'=>'153','member_id'=>'608','user_id'=>NULL,'realname'=>'杨金强','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'3','icard_code'=>'622727198406126810','icard_front'=>'https://www.dandicloud.cn/attachment/202402/21/0292dbfa-9d61-318b-8220-159bd6110ee6.jpg','icard_back'=>'https://www.dandicloud.cn/attachment/202402/21/a03d01c4-aa6a-3432-a95b-40a21128e1f7.jpg','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-02-21 19:19:12','update_time'=>'2024-02-21 19:20:57']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'28','bloc_id'=>'91','store_id'=>'153','member_id'=>'611','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-02-21 21:59:15','update_time'=>'2024-02-21 21:59:15']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'29','bloc_id'=>'91','store_id'=>'153','member_id'=>'620','user_id'=>NULL,'realname'=>'Elvis','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'3','icard_code'=>'970208385251','icard_front'=>'https://www.dandicloud.cn/attachment/202402/24/d40a14f7-44c9-3e48-a85f-17faab15c6ef.jpg','icard_back'=>'https://www.dandicloud.cn/attachment/202402/24/6f321205-950b-32b2-b09f-6a5d60d3cc81.jpg','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-02-24 14:09:49','update_time'=>'2024-02-24 14:12:00']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'30','bloc_id'=>'91','store_id'=>'153','member_id'=>'624','user_id'=>NULL,'realname'=>'Lai','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'3','icard_code'=>'1235677','icard_front'=>'https://www.dandicloud.cn/attachment/202402/27/12705993-22ea-326e-9827-4e02a3da498d.jpg','icard_back'=>'','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-02-27 11:36:27','update_time'=>'2024-02-27 14:56:23']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'31','bloc_id'=>'91','store_id'=>'153','member_id'=>'640','user_id'=>NULL,'realname'=>'李存虎','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'3','icard_code'=>'642222199608093235','icard_front'=>'https://www.dandicloud.cn/attachment/202403/20/ebc104fd-2f8b-30ef-b550-a474cebe7183.jpg','icard_back'=>'https://www.dandicloud.cn/attachment/202403/20/79e446e3-24ec-3f34-ba13-56f96a216da4.jpg','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-03-19 23:57:56','update_time'=>'2024-03-20 00:00:57']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'32','bloc_id'=>'98','store_id'=>'296','member_id'=>'310','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-03-28 10:46:30','update_time'=>'2024-03-28 10:46:30']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'33','bloc_id'=>'91','store_id'=>'153','member_id'=>'656','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-03-28 23:23:31','update_time'=>'2024-03-28 23:23:31']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'34','bloc_id'=>'91','store_id'=>'153','member_id'=>'660','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-04-08 14:30:00','update_time'=>'2024-04-08 14:30:00']);
        $this->insert('{{%diandi_place_landlord}}',['id'=>'35','bloc_id'=>'91','store_id'=>'153','member_id'=>'662','user_id'=>NULL,'realname'=>'','language'=>NULL,'desc'=>'','content'=>NULL,'mobile'=>'','status'=>'1','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','contract'=>NULL,'is_admin'=>'0','create_time'=>'2024-04-10 09:11:08','update_time'=>'2024-04-10 09:11:08']);
        
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

