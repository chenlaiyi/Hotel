<?php

use yii\db\Migration;

class m240417_014729_officialaccount_wechat_token extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%officialaccount_wechat_token}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '粉丝id'",
            'bloc_id' => "int(11) NULL",
            'store_id' => "int(11) NULL",
            'update_time' => "datetime NULL COMMENT '更新时间'",
            'create_time' => "datetime NULL",
            'authorizer_appid' => "varchar(255) NULL",
            'authorizer_access_token' => "varchar(255) NULL COMMENT 'AccessToken(访问令牌)'",
            'expires_in' => "varchar(255) NULL COMMENT '微信服务Token(令牌)'",
            'authorizer_refresh_token' => "varchar(255) NULL COMMENT '消息加密秘钥EncodingAesKey'",
            'func_info' => "varchar(255) NULL COMMENT 'API地址'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='公众号粉丝表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%officialaccount_wechat_token}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

