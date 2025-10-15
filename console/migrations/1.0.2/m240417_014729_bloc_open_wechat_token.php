<?php

use yii\db\Migration;

class m240417_014729_bloc_open_wechat_token extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%bloc_open_wechat_token}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT",
            'bloc_id' => "int(10) unsigned NULL",
            'authorizer_appid' => "varchar(255) NULL COMMENT 'app_id'",
            'authorizer_access_token' => "varchar(255) NULL",
            'expires_in' => "int(11) NULL",
            'authorizer_refresh_token' => "varchar(255) NULL",
            'func_info' => "text NULL",
            'service_type_id' => "int(11) NULL",
            'verify_type_id' => "int(11) NULL",
            'nick_name' => "varchar(50) NULL",
            'qrcode_url' => "varchar(255) NULL",
            'update_time' => "datetime NULL",
            'create_time' => "datetime NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='公众号授权参数配置'");
        
        /* 索引设置 */
        $this->createIndex('bloc_id','{{%bloc_open_wechat_token}}','bloc_id, service_type_id, verify_type_id',1);
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%bloc_open_wechat_token}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

