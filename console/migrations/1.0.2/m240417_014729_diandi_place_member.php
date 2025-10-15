<?php

use yii\db\Migration;

class m240417_014729_diandi_place_member extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%diandi_place_member}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(11) NULL DEFAULT '0' COMMENT '公司ID'",
            'store_id' => "int(11) NULL DEFAULT '0' COMMENT '商户ID'",
            'member_id' => "int(11) NULL COMMENT '会员ID'",
            'avatar' => "varchar(255) NULL",
            'face_img' => "varchar(255) NULL COMMENT '脸部照片'",
            'realname' => "varchar(255) NULL DEFAULT '' COMMENT '真实姓名'",
            'mobile' => "varchar(255) NULL DEFAULT '' COMMENT '手机号'",
            'status' => "int(11) NULL DEFAULT '0' COMMENT '用户状态'",
            'icard_code' => "varchar(255) NULL COMMENT '身份证号码'",
            'icard_front' => "varchar(200) NULL DEFAULT '' COMMENT '身份证正面'",
            'icard_back' => "varchar(200) NULL DEFAULT '' COMMENT '身份证反面'",
            'is_vip' => "int(11) NULL DEFAULT '0' COMMENT '是否是会员'",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'icard_auth_status' => "tinyint(1) NOT NULL DEFAULT '0' COMMENT '0审核中 1认证成功 2认证失败'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");
        
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_member}}','store_id',0);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_member}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

