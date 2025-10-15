<?php

use yii\db\Migration;

class m240417_014729_officialaccount_msg_template_list extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%officialaccount_msg_template_list}}', [
            'id' => "bigint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id'",
            'bloc_id' => "int(11) NULL",
            'template_id' => "varchar(100) NOT NULL COMMENT '公众号模板ID'",
            'url' => "varchar(255) NULL COMMENT '链接地址'",
            'data' => "text NULL COMMENT '模板内容'",
            'miniprogram_appid' => "varchar(100) NULL COMMENT '小程序appid'",
            'miniprogram_pagepath' => "varchar(100) NULL COMMENT '小程序页面地址'",
            'status' => "tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有效'",
            'create_time' => "varchar(30) NULL",
            'update_time' => "varchar(30) NULL",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='消息模板'");
        
        /* 索引设置 */
        $this->createIndex('idx_status','{{%officialaccount_msg_template_list}}','status',0);
        
        
        /* 表数据 */
        $this->insert('{{%officialaccount_msg_template_list}}',['id'=>'9','bloc_id'=>'0','template_id'=>'r1Md6RPAMXvJDWBevoTgirsnM_n6ibjpJbh3X0-zEeo','url'=>'https://www.dandicloud.cn/','data'=>'[{\"name\":\"官网入口\",\"field\":\"character_string2\",\"value\":\"https:\\/\\/www.dandicloud.cn\\/\",\"color\":\"#00FFBB\"},{\"name\":\"登录时间\",\"field\":\"time1\",\"value\":\"2024-03-18\",\"color\":\"#931919\"}]','miniprogram_appid'=>'wx7b07c981b0a853a5','miniprogram_pagepath'=>'pages/index/index','status'=>'1','create_time'=>'2024-03-18 23:27:31','update_time'=>'2024-03-19 08:29:03']);
        $this->insert('{{%officialaccount_msg_template_list}}',['id'=>'10','bloc_id'=>'0','template_id'=>'DfaNjZQ_8JFg5YOBaoSiSiJa2wktY8HPOm_uTr1FC0U','url'=>'https://www.dandicloud.cn/store','data'=>'[{\"name\":\"试用版本\",\"field\":\"phrase3\",\"value\":\"体验版\",\"color\":\"#FF0000\"},{\"name\":\"到期时间\",\"field\":\"time4\",\"value\":\"2024-04-19\",\"color\":\"\"}]','miniprogram_appid'=>NULL,'miniprogram_pagepath'=>NULL,'status'=>'1','create_time'=>'2024-03-19 09:55:03','update_time'=>'2024-03-19 09:55:03']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%officialaccount_msg_template_list}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

