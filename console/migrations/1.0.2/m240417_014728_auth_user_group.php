<?php

use yii\db\Migration;

class m240417_014728_auth_user_group extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%auth_user_group}}', [
            'id' => "int(11) unsigned NOT NULL AUTO_INCREMENT",
            'item_id' => "int(11) NULL",
            'name' => "varchar(64) NOT NULL COMMENT '用户组名称'",
            'module_name' => "varchar(255) NULL",
            'type' => "smallint(6) NOT NULL DEFAULT '0' COMMENT '用户组类型0系统1商户'",
            'is_sys' => "smallint(6) NULL COMMENT '0否1是'",
            'description' => "text NULL COMMENT '用户组名称'",
            'bloc_id' => "int(11) NULL COMMENT '公司'",
            'store_id' => "int(11) NULL COMMENT '商户'",
            'is_default' => "int(11) NULL DEFAULT '0' COMMENT '是否默认'",
            'created_at' => "int(11) NULL",
            'updated_at' => "int(11) NULL",
            'PRIMARY KEY (`id`,`name`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='后台用户组'");
        
        /* 索引设置 */
        $this->createIndex('type','{{%auth_user_group}}','type',0);
        $this->createIndex('name','{{%auth_user_group}}','name',0);
        
        
        /* 表数据 */
        $this->insert('{{%auth_user_group}}',['id'=>'551','item_id'=>'14401','name'=>'基础权限组','module_name'=>'sys','type'=>'0','is_sys'=>'1','description'=>'','bloc_id'=>NULL,'store_id'=>NULL,'is_default'=>'0','created_at'=>'1588976797','updated_at'=>'1588837647']);
        $this->insert('{{%auth_user_group}}',['id'=>'552','item_id'=>'9733','name'=>'总管理员','module_name'=>'sys','type'=>'0','is_sys'=>'1','description'=>'','bloc_id'=>NULL,'store_id'=>NULL,'is_default'=>'0','created_at'=>'1588976797','updated_at'=>'1621841609']);
        $this->insert('{{%auth_user_group}}',['id'=>'553','item_id'=>'17246','name'=>'酒店用户组','module_name'=>NULL,'type'=>'0','is_sys'=>'1','description'=>NULL,'bloc_id'=>'0','store_id'=>'0','is_default'=>'0','created_at'=>'1706532871','updated_at'=>'1706532871']);
        $this->insert('{{%auth_user_group}}',['id'=>'554','item_id'=>'17247','name'=>'茶室用户组','module_name'=>NULL,'type'=>'0','is_sys'=>'1','description'=>'','bloc_id'=>'0','store_id'=>'0','is_default'=>'0','created_at'=>'1706532896','updated_at'=>'1706532896']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%auth_user_group}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

