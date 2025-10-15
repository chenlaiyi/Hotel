<?php

use yii\db\Migration;

class m240417_014729_bloc_open_wechat_user extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%bloc_open_wechat_user}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(10) unsigned NULL",
            'user_id' => "int(11) NULL DEFAULT '0' COMMENT '用户ID'",
            'openid' => "varchar(100) NULL COMMENT 'openid'",
            'union_id' => "varchar(100) NULL",
            'nick_name' => "varchar(50) NULL",
            'update_time' => "datetime NULL",
            'create_time' => "datetime NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='公众号授权参数配置'");
        
        /* 索引设置 */
        $this->createIndex('user_id','{{%bloc_open_wechat_user}}','bloc_id, openid',1);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%bloc_open_wechat_user}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

