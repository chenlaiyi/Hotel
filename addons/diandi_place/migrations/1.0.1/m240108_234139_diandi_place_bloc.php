<?php
use yii\db\Migration;
class m240108_234139_diandi_place_bloc extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        /* 创建表 */
        $this->createTable('{{%diandi_place_bloc}}', [
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
            'is_inform' => "int(11) NULL DEFAULT '0' COMMENT '是否发送通知'",
            'auth_key' => "varchar(255) NULL COMMENT '密码加密字符串'",
            'passworkd' => "varchar(255) NULL COMMENT '登录密码'",
            'create_time' => "datetime NULL COMMENT '创建时间'",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM  DEFAULT CHARSET=utf8");
        /* 索引设置 */
        $this->createIndex('indx_weid','{{%diandi_place_bloc}}','store_id',0);
        /* 表数据 */
        $this->insert('{{%diandi_place_bloc}}',['id'=>'13','bloc_id'=>'38','store_id'=>'138','member_id'=>'133','avatar'=>NULL,'face_img'=>NULL,'realname'=>'星级用户','mobile'=>'','status'=>'0','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','is_inform'=>'0','auth_key'=>NULL,'passworkd'=>NULL,'create_time'=>'2023-04-23 14:11:03','update_time'=>'2023-04-23 14:11:03']);
        $this->insert('{{%diandi_place_bloc}}',['id'=>'14','bloc_id'=>'38','store_id'=>'138','member_id'=>'136','avatar'=>NULL,'face_img'=>NULL,'realname'=>'星级用户','mobile'=>'','status'=>'0','icard_code'=>NULL,'icard_front'=>'','icard_back'=>'','is_inform'=>'0','auth_key'=>NULL,'passworkd'=>NULL,'create_time'=>'2023-04-23 14:33:35','update_time'=>'2023-04-23 14:33:35']);
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }
    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%diandi_place_bloc}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
